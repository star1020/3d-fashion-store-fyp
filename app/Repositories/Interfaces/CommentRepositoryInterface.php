<?php
namespace App\Repositories\Interfaces;

Interface CommentRepositoryInterface{
    public function allComment();
    public function allCommentByProductId($product_id);
    public function updateLikes($data, $id);
    public function storeComment($data);
    public function findComment($id);
    public function updateComment($data, $id);
    public function destroyComment($id);
    public function weeklyReview();
    public function weeklyReviewPercentageChange();
    public function weeklyReviewChart();
    public function commentAnalysis_report();
    
}