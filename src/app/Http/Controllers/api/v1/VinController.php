<?php

namespace App\Http\Controllers\api\v1;

use App\Classes\Vindecoder\VindecoderClient;
use App\Exceptions\VindecoderClientCouldNotCheckVinException;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VinController extends Controller
{
    /**
     * Search a Vin number
     *
     * @param string $vin
     *
     * @return JsonResponse
     */
    public function search(string $vin): JsonResponse
    {
        try {
            $vin_decoder = new VindecoderClient();
//            $vin_info = $vin_decoder->checkVin($vin); // API limit exceeded

            $vin_info = [
                'success' => true,
                'is_salvage' => true,
                'info' => [
                    'images' => [],
                    'vehicle_title' => "NY - MV-907A SALVAGE CERTIFICATE",
                    'mileage' => "628 (ACTUAL)",
                    'primary_damage'  => "REAR END",
                    'secondary_damage' => "FR",
                    'loss_type' => "COLLISION"
                ]
            ];

            return response()->json($vin_info, 200);

        } catch (VindecoderClientCouldNotCheckVinException $e) {
            // You can use a different logic to manage vindecoder errors

            return response()->json(['message' => $e->getMessage()], 500);

        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
