<?php
namespace App\Enums;

enum ProductType: string
{
    case Tops = 'tops';
    case Bottoms = 'bottoms';
    case Sets = 'sets';
    case Footwear = 'footwear';
    case Eyewear = 'eyewear';
    case Accessories = 'accessories';

    public function label(): string {
        return ucwords($this->value);
    }
}