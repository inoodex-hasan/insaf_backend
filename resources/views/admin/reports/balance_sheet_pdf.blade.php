<!-- <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Balance Sheet</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 18pt;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .header p {
            margin: 5px 0 0;
            color: #666;
            font-size: 9pt;
        }
        .two-column {
            width: 100%;
        }
        .column {
            width: 48%;
            float: left;
            padding: 0 1%;
        }
        .column:last-child {
            float: right;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-header {
            background: #f0f0f0;
            padding: 8px 10px;
            font-weight: bold;
            font-size: 11pt;
            text-transform: uppercase;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
        }
        .account-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 10px;
            border-bottom: 1px dotted #ddd;
        }
        .account-name {
            flex: 1;
        }
        .account-code {
            font-size: 7pt;
            color: #999;
            margin-left: 5px;
        }
        .account-balance {
            font-weight: bold;
            text-align: right;
            min-width: 100px;
        }
        .total-row {
            background: #f8f8f8;
            padding: 8px 10px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            border: 1px solid #ddd;
        }
        .grand-total {
            background: #333;
            color: white;
            padding: 10px;
            font-weight: bold;
            font-size: 12pt;
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }
        .net-profit {
            background: #e8f5e9;
            padding: 8px 10px;
            margin: 10px 0;
            border: 1px dashed #4caf50;
        }
        .balance-alert {
            margin-top: 20px;
            padding: 10px;
            text-align: center;
            font-weight: bold;
        }
        .balanced {
            background: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #4caf50;
        }
        .unbalanced {
            background: #ffebee;
            color: #c62828;
            border: 1px solid #f44336;
        }
        .clear {
            clear: both;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Balance Sheet</h1>
        <p>As of {{ \Carbon\Carbon::parse($asOfDate)->format('d F, Y') }}</p>
    </div>

    <div class="two-column">
        {{-- Left Column: Assets --}}
        <div class="column">
            <div class="section">
                <div class="section-header" style="color: #1976d2; background: #e3f2fd;">
                    <span>Assets</span>
                    <span>Amount (BDT)</span>
                </div>

                @forelse($data['asset'] as $asset)
                    <div class="account-row">
                        <div class="account-name">
                            {{ $asset->name }}
                            <span class="account-code">{{ $asset->code }}</span>
                        </div>
                        <div class="account-balance">{{ number_format($asset->balance, 2) }}</div>
                    </div>
                @empty
                    <div class="account-row">
                        <div class="account-name" style="color: #999; font-style: italic;">No assets recorded.</div>
                        <div class="account-balance">-</div>
                    </div>
                @endforelse

                <div class="total-row" style="color: #1976d2; border-color: #1976d2;">
                    <span>Total Assets</span>
                    <span>{{ number_format($totalAssets, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- Right Column: Liabilities & Equity --}}
        <div class="column">
            {{-- Liabilities --}}
            <div class="section">
                <div class="section-header" style="color: #d32f2f; background: #ffebee;">
                    <span>Liabilities</span>
                    <span>Amount (BDT)</span>
                </div>

                @forelse($data['liability'] as $liability)
                    <div class="account-row">
                        <div class="account-name">
                            {{ $liability->name }}
                            <span class="account-code">{{ $liability->code }}</span>
                        </div>
                        <div class="account-balance">{{ number_format($liability->balance, 2) }}</div>
                    </div>
                @empty
                    <div class="account-row">
                        <div class="account-name" style="color: #999; font-style: italic;">No liabilities recorded.</div>
                        <div class="account-balance">-</div>
                    </div>
                @endforelse

                <div class="total-row" style="color: #d32f2f; border-color: #d32f2f;">
                    <span>Total Liabilities</span>
                    <span>{{ number_format($totalLiabilities, 2) }}</span>
                </div>
            </div>

            {{-- Equity --}}
            <div class="section">
                <div class="section-header" style="color: #388e3c; background: #e8f5e9;">
                    <span>Equity</span>
                    <span>Amount (BDT)</span>
                </div>

                @foreach($data['equity'] as $equity)
                    <div class="account-row">
                        <div class="account-name">
                            {{ $equity->name }}
                            <span class="account-code">{{ $equity->code }}</span>
                        </div>
                        <div class="account-balance">{{ number_format($equity->balance, 2) }}</div>
                    </div>
                @endforeach

                {{-- Net Profit --}}
                <div class="net-profit">
                    <div style="display: flex; justify-content: space-between;">
                        <span>Net Profit / (Loss) <small style="font-weight: normal; color: #666;">(Current Period)</small></span>
                        <span style="font-weight: bold; color: {{ $netProfit >= 0 ? '#2e7d32' : '#c62828' }}">
                            {{ number_format($netProfit, 2) }}
                        </span>
                    </div>
                </div>

                <div class="total-row" style="color: #388e3c; border-color: #388e3c;">
                    <span>Total Equity</span>
                    <span>{{ number_format($totalEquity + $netProfit, 2) }}</span>
                </div>
            </div>

            {{-- Grand Total --}}
            <div class="grand-total">
                <span>Total Liabilities & Equity</span>
                <span>{{ number_format($totalLiabilities + $totalEquity + $netProfit, 2) }}</span>
            </div>
        </div>
    </div>

    <div class="clear"></div>

    {{-- Balance Verification --}}
    @if($isBalanced)
        <div class="balance-alert balanced">
            Statement is balanced - Total Assets = Total Liabilities & Equity
        </div>
    @else
        <div class="balance-alert unbalanced">
            Warning: Balance sheet is out of balance by BDT {{ number_format($diff, 2) }}
        </div>
    @endif
</body>
</html> -->

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Balance Sheet</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9pt;
            color: #1a1a2e;
            background: #ffffff;
            padding: 20px 24px;
        }

        /* ── Header ─────────────────────────────────── */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }
        .header-left {
            vertical-align: middle;
        }
        .header-right {
            text-align: right;
            vertical-align: middle;
        }
        .company-name {
            font-size: 16pt;
            font-weight: bold;
            color: #0d1b4b;
            letter-spacing: 0.5px;
        }
        .report-title {
            font-size: 10pt;
            color: #555;
            margin-top: 2px;
        }
        .date-badge {
            display: inline-block;
            background: #0d1b4b;
            color: #ffffff;
            font-size: 8pt;
            font-weight: bold;
            padding: 5px 12px;
            border-radius: 3px;
            letter-spacing: 0.5px;
        }
        .header-divider {
            width: 100%;
            border: none;
            border-top: 2px solid #0d1b4b;
            margin-bottom: 18px;
        }

        /* ── Main layout table ───────────────────────── */
        .main-table {
            width: 100%;
            border-collapse: collapse;
        }
        .col-assets {
            width: 49%;
            vertical-align: top;
            padding-right: 10px;
        }
        .col-divider {
            width: 2%;
            border-right: 1px solid #d0d5e8;
        }
        .col-liabilities {
            width: 49%;
            vertical-align: top;
            padding-left: 10px;
        }

        /* ── Section header ──────────────────────────── */
        .section-header {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }
        .section-header td {
            padding: 7px 10px;
            font-size: 9.5pt;
            font-weight: bold;
            letter-spacing: 0.4px;
            text-transform: uppercase;
        }
        .section-header .sh-label { text-align: left; }
        .section-header .sh-amount { text-align: right; }

        .sh-assets    { background: #e8edf8; color: #0d1b4b; }
        .sh-liab      { background: #fdecea; color: #8b1a1a; }
        .sh-equity    { background: #e6f4ea; color: #1a5c2a; }

        /* ── Sub-section title ───────────────────────── */
        .subsection-row {
            width: 100%;
            border-collapse: collapse;
        }
        .subsection-row td {
            padding: 6px 10px 3px;
            font-size: 8.5pt;
            font-weight: bold;
            color: #444;
            border-bottom: 1px solid #e0e4f0;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        /* ── Account row ─────────────────────────────── */
        .account-table {
            width: 100%;
            border-collapse: collapse;
        }
        .account-table tr {
            border-bottom: 1px dotted #dde2f0;
        }
        .account-table tr:last-child {
            border-bottom: none;
        }
        .account-table td {
            padding: 5px 10px;
            vertical-align: middle;
        }
        .td-name {
            text-align: left;
            color: #222;
        }
        .td-code {
            font-size: 7pt;
            color: #aaa;
            margin-left: 4px;
        }
        .td-amount {
            text-align: right;
            font-weight: bold;
            color: #222;
            white-space: nowrap;
        }
        .td-amount-muted {
            text-align: right;
            color: #666;
            white-space: nowrap;
        }
        .td-indent {
            padding-left: 22px;
        }
        .row-alt {
            background: #f7f8fc;
        }

        /* ── Subtotal row ────────────────────────────── */
        .subtotal-table {
            width: 100%;
            border-collapse: collapse;
        }
        .subtotal-table td {
            padding: 5px 10px;
            font-size: 8.5pt;
            font-weight: bold;
            border-top: 1px solid #b0bcd8;
            border-bottom: 1px solid #b0bcd8;
            background: #f0f3fb;
        }
        .subtotal-label { text-align: left; color: #0d1b4b; }
        .subtotal-amount { text-align: right; color: #0d1b4b; white-space: nowrap; }

        .subtotal-liab td {
            border-top: 1px solid #e0a0a0;
            border-bottom: 1px solid #e0a0a0;
            background: #fdf1f0;
        }
        .subtotal-liab .subtotal-label,
        .subtotal-liab .subtotal-amount { color: #8b1a1a; }

        .subtotal-equity td {
            border-top: 1px solid #90c49a;
            border-bottom: 1px solid #90c49a;
            background: #f0faf2;
        }
        .subtotal-equity .subtotal-label,
        .subtotal-equity .subtotal-amount { color: #1a5c2a; }

        /* ── Net profit row ──────────────────────────── */
        .net-profit-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2px;
        }
        .net-profit-table td {
            padding: 5px 10px;
            background: #fffbe6;
            border: 1px dashed #e0b800;
            font-size: 8.5pt;
        }
        .np-label { text-align: left; color: #5a4500; }
        .np-sub   { font-size: 7.5pt; color: #9a7a00; font-weight: normal; }
        .np-amount-pos { text-align: right; color: #1a5c2a; font-weight: bold; white-space: nowrap; }
        .np-amount-neg { text-align: right; color: #8b1a1a; font-weight: bold; white-space: nowrap; }

        /* ── Grand total ─────────────────────────────── */
        .grand-total-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        .grand-total-table td {
            padding: 8px 10px;
            background: #0d1b4b;
            color: #ffffff;
            font-size: 10pt;
            font-weight: bold;
        }
        .gt-label  { text-align: left; }
        .gt-amount { text-align: right; white-space: nowrap; }

        /* ── Balance alert ───────────────────────────── */
        .alert-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }
        .alert-table td {
            padding: 8px 14px;
            text-align: center;
            font-size: 8.5pt;
            font-weight: bold;
            border-radius: 3px;
        }
        .alert-balanced   { background: #e6f4ea; color: #1a5c2a; border: 1px solid #7ec899; }
        .alert-unbalanced { background: #fdecea; color: #8b1a1a; border: 1px solid #e89090; }

        /* ── Spacer ──────────────────────────────────── */
        .spacer { height: 10px; }
    </style>
</head>
<body>

    {{-- ═══ HEADER ════════════════════════════════════════════ --}}
    <table class="header-table">
        <tr>
            <td class="header-left">
                <!-- <div class="company-name">{{ $companyInfo->name ?? 'Your Company' }}</div> -->
                <div class="report-title">Statement of Financial Position &mdash; Balance Sheet</div>
            </td>
            <td class="header-right">
                <div class="date-badge">
                    As of {{ \Carbon\Carbon::parse($asOfDate)->format('d F Y') }}
                </div>
            </td>
        </tr>
    </table>
    <hr class="header-divider">

    {{-- ═══ TWO-COLUMN BODY ════════════════════════════════════ --}}
    <table class="main-table">
        <tr>

            {{-- ── LEFT: ASSETS ───────────────────────────── --}}
            <td class="col-assets">

                {{-- Assets header --}}
                <table class="section-header">
                    <tr>
                        <td class="sh-label sh-assets">Assets</td>
                        <td class="sh-amount sh-assets">Amount (BDT)</td>
                    </tr>
                </table>

                {{-- Current Assets --}}
                @php $currentAssets = $data['asset']->where('type', 'current'); @endphp
                @if($currentAssets->isNotEmpty())
                <table class="subsection-row"><tr><td>Current Assets</td></tr></table>
                <table class="account-table">
                    @foreach($currentAssets as $i => $asset)
                    <tr class="{{ $i % 2 === 0 ? '' : 'row-alt' }}">
                        <td class="td-name td-indent">
                            {{ $asset->name }}
                            <span class="td-code">{{ $asset->code }}</span>
                        </td>
                        <td class="td-amount-muted">{{ number_format($asset->balance, 2) }}</td>
                    </tr>
                    @endforeach
                </table>
                <table class="subtotal-table">
                    <tr>
                        <td class="subtotal-label">Total Current Assets</td>
                        <td class="subtotal-amount">{{ number_format($currentAssets->sum('balance'), 2) }}</td>
                    </tr>
                </table>
                <table><tr><td class="spacer"></td></tr></table>
                @endif

                {{-- Fixed Assets --}}
                @php $fixedAssets = $data['asset']->where('type', 'fixed'); @endphp
                @if($fixedAssets->isNotEmpty())
                <table class="subsection-row"><tr><td>Fixed Assets</td></tr></table>
                <table class="account-table">
                    @foreach($fixedAssets as $i => $asset)
                    <tr class="{{ $i % 2 === 0 ? '' : 'row-alt' }}">
                        <td class="td-name td-indent">
                            {{ $asset->name }}
                            <span class="td-code">{{ $asset->code }}</span>
                        </td>
                        <td class="td-amount-muted">{{ number_format($asset->balance, 2) }}</td>
                    </tr>
                    @endforeach
                </table>
                <table class="subtotal-table">
                    <tr>
                        <td class="subtotal-label">Total Fixed Assets</td>
                        <td class="subtotal-amount">{{ number_format($fixedAssets->sum('balance'), 2) }}</td>
                    </tr>
                </table>
                <table><tr><td class="spacer"></td></tr></table>
                @endif

                {{-- Other Assets (fallback — renders when no type grouping) --}}
                @php
                    $ungrouped = $data['asset']->whereNotIn('type', ['current', 'fixed']);
                @endphp
                @if($ungrouped->isNotEmpty())
                <table class="account-table">
                    @foreach($ungrouped as $i => $asset)
                    <tr class="{{ $i % 2 === 0 ? '' : 'row-alt' }}">
                        <td class="td-name td-indent">
                            {{ $asset->name }}
                            <span class="td-code">{{ $asset->code }}</span>
                        </td>
                        <td class="td-amount-muted">{{ number_format($asset->balance, 2) }}</td>
                    </tr>
                    @endforeach
                </table>
                <table><tr><td class="spacer"></td></tr></table>
                @endif

                {{-- TOTAL ASSETS --}}
                <table class="grand-total-table">
                    <tr>
                        <td class="gt-label">Total Assets</td>
                        <td class="gt-amount">{{ number_format($totalAssets, 2) }}</td>
                    </tr>
                </table>

            </td>

            {{-- ── COLUMN DIVIDER ──────────────────────────── --}}
            <td class="col-divider">&nbsp;</td>

            {{-- ── RIGHT: LIABILITIES & EQUITY ────────────── --}}
            <td class="col-liabilities">

                {{-- ── LIABILITIES ──────────────── --}}
                <table class="section-header">
                    <tr>
                        <td class="sh-label sh-liab">Liabilities</td>
                        <td class="sh-amount sh-liab">Amount (BDT)</td>
                    </tr>
                </table>

                {{-- Current Liabilities --}}
                @php $currentLiab = $data['liability']->where('type', 'current'); @endphp
                @if($currentLiab->isNotEmpty())
                <table class="subsection-row"><tr><td>Current Liabilities</td></tr></table>
                <table class="account-table">
                    @foreach($currentLiab as $i => $liability)
                    <tr class="{{ $i % 2 === 0 ? '' : 'row-alt' }}">
                        <td class="td-name td-indent">
                            {{ $liability->name }}
                            <span class="td-code">{{ $liability->code }}</span>
                        </td>
                        <td class="td-amount-muted">{{ number_format($liability->balance, 2) }}</td>
                    </tr>
                    @endforeach
                </table>
                <table class="subtotal-table subtotal-liab">
                    <tr>
                        <td class="subtotal-label">Total Current Liabilities</td>
                        <td class="subtotal-amount">{{ number_format($currentLiab->sum('balance'), 2) }}</td>
                    </tr>
                </table>
                <table><tr><td class="spacer"></td></tr></table>
                @endif

                {{-- Long-term Liabilities --}}
                @php $longLiab = $data['liability']->where('type', 'longterm'); @endphp
                @if($longLiab->isNotEmpty())
                <table class="subsection-row"><tr><td>Long-term Liabilities</td></tr></table>
                <table class="account-table">
                    @foreach($longLiab as $i => $liability)
                    <tr class="{{ $i % 2 === 0 ? '' : 'row-alt' }}">
                        <td class="td-name td-indent">
                            {{ $liability->name }}
                            <span class="td-code">{{ $liability->code }}</span>
                        </td>
                        <td class="td-amount-muted">{{ number_format($liability->balance, 2) }}</td>
                    </tr>
                    @endforeach
                </table>
                <table class="subtotal-table subtotal-liab">
                    <tr>
                        <td class="subtotal-label">Total Long-term Liabilities</td>
                        <td class="subtotal-amount">{{ number_format($longLiab->sum('balance'), 2) }}</td>
                    </tr>
                </table>
                <table><tr><td class="spacer"></td></tr></table>
                @endif

                {{-- Ungrouped Liabilities --}}
                @php $ungroupedLiab = $data['liability']->whereNotIn('type', ['current', 'longterm']); @endphp
                @if($ungroupedLiab->isNotEmpty())
                <table class="account-table">
                    @foreach($ungroupedLiab as $i => $liability)
                    <tr class="{{ $i % 2 === 0 ? '' : 'row-alt' }}">
                        <td class="td-name td-indent">
                            {{ $liability->name }}
                            <span class="td-code">{{ $liability->code }}</span>
                        </td>
                        <td class="td-amount-muted">{{ number_format($liability->balance, 2) }}</td>
                    </tr>
                    @endforeach
                </table>
                <table><tr><td class="spacer"></td></tr></table>
                @endif

                {{-- Total Liabilities --}}
                <table class="subtotal-table subtotal-liab">
                    <tr>
                        <td class="subtotal-label" style="font-size:9pt;">Total Liabilities</td>
                        <td class="subtotal-amount" style="font-size:9pt;">{{ number_format($totalLiabilities, 2) }}</td>
                    </tr>
                </table>

                <table><tr><td style="height:14px;"></td></tr></table>

                {{-- ── EQUITY ────────────────────── --}}
                <table class="section-header">
                    <tr>
                        <td class="sh-label sh-equity">Equity</td>
                        <td class="sh-amount sh-equity">Amount (BDT)</td>
                    </tr>
                </table>

                @if($data['equity']->isEmpty())
                <table class="account-table">
                    <tr>
                        <td class="td-name" style="color:#aaa; font-style:italic;">No equity accounts recorded.</td>
                        <td class="td-amount-muted">—</td>
                    </tr>
                </table>
                @else
                <table class="account-table">
                    @foreach($data['equity'] as $i => $equity)
                    <tr class="{{ $i % 2 === 0 ? '' : 'row-alt' }}">
                        <td class="td-name td-indent">
                            {{ $equity->name }}
                            <span class="td-code">{{ $equity->code }}</span>
                        </td>
                        <td class="td-amount-muted">{{ number_format($equity->balance, 2) }}</td>
                    </tr>
                    @endforeach
                </table>
                @endif

                {{-- Net Profit --}}
                <table class="net-profit-table">
                    <tr>
                        <td class="np-label">
                            Net Profit / (Loss)
                            <span class="np-sub">&nbsp; Current Period</span>
                        </td>
                        <td class="{{ $netProfit >= 0 ? 'np-amount-pos' : 'np-amount-neg' }}">
                            {{ number_format($netProfit, 2) }}
                        </td>
                    </tr>
                </table>

                <table class="subtotal-table subtotal-equity" style="margin-top:4px;">
                    <tr>
                        <td class="subtotal-label" style="font-size:9pt;">Total Equity</td>
                        <td class="subtotal-amount" style="font-size:9pt;">{{ number_format($totalEquity + $netProfit, 2) }}</td>
                    </tr>
                </table>

                <table><tr><td style="height:8px;"></td></tr></table>

                {{-- GRAND TOTAL: Liabilities + Equity --}}
                <table class="grand-total-table">
                    <tr>
                        <td class="gt-label">Total Liabilities &amp; Equity</td>
                        <td class="gt-amount">{{ number_format($totalLiabilities + $totalEquity + $netProfit, 2) }}</td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>

    {{-- ═══ BALANCE VERIFICATION ═══════════════════════════════ --}}
    <table class="alert-table">
        <tr>
            @if($isBalanced)
            <td class="alert-balanced">
                &#10003;&nbsp; Statement is balanced &mdash; Total Assets = Total Liabilities &amp; Equity
            </td>
            @else
            <td class="alert-unbalanced">
                &#9888;&nbsp; Warning: Balance sheet is out of balance by BDT {{ number_format($diff, 2) }}
            </td>
            @endif
        </tr>
    </table>

</body>
</html>
