<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Expenses Report</title>
    <style>
        @page {
            margin: 0.5in;
            footer: html_DocFooter;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #2c3e50;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        /* Header Styling */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #47389D;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }

        .report-title {
            font-size: 24pt;
            font-weight: bold;
            color: #47389D;
            text-transform: uppercase;
        }

        /* Filter Info */
        .filter-info {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            padding: 10px 15px;
            margin-bottom: 20px;
            font-size: 9pt;
            color: #666;
        }

        .filter-info strong {
            color: #47389D;
        }

        /* Summary Box */
        .summary-box {
            width: 100%;
            background-color: #47389D;
            color: #ffffff;
            margin-bottom: 20px;
        }

        .summary-box td {
            padding: 15px 20px;
        }

        .summary-label {
            font-size: 10pt;
            text-transform: uppercase;
        }

        .summary-value {
            font-size: 18pt;
            font-weight: bold;
            text-align: right;
        }

        /* Data Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .data-table thead th {
            background-color: #47389D;
            color: #ffffff;
            font-size: 10pt;
            font-weight: bold;
            padding: 10px 8px;
            text-align: left;
            text-transform: uppercase;
        }

        .data-table tbody td {
            padding: 8px;
            border-bottom: 1px solid #edf2f7;
            font-size: 9.5pt;
            vertical-align: top;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .date-cell {
            white-space: nowrap;
            font-weight: bold;
        }

        .amount-cell {
            text-align: right;
            font-weight: bold;
            color: #e91e63;
        }

        .category-badge {
            background-color: #e3f2fd;
            color: #1976d2;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 8pt;
            display: inline-block;
        }

        .footer-content {
            text-align: center;
            font-size: 9pt;
            color: #a0aec0;
            border-top: 1px solid #edf2f7;
            padding-top: 10px;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #a0aec0;
            font-style: italic;
        }
    </style>
</head>

<body>
    <table class="header-table">
        <tr>
            <td class="report-title">Expenses Report</td>
            <td style="text-align: right; vertical-align: bottom; color: #718096; font-size: 10pt;">
                Generated: {{ now()->format('M d, Y') }}
            </td>
        </tr>
    </table>

    {{-- Filter Information --}}
    <div class="filter-info">
        <strong>Filters Applied:</strong>
        @if ($request->get('search'))
            | Search: {{ $request->get('search') }}
        @endif
        @if ($request->get('category'))
            | Category: {{ $request->get('category') }}
        @endif
        @if ($request->get('start_date'))
            | From: {{ $request->get('start_date') }}
        @endif
        @if ($request->get('end_date'))
            | To: {{ $request->get('end_date') }}
        @endif
        @if (
            !$request->get('search') &&
                !$request->get('category') &&
                !$request->get('start_date') &&
                !$request->get('end_date'))
            All Records
        @endif
    </div>

    {{-- Summary --}}
    <table class="summary-box">
        <tr>
            <td class="summary-label">Total Expenses: {{ $expenses->count() }} Records</td>
            <td class="summary-value">{{ number_format($totalAmount, 2) }} BDT</td>
        </tr>
    </table>

    {{-- Data Table --}}
    @if ($expenses->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 12%;">Date</th>
                    <th style="width: 25%;">Description</th>
                    <th style="width: 18%;">Category</th>
                    <th style="width: 15%;">Amount (BDT)</th>
                    <th style="width: 15%;">Method</th>
                    <th style="width: 15%;">Recorded By</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($expenses as $expense)
                    <tr>
                        <td class="date-cell">{{ $expense->expense_date->format('M d, Y') }}</td>
                        <td>{{ $expense->description }}</td>
                        <td>
                            <span class="category-badge">{{ $expense->chartOfAccount->name ?? 'General' }}</span>
                        </td>
                        <td class="amount-cell">{{ number_format($expense->amount, 2) }}</td>
                        <td>{{ ucwords(str_replace('_', ' ', $expense->payment_method)) ?: '-' }}</td>
                        <td>{{ $expense->creator->name ?? 'System' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            No expenses found matching the selected filters.
        </div>
    @endif

    <htmlpagefooter name="DocFooter">
        <div class="footer-content">
            Computer generated report
        </div>
    </htmlpagefooter>
</body>

</html>
