<?php

namespace App\Enums;

enum ProductColor: string
{
    case Black = 'Black';
    case White = 'White';
    case Green = 'Green';
    case Blue = 'Blue';
    case Red = 'Red';
    case Other = 'Other';

    public function colorCode(): string
    {
        return match($this) {
            self::Black => '#000000',
            self::White => '#FFFFFF',
            self::Green => '#008000',
            self::Blue => '#0000FF',
            self::Red => '#FF0000',
            self::Other => 'linear-gradient(90deg, #FFFF00, #EE82EE)', // Assuming 'Other' is a gradient.
        };
    }

    public function label(): string {
        return ucwords($this->value);
    }
    
}
