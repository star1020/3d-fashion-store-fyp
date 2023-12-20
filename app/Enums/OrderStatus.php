<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Confirmed = 'confirmed'; 
    case CourierPicked = 'courier_picked'; 
    case OnTheWay = 'on_the_way';
    case ReadyForPickup = 'ready_for_pickup';
    case Completed = 'completed';
}