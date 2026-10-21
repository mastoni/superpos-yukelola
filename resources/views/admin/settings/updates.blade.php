<x-admin-layout
    active="settings"
    :title="__('updates.title')"
    :crumbs="[
        ['label' => __('settings.crumb_parent')],
        ['label' => __('settings.title'), 'href' => route('admin.settings.index')],
        ['label' => __('updates.title')],
    ]">

    <div class="page-wide">
        <div class="page-header mb-6">
            <div class="flex items-start gap-3">
                <a href="{{ route('admin.settings.index') }}" class="pos-btn pos-btn-sm pos-btn-ghost" aria-label="{{ __('settings.title') }}">
                    <x-icon name="back" class="w-4 h-4" />
                </a>
                <div>
                    <h1 class="page-title">{{ __('updates.title') }}</h1>
                    <p class="page-sub">{{ __('updates.sub') }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Card 1: Application Version & Environment --}}
            <div class="pos-card p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2.5 rounded-lg bg-emerald-50 text-emerald-600 dark:bg-emerald-950 dark:text-emerald-400">
                        <x-icon name="info" class="w-5 h-5" />
                    </div>
                    <div>
                        <h2 class="text-base font-semibold">{{ __('updates.sections.status') }}</h2>
                        <p class="text-xs fg-secondary">{{ __('updates.sections.status_sub') }}</p>
                    </div>
                </div>

                <dl class="divide-y divide-gray-100 dark:divide-gray-800 text-sm">
                    <div class="py-3 flex justify-between items-center">
                        <dt class="fg-secondary">{{ __('updates.fields.app_name') }}</dt>
                        <dd class="font-medium">{{ $appName }}</dd>
                    </div>
                    <div class="py-3 flex justify-between items-center">
                        <dt class="fg-secondary">{{ __('updates.fields.current_version') }}</dt>
                        <dd class="font-semibold text-emerald-600 dark:text-emerald-400">v{{ $currentVersion }}</dd>
                    </div>
                    <div class="py-3 flex justify-between items-center">
                        <dt class="fg-secondary">{{ __('updates.fields.php_version') }}</dt>
                        <dd class="font-mono text-xs">{{ $phpVersion }}</dd>
                    </div>
                    <div class="py-3 flex justify-between items-center">
                        <dt class="fg-secondary">{{ __('updates.fields.laravel_version') }}</dt>
                        <dd class="font-mono text-xs">v{{ $laravelVersion }}</dd>
                    </div>
                    <div class="py-3 flex justify-between items-center">
                        <dt class="fg-secondary">{{ __('updates.fields.database_driver') }}</dt>
                        <dd class="font-mono text-xs uppercase">{{ $databaseDriver }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Card 2: Official Distribution Policy --}}
            <div class="pos-card p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2.5 rounded-lg bg-blue-50 text-blue-600 dark:bg-blue-950 dark:text-blue-400">
                        <x-icon name="shield" class="w-5 h-5" />
                    </div>
                    <div>
                        <h2 class="text-base font-semibold">{{ __('updates.sections.distribution') }}</h2>
                        <p class="text-xs fg-secondary">{{ __('updates.sections.distribution_sub') }}</p>
                    </div>
                </div>

                <div class="space-y-4 text-sm">
                    <div class="p-3.5 rounded-lg bg-gray-50 dark:bg-gray-800/60 border border-gray-100 dark:border-gray-800">
                        <div class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('updates.fields.channel') }}
                        </div>
                        <div class="font-medium text-emerald-700 dark:text-emerald-400">
                            {{ __('updates.fields.channel_value') }}
                        </div>
                    </div>

                    <div class="text-xs leading-relaxed fg-secondary">
                        <p>{{ __('updates.fields.distribution_note') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
