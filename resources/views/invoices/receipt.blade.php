<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $invoice->invoice_no }}</title>
    <style>
        /* Reset */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            background: #f5f5f5;
        }

        .receipt {
            width: 302px; /* 80mm thermal paper */
            margin: 10px auto;
            padding: 10px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .receipt-58 {
            width: 219px; /* 58mm thermal paper */
        }

        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: bold; }
        .large { font-size: 14px; }
        .small { font-size: 10px; }

        .divider {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        .double-divider {
            border-top: 2px solid #000;
            margin: 6px 0;
        }

        /* Shop Header */
        .shop-name {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .shop-details {
            text-align: center;
            font-size: 10px;
            margin-top: 2px;
        }

        /* Info rows */
        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 2px 0;
        }

        .info-row .label { color: #555; }

        /* Items table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
        }

        .items-table th {
            font-size: 11px;
            text-align: left;
            padding: 3px 0;
            border-bottom: 1px solid #000;
        }

        .items-table th:nth-child(2),
        .items-table th:nth-child(3),
        .items-table th:nth-child(4) {
            text-align: right;
        }

        .items-table td {
            padding: 3px 0;
            vertical-align: top;
            font-size: 11px;
        }

        .items-table td:nth-child(2),
        .items-table td:nth-child(3),
        .items-table td:nth-child(4) {
            text-align: right;
        }

        .item-name {
            max-width: 120px;
            word-wrap: break-word;
        }

        /* Totals */
        .totals .info-row {
            font-size: 11px;
        }

        .grand-total {
            font-size: 14px;
            font-weight: bold;
        }

        .payment-info {
            font-size: 11px;
        }

        /* Footer */
        .footer {
            text-align: center;
            font-size: 10px;
            margin-top: 8px;
        }

        /* Print controls - hidden on print */
        .print-controls {
            text-align: center;
            margin: 20px auto;
            max-width: 400px;
        }

        .print-controls button, .print-controls a {
            display: inline-block;
            padding: 10px 24px;
            margin: 5px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            color: #fff;
        }

        .btn-print { background: #2563eb; }
        .btn-print:hover { background: #1d4ed8; }
        .btn-back { background: #6b7280; }
        .btn-back:hover { background: #4b5563; }
        .btn-size { background: #059669; font-size: 12px; padding: 8px 16px; }
        .btn-size:hover { background: #047857; }
        .btn-size.active { background: #7c3aed; }

        /* Print styles */
        @media print {
            body { background: #fff; }
            .receipt { 
                width: 100%;
                margin: 0;
                padding: 2mm;
                box-shadow: none;
            }
            .print-controls { display: none !important; }

            @page {
                margin: 0;
                size: 80mm auto;
            }
        }

        @media print and (width: 58mm) {
            @page { size: 58mm auto; }
        }
    </style>
</head>
<body>

    <!-- Print Controls -->
    <div class="print-controls">
        <div style="margin-bottom: 10px;">
            <button class="btn-size active" onclick="setSize(302)" id="btn80">80mm</button>
            <button class="btn-size" onclick="setSize(219)" id="btn58">58mm</button>
        </div>
        <button class="btn-print" onclick="window.print()">🖨️ Print Receipt</button>
        <a href="{{ route('invoices.show', $invoice) }}" class="btn-back">← Back</a>
    </div>

    <!-- Receipt -->
    <div class="receipt" id="receipt">
        <!-- Shop Header -->
        <div class="shop-name">{{ $invoice->shop->name }}</div>
        <div class="shop-details">
            {{ $invoice->shop->address }}<br>
            @if($invoice->shop->phone)Ph: {{ $invoice->shop->phone }}<br>@endif
            @if($invoice->shop->gstin)GSTIN: {{ $invoice->shop->gstin }}@endif
        </div>

        <div class="divider"></div>

        <!-- Invoice Info -->
        <div class="center bold" style="font-size: 13px; margin: 4px 0;">
            {{ Str::startsWith($invoice->invoice_no, 'REC-') ? 'PAYMENT RECEIPT' : 'TAX INVOICE' }}
        </div>

        <div class="divider"></div>

        <div class="info-row">
            <span class="label">No:</span>
            <span class="bold">{{ $invoice->invoice_no }}</span>
        </div>
        <div class="info-row">
            <span class="label">Date:</span>
            <span>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</span>
        </div>

        <div class="divider"></div>

        <!-- Customer -->
        <div style="margin: 2px 0; font-size: 11px;">
            <span class="bold">Customer:</span> {{ $invoice->customer->name }}<br>
            @if($invoice->customer->phone)
                <span class="bold">Ph:</span> {{ $invoice->customer->phone }}<br>
            @endif
            @if($invoice->customer->gstin)
                <span class="bold">GSTIN:</span> {{ $invoice->customer->gstin }}
            @endif
        </div>

        <div class="divider"></div>

        <!-- Items -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Rate</th>
                    <th>Amt</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                    <tr>
                        <td class="item-name">{{ $item->product->name }}</td>
                        <td>{{ $item->qty }}</td>
                        <td>{{ number_format($item->price, 2) }}</td>
                        <td>{{ number_format($item->qty * $item->price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="double-divider"></div>

        <!-- Totals -->
        <div class="totals">
            <div class="info-row">
                <span>Subtotal</span>
                <span>{{ number_format($invoice->subtotal, 2) }}</span>
            </div>

            @if($invoice->apply_gst)
                @if($invoice->cgst > 0)
                    <div class="info-row">
                        <span>CGST</span>
                        <span>{{ number_format($invoice->cgst, 2) }}</span>
                    </div>
                @endif
                @if($invoice->sgst > 0)
                    <div class="info-row">
                        <span>SGST</span>
                        <span>{{ number_format($invoice->sgst, 2) }}</span>
                    </div>
                @endif
                @if($invoice->igst > 0)
                    <div class="info-row">
                        <span>IGST</span>
                        <span>{{ number_format($invoice->igst, 2) }}</span>
                    </div>
                @endif
            @endif

            @if($invoice->discount_amount > 0)
                <div class="info-row">
                    <span>Discount</span>
                    <span>-{{ number_format($invoice->discount_amount, 2) }}</span>
                </div>
            @endif

            <div class="double-divider"></div>

            <div class="info-row grand-total">
                <span>GRAND TOTAL</span>
                <span>₹{{ number_format($invoice->grand_total, 2) }}</span>
            </div>

            <div class="divider"></div>

            <div class="payment-info">
                <div class="info-row">
                    <span>Paid</span>
                    <span>₹{{ number_format($invoice->paid_amount, 2) }}</span>
                </div>
                @if($invoice->due_amount > 0)
                    <div class="info-row bold">
                        <span>DUE</span>
                        <span>₹{{ number_format($invoice->due_amount, 2) }}</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="double-divider"></div>

        <!-- Footer -->
        <div class="footer">
            <p class="bold">Thank you for your purchase!</p>
            <p style="margin-top: 4px;">{{ $invoice->shop->name }}</p>
            <p style="margin-top: 8px; font-size: 9px;">This is a computer generated receipt</p>
        </div>
    </div>

    <script>
        function setSize(width) {
            document.getElementById('receipt').style.width = width + 'px';
            document.getElementById('btn80').classList.toggle('active', width === 302);
            document.getElementById('btn58').classList.toggle('active', width === 219);

            // Update print page size
            const style = document.getElementById('dynamic-print') || document.createElement('style');
            style.id = 'dynamic-print';
            style.textContent = '@media print { @page { size: ' + (width === 302 ? '80mm' : '58mm') + ' auto; } }';
            document.head.appendChild(style);
        }

        // Auto-print if ?autoprint=1
        if (new URLSearchParams(window.location.search).get('autoprint') === '1') {
            window.addEventListener('load', () => window.print());
        }
    </script>
</body>
</html>
