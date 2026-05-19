<?php

namespace App\Http\Controllers;

use App\Models\EgateLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
        $search = trim((string) $request->get('search', ''));
        $department = trim((string) $request->get('department', ''));
        $course = trim((string) $request->get('course', ''));
        $yearLevel = trim((string) $request->get('year_level', ''));

        $records = EgateLog::query()
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
            ->when($yearLevel !== '', fn ($query) => $query->where('year_level', $yearLevel))
            ->orderBy('last_name')
            ->orderBy('first_name')
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
}
