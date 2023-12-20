<?php
namespace App\Repositories;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Product;
use App\Models\CartItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
class PaymentRepository implements PaymentRepositoryInterface
{
    public function create(array $data)
    {
        return Payment::create([
            'orderId' => $data['orderId'],
            'userId' => $data['userId'],
            'paymentMethod' => $data['paymentMethod'],
            'paymentDate' => $data['paymentDate'],
            'totalPaymentFee' => $data['totalPaymentFee'],
            'transactionId' => $data['transactionId'],
        ]);
    }

    public function getPaymentById($paymentId)
    {
        return Payment::findOrFail($paymentId);
    }

    public function getPaymentByOrderId($orderId)
    {
        return Payment::where('orderId', $orderId)->first();
    }
    
    public function getAllPaymentsWithOrdersByUserId($userId)
    {
        return Payment::with('order')
                  ->where('userId', $userId)
                  ->orderBy('paymentDate', 'desc')
                  ->get();
    }

    public function getAllPayments()
    {
        return Payment::all();
    }

    public function getAllPaymentsWithOrders()
    {
        return Payment::with('order')
                  ->get();
    }
    public function getTotalPaymentsForPeriod($start, $end)
    {
        return Payment::whereBetween('paymentDate', [$start, $end])->sum('totalPaymentFee');
    }
    
    public function getPaymentsForLastSevenDays()
    {
        return Payment::select(\DB::raw("DATE_FORMAT(paymentDate, '%d/%m') as date"), \DB::raw('SUM(totalPaymentFee) as total'))
            ->whereBetween('paymentDate', [Carbon::now()->subDays(6)->startOfDay(), Carbon::now()->endOfDay()])
            ->groupBy('date')
            ->orderBy('paymentDate')
            ->get()
            ->keyBy('date');
    }

    public function weeklySales($weeksAgo = 0)
    {
        $startDate = Carbon::now()->subWeeks($weeksAgo)->startOfWeek();
        $endDate = Carbon::now()->subWeeks($weeksAgo)->endOfWeek();
    
        $weeklySalesTotal = Payment::whereBetween('created_at', [$startDate, $endDate])
        ->sum('totalPaymentFee');
    
        return $weeklySalesTotal;
    }

    public function weeklySalesPercentageChange()
    {
        // Get the current week's SalesTotal
        $currentSalesTotal = $this->weeklySales();

        // Get the previous week's SalesTotal
        $previousSalesTotal = $this->weeklySales(1);

        // Calculate the percentage change in review count
        $percentageChange = 0;
        if ($previousSalesTotal > 0) {
            $percentageChange = (($currentSalesTotal - $previousSalesTotal) / $previousSalesTotal) * 100;
        }

        return round($percentageChange, 2);
    }

    public function weeklySalesChart()
    {
        // Get the start and end dates of the current week (Monday to Sunday)
        $startDate = Carbon::now()->startOfWeek()->format('Y-m-d H:i:s');
        $endDate = Carbon::now()->endOfWeek()->format('Y-m-d H:i:s');
    
        // Get the sales for the current week and group them by the day of the week
        $sales = Payment::select(
                    DB::raw("DATE_FORMAT(created_at,'%W') as dayOfWeek"), 
                    DB::raw('COUNT(id) as count'), 
                    DB::raw('SUM(totalPaymentFee) as totalSales')
                )
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('dayOfWeek')
                ->get();
    
        return $sales;
    }

    public function productSalesCount()
    {
        // Initialize the array to store product sales counts
        $productSalesCounts = [];
        Log::info('Retrieving all completed payments');
        // Get all completed payments
        $payments = Payment::all();

        // Iterate over payments to count product sales
        foreach ($payments as $payment) {
            Log::info("Processing payment ID: {$payment->id}");
            $order = Order::where('id', $payment->orderId)
                        ->first();

            if ($order) {
                $cartItems = explode('|', $order->cartItemIds);

                foreach ($cartItems as $cartItemId) {
                    Log::info("Processing cart item ID: {$cartItemId}");
                    $product = Product::whereHas('cartItems', function ($query) use ($cartItemId) {
                        $query->where('id', $cartItemId);
                    })->first();

                    if ($product) {
                        Log::info("Found product: {$product->productName}");
                        // Count the number of times this product appears in sales
                        $productName = $product->productName;
                        if (array_key_exists($productName, $productSalesCounts)) {
                            $productSalesCounts[$productName]++;
                        } else {
                            $productSalesCounts[$productName] = 1;
                        }
                    }
                }
            }
        }

        return $productSalesCounts;
    }
    public function getPaymentsForLastTwelveMonths()
    {
        $startDate = Carbon::now()->subMonths(11)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        return Payment::select(
                \DB::raw("DATE_FORMAT(paymentDate, '%m-%Y') as month"),
                \DB::raw('SUM(totalPaymentFee) as total')
            )
            ->whereBetween('paymentDate', [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('paymentDate')
            ->get()
            ->keyBy('month');
    }
}
