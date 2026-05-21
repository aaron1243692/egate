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
                ->whereNotNull('year_level')
                ->where('year_level', '!=', '')
                ->distinct()
                ->orderBy('year_level')
                ->pluck('year_level')
                ->values(),
        ]);
    }

    public function fetchData(Request $request): JsonResponse
    {
        abort_unless(auth()->user()?->can('data.view'), 403);
        $records = $this->buildFilteredQuery($request)
            ->orderBy('last_name', $this->resolveNameSortDirection($request))
            ->orderBy('first_name', $this->resolveNameSortDirection($request))
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
            ->orderBy('last_name', $this->resolveNameSortDirection($request))
            ->orderBy('first_name', $this->resolveNameSortDirection($request))
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
            ->orderBy('last_name', $this->resolveNameSortDirection($request))
            ->orderBy('first_name', $this->resolveNameSortDirection($request))
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

            $record = EgateLog::query()->create($validated);

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
            $record->update($validated);

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
            'last_name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'sex' => ['nullable', 'string', 'max:20'],
            'department' => ['nullable', 'string', 'max:255'],
            'course' => ['nullable', 'string', 'max:255'],
            'year_level' => ['nullable', 'string', 'max:50'],
            'grade_level' => ['nullable', 'string', 'max:50'],
            'status' => ['nullable', 'string', 'max:10'],
            'image' => ['nullable', 'string'],
            'logged_at' => ['nullable', 'date'],
            'gate_name' => ['nullable', 'string', 'max:255'],
            'ip_address' => ['nullable', 'string', 'max:45'],
            'remarks' => ['nullable', 'string'],
        ];
    }

    private function resolveNameSortDirection(Request $request): string
    {
        return $request->get('name_sort') === 'desc' ? 'desc' : 'asc';
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
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('middle_name', 'like', "%{$search}%")
                        ->orWhere('department', 'like', "%{$search}%")
                        ->orWhere('course', 'like', "%{$search}%");
                });
            })
            ->when($department !== '', fn ($query) => $query->where('department', $department))
            ->when($course !== '', fn ($query) => $query->where('course', $course))
            ->when($yearLevel !== '', fn ($query) => $query->where('year_level', $yearLevel));
    }
}
