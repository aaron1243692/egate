@extends('layouts.app')
@section('title', 'users')
@section('content')

<main class="w-full p-3 gap-3
flex flex-1 flex-col">
    <h3>User Accounts</h3>

    <section class="w-full flex flex-1 justify-center p-4">

        <div class="w-full bg-white rounded-lg shadow-md overflow-hidden">

            <table class="w-full text-lg text-left">

                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="px-4 py-3">No.</th>
                        <th class="px-4 py-3">ID</th>
                        <th class="px-4 py-3">Username</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Role</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    <!-- Sample row -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3">1</td>
                        <td class="px-4 py-3">1001</td>
                        <td class="px-4 py-3">admin</td>
                        <td class="px-4 py-3">admin@email.com</td>
                        <td class="px-4 py-3">Admin</td>
                        <td class="px-4 py-3 text-center space-x-4
                        flex flex-row justify-center items-center">
                            <img src="{{ asset('icons/list.png') }}" class="w-7 h-7
                            hover:scale-120 transition duration-200" alt="edit">
                            <img src="{{ asset('icons/key.png') }}" class="w-7 h-7
                            hover:scale-120 transition duration-200" alt="edit">
                            <img src="{{ asset('icons/delete.png') }}" class="w-7 h-7
                            hover:scale-120 transition duration-200" alt="edit">
                        </td>
                    </tr>

                </tbody>

            </table>

        </div>

    </section>

</main>

@endsection
