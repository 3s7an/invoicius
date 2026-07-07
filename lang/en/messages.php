<?php

return [
    'invoice_created' => 'Invoice was created.',
    'invoice_updated' => 'Invoice was updated.',
    'invoice_status_updated' => 'Invoice status was updated.',
    'invoice_deleted' => 'Invoice was deleted.',
    'invoice_number_taken' => 'An invoice with this number already exists. Please try again.',
    'invoice_number_exists' => 'Invoice number already exists.',
    'duplicate_invoice_number' => 'Invoice number [:number] is already in use.',

    'recipient_created' => 'Recipient was created.',
    'recipient_updated' => 'Recipient was updated.',
    'recipient_deleted' => 'Recipient was deleted.',
    'recipient_not_owned' => 'The selected recipient does not belong to your account.',

    'automatization_created' => 'Automatization was created.',
    'automatization_updated' => 'Automatization was updated.',
    'automatization_deleted' => 'Automatization was deleted.',
    'automatization_already_processed' => 'The automatization has already been processed.',
    'automatization_missing_user' => 'The automatization has no assigned user.',
    'automatization_missing_recipient' => 'The automatization has no assigned recipient.',
    'automatization_missing_user_or_recipient' => 'The automatization\'s user or recipient could not be found.',

    'payment_recipient_fallback' => 'Payment recipient',
    'payment_invoice_prefix' => 'Invoice :number',
    'payment_reference_fallback' => 'Invoice payment',
];
