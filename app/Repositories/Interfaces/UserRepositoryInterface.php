<?php
namespace App\Repositories\Interfaces;

Interface UserRepositoryInterface{
    public function allUser();
    public function allStaff();
    public function findUser($id);
    public function storeUser($data);
    public function updateUser($data, $id); 
    public function updateUserMembership($level, $id);
    public function updateUserLoginTime($id);
    public function updateUserTotalSpent($total_spent, $id);
    public function updateUserRewardPoint($point, $id);
    public function findUserByEmail($email);
    public function password_reset($data); 
    public function edit_password($data, $id); 
    public function destroyUser($id);
    public function userDemographic_report();

}