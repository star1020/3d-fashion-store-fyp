<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Http;
use Throwable;
use App\Repositories\Interfaces\ChatbotRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\RewardRepositoryInterface;
use App\Repositories\Interfaces\MembershipRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\OrderRepositoryInterface;

class ChatbotController extends Controller
{
    private $chatbotRepository;
    private $productRepository;
    private $rewardRepository;
    private $membershipRepository;
    private $userRepository;
    private $orderRepository;

    public function __construct(ChatbotRepositoryInterface $chatbotRepository, ProductRepositoryInterface $productRepository, RewardRepositoryInterface $rewardRepository, MembershipRepositoryInterface $membershipRepository, UserRepositoryInterface $userRepository, OrderRepositoryInterface $orderRepository)
    {
        $this->chatbotRepository = $chatbotRepository;
        $this->productRepository = $productRepository;
        $this->rewardRepository = $rewardRepository;
        $this->membershipRepository = $membershipRepository;
        $this->userRepository = $userRepository;
        $this->orderRepository = $orderRepository;
    }

    public function sendChat(Request $request) {
        try {
            // Save the user's input to the database
            // $chatConversation = new ChatConversation();
            // $chatConversation->user_id = auth()->user()->id; // assuming you have user authentication
            // $chatConversation->input = $request->input;
            // $chatConversation->save();

            $products = $this->formatProducts($this->productRepository->getAll());
            $rewards = $this->formatRewards($this->rewardRepository->allReward());
            $memberships = $this->formatMemberships($this->membershipRepository->allMembership());
            $messages = [
                [
                    'role' => 'system',
                    'content' => $products
                ],
                [
                    'role' => 'system',
                    'content' => $rewards
                ],
                [
                    'role' => 'system',
                    'content' => $memberships
                ]
            ];
            if(auth()->check()) {
                $user = $this->formatUserDetails($this->userRepository->findUser(auth()->user()->id));
                $messages[] = [
                    'role' => 'system',
                    'content' => $user
                ];
                $orders = $this->formatOrderDetails($this->orderRepository->getOrdersByUserIdWithCartItemsAndProducts(auth()->user()->id));
                $messages[] = [
                    'role' => 'system',
                    'content' => $orders
                ];
            } else {
                $messages[] = [
                    'role' => 'system',
                    'content' => 'Please log in to view your account and order and delivery details.'
                ];
            }

            $messages[] = [
                "role" => "user",
                "content" => $request->input
            ];

            $response = Http::withHeaders([
                "Content-Type" => "application/json",
                "Authorization" => "Bearer " . config('openai.api_key')
            ])->post('https://api.openai.com/v1/chat/completions', [
                "model" => 'gpt-3.5-turbo',
                "messages" => $messages,
                "temperature" => 0.7, //0 to 1, 0 more deterministic and less varied responses, 1 more creative response
                "max_tokens" => 100 //max words
            ])->body();

            // Decode the response to save to the database
            // $responseDecoded = json_decode($response, true);
            // $chatConversation->response = $responseDecoded['choices'][0]['message']['content'] ?? null;
            // $chatConversation->save();

            return response()->json(json_decode($response));
        } catch (Throwable $e) {
            return response()->json(['error' => 'Chat GPT Limit Reached. This means too many people have used this demo this month and hit the FREE limit available. You will need to wait, sorry about that.'], 401);
        }
    }

    private function formatProducts($products) {
        // Format each product into a string
        $formattedProducts = $products->map(function ($product) {
            $productType = (string) $product->productType->value;
            $productCategory = (string) $product->category->value;
            return "Name: {$product->productName}, Type: {$productType}, Description: {$product->productDesc}, Category: {$productCategory}, Color: {$product->color}, Size: {$product->size}, Stock: {$product->stock}, Price: {$product->price}";
        })->join('. ');
    
        return "Our products are: " . $formattedProducts;
    }
    
