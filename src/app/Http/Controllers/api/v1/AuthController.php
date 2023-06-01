<?php

namespace App\Http\Controllers\api\v1;

use App\Classes\Telesign\TelesignClient;
use App\Events\LoginFirstStepDone;
use App\Exceptions\TelesignClientCouldNotSendVerifySMSException;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterAuthRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Register a new user
     *
     * @param RegisterAuthRequest $request
     *
     * @return JsonResponse
     */
    public function register(RegisterAuthRequest $request): JsonResponse
    {
        try {
            $telesign_lient = new TelesignClient();

            $verify_code = $telesign_lient->sendVerifySMS($request->phone);

            User::create([
                'name'          => $request->name,
                'email'         => $request->email,
                'phone_number'  => $request->phone,
                'verify_code'   => $verify_code,
                'password'      => Hash::make($request->password),
            ]);

            return response()->json(['message' => 'User registered successfully'], 200);

        } catch (TelesignClientCouldNotSendVerifySMSException $e) { return response()->json(['message' => $e->getMessage()], 500);
            // You can use a different logic to manage telesign errors

            return response()->json(['message' => $e->getMessage()], 500);

        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Verify new user using the verify code sent to the client
     *
     * @param Request $request
     * @param int $user_id
     *
     * @return JsonResponse
     */
    public function verifyNewUser(Request $request, int $user_id): JsonResponse
    {
        try {
            $user = User::find($user_id);
            $verify_code = $request->verify_code;

            if (is_null($user)) {
                return response()->json(['message' => 'User not found'], 404);
            }

            if ($user->phone_number_verified_at) {
                return response()->json(['message' => 'User verified previously.'], 200);
            }

            if ($verify_code != $user->verify_code) {
                return response()->json(['message' => 'Code does not match.'], 404);
            }

            $user->phone_number_verified_at = now();
            $user->verify_code = null;
            $user->save();

            return response()->json(['message' => 'User verified successfully'], 200);

        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Login endpoint
     *
     * It generates a token to access to the second step endpoint and send a verify sms.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getLoginToken(Request $request): JsonResponse
    {
        try {
            $login = $request->validate([
                'email' => 'required|string',
                'password' => 'required|string',
            ]);

            if (!Auth::attempt($login)) {
                return response()->json(['message' => 'Your email or password are incorrect.'], 403);
            }

            $user = Auth::user();

            $access_token = $user->createToken('authToken', ['user:verify-login'])->accessToken;

            event(new LoginFirstStepDone($user));

            return response()->json(
                [
                    'name'          => $user->name,
                    'email'         => $user->email,
                    'phone_number'  => $user->phone_number,
                    'access_token'  => $access_token,
                ],
                200
            );

        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Verify new user using the verify code sent to the client
     *
     * @param Request $request
     * @param int $user_id
     *
     * @return JsonResponse
     */
    public function getAccessToken(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $verify_code = $request->verify_code;

            if ($verify_code != $user->verify_code) {
                return response()->json(['message' => 'Code does not match.'], 404);
            }

            if (!$user->phone_number_verified_at) {
                $user->phone_number_verified_at = now();
            }

            $user->verify_code = null;
            $user->save();

            $access_token = $user->createToken('authToken', ['user:all'])->accessToken;

            return response()->json(
                [
                    'name'          => $user->name,
                    'email'         => $user->email,
                    'phone_number'  => $user->phone_number,
                    'access_token'  => $access_token,
                ],
                200
            );

        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

}
