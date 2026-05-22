<table border="1">
    <thead>
        <tr style="color: #000000;">
            <th>No.</th>
            <th>Student ID</th>
            <th>LRN</th>
            <th>RFID</th>
            <th>Name</th>
            <th>Role</th>
            <th>Email</th>
            <th>Contact</th>
            <th>Sex</th>
            <th>Department</th>
            <th>Course</th>
            <th>School Level</th>
            <th>Grade Level</th>
            <th>Image</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($records as $index => $record)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $record->student_number }}</td>
                <td>{{ $record->lrn }}</td>
                <td>{{ $record->rfid }}</td>
                <td>{{ $record->name }}</td>
                <td>{{ $record->role }}</td>
                <td>{{ $record->email }}</td>
                <td>{{ $record->contact }}</td>
                <td>{{ $record->sex }}</td>
                <td>{{ $record->department }}</td>
                <td>{{ $record->course }}</td>
                <td>{{ $record->school_level }}</td>
                <td>{{ $record->grade_level }}</td>
                <td>{{ $record->image }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="15">No records found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
