<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->number }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1f2937; line-height: 1.4; margin: 0; padding: 24px; }
        .header { border-bottom: 3px solid {{ $accentColor }}; padding-bottom: 16px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; }
        .header h1 { margin: 0; font-size: 24px; color: {{ $accentColor }}; flex: 1; }
        .header-logo { max-height: 56px; max-width: 180px; object-fit: contain; }
        .columns { width: 100%; }
        .columns td { vertical-align: top; width: 50%; padding: 0 8px 0 0; }
        .columns td + td { padding: 0 0 0 8px; }
        .label { font-weight: bold; color: #6b7280; font-size: 10px; text-transform: uppercase; margin-bottom: 2px; }
        .issuer, .recipient { margin-bottom: 20px; }
        .meta { margin-top: 24px; margin-bottom: 20px; }
        .meta table { width: 100%; }
        .meta th { text-align: left; font-weight: 600; width: 140px; color: #6b7280; }
        table.items { width: 100%; border-collapse: collapse; margin-top: 16px; border: 1px solid #d1d5db; }
        table.items th { text-align: left; padding: 10px 10px; border: 1px solid #d1d5db; border-bottom: 2px solid {{ $accentColor }}; font-size: 10px; text-transform: uppercase; color: #6b7280; background: #f9fafb; }
        table.items td { padding: 10px 10px; border: 1px solid #d1d5db; }
        table.items .num { text-align: right; }
        .totals { margin-top: 24px; margin-left: auto; width: 280px; border-collapse: collapse; border: 1px solid #d1d5db; }
        .totals tr td { padding: 8px 12px; border: 1px solid #d1d5db; }
        .totals tr td:first-child { text-align: right; color: #6b7280; }
        .totals tr td:last-child { text-align: right; font-weight: 600; }
        .totals .grand-total td { font-size: 14px; border-top: 2px solid {{ $accentColor }}; padding-top: 10px; padding-bottom: 10px; }
        .footer { margin-top: 32px; padding-top: 16px; border-top: 1px solid #e5e7eb; font-size: 10px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Invoice {{ $invoice->number }}</h1>
        @if(!empty($logoDataUrl))
            <img src="{{ $logoDataUrl }}" alt="" class="header-logo" />
        @endif
    </div>

    <table class="columns">
        <tr>
            <td>
                <div class="label">From (Seller)</div>
                <div class="issuer">
                    @if($issuer->company_name){{ $issuer->company_name }}<br>@endif
                    {{ $issuer->name }}<br>
                    {{ $issuer->street }} {{ $issuer->street_num }}<br>
                    {{ $issuer->zip }} {{ $issuer->city }}@if($issuer->state), {{ $issuer->state }}@endif<br>
                    @if($issuer->ico)Company ID (IČ): {{ $issuer->ico }}@endif
                    @if($issuer->dic)<br>VAT ID (DIČ): {{ $issuer->dic }}@endif
                    @if($issuer->ic_dph)<br>VAT reg. no. (IČ DPH): {{ $issuer->ic_dph }}@endif
                </div>
            </td>
            <td>
                <div class="label">Bill to (Buyer)</div>
                <div class="recipient">
                    {{ $invoice->recipient_name }}<br>
                    @if($invoice->recipient_street){{ $invoice->recipient_street }} @if($invoice->recipient_street_num){{ $invoice->recipient_street_num }}@endif<br>@endif
                    @if($invoice->recipient_city){{ $invoice->recipient_city }}@if($invoice->recipient_state), {{ $invoice->recipient_state }}@endif<br>@endif
                    @php
                    $buyerIco = $invoice->recipient_ico ?? $invoice->recipient?->ico;
                    $buyerDic = $invoice->recipient_dic ?? $invoice->recipient?->dic;
                    $buyerIcDph = $invoice->recipient_ic_dph ?? $invoice->recipient?->ic_dph;
                    @endphp
                    @if(!empty($buyerIco))Company ID (IČ): {{ $buyerIco }}<br>@endif
                    @if(!empty($buyerDic))VAT ID (DIČ): {{ $buyerDic }}<br>@endif
                    @if(!empty($buyerIcDph))VAT reg. no. (IČ DPH): {{ $buyerIcDph }}<br>@endif
                    @if($invoice->iban)IBAN: {{ $invoice->iban }}@endif
                </div>
            </td>
        </tr>
    </table>

    <div class="meta">
        <table>
            <tr><th>Issue date:</th><td>{{ $invoice->issue_date?->format('d.m.Y') }}</td></tr>
            <tr><th>Due date:</th><td>{{ $invoice->due_date?->format('d.m.Y') }}</td></tr>
            <tr><th>Variable symbol:</th><td>{{ $invoice->varsym }}</td></tr>
        </table>
    </div>

    <table class="items">
        <thead>
            <tr>
                <th>Description</th>
                <th class="num">Qty</th>
                <th>Unit</th>
                <th class="num">Unit price</th>
                <th class="num">Net</th>
                <th class="num">VAT</th>
                <th class="num">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td class="num">{{ number_format($item->quantity, 2, ',', ' ') }}</td>
                <td>{{ $item->unit ?? 'pcs' }}</td>
                <td class="num">{{ number_format($item->unit_price, 2, ',', ' ') }} {{ $currencySymbol }}</td>
                <td class="num">{{ number_format($item->line_wo_vat ?? 0, 2, ',', ' ') }} {{ $currencySymbol }}</td>
                <td class="num">{{ number_format($item->vat ?? 0, 2, ',', ' ') }} {{ $currencySymbol }}</td>
                <td class="num">{{ number_format($item->line_total ?? 0, 2, ',', ' ') }} {{ $currencySymbol }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td>Subtotal (net):</td>
            <td>{{ number_format($invoice->wo_vat_price ?? 0, 2, ',', ' ') }} {{ $currencySymbol }}</td>
        </tr>
        <tr>
            <td>VAT:</td>
            <td>{{ number_format($invoice->vat_price ?? 0, 2, ',', ' ') }} {{ $currencySymbol }}</td>
        </tr>
        <tr class="grand-total">
            <td>Total due:</td>
            <td>{{ number_format($invoice->total_price ?? 0, 2, ',', ' ') }} {{ $currencySymbol }}</td>
        </tr>
    </table>

    @if($invoice->iban || $invoice->notes)
    <div class="footer">
        @if($invoice->iban)<p>Payment to account: {{ $invoice->iban }}</p>@endif
        @if($invoice->notes)<p>{{ $invoice->notes }}</p>@endif
    </div>
    @endif
</body>
</html>
