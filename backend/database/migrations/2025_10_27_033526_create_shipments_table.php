<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number')->unique();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->string('product_name');
            $table->text('product_description')->nullable();
            $table->decimal('weight', 10, 2)->nullable();
            $table->decimal('price', 10, 2);
            $table->enum('status', ['not_sent', 'in_transit', 'delivered'])->default('not_sent');
            $table->text('sender_address');
            $table->text('receiver_address');
            $table->string('receiver_name');
            $table->string('receiver_phone');
            $table->date('shipping_date')->nullable();
            $table->date('delivery_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
