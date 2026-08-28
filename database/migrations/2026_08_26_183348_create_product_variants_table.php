<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The fix for the old shop's worst modelling problem: four separate products
 * all named "Арония плодов сироп със захар", distinguished only by an internal
 * id and a format string. Here they are one product with four variants.
 *
 * Prices are integer minor units — never floats.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('sku')->unique();
            $table->string('label');
            $table->unsignedInteger('size_value')->nullable();
            $table->string('size_unit')->nullable();
            $table->string('dilution')->nullable();
            $table->string('packaging')->nullable();
            $table->unsignedInteger('price_minor');
            $table->unsignedInteger('compare_at_price_minor')->nullable();
            $table->string('currency', 3)->default('EUR');
            $table->unsignedInteger('stock')->default(0);
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('position')->default(0);
            $table->timestamps();

            $table->index(['product_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
