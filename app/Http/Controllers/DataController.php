<?php

namespace App\Http\Controllers;

use App\Models\EgateLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Validation\ValidationException;

class DataController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()?->can('data.view'), 403);
        return view('admin.data', [
            'departments' => EgateLog::query()
                ->whereNotNull('department')
                ->where('department', '!=', '')
                ->distinct()
                ->orderBy('department')
                ->pluck('department')
                ->values(),
            'courses' => EgateLog::query()
                ->whereNotNull('course')
                ->where('course', '!=', '')
                ->distinct()
                ->orderBy('course')
                ->pluck('course')
                ->values(),
            'yearLevels' => EgateLog::query()
                ->whereNotNull('grade_level')
                ->where('grade_level', '!=', '')
                ->distinct()
                ->orderBy('grade_level')
                ->pluck('grade_level')
                ->values(),
        ]);
    }

    public function fetchData(Request $request): JsonResponse
    {
        abort_unless(auth()->user()?->can('data.view'), 403);
        $records = $this->buildFilteredQuery($request)
            ->orderBy('name', $this->resolveNameSortDirection($request))
            ->paginate(10);

        return response()->json($records);
    }

    public function show(int $id): JsonResponse
    {
        abort_unless(auth()->user()?->can('data.view'), 403);
        $record = EgateLog::query()->find($id);

        if (! $record) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'record' => $record,
        ]);
    }

    public function print(Request $request)
    {
        abort_unless(auth()->user()?->can('data.print'), 403);

        $records = $this->buildFilteredQuery($request)
            ->when($request->filled('record_id'), fn ($query) => $query->whereKey($request->integer('record_id')))
            ->orderBy('name', $this->resolveNameSortDirection($request))
            ->get();

        return view('admin.print-data', [
            'records' => $records,
            'individualPrint' => $request->filled('record_id'),
            'printedAt' => now(),
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        abort_unless(auth()->user()?->can('data.export'), 403);

        $records = $this->buildFilteredQuery($request)
            ->orderBy('name', $this->resolveNameSortDirection($request))
            ->get();

        $filename = 'student-data-' . now()->format('Y-m-d_H-i-s') . '.xls';
        $html = view('admin.export-data', [
            'records' => $records,
        ])->render();

        return response()->streamDownload(function () use ($html) {
            echo $html;
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        abort_unless(auth()->user()?->can('data.create'), 403);

        try {
            $validated = $request->validate($this->rules());

            $record = EgateLog::query()->create($this->dataForSave($validated));

            return response()->json([
                'success' => true,
                'message' => 'Student data created successfully.',
                'record' => $record,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => collect($e->errors())->flatten()->first() ?? 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating student data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        abort_unless(auth()->user()?->can('data.update'), 403);

        try {
            $record = EgateLog::query()->findOrFail($id);
            $validated = $request->validate($this->rules($record->id));
            $record->update($this->dataForSave($validated));

            return response()->json([
                'success' => true,
                'message' => 'Student data updated successfully.',
                'record' => $record->fresh(),
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => collect($e->errors())->flatten()->first() ?? 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating student data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        abort_unless(auth()->user()?->can('data.delete'), 403);

        try {
            $record = EgateLog::query()->findOrFail($id);
            $record->delete();

            return response()->json([
                'success' => true,
                'message' => 'Student data deleted successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting student data: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function rules(?int $ignoreId = null): array
    {
        return [
            'student_number' => ['required', 'string', 'max:255', 'unique:egate_data,student_number' . ($ignoreId ? ',' . $ignoreId : '')],
            'lrn' => ['nullable', 'digits_between:1,20'],
            'rfid' => ['nullable', 'integer'],
            'name' => ['required', 'string', 'max:150'],
            'role' => ['nullable', 'integer', 'in:1,2'],
            'email' => ['nullable', 'email', 'max:100'],
            'contact' => ['nullable', 'string', 'max:100'],
            'sex' => ['nullable', 'string', 'max:20'],
            'department' => ['nullable', 'string', 'max:100'],
            'course' => ['nullable', 'string', 'max:100'],
            'school_level' => ['nullable', 'string', 'max:100'],
            'grade_level' => ['nullable', 'string', 'max:100'],
        ];
    }

    private function dataForSave(array $validated): array
    {
        return [
            'student_number' => $validated['student_number'],
            'lrn' => $validated['lrn'] ?? null,
            'rfid' => $validated['rfid'] ?? null,
            'name' => $this->normalizeName($validated['name']),
            'role' => $validated['role'] ?? null,
            'email' => $validated['email'] ?? null,
            'contact' => $validated['contact'] ?? null,
            'sex' => $validated['sex'] ?? null,
            'department' => $validated['department'] ?? null,
            'course' => $validated['course'] ?? null,
            'school_level' => $validated['school_level'] ?? null,
            'grade_level' => $validated['grade_level'] ?? null,
        ];
    }

    private function resolveNameSortDirection(Request $request): string
    {
        return $request->get('name_sort') === 'desc' ? 'desc' : 'asc';
    }

    private function normalizeName(string $name): string
    {
        $name = trim(preg_replace('/\s+/', ' ', $name) ?? $name);
        $parts = array_values(array_filter(array_map('trim', explode(',', $name))));

        if (count($parts) === 3) {
            return "{$parts[0]} {$parts[1]}, {$parts[2]}";
        }

        return $name;
    }

    private function buildFilteredQuery(Request $request): Builder
    {
        $search = trim((string) $request->get('search', ''));
        $department = trim((string) $request->get('department', ''));
        $course = trim((string) $request->get('course', ''));
        $yearLevel = trim((string) $request->get('year_level', ''));

        return EgateLog::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery
                        ->where('student_number', 'like', "%{$search}%")
                        ->orWhere('lrn', 'like', "%{$search}%")
                        ->orWhere('rfid', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('role', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('contact', 'like', "%{$search}%")
                        ->orWhere('department', 'like', "%{$search}%")
                        ->orWhere('course', 'like', "%{$search}%")
                        ->orWhere('school_level', 'like', "%{$search}%")
                        ->orWhere('grade_level', 'like', "%{$search}%");
                });
            })
            ->when($department !== '', fn ($query) => $query->where('department', $department))
            ->when($course !== '', fn ($query) => $query->where('course', $course))
            ->when($yearLevel !== '', fn ($query) => $query->where('grade_level', $yearLevel));
    }
}
