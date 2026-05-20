<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Student Data Print</title>
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
    <h1>Student Data</h1>
    <p>Printed at {{ $printedAt->format('F j, Y g:i A') }}</p>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Student ID</th>
                <th>Name</th>
                <th>Department</th>
                <th>Course</th>
                <th>Year Level</th>
                <th>Grade Level</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($records as $index => $record)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $record->student_number }}</td>
                    <td>{{ trim(($record->last_name ?? '') . ', ' . ($record->first_name ?? '') . (($record->middle_name ?? '') ? ' ' . $record->middle_name : '')) }}</td>
                    <td>{{ $record->department ?: 'N/A' }}</td>
                    <td>{{ $record->course ?: 'N/A' }}</td>
                    <td>{{ $record->year_level ?: 'N/A' }}</td>
                    <td>{{ $record->grade_level ?: 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No records found.</td>
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
