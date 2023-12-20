<?php

namespace App\Http\Controllers;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Enums\CartItemStatus;
use App\Enums\OrderStatus;
use App\Repositories\Interfaces\DeliveryRepositoryInterface;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\CartItemRepositoryInterface;
use App\Repositories\Interfaces\NotificationRepositoryInterface;
class DeliveryController extends Controller
{
    protected $deliveryRepository;
    protected $orderRepository;
    protected $cartItemRepository;
    protected $notificationRepository;
    public function __construct(DeliveryRepositoryInterface $deliveryRepository, OrderRepositoryInterface $orderRepository, CartItemRepositoryInterface $cartItemRepository, NotificationRepositoryInterface $notificationRepository) {
        $this->deliveryRepository = $deliveryRepository;
        $this->orderRepository = $orderRepository;
        $this->cartItemRepository = $cartItemRepository;
        $this->notificationRepository = $notificationRepository;
    }
    public function displayAllDeliveryData()
    {
        $ordersWithDeliveries = $this->orderRepository->getAllOrdersWithDeliveries();
        return view('/admin/all-delivery', ['ordersWithDeliveries' => $ordersWithDeliveries]);
        
    }

    public function edit($id)
    {
        $ordersWithDelivery = $this->orderRepository->getOrdersAndDeliveriesById($id);
        $cartItemIds = explode('|', $ordersWithDelivery->cartItemIds);

        $allGroupedCartItems = collect();
    
        $cartItems = $this->cartItemRepository->getByIds($cartItemIds, CartItemStatus::purchased->value);
        $groupedCartItems = $cartItems->groupBy('productId');
        $allGroupedCartItems[$ordersWithDelivery->id] = $groupedCartItems;
            
        return view('/admin/edit-delivery', [
            'ordersWithDelivery' => $ordersWithDelivery,
            'allGroupedCartItems' => $allGroupedCartItems,
        ]);
        
    }
    
    public function update(Request $request, $id)
    {
        $oldOrder = $this->orderRepository->getOrderById($id);
        $order = $this->orderRepository->updateStatus($id,$request->input('orderStatus'));
        $delivery = $this->deliveryRepository->getDeliveryByOrderId($order->id);

        //prevent submit with unchanged status and create again the notification
        if ($request->input('orderStatus') != 'confirmed' && $oldOrder->orderStatus != $order->orderStatus) {
            $orderStatuses = [
                'courier_picked' => 'courier-picked.png',
                'on_the_way' => 'on-the-way.png',
                'ready_for_pickup' => 'ready-for-pickup.png',
                'completed' => 'completed.png',
            ];
            $notificationImg = $orderStatuses[$request->input('orderStatus')] ?? '';
            $orderStatuses2 = [
                'courier_picked' => 'Courier Picked',
                'on_the_way' => 'On The Way',
                'ready_for_pickup' => 'Ready For Pickup',
                'completed' => 'Completed',
            ];
            $orderStatusText = $orderStatuses2[$request->input('orderStatus')] ?? '';

            $notificationData = [
                'user_id' => $order->userId,
                'related_id' => $order->id,
                'type' => 'order_status_update',
                'title' => 'Order Status Update',
                'body' => 'Order ID #'. $order->id .' is '. $orderStatusText,
                'image' => $notificationImg,
            ];
            $this->notificationRepository->storeNotification($notificationData);
        }

        if ($request->input('orderStatus') === OrderStatus::CourierPicked->value) {
            $deliveryData = [
                'deliveryManName' => $request->input('deliveryManName'),
                'deliveryManPhone' => $request->input('deliveryManPhone'),
                'deliveryCompany' => $request->input('deliveryCompany'),
            ];
            $this->deliveryRepository->updateDeliveryManData($delivery->id, $deliveryData);
        }else if($request->input('orderStatus') === OrderStatus::ReadyForPickup->value){
            $currentDateTime = Carbon::now();
            $this->deliveryRepository->updateActualDeliveryDate($delivery->id, $currentDateTime);
        }
        return redirect()->route('all-delivery')->with('success', 'Delivery & Order updated successfully.');
    }
}
