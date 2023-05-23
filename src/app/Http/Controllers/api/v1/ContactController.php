<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    /**
     * Store new contact message
     *
     * @param StoreContactRequest $request
     *
     * @return JsonResponse
     */
    public function store(StoreContactRequest $request): JsonResponse
    {
        try {
            $contact = Contact::create([
                'name'    => $request->name,
                'email'   => $request->email,
                'phone'   => $request->phone,
                'comment' => $request->comment ?? ''
            ]);

            return response()->json(
                [
                    'message' =>    $contact->name . ', ' .
                                    $contact->email . ', ' .
                                    $contact->phone .
                                    ($contact->comment ? (', ' . substr($contact->comment, 3)) : '') .
                                    ", contact saved successfully"
                ],
                200
            );

        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
