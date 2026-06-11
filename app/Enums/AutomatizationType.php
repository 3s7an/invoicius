<?php

declare(strict_types=1);

namespace App\Enums;

enum AutomatizationType: string
{
    case InvoiceAutoGen = 'invoice_auto_gen';
    case InvoiceReport = 'invoice_report';
    case InvoiceDueReminder = 'invoice_due_reminder';

    public function label(): string
    {
        return match ($this) {
            self::InvoiceAutoGen => 'Automatické generovanie faktúr',
            self::InvoiceReport => 'Mesačný report faktúr',
            self::InvoiceDueReminder => 'Upozornenie na splatnosť',
        };
    }

    public function requiresRecipient(): bool
    {
        return $this === self::InvoiceAutoGen;
    }

    public function requiresDueOffsetDays(): bool
    {
        return $this === self::InvoiceDueReminder;
    }

    public function requiresItemNames(): bool
    {
        return $this === self::InvoiceAutoGen;
    }

    public function usesDailySchedule(): bool
    {
        return $this === self::InvoiceDueReminder;
    }

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * @return list<array{
     *   value: string,
     *   label: string,
     *   requires_recipient: bool,
     *   requires_due_offset_days: bool,
     *   requires_item_names: bool,
     *   uses_daily_schedule: bool,
     * }>
     */
    public static function optionsForFrontend(): array
    {
        return array_map(
            fn (self $type) => [
                'value' => $type->value,
                'label' => $type->label(),
                'requires_recipient' => $type->requiresRecipient(),
                'requires_due_offset_days' => $type->requiresDueOffsetDays(),
                'requires_item_names' => $type->requiresItemNames(),
                'uses_daily_schedule' => $type->usesDailySchedule(),
            ],
            self::cases(),
        );
    }
}
