<?php

return [
    'types' => [
        'invoice_auto_gen' => [
            'label' => 'Automatic invoice generation',
            'description' => 'An invoice is created automatically on the chosen day.',
        ],
        'invoice_report' => [
            'label' => 'Monthly invoice report',
            'description' => 'A summary of last month\'s billing sent by email.',
        ],
        'invoice_due_reminder' => [
            'label' => 'Due date reminder',
            'description' => 'Reminds the client that an invoice is due.',
        ],
        'invoice_status_auto_update' => [
            'label' => 'Automatic invoice status update',
            'description' => 'Overdue invoices with the "Sent" status are automatically marked as "Overdue".',
        ],
    ],
];
