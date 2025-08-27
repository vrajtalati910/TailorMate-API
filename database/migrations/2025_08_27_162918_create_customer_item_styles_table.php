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
        Schema::create('customer_item_styles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_item_id')->nullable()->constrained('customer_items')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('item_style_id')->nullable()->constrained('item_styles')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_item_styles');
    }
};
