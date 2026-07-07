<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class DuplicateInvoiceNumberException extends Exception
{
    public function __construct(string $number)
    {
        parent::__construct(__('messages.duplicate_invoice_number', ['number' => $number]));
    }
}
