<?php

namespace App\Support;

use App\Models\CateringPackage;

class CateringQuote
{
    public const DEFAULT_PER_GUEST = 24;

    public static function estimate(?int $packageId, int $guestCount): float
    {
        if ($packageId) {
            $package = CateringPackage::find($packageId);
            $estimate = $package?->estimateForGuests($guestCount);
            if ($estimate !== null) {
                return $estimate;
            }
        }

        return round($guestCount * self::DEFAULT_PER_GUEST, 2);
    }
}
