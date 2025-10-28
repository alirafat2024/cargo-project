<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShipmentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        $query = Shipment::with(['customer', 'branch']);
        
        if ($user->isBranchUser()) {
            $query->where('branch_id', $user->branch_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $shipments = $query->latest()->get();

        return response()->json($shipments);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'branch_id' => 'required|exists:branches,id',
            'product_name' => 'required|string|max:255',
            'product_description' => 'nullable|string',
            'weight' => 'nullable|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'sender_address' => 'required|string',
            'receiver_address' => 'required|string',
            'receiver_name' => 'required|string|max:255',
            'receiver_phone' => 'required|string|max:50',
            'shipping_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $user = $request->user();
        if ($user->isBranchUser() && $validated['branch_id'] != $user->branch_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated['tracking_number'] = 'TRK-' . strtoupper(Str::random(10));
        $validated['status'] = 'not_sent';

        $shipment = Shipment::create($validated);

        return response()->json($shipment->load(['customer', 'branch']), 201);
    }

    public function show(Request $request, Shipment $shipment)
    {
        $user = $request->user();
        
        if ($user->isBranchUser() && $shipment->branch_id !== $user->branch_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($shipment->load(['customer', 'branch']));
    }

    public function update(Request $request, Shipment $shipment)
    {
        $user = $request->user();
        
        if ($user->isBranchUser() && $shipment->branch_id !== $user->branch_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'product_name' => 'string|max:255',
            'product_description' => 'nullable|string',
            'weight' => 'nullable|numeric|min:0',
            'price' => 'numeric|min:0',
            'status' => 'in:not_sent,in_transit,delivered',
            'sender_address' => 'string',
            'receiver_address' => 'string',
            'receiver_name' => 'string|max:255',
            'receiver_phone' => 'string|max:50',
            'shipping_date' => 'nullable|date',
            'delivery_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $shipment->update($validated);

        return response()->json($shipment->load(['customer', 'branch']));
    }

    public function destroy(Request $request, Shipment $shipment)
    {
        $user = $request->user();
        
        if ($user->isBranchUser() && $shipment->branch_id !== $user->branch_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        $shipment->delete();

        return response()->json(['message' => 'Shipment deleted successfully']);
    }
}
