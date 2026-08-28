<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Powers the composition panel — the site's central argument.
 *
 * `is_added` is what turns "nothing added" from a slogan into a queryable
 * fact: a product with no added ingredients cannot silently acquire one
 * without the claim on the page changing with it.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_ingredients', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->decimal('percentage', 5, 2)->nullable();
            $table->boolean('is_added')->default(false);
            $table->unsignedSmallInteger('position')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_ingredients');
    }
};
