<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        if ($user->isAdmin()) {
            $customers = Customer::with('branch')->get();
        } else {
            $customers = Customer::where('branch_id', $user->branch_id)
                ->with('branch')
                ->get();
        }

        return response()->json($customers);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'branch_id' => 'required|exists:branches,id',
        ]);

        $user = $request->user();
        if ($user->isBranchUser() && $validated['branch_id'] != $user->branch_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $customer = Customer::create($validated);

        return response()->json($customer->load('branch'), 201);
    }

    public function show(Request $request, Customer $customer)
    {
        $user = $request->user();
        
        if ($user->isBranchUser() && $customer->branch_id !== $user->branch_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($customer->load(['branch', 'shipments']));
    }

    public function update(Request $request, Customer $customer)
    {
        $user = $request->user();
        
        if ($user->isBranchUser() && $customer->branch_id !== $user->branch_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'string|max:255',
            'phone' => 'string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
        ]);

        $customer->update($validated);

        return response()->json($customer);
    }

    public function destroy(Request $request, Customer $customer)
    {
        $user = $request->user();
        
        if ($user->isBranchUser() && $customer->branch_id !== $user->branch_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        $customer->delete();

        return response()->json(['message' => 'Customer deleted successfully']);
    }
}
