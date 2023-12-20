<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\NotificationController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/auth/google', [UserController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [UserController::class, 'handleGoogleCallback']);

Route::middleware(['log.visitor'])->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/forget_password', function () {
        return view('user/forgetPassword');
    });
    Route::post('/forget_password', [UserController::class, 'forgetPassword']);
    Route::post('/submitResetPasswordForm', [UserController::class, 'submitResetPasswordForm']);
    Route::get('user/reset_password/{token}/{email}', [UserController::class, 'verify_reset_password'])->name('reset_password');
    
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
    
    Route::get('/product', [ProductController::class, 'show'])->name('products.show');
    Route::get('/product/{id}', [ProductController::class, 'showDetail'])->name('product.detail');
    
    Route::post('/sendChat', [ChatbotController::class, 'sendChat'])->name('sendChat');
    
    Route::get('/virtual-showroom', [ProductController::class, 'showVirtualShowroom'])->name('products.showVirtualShowroom');
    Route::get('/header-search', [ProductController::class, 'headerSearch'])->name('header.search');
});

// user
Route::prefix('user')->middleware(['auth'])->group(function(){
    Route::get('/edit-profile', [UserController::class, 'edit_profile'])->name('profile');
    Route::post('/submitEditProfileForm/{id}', [UserController::class, 'submitEditProfileForm'])->name('submitEditProfileForm');
    Route::get('/sendOTP/{phoneNumber}', [UserController::class, 'sendOTP']);
    Route::get('/validateOTP/{otp}', [UserController::class, 'validateOTP']);

    Route::post('/add-to-cart', [CartItemController::class, 'addToCart']);
    Route::post('/remove-from-cart', [CartItemController::class, 'removeItem']);
    Route::get('/cart', [CartItemController::class, 'showCart'])->name('showCart');
    Route::post('/update-cart-item', [CartItemController::class, 'updateItem']);
    Route::get('/update-cart-header-quantity', [CartItemController::class, 'getTotalQuantity']);
    Route::post('/make-order', [CartItemController::class, 'makeOrder']);

    Route::get('/payment-history', [UserController::class, 'displayPaymentHistory'])->name('paymentHistory');
    Route::get('/change-password', [UserController::class, 'changePassword'])->name('changePassword');
    Route::post('/edit_password/{id}', [UserController::class, 'edit_password'])->name('submitChangePassword');

    Route::post('/process-payment', [PaymentController::class, 'processPayment']);
    Route::get('/payment/token', [PaymentController::class, 'clientToken']);
    Route::post('/store-transaction', [OrderController::class, 'store']);
    Route::get('/tracking/{orderId}', [OrderController::class, 'track'])->name('trackingOrder');
    Route::post('/check-stock', [ProductController::class, 'checkStock']);
    Route::get('/payment-history', [PaymentController::class, 'viewHistory']);
    Route::post('/mark-order-received/{orderId}', [OrderController::class, 'markOrderReceived'])->name('mark-order-received');

    Route::post('/requestLiveChat', [ChatController::class, 'requestLiveChat'])->name('requestLiveChat');
    Route::post('/sendLiveChat', [ChatController::class, 'sendLiveChat'])->name('sendLiveChat');

    Route::resource('comments', CommentController::class)->only(['store']);
    Route::post('/comment/{comment_id}/like', [CommentController::class, 'like']);

    Route::get('/reward', [RewardController::class, 'showRewardAndHistory'])->name('reward');
    Route::post('/redeem/{rewardId}', [RewardController::class, 'redeem'])->name('redeem');
    Route::post('/deduct-points', [RewardController::class, 'deductPoints'])->name('deductPoints');
    Route::post('/update-points', [RewardController::class, 'updatePoints'])->name('updatePoints');
    Route::post('/reward-delivery-tracking', [RewardController::class, 'deliveryTracking'])->name('deliveryTracking');

    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/delete', [NotificationController::class, 'delete'])->name('notifications.delete');
    Route::get('/notifications/unread', [NotificationController::class, 'getUserUnreadCount'])->name('notifications.getUserUnreadCount');

    Route::get('/logout', function () {
        session()->flush();
        return redirect('/');
    });
});


