<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Repositories\Interfaces\MembershipRepositoryInterface;

class MembershipController extends Controller
{
    private $membershipRepository;

    public function __construct(MembershipRepositoryInterface $membershipRepository)
    {
        $this->membershipRepository = $membershipRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $memberships = $this->membershipRepository->allMembership();
        return view('admin/all-membership', compact('memberships'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin/add-membership');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'level' => 'required|string|max:255|unique:memberships',
            'totalAmount_spent' => 'required|numeric|min:0',
            'discount' => 'required|numeric|between:0,100',
        ]);

        $data = [
            'level' => $request->level,
            'totalAmount_spent' => $request->totalAmount_spent,
            'discount' => $request->discount,
        ];
        $this->membershipRepository->storeMembership($data);
        return redirect()->route('memberships.index')->with('success', 'Successfully added a membership');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $membership = $this->membershipRepository->findMembership($id);
        return view('admin/edit-membership', compact('membership'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'level' => [
                'required',
                'string',
                'max:255',
                Rule::unique('memberships')->ignore($id)
            ],
            'totalAmount_spent' => 'required|numeric|min:0',
            'discount' => 'required|numeric|between:0,100',
        ]);

        $data = [
            'level' => $request->level,
            'totalAmount_spent' => $request->totalAmount_spent,
            'discount' => $request->discount,
        ];
        $this->membershipRepository->updateMembership($data, $id);

        return redirect()->route('memberships.index')->with('success', 'Information has been updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->membershipRepository->destroyMembership($id);
        return redirect()->route('memberships.index')->with('success', 'Information has been deleted');
    }
}
