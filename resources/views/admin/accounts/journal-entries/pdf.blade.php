<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Journal Entries Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0 0 5px 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .header p {
            margin: 0;
            color: #666;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-row {
            font-weight: bold;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Journal Entries Report</h2>
        <p>Generated on {{ now()->format('M d, Y h:i A') }}</p>
        @if(request()->filled('start_date') || request()->filled('end_date'))
            <p>Period: {{ request('start_date', 'Start') }} to {{ request('end_date', 'End') }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Reference No</th>
                <th>Period</th>
                <th>Student / Application</th>
                <th>Posted By</th>
                <th>Status</th>
                <th class="text-right">Amount (BDT)</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @forelse($entries as $entry)
                @php $grandTotal += $entry->total_amount; @endphp
                <tr>
                    <td>{{ $entry->date->format('M d, Y') }}</td>
                    <td>{{ $entry->reference_number }}</td>
                    <td>{{ $entry->period->name ?? '-' }}</td>
                    <td>
                        @if($entry->application)
                            {{ $entry->application->student->first_name }} {{ $entry->application->student->last_name }} 
                            <br><small>({{ $entry->application->application_id }})</small>
                        @else
                            General Entry
                        @endif
                    </td>
                    <td>{{ $entry->creator->name ?? '-' }}</td>
                    <td style="text-transform: capitalize;">{{ $entry->status }}</td>
                    <td class="text-right">{{ number_format($entry->total_amount, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No journal entries found matching the criteria.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="6" class="text-right">Grand Total:</td>
                <td class="text-right">{{ number_format($grandTotal, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
