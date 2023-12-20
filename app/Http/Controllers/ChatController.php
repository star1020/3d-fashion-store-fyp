<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\PusherBroadcast;
use App\Repositories\Interfaces\ChatRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    private $chatRepository;

    public function __construct(ChatRepositoryInterface $chatRepository)
    {
        $this->chatRepository = $chatRepository;
    }

    public function index() {
        return view('admin/chat');
    }

    public function requestLiveChat() {
        event(new PusherBroadcast('Live chat request', 'my-channel', 'my-event'));
        $data = [
            'user_id' => auth()->user()->id,
            'status' => 'pending',
        ];
        $this->chatRepository->storeChatRequest($data);
        return response()->json(['message' => 'Live chat request dispatched']);
    }

    public function getChatRequestState() {
        $pendingRequests = $this->chatRepository->getChatRequests();

        $pendingRequestsData = $pendingRequests->map(function ($request) {
            return [
                'id' => $request->id,
                'user_id' => $request->user->id,
                'username' => $request->user->name,
                'image' => asset('user/images/profile_image/' . $request->user->image),
                'request_time' => $request->created_at->format('H:i'),
            ];
        });

        return response()->json([
            'hasPendingRequests' => $pendingRequests->isNotEmpty(),
            'pendingRequests' => $pendingRequestsData
        ]);
    }

    public function updateChatRequestState(Request $request) {
        $staff_id = auth()->user()->id;
        $updatedRequest = $this->chatRepository->updateChatRequests($request->id, $request->status, $staff_id);
    
        if ($updatedRequest) {
            // Return a success response
            $activeRequests = $this->chatRepository->getChatRequestsById($request->id);

            $activeRequestsData = $activeRequests->map(function ($request) {
                return [
                    'username' => $request->user->name,
                    'image' => asset('user/images/profile_image/' . $request->user->image),
                    'email' => $request->user->email,
                ];
            });

            if ($updatedRequest->status == 'active') {
                $data = [
                    'staffId' => $staff_id,
                    'staffName' => auth()->user()->name,
                    'staffImage' => asset('user/images/profile_image/' . auth()->user()->image),
                    'message' => 'accepted'
                ];
        
                event(new PusherBroadcast($data, 'user-channel-'.$request->user_id, 'user-event-'.$request->user_id));
            }

            return response()->json([
                'message' => 'Chat request status updated successfully', 
                'status' => $updatedRequest->status,
                'activeRequests' => $activeRequestsData
            ]);
        } else {
            // Return an error response if something goes wrong
            return response()->json(['message' => 'Chat request is no longer pending or does not exist'], 422);
        }
    }

    public function liveChatUploadImage(Request $request)
    {
        $urls = [];
    
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                try {
                    $filename = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('public/livechat', $filename);
                    $urls[] = Storage::url($path);
                } catch (\Exception $e) {
                    \Log::error("File upload error: " . $e->getMessage());
                }
            }
        }
    
        return response()->json(['urls' => $urls]);
    }

    public function liveAgentResponse(Request $request)
    {
        event(new PusherBroadcast($request->input, 'user-channel-'.$request->user_id, 'user-event-'.$request->user_id));
        return response()->json(['success' => true]);
    }

    public function sendLiveChat(Request $request)
    {
        event(new PusherBroadcast($request->input, 'admin-channel-'.$request->staff_id, 'admin-event-'.$request->staff_id));
        return response()->json(['success' => true]);
    }

    public function endChatSession(Request $request)
    {
        $updatedRequest = $this->chatRepository->endChatRequests($request->id);
        $data = [
            'staffName' => auth()->user()->name,
            'message' => 'end'
        ];
        event(new PusherBroadcast($data, 'user-channel-'.$request->user_id, 'user-event-'.$request->user_id));
        return response()->json(['success' => true]);
    }
}
