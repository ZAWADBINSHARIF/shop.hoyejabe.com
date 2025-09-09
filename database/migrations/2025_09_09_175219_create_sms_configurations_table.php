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
        Schema::create('sms_configurations', function (Blueprint $table) {
            $table->id();
            $table->enum('active_provider', ['smsq', 'bulksmsbd'])->default('smsq');
            $table->text('smsq_api_key')->nullable();
            $table->string('smsq_client_id')->nullable();
            $table->string('smsq_sender_id')->nullable();
            $table->text('bulksmsbd_api_key')->nullable();
            $table->string('bulksmsbd_sender_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_configurations');
    }
};