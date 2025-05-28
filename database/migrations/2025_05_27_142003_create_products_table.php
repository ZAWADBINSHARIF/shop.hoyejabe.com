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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->json('images');
            $table->text('highlighted_description');
            $table->text('details_description')->nullable();
            $table->foreignId('product_category')->constrained('product_categories')->cascadeOnUpdate();
            $table->decimal('base_price', 10, 2);
            $table->decimal('extra_shipping_cost', 10, 2)->default(0);
            $table->boolean("published");
            $table->boolean("out_of_stock");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
