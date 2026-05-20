<header class="w-full p-1.5 gap-0 bg-blue-600 sticky top-0
flex flex-row justify-center items-center">
    <h5 onclick="window.location.href='{{ route('admin.dashboard') }}'"
    class="flex items-center gap-2 text-white border border-white/20 rounded-full py-1 px-2.5 text-sm font-semibold cursor-pointer
    hover:bg-white/20 hover:border-white/40 hover:scale-105 transition duration-200 select-none">
        <img src="{{ asset('images/olpcc-logo.png') }}"
            alt="Logo"
            class="w-7 h-7 rounded-full object-cover">
        OSMIS-eGATE
    </h5>

    <nav class="gap-1
    flex flex-1 justify-start items-center">
        @can('data.view')
        <a href="{{ route('admin.data') }}" class="text-white text-md text-decoration-none py-1 px-2.5 hover:bg-white/20 hover:scale-105 rounded-full transition duration-200"
        >Data</a>
        @endcan
        @can('logs.view')
        <a href="{{ route('admin.logs') }}" class="text-white text-md text-decoration-none py-1 px-2.5 hover:bg-white/20 hover:scale-105 rounded-full transition duration-200"
        >Logs</a>
        @endcan
        @can('roles.view')
        <a href="{{ route('admin.roles') }}" class="text-white text-md text-decoration-none py-1 px-2.5 hover:bg-white/20 hover:scale-105 rounded-full transition duration-200"
        >Roles</a>
        @endcan
        @can('users.view')
            <a href="{{ route('admin.users.index') }}" class="text-white text-md text-decoration-none py-1 px-2.5 hover:bg-white/20 hover:scale-105 rounded-full transition duration-200"
            >Users</a>
        @endcan
    </nav>

    <a href="{{ route('login') }}"
    class="mr-3 text-decoration-none px-3 py-1 text-sm font-medium text-white bg-white/10 border
    border-white/20 transition duration-300 hover:bg-black/30
    hover:border-black hover:text-white hover:scale-107"
    style="border-radius: 1.5rem;">
    Sign Out
    </a>

</header>
