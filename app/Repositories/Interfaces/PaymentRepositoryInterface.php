<?php
namespace App\Repositories\Interfaces;
interface PaymentRepositoryInterface
{
    public function create(array $data);
    public function getPaymentById($paymentId);
    public function getPaymentByOrderId($orderId);
    public function getAllPaymentsWithOrdersByUserId($userId);
    public function getAllPayments();
    public function getAllPaymentsWithOrders();
    public function getTotalPaymentsForPeriod($start, $end);
    public function getPaymentsForLastSevenDays();
    public function weeklySales();
    public function weeklySalesPercentageChange();
    public function weeklySalesChart();
    public function productSalesCount();
    public function getPaymentsForLastTwelveMonths();
}
