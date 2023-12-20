<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Membership;

class DowngradeMemberships extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:downgrade-memberships';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting membership downgrade process...');

        // Retrieve all memberships ordered by totalAmount_spent in ascending order
        $memberships = Membership::orderBy('totalAmount_spent', 'asc')->get();

        // Find users to downgrade
        $usersToDowngrade = User::where('last_login_at', '<', now()->subMonth())
                                ->whereNotNull('membership_level')
                                ->where(function($query) {
                                    $query->whereNull('downgraded_at')
                                        ->orWhere('downgraded_at', '<', now()->subMonth());
                                })
                                ->get();

        foreach ($usersToDowngrade as $user) {
            $currentMembershipIndex = $memberships->search(function ($membership) use ($user) {
                return $membership->level === $user->membership_level;
            });

            if ($currentMembershipIndex > 0) {
                $newMembershipLevel = $memberships[$currentMembershipIndex - 1]->level;
                $newMembershipTotalSpent = $memberships[$currentMembershipIndex - 1]->totalAmount_spent;
            } else {
                $newMembershipLevel = null;
                $newMembershipTotalSpent = 0;
            }

            // Update the user's membership level and set the downgraded_at timestamp
            $user->membership_level = $newMembershipLevel;
            $user->total_spent = $newMembershipTotalSpent;
            $user->downgraded_at = now(); // Track when the user was downgraded
            $user->save();

            // $this->info("User {$user->id} downgraded to {$newMembershipLevel}.");
        }
    }

}
