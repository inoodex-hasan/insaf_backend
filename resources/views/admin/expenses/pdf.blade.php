<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Expense Report - #{{ $expense->id }}</title>
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

        /* Summary Box */
        .summary-table {
            width: 100%;
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            margin-bottom: 30px;
        }

        .summary-label {
            font-size: 10pt;
            color: #7f8c8d;
            padding: 15px 20px 5px 20px;
        }

        .summary-value {
            font-size: 18pt;
            font-weight: bold;
            color: #2c3e50;
            padding: 0 20px 15px 20px;
        }

        .amount-highlight {
            color: #e91e63;
            font-size: 22pt;
        }

        /* Section Layout */
        .section-header {
            background-color: #47389D;
            color: #ffffff;
            font-size: 11pt;
            font-weight: bold;
            padding: 8px 12px;
            margin-top: 20px;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .details-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #edf2f7;
            font-size: 10.5pt;
        }

        .label-cell {
            font-weight: bold;
            color: #4a5568;
            width: 35%;
            background-color: #fcfcfc;
        }

        .value-cell {
            width: 65%;
            text-align: right;
        }

        /* Notes Box */
        .notes-container {
            border-left: 4px solid #C2A56D;
            background-color: #fffdf7;
            padding: 15px;
            margin-top: 10px;
            font-style: italic;
        }

        .footer-content {
            text-align: center;
            font-size: 9pt;
            color: #a0aec0;
            border-top: 1px solid #edf2f7;
            padding-top: 10px;
        }
    </style>
</head>

<body>
    <table class="header-table">
        <tr>
            <td class="report-title">Expense Report</td>
            <td style="text-align: right; vertical-align: bottom; color: #718096;">
                ID: #{{ $expense->id }}
            </td>
        </tr>
    </table>

    <table class="summary-table">
        <tr>
            <td class="summary-label">TOTAL AMOUNT</td>
            <td class="summary-label" style="text-align: right;">EXPENSE DATE</td>
        </tr>
        <tr>
            <td class="summary-value amount-highlight">
                {{ number_format($expense->amount, 2) }} <span style="font-size: 12pt;">BDT</span>
            </td>
            <td class="summary-value" style="text-align: right;">
                {{ $expense->expense_date->format('M d, Y') }}
            </td>
        </tr>
    </table>

    <div class="section-header">Basic Information</div>
    <table class="details-table">
        <tr>
            <td class="label-cell">Description</td>
            <td class="value-cell">{{ $expense->description }}</td>
        </tr>
        <tr>
            <td class="label-cell">Category</td>
            <td class="value-cell">{{ $expense->category ?: 'General' }}</td>
        </tr>
    </table>

    <div class="section-header">Payment & Audit</div>
    <table class="details-table">
        <tr>
            <td class="label-cell">Payment Method</td>
            <td class="value-cell">{{ $expense->payment_method ?: 'N/A' }}</td>
        </tr>
        @if ($expense->office_account)
            <tr>
                <td class="label-cell">Account Name</td>
                <td class="value-cell">{{ $expense->office_account->account_name }}</td>
            </tr>
        @endif
        <tr>
            <td class="label-cell">Recorded By</td>
            <td class="value-cell">{{ $expense->creator->name ?? 'System' }}</td>
        </tr>
        <tr>
            <td class="label-cell">Record Created</td>
            <td class="value-cell">{{ $expense->created_at->format('M d, Y') }}</td>
        </tr>
    </table>

    @if ($expense->notes)
        <div class="section-header">Notes & Comments</div>
        <div class="notes-container">
            {{ $expense->notes }}
        </div>
    @endif

    <htmlpagefooter name="DocFooter">
        <div class="footer-content">
            Computer generated document • Generated on {{ now()->format('M d, Y') }}
        </div>
    </htmlpagefooter>
</body>

</html>
