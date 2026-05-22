<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Student Data Print</title>
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
        h1 { margin: 0 0 8px; font-size: 24px; }
        p { margin: 0 0 16px; color: #4b5563; }
        .student-form { display: grid; grid-template-columns: minmax(0, 1fr); gap: 12px; margin-top: 18px; max-width: 520px; }
        .field { display: flex; flex-direction: column; gap: 5px; }
        .field label { font-size: 12px; font-weight: 700; color: #374151; }
        .field .value { min-height: 18px; border-bottom: 1px solid #111827; padding: 4px 0 5px; font-size: 13px; color: #111827; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #d1d5db; padding: 8px 10px; text-align: left; font-size: 13px; }
        th { background: #2563eb; color: #000; }
    </style>
</head>
<body>
    <h1>Student Data</h1>
    <p>Printed at {{ $printedAt->format('F j, Y g:i A') }}</p>

    @if ($individualPrint && $records->isNotEmpty())
        @php
            $record = $records->first();
            $value = fn ($field) => filled($field) ? $field : 'N/A';
        @endphp

        <div class="student-form">
            <div class="field">
                <label>Student ID</label>
                <div class="value">{{ $value($record->student_number) }}</div>
            </div>
            <div class="field">
                <label>LRN</label>
                <div class="value">{{ $value($record->lrn) }}</div>
            </div>
            <div class="field">
                <label>RFID</label>
                <div class="value">{{ $value($record->rfid) }}</div>
            </div>
            <div class="field">
                <label>Name</label>
                <div class="value">{{ $value($record->name) }}</div>
            </div>
            <div class="field">
                <label>Role</label>
                <div class="value">{{ $value($record->role) }}</div>
            </div>
            <div class="field">
                <label>Email</label>
                <div class="value">{{ $value($record->email) }}</div>
            </div>
            <div class="field">
                <label>Contact</label>
                <div class="value">{{ $value($record->contact) }}</div>
            </div>
            <div class="field">
                <label>Sex</label>
                <div class="value">{{ $value($record->sex) }}</div>
            </div>
            <div class="field">
                <label>Department</label>
                <div class="value">{{ $value($record->department) }}</div>
            </div>
            <div class="field">
                <label>Course</label>
                <div class="value">{{ $value($record->course) }}</div>
            </div>
            <div class="field">
                <label>School Level</label>
                <div class="value">{{ $value($record->school_level) }}</div>
            </div>
            <div class="field">
                <label>Grade Level</label>
                <div class="value">{{ $value($record->grade_level) }}</div>
            </div>
        </div>
    @else
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Student ID</th>
                <th>Name</th>
                <th>RFID</th>
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
                    <td>{{ $record->name ?: 'N/A' }}</td>
                    <td>{{ $record->rfid ?: 'N/A' }}</td>
                    <td>{{ $record->department ?: 'N/A' }}</td>
                    <td>{{ $record->course ?: 'N/A' }}</td>
                    <td>{{ $record->grade_level ?: 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @endif

    <script>
        const returnUrl = @json(url('admin/data'));
        let printRequested = false;
        let redirected = false;

        function redirectBackToData() {
            if (redirected) {
                return;
            }

            redirected = true;
            window.location.replace(returnUrl);
        }

        window.addEventListener('afterprint', () => {
            window.setTimeout(redirectBackToData, 100);
        });

        window.addEventListener('focus', () => {
            if (printRequested) {
                window.setTimeout(redirectBackToData, 300);
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
