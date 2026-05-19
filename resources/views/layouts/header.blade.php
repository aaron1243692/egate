<header class="w-full p-2 gap-0 bg-blue-600 sticky top-0
flex flex-row justify-center items-center">
    <h5 onclick="window.location.href='{{ route('admin.dashboard') }}'"
    class="flex items-center gap-2 text-white border border-white/20 rounded-full py-1 px-3 cursor-pointer
    hover:bg-white/20 hover:border-white/40 hover:scale-105 transition duration-200 select-none">
        <img src="{{ asset('images/olpcc-logo.png') }}"
            alt="Logo"
            class="w-8 h-8 rounded-full object-cover">
        OSMIS-eGATE
    </h5>

    <nav class="gap-1
    flex flex-1 justify-start items-center">
        <a href="{{ route('admin.data') }}" class="text-white text-decoration-none py-1 px-3 hover:bg-white/20 hover:scale-105 rounded-full transition duration-200"
        >Data</a>
        <a href="{{ route('admin.logs') }}" class="text-white text-decoration-none py-1 px-3 hover:bg-white/20 hover:scale-105 rounded-full transition duration-200"
        >Logs</a>
        <a href="{{ route('admin.roles') }}" class="text-white text-decoration-none py-1 px-3 hover:bg-white/20 hover:scale-105 rounded-full transition duration-200"
        >Roles</a>
        <a href="{{ route('admin.users.index') }}" class="text-white text-decoration-none py-1 px-3 hover:bg-white/20 hover:scale-105 rounded-full transition duration-200"
        >Users</a>
        <a href="{{ route('admin.settings') }}" class="text-white text-decoration-none py-1 px-3 hover:bg-white/20 hover:scale-105 rounded-full transition duration-200"
        >Setting</a>
    </nav>

</header>
