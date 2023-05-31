<?php

namespace App\Http\Controllers\api\v1;

use App\Classes\Telesign\TelesignClient;
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

        }catch (TelesignClientCouldNotSendVerifySMSException $e) { return response()->json(['message' => $e->getMessage()], 500);
            // You can use a different logic to manage telesign errors

            return response()->json(['message' => $e->getMessage()], 500);

        }catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

}
