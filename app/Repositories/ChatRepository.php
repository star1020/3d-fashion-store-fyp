<?php

namespace App\Repositories;

use App\Repositories\Interfaces\ChatRepositoryInterface;
use App\Models\ChatRequest;
use Illuminate\Support\Facades\Hash;

class ChatRepository implements ChatRepositoryInterface
{
    public function storeChatRequest($data)
    {
        return ChatRequest::create($data);
    }

    public function getChatRequests()
    {
        return ChatRequest::with('user')->where('status', 'pending')->get();
    }

    public function getChatRequestsById($id)
    {
        return ChatRequest::with('user')->where('id', $id)->get();
    }

    public function updateChatRequests($id, $status, $staff_id)
    {
        $chatRequest = ChatRequest::findOrFail($id);

        // Check if the request is still pending
        if ($chatRequest->status == 'pending') {
            $chatRequest->status = $status;
            $chatRequest->staff_id = $staff_id;
            $chatRequest->save();
            return $chatRequest;
        } else {
            return null;
        }
    }

    public function endChatRequests($id)
    {
        $chatRequest = ChatRequest::findOrFail($id);

        $chatRequest->status = 'end';
        $chatRequest->save();
        return $chatRequest;
    }
}
