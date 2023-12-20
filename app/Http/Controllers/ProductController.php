<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Enums\ProductCategory;
use App\Enums\ProductType;
use App\Enums\ProductColor;
use App\Enums\ProductSize;
use App\Enums\CartItemStatus;
use Illuminate\Support\Facades\Log;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\CartItemRepositoryInterface;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Repositories\Interfaces\CommentRepositoryInterface;
use App\Repositories\Interfaces\NotificationRepositoryInterface;

class ProductController extends Controller
{
    protected $productRepository;
    protected $cartItemRepository;
    protected $commentRepository;
    protected $notificationRepository;

    public function __construct(ProductRepositoryInterface $productRepository,CartItemRepositoryInterface $cartItemRepository, CommentRepositoryInterface $commentRepository, NotificationRepositoryInterface $notificationRepository)
    {
        $this->productRepository = $productRepository;
        $this->cartItemRepository = $cartItemRepository;
        $this->commentRepository = $commentRepository;
        $this->notificationRepository = $notificationRepository;
    }

    public function show(Request $request)
    {
        $products = $this->productRepository->allWithFilters($request);

        // Retrieve all enum cases for filters
        $categories = ProductCategory::cases();
        $types = ProductType::cases();
        $colors = ProductColor::cases();

        // Return the view with the products and enums
        return view('user/product', compact('products', 'categories', 'types', 'colors'));
    }

    // ProductController.php
    public function showDetail($id)
    {
        $mainProduct = $this->productRepository->find($id);
        $relatedProducts = $this->productRepository->findRelatedProducts($mainProduct->productType,$mainProduct->category, $id);
        $comments = $this->commentRepository->allCommentByProductId($id);
        $totalReviews = count($comments);
        $totalRatingSum = 0;
        $ratingCounts = array_fill(1, 5, 0);

        foreach ($comments as $comment) {
            // Retrieve the order_id from the payment table using the payment_id from the comment
            $payment = Payment::where('id', $comment->payment_id)->first();
            $order = null;
            if ($payment) {
                // Now retrieve the order using the order_id from the payment
                $order = Order::where('id', $payment->orderId)->first();
            }
    
            // Initialize an array to store size and color
            $sizesAndColors = [];
    
            if ($order) {
                // Split the cartItemIds and find the related cart items
                $cartItemIds = explode('|', $order->cartItemIds);
                $cartItems = CartItem::whereIn('id', $cartItemIds)
                                     ->where('status', 'purchased')
                                     ->get();
    
                // Map the sizes and colors
                foreach ($cartItems as $cartItem) {
                    if ($cartItem->productId == $id) { // Check if the cart item's product matches the comment's product
                        $sizesAndColors[] = '[' . $cartItem->size . ', ' . $cartItem->color . ']';
                    }
                }
            }
    
            // Attach the sizes and colors to the comment
            $comment->sizesAndColors = implode(', ', $sizesAndColors);

            // Sum up all ratings.
            $totalRatingSum += $comment->rating;
            
            // Increment the count for the respective star level.
            $ratingCounts[$comment->rating]++;
        }

        // Calculate the average rating to one decimal place.
        $averageRating = $totalReviews > 0 ? number_format($totalRatingSum / $totalReviews, 1, '.', '') : 0;

        // Prepare the array for total number of comments per star level.
        $totalCommentsPerStarLevel = [];
        foreach ($ratingCounts as $star => $count) {
            $totalCommentsPerStarLevel[$star] = [
                'star' => $star,
                'totalComments' => $count
            ];
        }

        // Return the view with the main product and related products
        return view('user/product-detail', [
            'mainProduct' => $mainProduct,
            'relatedProducts' => $relatedProducts,
            'comments' => $comments,
            'totalReviews' => $totalReviews,
            'averageRating' => $averageRating,
            'totalCommentsPerStarLevel' => $totalCommentsPerStarLevel,
        ]);
    }

