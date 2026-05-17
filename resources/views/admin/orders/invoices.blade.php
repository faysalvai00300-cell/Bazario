<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Invoices</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            margin: 0;
            padding: 0;
            background: #eee;
        }
        .invoice-wrapper {
            background: white;
            width: 80mm;
            margin: 10px auto;
            padding: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        @media print {
            body { background: white; margin: 0; padding: 0; }
            .invoice-wrapper {
                width: 80mm;
                margin: 0;
                padding: 10px;
                box-shadow: none;
                page-break-after: always;
            }
            .no-print { display: none; }
        }
        .header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
            margin-bottom: 5px;
        }
        .header h2 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 10px; }
        .info { margin-bottom: 10px; border-bottom: 1px dashed #000; padding-bottom: 5px; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 2px; }
        .info-row span:first-child { font-weight: bold; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .items-table th { border-bottom: 1px solid #000; text-align: left; font-size: 11px; }
        .items-table td { padding: 4px 0; font-size: 11px; vertical-align: top; }
        .totals { border-top: 1px dashed #000; padding-top: 5px; }
        .total-row { display: flex; justify-content: space-between; margin-bottom: 2px; }
        .total-row.grand-total { font-weight: bold; font-size: 14px; border-top: 1px solid #000; padding-top: 5px; margin-top: 5px; }
        .footer { text-align: center; margin-top: 15px; font-size: 10px; border-top: 1px dashed #000; padding-top: 10px; }
        .print-btn-container { text-align: center; padding: 20px; }
        .print-btn {
            background: #45b86f;
            color: white;
            border: none;
            padding: 10px 30px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="no-print print-btn-container">
        <button class="print-btn" onclick="window.print()">Print All Invoices</button>
    </div>

    @foreach($orders as $order)
    <div class="invoice-wrapper">
        <div class="header">
            <h2>{{ $settings->site_name ?? 'GADGET BD' }}</h2>
            <p>{{ $settings->address ?? 'House, Market, Road, Dhaka' }}</p>
            <p>Phone: {{ $settings->phone ?? '01XXXXXXXXX' }}</p>
            <div style="margin-top: 5px; font-weight: bold; border: 1px solid #000; display: inline-block; padding: 2px 10px;">
                POS Invoice
            </div>
        </div>

        <div class="info">
            <div class="info-row">
                <span>Bill No:</span>
                <span>{{ $order->order_number }}</span>
            </div>
            <div class="info-row">
                <span>Date:</span>
                <span>{{ $order->created_at->format('d-m-Y H:i') }}</span>
            </div>
            <div class="info-row">
                <span>Buyer:</span>
                <span>{{ $order->name }}</span>
            </div>
            <div class="info-row">
                <span>Phone:</span>
                <span>{{ $order->phone }}</span>
            </div>
            <div class="info-row">
                <span>Address:</span>
                <span style="text-align: right; max-width: 60%;">{{ $order->address }}</span>
            </div>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th># Product</th>
                    <th style="text-align: center;">Qty</th>
                    <th style="text-align: right;">Rate</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }} {{ $item->product_name }}</td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">{{ number_format($item->price) }}</td>
                    <td style="text-align: right;">{{ number_format($item->total) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <div class="total-row">
                <span>Subtotal</span>
                <span>{{ number_format($order->subtotal) }}</span>
            </div>
            <div class="total-row">
                <span>Delivery (+)</span>
                <span>{{ number_format($order->shipping) }}</span>
            </div>
            @if($order->discount > 0)
            <div class="total-row">
                <span>Discount (-)</span>
                <span>{{ number_format($order->discount) }}</span>
            </div>
            @endif
            <div class="total-row grand-total">
                <span>Total {{ $order->items->sum('quantity') }} No</span>
                <span>Tk {{ number_format($order->total) }}</span>
            </div>
        </div>

        <div class="info" style="border: none; margin-top: 10px;">
            <div class="info-row">
                <span>Method:</span>
                <span style="text-transform: uppercase;">{{ $order->payment_method }}</span>
            </div>
            <div class="info-row">
                <span>Pay Status:</span>
                <span style="text-transform: uppercase;">{{ $order->payment_status }}</span>
            </div>
            @if($order->courier_name)
            <div class="info-row">
                <span>Courier:</span>
                <span>{{ ucfirst($order->courier_name) }}</span>
            </div>
            @endif
            @if($order->courier_tracking_id)
            <div class="info-row">
                <span>Tracking:</span>
                <span>{{ $order->courier_tracking_id }}</span>
            </div>
            @endif
        </div>

        <div class="footer">
            <p>Thank You!</p>
            <p>Visit Again!</p>
            <p style="font-size: 8px; margin-top: 5px;">* Computer generated Invoice. No signature required.</p>
        </div>
    </div>
    @endforeach

    <script>
        window.onload = function() {
            // Optional: window.print();
        }
    </script>
</body>
</html>
