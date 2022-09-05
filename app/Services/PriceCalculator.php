<?php

namespace App\Services;

use Illuminate\Auth\EloquentUserProvider;

class PriceCalculator
{
    const CHARGES_PER_PALLET_UNDER_51_PALLET = 8;
    const CHARGES_PER_MILE_UNDER_51_PALLET = 2;

    const CHARGES_PER_PALLET_UNDER_1000_PALLET = 6;
    const CHARGES_PER_MILE_UNDER_1000_PALLET = 1;

    const CHARGES_PER_PALLET_OVER_1000_PALLET = 5;
    const CHARGES_PER_MILE_OVER_1000_PALLET = 0.5;

    const SERVICE_CHARGES = 0.5;

    public function calculatePrice($miles, $pallets, $startDate, $endDate)
    {

        $miles = str_replace(' mi', '', $miles);
        $to = \Carbon\Carbon::createFromFormat('Y-m-d', $startDate);
        $from = \Carbon\Carbon::createFromFormat('Y-m-d', $endDate);

        $days = $to->diffInDays($from);

        $totalPrice = $this->priceFormula($pallets, $days, $miles);

        $finalReport = $this->finalReport($totalPrice);
        return $finalReport;
    }

    private function priceFormula($pallets, $days, $miles)
    {
        $days = $days + 1;

        if ($pallets <= 50) {
            $chargesPerPallet = self::CHARGES_PER_PALLET_UNDER_51_PALLET;
            $chargesPerMile = self::CHARGES_PER_MILE_UNDER_51_PALLET;
        } elseif ($pallets <= 1000) {
            $chargesPerPallet = self::CHARGES_PER_PALLET_UNDER_1000_PALLET;
            $chargesPerMile = self::CHARGES_PER_MILE_UNDER_1000_PALLET;
        } else {
            $chargesPerPallet = self::CHARGES_PER_PALLET_OVER_1000_PALLET;
            $chargesPerMile = self::CHARGES_PER_MILE_OVER_1000_PALLET;
        }

        return ($days * $pallets * $chargesPerPallet) + ($chargesPerMile * $pallets * $miles);
    }

    private function finalReport($totalPrice)
    {
        $serviceCharges = $totalPrice * self::SERVICE_CHARGES / 100;

        return [
            'total' => $totalPrice,
            'service_charges' => $serviceCharges,
            'over_all_total' => $totalPrice - $serviceCharges
        ];
    }
}
