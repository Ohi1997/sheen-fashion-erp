@component('mail::message')
# Order Confirmed

Thank you for your purchase! Your order number is **{{ $order->number }}**.

@component('mail::table')
| Item | Qty | Total |
| :--- | ---: | ---: |
@foreach ($order->items as $item)
| {{ $item->name_snapshot }} | {{ $item->quantity }} | ${{ number_format($item->total, 2) }} |
@endforeach
@endcomponent

**Grand Total:** ${{ number_format($order->grand_total, 2) }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
