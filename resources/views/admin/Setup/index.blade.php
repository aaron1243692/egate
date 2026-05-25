@extends('layouts.app')

@section('title', 'Setup Management')

@section('content')
<main class="eg-setup-page">
    <section class="eg-setup-hero">
        <div>
            <p class="eg-setup-kicker">Administration</p>
            <h1 class="eg-setup-title">Setup Management</h1>
            <p class="eg-setup-subtitle">
                Control schedule rules and employee schedule assignments from one clean admin hub.
            </p>
        </div>
    </section>

    <section class="eg-setup-grid" aria-label="Setup modules">
        @can('setschedcehed.view')
        <a class="eg-setup-card eg-setup-card-link" href="{{ route('admin.setup.schedules') }}">
            <span class="eg-setup-card-icon">S</span>
            <span class="eg-setup-card-body">
                <span class="eg-setup-card-title">Schedules</span>
                <span class="eg-setup-card-text">Manage attendance schedule names and time rules.</span>
            </span>
            <span class="eg-setup-card-arrow" aria-hidden="true">-></span>
        </a>
        @endcan

        @can('setschedem.view')
        <a class="eg-setup-card eg-setup-card-link" href="{{ route('admin.setup.employee.index') }}">
            <span class="eg-setup-card-icon">E</span>
            <span class="eg-setup-card-body">
                <span class="eg-setup-card-title">Employee Schedules</span>
                <span class="eg-setup-card-text">Assign schedules to employees.</span>
            </span>
            <span class="eg-setup-card-arrow" aria-hidden="true">-></span>
        </a>
        @endcan
    </section>
</main>
@endsection
