<?php
namespace App\Repositories\Interfaces;

Interface ChatRepositoryInterface{
    public function storeChatRequest($data);
    public function getChatRequests();
    public function getChatRequestsById($id);
    public function updateChatRequests($id, $status, $staff_id);
    public function endChatRequests($id);

}