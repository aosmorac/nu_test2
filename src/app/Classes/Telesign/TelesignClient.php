<?php

namespace App\Classes\Telesign;

use App\Exceptions\TelesignClientCouldNotSendVerifySMSException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class TelesignClient
{
    /**
     * Endpoint in the telesign api to send verify sms.
     */
    const VERIFY_SMS_ENDPOINT = 'https://rest-ww.telesign.com/v1/verify/sms';

    /**
     * Send Verify SMS using Telesign API
     *
     * @param string $phone_number
     *
     * @return string
     */
    public function sendVerifySMS(string $phone_number): string
    {
        try {
            $code = random_int(1000, 9999);

            $response_data = [
                'phone_number'  => (int)$phone_number,
                'verify_code'   => $code
            ];

            $response = Http::withBasicAuth(
                    Config::get('services.telesign.customer_id'), Config::get('services.telesign.api_key')
                )
                ->asForm()
                ->post(SELF::VERIFY_SMS_ENDPOINT, $response_data);

            $response_body = json_decode($response->body());
            $response_status_code = (int)$response_body->status->code;

            if ($response_status_code > 300) {
                $error_message = $response_body->status->description . ' ' .
                    ($response_body->errors[0] ? $response_body->errors[0]->description : '');

                throw new TelesignClientCouldNotSendVerifySMSException($error_message);
            }

            return $code;

        } catch (\Throwable $e) {
            throw new TelesignClientCouldNotSendVerifySMSException($e->getMessage());
        }
    }

}
