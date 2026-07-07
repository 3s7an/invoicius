<?php

declare(strict_types=1);

namespace App\Enums;

enum AutomatizationType: string
{
    case InvoiceAutoGen = 'invoice_auto_gen';
    case InvoiceReport = 'invoice_report';
    case InvoiceDueReminder = 'invoice_due_reminder';
    case InvoiceStatusAutoUpdate = 'invoice_status_auto_update';

    public function label(): string
    {
        return __("automatization.types.{$this->value}.label");
    }

    public function icon(): string
    {
        return match ($this) {
            self::InvoiceAutoGen => 'pi-file',
            self::InvoiceReport => 'pi-chart-bar',
            self::InvoiceDueReminder => 'pi-bell',
            self::InvoiceStatusAutoUpdate => 'pi-sync',
        };
    }

    public function description(): string
    {
        return __("automatization.types.{$this->value}.description");
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
        return $this === self::InvoiceDueReminder || $this === self::InvoiceStatusAutoUpdate;
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
     *   icon: string,
     *   description: string,
     *   requires_recipient: bool,
     *   requires_due_offset_days: bool,
     *   requires_item_names: bool,
     *   uses_daily_schedule: bool,
     * }>
     */
    public static function optionsForFrontend(): array
    {
        return array_map(
            fn (self $type): array => [
                'value' => $type->value,
                'label' => $type->label(),
                'icon' => $type->icon(),
                'description' => $type->description(),
                'requires_recipient' => $type->requiresRecipient(),
                'requires_due_offset_days' => $type->requiresDueOffsetDays(),
                'requires_item_names' => $type->requiresItemNames(),
                'uses_daily_schedule' => $type->usesDailySchedule(),
            ],
            self::cases(),
        );
    }
}
