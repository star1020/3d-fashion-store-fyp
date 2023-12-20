<?php
namespace App\Repositories\Interfaces;

Interface VisitorRepositoryInterface{
    
    public function allVisitor();
    public function storeVisitor($data);
    public function findVisitor($id);
    public function findVisitorIP($ip);
    public function updateVisitor($data); 
    public function destroyVisitor($id);
    public function weeklyVisitor($weeksAgo);
    public function weeklyVisitorPercentageChange();
    public function weeklyVisitChart();

}