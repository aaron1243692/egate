@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<main class="w-full p-2 gap-3 flex flex-1 flex-col overflow-auto bg-slate-50">
    <div class="flex flex-col gap-1">
        <h3 class="text-lg font-semibold text-slate-800">Dashboard</h3>
        <p class="text-sm text-slate-500">Filtered operational summary for logs and student data.</p>
    </div>

    <form method="GET" action="{{ route('admin.dashboard') }}" class="w-full rounded-md border border-slate-200 bg-white p-3 shadow-sm">
        <div class="grid gap-2 md:grid-cols-3 xl:grid-cols-6">
            <div class="flex flex-col gap-1">
                <label for="status" class="text-sm font-medium text-slate-700">Status</label>
                <select id="status" name="status" class="w-full rounded-full border border-slate-300 bg-white px-3 py-1.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    <option value="">All status</option>
                    <option value="1" @selected($filters['status'] === '1')>Log In</option>
                    <option value="0" @selected($filters['status'] === '0')>Log Out</option>
                    <option value="2" @selected($filters['status'] === '2')>N/A</option>
                </select>
            </div>

            <div class="flex flex-col gap-1">
                <label for="department" class="text-sm font-medium text-slate-700">Department</label>
                <select id="department" name="department" class="w-full rounded-full border border-slate-300 bg-white px-3 py-1.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    <option value="">All departments</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department }}" @selected($filters['department'] === $department)>{{ $department }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col gap-1">
                <label for="course" class="text-sm font-medium text-slate-700">Course</label>
                <select id="course" name="course" class="w-full rounded-full border border-slate-300 bg-white px-3 py-1.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    <option value="">All courses</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course }}" @selected($filters['course'] === $course)>{{ $course }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col gap-1">
                <label for="year_level" class="text-sm font-medium text-slate-700">Year Level</label>
                <select id="year_level" name="year_level" class="w-full rounded-full border border-slate-300 bg-white px-3 py-1.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    <option value="">All year levels</option>
                    @foreach ($yearLevels as $yearLevel)
                        <option value="{{ $yearLevel }}" @selected($filters['year_level'] === $yearLevel)>{{ $yearLevel }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col gap-1">
                <label for="date_from" class="text-sm font-medium text-slate-700">From</label>
                <input id="date_from" name="date_from" type="date" value="{{ $filters['date_from'] }}" class="w-full rounded-full border border-slate-300 px-3 py-1.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
            </div>

            <div class="flex flex-col gap-1">
                <label for="date_to" class="text-sm font-medium text-slate-700">To</label>
                <input id="date_to" name="date_to" type="date" value="{{ $filters['date_to'] }}" class="w-full rounded-full border border-slate-300 px-3 py-1.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
            </div>
        </div>

        <div class="mt-3 flex flex-wrap justify-end gap-2">
            <a href="{{ route('admin.dashboard') }}" class="rounded-full border border-slate-300 px-4 py-1.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Reset</a>
            <button type="submit" class="rounded-full bg-blue-600 px-4 py-1.5 text-sm font-semibold text-white transition hover:bg-blue-700">Apply Filter</button>
        </div>
    </form>

    <section class="rounded-md border border-slate-200 bg-white p-3 shadow-sm">
        <div class="mb-3 flex items-center justify-between">
            <h4 class="text-base font-semibold text-slate-800">Logs Summary</h4>
            <span class="text-xs font-medium uppercase tracking-wide text-slate-400">Priority</span>
        </div>

        <div class="grid gap-2 md:grid-cols-5">
            <div class="rounded-md border border-slate-200 p-3">
                <p class="text-xs font-medium text-slate-500">Total Logs</p>
                <p class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($logSummary['total']) }}</p>
            </div>
            <div class="rounded-md border border-emerald-200 p-3">
                <p class="text-xs font-medium text-emerald-600">Log In</p>
                <p class="mt-1 text-2xl font-bold text-emerald-700">{{ number_format($logSummary['log_in']) }}</p>
            </div>
            <div class="rounded-md border border-rose-200 p-3">
                <p class="text-xs font-medium text-rose-600">Log Out</p>
                <p class="mt-1 text-2xl font-bold text-rose-700">{{ number_format($logSummary['log_out']) }}</p>
            </div>
            <div class="rounded-md border border-amber-200 p-3">
                <p class="text-xs font-medium text-amber-600">N/A</p>
                <p class="mt-1 text-2xl font-bold text-amber-700">{{ number_format($logSummary['na']) }}</p>
            </div>
            <div class="rounded-md border border-blue-200 p-3">
                <p class="text-xs font-medium text-blue-600">Students</p>
                <p class="mt-1 text-2xl font-bold text-blue-700">{{ number_format($logSummary['unique_students']) }}</p>
            </div>
        </div>
    </section>

    <section class="rounded-md border border-slate-200 bg-white p-3 shadow-sm">
        <h4 class="mb-3 text-base font-semibold text-slate-800">Data Summary</h4>
        <div class="grid gap-2 sm:grid-cols-2">
            <div class="rounded-md border border-slate-200 p-3">
                <p class="text-xs font-medium text-slate-500">Students</p>
                <p class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($dataSummary['total']) }}</p>
            </div>
            <div class="rounded-md border border-slate-200 p-3">
                <p class="text-xs font-medium text-slate-500">Departments</p>
                <p class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($dataSummary['departments']) }}</p>
            </div>
            <div class="rounded-md border border-slate-200 p-3">
                <p class="text-xs font-medium text-slate-500">Courses</p>
                <p class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($dataSummary['courses']) }}</p>
            </div>
            <div class="rounded-md border border-slate-200 p-3">
                <p class="text-xs font-medium text-slate-500">Year Levels</p>
                <p class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($dataSummary['year_levels']) }}</p>
            </div>
        </div>

        <h5 class="mt-4 mb-2 text-sm font-semibold text-slate-700">Department Breakdown</h5>
        <div class="space-y-2">
            @forelse ($departmentBreakdown as $department)
                <div class="flex items-center justify-between rounded-md border border-slate-200 px-3 py-2">
                    <span class="text-sm text-slate-700">{{ $department->label }}</span>
                    <span class="text-sm font-semibold text-slate-900">{{ number_format($department->total) }}</span>
                </div>
            @empty
                <p class="rounded-md border border-slate-200 p-3 text-sm text-slate-500">No department data found.</p>
            @endforelse
        </div>
    </section>
</main>
@endsection
