@extends('layouts.app')

@section('title', 'Settings')

@section('content')
    <div class="w-full max-w-4xl px-3 py-4 overflow-hidden">
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="border-b border-slate-200 px-4 py-3">
                <h1 class="text-xl font-bold text-slate-900">Admin Settings</h1>
                <p class="mt-1 text-sm text-slate-500">Update login controls stored in the database config table.</p>
            </div>

            @if (session('status'))
                <div class="mx-4 mt-4 rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            <div class="grid gap-3 px-4 py-4">
                @foreach ($settings as $setting)
                    <form method="POST" action="{{ route('admin.settings.update', $setting['id']) }}" class="flex items-center justify-between rounded-xl border border-slate-200 px-4 py-3">
                        @csrf
                        @method('PUT')

                        <div>
                            <h2 class="text-base font-semibold text-slate-900">{{ $setting['name'] }}</h2>
                            <p class="text-sm text-slate-500">
                                Current status:
                                <span class="font-medium {{ $setting['enabled'] ? 'text-emerald-700' : 'text-slate-600' }}">
                                    {{ $setting['enabled'] ? 'Enabled' : 'Disabled' }}
                                </span>
                            </p>
                        </div>

                        <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                            <input type="hidden" name="control" value="0">
                            <input type="checkbox" name="control" value="1" class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500" {{ $setting['enabled'] ? 'checked' : '' }}>
                            Enabled
                        </label>

                        <button type="submit" class="rounded-full bg-slate-900 px-4 py-1.5 text-sm font-medium text-white transition hover:bg-slate-800">
                            Save
                        </button>
                    </form>
                @endforeach
            </div>
        </div>
    </div>
@endsection
