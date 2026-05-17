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
            color: #000;
        }
        .invoices-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            padding: 20px;
            width: 100%;
            margin: 0 auto;
            box-sizing: border-box;
        }
        .invoice-wrapper {
            background: white;
            width: 190mm; /* A4 printable area width on screen for full-page look */
            max-width: 100%;
            margin: 10px auto;
            padding: 15mm 20mm;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            box-sizing: border-box;
        }
        @media print {
            body { background: white; margin: 0; padding: 0; }
            .invoices-container {
                display: block;
                padding: 0;
                margin: 0;
                width: 100%;
            }
            .invoice-wrapper {
                width: 100% !important;
                margin: 0;
                padding: 10mm 15mm;
                box-shadow: none;
                page-break-after: always;
            }
            .no-print { display: none; }
        }

        /* Centered Header Layout */
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .header h2 { 
            margin: 0 0 5px 0; 
            font-size: 20px; 
            font-weight: bold;
            text-transform: uppercase; 
            letter-spacing: 0.5px;
        }
        .header p { 
            margin: 3px 0; 
            font-size: 11px; 
            font-weight: 500;
        }
        
        .header-divider {
            border-bottom: 1.5px solid #000;
            margin: 8px 0;
        }

        .pos-invoice-title-wrapper {
            text-align: center;
            margin: 12px 0;
        }

        .pos-invoice-title {
            font-weight: bold; 
            font-size: 13px;
            border: 1.5px solid #000; 
            display: inline-block; 
            padding: 3px 18px;
            text-transform: uppercase;
        }

        /* Metadata Info Block */
        .info { 
            margin-bottom: 10px; 
        }
        .info-row { 
            display: flex; 
            justify-content: space-between; 
            margin-bottom: 4px; 
            font-size: 12px;
            line-height: 1.4;
        }
        .info-row span:first-child { 
            font-weight: bold; 
        }

        .info-divider {
            border-bottom: 1.5px solid #000;
            margin: 8px 0;
        }

        /* Items Table Layout */
        .items-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 12px 0; 
        }
        .items-table th { 
            border-top: 1.5px solid #000;
            border-bottom: 1.5px solid #000; 
            padding: 6px 0;
            text-align: left; 
            font-size: 12px; 
            font-weight: bold;
        }
        .items-table td { 
            padding: 6px 0; 
            font-size: 12px; 
            vertical-align: top; 
        }
        
        /* Totals Block */
        .totals { 
            margin-top: 10px;
        }
        .total-row { 
            display: flex; 
            justify-content: space-between; 
            margin-bottom: 4px; 
            font-size: 12px;
        }
        .total-row.grand-total { 
            font-weight: bold; 
            font-size: 15px; 
        }

        .totals-divider-thick {
            border-bottom: 1.5px solid #000;
            margin: 8px 0;
        }
        .totals-divider-thin {
            border-bottom: 1px solid #000;
            margin: 6px 0;
        }

        /* Footer Layout */
        .footer { 
            text-align: center; 
            margin-top: 25px; 
            font-size: 11px; 
            border-top: 1px dashed #000; 
            padding-top: 15px; 
        }
        .footer p {
            margin: 4px 0;
        }
        .footer .thank-you {
            font-size: 14px;
            font-weight: bold;
        }
        
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

    <div class="invoices-container">
        @foreach($orders as $order)
        <div class="invoice-wrapper">
            <!-- Centered Header Block -->
            <div class="header">
                <h2>{{ $settings->site_name ?? 'BAZARIO' }}</h2>
                <p>{{ $settings->contact_address ?? 'House, Market, Road, Dhaka' }}</p>
                <p>Phone: {{ $settings->contact_phone ?? '01XXXXXXXXX' }}</p>
                @if(!empty($settings->contact_email))
                    <p>{{ $settings->contact_email }}</p>
                @else
                    <p>info@bazario.com</p>
                @endif
            </div>

            <!-- Thick Header Divider Line -->
            <div class="header-divider"></div>

            <!-- POS Invoice Title Box -->
            <div class="pos-invoice-title-wrapper">
                <div class="pos-invoice-title">
                    @if(str_contains($order->order_number, 'POS-'))
                        POS Invoice
                    @else
                        Invoice
                    @endif
                </div>
            </div>

            <!-- Metadata Info Block -->
            <div class="info">
                <div class="info-row">
                    <span>Bill No. : {{ $order->order_number }}</span>
                    <span>{{ $order->created_at->format('h:i A') }}</span>
                </div>
                <div class="info-row">
                    <span>Date &nbsp;&nbsp;&nbsp;: {{ $order->created_at->format('d-m-Y') }}</span>
                </div>
                <div class="info-row">
                    <span>Buyer &nbsp;&nbsp;: {{ $order->name }}</span>
                </div>
                <div class="info-row">
                    <span>Phone &nbsp;&nbsp;: {{ $order->phone }}</span>
                </div>
                <div class="info-row">
                    <span>Address : {{ $order->address }}</span>
                </div>
            </div>

            <!-- Thick Info Divider Line -->
            <div class="info-divider"></div>

            <!-- Items Table -->
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 60%; text-align: left;"># Product</th>
                        <th style="width: 10%; text-align: center;">Qty</th>
                        <th style="width: 15%; text-align: right;">Rate</th>
                        <th style="width: 15%; text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $index => $item)
                    <tr>
                        <td style="text-align: left;">{{ $index + 1 }} {{ $item->product_name }}</td>
                        <td style="text-align: center;">{{ $item->quantity }}</td>
                        <td style="text-align: right;">{{ number_format($item->price, 2) }}</td>
                        <td style="text-align: right;">{{ number_format($item->total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Thick Table Bottom Line -->
            <div style="border-bottom: 1.5px solid #000; margin-top: -12px; margin-bottom: 12px;"></div>

            <!-- Totals Section -->
            <div class="totals">
                <div class="total-row">
                    <span>Subtotal</span>
                    <span>{{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="total-row">
                    <span>Delivery (+)</span>
                    <span>{{ number_format($order->shipping, 2) }}</span>
                </div>
                @if($order->discount > 0)
                <div class="total-row">
                    <span>Discount (-)</span>
                    <span>{{ number_format($order->discount, 2) }}</span>
                </div>
                @endif
                
                <!-- Thick Line above Total -->
                <div class="totals-divider-thick"></div>

                <div class="total-row grand-total">
                    <span>Total {{ $order->items->sum('quantity') }} No</span>
                    <span>৳ {{ number_format($order->total, 2) }}</span>
                </div>
                
                <!-- Thin Line below Total -->
                <div class="totals-divider-thin"></div>

                <div class="total-row">
                    <span>Method :</span>
                    <span style="text-transform: uppercase;">{{ $order->payment_method }}</span>
                </div>
                <div class="total-row">
                    <span>Pay Status :</span>
                    <span style="text-transform: uppercase;">{{ $order->payment_status }}</span>
                </div>

                <!-- Thick Line above Total Paid -->
                <div class="totals-divider-thick"></div>

                <div class="total-row grand-total">
                    <span>Total Paid</span>
                    <span>৳ {{ number_format($order->payment_status === 'paid' ? $order->total : 0, 2) }}</span>
                </div>

                <!-- Dashed Line below Total Paid -->
                <div style="border-bottom: 1.5px dashed #000; margin: 8px 0;"></div>

                <div class="total-row">
                    <span>Order Status :</span>
                    <span>{{ ucfirst($order->status) }}</span>
                </div>

                <!-- Thick Line below Order Status -->
                <div class="totals-divider-thick"></div>
            </div>

            <!-- Footer Section -->
            <div class="footer">
                <p class="thank-you">Thank You!</p>
                <p>Visit Again!</p>
                <p style="font-size: 8px; margin-top: 8px;">* Computer generated invoice. No signature required.</p>
            </div>
        </div>
        @endforeach
    </div>

    <script>
        window.onload = function() {
            // Optional: window.print();
        }
    </script>
</body>
</html>
