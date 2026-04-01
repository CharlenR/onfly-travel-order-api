<?php

namespace App\Domain\TravelOrder\Exceptions;

class InvalidTravelOrderStatusTransitionException extends TravelOrderException
{
    public function __construct($from, $to)
    {
        parent::__construct("Is not possible change TravelOrder from {$from} to {$to}");
    }
}