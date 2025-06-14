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
        Schema::table('product_sizes', function (Blueprint $table) {
            // Drop foreign key constraints first
            $table->dropForeign(['product_id']);
            $table->dropForeign(['size_id']);
        });

        Schema::table('product_sizes', function (Blueprint $table) {
            // Then drop the columns
            $table->dropColumn('product_id');
            $table->dropColumn('size_id');
        });

        Schema::table('product_sizes', function (Blueprint $table) {
            // Now add the columns with new foreign keys and cascade rules
            $table->foreignId('product_id')->constrained('products')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('size_id')->constrained('sizes')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('product_sizes', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropForeign(['size_id']);
        });

        Schema::table('product_sizes', function (Blueprint $table) {
            $table->dropColumn('product_id');
            $table->dropColumn('size_id');
        });

        Schema::table('product_sizes', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained('products')->cascadeOnUpdate();
            $table->foreignId('size_id')->constrained('sizes')->cascadeOnUpdate();
        });
    }
};
