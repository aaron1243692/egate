@extends('layouts.app')

@section('title', 'Settings')

@section('content')

<main class="w-full p-3 gap-3 flex flex-1 flex-col">
    <h3 class="text-xl font-semibold text-slate-800">Admin Settings</h3>

    <section class="w-full flex flex-1 justify-center p-4">
        <div class="w-full bg-white rounded-lg shadow-md overflow-hidden border border-slate-200">
            <div class="w-full px-4 py-4 border-b border-slate-200">
                <p class="text-sm text-slate-600">
                    Edit the setting name or toggle the checkbox. Changes are saved automatically.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-blue-600 text-white">
                        <tr>
                            <th class="px-4 py-3">ID</th>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Enabled</th>
                        </tr>
                    </thead>

                    <tbody id="settings-table-body" class="divide-y divide-slate-200">
                        @forelse ($settings as $setting)
                            <tr class="hover:bg-gray-50 transition" data-setting-row data-id="{{ $setting->id }}">
                                <td class="px-4 py-3 font-medium text-slate-700">{{ $setting->id }}</td>
                                <td class="px-4 py-3" data-setting-name>
                                    {{ $setting->name }}
                                </td>
                                <td class="px-4 py-3">
                                    <label class="inline-flex items-center gap-3">
                                        <input
                                            type="checkbox"
                                            data-control-input
                                            class="h-5 w-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                            {{ (int) $setting->control === 1 ? 'checked' : '' }}
                                        >
                                        <span class="text-sm text-slate-700">Enabled</span>
                                    </label>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-slate-500">No settings found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-4 border-t border-slate-200">
                <p id="settings-feedback" class="text-sm text-slate-600">Ready.</p>
            </div>
        </div>
    </section>
</main>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    const settingsUpdateBase = @json(url('admin/settings'));
    const settingsFeedback = document.getElementById('settings-feedback');
    function setFeedback(message, tone = 'idle') {
        settingsFeedback.textContent = message;
        settingsFeedback.className = 'text-sm';

        if (tone === 'success') {
            settingsFeedback.classList.add('text-emerald-700');
            return;
        }

        if (tone === 'error') {
            settingsFeedback.classList.add('text-rose-700');
            return;
        }

        if (tone === 'saving') {
            settingsFeedback.classList.add('text-sky-700');
            return;
        }

        settingsFeedback.classList.add('text-slate-600');
    }

    function updateRowUi(row, control) {
        const controlValue = row.querySelector('[data-control-value]');
        const statusBadge = row.querySelector('[data-status-badge]');
        const enabled = Number(control) === 1;

        if (controlValue) {
            controlValue.textContent = String(control);
        }

        if (statusBadge) {
            statusBadge.textContent = enabled ? 'Enabled' : 'Disabled';
            statusBadge.className = `inline-flex rounded-full px-3 py-1 text-xs font-semibold ${enabled ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'}`;
        }
    }

    async function saveSetting(row) {
        const id = row.dataset.id;
        const nameCell = row.querySelector('[data-setting-name]');
        const controlInput = row.querySelector('[data-control-input]');
        const name = nameCell ? nameCell.textContent.trim() : '';
        const payload = new FormData();
        payload.set('_method', 'PUT');
        payload.set('name', name);
        payload.set('control', controlInput.checked ? '1' : '0');

        if (payload.get('name') === '') {
            setFeedback('Setting name cannot be empty.', 'error');
            return;
        }

        setFeedback(`Saving setting #${id}...`, 'saving');

        try {
            const response = await fetch(`${settingsUpdateBase}/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: payload,
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || 'Unable to save setting.');
            }

            controlInput.checked = Number(result.setting.control) === 1;
            updateRowUi(row, result.setting.control);
            setFeedback(`Setting #${id} saved automatically.`, 'success');
        } catch (error) {
            setFeedback(error.message || 'Unable to save setting.', 'error');
        }
    }

    document.querySelectorAll('[data-setting-row]').forEach((row) => {
        row.querySelector('[data-control-input]')?.addEventListener('change', () => {
            updateRowUi(row, row.querySelector('[data-control-input]').checked ? 1 : 0);
            saveSetting(row);
        });
    });
</script>

@endsection
