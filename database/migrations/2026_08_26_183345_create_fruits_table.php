<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fruit as a first-class facet, so a visitor can browse "арония" across juices,
 * nectars, syrups and teas — something the current shop cannot do at all.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fruits', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_signature')->default(false);
            $table->unsignedSmallInteger('position')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fruits');
    }
};
