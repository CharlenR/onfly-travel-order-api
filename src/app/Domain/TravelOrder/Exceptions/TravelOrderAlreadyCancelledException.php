<?php

namespace App\Domain\TravelOrder\Exceptions;

class TravelOrderAlreadyCancelledException extends TravelOrderException
{
    protected $message = 'TravelOrder already canceled';
}