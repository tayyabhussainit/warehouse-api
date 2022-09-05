<?php

namespace App\Http\Controllers\API;

use App\Services\GoogleMap;
use App\Services\PriceCalculator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PriceController extends Controller
{
    private $priceCalculator;
    private $googleMap;

    public function __construct(PriceCalculator $priceCalculator, GoogleMap $googleMap)
    {
        $this->priceCalculator = $priceCalculator;
        $this->googleMap = $googleMap;
    }
    public function getPrice(Request $request)
    {

        $fields = $request->validate([
            'address_1' => 'required|string',
            'address_2' => 'required|string',
            'start_date' => 'required',
            'end_date' => 'required',
            'pallets' => 'required|integer',
        ]);

        /**Mock Code Start */
       // $response = $this->priceCalculator->calculatePrice("752 mi", $request->input("pallets"), $request->input("start_date"), $request->input("end_date"));
       // return response(['data' => $response, 'code' => 200]);
        /**Mock Code End */
        $response = $this->googleMap->makeApiCall($request->input("address_1"), $request->input("address_2"));

        if (isset($response['rows'][0]['elements'][0]['distance']['text'])) {
            $miles = $response['rows'][0]['elements'][0]['distance']['text'];
            $origin = $response['origin_addresses'][0];
            $destination = $response['destination_addresses'][0];
            $response = $this->priceCalculator->calculatePrice($miles, $request->input("pallets"), $request->input("start_date"), $request->input("end_date"));
            $response['origin_addresses'] = $origin;
            $response['destination_addresses'] = $destination; 
            $response['miles'] = $miles; 
            return response(['data' => $response, 'code' => 200]);
        } else {
            return response(['msg' => 'Distance cannot be calculated, Please enter valid address', 'code' => 400]);
        }
    }
}
