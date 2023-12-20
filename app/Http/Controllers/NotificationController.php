<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\NotificationRepositoryInterface;

class NotificationController extends Controller
{
    private $notificationRepository;

    public function __construct(NotificationRepositoryInterface $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    public function markAsRead(Request $request) {
        $this->notificationRepository->updateNotificationReadAt($request->notificationId);
        return response()->json(['success' => 'Notification marked as read.']);
    }
    
    public function delete(Request $request) {
        $this->notificationRepository->destroyNotification($request->notificationId);
        return response()->json(['success' => 'Notification deleted.']);
    }

    public function getUserUnreadCount() {
        $unreadCount = $this->notificationRepository->getUserUnreadCount(auth()->user()->id);
        return response()->json(['unreadCount' => $unreadCount]);
    }
    

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
