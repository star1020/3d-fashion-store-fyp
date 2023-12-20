<?php
namespace App\Repositories\Interfaces;
interface OrderRepositoryInterface
{
    public function create(array $data);
    public function getOrdersByUserIdWithCartItemsAndProducts($userId);
    public function getOrderById($orderId);
    public function updateStatus($orderId,$status);
    public function getAllOrdersWithDeliveries();
    public function getOrdersAndDeliveriesById($id);
    public function getAllOrders();
    public function getTotalOrdersForPeriod($start, $end);
    public function getOrdersForLastSevenDays();
}
