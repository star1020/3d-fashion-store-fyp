<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\CommentRepositoryInterface;
use App\Repositories\Interfaces\NotificationRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;

class CommentController extends Controller
{
    private $commentRepository;
    private $userRepository;
    protected $notificationRepository;
    protected $productRepository;

    public function __construct(CommentRepositoryInterface $commentRepository, UserRepositoryInterface $userRepository, NotificationRepositoryInterface $notificationRepository, ProductRepositoryInterface $productRepository)
    {
        $this->commentRepository = $commentRepository;
        $this->userRepository = $userRepository;
        $this->notificationRepository = $notificationRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = $this->commentRepository->allComment();
        return view('admin/all-comment', compact('comments'));
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
        $images = array();
        $review_img = NULL;
        if ($files = $request->input('filepond')) {
            foreach ($files as $file) {
                $json_string = json_decode($file, true);
                $data_column = $json_string['data'];

                $image = base64_decode($data_column);
                $image_name = uniqid(rand(), false) . '.png';
                file_put_contents('../public/user/images/review/'.$image_name, $image);
                $images[] = $image_name;
            }
            $review_img = implode("|",$images);
        }

        $data = [
            'user_id' => auth()->user()->id,
            'payment_id' => $request->payment_id,
            'product_id' => $request->product_id,
            'rating' => $request->rate_value,
            'review' => $request->review,
            'image' => $review_img
        ];
        $this->commentRepository->storeComment($data);

        $currentProduct = $this->productRepository->find($request->product_id);
        $notificationData = [
            'user_id' => auth()->user()->id,
            'related_id' => $request->product_id,
            'type' => 'comment_add',
            'title' => 'New Comment Added',
            'body' => 'A new comment has been added to "' . $currentProduct->productName . '".',
            'image' => 'add-comment.png',
        ];
        $this->notificationRepository->storeNotification($notificationData);

        if(auth()->user()->membership_level) {
            $this->userRepository->updateUserRewardPoint(10, auth()->user()->id);
            $message = 'Review and Rating have been added. You have earned 10 reward points!';
        } else {
            $message = 'Review and Rating have been added.';
        }
        return redirect('user/payment-history')->with('add_review_message', $message);
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
        $comment = $this->commentRepository->findComment($id);
        return view('admin/edit-comment', compact('comment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'admin_reply' => 'required|string',
        ]);

        $oldComment = $this->commentRepository->findComment($id);

        $data = [
            'admin_reply' => $request->admin_reply,
        ];
        $this->commentRepository->updateComment($data, $id);

        if (is_null($oldComment->admin_reply)) {
            $currentProduct = $this->productRepository->find($request->productId);
            $notificationData = [
                'user_id' => $request->userId,
                'related_id' => $request->productId,
                'type' => 'admin_reply',
                'title' => 'Admin Response',
                'body' => 'Admin has responded to your comment on "' . $currentProduct->productName . '"',
                'image' => 'admin-reply.png',
            ];
            $this->notificationRepository->storeNotification($notificationData);
        }

        return redirect('admin/comments')->with('success', 'Information has been updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->commentRepository->destroyComment($id);
        return redirect('admin/comments')->with('success', 'Information has been deleted');
    }

    public function like($comment_id){
        $comment = $this->commentRepository->findComment($comment_id);
        $user_id = auth()->user()->id;
        if($comment->likes != NULL){
            $likes = $comment->likes;
            $user_index = array_search($user_id, $likes['users_id']);
            if($user_index !== false){
                array_splice($likes['users_id'], $user_index, 1);
            } else {
                $likes['users_id'][] = $user_id;
            }
            // Count the number of likes
            $num_likes = count($likes['users_id']); 
            $response = array('num_likes' => $num_likes);
        }else{
            $likes = array(
                'users_id'  =>  array($user_id)
            );
            $response = array('num_likes' => '1');
        }
        $this->commentRepository->updateLikes(json_encode($likes), $comment_id);
        return json_encode($response);
    }

    public function commentAnalysis_report()
    {
        $comments = $this->commentRepository->commentAnalysis_report();

        // return $comments;
        return view('admin/report-commentAnalysis', compact('comments'));
    }
}
