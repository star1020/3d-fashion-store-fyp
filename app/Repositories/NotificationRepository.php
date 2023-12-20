<?php

namespace App\Repositories;

use App\Repositories\Interfaces\NotificationRepositoryInterface;
use App\Models\Notification;
use Illuminate\Support\Facades\Hash;

class NotificationRepository implements NotificationRepositoryInterface
{
    public function allNotification()
    {
        return Notification::where('deleted_at', 0)
        ->get();
    }

    public function storeNotification($data)
    {
        return Notification::create($data);
    }

    public function findNotification($id)
    {
        return Notification::where('deleted_at', 0)->find($id);
    }

    public function findNewProductRecommendationsForUser()
    {
        $recentlyAddedDays = 7; // Define the number of days to consider for new products
        $today = now()->startOfDay();

        return Notification::where('notifications.deleted_at', 0)
            ->join('product', 'notifications.related_id', '=', 'product.id')
            ->whereNull('notifications.user_id')
            ->where('notifications.type', 'new_product')
            ->whereDate('notifications.created_at', '>=', $today->subDays($recentlyAddedDays))
            ->orderBy('notifications.id', 'desc')
            ->get(['product.productName', 'product.price', 'notifications.*']);
    }

    public function findTodaysProductSuggestionsForUser($user_id)
    {
        $today = now()->startOfDay(); // Get the start of today

        return Notification::where('notifications.deleted_at', 0)
            ->join('product', 'notifications.related_id', '=', 'product.id')
            ->where('notifications.user_id', $user_id)
            ->where('notifications.type', 'product_suggestion')
            ->whereDate('notifications.created_at', $today)
            ->orderBy('notifications.id', 'desc')
            ->get(['product.productName', 'product.price', 'notifications.*']);
    }

    public function findSpecificNotification($user_id)
    {
        return Notification::where('deleted_at', 0)
        ->where('user_id', $user_id)
        ->where('type', '!=', 'product_suggestion')
        ->orderBy('id', 'desc')
        ->get();
    }

    public function getUserUnreadCount($user_id)
    {
        return Notification::where('deleted_at', 0)
        ->where('user_id', $user_id)
        ->where('type', '!=', 'product_suggestion')
        ->whereNull('read_at')
        ->count();
    }

    public function updateNotificationReadAt($id)
    {
        $notification = Notification::where('id', $id)->first();
        $notification->read_at = now();
        $notification->save();
    }

    public function destroyNotification($id)
    {
        $notification = Notification::find($id);
        $notification->deleted_at = 1;
        $notification->save();
    }
}
