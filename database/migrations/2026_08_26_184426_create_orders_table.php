<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->string('number')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('pending');

            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');

            $table->string('delivery_method');
            $table->string('delivery_city');
            $table->string('delivery_address');
            $table->string('delivery_postcode')->nullable();
            $table->text('notes')->nullable();

            $table->string('payment_method');
            $table->unsignedInteger('subtotal_minor');
            $table->unsignedInteger('shipping_minor');
            $table->unsignedInteger('total_minor');
            $table->string('currency', 3)->default('EUR');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
