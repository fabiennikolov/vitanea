<x-mail::message>
# Благодарим за поръчката

Здравейте, {{ $order->customer_name }},

Получихме вашата поръчка **{{ $order->number }}**. Ще се свържем с вас при изпращане.

<x-mail::table>
| Продукт | Кол. | Сума |
|:--------|:----:|-----:|
@foreach ($order->items as $item)
| {{ $item->product_name }} ({{ $item->variant_label }}) | {{ $item->quantity }} | {{ $item->lineTotal()->eur() }} |
@endforeach
</x-mail::table>

**Междинна сума:** {{ $order->subtotal()->eur() }}
**Доставка:** {{ $order->shipping()->isZero() ? 'Безплатна' : $order->shipping()->eur() }}
**Общо:** {{ $order->total()->eur() }} ({{ $order->total()->bgn() }})

**Начин на плащане:** {{ $order->payment_method->label() }}
{{ $order->payment_method->description() }}

**Доставка до:** {{ $order->delivery_method->label() }}, {{ $order->delivery_city }}, {{ $order->delivery_address }}

Поздрави,
Витанеа
</x-mail::message>
