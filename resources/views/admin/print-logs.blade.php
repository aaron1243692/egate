<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Logs Print</title>
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
        body { font-family: Arial, sans-serif; margin: 24px; color: #111827; }
        h1 { margin: 0 0 4px; font-size: 24px; }
        .student-info { margin: 0 0 12px; color: #000; font-size: 14px; line-height: 1.5; }
        .student-info div { font-weight: 700; }
        p { margin: 0 0 16px; color: #4b5563; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #d1d5db; padding: 8px 10px; text-align: left; font-size: 13px; }
        th { background: #2563eb; color: #000; }
    </style>
</head>
<body>
    <h1>Logs</h1>
    @if ($studentName)
        <div class="student-info">
            <div>Name: {{ $studentName }}</div>
            <div>St No.: {{ $studentNumber ?: 'N/A' }}</div>
            <div>LRN: {{ $studentLrn ?: 'N/A' }}</div>
            <div>Contact: {{ $studentContact ?: 'N/A' }}</div>
            <div>Email: {{ $studentEmail ?: 'N/A' }}</div>
        </div>
    @endif
    <p>Printed at {{ $printedAt->format('F j, Y g:i A') }}</p>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Student ID</th>
                @if (! $studentName)
                <th>Name</th>
                @endif
                <th>Status</th>
                <th>DateTime</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($logs as $index => $log)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $log['student_id'] }}</td>
                    @if (! $studentName)
                    <td>{{ $log['name'] }}</td>
                    @endif
                    <td>{{ $log['status'] }}</td>
                    <td>{{ $log['time'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $studentName ? 4 : 5 }}">No logs found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <script>
        const returnUrl = @json(url('admin/logs'));
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
