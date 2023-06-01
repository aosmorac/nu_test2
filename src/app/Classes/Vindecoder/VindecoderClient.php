<?php

namespace App\Classes\Vindecoder;

use App\Exceptions\VindecoderClientCouldNotCheckVinException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class VindecoderClient
{
    /**
     * Endpoint in the videcoder api to check salvage.
     */
    const CHECKER_ENDPOINT = 'https://vindecoder.p.rapidapi.com/salvage_check';

    public function checkVin(string $vin): array
    {
        try {
            $response = Http::withHeaders([
                    'X-RapidAPI-Key'  => Config::get('services.vindecoder.api_key'),
                    'X-RapidAPI-Host' => Config::get('services.vindecoder.host'),
                ])
                ->get(SELF::CHECKER_ENDPOINT . "?vin=$vin");

            $response_body = json_decode($response->body(), true);

            if ((int)$response->status() >= 300) {
                $error_message = $response_body['message'] . ' - ' .
                    (
                        isset($response_body['errors']) && isset($response_body['errors']['vin'][0]) ?
                        $response_body['errors']['vin'][0] :
                        ''
                    );

                throw new VindecoderClientCouldNotCheckVinException($error_message);
            }

            return $response_body;

        } catch (\Throwable $e) {
            throw new VindecoderClientCouldNotCheckVinException($e->getMessage());
        }
    }

}
