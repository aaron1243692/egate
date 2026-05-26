@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@php
    $logTotal = max((int) $logSummary['total'], 0);
    $dataTotal = max((int) $dataSummary['total'], 0);
    $departmentMax = max((int) $departmentBreakdown->max('total'), 1);
    $statusVisuals = [
        [
            'label' => 'Time In',
            'value' => (int) $logSummary['log_in'],
            'class' => 'is-in',
        ],
        [
            'label' => 'Time Out',
            'value' => (int) $logSummary['log_out'],
            'class' => 'is-out',
        ],
        [
            'label' => 'N/A',
            'value' => (int) $logSummary['na'],
            'class' => 'is-na',
        ],
    ];
@endphp

<main class="eg-dash">
    <section class="eg-dash-hero">
        <div class="eg-dash-hero-copy">
            <span class="eg-dash-eyebrow">
                <span class="eg-dash-live-dot" aria-hidden="true"></span>
                Admin Monitoring Panel
            </span>
            <h1>EGate Dashboard</h1>
            <p>Real-time overview of student gate logs and registered data.</p>
        </div>

        <div class="eg-dash-hero-side">
            <div class="eg-dash-clock">
                <span data-eg-dash-date>{{ now()->format('F j, Y') }}</span>
                <strong data-eg-dash-time>{{ now()->format('h:i A') }}</strong>
            </div>

            <div class="eg-dash-actions" aria-label="Gate quick actions">
                @if (canAccessWithParent(auth()->user(), 'time.in'))
                    <a href="{{ route('in') }}" class="eg-dash-action eg-dash-action--in">
                        <span class="eg-dash-action-icon">IN</span>
                        <span>Time In</span>
                    </a>
                @endif
                @if (canAccessWithParent(auth()->user(), 'time.out'))
                    <a href="{{ route('out') }}" class="eg-dash-action eg-dash-action--out">
                        <span class="eg-dash-action-icon">OUT</span>
                        <span>Time Out</span>
                    </a>
                @endif
            </div>
        </div>
    </section>

    <form id="dashboard-filter-form" method="GET" action="{{ route('admin.dashboard') }}" class="eg-dash-filter">
        <div class="eg-dash-section-head">
            <div>
                <h2>Dashboard Filters</h2>
                <p>Refine monitoring results by student group and log period.</p>
            </div>
            <span class="eg-dash-pill">Filtered View</span>
        </div>

        <div class="eg-dash-filter-grid">
            <label class="eg-dash-field" for="status">
                <span>Status</span>
                <select id="status" name="status">
                    <option value="">All status</option>
                    <option value="1" @selected($filters['status'] === '1')>Time In</option>
                    <option value="0" @selected($filters['status'] === '0')>Time Out</option>
                    <option value="2" @selected($filters['status'] === '2')>N/A</option>
                </select>
            </label>

            <label class="eg-dash-field" for="department">
                <span>Department</span>
                <select id="department" name="department">
                    <option value="">All departments</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department }}" @selected($filters['department'] === $department)>{{ $department }}</option>
                    @endforeach
                </select>
            </label>

            <label class="eg-dash-field" for="course">
                <span>Course</span>
                <select id="course" name="course">
                    <option value="">All courses</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course }}" @selected($filters['course'] === $course)>{{ $course }}</option>
                    @endforeach
                </select>
            </label>

            <label class="eg-dash-field" for="year_level">
                <span>Year Level</span>
                <select id="year_level" name="year_level">
                    <option value="">All year levels</option>
                    @foreach ($yearLevels as $yearLevel)
                        <option value="{{ $yearLevel }}" @selected($filters['year_level'] === $yearLevel)>{{ $yearLevel }}</option>
                    @endforeach
                </select>
            </label>

            <label class="eg-dash-field" for="date_from">
                <span>From</span>
                <input id="date_from" name="date_from" type="date" value="{{ $filters['date_from'] }}">
            </label>

            <label class="eg-dash-field" for="date_to">
                <span>To</span>
                <input id="date_to" name="date_to" type="date" value="{{ $filters['date_to'] }}">
            </label>
        </div>

        <div class="eg-dash-filter-actions">
            <a id="dashboard-filter-reset" href="{{ route('admin.dashboard') }}" class="eg-dash-button eg-dash-button--secondary">Reset</a>
            <button type="submit" class="eg-dash-button eg-dash-button--primary">Apply Filter</button>
        </div>
    </form>

    <section class="eg-dash-section">
        <div class="eg-dash-section-head">
            <div>
                <h2>Logs Summary</h2>
                <p>Gate monitoring totals matching the active filters.</p>
            </div>
            <span class="eg-dash-pill eg-dash-pill--blue">Live Records</span>
        </div>

        <div class="eg-dash-metrics">
            <article class="eg-dash-metric is-total">
                <span class="eg-dash-metric-icon">LOG</span>
                <span class="eg-dash-metric-label">Total Logs</span>
                <strong data-eg-count="{{ $logSummary['total'] }}">{{ number_format($logSummary['total']) }}</strong>
                <p>All recorded gate entries</p>
            </article>
            <article class="eg-dash-metric is-in">
                <span class="eg-dash-metric-icon">IN</span>
                <span class="eg-dash-metric-label">Time In</span>
                <strong data-eg-count="{{ $logSummary['log_in'] }}">{{ number_format($logSummary['log_in']) }}</strong>
                <p>Incoming student scans</p>
            </article>
            <article class="eg-dash-metric is-out">
                <span class="eg-dash-metric-icon">OUT</span>
                <span class="eg-dash-metric-label">Time Out</span>
                <strong data-eg-count="{{ $logSummary['log_out'] }}">{{ number_format($logSummary['log_out']) }}</strong>
                <p>Outgoing student scans</p>
            </article>
            <article class="eg-dash-metric is-na">
                <span class="eg-dash-metric-icon">N/A</span>
                <span class="eg-dash-metric-label">N/A</span>
                <strong data-eg-count="{{ $logSummary['na'] }}">{{ number_format($logSummary['na']) }}</strong>
                <p>Unclassified entries</p>
            </article>
            <article class="eg-dash-metric is-students">
                <span class="eg-dash-metric-icon">ID</span>
                <span class="eg-dash-metric-label">Unique Students</span>
                <strong data-eg-count="{{ $logSummary['unique_students'] }}">{{ number_format($logSummary['unique_students']) }}</strong>
                <p>Distinct monitored students</p>
            </article>
        </div>
    </section>

    <div class="eg-dash-columns">
        <section class="eg-dash-section">
            <div class="eg-dash-section-head">
                <div>
                    <h2>Data Summary</h2>
                    <p>Registered student information coverage.</p>
                </div>
            </div>

            <div class="eg-dash-data-grid">
                <article class="eg-dash-card">
                    <span>Registered Students</span>
                    <strong data-eg-count="{{ $dataSummary['total'] }}">{{ number_format($dataSummary['total']) }}</strong>
                    <p>Profile records available</p>
                </article>
                <article class="eg-dash-card">
                    <span>Departments</span>
                    <strong data-eg-count="{{ $dataSummary['departments'] }}">{{ number_format($dataSummary['departments']) }}</strong>
                    <p>Academic departments</p>
                </article>
                <article class="eg-dash-card">
                    <span>Courses</span>
                    <strong data-eg-count="{{ $dataSummary['courses'] }}">{{ number_format($dataSummary['courses']) }}</strong>
                    <p>Programs represented</p>
                </article>
                <article class="eg-dash-card">
                    <span>Year Levels</span>
                    <strong data-eg-count="{{ $dataSummary['year_levels'] }}">{{ number_format($dataSummary['year_levels']) }}</strong>
                    <p>Active classifications</p>
                </article>
            </div>
        </section>

        <section class="eg-dash-section eg-dash-breakdown">
            <div class="eg-dash-section-head">
                <div>
                    <h2>Department Breakdown</h2>
                    <p>Registered students per department.</p>
                </div>
            </div>

            <div class="eg-dash-breakdown-list">
                @forelse ($departmentBreakdown as $department)
                    @php
                        $departmentTotal = max((int) $department->total, 0);
                        $percentage = $dataTotal > 0
                            ? min(100, round(($departmentTotal / $dataTotal) * 100))
                            : 0;
                        $relativeWidth = min(100, round(($departmentTotal / $departmentMax) * 100));
                    @endphp
                    <div class="eg-dash-breakdown-item">
                        <div class="eg-dash-breakdown-row">
                            <span>{{ $department->label }}</span>
                            <strong>{{ number_format($departmentTotal) }} <small>{{ $percentage }}%</small></strong>
                        </div>
                        <div class="eg-dash-progress" role="progressbar" aria-valuenow="{{ $relativeWidth }}" aria-valuemin="0" aria-valuemax="100">
                            <span style="--eg-progress: {{ $relativeWidth }}%;"></span>
                        </div>
                    </div>
                @empty
                    <div class="eg-dash-empty">
                        <strong>No department data found</strong>
                        <span>Results will appear here once records match the active filters.</span>
                    </div>
                @endforelse
            </div>
        </section>
    </div>

    <section class="eg-dash-section eg-dash-analytics">
        <div class="eg-dash-section-head">
            <div>
                <h2>Visual Analytics</h2>
                <p>Quick distribution view without additional chart dependencies.</p>
            </div>
        </div>

        <div class="eg-dash-analytics-grid">
            <div class="eg-dash-analytics-panel">
                <h3>Status Distribution</h3>
                @foreach ($statusVisuals as $visual)
                    @php
                        $statusPercentage = $logTotal > 0
                            ? min(100, round(($visual['value'] / $logTotal) * 100))
                            : 0;
                    @endphp
                    <div class="eg-dash-status {{ $visual['class'] }}">
                        <div>
                            <span>{{ $visual['label'] }}</span>
                            <strong>{{ number_format($visual['value']) }} <small>{{ $statusPercentage }}%</small></strong>
                        </div>
                        <div class="eg-dash-progress">
                            <span style="--eg-progress: {{ $statusPercentage }}%;"></span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="eg-dash-analytics-panel">
                <h3>Department Distribution</h3>
                <div class="eg-dash-distribution">
                    @forelse ($departmentBreakdown as $department)
                        @php
                            $share = $dataTotal > 0
                                ? min(100, round(((int) $department->total / $dataTotal) * 100))
                                : 0;
                        @endphp
                        <div class="eg-dash-distribution-chip">
                            <span>{{ $department->label }}</span>
                            <strong>{{ $share }}%</strong>
                        </div>
                    @empty
                        <div class="eg-dash-empty eg-dash-empty--compact">
                            <span>No distribution data for this selection.</span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    const dashboardFilterForm = document.getElementById('dashboard-filter-form');
    const dashboardFilterReset = document.getElementById('dashboard-filter-reset');
    const dashboardFilterRestoreKey = 'admin.dashboard.restore_filters';
    const dashboardFilterKeys = ['status', 'department', 'course', 'year_level', 'date_from', 'date_to'];

    function clearDashboardFilterRestore() {
        try {
            window.sessionStorage.removeItem(dashboardFilterRestoreKey);
        } catch (error) {
            // Nothing to clear when storage is unavailable.
        }
    }

    function hasDashboardFilterQuery() {
        const params = new URLSearchParams(window.location.search);

        return dashboardFilterKeys.some((key) => params.has(key));
    }

    function shouldRestoreDashboardFilters() {
        try {
            return window.sessionStorage.getItem(dashboardFilterRestoreKey) === '1';
        } catch (error) {
            return false;
        }
    }

    function resetDashboardOnFreshVisit() {
        const shouldRestore = shouldRestoreDashboardFilters();
        clearDashboardFilterRestore();

        if (!shouldRestore && hasDashboardFilterQuery()) {
            window.location.replace(window.location.pathname);
        }
    }

    dashboardFilterForm?.addEventListener('submit', () => {
        try {
            window.sessionStorage.setItem(dashboardFilterRestoreKey, '1');
        } catch (error) {
            // Without storage, the submitted URL still shows the filtered result.
        }
    });

    dashboardFilterReset?.addEventListener('click', () => {
        clearDashboardFilterRestore();
    });

    window.addEventListener('pageshow', (event) => {
        if (event.persisted) {
            resetDashboardOnFreshVisit();
        }
    });

    resetDashboardOnFreshVisit();
</script>
@endsection
