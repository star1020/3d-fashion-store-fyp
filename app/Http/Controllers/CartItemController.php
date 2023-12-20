<?php

namespace App\Http\Controllers;
use App\Enums\CartItemStatus;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\CartItemRepositoryInterface;
use App\Repositories\Interfaces\MembershipRepositoryInterface;
use Auth;
use App\Models\Product;

class CartItemController extends Controller
{
    protected $cartItemRepository;
    private $membershipRepository;

    public function __construct(CartItemRepositoryInterface $cartItemRepository, MembershipRepositoryInterface $membershipRepository)
    {
        $this->cartItemRepository = $cartItemRepository;
        $this->membershipRepository = $membershipRepository;
    }

    public function addToCart(Request $request)
    {
        $validatedData = $request->validate([
            'productId' => 'required|exists:product,id',
            'color' => 'required',
            'size' => 'required',
            'num-product' => 'required|integer|min:1'
        ]);
        $status = CartItemStatus::inCart->value;
        $product = Product::find($validatedData['productId']);
        if (!$product) {
            return back()->withErrors(['Product not found.']);
        }
        
        $existingCartItem = $this->cartItemRepository->findExistingCartItem(
            $validatedData['productId'], 
            Auth::id(), 
            $validatedData['color'], 
            $validatedData['size'],
            $status,
        );
    
        $newQuantity = $validatedData['num-product'];
    
        if ($existingCartItem) {
            $totalQuantity = $existingCartItem->quantity + $newQuantity;
            
            $maxQuantityAllowed = $request->maxProductQuantity;

            if ($totalQuantity > $maxQuantityAllowed) {
                return response()->json(['error' => 'The item in your cart already reaches the maximum of stock.'], 422);
            }else{
                // Update existing cart item quantity
                $existingCartItem->quantity = $totalQuantity;
                $existingCartItem->save();
            }
        } else {
            $data = [
                'productId' => $validatedData['productId'],
                'userId' => Auth::id(),
                'color' => $validatedData['color'],
                'size' => $validatedData['size'],
                'quantity' => $newQuantity,
                'status'=> $status
            ];
    
            $this->cartItemRepository->addToCart($data);
        }
        $cartItems = $this->cartItemRepository->getByUserId(Auth::id());
        $totalPrice =  $this->cartItemRepository->updateTotal(Auth::id());
        $totalPrice = floatval(str_replace(',', '', $totalPrice));
        // Render the cart items view with the necessary data
        $cartItemsHtml = view('user.partials.cart_items', [
            'cartItems' => $cartItems,
            'totalPrice' => number_format($totalPrice, 2)
        ])->render();
        $totalQuantity = $totalQuantity = $this->cartItemRepository->getTotalQuantityByUserId(Auth::id());
        
        return response()->json([
            'cartItemsHtml' => $cartItemsHtml,
            'newTotalPrice' => number_format($totalPrice, 2),
            'totalQuantity' => $totalQuantity
        ]);
    }

    public function removeItem(Request $request)
    {
        $itemId = $request->input('itemId');
        $userId = auth()->id(); // Or however you get the user's ID

        $newTotal = $this->cartItemRepository->removeItemAndUpdateTotal($itemId, $userId);
        $totalQuantity = $this->cartItemRepository->getTotalQuantityByUserId($userId);
        $newTotal = floatval(str_replace(',', '', $newTotal));
        return response()->json(['success' => 'Item removed successfully', 'newTotal' => number_format($newTotal, 2) , 'totalQuantity' => $totalQuantity]);
    }
    
    public function showCart()
    {
        // Assuming the user is authenticated and cart items are associated with the user.
        $userId = Auth::id();

        // Fetch cart items for the user
        $cartItems = $this->cartItemRepository->getAllByUserId($userId);
        // Calculate the total price
        $totalPrice = $cartItems->reduce(function ($total, $cartItem) {
            return $total + ($cartItem->quantity * $cartItem->product->price);
        }, 0);
        $totalPrice = floatval(str_replace(',', '', $totalPrice));
        $discountRate = 0;
        if(auth()->user()->membership_level){
            $membership = $this->membershipRepository->findMembershipByLevel(auth()->user()->membership_level);
            $discountRate = $membership->discount;
        }
        // Return the cart view with cart items and total price
        return view('/user/cart', [
            'cartItems' => $cartItems,
            'totalPrice' => number_format($totalPrice, 2),
            'discountRate' => $discountRate
        ]);
    }

    public function updateItem(Request $request)
    {
        $cartItem = $this->cartItemRepository->updateQuantity($request->itemId, $request->quantity);
        $newSubPrice = $cartItem->quantity * $cartItem->product->price;
        $newSubPrice = floatval(str_replace(',', '', $newSubPrice));
        $discountRate = 10;
        return response()->json([
            'success' => true,
            'newSubPrice' => number_format($newSubPrice, 2),
            'discountRate' => $discountRate
        ]);
    }

    public function getTotalQuantity()
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $totalQuantity = $this->cartItemRepository->getTotalQuantityByUserId($userId);
            return response()->json(['totalQuantity' => $totalQuantity]);
        }

        return response()->json(['totalQuantity' => 0]);
    }

    public function makeOrder(Request $request) {
        $selectedItemIds = $request->input('selectedItems', []);
        $cartItems = $this->cartItemRepository->getByIds($selectedItemIds, CartItemStatus::inCart->value);
        
        $groupedItems = [];
        $overallSubtotal = 0; // This will hold the subtotal for all selected items

        foreach ($cartItems as $item) {
            $productId = $item->product->id;
            $productDetails = $item->color . ', ' . $item->size;
            $itemSubtotal = $item->quantity * $item->product->price;
            $overallSubtotal += $itemSubtotal;

            if (!isset($groupedItems[$productId])) {
                $groupedItems[$productId] = [
                    'name' => $item->product->productName,
                    'details' => [],
                    'subtotal' => 0 // Initialize subtotal for this product
                ];
            }

            $groupedItems[$productId]['details'][] = $productDetails . ' ' . $item->quantity;
            $groupedItems[$productId]['subtotal'] += $itemSubtotal;
        }
        $cartItemIds = implode('|', $selectedItemIds);
        $discountRate = $request->input('discountRate');
        $discount = $overallSubtotal * ($discountRate / 100);
        $shippingCost = $request->input('shippingCostHidden',0);
        $finalTotalPrice = $overallSubtotal + $shippingCost - $discount;
        $finalTotalPrice = floatval(str_replace(',', '', $finalTotalPrice));
        $discount = floatval(str_replace(',', '', $discount));

        $address = $request->input('address');
        $postcode = $request->input('postcode');
        $state = $request->input('state');
        $country = $request->input('country');
        $fullAddress = "{$address}, {$postcode}, {$state}, {$country}";

        return view('/user/payment', [
            'groupedItems' => $groupedItems,
            'discountRate' => $discountRate,
            'discount' => number_format($discount, 2),
            'shippingCost' => $shippingCost,
            'finalTotalPrice' => number_format($finalTotalPrice, 2),
            'cartItemIds' => $cartItemIds,
            'fullAddress' => $fullAddress,
            'state' => $state,
        ]);
    }

    public function displayAllCart(){
        $cartItems = $this->cartItemRepository->getAllCart();

        return view('/admin/all-cart', [
            'cartItems' => $cartItems
        ]);
    }
}

