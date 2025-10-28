<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'tracking_number',
        'customer_id',
        'branch_id',
        'product_name',
        'product_description',
        'weight',
        'price',
        'status',
        'sender_address',
        'receiver_address',
        'receiver_name',
        'receiver_phone',
        'shipping_date',
        'delivery_date',
        'notes',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'price' => 'decimal:2',
        'shipping_date' => 'date',
        'delivery_date' => 'date',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function isNotSent()
    {
        return $this->status === 'not_sent';
    }

    public function isInTransit()
    {
        return $this->status === 'in_transit';
    }

    public function isDelivered()
    {
        return $this->status === 'delivered';
    }
}
