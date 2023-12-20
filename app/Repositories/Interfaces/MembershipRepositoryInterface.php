<?php
namespace App\Repositories\Interfaces;

Interface MembershipRepositoryInterface{
    public function allMembership();
    public function storeMembership($data);
    public function findMembership($id);
    public function findMembershipByLevel($level);
    public function updateMembership($data, $id);
    public function destroyMembership($id);

}