<?php

namespace App\Repositories;

use App\Repositories\Interfaces\MembershipRepositoryInterface;
use App\Models\Membership;
use Illuminate\Support\Facades\Hash;

class MembershipRepository implements MembershipRepositoryInterface
{
    public function allMembership()
    {
        return Membership::where('deleted_at', 0)
        ->orderBy('totalAmount_spent', 'asc')
        ->get();
    }

    public function storeMembership($data)
    {
        return Membership::create($data);
    }

    public function findMembership($id)
    {
        return Membership::find($id);
    }

    public function findMembershipByLevel($level)
    {
        return Membership::where('level', $level)->first();
    }

    public function updateMembership($data, $id)
    {
        $membership = Membership::where('id', $id)->first();
        $membership->level = $data['level'];
        $membership->totalAmount_spent = $data['totalAmount_spent'];
        $membership->discount = $data['discount'];
        $membership->save();
    }

    public function destroyMembership($id)
    {
        $membership = Membership::find($id);
        $membership->deleted_at = 1;
        $membership->save();
    }
}