    private function formatRewards($rewards) {
        // Format each reward into a string
        $formattedRewards = $rewards->map(function ($reward) {
            return "Name: {$reward->name}, Description: {$reward->description}, Points Required: {$reward->points_required}, Quantity Available: {$reward->quantity_available}";
        })->join('. ');
    
        return "Available rewards for membership customer to claim: " . $formattedRewards;
    }
    
    private function formatMemberships($memberships) {
        // Format each membership into a string
        $formattedMemberships = $memberships->map(function ($membership) {
            return "Level: {$membership->level}, Total Amount Spent: {$membership->totalAmount_spent}, Discount: {$membership->discount}%, Created At: {$membership->created_at}";
        })->join('. ');
    
        return "Membership levels: " . $formattedMemberships;
    }

    private function formatUserDetails($user) {
        // Include any user details you want to format and return
        return "User Account Details: Name: {$user->name}, Email: {$user->email}, Membership Level: {$user->membership_level}, Total Spent: {$user->total_spent}, Reward Point: {$user->reward_point}, Downgraded at: {$user->downgraded_at}";
    }

    private function formatOrderDetails($orders) {
        $formattedOrderDetailsString = "Order and Delivery Details:";
    
        foreach ($orders as $order) {
            $formattedCartItems = collect($order->cartItemsWithProducts)->map(function ($cartItemWithProduct) {
                return "Product Name: {$cartItemWithProduct['product']->productName}, " .
                       "Quantity: {$cartItemWithProduct['cart_item']->quantity}, " .
                       "Color: {$cartItemWithProduct['cart_item']->color}, " .
                       "Size: {$cartItemWithProduct['cart_item']->size}";
            })->join('; ');
    
            $delivery = $order->delivery;
            $deliveryDetails = "Delivery Man: {$delivery->deliveryManName}, " .
                               "Delivery Man Phone: {$delivery->deliveryManPhone}, " .
                               "Delivery Company: {$delivery->deliveryCompany}, " .
                               "Delivery Address: {$delivery->deliveryAddress}, " .
                               "Estimated Delivery Date: {$delivery->estimatedDeliveryDate}, " .
                               "Actual Delivery Date: {$delivery->actualDeliveryDate}";
    
            // Concatenate each order details into a single string
            $formattedOrderDetailsString .= "\nOrder ID: {$order->id}, " .
                                            "Order Status: {$order->orderStatus}, " .
                                            "Order Date: {$order->orderDate}, " .
                                            "Cart Items: {$formattedCartItems}, " .
                                            "Delivery Details: {$deliveryDetails}.";
        }
    
        return $formattedOrderDetailsString;
    }

    public function indexFAQ(Request $request)
    {
        $faqs = $this->chatbotRepository->allFAQ();
        return view('admin/all-faq', compact('faqs'));
    }

    public function createFAQ(){
        return view('admin/add-faq');
    }

    public function storeFAQ(Request $req){
        $req->validate([
            'question' => 'required',
            'answer' => 'required'
        ]);

        $data = [
            'question' => $req->question,
            'answer' => $req->answer,
        ];
        $this->chatbotRepository->storeFAQ($data);
        return redirect()->route('faqs.index')->with('success', 'Successfully added a FAQ');
    }

    public function editFAQ($id){
        $faq = $this->chatbotRepository->findFAQ($id);
        return view('admin/edit-faq', compact('faq'));
    }

    public function updateFAQ(Request $request, $id)
    {
        $request->validate([
            'question' => 'required',
            'answer' => 'required'
        ]);

        $data = [
            'question' => $request->question,
            'answer' => $request->answer,
        ];
        $this->chatbotRepository->updateFAQ($data, $id);

        return redirect()->route('faqs.index')->with('success', 'Information has been updated');
    }

    public function destroyFAQ($id)
    {
        $this->chatbotRepository->destroyFAQ($id);
        return redirect()->route('faqs.index')->with('success', 'Information has been deleted');
    }
}
