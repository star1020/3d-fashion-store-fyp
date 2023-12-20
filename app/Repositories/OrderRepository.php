<?php
namespace App\Repositories;
use App\Models\Order;
use App\Models\CartItem;
use App\Enums\OrderStatus;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Repositories\Interfaces\OrderRepositoryInterface;
class OrderRepository implements OrderRepositoryInterface
{
    public function create(array $data)
    {
        return Order::create([
            'cartItemIds' => $data['cartItemIds'],
            'userId' => $data['userId'],
            'deliveryAddress' => $data['deliveryAddress'],
            'orderStatus' => OrderStatus::Confirmed,
            'orderDate' => $data['orderDate'],
        ]);
    }

    public function getOrdersByUserIdWithCartItemsAndProducts($userId)
    {
        // Retrieve all orders for the given user ID with related deliveries
        $orders = Order::with(['delivery'])
                      ->where('userId', $userId)
                      ->get();

        // Loop through each order to handle cart items and products
        foreach ($orders as $order) {
            // Split the cartItemIds string into an array of IDs
            $cartItemIds = explode('|', $order->cartItemIds);

            // Retrieve CartItem models for these IDs along with their related products
            $cartItemsWithProducts = CartItem::with('product')
                                             ->whereIn('id', $cartItemIds)
                                             ->get()
                                             ->map(function ($cartItem) {
                                                 // Return the cart item with the product details
                                                 return [
                                                     'cart_item' => $cartItem,
                                                     'product' => $cartItem->product,
                                                 ];
                                             });

            // Add the cart items with products to the order
            $order->cartItemsWithProducts = $cartItemsWithProducts;
        }

        return $orders;
    }

    public function getOrderById($orderId)
    {
        return Order::findOrFail($orderId);
    }

    public function updateStatus($orderId, $status)
    {
        $order = $this->getOrderById($orderId);
        $order->orderStatus = $status;
        $order->save();

        return $order;
    }

    public function getAllOrdersWithDeliveries()
    {
        $orders = Order::with('delivery')->get();
        return $orders;
    }
    public function getOrdersAndDeliveriesById($id)
    {
        $order = Order::whereHas('delivery', function ($query) use ($id) {
            $query->where('id', $id);
        })->with('delivery')->first();
        return $order;
    }
    
    public function getAllOrders()
    {
        return Order::all();
    }
    public function getTotalOrdersForPeriod($start, $end)
    {
        return Order::whereBetween('created_at', [$start, $end])->count();
    }
    public function getOrdersForLastSevenDays()
    {
        return Order::select(\DB::raw("DATE_FORMAT(created_at, '%d/%m') as date"), \DB::raw('COUNT(*) as total'))
            ->whereBetween('created_at', [Carbon::now()->subDays(6)->startOfDay(), Carbon::now()->endOfDay()])
            ->groupBy('date')
            ->orderBy('created_at')
            ->get()
            ->keyBy('date');
    }
}
