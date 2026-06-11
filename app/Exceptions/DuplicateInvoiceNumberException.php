<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class DuplicateInvoiceNumberException extends Exception
{
    public function __construct(string $number)
    {
        parent::__construct("Číslo faktúry [{$number}] je už použité.");
    }
}
