<?php

namespace App\Domain\TravelOrder\Exceptions;

class TravelOrderAlreadyApprovedException extends TravelOrderException
{
    protected $message = 'Order already approved!';
}