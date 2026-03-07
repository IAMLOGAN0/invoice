<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>GST Tax Invoice</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: "DejaVu Sans", Arial, Helvetica, sans-serif;
            font-size: 11px;
            line-height: 1.6;
            color: #1f2937;
            background: #ffffff;
        }

        @page {
            size: A4;
            margin: 14mm;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* ================= HEADER ================= */

        .header-table {
            background-color: #4f46e5;
            color: #ffffff;
            margin-bottom: 24px;
            border-radius: 6px;
        }

        .header-table td {
            padding: 22px 24px;
            vertical-align: middle;
        }

        .header-left {
            width: 65%;
        }

        .header-right {
            width: 35%;
            text-align: right;
        }

        .header-title {
            font-size: 26px;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .header-company {
            font-size: 13px;
            opacity: 0.95;
        }

        .invoice-number {
            font-size: 10px;
            opacity: 0.85;
            display: block;
            margin-top: 6px;
        }

        .invoice-value {
            font-size: 13px;
            font-weight: 700;
            display: block;
        }

        /* ================= DETAILS ================= */

        .details-table {
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            margin-bottom: 22px;
        }

        .details-table td {
            padding: 18px 20px;
            vertical-align: top;
        }

        .detail-left {
            width: 50%;
            border-right: 1px solid #e5e7eb;
        }

        .detail-right {
            width: 50%;
        }

        .detail-title {
            font-size: 11px;
            font-weight: 700;
            color: #4f46e5;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .detail-name {
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .detail-info {
            font-size: 10px;
            color: #4b5563;
            margin-bottom: 3px;
        }

        .detail-gstin {
            margin-top: 10px;
            padding-top: 8px;
            border-top: 1px dashed #d1d5db;
            font-size: 10px;
        }

        .gstin-label {
            font-size: 9px;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .gstin-value {
            font-weight: 700;
            letter-spacing: 0.4px;
        }

        /* ================= ITEMS TABLE ================= */

        .items-table {
            margin-bottom: 24px;
        }

        .items-table thead {
            background-color: #eef2ff;
        }

        .items-table th {
            padding: 9px 6px;
            text-align: right;
            font-size: 9px;
            font-weight: 700;
            color: #1f2937;
            border: 1px solid #c7d2fe;
            text-transform: uppercase;
        }

        .items-table th:first-child {
            text-align: center;
            width: 4%;
        }

        .items-table th:nth-child(2) {
            text-align: left;
            width: 22%;
        }

        .items-table th:nth-child(3) {
            text-align: center;
            width: 9%;
        }

        .items-table td {
            padding: 8px 6px;
            text-align: right;
            border: 1px solid #e5e7eb;
            font-size: 9.5px;
        }

        .items-table td:first-child {
            text-align: center;
        }

        .items-table td:nth-child(2) {
            text-align: left;
            font-weight: 600;
        }

        .items-table td:nth-child(3) {
            text-align: center;
        }

        /* ================= SUMMARY ================= */

        .summary-table {
            width: 48%;
            margin-left: auto;
        }

        .summary-table td {
            padding: 9px 10px;
            font-size: 10px;
            text-align: right;
        }

        .summary-label {
            width: 60%;
            color: #374151;
            border-bottom: 1px solid #e5e7eb;
        }

        .summary-value {
            width: 40%;
            font-weight: 700;
            border-bottom: 1px solid #e5e7eb;
        }

        .total-row td {
            background-color: #4f46e5;
            color: #ffffff;
            font-weight: 700;
            font-size: 12px;
            padding: 10px;
            border: none;
        }

        /* ================= FOOTER ================= */

        .footer {
            text-align: center;
            color: #6b7280;
            font-size: 9px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            margin-top: 28px;
        }

        .footer p {
            margin: 4px 0;
        }
    </style>
</head>

<body>

<div style="padding: 6px 2px;">

    <!-- HEADER -->
    <table class="header-table">
        <tr>
            <td class="header-left">
                <div class="header-title">TAX INVOICE</div>
                <div class="header-company">{{ $shop->name ?? 'Your Shop' }}</div>
            </td>
            <td class="header-right">
                <span class="invoice-number">Invoice Number</span>
                <span class="invoice-value">{{ $invoice->invoice_no }}</span>

                <span class="invoice-number" style="margin-top:10px;">Invoice Date</span>
                <span class="invoice-value">
                    {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') }}
                </span>
            </td>
        </tr>
    </table>

    <!-- SELLER / BUYER -->
    <table class="details-table">
        <tr>
            <td class="detail-left">
                <div class="detail-title">Seller Details</div>
                <div class="detail-name">{{ $shop->name }}</div>
                <div class="detail-info">{{ $shop->address }}</div>
                <div class="detail-info">{{ $shop->city }} - {{ $shop->pincode }}</div>

                <div class="detail-gstin">
                    <div class="gstin-label">GSTIN</div>
                    <div class="gstin-value">{{ $shop->gstin }}</div>
                </div>
            </td>

            <td class="detail-right">
                <div class="detail-title">Bill To</div>
                <div class="detail-name">{{ $customer->name }}</div>
                <div class="detail-info">{{ $customer->address }}</div>
                <div class="detail-info">{{ $customer->city }} - {{ $customer->pincode }}</div>

                <div class="detail-gstin">
                    <div class="gstin-label">GSTIN</div>
                    <div class="gstin-value">{{ $customer->gstin }}</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- ITEMS -->
    <table class="items-table">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Description</th>
                <th>HSN</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Taxable</th>
                <th>CGST</th>
                <th>SGST</th>
                <th>IGST</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
        @foreach($invoice->items as $i => $item)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->product->hsn_sac }}</td>
                <td>{{ $item->qty }}</td>
                <td>₹{{ number_format($item->price,2) }}</td>
                <td>₹{{ number_format($item->taxable,2) }}</td>
                <td>₹{{ number_format($item->cgst_amount,2) }}</td>
                <td>₹{{ number_format($item->sgst_amount,2) }}</td>
                <td>₹{{ number_format($item->igst_amount,2) }}</td>
                <td>₹{{ number_format($item->total,2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <!-- SUMMARY -->
    <table class="summary-table">
        <tr>
            <td class="summary-label">Taxable Value</td>
            <td class="summary-value">₹{{ number_format($invoice->subtotal,2) }}</td>
        </tr>
        <tr>
            <td class="summary-label">CGST</td>
            <td class="summary-value">₹{{ number_format($invoice->cgst,2) }}</td>
        </tr>
        <tr>
            <td class="summary-label">SGST</td>
            <td class="summary-value">₹{{ number_format($invoice->sgst,2) }}</td>
        </tr>
        <tr>
            <td class="summary-label">IGST</td>
            <td class="summary-value">₹{{ number_format($invoice->igst,2) }}</td>
        </tr>
        @if ($invoice->discount_amount > 0)
            <tr style="color: #dc2626; border-bottom: 1px solid #e5e7eb;">
                <td class="summary-label">Discount 
                    @if ($invoice->discount_type === 'coupon')
                        ({{ $invoice->coupon->code ?? 'N/A' }})
                    @else
                        (Flat)
                    @endif
                </td>
                <td class="summary-value" style="color: #dc2626;">-₹{{ number_format($invoice->discount_amount,2) }}</td>
            </tr>
        @endif
        <tr class="total-row">
            <td>GRAND TOTAL</td>
            <td>₹{{ number_format($invoice->grand_total,2) }}</td>
        </tr>
    </table>

    <!-- FOOTER -->
    <div class="footer">
        <p>Thank you for your business</p>
        <p>This is a computer generated invoice</p>
        <p style="margin-top: 12px; font-size: 8px; border-top: 1px solid #d1d5db; padding-top: 8px;">Developed by Soumendu Giri | soumendugiri654@gmail.com</p>
    </div>

</div>

</body>
</html>
