<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Logs Print</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; color: #111827; }
        h1 { margin: 0 0 8px; font-size: 24px; }
        p { margin: 0 0 16px; color: #4b5563; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #d1d5db; padding: 8px 10px; text-align: left; font-size: 13px; }
        th { background: #2563eb; color: #fff; }
    </style>
</head>
<body>
    <h1>Logs</h1>
    <p>Printed at {{ $printedAt->format('F j, Y g:i A') }}</p>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Student ID</th>
                <th>Name</th>
                <th>Status</th>
                <th>DateTime</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($logs as $index => $log)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $log['student_id'] }}</td>
                    <td>{{ $log['name'] }}</td>
                    <td>{{ $log['status'] }}</td>
                    <td>{{ $log['time'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No logs found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <script>
        window.addEventListener('load', () => {
            window.print();
        });
    </script>
</body>
</html>
