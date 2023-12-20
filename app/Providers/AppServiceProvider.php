<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\ProductRepository;
use App\Repositories\Interfaces\CartItemRepositoryInterface;
use App\Repositories\CartItemRepository;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\OrderRepository;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use App\Repositories\PaymentRepository;
use App\Repositories\Interfaces\DeliveryRepositoryInterface;
use App\Repositories\DeliveryRepository;
use App\Repositories\Interfaces\ChatRepositoryInterface;
use App\Repositories\ChatRepository;
use App\Repositories\Interfaces\ChatbotRepositoryInterface;
use App\Repositories\ChatbotRepository;
use App\Repositories\Interfaces\MembershipRepositoryInterface;
use App\Repositories\MembershipRepository;
use App\Repositories\Interfaces\CommentRepositoryInterface;
use App\Repositories\CommentRepository;
use App\Repositories\Interfaces\RewardRepositoryInterface;
use App\Repositories\RewardRepository;
use App\Repositories\Interfaces\NotificationRepositoryInterface;
use App\Repositories\NotificationRepository;
use App\Repositories\Interfaces\VisitorRepositoryInterface;
use App\Repositories\VisitorRepository;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(CartItemRepositoryInterface::class, CartItemRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(DeliveryRepositoryInterface::class, DeliveryRepository::class);
        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);
        $this->app->bind(ChatRepositoryInterface::class, ChatRepository::class);
        $this->app->bind(ChatbotRepositoryInterface::class, ChatbotRepository::class);
        $this->app->bind(MembershipRepositoryInterface::class, MembershipRepository::class);
        $this->app->bind(CommentRepositoryInterface::class, CommentRepository::class);
        $this->app->bind(RewardRepositoryInterface::class, RewardRepository::class);
        $this->app->bind(NotificationRepositoryInterface::class, NotificationRepository::class);
        $this->app->bind(VisitorRepositoryInterface::class, VisitorRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(CartItemRepositoryInterface $cartItemRepository, ChatbotRepositoryInterface $chatbotRepository, NotificationRepositoryInterface $notificationRepository)
    {
        View::composer('user/header', function ($view) use ($cartItemRepository) {
            try {
                $totalQuantity = 0;
                if (Auth::check()) {
                    $userId = Auth::id();
                    $cartItems = $cartItemRepository->getByUserId($userId);
                    $totalPrice = $cartItems->reduce(function ($total, $item) {
                        return $total + ($item->quantity * $item->product->price);
                    }, 0);
                    $totalQuantity = $cartItems->sum('quantity');
                } else {
                    $cartItems = collect();
                    $totalPrice = 0;
                }
        
                $view->with('cartItems', $cartItems)->with('totalPrice', $totalPrice)->with('totalQuantity', $totalQuantity);
            } catch (\Exception $e) {
                \Log::error('Error in View Composer: ' . $e->getMessage());
            }
            
        });

        View::composer('user/header', function ($view) use ($notificationRepository) {
            if (Auth::check()) {
                $specificNotifications = $notificationRepository->findSpecificNotification(auth()->user()->id);
                $unreadCount = $notificationRepository->getUserUnreadCount(auth()->user()->id);
                $view->with('specificNotifications', $specificNotifications)->with('unreadCount', $unreadCount);
            }
        });

        View::composer('user/master', function ($view) use ($chatbotRepository) {
            $faqs = $chatbotRepository->allFAQ();
            $view->with('faqs', $faqs);
        });

        View::composer('user/master', function ($view) use ($notificationRepository) {
            $newProductRecommendations = $notificationRepository->findNewProductRecommendationsForUser();
            $view->with('newProductRecommendations', $newProductRecommendations);
        });

        View::composer('user/master', function ($view) use ($notificationRepository) {
            $personalizedProducts = '';
            if (Auth::check()) {
                $personalizedProducts = $notificationRepository->findTodaysProductSuggestionsForUser(auth()->user()->id);
            }
            $view->with('personalizedProducts', $personalizedProducts);
        });
    }
}
