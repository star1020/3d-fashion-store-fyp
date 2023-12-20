<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\RewardRepositoryInterface;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\RewardClaim;
use App\Models\Reward;


class RewardController extends Controller
{
    private $rewardRepository;

    public function __construct(RewardRepositoryInterface $rewardRepository)
    {
        $this->rewardRepository = $rewardRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rewards = $this->rewardRepository->allReward();
        return view('admin/all-reward', compact('rewards'));
    }

    public function indexRewardClaim()
    {
        $rewardClaims = $this->rewardRepository->allRewardClaim();
        return view('admin/all-rewardClaim', compact('rewardClaims'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin/add-reward');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:rewards',
            'description' => 'required|string|max:255',
            'filepond' => 'required',
            'points_required' => 'required|numeric|min:0',
            'quantity_available' => 'required|numeric|min:0',
        ]);

        $file = $request->input('filepond');
        if ($file) {
            $json_string = json_decode($file, true);
            $data_column = $json_string['data'];
            $image = base64_decode($data_column);
            $image_name = uniqid(rand(), false) . '.png';
            file_put_contents('../public/user/images/reward/'.$image_name, $image);
            $reward_image = $image_name;
        }

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'image' => $reward_image,
            'points_required' => $request->points_required,
            'quantity_available' => $request->quantity_available,
        ];
        $this->rewardRepository->storeReward($data);
        return redirect()->route('rewards.index')->with('success', 'Successfully added a membership');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $reward = $this->rewardRepository->findReward($id);
        return view('admin/edit-reward', compact('reward'));
    }

    public function editRewardClaim(string $id)
    {
        $rewardClaim = $this->rewardRepository->findRewardClaim($id);
        return view('admin/edit-rewardClaim', compact('rewardClaim'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('rewards')->ignore($id)
            ],
            'description' => 'required|string|max:255',
            'filepond' => 'required',
            'points_required' => 'required|numeric|min:0',
            'quantity_available' => 'required|numeric|min:0',
        ]);

        $reward = $this->rewardRepository->findReward($id);
        $reward_image = $reward->image;
        $file = $request->input('filepond');
        $json_string = json_decode($file, true);
        $data_column = $json_string['data'];
        $filename = $json_string['name'];
        if($filename != $reward->image) {
            $image = base64_decode($data_column);
            $image_name = uniqid(rand(), false) . '.png';
            file_put_contents('../public/user/images/reward/'.$image_name, $image);
            $reward_image = $image_name;
        }

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'image' => $reward_image,
            'points_required' => $request->points_required,
            'quantity_available' => $request->quantity_available,
        ];
        $this->rewardRepository->updateReward($data, $id);

        return redirect()->route('rewards.index')->with('success', 'Information has been updated');
    }

    public function updateRewardClaim(Request $request, string $id)
    {
        $request->validate([
            'current_address' => 'required|string|max:255',
            'delivery_address' => 'required|string|max:255',
            'status' => 'required',
        ]);

        $data = [
            'current_address' => $request->current_address,
            'delivery_address' => $request->delivery_address,
            'status' => $request->status,
        ];
        $this->rewardRepository->updateRewardClaim($data, $id);

        return redirect()->route('rewardClaims.index')->with('success', 'Information has been updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyRewardClaim(string $id)
    {
        $this->rewardRepository->destroyRewardClaim($id);
        return redirect()->route('rewardClaims.index')->with('success', 'Information has been deleted');
    }

    public function destroy(string $id)
    {
        $this->rewardRepository->destroyReward($id);
        return redirect()->route('rewards.index')->with('success', 'Information has been deleted');
    }

    public function showRewardAndHistory()
    {
        $rewards = $this->rewardRepository->allReward();
        $rewardHistory = $this->rewardRepository->userRewardHistory(auth()->user()->id);

        return view('user/redeem', [
            'rewards' => $rewards,
            'rewardHistory' => $rewardHistory
        ]);
    }

    public function redeem(Request $request, $rewardId) {
        $user = auth()->user();
        $reward = Reward::findOrFail($rewardId);
    
        if ($user->reward_point < $reward->points_required) {
            return redirect()->route('reward')->with('redeem_reward_error', 'Not enough points to redeem this reward.');
        }
    
        if ($reward->quantity_available <= 0) {
            return redirect()->route('reward')->with('redeem_reward_error', 'Reward is no longer available.');
        }
    
        $user->reward_point -= $reward->points_required;
        $user->save();
    
        $reward->quantity_available -= 1;
        $reward->save();
    
        RewardClaim::create([
            'user_id' => $user->id,
            'reward_id' => $reward->id,
            'current_address' => '18, Jalan USJ 7/3c, Usj 7, 47610 Subang Jaya, Selangor',
            'delivery_address' => $request->address,
            'status' => 'confirmed',
        ]);
    
        return redirect()->route('reward')->with('redeem_reward_success', 'Reward redeemed successfully.');
    }

    public function deductPoints(Request $request) {
        $user = auth()->user();
        $pointsToDeduct = $request->input('points');
        
        if ($user->reward_point >= $pointsToDeduct) {
            $user->reward_point -= $pointsToDeduct;
            $user->save();
            return response()->json(['success' => true, 'newPoints' => $user->reward_point]);
        }
    
        return response()->json(['success' => false, 'error' => 'Not enough points']);
    }

    public function updatePoints(Request $request) {
        $user = auth()->user();
        $pointsToUpdate = $request->input('points');
    
        // Update logic
        $newPoints = $user->reward_point + $pointsToUpdate;
        if ($newPoints < 0) {
            $newPoints = 0; // Ensure points don't go negative
        }
    
        $user->reward_point = $newPoints;
        $user->save();
    
        return response()->json(['success' => true, 'newPoints' => $user->reward_point]);
    }

    public function deliveryTracking(Request $request) {
        $reward = $this->rewardRepository->findRewardClaim($request->claimId);
        return response()->json(['startAddress' => $reward->current_address, 'endAddress' => $reward->delivery_address]);
    }
    
}
