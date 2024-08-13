@props(['type'])

@php
    $color = [
        'success' => 'border-green-200 bg-green-100 text-green-900',
        'danger' => 'border-red-200 bg-red-100 text-red-900',
        'warning' => 'border-yellow-200 bg-yellow-100 text-yellow-900',
    ];
    $heading = [
        'success' => 'Sukses',
        'danger' => 'Kesalahan',
        'warning' => 'Perhatian',
    ];
@endphp
<div {{ $attributes->merge(['class' => $color[$type] . ' m-4 rounded-md border p-2 text-left']) }}>
    <div class="flex flex-wrap justify-between">
        <div class="flex w-0 flex-1">
            <div class="mr-3 pt-1">
                @switch($type)
                    @case('success')
                        <x-filament::icon icon="heroicon-o-check-circle" class="h-6 w-6" />
                    @break

                    @case('danger')
                    @case('warning')
                        <x-filament::icon icon="heroicon-o-exclamation-triangle" class="h-6 w-6" />
                    @break

                    @default
                        <x-filament::icon icon="heroicon-o-exclamation-circle" class="h-6 w-6" />
                @endswitch
            </div>
            <div>
                <h4 class="text-md font-bold leading-6">
                    {{ $heading[$type] ?? 'Informasi' }}
                </h4>
                <p class="text-sm">
                    {{ $slot }}
                </p>
            </div>
        </div>
        <div>
            <button type="button" wire:click="resetForm()"
                class="rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor">
                    <path
                        d="M17.6555 6.3331a.9.9 0 0 1 .001 1.2728l-4.1032 4.1085a.4.4 0 0 0 0 .5653l4.1031 4.1088a.9002.9002 0 0 1 .0797 1.1807l-.0806.092a.9.9 0 0 1-1.2728-.0009l-4.1006-4.1068a.4.4 0 0 0-.5662 0l-4.099 4.1068a.9.9 0 1 1-1.2738-1.2718l4.1027-4.1089a.4.4 0 0 0 0-.5652L6.343 7.6059a.9002.9002 0 0 1-.0796-1.1807l.0806-.092a.9.9 0 0 1 1.2728.0009l4.099 4.1055a.4.4 0 0 0 .5662 0l4.1006-4.1055a.9.9 0 0 1 1.2728-.001z">
                    </path>
                </svg>
            </button>
        </div>
    </div>
</div>
