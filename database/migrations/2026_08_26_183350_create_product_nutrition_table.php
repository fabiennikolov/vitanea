<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_nutrition', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('kcal_per_100')->nullable();
            $table->unsignedInteger('kj_per_100')->nullable();
            $table->decimal('fruit_content_percent', 5, 2)->nullable();
            $table->decimal('sugars_per_100', 6, 2)->nullable();
            $table->decimal('carbohydrates_per_100', 6, 2)->nullable();
            $table->timestamps();

            $table->unique('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_nutrition');
    }
};