    public function checkStock(Request $request) {
        Log::info('Checking stock with request data:', $request->all());
    
        $cartItemIds = explode('|', $request->input('cartItemIds',''));
        $isInStock = true;
    
        // Retrieve cart items including associated products
        $cartItems = $this->cartItemRepository->getByIds($cartItemIds, CartItemStatus::inCart->value);
        Log::info('Retrieved cart items:', ['cartItems' => $cartItems]);
    
        if(count($cartItems) == 0) {
            $isInStock = false;
        }
        foreach ($cartItems as $cartItem) {
            // Log each cart item and its associated product
            Log::info('Cart Item:', ['cartItem' => $cartItem]);
    
            // Check if product is deleted
            if ($cartItem->product->deleted) {
                Log::info('Product is deleted', ['productId' => $cartItem->productId]);
                $isInStock = false;
                break;
            }
    
            // Parse and log the stock information
            $stockInfo = $this->parseStockInfo($cartItem->product->color, $cartItem->product->size, $cartItem->product->stock);
            Log::info('Parsed stock info', ['stockInfo' => $stockInfo]);
    
            // Check if stock is sufficient
            if (!$this->isStockSufficient($stockInfo, $cartItem->color, $cartItem->size, $cartItem->quantity)) {
                Log::info('Stock insufficient', ['product' => $cartItem->product->id, 'requested' => ['color' => $cartItem->color, 'size' => $cartItem->size, 'quantity' => $cartItem->quantity]]);
                $isInStock = false;
                break;
            }
        }
    
        // Log the final result of the stock check
        Log::info('Stock check result', ['inStock' => $isInStock]);
        return response()->json(['success' => true, 'inStock' => $isInStock]);
    }
    
    protected function parseStockInfo($colors, $sizes, $stocks) {
        $colorArray = explode('|', $colors);
        $sizeArray = array_map('explode', array_fill(0, count($colorArray), ','), explode('|', $sizes));
        $stockArray = array_map('explode', array_fill(0, count($colorArray), ','), explode('|', $stocks));
    
        $stockInfo = [];
        foreach ($colorArray as $colorIndex => $color) {
            foreach ($sizeArray[$colorIndex] as $sizeIndex => $size) {
                $stockInfo[$color][$size] = $stockArray[$colorIndex][$sizeIndex];
            }
        }
        return $stockInfo;
    }
    
    protected function isStockSufficient($stockInfo, $color, $size, $quantity) {
        return isset($stockInfo[$color][$size]) && $stockInfo[$color][$size] >= $quantity;
    }

    public function displayAllProduct() {
        $products = $this->productRepository->getAll();
        return view('/admin/all-product', [
            'products' => $products,
        ]);
    }

    public function create()
    {
        $productTypes = ProductType::cases();
        $categories = ProductCategory::cases();
        $colors = ProductColor::cases();
        $sizes = ProductSize::cases();
        return view('/admin/add-product', compact('productTypes', 'categories','colors', 'sizes'));
    }

