<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Monthly DTR</title>
    <style>
        @page {
            size: letter;
            margin: 0;
        }

        body {
            box-sizing: border-box;
            font-family: "Times New Roman", serif;
            color: #000;
            margin: 0;
            padding: 14mm;
        }

        .sheet {
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            height: 18px;
            padding: 1px 3px;
            text-align: center;
            vertical-align: middle;
        }

        th {
            font-weight: 400;
        }

        .day-heading {
            font-size: 14px;
        }

        .info {
            margin-bottom: 10px;
            font-size: 12px;
            line-height: 1.35;
        }

        .summary {
            margin-top: 8px;
            font-size: 12px;
            line-height: 1.45;
        }
    </style>
</head>
<body>
    <main class="sheet">
        <div class="info">
            <div>{{ $monthName }}</div>
            <div>Name: {{ trim((string) $employee->name) ?: $employee->student_number }}</div>
            <div>Contact: {{ $employee->contact ?: 'N/A' }}</div>
            <div>Email: {{ $employee->email ?: 'N/A' }}</div>
            <div>Printed at {{ $printedAt->format('F j, Y g:i A') }}</div>
        </div>

        <table>
            <colgroup>
                <col style="width: 10%">
                <col style="width: 10%">
                <col style="width: 11%">
                <col style="width: 11%">
                <col style="width: 11%">
                <col style="width: 11%">
                <col style="width: 13%">
                <col style="width: 12%">
                <col style="width: 11%">
            </colgroup>
            <thead>
                <tr>
                    <th colspan="2" rowspan="2" class="day-heading">Day</th>
                    <th colspan="2">AM</th>
                    <th colspan="2">PM</th>
                    <th rowspan="2">Late</th>
                    <th rowspan="2">Under time</th>
                    <th rowspan="2">Abs</th>
                </tr>
                <tr>
                    <th>In</th>
                    <th>Out</th>
                    <th>In</th>
                    <th>Out</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $row)
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

        <div class="summary">
            <div>Total Time: {{ $summary['total_time'] }}</div>
            <div>Late: {{ $summary['late'] }}</div>
            <div>Undertime: {{ $summary['undertime'] }}</div>
            <div>Absents: {{ $summary['absence'] }}</div>
        </div>

    </main>

    <script>
        const returnUrl = @json(url('admin/employee-logs'));
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
