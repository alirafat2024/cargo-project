<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\Shipment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $mainBranch = Branch::create([
            'name' => 'Main Branch',
            'code' => 'MAIN',
            'address' => '123 Main Street',
            'phone' => '+1234567890',
            'email' => 'main@cargo.com',
            'city' => 'Kabul',
            'is_active' => true,
        ]);

        $branch2 = Branch::create([
            'name' => 'Herat Branch',
            'code' => 'HERAT',
            'address' => '456 West Avenue',
            'phone' => '+1234567891',
            'email' => 'herat@cargo.com',
            'city' => 'Herat',
            'is_active' => true,
        ]);

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@cargo.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'branch_id' => null,
        ]);

        $branchUser1 = User::create([
            'name' => 'Main Branch User',
            'email' => 'main@cargo.com',
            'password' => Hash::make('password'),
            'role' => 'branch_user',
            'branch_id' => $mainBranch->id,
        ]);

        $branchUser2 = User::create([
            'name' => 'Herat Branch User',
            'email' => 'herat@user.com',
            'password' => Hash::make('password'),
            'role' => 'branch_user',
            'branch_id' => $branch2->id,
        ]);

        $customer1 = Customer::create([
            'name' => 'Ahmad Karimi',
            'phone' => '+93701234567',
            'email' => 'ahmad@example.com',
            'address' => 'Street 10, District 5',
            'city' => 'Kabul',
            'country' => 'Afghanistan',
            'branch_id' => $mainBranch->id,
        ]);

        $customer2 = Customer::create([
            'name' => 'Fatima Ahmadi',
            'phone' => '+93702234567',
            'email' => 'fatima@example.com',
            'address' => 'Street 15, District 3',
            'city' => 'Herat',
            'country' => 'Afghanistan',
            'branch_id' => $branch2->id,
        ]);

        Shipment::create([
            'tracking_number' => 'TRK-' . strtoupper(Str::random(10)),
            'customer_id' => $customer1->id,
            'branch_id' => $mainBranch->id,
            'product_name' => 'Electronics Package',
            'product_description' => 'Laptop and accessories',
            'weight' => 5.5,
            'price' => 1500.00,
            'status' => 'in_transit',
            'sender_address' => 'Kabul Main Office',
            'receiver_address' => 'Street 10, District 5, Kabul',
            'receiver_name' => 'Ahmad Karimi',
            'receiver_phone' => '+93701234567',
            'shipping_date' => now()->subDays(2),
            'delivery_date' => null,
            'notes' => 'Handle with care',
        ]);

        Shipment::create([
            'tracking_number' => 'TRK-' . strtoupper(Str::random(10)),
            'customer_id' => $customer2->id,
            'branch_id' => $branch2->id,
            'product_name' => 'Clothing Items',
            'product_description' => 'Traditional clothing',
            'weight' => 2.0,
            'price' => 500.00,
            'status' => 'not_sent',
            'sender_address' => 'Herat Branch Office',
            'receiver_address' => 'Street 15, District 3, Herat',
            'receiver_name' => 'Fatima Ahmadi',
            'receiver_phone' => '+93702234567',
            'shipping_date' => null,
            'delivery_date' => null,
        ]);

        Shipment::create([
            'tracking_number' => 'TRK-' . strtoupper(Str::random(10)),
            'customer_id' => $customer1->id,
            'branch_id' => $mainBranch->id,
            'product_name' => 'Books',
            'product_description' => 'Educational books',
            'weight' => 3.5,
            'price' => 200.00,
            'status' => 'delivered',
            'sender_address' => 'Kabul Main Office',
            'receiver_address' => 'Street 10, District 5, Kabul',
            'receiver_name' => 'Ahmad Karimi',
            'receiver_phone' => '+93701234567',
            'shipping_date' => now()->subDays(5),
            'delivery_date' => now()->subDays(1),
        ]);
    }
}
