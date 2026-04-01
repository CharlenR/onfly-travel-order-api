<?php

namespace App\Models;

use App\Enums\TravelOrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Domain\TravelOrder\Exceptions\TravelOrderAlreadyApprovedException;
use App\Domain\TravelOrder\Exceptions\TravelOrderAlreadyCancelledException;
use App\Domain\TravelOrder\Exceptions\InvalidTravelOrderStatusTransitionException;

class TravelOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'requester_name',
        'destination',
        'departure_date',
        'return_date',
        'status'
    ];

    protected $casts = [
        'status' => TravelOrderStatus::class,
        'departure_date' => 'date',
        'return_date' => 'date',
    ];

    protected $attributes = [
        'status' => 'requested',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approve()
    {
        if ($this->status === TravelOrderStatus::APPROVED) {
            throw new TravelOrderAlreadyApprovedException();
        }

        $this->status = TravelOrderStatus::APPROVED;
    }

    public function cancel()
    {
        if ($this->status === TravelOrderStatus::APPROVED) {
            throw new InvalidTravelOrderStatusTransitionException(TravelOrderStatus::APPROVED, TravelOrderStatus::CANCELED);
        }

        if ($this->status === TravelOrderStatus::CANCELED) {
            throw new TravelOrderAlreadyCancelledException();
        }

        $this->status = TravelOrderStatus::APPROVED;
    }
}