    public function add(Request $request)
    {
        $validatedData = $request->validate([
            'productName' => 'required|max:255',
            'productType' => 'required',
            'category' => 'required',
            'productDesc' => 'required|max:255',
            'productPrice' => 'required',
            'color' => 'required|array',
            'size' => 'required|array',
            'stock' => 'required|array',
        ]);
        $validatedData['productPrice'] = str_replace(['RM', ' '], '', $validatedData['productPrice']);

        $sizeCounts = json_decode($request->input('sizeCountData'), true);
        if (is_null($sizeCounts)) {
            $sizeCounts = [1 => 1];
        }
        $sizes = $validatedData['size'];
        $stocks = $validatedData['stock'];
        $sizeString = '';
        $stockString = '';
        $currentIndex = 0;

        foreach ($sizeCounts as $colorId => $count) {
            $sizesForColor = array_slice($sizes, $currentIndex, $count);
            $stocksForColor = array_slice($stocks, $currentIndex, $count);

            list($sortedSizesForColor, $sortedStocksForColor) = $this->sortSizesAndStocks($sizesForColor, $stocksForColor);

            $sizeString .= ($sizeString === '' ? '' : '|') . implode(',', $sortedSizesForColor);
            $stockString .= ($stockString === '' ? '' : '|') . implode(',', $sortedStocksForColor);

            $currentIndex += $count;
        }

        $qrFileName  = '';
        $images = array();
        if ($files = $request->input('filepond')) {
            foreach ($files as $file) {
                $json_string = json_decode($file, true);
                $data_column = $json_string['data'];
                $image = base64_decode($data_column);
                $imageName = time() . '_' . $json_string['name']; // timestamp_imageName
                file_put_contents('../public/user/images/product/'.$imageName, $image);
                $images[] = $imageName;
            }
            $product_image = implode("|", $images);
        }

        if ($modelFile = $request->input('productModel')) {
            $json_string = json_decode($modelFile, true);
            $data_column = $json_string['data'];

            $model = base64_decode($data_column);
            $modelName = time() . '_' . $json_string['name']; // timestamp_imageName
            file_put_contents('../public/user/images/product/'.$modelName, $model);
            $product_image .= '|' . $modelName;
        }

        // Handle virtual try-on QR upload
        if ($qrFile = $request->input('virtualTryOnQR')) {
            $json_string = json_decode($qrFile, true);
            $data_column = $json_string['data'];

            $qr = base64_decode($data_column);
            $qrName = time() . '_QR_' . $json_string['name']; // timestamp_QR_imageName
            file_put_contents('../public/user/images/product/'.$qrName, $qr);
            $qrFileName = $qrName; 
        }

        $productData = [
            'productName' => $validatedData['productName'],
            'productType' => $validatedData['productType'],
            'category' => $validatedData['category'],
            'productDesc' => $validatedData['productDesc'],
            'price' => $validatedData['productPrice'],
            'color' => implode('|', $validatedData['color']),
            'size' => $sizeString,
            'stock' => $stockString,
            'productImgObj' => $product_image, 
            'productTryOnQR' => $qrFileName,
            'deleted'=>0,
        ];
        $newProduct = $this->productRepository->create($productData);
        $images = explode('|', $newProduct->productImgObj);
        $firstImage = $images[0];

        $notificationData = [
            'related_id' => $newProduct->id,
            'type' => 'new_product',
            'title' => 'New Product Alert',
            'body' => "We have a new product: {$newProduct->productName}! Check it out now!",
            'image' => $firstImage,
        ];
        // Store the notification
        $this->notificationRepository->storeNotification($notificationData);

        return redirect()->route('all-products')->with('success', 'Product processed successfully.');
    }

    private function sortSizesAndStocks(array $sizes, array $stocks)
    {
        $sizeOrder = [ProductSize::S->value, ProductSize::M->value, ProductSize::L->value, ProductSize::XL->value, ProductSize::XXL->value];
        
        // Combine sizes and stocks into an array of pairs
        $combined = array_map(null, $sizes, $stocks);
        
        // Sort the combined array based on the size order
        usort($combined, function($a, $b) use ($sizeOrder) {
            return array_search($a[0], $sizeOrder) - array_search($b[0], $sizeOrder);
        });

        // Separate the sorted sizes and stocks
        $sortedSizes = array_column($combined, 0);
        $sortedStocks = array_column($combined, 1);

        return [$sortedSizes, $sortedStocks];
    }

    public function edit($id)
    {
        $product = $this->productRepository->find($id);
        $allColors = explode('|', $product->color);
        $allSizes = explode('|', $product->size);
        $allStocks = explode('|', $product->stock);

        $sizeCount = [];
        foreach ($allSizes as $index => $sizesForColor) {
            $sizesArray = explode(',', $sizesForColor);
            $sizeCount[$index + 1] = count($sizesArray);
    
            $allSizes[$index] = $sizesArray;
            $allStocks[$index] = explode(',', $allStocks[$index]);
        }
        Log::info('Processed product details:', [
            'colors' => $allColors,
            'sizes' => $allSizes,
            'stocks' => $allStocks,
            'sizeCount' => $sizeCount
        ]);
        $productTypes = ProductType::cases();
        $categories = ProductCategory::cases();
        $colors = ProductColor::cases();
        $sizes = ProductSize::cases();
        return view('/admin/edit-product', compact('productTypes', 'categories','colors', 'sizes','product', 'sizeCount','allColors','allSizes','allStocks'));
    }

