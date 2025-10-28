<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        if ($user->isAdmin()) {
            $branches = Branch::withCount(['users', 'customers', 'shipments'])->get();
        } else {
            $branches = Branch::where('id', $user->branch_id)
                ->withCount(['users', 'customers', 'shipments'])
                ->get();
        }

        return response()->json($branches);
    }

    public function store(Request $request)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:branches',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'city' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $branch = Branch::create($validated);

        return response()->json($branch, 201);
    }

    public function show(Request $request, Branch $branch)
    {
        $user = $request->user();
        
        if ($user->isBranchUser() && $branch->id !== $user->branch_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($branch->load(['users', 'customers', 'shipments']));
    }

    public function update(Request $request, Branch $branch)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'string|max:255',
            'code' => 'string|max:50|unique:branches,code,' . $branch->id,
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'city' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $branch->update($validated);

        return response()->json($branch);
    }

    public function destroy(Request $request, Branch $branch)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        $branch->delete();

        return response()->json(['message' => 'Branch deleted successfully']);
    }
}
