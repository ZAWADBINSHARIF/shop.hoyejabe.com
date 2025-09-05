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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('phone_number')->unique();
            $table->string('email')->nullable()->unique();
            $table->string('city')->nullable();
            $table->string('upazila')->nullable();
            $table->string('thana')->nullable();
            $table->string('post_code')->nullable();
            $table->text('delivery_address')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            // Add indexes for commonly queried fields
            $table->index('phone_number');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
