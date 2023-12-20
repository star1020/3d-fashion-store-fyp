<?php
namespace App\Repositories\Interfaces;
interface CartItemRepositoryInterface
{
    public function addToCart(array $data);
    public function findExistingCartItem($productId, $userId, $color, $size, $status);
    public function getAllByUserId($userId);
    public function getByUserId($userId);
    public function deleteById($itemId);
    public function removeItemAndUpdateTotal($itemId, $userId);
    public function updateTotal($userId);
    public function updateQuantity($itemId, $quantity);
    public function getTotalQuantityByUserId($userId);
    public function getByIds(array $ids, $status);
    public function getById($cartItemId);
    public function updateStatus($cartItemId, $status);
    public function getAllCart();
    public function getRestocked($id, $color, $size);
    public function getPriceDrop($id);

}
