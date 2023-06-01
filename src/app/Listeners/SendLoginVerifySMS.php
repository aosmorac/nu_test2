<?php

namespace App\Listeners;

use App\Classes\Telesign\TelesignClient;
use App\Events\LoginFirstStepDone;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendLoginVerifySMS implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\LoginFirstStepDone  $event
     * @return void
     */
    public function handle(LoginFirstStepDone $event)
    {
        $user = $event->user;

        try {
            $telesign_lient = new TelesignClient();
            $verify_code = $telesign_lient->sendVerifySMS($user->phone_number);

            $user->verify_code = $verify_code;
            $user->save();

            Log::info(
                'Verify SMS - Login second step sent, ' .
                'user ID: ' . $user->id . ', ' .
                'user email: ' . $user->email . ', ' .
                'user phone_number: ' . $user->phone_number . ', ' .
                'verify_code: ' . $verify_code
            );

        } catch (\Throwable $e) {
            Log::error(
                'Error Verify SMS - Login second step, ' .
                'user ID: ' . $user->id . ', ' .
                'user email: ' . $user->email . ', ' .
                'user phone_number: ' . $user->phone_number . ', ' .
                'error: ' . $e->getMessage()
            );
        }
    }
}
