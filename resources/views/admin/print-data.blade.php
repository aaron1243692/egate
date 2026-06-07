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
        .student-record { page-break-inside: avoid; break-inside: avoid; margin-top: 18px; }
        .student-record + .student-record { border-top: 1px solid #d1d5db; padding-top: 18px; }
        .student-record h2 { margin: 0 0 12px; font-size: 16px; color: #111827; }
        .student-form { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px 18px; max-width: 720px; }
        .field { display: flex; flex-direction: column; gap: 5px; }
        .field label { font-size: 12px; font-weight: 700; color: #374151; }
        .field .value { min-height: 18px; border-bottom: 1px solid #111827; padding: 4px 0 5px; font-size: 13px; color: #111827; }
        .empty-state { margin-top: 18px; font-size: 13px; color: #4b5563; }
    </style>
</head>
<body>
    <h1>Student Data</h1>
    <p>Printed at {{ $printedAt->format('F j, Y g:i A') }}</p>

    @php
        $value = fn ($field) => filled($field) ? $field : 'N/A';
        $roleLabel = fn ($role) => match ((string) $role) {
            '1' => 'Student',
            '2' => 'Employee',
            default => $value($role),
        };
        $printFields = function ($record) use ($value, $roleLabel) {
            return [
                'ST No / LRN' => $value($record->student_number ?: $record->lrn),
                'Name (FN MN, LN)' => $value($record->name),
                'Role' => $roleLabel($record->role),
                'Email' => $value($record->email),
                'Contact' => $value($record->contact),
                'Department' => $value($record->department),
                'Course' => $value($record->course),
                'School Level' => $value($record->school_level),
                'Grade Level' => $value($record->grade_level),
            ];
        };
    @endphp

    @forelse ($records as $index => $record)
        <section class="student-record">
            @unless ($individualPrint)
                <h2>Record {{ $index + 1 }}</h2>
            @endunless
            <div class="student-form">
                @foreach ($printFields($record) as $label => $fieldValue)
                    <div class="field">
                        <label>{{ $label }}</label>
                        <div class="value">{{ $fieldValue }}</div>
                    </div>
                @endforeach
            </div>
        </section>
    @empty
        <div class="empty-state">No records found.</div>
    @endforelse

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
