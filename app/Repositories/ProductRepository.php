<?php
namespace App\Repositories;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Support\Facades\Log;
use App\Models\CartItem;
use App\Enums\CartItemStatus;
class ProductRepository implements ProductRepositoryInterface
{
    public function getAll()
    {
        $ratingQuery = \DB::table('comments')
        ->select('product_id', \DB::raw('AVG(rating) as average_rating'))
        ->groupBy('product_id');

        $products = Product::available()
                ->leftJoinSub($ratingQuery, 'ratings', 'product.id', '=', 'ratings.product_id')
                ->select('product.*', 'ratings.average_rating') 
                ->get();

        return $products;
    }

    public function allWithFilters(Request $request)
    {
        $query = Product::available();

        $salesQuery = \DB::table('cart_item')
                    ->select('productId', \DB::raw('COALESCE(SUM(quantity), 0) as total_sales'))
                    ->where('status', CartItemStatus::purchased->value)
                    ->groupBy('productId');
        $ratingQuery = \DB::table('comments')
                    ->select('product_id', \DB::raw('AVG(rating) as average_rating'))
                    ->groupBy('product_id');

        $query->leftJoinSub($salesQuery, 'sales', function($join) {
            $join->on('product.id', '=', 'sales.productId');
        })->leftJoinSub($ratingQuery, 'ratings', function($join) {
            $join->on('product.id', '=', 'ratings.product_id');
        });

        $sort = $request->query('sort');
        switch ($sort) {
            case 'popularity':
                $query->orderBy('sales.total_sales', 'desc');
            case 'rating':
                $query->orderBy('ratings.average_rating', 'desc');
                break;
            case 'newness':
                $query->orderBy('created_at', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->orderBy('id');
                break;
        }

        // Filter by price range if specified
        $price = $request->query('price');
        if ($price) {
            [$minPrice, $maxPrice] = explode('-', $price);
            $query->whereBetween('price', [(float)$minPrice, (float)$maxPrice]);
        }

        // Filter by colors if specified
        $colors = $request->query('color', []);
        if (!empty($colors)) {
            $query->where(function($q) use ($colors) {
                foreach ($colors as $color) {
                    $q->orWhere('color', 'LIKE', "%{$color}%");
                }
            });
        }

        // Filter by category if specified
        $category = $request->query('category');
        if ($category) {
            $query->where('category', $category);
        }
        
        $searchTerm = $request->query('search-product');
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('productName', 'LIKE', "%{$searchTerm}%")
                ->orWhere('productDesc', 'LIKE', "%{$searchTerm}%");
            });
        }
        return $query->get();
    }

    public function find($id)
    {
        return Product::available()->findOrFail($id);
    }

    public function findRelatedProducts($type,$category, $excludeId)
    {
        $ratingQuery = \DB::table('comments')
                ->select('product_id', \DB::raw('AVG(rating) as average_rating'))
                ->groupBy('product_id');
        $query = Product::available()
                ->where(function ($query) use ($category, $type) {
                    $query->where('category', $category)
                          ->orWhere('productType', $type);
                })
                ->where('id', '!=', $excludeId)
                ->leftJoinSub($ratingQuery, 'ratings', 'product.id', '=', 'ratings.product_id')
                ->select('product.*', 'ratings.average_rating'); 

        return $query->get();
    }

    public function updateStock($productId, $color, $size, $quantity)
    {
        $product = Product::find($productId);
        if ($product) {
            // Parsing existing stock information
            $colorArray = explode('|', $product->color);
            $sizeArray = array_map(function ($sizes) {
                return explode(',', $sizes);
            }, explode('|', $product->size));
            $stockArray = array_map(function ($stocks) {
                return explode(',', $stocks);
            }, explode('|', $product->stock));

            // Finding the index of the specified color
            $colorIndex = array_search($color, $colorArray);
            if ($colorIndex === false) {
                // Color not found
                return;
            }

            // Finding the index of the specified size within the specified color
            $sizeIndex = array_search($size, $sizeArray[$colorIndex]);
            if ($sizeIndex === false) {
                // Size not found
                return;
            }

            // Reducing the stock
            $currentStock = $stockArray[$colorIndex][$sizeIndex];
            $newStock = max(0, $currentStock - $quantity); // Ensure stock doesn't go below zero
            $stockArray[$colorIndex][$sizeIndex] = $newStock;

            // Formatting the updated stock back to a string
            $updatedStock = implode('|', array_map(function ($stocks) {
                return implode(',', $stocks);
            }, $stockArray));

            // Saving updated stock
            $product->stock = $updatedStock;
            $product->save();
        }
    }
    public function create(array $data)
    {
        return Product::create($data);
    }
    public function update(array $data, $id) {
        $product = $this->find($id);
        if ($product) {
            $product->update($data);
            return $product;
        }
    }
    public function delete($id) {
        $product = $this->find($id);
        if ($product) {
            $product->deleted = 1;
            $product->save();
            return $product;
        }
    }
    public function increaseStock($stockString, $id) {
        $product = $this->find($id);
        if ($product) {
            $product->stock = $stockString;
            $product->save();
            return $product;
        }
    }
}
