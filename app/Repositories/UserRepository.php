<?php

namespace App\Repositories;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function allUser()
    {
        return User::where('deleted_at', 0)
        ->where('role', 'user')
        ->get();
    }

    public function allStaff()
    {
        return User::where('deleted_at', 0)
        ->where('role', 'staff')
        ->get();
    }

    public function storeUser($data)
    {
        return User::create($data);
    }

    public function updateUser($data, $id)
    {
        $user = User::where('id', $id)->first();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->gender = $data['gender'];
        $user->address = $data['address'];
        $user->role = $data['role'];
        if($data['image'] != ''){
            $user->image = $data['image'];
        }
        $user->phone_number = $data['phone_number'];
        $user->save();
    }

    public function updateUserMembership($level, $id)
    {
        $user = User::where('id', $id)->first();
        $user->membership_level = $level;
        $user->save();
    }

    public function updateUserLoginTime($id)
    {
        $user = User::where('id', $id)->first();
        $user->last_login_at = now();
        $user->save();
    }

    public function updateUserTotalSpent($total_spent, $id)
    {
        $user = User::where('id', $id)->first();
        $user->total_spent += $total_spent;
        $user->save();
    }

    public function updateUserRewardPoint($point, $id)
    {
        $user = User::where('id', $id)->first();
        $user->reward_point += $point;
        $user->save();
    }

    public function destroyUser($id)
    {
        $user = User::find($id);
        $user->deleted_at = 1;
        $user->save();
    }

    public function findUser($id)
    {
        return User::find($id);
    }

    public function findUserByEmail($email)
    {
        return User::where(['email'=>$email])->first();
    }

    public function password_reset($data)
    {
        $user = User::where('email', $data['email'])->first();
        $user->password = Hash::make($data['password']);
        $user->save();
    }

    public function edit_password($data, $id)
    {
        $user = User::find($id);
        $user->password = Hash::make($data['password']);
        $user->save();
    }

    public function userDemographic_report()
    {
        return User::selectRaw('gender, COUNT(*) as count')
        ->groupBy('gender')
        ->get();
    }
}