    public function update(Request $request, $id)
    {
        $product = $this->productRepository->find($id);
        $previousPrice = $product->price;
        $previousColors = explode('|', $product->color);
        $previousSizes = array_map(function($sizes) {
            return explode(',', $sizes);
        }, explode('|', $product->size));
        $previousStocks = array_map(function($stocks) {
            return explode(',', $stocks);
        }, explode('|', $product->stock));

        $validatedData = $request->validate([
            'productName' => 'required|max:255',
            'productType' => 'required',
            'category' => 'required',
            'productDesc' => 'required|max:255',
            'productPrice' => 'required',
            'color' => 'required|array',
            'size' => 'required|array',
            'stock' => 'required|array',
        ]);
        $validatedData['productPrice'] = str_replace(['RM', ' '], '', $validatedData['productPrice']);

        $sizeCounts = json_decode($request->input('sizeCountData'), true);
        if (is_null($sizeCounts)) {
            $sizeCounts = [1 => 1];
        }
        $sizes = $validatedData['size'];
        $stocks = $validatedData['stock'];
        $sizeString = '';
        $stockString = '';
        $currentIndex = 0;

        foreach ($sizeCounts as $colorId => $count) {
            $sizesForColor = array_slice($sizes, $currentIndex, $count);
            $stocksForColor = array_slice($stocks, $currentIndex, $count);

            list($sortedSizesForColor, $sortedStocksForColor) = $this->sortSizesAndStocks($sizesForColor, $stocksForColor);

            $sizeString .= ($sizeString === '' ? '' : '|') . implode(',', $sortedSizesForColor);
            $stockString .= ($stockString === '' ? '' : '|') . implode(',', $sortedStocksForColor);

            $currentIndex += $count;
        }

        $qrFileName  = '';
        $images = array();
$newModel = false;
$newImage = false;
$product_image = $product->productImgObj;
$existingModelName = ''; 
$existingFiles = explode('|', $product_image);
foreach ($existingFiles as $file) {
    if (strpos($file, '.gltf') !== false || strpos($file, '.glb') !== false) {
        $existingModelName = $file;
        break;
    }
}

// Handle model file upload
if ($modelFile = $request->input('productModel')) {
    $json_string = json_decode($modelFile, true);
    $data_column = $json_string['data'];

    $model = base64_decode($data_column);
    $modelName = time() . '_' . $json_string['name'];
    file_put_contents('../public/user/images/product/'.$modelName, $model);
    $newModel = true;
}

// Handle image uploads
if ($files = $request->input('filepond')) {
    $newImage = true;
    $existingFiles = explode('|', $product_image);
    $modelTimestampMap = [];

    // Mapping existing model files
    foreach ($existingFiles as $file) {
        if (strpos($file, 'gltf') !== false || strpos($file, 'glb') !== false) {
            $parts = explode('_', $file);
            $timestamp = $parts[0];
            $baseName = str_replace(['.gltf', '.glb'], '', end($parts));
            $modelTimestampMap[$baseName] = $timestamp;
        }
    }

    foreach ($files as $file) {
        $json_string = json_decode($file, true);
        $data_column = $json_string['data'];
        $image = base64_decode($data_column);
        $parts = explode('_', $json_string['name']);
        $imageBaseName = str_replace(['.jpg', '.png', '.jpeg'], '', end($parts));

        if ($newModel || !isset($modelTimestampMap[$imageBaseName])) {
            $imageName = time() . '_' . $json_string['name']; // New model or no matching model file
        } else {
            $timestamp = $modelTimestampMap[$imageBaseName];
            $imageName = $timestamp . '_' . $json_string['name']; // Use timestamp from matching model file
        }

        file_put_contents('../public/user/images/product/'.$imageName, $image);
        $images[] = $imageName;
    }
}
if ($newModel) {
    Log::info('new model');
    $images[] = $modelName;
}else if(!empty($existingModelName)){
    Log::info('exist model');
    $images[] = $existingModelName;
}
if (!empty($images)) {
    $product_image = implode("|", $images); // Append new images
}
if(!$newImage){
    $product_image = $product->productImgObj;
}

        if ($qrFile = $request->input('virtualTryOnQR')) {
            $json_string = json_decode($qrFile, true);
            $data_column = $json_string['data'];

            $qr = base64_decode($data_column);
            $qrName = time() . '_QR_' . $json_string['name'];
            file_put_contents('../public/user/images/product/'.$qrName, $qr);
            $qrFileName = $qrName; 
        } else {
            $qrFileName = $product->productTryOnQR;
        }

        $capitalizedColors = array_map(function($color) {
            return ucwords($color);
        }, $validatedData['color']);
        
        $colorString = implode('|', $capitalizedColors);
        
        $productData = [
            'productName' => $validatedData['productName'],
            'productType' => $validatedData['productType'],
            'category' => $validatedData['category'],
            'productDesc' => $validatedData['productDesc'],
            'price' => $validatedData['productPrice'],
            'color' => implode('|', $validatedData['color']),
            'size' => $sizeString,
            'stock' => $stockString,
            'productImgObj' => $product_image, 
            'productTryOnQR' => $qrFileName,
            'deleted'=>0,
        ];
        // Log::info('Final product data for update', ['id' => $id, 'productData' => $productData]);
        $this->productRepository->update($productData,$id);

        if($validatedData['productPrice'] < $previousPrice) {
            // Retrieve users to notify
            $usersToNotify = $this->cartItemRepository->getPriceDrop($product->id);
            $notifiedUsers = [];

            foreach ($usersToNotify as $userId) {
                // Skip if the user has already been notified
                if (in_array($userId, $notifiedUsers)) {
                    continue;
                }

                // Prepare notification data
                $notificationData = [
                    'user_id' => $userId,
                    'related_id' => $product->id,
                    'type' => 'price_drop',
                    'title' => 'Price Drop Alert',
                    'body' => "The price for {$product->productName} has been reduced! Check it out now!",
                    'image' => 'price-drop.png',
                ];
                // Store the notification
                $this->notificationRepository->storeNotification($notificationData);

                // Add the user to the notified users array
                $notifiedUsers[] = $userId;
            }
        }

        $newColors = $validatedData['color'];
        $newSizes = array_map(function($sizes) {
            return explode(',', $sizes);
        }, explode('|', $sizeString));
        $newStocks = array_map(function($stocks) {
            return explode(',', $stocks);
        }, explode('|', $stockString));
        
        // Prepare arrays to map previous stock levels by [color][size]
        $previousStockArray = [];
        foreach ($previousColors as $index => $color) {
            foreach ($previousSizes[$index] as $sizeIndex => $size) {
                $previousStockArray[$color][$size] = $previousStocks[$index][$sizeIndex];
            }
        }

        foreach ($newColors as $index => $color) {
            // If the color is new, skip it
            if (!isset($previousStockArray[$color])) {
                continue;
            }
    
            foreach ($newSizes[$index] as $sizeIndex => $size) {
                // Skip if the size is new for this color
                if (!isset($previousStockArray[$color][$size])) {
                    continue;
                }
                
                $previousStock = (int)$previousStockArray[$color][$size];
                $newStock = (int)$newStocks[$index][$sizeIndex];
    
                // Check for restock: previous stock is 0 and new stock is greater than 0
                if ($previousStock === 0 && $newStock > 0) {
                    // Retrieve users to notify
                    $usersToNotify = $this->cartItemRepository->getRestocked($product->id, $color, $size);
    
                    foreach ($usersToNotify as $userId) {
                        // Prepare notification data
                        $notificationData = [
                            'user_id' => $userId,
                            'related_id' => $product->id,
                            'type' => 'product_restock',
                            'title' => 'Product Restocked',
                            'body' => "The product {$product->productName} in color $color and size $size is back in stock!",
                            'image' => 'restocked.png',
                        ];
                        // Store the notification
                        $this->notificationRepository->storeNotification($notificationData);
                    }
                }
            }
        }

        return redirect()->route('all-products')->with('success', 'Product updated successfully.');
    }
    
