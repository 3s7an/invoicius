<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->number }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1f2937;
            line-height: 1.5;
            margin: 0;
            padding: 40px 44px;
            background: #fff;
        }

        .header { width: 100%; margin-bottom: 36px; border-bottom: 2px solid {{ $accentColor }}; padding-bottom: 20px; }
        .header td { vertical-align: bottom; }
        .header .title {
            font-size: 22px;
            font-weight: 700;
            color: {{ $accentColor }};
        }
        .header .number {
            font-size: 11px;
            color: #6b7280;
            margin-top: 3px;
        }
        .header .logo {
            max-height: 48px;
            max-width: 160px;
            object-fit: contain;
        }

        .parties { width: 100%; margin-bottom: 30px; }
        .parties td { vertical-align: top; width: 50%; }
        .parties td:first-child { padding-right: 24px; }
        .parties td:last-child { padding-left: 24px; }

        .section-label {
            font-size: 10px;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 6px;
        }
        .party-name {
            font-size: 12px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 3px;
        }
        .party-info {
            font-size: 10.5px;
            color: #374151;
            line-height: 1.65;
        }
        .party-meta {
            margin-top: 6px;
            font-size: 10px;
            color: #6b7280;
            line-height: 1.65;
        }

        .details { width: 100%; margin-bottom: 30px; border-collapse: collapse; }
        .details td {
            padding: 7px 0;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
            font-size: 11px;
        }
        .details tr:last-child td { border-bottom: none; }
        .details .dt {
            width: 140px;
            color: #6b7280;
            font-weight: 600;
        }
        .details .dd {
            color: #111827;
            font-weight: 600;
        }

        table.items { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        table.items thead th {
            font-size: 10px;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            text-align: left;
            padding: 8px 10px;
            background: #f9fafb;
            border-bottom: 2px solid {{ $accentColor }};
        }
        table.items thead th.r { text-align: right; }
        table.items tbody td {
            padding: 10px 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
            color: #374151;
        }
        table.items tbody td.r { text-align: right; }
        table.items tbody td.name { font-weight: 600; color: #111827; }

        .summary { width: 100%; margin-top: 8px; }
        .summary td.empty { width: 58%; }
        .summary-table { width: 100%; border-collapse: collapse; }
        .summary-table td { padding: 6px 10px; font-size: 11px; }
        .summary-table .lbl { text-align: right; color: #6b7280; }
        .summary-table .val { text-align: right; font-weight: 600; color: #1f2937; }
        .summary-table .total td {
            padding-top: 10px;
            border-top: 2px solid {{ $accentColor }};
        }
        .summary-table .total .lbl {
            font-weight: 700;
            font-size: 12px;
            color: #111827;
        }
        .summary-table .total .val {
            font-size: 15px;
            font-weight: 700;
            color: {{ $accentColor }};
        }

        .footer {
            margin-top: 36px;
            padding-top: 14px;
            border-top: 1px solid #e5e7eb;
            font-size: 10px;
            color: #6b7280;
            line-height: 1.7;
        }
    </style>
</head>
<body>

    <table class="header">
        <tr>
            <td>
                <div class="title">Invoice</div>
                <div class="number">{{ $invoice->number }}</div>
            </td>
            @if(!empty($logoDataUrl))
            <td style="text-align: right;">
                <img src="{{ $logoDataUrl }}" alt="" class="logo" />
            </td>
            @endif
        </tr>
    </table>

    <table class="parties">
        <tr>
            <td>
                <div class="section-label">From</div>
                <div class="party-name">{{ $issuer->company_name ?: $issuer->name }}</div>
                <div class="party-info">
                    @if($issuer->company_name)
                        {{ $issuer->name }}<br>
                    @endif
                    {{ $issuer->street }} {{ $issuer->street_num }}<br>
                    {{ $issuer->zip }} {{ $issuer->city }}
                    @if($issuer->state)
                        , {{ $issuer->state }}
                    @endif
                </div>
                @if($issuer->ico || $issuer->dic || $issuer->ic_dph)
                <div class="party-meta">
                    @if($issuer->ico)
                        IČ: {{ $issuer->ico }}<br>
                    @endif
                    @if($issuer->dic)
                        DIČ: {{ $issuer->dic }}<br>
                    @endif
                    @if($issuer->ic_dph)
                        IČ DPH: {{ $issuer->ic_dph }}
                    @endif
                </div>
                @endif
            </td>
            <td>
                <div class="section-label">Bill to</div>
                <div class="party-name">{{ $invoice->recipient_name }}</div>
                <div class="party-info">
                    @if($invoice->recipient_street)
                        {{ $invoice->recipient_street }} {{ $invoice->recipient_street_num }}<br>
                    @endif
                    @if($invoice->recipient_city)
                        {{ $invoice->recipient_city }}
                        @if($invoice->recipient_state)
                            , {{ $invoice->recipient_state }}
                        @endif
                    @endif
                </div>
                @php
                    $buyerIco = $invoice->recipient_ico ?? $invoice->recipient?->ico;
                    $buyerDic = $invoice->recipient_dic ?? $invoice->recipient?->dic;
                    $buyerIcDph = $invoice->recipient_ic_dph ?? $invoice->recipient?->ic_dph;
                @endphp
                @if(!empty($buyerIco) || !empty($buyerDic) || !empty($buyerIcDph))
                <div class="party-meta">
                    @if(!empty($buyerIco))
                        IČ: {{ $buyerIco }}<br>
                    @endif
                    @if(!empty($buyerDic))
                        DIČ: {{ $buyerDic }}<br>
                    @endif
                    @if(!empty($buyerIcDph))
                        IČ DPH: {{ $buyerIcDph }}
                    @endif
                </div>
                @endif
                @if($invoice->iban)
                <div class="party-meta">IBAN: {{ $invoice->iban }}</div>
                @endif
            </td>
        </tr>
    </table>

    <table class="details">
        <tr>
            <td class="dt">Issue date</td>
            <td class="dd">{{ $invoice->issue_date?->format('d.m.Y') }}</td>
        </tr>
        <tr>
            <td class="dt">Due date</td>
            <td class="dd">{{ $invoice->due_date?->format('d.m.Y') }}</td>
        </tr>
        <tr>
            <td class="dt">Variable symbol</td>
            <td class="dd">{{ $invoice->varsym }}</td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th>Description</th>
                <th class="r">Qty</th>
                <th>Unit</th>
                <th class="r">Unit price</th>
                <th class="r">Net</th>
                <th class="r">VAT</th>
                <th class="r">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
            <tr>
                <td class="name">{{ $item->name }}</td>
                <td class="r">{{ number_format($item->quantity, 2, ',', ' ') }}</td>
                <td>{{ $item->unit ?? 'pcs' }}</td>
                <td class="r">{{ number_format($item->unit_price, 2, ',', ' ') }} {{ $currencySymbol }}</td>
                <td class="r">{{ number_format($item->line_wo_vat ?? 0, 2, ',', ' ') }} {{ $currencySymbol }}</td>
                <td class="r">{{ number_format($item->vat ?? 0, 2, ',', ' ') }} {{ $currencySymbol }}</td>
                <td class="r">{{ number_format($item->line_total ?? 0, 2, ',', ' ') }} {{ $currencySymbol }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="summary">
        <tr>
            <td class="empty">&nbsp;</td>
            <td>
                <table class="summary-table">
                    <tr>
                        <td class="lbl">Subtotal</td>
                        <td class="val">{{ number_format($invoice->wo_vat_price ?? 0, 2, ',', ' ') }} {{ $currencySymbol }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">VAT</td>
                        <td class="val">{{ number_format($invoice->vat_price ?? 0, 2, ',', ' ') }} {{ $currencySymbol }}</td>
                    </tr>
                    <tr class="total">
                        <td class="lbl">Total due</td>
                        <td class="val">{{ number_format($invoice->total_price ?? 0, 2, ',', ' ') }} {{ $currencySymbol }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    @if($invoice->iban || $invoice->notes)
    <div class="footer">
        @if($invoice->iban)
            Payment account: {{ $invoice->iban }}<br>
        @endif
        @if($invoice->notes)
            {{ $invoice->notes }}
        @endif
    </div>
    @endif

</body>
</html>
