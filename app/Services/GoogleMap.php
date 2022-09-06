<?php

namespace App\Services;

class GoogleMap
{
    public function makeApiCall($address_1, $address_2)
    {
        $address_1 = str_replace(' ', '%20', $address_1);
        $address_2 = str_replace(' ', '%20', $address_2);
        $api = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=" . $address_1 . "&destinations=" . $address_2 . "&language=en-EN&sensor=false&units=imperial&key=".env('g_key');

        $response = file_get_contents($api);
        $response = json_decode($response, true);

        return $response;
    }
}
