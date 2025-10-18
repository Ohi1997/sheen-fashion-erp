<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use Illuminate\Support\Facades\Log;

class NotifyAdminOfOrder
{
    public function handle(OrderPlaced $event): void
    {
        Log::channel('stack')->info('Admin notified of order', [
            'order' => $event->order->number,
        ]);
    }
}
