<?php

declare(strict_types=1);

namespace App\Http\Requests\Shop;

use App\Enums\DeliveryMethod;
use App\Enums\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckoutRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_email' => ['required', 'email', 'max:180'],
            'customer_phone' => ['required', 'string', 'max:40'],
            'delivery_method' => ['required', Rule::enum(DeliveryMethod::class)],
            'delivery_city' => ['required', 'string', 'max:120'],
            'delivery_address' => ['required', 'string', 'max:255'],
            'delivery_postcode' => ['nullable', 'string', 'max:12'],
            'payment_method' => ['required', Rule::enum(PaymentMethod::class)],
            'notes' => ['nullable', 'string', 'max:1000'],
            'terms' => ['accepted'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'customer_name' => 'име',
            'customer_email' => 'имейл',
            'customer_phone' => 'телефон',
            'delivery_method' => 'начин на доставка',
            'delivery_city' => 'населено място',
            'delivery_address' => 'адрес',
            'payment_method' => 'начин на плащане',
            'terms' => 'общите условия',
        ];
    }
}
