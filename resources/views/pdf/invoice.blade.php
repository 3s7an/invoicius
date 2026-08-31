<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('invoice.title') }} {{ $invoice->number }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10pxinv
            font-weight: normal;
            color: #27272a;
            line-height: 1.45;
            padding: 40px 44px;
        }

        table { border-collapse: collapse; }

        .muted { color: #a1a1aa; }

        /* Header */
        .head { width: 100%; margin-bottom: 36px; }
        .head td { vertical-align: top; }
        .head .logo { max-height: 40px; max-width: 130px; }
        .head .right { text-align: right; }
        .head .num { margin-top: 4px; }

        /* Meta */
        .meta { width: 100%; margin-bottom: 32px; }
        .meta td {
            padding: 0 24px 0 0;
            vertical-align: top;
        }
        .meta td:last-child { padding-right: 0; }

        /* Parties */
        .parties { width: 100%; margin-bottom: 32px; }
        .parties td {
            width: 50%;
            vertical-align: top;
            padding-right: 24px;
        }
        .parties td:last-child { padding-right: 0; padding-left: 24px; }
        .parties .block { margin-top: 14px; }

        /* Items */
        .items { width: 100%; margin-bottom: 24px; }
        .items th {
            text-align: left;
            padding: 0 8px 6px 0;
            border-bottom: 1px solid #27272a;
            color: #a1a1aa;
        }
        .items th.r { text-align: right; padding-right: 0; padding-left: 8px; }
        .items td {
            padding: 8px 8px 8px 0;
            border-bottom: 1px solid #e4e4e7;
            vertical-align: top;
        }
        .items td.r {
            text-align: right;
            padding-right: 0;
            padding-left: 8px;
            white-space: nowrap;
        }
        .items tr:last-child td { border-bottom: 1px solid #27272a; }

        /* Totals */
        .sum { width: 100%; }
        .sum .gap { width: 55%; }
        .sum-inner { width: 100%; }
        .sum-inner td {
            padding: 4px 0;
            text-align: right;
        }
        .sum-inner .lbl { padding-right: 20px; color: #a1a1aa; }
        .sum-inner .due td {
            padding-top: 10px;
            border-top: 1px solid #27272a;
        }

        /* Payment */
        .pay { width: 100%; margin-top: 28px; }
        .pay-box {
            padding: 16px;
            border: 1px solid #e4e4e7;
        }
        .pay-inner td { vertical-align: top; }
        .pay .line { margin-bottom: 3px; }

        .notes {
            margin-top: 28px;
            padding-top: 12px;
            border-top: 1px solid #e4e4e7;
            color: #a1a1aa;
        }
    </style>
</head>
<body>

    <table class="head">
        <tr>
            <td>
                @if(!empty($logoDataUrl))
                    <img src="{{ $logoDataUrl }}" alt="" class="logo" />
                @endif
            </td>
            <td class="right">
                <span class="muted">{{ __('invoice.title') }}</span><br>
                <span class="num">{{ $invoice->number }}</span>
            </td>
        </tr>
    </table>

    <table class="meta">
        <tr>
            <td>
                <span class="muted">{{ __('invoice.issue_date') }}</span><br>
                {{ $invoice->issue_date?->format('d.m.Y') }}
            </td>
            <td>
                <span class="muted">{{ __('invoice.due_date') }}</span><br>
                {{ $invoice->due_date?->format('d.m.Y') }}
            </td>
            <td>
                <span class="muted">{{ __('invoice.variable_symbol') }}</span><br>
                {{ $invoice->varsym }}
            </td>
        </tr>
    </table>

    <table class="parties">
        <tr>
            <td>
                <span class="muted">{{ __('invoice.issuer') }}</span>
                <div class="block">
                    {{ $issuer->company_name ?: $issuer->name }}<br>
                    @if($issuer->company_name)
                        {{ $issuer->name }}<br>
                    @endif
                    {{ $issuer->street }} {{ $issuer->street_num }}<br>
                    {{ $issuer->zip }} {{ $issuer->city }}@if($issuer->state), {{ $issuer->state }}@endif
                    @if($issuer->ico || $issuer->dic || $issuer->ic_dph)
                        <br>
                        @if($issuer->ico){{ __('invoice.company_reg_no') }} {{ $issuer->ico }}@endif
                        @if($issuer->dic) · {{ __('invoice.tax_id') }} {{ $issuer->dic }}@endif
                        @if($issuer->ic_dph) · {{ __('invoice.vat_id') }} {{ $issuer->ic_dph }}@endif
                    @endif
                </div>
            </td>
            <td>
                <span class="muted">{{ __('invoice.recipient') }}</span>
                <div class="block">
                    {{ $invoice->recipient_name }}<br>
                    @if($invoice->recipient_street)
                        {{ $invoice->recipient_street }} {{ $invoice->recipient_street_num }}<br>
                    @endif
                    @if($invoice->recipient_city)
                        {{ $invoice->recipient_city }}@if($invoice->recipient_state), {{ $invoice->recipient_state }}@endif
                    @endif
                    @php
                        $buyerIco = $invoice->recipient_ico ?? $invoice->recipient?->ico;
                        $buyerDic = $invoice->recipient_dic ?? $invoice->recipient?->dic;
                        $buyerIcDph = $invoice->recipient_ic_dph ?? $invoice->recipient?->ic_dph;
                    @endphp
                    @if(!empty($buyerIco) || !empty($buyerDic) || !empty($buyerIcDph))
                        <br>
                        @if(!empty($buyerIco)){{ __('invoice.company_reg_no') }} {{ $buyerIco }}@endif
                        @if(!empty($buyerDic)) · {{ __('invoice.tax_id') }} {{ $buyerDic }}@endif
                        @if(!empty($buyerIcDph)) · {{ __('invoice.vat_id') }} {{ $buyerIcDph }}@endif
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th>{{ __('invoice.description') }}</th>
                <th class="r">{{ __('invoice.quantity') }}</th>
                <th class="r">{{ __('invoice.unit_price') }}</th>
                <th class="r">{{ __('invoice.total') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
            @php
                $unit = $unitLabels[$item->unit ?? 'pcs'] ?? ($item->unit ?? 'ks');
                $qty = number_format($item->quantity, 2, ',', ' ');
            @endphp
            <tr>
                <td>{{ $item->name }}</td>
                <td class="r muted">{{ $qty }} {{ $unit }}</td>
                <td class="r">{{ number_format($item->unit_price, 2, ',', ' ') }} {{ $currencySymbol }}</td>
                <td class="r">{{ number_format($item->line_total ?? 0, 2, ',', ' ') }} {{ $currencySymbol }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="sum">
        <tr>
            <td class="gap"></td>
            <td>
                <table class="sum-inner">
                    <tr>
                        <td class="lbl">{{ __('invoice.subtotal_excl_vat') }}</td>
                        <td>{{ number_format($invoice->wo_vat_price ?? 0, 2, ',', ' ') }} {{ $currencySymbol }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">{{ __('invoice.vat') }}</td>
                        <td>{{ number_format($invoice->vat_price ?? 0, 2, ',', ' ') }} {{ $currencySymbol }}</td>
                    </tr>
                    <tr class="due">
                        <td class="lbl">{{ __('invoice.total_due') }}</td>
                        <td>{{ number_format($invoice->total_price ?? 0, 2, ',', ' ') }} {{ $currencySymbol }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    @if(!empty($paymentIban))
    <table class="pay">
        <tr>
            <td class="pay-box">
                <table class="pay-inner">
                    <tr>
                        <td>
                            <span class="muted">{{ __('invoice.payment') }}</span><br>
                            <div class="block">
                                <div class="line">IBAN {{ trim(chunk_split($paymentIban, 4, ' ')) }}</div>
                                <div class="line">{{ __('invoice.amount') }} {{ number_format($invoice->total_price ?? 0, 2, ',', ' ') }} {{ $currencySymbol }}</div>
                                @if($invoice->varsym)
                                <div class="line">VS {{ $invoice->varsym }}</div>
                                @endif
                                <div class="line">{{ $issuer->company_name ?: $issuer->name }}</div>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    @endif

    @if($invoice->notes)
    <div class="notes">{{ $invoice->notes }}</div>
    @endif

</body>
</html>
