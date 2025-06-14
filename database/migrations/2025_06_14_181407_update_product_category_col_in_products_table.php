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
        Schema::table('products', function (Blueprint $table) {
            // Make sure the foreign key is dropped before dropping the column
            if (Schema::hasColumn('products', 'product_category')) {
                $table->dropForeign(['product_category']);
                $table->dropColumn('product_category');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('product_category')
                ->constrained('product_categories')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop the modified FK first
            $table->dropForeign(['product_category']);
            $table->dropColumn('product_category');
        });

        Schema::table('products', function (Blueprint $table) {
            // Restore original FK behavior
            $table->foreignId('product_category')
                ->constrained('product_categories')
                ->cascadeOnUpdate();
        });
    }
};
