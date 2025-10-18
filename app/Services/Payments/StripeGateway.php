<?php

namespace App\Services\Payments;

use App\Models\Order;
use Stripe\StripeClient;

class StripeGateway implements PaymentGateway
{
    public function __construct(private StripeClient $client)
    {
    }

    public function charge(Order $order, array $paymentData): string
    {
        $intent = $this->client->paymentIntents->create([
            'amount' => (int) ($order->grand_total * 100),
            'currency' => strtolower($order->currency ?? 'USD'),
            'confirm' => true,
            'payment_method' => $paymentData['payment_method'] ?? null,
            'metadata' => [
                'order_number' => $order->number,
            ],
        ]);

        return $intent->id;
    }
}
