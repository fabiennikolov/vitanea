<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Product types — Сокове, Нектари, Сиропи, Пектини, Чайове.
 *
 * Deliberately NOT the old shop's syrup subcategories, which split by dilution
 * ratio (1:6, 1:7, 1:10, 1:13) and made it impossible to browse a flavour
 * across formats. Dilution is a variant attribute here; flavour is a fruit.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('position')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
