<?php

namespace App\Http\Controllers;
use Auth;
use Braintree\Gateway;
use App\Enums\CartItemStatus;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use App\Repositories\Interfaces\CartItemRepositoryInterface;
use App\Repositories\Interfaces\DeliveryRepositoryInterface;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\CommentRepositoryInterface;

class PaymentController extends Controller
{
    protected $gateway;
    protected $paymentRepository;
    protected $cartItemRepository;
    private $commentRepository;
    protected $deliveryRepository;
    protected $orderRepository;

    public function __construct(PaymentRepositoryInterface $paymentRepository, CartItemRepositoryInterface $cartItemRepository, CommentRepositoryInterface $commentRepository, DeliveryRepositoryInterface $deliveryRepository, OrderRepositoryInterface $orderRepository) {
        $this->paymentRepository = $paymentRepository;
        $this->cartItemRepository = $cartItemRepository;
        $this->deliveryRepository = $deliveryRepository;
        $this->orderRepository = $orderRepository;
        $this->commentRepository = $commentRepository;
        $this->gateway = new Gateway([
            'environment' => config('braintree.environment'),
            'merchantId' => config('braintree.merchantId'),
            'publicKey' => config('braintree.publicKey'),
            'privateKey' => config('braintree.privateKey'),
        ]);
    }

    public function processPayment(Request $request)
    {
        $amount = $request->totalPrice;

        // Determine the card type
        $cardType = $this->determineCardType($request->cardNumber);

        // Proceed with the payment transaction
        $result = $this->gateway->transaction()->sale([
            'amount' => $amount,
            'paymentMethodNonce' => $request->transactionId,
            'options' => ['submitForSettlement' => true]
        ]);

        if ($result->success) {
            return response()->json([
                'success' => true,
                'transactionId' => $result->transaction->id, 
                'cardType' => $cardType
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $result->message
            ]);
        }
    }


    protected function determineCardType($cardNumber)
    {
        if (empty($cardNumber)) {
            return 'visa';
        }

        // Regular expressions for different card types
        $cardPatterns = [
            'visa' => '/^4[0-9]{12}(?:[0-9]{3})?$/',
            'mastercard' => '/^5[1-5][0-9]{14}$/',
            'amex' => '/^3[47][0-9]{13}$/',
            'discover' => '/^6(?:011|5[0-9]{2})[0-9]{12}$/',
            'troy' => '/^9792[0-9]{12}$/',
        ];

        foreach ($cardPatterns as $type => $pattern) {
            if (preg_match($pattern, $cardNumber)) {
                return $type;
            }
        }

        return 'visa';
    }

    // Generate a client token
    public function clientToken()
    {
        $clientToken = $this->gateway->clientToken()->generate();
        return response()->json(['token' => $clientToken]);
    }

    public function viewHistory()
    {
        $userId = auth()->id();
        $payments = $this->paymentRepository->getAllPaymentsWithOrdersByUserId($userId);
        $allGroupedCartItems = collect(); // To store grouped cart items for all payments
        
        foreach ($payments as $payment) {
            if ($payment->order) {
                $ids = explode('|', $payment->order->cartItemIds);
                $cartItems = $this->cartItemRepository->getByIds($ids, CartItemStatus::purchased->value);
                $groupedCartItems = $cartItems->groupBy('productId');
                $allGroupedCartItems[$payment->order->id] = $groupedCartItems;
            }
        }
        $comments = $this->commentRepository->allComment();    $comments = $this->commentRepository->allComment();
        // Pass the payments and the grouped cart items to the view
        return view('user.payment-history', compact('payments', 'allGroupedCartItems', 'comments'));
    }

    public function displayAllPayment(){

        $payments = $this->paymentRepository->getAllPayments();

        return view('/admin/all-payment', [
            'payments' => $payments,
        ]);
    }

    public function viewMonthlyReport()
    {
        $monthlySales = $this->paymentRepository->getPaymentsForLastTwelveMonths();

        $salesArray = [];
        foreach ($monthlySales as $month => $data) {
            $salesArray[$month] = $data->total;
        }

        return view('admin.monthly-sales-report', compact('salesArray'));
    }

}

