<?php

namespace App\Services;

use App\Models\Unit;
use InvalidArgumentException;

class UnitConversionService
{
    public function convert(float $quantity, Unit $from, Unit $to): float
    {
        if ($from->unit_category_id !== $to->unit_category_id) {
            throw new InvalidArgumentException(
                'Units must belong to the same category.'
            );
        }

        return $quantity * $from->conversion_factor/$to->conversion_factor;
    }
}