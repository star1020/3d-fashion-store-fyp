<?php
namespace App\Repositories\Interfaces;

Interface ChatbotRepositoryInterface{
    public function allFAQ();
    public function storeFAQ($data);
    public function findFAQ($id);
    public function updateFAQ($data, $id);
    public function destroyFAQ($id);
    
}