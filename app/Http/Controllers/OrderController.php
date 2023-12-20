<?php

namespace App\Http\Controllers;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Enums\CartItemStatus;
use App\Enums\OrderStatus;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\DeliveryRepositoryInterface;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use App\Repositories\Interfaces\CartItemRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\MembershipRepositoryInterface;
use App\Repositories\Interfaces\NotificationRepositoryInterface;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentReceiptMail;
use Session;
use App\Models\CartItem;
use App\Models\Product;

class OrderController extends Controller
{
    protected $orderRepository;
    protected $deliveryRepository;
    protected $paymentRepository;
    protected $cartItemRepository;
    private $userRepository;
    private $membershipRepository;
    protected $productRepository;
    protected $notificationRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        OrderRepositoryInterface $orderRepository, 
        DeliveryRepositoryInterface $deliveryRepository, 
        PaymentRepositoryInterface $paymentRepository,
        CartItemRepositoryInterface $cartItemRepository,
        ProductRepositoryInterface $productRepository,
        MembershipRepositoryInterface $membershipRepository,
        NotificationRepositoryInterface $notificationRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->deliveryRepository = $deliveryRepository;
        $this->paymentRepository = $paymentRepository;
        $this->cartItemRepository = $cartItemRepository;
        $this->userRepository = $userRepository;
        $this->membershipRepository = $membershipRepository;
        $this->productRepository = $productRepository;
        $this->notificationRepository = $notificationRepository;
    }

    public function store(Request $request, PaymentController $paymentController)
    {
        $validatedData = $request->validate([
            'transactionId' => 'required|string',
            'cartItemIds' => 'required|string',
            'deliveryAddress' => 'required|string',
        ]);
        
        if ($request->input('paymentType') == 'creditCard') {
            $paymentResponse = $paymentController->processPayment($request);
            $paymentResult = json_decode($paymentResponse->getContent(), true);
            if (!$paymentResult['success']) {
                \Log::info(''. $paymentResponse->getContent());
                return response()->json(['success' => false, 'message' => $paymentResult['message']]);
            }
        }
        $validatedData['userId'] = Auth::id();

        // Set order date to current date and time
        $validatedData['orderDate'] = Carbon::now();

        // Calculate estimated delivery date
        $eastMalaysiaStates = ['Sabah', 'Sarawak', 'Labuan']; // Define East Malaysia states
        $isEastMalaysia = in_array($request->input('state'), $eastMalaysiaStates);
        $estimatedDeliveryDays = $isEastMalaysia ? 14 : 7;
        $validatedData['estiDeliveryDate'] = Carbon::now()->addDays($estimatedDeliveryDays)->format('Y-m-d');

        try {
            $order = $this->orderRepository->create($validatedData);
            Log::info('Order created successfully:', ['orderId' => $order->id]);

            $deliveryData = [
                'orderId' => $order->id,
                'estimatedDeliveryDate' => Carbon::now()->addDays($estimatedDeliveryDays),
            ];
            $paymentData = [
                'orderId' => $order->id,
                'userId' => Auth::id(),
                'transactionId' => $validatedData['transactionId'],
                'paymentMethod' => $request->input('paymentType'),
                'paymentDate' => Carbon::now(),
                'totalPaymentFee' => $request->input('totalPrice')
            ];

            $cartItemIds = explode('|', $request->input('cartItemIds'));
            foreach ($cartItemIds as $cartItemId) {
                // Update cart item status
                $this->cartItemRepository->updateStatus($cartItemId, CartItemStatus::purchased->value);

                // Retrieve the cart item to get product details
                $cartItem = $this->cartItemRepository->getById($cartItemId);
                if ($cartItem) {
                    // Deduct product stock
                    $this->productRepository->updateStock($cartItem->productId, $cartItem->color, $cartItem->size, $cartItem->quantity);
                }
            }
            $deliveryPrice = $request->input('deliveryPrice');
            $this->deliveryRepository->create($deliveryData);
            $this->paymentRepository->create($paymentData);
            $eReceiptDetails = $this->prepareReceiptDetails($order, $paymentData, $cartItemIds,$deliveryPrice);
            Mail::to(Auth::user()->email)->queue(new \App\Mail\PaymentReceipt($eReceiptDetails));
            $this->userRepository->updateUserTotalSpent($request->input('totalPrice'), auth()->user()->id);

            // Get details of products from the current order
            $purchasedProducts = CartItem::whereIn('id', explode('|', $request->input('cartItemIds')))
            ->with('product')
            ->get();

            // Extract categories and product types
            $productDetails = $purchasedProducts->map(function ($cartItem) {
                return [
                    'category' => $cartItem->product->category,
                    'productType' => $cartItem->product->productType
                ];
            });

            // Get unique categories and product types
            $uniqueCategories = $productDetails->pluck('category')->unique();
            $uniqueProductTypes = $productDetails->pluck('productType')->unique();

            // Generate product suggestions based on these categories and product types
            $suggestedProducts = Product::where('deleted', 0)
            ->whereIn('category', $uniqueCategories)
            ->whereIn('productType', $uniqueProductTypes)
            ->whereNotIn('id', $purchasedProducts->pluck('product.id')) // Exclude already purchased products
            ->take(3) // Limit the number of suggestions
            ->get();

            // Store each suggested product in the notifications table
            foreach ($suggestedProducts as $product) {
                // Assume the first image is the primary image
                $imageFiles = explode('|', $product->productImgObj);
                $primaryImage = $imageFiles[0];

                $notificationData = [
                    'user_id' => $validatedData['userId'],
                    'related_id' => $product->id,
                    'type' => 'product_suggestion',
                    'title' => 'Product Suggestion',
                    'body' => "Based on your recent purchase, you might like the {$product->productName}. Check it out!",
                    'image' => $primaryImage,
                ];
                $this->notificationRepository->storeNotification($notificationData);
            }
            
            return response()->json([
                'success' => true,
                'orderId' => $order->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating order:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error creating order'
            ], 500);
        }
    }

    private function prepareReceiptDetails($order, $paymentData, $cartItemIds, $deliveryPrice)
    {
        $cartItems = $this->cartItemRepository->getByIds($cartItemIds, CartItemStatus::purchased->value);
        $productDetails = $cartItems->map(function ($cartItem) {
            // Assuming that the 'product' relationship is defined in the CartItem model
            $product = $cartItem->product; // Directly use the loaded relationship
            return [
                'productName' => $product->productName,
                'color' => $cartItem->color,
                'size' => $cartItem->size,
                'quantity' => $cartItem->quantity
            ];
        });

        return [
            'transactionId' => $paymentData['transactionId'],
            'paymentMethod' => $paymentData['paymentMethod'],
            'paymentDate' => $paymentData['paymentDate']->format('Y-m-d H:i:s'),
            'totalPaymentFee' => $paymentData['totalPaymentFee'],
            'deliveryAddress' => $order->deliveryAddress,
            'deliveryPrice' =>  $deliveryPrice,
            'productDetails' => $productDetails->toArray()
        ];
    }

    public function track($orderId)
    {
        $order = $this->orderRepository->getOrderById($orderId);
        $delivery = $this->deliveryRepository->getDeliveryByOrderId($orderId);
        $payment = $this->paymentRepository->getPaymentByOrderId($orderId);

        $cartItemIds = explode('|', $order->cartItemIds);

        $cartItems = $this->cartItemRepository->getByIds($cartItemIds, CartItemStatus::purchased->value);

        $memberships = $this->membershipRepository->allMembership();
        $membership_level = '';
        foreach ($memberships as $membership) {
            if (auth()->user()->total_spent >= $membership->totalAmount_spent) {
                $membership_level = $membership->level;
            } 
        }
        if(auth()->user()->membership_level != $membership_level) {
            //upgrade membership level
            $this->userRepository->updateUserMembership($membership_level, auth()->user()->id);
            Session::flash('membership_upgrade_message', 'Congratulations, you have been upgraded to '.$membership_level.' membership level!');
        }

        // Pass all data to the view
        return view('user.tracking', [
            'order' => $order,
            'delivery' => $delivery,
            'payment' => $payment,
            'cartItems' => $cartItems,
            'orderStatus' => OrderStatus::cases()
        ]);
    }

    public function markOrderReceived($orderId)
    {
        $this->orderRepository->updateStatus($orderId,OrderStatus::Completed->value);

        return redirect()->back()->with('success', 'Order marked as received.');
    }

}

