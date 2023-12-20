<?php
namespace App\Repositories\Interfaces;

Interface NotificationRepositoryInterface{
    public function allNotification();
    public function storeNotification($data);
    public function findNotification($id);
    public function updateNotificationReadAt($id);
    public function findNewProductRecommendationsForUser();
    public function findTodaysProductSuggestionsForUser($user_id);
    public function findSpecificNotification($user_id);
    public function getUserUnreadCount($user_id);
    public function destroyNotification($id);

}