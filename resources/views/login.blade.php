@extends('layouts.clean')

@section('title', 'Login')

@section('clean')
    <main class="w-full h-full
    flex flex-col justify-center items-center">
        @if (! $manualLoginEnabled)
        <section class="w-[20rem] rounded-2xl border border-amber-300 bg-amber-50 px-5 py-6 text-center shadow-lg">
            <h4 class="text-lg font-bold text-amber-900">Manual Login Disabled</h4>
            <p class="mt-2 text-sm leading-relaxed text-amber-800">
                This sign-in form is currently disabled from Admin Settings.
            </p>
        </section>
        @else
        <form id="signinForm" action="{{ route('signin.submit') }}" method="POST" class="w-[20rem] rounded-lg shadow-lg py-3 px-4
        flex flex-col items-center gap-3
        border-2 border-black/40">
            @csrf
            <h4 class="text-lg font-bold">Sign In</h4>

            <div class="w-full gap-1
            flex flex-col">
                <label for="login">Email Or Username</label>
                <input type="text" name="login" id="login" value="{{ old('login') }}"
                class="w-full rounded-full border-1 border-black/70 outline-none py-1 px-2"
                autocomplete="username" required>
            </div>

            <div class="w-full gap-1
            flex flex-col">
                <label for="password">Password</label>
                <input type="password" name="password" id="password"
                class="w-full rounded-full border-1 border-black/70 outline-none py-1 px-2"
                autocomplete="current-password" required>
            </div>

            <button type="submit" class="w-full rounded-full bg-blue-500 text-white py-1 px-2
            hover:bg-blue-600 hover:scale-105 transition-colors duration-200 font-semibold"
            style="border-radius: 1.5rem;">Sign In</button>

        </form>
        @endif
    </main>



<div id="errorModal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex justify-center items-center p-4">
    <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl flex flex-col items-center text-center transform transition-all">

        <!-- Error Icon Container -->
        <div class="mb-2 flex h-12 w-12 items-center justify-center rounded-full bg-red-50 text-red-500">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>

        <!-- Content -->
        <div class="space-y-2">
            <h2 class="text-xl font-semibold text-gray-900">
                Login Failed
            </h2>
            <p id="errorMessage" class="text-sm text-gray-500 leading-relaxed">
                The username or password you entered is incorrect. Please try again.
            </p>
        </div>

        <!-- Action Button -->
        <button id="closeModal" class="mt-6 w-full rounded-xl bg-gray-900 px-4 py-2.5 text-sm font-medium text-white hover:bg-gray-800 active:scale-[0.98] transition-all duration-150 shadow-sm"
        style="border-radius: 1.5rem;">
            Try Again
        </button>

    </div>
</div>

    <script>
    const form = document.getElementById('signinForm');
    const modal = document.getElementById('errorModal');
    const message = document.getElementById('errorMessage');
    const closeModal = document.getElementById('closeModal');

    if (form) {
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .content,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (data.status === 1) {
                window.location.href = data.redirect || "{{ route('admin.dashboard') }}";
                return;
            }

            // show modal if status = 0
            message.textContent = data.message;
            modal.classList.remove('hidden');

        } catch (error) {
            message.textContent = 'Something went wrong';
            modal.classList.remove('hidden');
        }
    });
    }

    closeModal.addEventListener('click', () => {
        modal.classList.add('hidden');
    });
    </script>

@endsection
