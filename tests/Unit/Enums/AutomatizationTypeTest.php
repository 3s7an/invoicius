<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\AutomatizationType;
use PHPUnit\Framework\TestCase;

class AutomatizationTypeTest extends TestCase
{
    public function test_values_match_database_strings(): void
    {
        $this->assertSame('invoice_auto_gen', AutomatizationType::InvoiceAutoGen->value);
        $this->assertSame('invoice_report', AutomatizationType::InvoiceReport->value);
        $this->assertSame('invoice_due_reminder', AutomatizationType::InvoiceDueReminder->value);
    }

    public function test_options_for_frontend_include_labels_and_flags(): void
    {
        $options = AutomatizationType::optionsForFrontend();

        $this->assertCount(3, $options);
        $this->assertSame(
            AutomatizationType::InvoiceAutoGen->label(),
            collect($options)->firstWhere('value', AutomatizationType::InvoiceAutoGen->value)['label'],
        );
        $this->assertTrue(
            collect($options)->firstWhere('value', AutomatizationType::InvoiceAutoGen->value)['requires_recipient'],
        );
        $this->assertTrue(
            collect($options)->firstWhere('value', AutomatizationType::InvoiceDueReminder->value)['uses_daily_schedule'],
        );
    }
}