// admin
Route::prefix('admin')->middleware(['auth', 'isStafforAdmin'])->group(function(){
    Route::get('/admin_portal', [DashboardController::class, 'index'])->name('adminDashboard');
    Route::get('/chat', [ChatController::class, 'index'])->name('livechat');
    Route::resource('customers', UserController::class);
    Route::resource('memberships', MembershipController::class);
    Route::post('/getChatRequestState', [ChatController::class, 'getChatRequestState'])->name('getChatRequestState');
    Route::post('/updateChatRequestState', [ChatController::class, 'updateChatRequestState'])->name('updateChatRequestState');
    Route::post('/liveAgentResponse', [ChatController::class, 'liveAgentResponse'])->name('liveAgentResponse');
    Route::post('/endChatSession', [ChatController::class, 'endChatSession'])->name('endChatSession');

    //faq
    Route::get('/faqs', [ChatbotController::class, 'indexFAQ'])->name('faqs.index');
    Route::get('/faqs/create', [ChatbotController::class, 'createFAQ'])->name('faqs.create');
    Route::post('/faqs/store', [ChatbotController::class, 'storeFAQ'])->name('faqs.store');
    Route::get('/faqs/{id}/edit', [ChatbotController::class, 'editFAQ'])->name('faqs.edit');
    Route::post('/faqs/{id}/update', [ChatbotController::class, 'updateFAQ'])->name('faqs.update');
    Route::post('/faqs/{id}/destroy', [ChatbotController::class, 'destroyFAQ'])->name('faqs.destroy');

    //product
    Route::get('/all-product', [ProductController::class,'displayAllProduct'])->name('all-products');
    Route::get('/product/create', [ProductController::class,'create'])->name('product.create');
    Route::post('/product/add', [ProductController::class,'add'])->name('product.add');
    Route::get('/product/edit/{id}', [ProductController::class,'edit'])->name('product.edit');
    Route::post('/product/update/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::get('/product/delete/{id}', [ProductController::class, 'delete'])->name('product.delete');
    Route::get('/product/add-stock/{id}', [ProductController::class, 'addStock'])->name('product.addStock');
    Route::post('/product/increase-stock/{id}', [ProductController::class, 'increaseStock'])->name('product.increaseStock');
    //order delivery
    Route::get('/all-delivery', [DeliveryController::class,'displayAllDeliveryData'])->name('all-delivery');
    Route::get('/delivery/{id}', [DeliveryController::class,'edit'])->name('delivery.edit');
    Route::post('/delivery/update/{id}', [DeliveryController::class,'update'])->name('delivery.update');
    //payment
    Route::get('/all-payment', [PaymentController::class,'displayAllPayment'])->name('all-payment');
    Route::get('/monthly-sales-report', [PaymentController::class, 'viewMonthlyReport'])->name('payment.viewMonthlyReport');
    //cart
    Route::get('/all-cart', [CartItemController::class,'displayAllCart'])->name('all-cart');

    //reward
    Route::resource('rewards', RewardController::class);
    Route::get('rewardClaims',[RewardController::class,'indexRewardClaim'])->name('rewardClaims.index');
    Route::get('/rewardClaims/{id}/edit', [RewardController::class, 'editRewardClaim'])->name('rewardClaims.edit');
    Route::post('/rewardClaims/{id}/update', [RewardController::class, 'updateRewardClaim'])->name('rewardClaims.update');
    Route::post('/rewardClaims/{id}/destroy', [RewardController::class, 'destroyRewardClaim'])->name('rewardClaims.destroy');

    Route::resource('comments', CommentController::class)->only(['index', 'edit', 'update', 'destroy']);

    Route::get('/userDemographic', [UserController::class, 'userDemographic_report'])->name('userDemographic');
    Route::get('/commentAnalysis', [CommentController::class, 'commentAnalysis_report'])->name('commentAnalysis');

    Route::post('/liveChatUploadImage', [ChatController::class, 'liveChatUploadImage'])->name('liveChatUploadImage');
});

Route::prefix('admin')->middleware(['auth', 'isAdmin'])->group(function(){
    Route::resource('staffs', UserController::class);
    Route::get('/staff', [UserController::class, 'indexStaff'])->name('staffs.all');

});