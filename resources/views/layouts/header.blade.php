<header class="w-full p-2 gap-0 bg-blue-600 sticky top-0
flex flex-row justify-center items-center">
    <h5 class="text-white border-2 border-transparent rounded-full py-1 px-3
    hover:border-black/40 hover:bg-white/20 hover:scale-105 transition duration-200
    " style="cursor: pointer;">OSMIS-eGATE</h5>

    <nav class="gap-1
    flex flex-1 justify-start items-center">
        <a href="{{ route('admin.data') }}" class="text-white text-decoration-none py-1 px-3 hover:bg-white/20 hover:scale-105 rounded-full transition duration-200"
        >Data</a>
        <a href="{{ route('admin.logs') }}" class="text-white text-decoration-none py-1 px-3 hover:bg-white/20 hover:scale-105 rounded-full transition duration-200"
        >Logs</a>
        <a href="{{ route('admin.permissions') }}" class="text-white text-decoration-none py-1 px-3 hover:bg-white/20 hover:scale-105 rounded-full transition duration-200"
        >Permissions</a>
        <a href="{{ route('admin.roles') }}" class="text-white text-decoration-none py-1 px-3 hover:bg-white/20 hover:scale-105 rounded-full transition duration-200"
        >Roles</a>
        <a href="{{ route('admin.users') }}" class="text-white text-decoration-none py-1 px-3 hover:bg-white/20 hover:scale-105 rounded-full transition duration-200"
        >Users</a>
    </nav>

</header>
