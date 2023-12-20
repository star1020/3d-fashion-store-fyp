<?php
namespace App\Enums;

enum ProductCategory: string
{
    case Men = 'men';
    case Women = 'women';
    case Sport ='sport';
    case Other = 'other';

    public function label(): string {
        return ucwords($this->value);
    }
}