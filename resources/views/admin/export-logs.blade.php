<table border="1">
    <thead>
        <tr style="color: #000000;">
            <th>No.</th>
            <th>Student ID</th>
            <th>Name</th>
            <th>Contact</th>
            <th>Email</th>
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
                <td>{{ $log['contact'] ?? 'N/A' }}</td>
                <td>{{ $log['email'] ?? 'N/A' }}</td>
                <td>{{ $log['status'] }}</td>
                <td>{{ $log['time'] }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7">No logs found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
