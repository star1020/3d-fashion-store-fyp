<?php
namespace App\Repositories;
use App\Models\Delivery;
use Illuminate\Support\Facades\Log;

use App\Repositories\Interfaces\DeliveryRepositoryInterface;
class DeliveryRepository implements DeliveryRepositoryInterface
{
    public function create(array $data)
    {
        return Delivery::create([
            'orderId' => $data['orderId'],
            'estimatedDeliveryDate' => $data['estimatedDeliveryDate'],
            'deliveryManName' => null,
            'deliveryManPhone' => null,
            'deliveryCompany' => null,
            'actualDeliveryDate' => null,
        ]);
    }

    public function getDeliveryById($deliveryId)
    {
        return Delivery::findOrFail($deliveryId);
    }

    public function getDeliveryByOrderId($orderId)
    {
        return Delivery::where('orderId', $orderId)->first();
    }
    public function updateDeliveryManData($id, $data)
    {
        $delivery = Delivery::findOrFail($id);
        $delivery->deliveryManPhone = $data['deliveryManPhone'];
        $delivery->deliveryCompany = $data['deliveryCompany'];
        $delivery->deliveryManName = $data['deliveryManName'];
        $delivery->save();
    }   
    public function updateActualDeliveryDate($id, $dateTime) {
        $delivery = Delivery::findOrFail($id);
        $delivery->actualDeliveryDate = $dateTime;
        $delivery->save();
    }
    public function getAllDeliveries()
    {
        return Delivery::all();
    }
}
