<?php
namespace App\Repositories\Interfaces;
use Illuminate\Http\Request;
interface ProductRepositoryInterface
{
    public function getAll();
    public function allWithFilters(Request $request);
    public function find($id);
    public function findRelatedProducts($type, $category, $excludeId);
    public function updateStock($productId, $color, $size, $quantity);
    public function create(array $data);
    public function update(array $data, $id);
    public function delete($id);
    public function increaseStock($stockString, $id);
}
