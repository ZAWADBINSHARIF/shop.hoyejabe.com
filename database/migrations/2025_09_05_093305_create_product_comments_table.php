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
        Schema::create('product_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->text('comment');
            $table->tinyInteger('rating')->unsigned()->nullable()->comment('Rating from 1 to 5');
            $table->boolean('is_verified_purchase')->default(false);
            $table->boolean('is_approved')->default(false)->comment('Admin approval status');
            $table->boolean('is_visible')->default(true);
            $table->string('customer_name')->nullable()->comment('Cached customer name for display');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            
            $table->index(['product_id', 'is_approved', 'is_visible']);
            $table->index('customer_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_comments');
    }
};
