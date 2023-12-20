<?php
namespace App\Repositories\Interfaces;
interface DeliveryRepositoryInterface
{
    public function create(array $data);
    public function getDeliveryById($deliveryId);
    public function getDeliveryByOrderId($orderId);
    public function updateDeliveryManData($delivery, $data);
    public function updateActualDeliveryDate($id, $dateTime);
    public function getAllDeliveries();
}