    public function delete($id) {
        $this->productRepository->delete($id);
        return redirect()->route('all-products')->with('success', 'Product updated successfully.');
    }

    public function addStock($id)
    {
        $product = $this->productRepository->find($id);
        $allColors = explode('|', $product->color);
        $allSizes = explode('|', $product->size);

        $sizeCount = [];
        foreach ($allSizes as $index => $sizesForColor) {
            $sizesArray = explode(',', $sizesForColor);
            $sizeCount[$index + 1] = count($sizesArray);
    
            $allSizes[$index] = $sizesArray;
        }
        $productTypes = ProductType::cases();
        $categories = ProductCategory::cases();
        $colors = ProductColor::cases();
        $sizes = ProductSize::cases();
        return view('/admin/add-product-stock', compact('productTypes', 'categories','colors', 'sizes','product', 'sizeCount','allColors','allSizes'));
    }

    public function increaseStock(Request $request,$id)
    {
        $product = $this->productRepository->find($id);

        $previousColors = explode('|', $product->color);
        $previousSizes = array_map(function($sizes) {
            return explode(',', $sizes);
        }, explode('|', $product->size));
        $previousStocks = array_map(function($stocks) {
            return explode(',', $stocks);
        }, explode('|', $product->stock));

        $validatedData = $request->validate([
            'stock' => 'required|array',
        ]);

        $sizeCounts = json_decode($request->input('sizeCountData'), true);
        if (is_null($sizeCounts)) {
            $sizeCounts = [1 => 1];
        }

        $currentStocks = explode(',', str_replace('|', ',', $product->stock));

        $stocks = $validatedData['stock'];
        foreach ($currentStocks as $index => &$quantity) {
            if (isset($stocks[$index])) {
                $quantity += $stocks[$index];
            }
        }
        $stockString = '';
        $currentIndex = 0;
        foreach ($sizeCounts as $colorId => $count) {
            $stocksForColor = array_slice($currentStocks, $currentIndex, $count);
            $stockString .= ($stockString === '' ? '' : '|') . implode(',', $stocksForColor);
            $currentIndex += $count;
        }
        $this->productRepository->increaseStock($stockString,$id);

        $newStocks = array_map(function($stocks) {
            return explode(',', $stocks);
        }, explode('|', $stockString));
        
        // Prepare arrays to map previous stock levels by [color][size]
        $previousStockArray = [];
        foreach ($previousColors as $index => $color) {
            foreach ($previousSizes[$index] as $sizeIndex => $size) {
                $previousStockArray[$color][$size] = $previousStocks[$index][$sizeIndex];
            }
        }

        foreach ($previousColors as $index => $color) {    
            foreach ($previousSizes[$index] as $sizeIndex => $size) {
                $previousStock = (int)$previousStockArray[$color][$size];
                $newStock = (int)$newStocks[$index][$sizeIndex];
    
                // Check for restock: previous stock is 0 and new stock is greater than 0
                if ($previousStock === 0 && $newStock > 0) {
                    // Retrieve users to notify
                    $usersToNotify = $this->cartItemRepository->getRestocked($product->id, $color, $size);
    
                    foreach ($usersToNotify as $userId) {
                        // Prepare notification data
                        $notificationData = [
                            'user_id' => $userId,
                            'related_id' => $product->id,
                            'type' => 'product_restock',
                            'title' => 'Product Restocked',
                            'body' => "The product {$product->productName} in color $color and size $size is back in stock!",
                            'image' => 'restocked.png',
                        ];
                        // Store the notification
                        $this->notificationRepository->storeNotification($notificationData);
                    }
                }
            }
        }

        return redirect()->route('all-products')->with('success', 'Product updated successfully.');
    }

