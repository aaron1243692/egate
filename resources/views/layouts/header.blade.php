<header class="sticky top-0 flex w-full flex-row items-center gap-2 bg-blue-600 p-1.5">
    <h5 onclick="window.location.href='{{ route('admin.dashboard') }}'"
    class="m-0 flex shrink-0 items-center gap-2 text-white border border-white/20 rounded-full py-1 px-2.5 text-sm font-semibold leading-none cursor-pointer
    hover:bg-white/20 hover:border-white/40 hover:scale-105 transition duration-200 select-none">
        <img src="{{ asset('images/olpcc-logo.png') }}"
            alt="Logo"
            class="block h-7 w-7 rounded-full object-cover">
        <span class="leading-none">OSMIS-eGATE</span>
    </h5>

    <nav class="flex flex-1 items-center justify-start gap-1">
        @can('data.view')
        <a href="{{ route('admin.data') }}" class="text-white text-lg leading-none text-decoration-none py-1 px-2.5 hover:bg-white/20 hover:scale-105 rounded-full transition duration-200"
        >Data</a>
        @endcan
        @can('logs.view')
        <a href="{{ route('admin.logs') }}" class="text-white text-lg leading-none text-decoration-none py-1 px-2.5 hover:bg-white/20 hover:scale-105 rounded-full transition duration-200"
        >Student Logs</a>
        @endcan
        @can('emlog.view')
        <a href="{{ route('admin.employee_logs') }}" class="text-white text-lg leading-none text-decoration-none py-1 px-2.5 hover:bg-white/20 hover:scale-105 rounded-full transition duration-200"
        >Employee Logs</a>
        @endcan

        @can('roles.view')
        <a href="{{ route('admin.roles') }}" class="text-white text-lg leading-none text-decoration-none py-1 px-2.5 hover:bg-white/20 hover:scale-105 rounded-full transition duration-200"
        >Roles</a>
        @endcan
        @can('users.view')
            <a href="{{ route('admin.users.index') }}" class="text-white text-lg leading-none text-decoration-none py-1 px-2.5 hover:bg-white/20 hover:scale-105 rounded-full transition duration-200"
            >Users</a>
        @endcan
    </nav>

    <a href="{{ route('admin.reauth') }}"
    class="mr-3 shrink-0 text-decoration-none px-4 py-2 text-md font-medium leading-none text-white bg-white/10 border
    border-white/20 transition duration-300 hover:bg-black/30
    hover:border-black hover:text-white hover:scale-107"
    style="border-radius: 1.5rem;">
    Sign Out
    </a>

</header>
