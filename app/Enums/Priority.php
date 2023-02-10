<?php

namespace App\Enums;

use Illuminate\Support\Arr;

enum Priority: int
{
    case Lowest = 0;
    case Low = 1;
    case Medium = 2;
    case High = 3;
    case Highest = 4;


    public static function values(): array
    {
       return Arr::pluck(self::cases(), 'value');
    }
}
