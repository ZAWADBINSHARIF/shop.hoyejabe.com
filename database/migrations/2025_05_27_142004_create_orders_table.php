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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_mobile');
            $table->string('city');
            $table->string('address')->nullable();
            $table->string('upazila')->nullable();
            $table->string('thana')->nullable();
            $table->string('post_code')->nullable();
            $table->foreignId('selected_shipping_area')->constrained('shipping_costs');
            $table->decimal('shipping_cost', 10, 2);
            $table->decimal('extra_shipping_cost', 10, 2)->default(0);
            $table->decimal('total_price', 10, 2);
            $table->string('order_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
