<?php

return [
    'types' => [
        'invoice_auto_gen' => [
            'label' => 'Automatické generovanie faktúr',
            'description' => 'Faktúra sa vytvorí automaticky v zvolený deň.',
        ],
        'invoice_report' => [
            'label' => 'Mesačný report faktúr',
            'description' => 'Zhrnutie fakturácie za uplynulý mesiac zasielané e-mailom.',
        ],
        'invoice_due_reminder' => [
            'label' => 'Upozornenie na splatnosť',
            'description' => 'Upozornenie klienta na splatnosť faktúry.',
        ],
        'invoice_status_auto_update' => [
            'label' => 'Automatická zmena stavu faktúry',
            'description' => 'Faktúry po splatnosti so stavom „Odoslaná“ sa automaticky označia ako „Po splatnosti“.',
        ],
    ],
];
