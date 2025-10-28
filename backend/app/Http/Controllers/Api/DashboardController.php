<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Shipment;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function stats(Request $request)
    {
        $user = $request->user();
        
        if ($user->isAdmin()) {
            $stats = [
                'total_branches' => Branch::count(),
                'total_users' => User::count(),
                'total_customers' => Customer::count(),
                'total_shipments' => Shipment::count(),
                'not_sent' => Shipment::where('status', 'not_sent')->count(),
                'in_transit' => Shipment::where('status', 'in_transit')->count(),
                'delivered' => Shipment::where('status', 'delivered')->count(),
                'recent_shipments' => Shipment::with(['customer', 'branch'])
                    ->latest()
                    ->limit(10)
                    ->get(),
            ];
        } else {
            $stats = [
                'total_customers' => Customer::where('branch_id', $user->branch_id)->count(),
                'total_shipments' => Shipment::where('branch_id', $user->branch_id)->count(),
                'not_sent' => Shipment::where('branch_id', $user->branch_id)
                    ->where('status', 'not_sent')->count(),
                'in_transit' => Shipment::where('branch_id', $user->branch_id)
                    ->where('status', 'in_transit')->count(),
                'delivered' => Shipment::where('branch_id', $user->branch_id)
                    ->where('status', 'delivered')->count(),
                'recent_shipments' => Shipment::where('branch_id', $user->branch_id)
                    ->with(['customer', 'branch'])
                    ->latest()
                    ->limit(10)
                    ->get(),
            ];
        }

        return response()->json($stats);
    }
}
