<?php

namespace App\Exceptions;

use Exception;

class DuplicateInvoiceNumberException extends Exception
{
    public function __construct(string $number)
    {
        parent::__construct("Invoice number [{$number}] is already used.");
    }
}
