@php
    $canSetupSchedules = auth()->user()->can('setschedcehed.view');
    $canSetupEmployees = auth()->user()->can('setschedem.view');
    $canSetup = $canSetupSchedules || $canSetupEmployees;
    $canRecords = auth()->user()->can('data.view')
        || auth()->user()->can('logs.view')
        || auth()->user()->can('emlog.view');
    $canAccessControl = auth()->user()->can('roles.view')
        || auth()->user()->can('users.view');
    $canTimeIn = canAccessWithParent(auth()->user(), 'time.in');
    $canTimeOut = canAccessWithParent(auth()->user(), 'time.out');
    $canGate = $canTimeIn || $canTimeOut;

    $activeTab = match (true) {
        request()->routeIs('admin.setup.*') => 'setup',
        request()->routeIs('admin.data*', 'admin.logs*', 'admin.employee_logs*') => 'records',
        request()->routeIs('admin.roles*', 'admin.users.*', 'admin.permissions*') => 'access',
        default => 'dashboard',
    };

    if (
        ($activeTab === 'setup' && !$canSetup)
        || ($activeTab === 'records' && !$canRecords)
        || ($activeTab === 'access' && !$canAccessControl)
    ) {
        $activeTab = 'dashboard';
    }
@endphp

<header class="eg-rb" data-eg-ribbon>
    <div class="eg-rb-top">
        <a class="eg-rb-brand" href="{{ route('admin.dashboard') }}" aria-label="OSMIS eGATE dashboard">
            <img src="{{ asset('images/olpcc-logo-removebg.png') }}" alt="">
            <span>OLPCC / OSMIS-eGATE</span>
        </a>

        <nav class="eg-rb-tabs" aria-label="Admin navigation">
            <button class="eg-rb-tab {{ $activeTab === 'dashboard' ? 'is-active' : '' }}" type="button" data-eg-ribbon-tab="dashboard">Dashboard</button>
            @if ($canSetup)
                <button class="eg-rb-tab {{ $activeTab === 'setup' ? 'is-active' : '' }}" type="button" data-eg-ribbon-tab="setup">Setup</button>
            @endif
            @if ($canRecords)
                <button class="eg-rb-tab {{ $activeTab === 'records' ? 'is-active' : '' }}" type="button" data-eg-ribbon-tab="records">Records</button>
            @endif
            @if ($canAccessControl)
                <button class="eg-rb-tab {{ $activeTab === 'access' ? 'is-active' : '' }}" type="button" data-eg-ribbon-tab="access">Access Control</button>
            @endif
            @if ($canGate)
                <button class="eg-rb-tab" type="button" data-eg-ribbon-tab="gate">Gate</button>
            @endif
        </nav>

        <div class="eg-rb-user">
            <button
                class="eg-rb-userbtn"
                id="egRbUserBtn"
                type="button"
                aria-controls="egRbUserMenu"
                aria-expanded="false"
            >
                <span class="eg-rb-userlabel">{{ auth()->user()->username ?? auth()->user()->email }}</span>
                <span class="eg-rb-usercaret" aria-hidden="true"></span>
            </button>
            <div class="eg-rb-usermenu" id="egRbUserMenu">
                <div class="eg-rb-usercaption">Signed in as</div>
                <div class="eg-rb-username">{{ auth()->user()->username ?? auth()->user()->email }}</div>
                <a class="eg-rb-usermenu-link eg-rb-usermenu-link--danger" href="{{ route('admin.reauth') }}">
                    Sign Out / Re-auth
                </a>
            </div>
        </div>
    </div>

    <div class="eg-rb-ribbon">
        <div class="eg-rb-page {{ $activeTab === 'dashboard' ? 'is-active' : '' }}" data-eg-ribbon-page="dashboard">
            <section class="eg-rb-group">
                <div class="eg-rb-group-title">Dashboard</div>
                <div class="eg-rb-items">
                    <a class="eg-rb-tile {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <span class="eg-rb-icon">D</span>
                        <span>Dashboard</span>
                    </a>
                </div>
            </section>
        </div>

        @if ($canSetup)
            <div class="eg-rb-page {{ $activeTab === 'setup' ? 'is-active' : '' }}" data-eg-ribbon-page="setup">
                <section class="eg-rb-group">
                    <div class="eg-rb-group-title">Configuration</div>
                    <div class="eg-rb-items">
                        @if ($canSetupSchedules)
                            <a class="eg-rb-tile {{ request()->routeIs('admin.setup.schedules*') ? 'is-active' : '' }}" href="{{ route('admin.setup.schedules') }}">
                                <span class="eg-rb-icon">S</span>
                                <span>Schedules</span>
                            </a>
                        @endif
                        @if ($canSetupEmployees)
                            <a class="eg-rb-tile {{ request()->routeIs('admin.setup.employee.*') ? 'is-active' : '' }}" href="{{ route('admin.setup.employee.index') }}">
                                <span class="eg-rb-icon">E</span>
                                <span>Employees</span>
                            </a>
                        @endif
                    </div>
                </section>
            </div>
        @endif

        @if ($canRecords)
            <div class="eg-rb-page {{ $activeTab === 'records' ? 'is-active' : '' }}" data-eg-ribbon-page="records">
                <section class="eg-rb-group">
                    <div class="eg-rb-group-title">Records</div>
                    <div class="eg-rb-items">
                        @can('data.view')
                            <a class="eg-rb-tile {{ request()->routeIs('admin.data*') ? 'is-active' : '' }}" href="{{ route('admin.data') }}">
                                <span class="eg-rb-icon">D</span>
                                <span>Data</span>
                            </a>
                        @endcan
                        @can('logs.view')
                            <a class="eg-rb-tile {{ request()->routeIs('admin.logs*') ? 'is-active' : '' }}" href="{{ route('admin.logs') }}">
                                <span class="eg-rb-icon">SL</span>
                                <span>Student Logs</span>
                            </a>
                        @endcan
                        @can('emlog.view')
                            <a class="eg-rb-tile {{ request()->routeIs('admin.employee_logs*') ? 'is-active' : '' }}" href="{{ route('admin.employee_logs') }}">
                                <span class="eg-rb-icon">EL</span>
                                <span>Employee Logs</span>
                            </a>
                        @endcan
                    </div>
                </section>
            </div>
        @endif

        @if ($canAccessControl)
            <div class="eg-rb-page {{ $activeTab === 'access' ? 'is-active' : '' }}" data-eg-ribbon-page="access">
                <section class="eg-rb-group">
                    <div class="eg-rb-group-title">Access Control</div>
                    <div class="eg-rb-items">
                        @can('roles.view')
                            <a class="eg-rb-tile {{ request()->routeIs('admin.roles*') ? 'is-active' : '' }}" href="{{ route('admin.roles') }}">
                                <span class="eg-rb-icon">R</span>
                                <span>Roles</span>
                            </a>
                        @endcan
                        @can('users.view')
                            <a class="eg-rb-tile {{ request()->routeIs('admin.users.*') ? 'is-active' : '' }}" href="{{ route('admin.users.index') }}">
                                <span class="eg-rb-icon">U</span>
                                <span>Users</span>
                            </a>
                        @endcan
                    </div>
                </section>
            </div>
        @endif

        @if ($canGate)
            <div class="eg-rb-page" data-eg-ribbon-page="gate">
                <section class="eg-rb-group">
                    <div class="eg-rb-group-title">Gate</div>
                    <div class="eg-rb-items">
                        @if ($canTimeIn)
                            <a class="eg-rb-tile eg-rb-tile--success" href="{{ route('in') }}">
                                <span class="eg-rb-icon">IN</span>
                                <span>Time In</span>
                            </a>
                        @endif
                        @if ($canTimeOut)
                            <a class="eg-rb-tile eg-rb-tile--danger" href="{{ route('out') }}">
                                <span class="eg-rb-icon">OUT</span>
                                <span>Time Out</span>
                            </a>
                        @endif
                    </div>
                </section>
            </div>
        @endif
    </div>
</header>
