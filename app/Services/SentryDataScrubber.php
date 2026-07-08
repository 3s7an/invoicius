<?php

declare(strict_types=1);

namespace App\Services;

use Sentry\Breadcrumb;
use Sentry\Event;

/**
 * Strips billing PII (IBAN, DIC/ICO, client emails, amounts, ...) that the
 * Sentry SDK would otherwise capture verbatim from request bodies and
 * breadcrumbs, since Invoicius is a billing system operating under GDPR.
 */
class SentryDataScrubber
{
    /** @var list<string> */
    private const SENSITIVE_KEYS = [
        'iban',
        'ico',
        'dic',
        'ic_dph',
        'recipient_ico',
        'recipient_dic',
        'recipient_ic_dph',
        'varsym',
        'email',
        'recipient_name',
        'recipient_street',
        'recipient_street_num',
        'recipient_city',
        'recipient_state',
        'total_price',
        'vat_price',
        'wo_vat_price',
        'notes',
        'password',
        'password_confirmation',
        'current_password',
    ];

    private const MASK = '[Filtered]';

    public function __invoke(Event $event): ?Event
    {
        $request = $event->getRequest();
        if (isset($request['data'])) {
            $request['data'] = self::scrub($request['data']);
            $event->setRequest($request);
        }

        $event->setExtra(self::scrub($event->getExtra()));

        $breadcrumbs = array_map(
            static fn (Breadcrumb $breadcrumb): Breadcrumb => new Breadcrumb(
                $breadcrumb->getLevel(),
                $breadcrumb->getType(),
                $breadcrumb->getCategory(),
                $breadcrumb->getMessage(),
                self::scrub($breadcrumb->getMetadata()),
                $breadcrumb->getTimestamp(),
            ),
            $event->getBreadcrumbs(),
        );
        $event->setBreadcrumb($breadcrumbs);

        return $event;
    }

    /**
     * @param  mixed  $value
     * @return mixed
     */
    private static function scrub($value)
    {
        if (! is_array($value)) {
            return $value;
        }

        foreach ($value as $key => $item) {
            if (is_string($key) && self::isSensitiveKey($key)) {
                $value[$key] = self::MASK;

                continue;
            }

            if (is_array($item)) {
                $value[$key] = self::scrub($item);
            }
        }

        return $value;
    }

    private static function isSensitiveKey(string $key): bool
    {
        $normalized = strtolower($key);

        foreach (self::SENSITIVE_KEYS as $sensitiveKey) {
            if ($normalized === $sensitiveKey || str_ends_with($normalized, "_{$sensitiveKey}")) {
                return true;
            }
        }

        return false;
    }
}