    public function showVirtualShowroom(){
        $products = $this->productRepository->getAll();
    
        foreach ($products as $product) {
            $product->colors = implode(', ', array_filter(explode('|', $product->color)));
            
            $sizes = explode(',', str_replace('|',',', $product->size));
            $stocks = explode(',', str_replace('|',',', $product->stock));
            [$sortedSizes, $sortedStocks] = $this->sortSizesAndStocks($sizes, $stocks);

            $product->sizes = implode(',', array_filter($sortedSizes));
        }
    
        return view('/user/virtual-showroom', [
            'products' => $products,
        ]);
    }

    public function index()
    {
        $products = $this->productRepository->getAll();
        $types = ProductType::cases();
        return view('user/index', compact('products', 'types'));
    }

    public function headerSearch(Request $request)
    {
        $searchTerm = $request->input('term');
        
        // Assuming you have a Product model with a 'name' field and 'image' field
        $products = Product::where('productName', 'LIKE', '%' . $searchTerm . '%')->where('deleted', 0)->get();

        $formattedProducts = $products->map(function ($product) {
            $images = explode('|', $product->productImgObj);
            return [
                'productName' => $product->productName,
                'productId' => $product->id,
                'productImg' => asset('user/images/product/' . $images[0]), // Adjust path as needed
            ];
        });

        return response()->json($formattedProducts);
    }

    
}

