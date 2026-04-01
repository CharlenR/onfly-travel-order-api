<?php

namespace App\Enums;

enum TravelOrderStatus: string {
    case REQUESTED = 'requested';
    case APPROVED = 'approved';
    case CANCELED = 'canceled';
}
