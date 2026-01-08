<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Bookings Data</title>
    <style>
        body {
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: white;
            color: #1f2937;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
            color: #111827;
        }

        .header p {
            margin: 5px 0 0;
            color: #6b7280;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th {
            background-color: #f3f4f6;
            text-align: left;
            padding: 8px 12px;
            font-weight: 600;
            border-bottom: 1px solid #e5e7eb;
            color: #374151;
        }

        td {
            padding: 8px 12px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }

        tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-paid {
            background: #dcfce7;
            color: #166534;
        }

        .status-confirmed {
            background: #dcfce7;
            color: #166534;
        }

        .status-pending {
            background: #fef9c3;
            color: #854d0e;
        }

        .status-failed {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .text-right {
            text-align: right;
        }

        .print-footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #9ca3af;
        }

        @media print {
            body {
                padding: 0;
            }

            button {
                display: none;
            }
        }

        .no-print {
            text-align: center;
            margin-bottom: 20px;
        }

        .btn-print {
            background-color: #2563eb;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }
    </style>
</head>

<body onload="window.print()">

    <div class="no-print">
        <button onclick="window.print()" class="btn-print">Print / Save as PDF</button>
    </div>

    <div class="header">
        <h1>Bookings Data Report</h1>
        <p>Generated on {{ now()->format('d F Y, H:i') }}</p>
        @if(request('status') || request('filter_type'))
            <p style="font-style: italic; margin-top: 5px;">
                Filters:
                {{ request('status') ? 'Status: ' . ucfirst(request('status')) : '' }}
                {{ request('filter_type') ? '| Type: ' . ucfirst(request('filter_type')) : '' }}
                {{ request('filter_date') ? '| Date: ' . request('filter_date') : '' }}
            </p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>#Code</th>
                <th>Date & Time</th>
                <th>User / Cafe</th>
                <th>Table</th>
                <th>Items</th>
                <th class="text-right">Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $booking)
                <tr>
                    <td><span style="font-family: monospace;">{{ $booking->booking_code }}</span></td>
                    <td>
                        <div>{{ \Carbon\Carbon::parse($booking->arrival_time)->format('Y-m-d') }}</div>
                        <div style="color: #6b7280;">{{ \Carbon\Carbon::parse($booking->arrival_time)->format('H:i') }}
                        </div>
                    </td>
                    <td>
                        <div style="font-weight: 500;">{{ $booking->user->name }}</div>
                        <div style="color: #6b7280; font-size: 11px;">{{ $booking->cafe->name }}</div>
                    </td>
                    <td>{{ $booking->table->name ?? 'N/A' }}</td>
                    <td>
                        <div style="font-size: 11px; color: #4b5563;">
                            {{ $booking->items->count() }} items
                        </div>
                    </td>
                    <td class="text-right">
                        Rp {{ number_format($booking->total_amount, 0, ',', '.') }}
                    </td>
                    <td>
                        <span class="status-badge status-{{ $booking->status }}">
                            {{ $booking->status }}
                        </span>
                        <div style="font-size: 10px; margin-top: 2px;">{{ $booking->payment_status }}</div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="print-footer">
        Page 1 of 1 &bull; Total {{ $bookings->count() }} records
    </div>

</body>

</html>