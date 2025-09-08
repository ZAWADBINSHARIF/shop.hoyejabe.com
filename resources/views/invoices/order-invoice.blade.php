<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->order_tracking_id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            border-bottom: 3px solid #4F46E5;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .company-info h1 {
            color: #4F46E5;
            font-size: 28px;
            margin-bottom: 5px;
        }

        .company-info p {
            color: #666;
            font-size: 12px;
        }

        .invoice-title {
            text-align: right;
            margin-top: -40px;
        }

        .invoice-title h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 5px;
        }

        .invoice-number {
            color: #666;
            font-size: 14px;
        }

        .invoice-details {
            margin-bottom: 30px;
        }

        .row {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .col {
            display: table-cell;
            width: 48%;
            vertical-align: top;
        }

        .col-right {
            text-align: right;
        }

        .section-title {
            font-weight: bold;
            color: #4F46E5;
            font-size: 14px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .info-block {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .info-block p {
            margin-bottom: 5px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .table th {
            background-color: #4F46E5;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
            font-size: 12px;
        }

        .table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }

        .table tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .summary {
            margin-top: 30px;
            border-top: 2px solid #e5e7eb;
            padding-top: 20px;
        }

        .summary-row {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }

        .summary-label {
            display: table-cell;
            width: 70%;
            text-align: right;
            padding-right: 20px;
            font-size: 14px;
        }

        .summary-value {
            display: table-cell;
            width: 30%;
            text-align: right;
            font-size: 14px;
        }

        .total-row {
            font-weight: bold;
            font-size: 16px;
            color: #4F46E5;
            border-top: 2px solid #4F46E5;
            padding-top: 10px;
            margin-top: 10px;
        }

        .footer {
            margin-top: 50px;
            padding-top: 30px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #666;
            font-size: 11px;
        }

        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-pending {
            background-color: #FEF3C7;
            color: #92400E;
        }

        .badge-processing {
            background-color: #DBEAFE;
            color: #1E40AF;
        }

        .badge-shipped {
            background-color: #E0E7FF;
            color: #3730A3;
        }

        .badge-delivered {
            background-color: #D1FAE5;
            color: #065F46;
        }

        .badge-cancelled {
            background-color: #FEE2E2;
            color: #991B1B;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <h1>{{ config('app.name', 'Lion BD') }}</h1>
                <p>Your trusted online shopping destination</p>
                <p>Email: support@kenajabe.com | Phone: +880 1234-567890</p>
            </div>
            <div class="invoice-title">
                <h2>INVOICE</h2>
                <p class="invoice-number">#{{ $order->order_tracking_id }}</p>
            </div>
        </div>

        <!-- Invoice Details -->
        <div class="invoice-details">
            <div class="row">
                <div class="col">
                    <div class="info-block">
                        <p class="section-title">Bill To</p>
                        <p><strong>{{ $order->customer_name }}</strong></p>
                        <p>Phone: {{ $order->customer_mobile }}</p>
                        <p>{{ $order->address }}</p>
                        <p>{{ $order->city }}{{ $order->upazila ? ', ' . $order->upazila : '' }}</p>
                        {{ $order->thana ? '<p>Thana: ' . $order->thana . '</p>' : '' }}
                        {{ $order->post_code ? '<p>Post Code: ' . $order->post_code . '</p>' : '' }}
                    </div>
                </div>
                <div class="col">
                    <div class="info-block">
                        <p class="section-title">Invoice Details</p>
                        <p><strong>Invoice Date:</strong> {{ $order->created_at->format('F d, Y') }}</p>
                        <p><strong>Order Date:</strong> {{ $order->created_at->format('F d, Y') }}</p>
                        <p><strong>Order Status:</strong>
                            @php
                                $status = strtolower($order->order_status->value);
                                $badgeClass = 'badge-' . $status;
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ ucfirst($order->order_status->value) }}</span>
                        </p>
                        @if ($order->shipping)
                            <p><strong>Shipping Area:</strong> {{ $order->shipping->title }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 40%">Product</th>
                    <th class="text-center" style="width: 10%">Qty</th>
                    <th class="text-right" style="width: 15%">Unit Price</th>
                    <th class="text-right" style="width: 15%">Extras</th>
                    <th class="text-right" style="width: 20%">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->orderedProducts as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->product_name }}</strong>
                            @if ($item->selected_color_code)
                                <br><small>Color:
                                    <span
                                        style="display: inline-block; width: 12px; height: 12px; background-color: {{ $item->selected_color_code }}; border: 1px solid #ccc; border-radius: 2px; vertical-align: middle;"></span>
                                    {{ $item->selected_color_code }}
                                </small>
                            @endif
                            @if ($item->selected_size)
                                <br><small>Size: {{ $item->selected_size }}</small>
                            @endif
                        </td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">BDT {{ number_format($item->base_price, 2) }}</td>
                        <td class="text-right">
                            @php
                                $extras = 0;
                                if ($item->color_extra_price) {
                                    $extras += $item->color_extra_price;
                                }
                                if ($item->size_extra_price) {
                                    $extras += $item->size_extra_price;
                                }
                                if ($item->extra_shipping_cost) {
                                    $extras += $item->extra_shipping_cost;
                                }
                            @endphp
                            @if ($extras > 0)
                                BDT {{ number_format($extras, 2) }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-right"><strong>BDT {{ number_format($item->product_total_price, 2) }}</strong>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary -->
        <div class="summary">
            <div class="summary-row">
                <div class="summary-label">Subtotal:</div>
                <div class="summary-value">
                    BDT {{ number_format($order->orderedProducts->sum('product_total_price'), 2) }}
                </div>
            </div>

            <div class="summary-row">
                <div class="summary-label">Shipping Cost:</div>
                <div class="summary-value">BDT {{ number_format($order->shipping_cost, 2) }}</div>
            </div>

            @if ($order->extra_shipping_cost > 0)
                <div class="summary-row">
                    <div class="summary-label">Extra Shipping:</div>
                    <div class="summary-value">BDT {{ number_format($order->extra_shipping_cost, 2) }}</div>
                </div>
            @endif

            <div class="summary-row total-row">
                <div class="summary-label">Total Amount:</div>
                <div class="summary-value">BDT {{ number_format($order->total_price, 2) }}</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Thank you for your business!</strong></p>
            <p>This is a computer-generated invoice and does not require a signature.</p>
            <p>For any queries, please contact our customer service.</p>
            <p style="margin-top: 20px;">© {{ date('Y') }} {{ config('app.name', 'Lion BD') }}. All rights
                reserved.</p>
        </div>
    </div>
</body>

</html>
