<?php
namespace App\Events;

use App\Models\TravelOrder;

class TravelOrderApproved
{
    public function __construct(
        public TravelOrder $travelOrder
    ) {}
}