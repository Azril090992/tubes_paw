<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Booking #{{ $booking->booking_code }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 14px;
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }

        .details {
            margin-bottom: 15px;
        }

        .items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .items th,
        .items td {
            text-align: left;
            padding: 5px 0;
        }

        .items .price {
            text-align: right;
        }

        .total-section {
            border-top: 1px dashed #000;
            padding-top: 10px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .grand-total {
            font-weight: bold;
            border-top: 1px double #000;
            padding-top: 5px;
            margin-top: 5px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 11px;
            color: #555;
        }

        @media print {
            body {
                width: 100%;
                max-width: none;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <div class="header">
        <h2 style="margin:0">{{ $booking->cafe->name }}</h2>
        <p style="margin:5px 0">{{ $booking->cafe->location }}</p>
        <p style="margin:5px 0">Booking #{{ $booking->booking_code }}</p>
    </div>

    <div class="details">
        <p><strong>Customer:</strong> {{ $booking->user->name }}</p>
        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($booking->arrival_time)->format('Y-m-d H:i') }}</p>
        <p><strong>Table:</strong> {{ $booking->table->name }} ({{ $booking->people_count }} Pax)</p>
        <p><strong>Status:</strong> {{ ucfirst($booking->status) }}</p>
    </div>

    @if($booking->items->count() > 0)
        <table class="items">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th class="price">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($booking->items as $item)
                    <tr>
                        <td>{{ $item->menu->name }}</td>
                        <td>{{ $item->qty }}</td>
                        <td class="price">{{ number_format($item->subtotal, 0) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="total-section">
        @php
            $orderTotal = $booking->items->sum('subtotal');
            $deposit = $booking->deposit_amount;
            $voucher = $booking->voucher_amount ?? 0;
            $final = $booking->final_amount; // This is usually "Remaining Due" if updated correctly or "Deposit Paid" if old logic
            // Let's use clean calculation for receipt
            $toPay = max(0, $orderTotal - $deposit);
        @endphp

        <div class="total-row">
            <span>Order Subtotal:</span>
            <span>{{ number_format($orderTotal, 0) }}</span>
        </div>
        <div class="total-row">
            <span>Deposit Paid:</span>
            <span>-{{ number_format($deposit, 0) }}</span>
        </div>
        @if($voucher > 0)
            <div class="total-row">
                <span>Voucher:</span>
                <span>-{{ number_format($voucher, 0) }}</span>
            </div>
        @endif

        <div class="total-row grand-total">
            <span>REMAINING DUE:</span>
            <span>Rp {{ number_format($toPay, 0) }}</span>
        </div>
    </div>

    <div class="footer">
        <p>Thank you for your visit!</p>
        <p>Simpan struk ini sebagai bukti pembayaran.</p>
    </div>
</body>

</html>