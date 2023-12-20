<?php
namespace App\Repositories;

use App\Models\CartItem;
use App\Enums\CartItemStatus;
use App\Repositories\Interfaces\CartItemRepositoryInterface;
class CartItemRepository implements CartItemRepositoryInterface
{
    
    public function addToCart(array $data)
    {
        return CartItem::create($data);
    }

    public function findExistingCartItem($productId, $userId, $color, $size, $status)
    {
        return CartItem::where([
            'productId' => $productId,
            'userId' => $userId,
            'color' => $color,
            'size' => $size,
            'status' => $status,

        ])->first();
    }
    
    public function getAllByUserId($userId)
    {
        return CartItem::where('userId', $userId)
                   ->where('status', CartItemStatus::inCart->value)
                   ->get();

    }

    public function getByUserId($userId)
    {
        $cartItems = CartItem::with('product')
        ->where('userId', $userId)
        ->where('status', CartItemStatus::inCart->value)
        ->whereHas('product', function($query) {
            $query->where('deleted', 0);
        })
        ->get()
        ->filter(function ($cartItem) {
            // Explode the product's color, size, and stock strings into arrays
            $productColors = explode('|', $cartItem->product->color);
            $productSizes = explode('|', $cartItem->product->size);
            $productStocks = explode('|', $cartItem->product->stock);

            // Find the index of the cart item's color in the product's colors
            $colorIndex = array_search($cartItem->color, $productColors);

            // Ensure the color exists for the product
            if ($colorIndex === false) {
                return false;
            }

            // Get the sizes and stocks associated with this color
            $sizesForColor = isset($productSizes[$colorIndex]) ? explode(',', $productSizes[$colorIndex]) : [];
            $stocksForColor = isset($productStocks[$colorIndex]) ? explode(',', $productStocks[$colorIndex]) : [];

            // Find the index of the cart item's size in the sizes for this color
            $sizeIndex = array_search($cartItem->size, $sizesForColor);

            // Ensure the size exists and there is stock available
            return $sizeIndex !== false && isset($stocksForColor[$sizeIndex]) && $stocksForColor[$sizeIndex] > 0;
        });
        return $cartItems;

    }

    public function deleteById($itemId)
    {
        return CartItem::destroy($itemId);
    }

    public function removeItemAndUpdateTotal($itemId, $userId)
    {
        CartItem::destroy($itemId);

        return $this->updateTotal($userId);
    }
    public function updateTotal($userId){
        $cartItems = $this->getByUserId($userId);
        $newTotal = 0;

        foreach ($cartItems as $item) {
            $newTotal += $item->quantity * $item->product->price;
        }

        return $newTotal;
    }
    public function updateQuantity($itemId, $quantity)
    {
        $cartItem = CartItem::find($itemId);
        $cartItem->quantity = $quantity;
        $cartItem->save();

        return $cartItem;
    }

    public function getTotalQuantityByUserId($userId)
    {
        $cartItems = $this->getByUserId($userId); 
        $totalQuantity = $cartItems->sum('quantity'); 
        return $totalQuantity;
    }

    public function getByIds(array $ids, $status) {
        return CartItem::with('product')
                ->whereIn('id', $ids)
                ->where('status', $status)
                ->get();
    }

    public function getById($cartItemId) {
        return CartItem::find($cartItemId);
    }

    public function updateStatus($cartItemId, $status)
    {
        $cartItem = CartItem::find($cartItemId);
        if ($cartItem) {
            $cartItem->status = $status;
            $cartItem->save();
        }
    }

    public function getAllCart() {
        return CartItem::all();
    }

    public function getRestocked($id, $color, $size) {
        return CartItem::where('productId', $id)
            ->where('color', $color)
            ->where('size', $size)
            ->where('quantity', '=', 0)
            ->where('status', 'in cart')
            ->pluck('userId');
    }

    public function getPriceDrop($id) {
        return CartItem::where('productId', $id)
            ->where('status', 'in cart')
            ->groupBy('userId')
            ->pluck('userId');
    }
}
