<table border="1">
    <thead>
        <tr style="color: #000000;">
            <th>No.</th>
            <th>Student ID</th>
            <th>LRN</th>
            <th>Last Name</th>
            <th>First Name</th>
            <th>Middle Name</th>
            <th>Sex</th>
            <th>Department</th>
            <th>Course</th>
            <th>Grade Level</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($records as $index => $record)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $record->student_number }}</td>
                <td>{{ $record->lrn }}</td>
                <td>{{ $record->last_name }}</td>
                <td>{{ $record->first_name }}</td>
                <td>{{ $record->middle_name }}</td>
                <td>{{ $record->sex }}</td>
                <td>{{ $record->department }}</td>
                <td>{{ $record->course }}</td>
                <td>{{ $record->grade_level }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="10">No records found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
