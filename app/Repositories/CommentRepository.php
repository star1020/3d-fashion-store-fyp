<?php

namespace App\Repositories;

use App\Repositories\Interfaces\CommentRepositoryInterface;
use App\Models\Comment;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CommentRepository implements CommentRepositoryInterface
{
    public function allComment()
    {
        return Comment::with('user')
        ->where('deleted_at', 0)
        ->get();
    }

    public function allCommentByProductId($product_id)
    {
        return Comment::with('user')
        ->where('deleted_at', 0)
        ->where('product_id', $product_id)
        ->get();
    }

    public function storeComment($data)
    {
        return Comment::create($data);
    }

    public function findComment($id)
    {
        return Comment::with('user')
        ->find($id);
    }

    public function updateComment($data, $id)
    {
        $comment = Comment::where('id', $id)->first();
        $comment->admin_reply = $data['admin_reply'];
        $comment->save();
    }

    public function updateLikes($data, $id)
    {
        $comment = Comment::where('id', $id)->first();
        $comment->likes = $data;
        $comment->save();
    }

    public function destroyComment($id)
    {
        $comment = Comment::find($id);
        $comment->deleted_at = 1;
        $comment->save();
    }

    public function weeklyReview($weeksAgo = 0)
    {
        $weeklyReviewCount = Comment::where('comments.deleted_at', 0)
        ->whereBetween('comments.created_at', [Carbon::now()->subWeeks($weeksAgo)->startOfWeek(), Carbon::now()->subWeeks($weeksAgo)->endOfWeek()])
        ->count();

        return $weeklyReviewCount;
    }

    public function weeklyReviewPercentageChange()
    {
        // Get the current week's review count
        $currentWeekCount = $this->weeklyReview();

        // Get the previous week's review count
        $previousWeekCount = $this->weeklyReview(1);

        // Calculate the percentage change in review count
        $percentageChange = 0;
        if ($previousWeekCount > 0) {
            $percentageChange = (($currentWeekCount - $previousWeekCount) / $previousWeekCount) * 100;
        }

        return round($percentageChange, 2);
    }

    public function weeklyReviewChart()
    {
        // Get the start and end dates of the current week (Monday to Sunday)
        $startDate = Carbon::now()->startOfWeek()->format('Y-m-d H:i:s');
        $endDate = Carbon::now()->endOfWeek()->format('Y-m-d H:i:s');

        // Get the comments for the current week and group them by the day of the week
        $comments = Comment::select(DB::raw("DATE_FORMAT(created_at,'%W') as dayOfWeek"), DB::raw('count(*) as count'))
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->groupBy('dayOfWeek')
                    ->get();
                            
        return $comments;
    }

    public function commentAnalysis_report()
    {
        return Comment::select('id', 'rating', DB::raw('IFNULL(JSON_LENGTH(JSON_EXTRACT(likes, "$.users_id")), 0) as num_likes'))
        ->orderBy('id')
        ->get();
    }

}
