<?php

namespace App\Enums;

enum CostingMethod: string
{
    case STANDARD = 'standard';
    case AVERAGE = 'average';
    case FIFO = 'fifo';
    case LIFO = 'lifo';
}

?>