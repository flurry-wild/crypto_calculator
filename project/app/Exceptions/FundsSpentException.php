<?php

namespace App\Exceptions;

use Exception;

class FundsSpentException extends Exception
{
    protected $message = 'Unable to delete. Funds spent.';
}
