<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Enums\OrderStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\DeliveryRepositoryInterface;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
class UpdateOrderStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-order-status-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(PaymentRepositoryInterface $paymentRepository, DeliveryRepositoryInterface $deliveryRepository, OrderRepositoryInterface $orderRepository)
    {
        $payments = $paymentRepository->getAllPaymentsWithOrders();

        foreach ($payments as $payment) {
            if ($payment->order && $payment->order->orderStatus !== OrderStatus::Completed->value) {
                $delivery = $deliveryRepository->getDeliveryByOrderId($payment->order->id);

                if ($delivery && $delivery->actualDeliveryDate && Carbon::parse($delivery->actualDeliveryDate)->addDays(3)->isPast()) {
                    $orderRepository->updateStatus($payment->order->id, OrderStatus::Completed->value);
                }
            }
        }
    }
}
