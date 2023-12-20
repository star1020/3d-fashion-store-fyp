<?php
namespace App\Enums;

enum CartItemStatus: string
{
    case inCart = 'in cart';
    case purchased = 'purchased';
}