<?php

namespace App\Listeners;

use App\Events\TravelOrderApproved;
use App\Notifications\OrderStatusChanged;

class SendTravelOrderApprovedNotification
{
    public function handle(TravelOrderApproved $event): void
    {
        $event->travelOrder
            ->user
            ->notify(new OrderStatusChanged($event->travelOrder));
    }
}
