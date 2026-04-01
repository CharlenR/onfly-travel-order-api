<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use App\Events\TravelOrderApproved;
use App\Listeners\SendTravelOrderApprovedNotification;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        TravelOrderApproved::class => [
            SendTravelOrderApprovedNotification::class,
        ],
    ];
}