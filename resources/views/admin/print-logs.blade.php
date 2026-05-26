<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Student Logs Print</title>
    <style>
        @page {
            margin: 12mm;
            @bottom-center {
                content: "Page " counter(page) " of " counter(pages);
                font-family: Arial, sans-serif;
                font-size: 11px;
                color: #111827;
            }
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            color: #111827;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            line-height: 1.35;
            background: #fff;
        }

        .sheet {
            width: 100%;
            break-after: page;
            page-break-after: always;
        }

        @media screen {
            .sheet {
                margin-bottom: 28px;
            }
        }

        .sheet:last-child {
            break-after: auto;
            page-break-after: auto;
        }

        .report-header {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            gap: 12px;
            align-items: center;
            padding: 0 0 8px;
            border-bottom: 2px solid #111827;
        }

        .report-brand {
            display: flex;
            grid-column: 2;
            justify-self: center;
            gap: 12px;
            align-items: center;
        }

        .report-logo {
            width: 64px;
            height: 64px;
            object-fit: contain;
        }

        .agency {
            text-align: center;
        }

        .agency-name {
            margin: 0;
            font-family: "Times New Roman", serif;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.02em;
            text-transform: uppercase;
        }

        .agency-subtitle,
        .agency-system {
            margin: 2px 0 0;
            font-size: 10px;
            color: #374151;
        }

        .document-code {
            display: grid;
            grid-column: 3;
            justify-self: end;
            gap: 2px;
            font-size: 9px;
        }

        .document-code div {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            white-space: nowrap;
        }

        .report-title {
            margin: 8px 0 8px;
            text-align: center;
        }

        .report-title h2 {
            margin: 0;
            font-size: 13px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .report-title p {
            margin: 3px 0 0;
            color: #374151;
            font-size: 11px;
            font-weight: 700;
        }

        .meta-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0;
            margin-bottom: 8px;
            border: 1px solid #111827;
            border-right: 0;
            border-bottom: 0;
        }

        .meta-item {
            min-height: 30px;
            padding: 3px 6px;
            border-right: 1px solid #111827;
            border-bottom: 1px solid #111827;
        }

        .meta-item--wide {
            grid-column: span 2;
        }

        .meta-label {
            display: block;
            color: #4b5563;
            font-size: 8px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .meta-value {
            display: block;
            margin-top: 3px;
            color: #111827;
            font-size: 11px;
            font-weight: 700;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            margin-bottom: 8px;
            border: 1px solid #111827;
            border-right: 0;
        }

        .summary-item {
            padding: 5px 8px;
            border-right: 1px solid #111827;
            text-align: center;
        }

        .summary-label {
            display: block;
            color: #4b5563;
            font-size: 8px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .summary-value {
            display: block;
            margin-top: 2px;
            font-size: 12px;
            font-weight: 700;
        }

        .logs-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 10px;
        }

        .logs-table th,
        .logs-table td {
            border: 1px solid #111827;
            padding: 4px 5px;
            text-align: left;
            vertical-align: middle;
        }

        .logs-table th {
            background: #f3f4f6;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .logs-table tbody tr:nth-child(even) td {
            background: #fbfbfb;
        }

        .text-center {
            text-align: center;
        }

        .empty {
            padding: 18mm;
            color: #111827;
            font-size: 14px;
            font-weight: 700;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    @if ($isIndividual)
        @forelse ($reports as $report)
            @php($student = $report['student'])
            <main class="sheet">
                <header class="report-header">
                    <div class="report-brand">
                        <img src="{{ asset('images/olpcc-logo.png') }}" class="report-logo" alt="OLPCC logo">

                        <div class="agency">
                            <h1 class="agency-name">OLPCC / OSMIS-eGATE</h1>
                            <p class="agency-subtitle">Student Attendance Monitoring System</p>
                            <p class="agency-system">Official Student Gate Log Report</p>
                        </div>
                    </div>

                    <div class="document-code" aria-label="Document details">
                        <div><span>Report</span><strong>LOGS</strong></div>
                        <div><span>Mode</span><strong>Single</strong></div>
                        <div><span>Printed</span><strong>{{ $printedAt->format('m/d/Y') }}</strong></div>
                    </div>
                </header>

                <section class="report-title">
                    <h2>Student Attendance Logs</h2>
                    <p>{{ $periodLabel }}</p>
                </section>

                <section class="meta-grid" aria-label="Student information">
                    <div class="meta-item meta-item--wide">
                        <span class="meta-label">Student Name</span>
                        <span class="meta-value">{{ $student['name'] ?: 'N/A' }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Student ID</span>
                        <span class="meta-value">{{ $student['student_id'] ?: 'N/A' }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">LRN</span>
                        <span class="meta-value">{{ $student['lrn'] ?: 'N/A' }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Contact No.</span>
                        <span class="meta-value">{{ $student['contact'] ?: 'N/A' }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Email Address</span>
                        <span class="meta-value">{{ $student['email'] ?: 'N/A' }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Generated By</span>
                        <span class="meta-value">{{ auth()->user()->username ?? auth()->user()->email ?? 'System' }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Generated At</span>
                        <span class="meta-value">{{ $printedAt->format('F j, Y g:i A') }}</span>
                    </div>
                </section>

                <section class="summary-grid" aria-label="Log summary">
                    <div class="summary-item">
                        <span class="summary-label">Total Logs</span>
                        <span class="summary-value">{{ $report['summary']['total'] }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Time In</span>
                        <span class="summary-value">{{ $report['summary']['time_in'] }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Time Out</span>
                        <span class="summary-value">{{ $report['summary']['time_out'] }}</span>
                    </div>
                </section>

                <table class="logs-table">
                    <colgroup>
                        <col style="width: 10%">
                        <col style="width: 24%">
                        <col style="width: 22%">
                        <col style="width: 44%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th>Status</th>
                            <th>Student ID</th>
                            <th>DateTime</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($report['logs'] as $index => $log)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $log['status'] }}</td>
                                <td>{{ $log['student_id'] }}</td>
                                <td>{{ $log['time'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No logs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </main>
        @empty
            <div class="empty">No logs found.</div>
        @endforelse
    @else
        <main class="sheet">
            <header class="report-header">
                <div class="report-brand">
                    <img src="{{ asset('images/olpcc-logo.png') }}" class="report-logo" alt="OLPCC logo">

                    <div class="agency">
                        <h1 class="agency-name">OLPCC / OSMIS-eGATE</h1>
                        <p class="agency-subtitle">Student Attendance Monitoring System</p>
                        <p class="agency-system">Official Student Gate Log Report</p>
                    </div>
                </div>

                <div class="document-code" aria-label="Document details">
                    <div><span>Report</span><strong>LOGS</strong></div>
                    <div><span>Mode</span><strong>Bulk</strong></div>
                    <div><span>Printed</span><strong>{{ $printedAt->format('m/d/Y') }}</strong></div>
                </div>
            </header>

            <section class="report-title">
                <h2>Student Attendance Logs</h2>
                <p>{{ $periodLabel }}</p>
            </section>

            <section class="meta-grid" aria-label="Report information">
                <div class="meta-item meta-item--wide">
                    <span class="meta-label">Report Scope</span>
                    <span class="meta-value">All matching student logs</span>
                </div>
                <div class="meta-item meta-item--wide">
                    <span class="meta-label">Period</span>
                    <span class="meta-value">{{ $periodLabel }}</span>
                </div>
                <div class="meta-item meta-item--wide">
                    <span class="meta-label">Generated By</span>
                    <span class="meta-value">{{ auth()->user()->username ?? auth()->user()->email ?? 'System' }}</span>
                </div>
                <div class="meta-item meta-item--wide">
                    <span class="meta-label">Generated At</span>
                    <span class="meta-value">{{ $printedAt->format('F j, Y g:i A') }}</span>
                </div>
            </section>

            <section class="summary-grid" aria-label="Log summary">
                <div class="summary-item">
                    <span class="summary-label">Total Logs</span>
                    <span class="summary-value">{{ $summary['total'] }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Time In</span>
                    <span class="summary-value">{{ $summary['time_in'] }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Time Out</span>
                    <span class="summary-value">{{ $summary['time_out'] }}</span>
                </div>
            </section>

            <table class="logs-table">
                <colgroup>
                    <col style="width: 8%">
                    <col style="width: 18%">
                    <col style="width: 28%">
                    <col style="width: 18%">
                    <col style="width: 28%">
                </colgroup>
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th>ID No.</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>DateTime</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $index => $log)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $log['student_id'] }}</td>
                            <td>{{ $log['name'] }}</td>
                            <td>{{ $log['status'] }}</td>
                            <td>{{ $log['time'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No logs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </main>
    @endif

    <script>
        const returnUrl = @json(url('admin/logs') . (request()->except('student_id') ? '?' . http_build_query(request()->except('student_id')) : ''));
        let printRequested = false;
        let redirected = false;

        function redirectBackToLogs() {
            if (redirected) {
                return;
            }

            redirected = true;
            window.location.replace(returnUrl);
        }

        window.addEventListener('afterprint', () => {
            window.setTimeout(redirectBackToLogs, 100);
        });

        window.addEventListener('focus', () => {
            if (printRequested) {
                window.setTimeout(redirectBackToLogs, 300);
            }
        });

        window.addEventListener('load', () => {
            window.setTimeout(() => {
                printRequested = true;
                window.print();
            }, 100);
        });
    </script>
</body>
</html>
