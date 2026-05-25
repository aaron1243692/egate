<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Employee Monthly DTRs</title>
    <style>
        @page {
            size: letter;
            margin: 12mm;
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
            grid-template-columns: 58px 1fr 150px;
            gap: 12px;
            align-items: center;
            padding-bottom: 10px;
            border-bottom: 2px solid #111827;
        }

        .report-logo {
            width: 54px;
            height: 54px;
            object-fit: contain;
        }

        .agency {
            text-align: center;
        }

        .agency-name {
            margin: 0;
            font-family: "Times New Roman", serif;
            font-size: 17px;
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
            border: 1px solid #111827;
            font-size: 9px;
        }

        .document-code div {
            display: flex;
            justify-content: space-between;
            gap: 8px;
            padding: 3px 5px;
            border-bottom: 1px solid #111827;
        }

        .document-code div:last-child {
            border-bottom: 0;
        }

        .report-title {
            margin: 12px 0 10px;
            text-align: center;
        }

        .report-title h2 {
            margin: 0;
            font-size: 15px;
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
            margin-bottom: 10px;
            border: 1px solid #111827;
            border-right: 0;
            border-bottom: 0;
        }

        .meta-item {
            min-height: 38px;
            padding: 5px 7px;
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

        .dtr-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 9.5px;
        }

        .dtr-table th,
        .dtr-table td {
            border: 1px solid #111827;
            height: 16px;
            padding: 1px 3px;
            text-align: center;
            vertical-align: middle;
        }

        .dtr-table th {
            background: #f3f4f6;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .dtr-table tbody tr:nth-child(even) td {
            background: #fbfbfb;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            margin-top: 10px;
            border: 1px solid #111827;
            border-right: 0;
        }

        .summary-item {
            padding: 6px 8px;
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
            margin-top: 3px;
            font-size: 12px;
            font-weight: 700;
        }

        .certification {
            margin-top: 12px;
            color: #374151;
            font-size: 10px;
            text-align: justify;
        }

        .signature-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            margin-top: 22px;
        }

        .signature {
            text-align: center;
        }

        .signature-line {
            height: 26px;
            border-bottom: 1px solid #111827;
        }

        .signature-label {
            margin-top: 4px;
            color: #374151;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
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
    @forelse ($schedules as $schedule)
        @php($employee = $schedule['employee'])
        <main class="sheet">
            <header class="report-header">
                <img src="{{ public_path('images/olpcc-logo.png') }}" class="report-logo" alt="OLPCC logo">

                <div class="agency">
                    <h1 class="agency-name">OLPCC / OSMIS-eGATE</h1>
                    <p class="agency-subtitle">Employee Attendance Monitoring System</p>
                    <p class="agency-system">Official Monthly Daily Time Record</p>
                </div>

                <div class="document-code" aria-label="Document details">
                    <div><span>Report</span><strong>DTR</strong></div>
                    <div><span>Period</span><strong>{{ $schedule['monthName'] }}</strong></div>
                    <div><span>Printed</span><strong>{{ $printedAt->format('m/d/Y') }}</strong></div>
                </div>
            </header>

            <section class="report-title">
                <h2>Monthly Employee Daily Time Record</h2>
                <p>{{ $schedule['monthName'] }}</p>
            </section>

            <section class="meta-grid" aria-label="Employee information">
                <div class="meta-item meta-item--wide">
                    <span class="meta-label">Employee Name</span>
                    <span class="meta-value">{{ trim((string) $employee->name) ?: $employee->student_number }}</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Employee ID</span>
                    <span class="meta-value">{{ $employee->student_number ?: $employee->id }}</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Contact No.</span>
                    <span class="meta-value">{{ $employee->contact ?: 'N/A' }}</span>
                </div>
                <div class="meta-item meta-item--wide">
                    <span class="meta-label">Email Address</span>
                    <span class="meta-value">{{ $employee->email ?: 'N/A' }}</span>
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

            <table class="dtr-table">
                <colgroup>
                    <col style="width: 8%">
                    <col style="width: 10%">
                    <col style="width: 11%">
                    <col style="width: 11%">
                    <col style="width: 11%">
                    <col style="width: 11%">
                    <col style="width: 13%">
                    <col style="width: 14%">
                    <col style="width: 11%">
                </colgroup>
                <thead>
                    <tr>
                        <th colspan="2" rowspan="2">Day</th>
                        <th colspan="2">Morning</th>
                        <th colspan="2">Afternoon</th>
                        <th rowspan="2">Late</th>
                        <th rowspan="2">Undertime</th>
                        <th rowspan="2">Absent</th>
                    </tr>
                    <tr>
                        <th>In</th>
                        <th>Out</th>
                        <th>In</th>
                        <th>Out</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($schedule['rows'] as $row)
                        <tr>
                            <td>{{ $row['day'] }}</td>
                            <td>{{ $row['weekday'] }}</td>
                            <td>{{ $row['am_in'] }}</td>
                            <td>{{ $row['am_out'] }}</td>
                            <td>{{ $row['pm_in'] }}</td>
                            <td>{{ $row['pm_out'] }}</td>
                            <td>{{ $row['late'] }}</td>
                            <td>{{ $row['undertime'] }}</td>
                            <td>{{ $row['absence'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <section class="summary-grid" aria-label="Attendance summary">
                <div class="summary-item">
                    <span class="summary-label">Total Time</span>
                    <span class="summary-value">{{ $schedule['summary']['total_time'] }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Late</span>
                    <span class="summary-value">{{ $schedule['summary']['late'] }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Undertime</span>
                    <span class="summary-value">{{ $schedule['summary']['undertime'] }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Absences</span>
                    <span class="summary-value">{{ $schedule['summary']['absence'] }}</span>
                </div>
            </section>

            <p class="certification">
                I certify that the entries shown above are based on the attendance records captured by the OSMIS-eGATE system for the stated period.
            </p>

            <section class="signature-grid" aria-label="Signatures">
                <div class="signature">
                    <div class="signature-line"></div>
                    <div class="signature-label">Employee Signature</div>
                </div>
                <div class="signature">
                    <div class="signature-line"></div>
                    <div class="signature-label">Verified By</div>
                </div>
                <div class="signature">
                    <div class="signature-line"></div>
                    <div class="signature-label">Approved By</div>
                </div>
            </section>
        </main>
    @empty
        <div class="empty">No employees found.</div>
    @endforelse

    </body>
</html>

