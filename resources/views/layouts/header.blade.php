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
        @can('setup.view')
        <button type="button" id="setupButton" class="text-white text-lg leading-none text-decoration-none py-1 px-2.5 hover:bg-white/20 hover:scale-105 rounded-full transition duration-200"
        >Setup</button>
        @endcan

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

@canany(['setschedcehed.view', 'setschedem.view'])
<div id="setup" class="hidden w-full gap-2 py-2 px-3 m-0
flex flex-row justify-start items-center
">

    <div class="w-fit py-2 px-3
    flex flex-col gap-3
    bg-white shadow-md
    border border-gray-300 rounded-2xl
    ">

        <label class="text-sm font-semibold text-black tracking-wide">
            Schedules
        </label>

        <div class="w-full grid grid-cols-3 gap-2">

            @can('setschedcehed.view')
            <a href="{{ route('admin.setup.schedules') }}"
                class="flex items-center justify-center
                text-black/70 text-sm font-medium text-decoration-none
                py-1 px-2 bg-gray-100 rounded-xl
                transition duration-200 hover:scale-110"
            >
                Schedules
            </a>
            @endcan

            @can('setschedem.view')
            <a href="{{ route('admin.setup.employee.index') }}"
                class="flex items-center justify-center
                text-black/70 text-sm font-medium text-decoration-none
                py-1 px-2 bg-gray-100 rounded-xl
                transition duration-200 hover:scale-110"
            >
                Employees
            </a>
            @endcan

        </div>
    </div>

</div>
@endcanany

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const setupButton = document.getElementById('setupButton');
        const setup = document.getElementById('setup');

        setupButton?.addEventListener('click', () => {
            setup?.classList.remove('hidden');
        });

        setup?.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', () => {
                setup.classList.add('hidden');
            });
        });
    });
</script>
