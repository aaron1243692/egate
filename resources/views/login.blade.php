@extends('layouts.clean')

@section('title', 'Login')

@section('clean')

<main class="w-full flex flex-1
justify-center items-center">

    <form action="" class="w-[22rem] p-4 gap-3 border rounded-xl shadow-md
    flex flex-col itema-center justify-center">

        <h3 class="text-center">Sign In</h3>

        <div class="w-full gap-1
        flex flex-col">
            <label for="ml-2">Email or Username</label>
            <input class="w-full rounded-full p-2 not-[]:
            border-1 border-black/70 outline-none"
            type="text" value="">
        </div>

        <div class="w-full gap-1
        flex flex-col">
            <label for="ml-2">Password</label>
            <input class="w-full rounded-full p-2
            border-1 border-black/70 outline-none"
            type="password" value="">
        </div>

        <button type="submit"
        class="w-full py-2 bg-blue-600 text-white font-medium
        hover:bg-blue-700 hover:scale-105 transition-all duration-200 shadow-md"
        style="border-radius: 1.5rem;"
        >Sign In
        </button>

    </form>

</main>

@endsection

