@props([
    'title' => 'Confirm Action',
    'message' => 'Are you sure?',
    'confirmLabel' => 'Confirm',
    'cancelLabel' => 'Cancel',
    'confirmVariant' => 'destructive',
    'show' => false,
    'name' => 'confirm-modal',
])

<div
    x-data="{
        show: @js($show),
        close() { show = false },
    }"
    x-on:open-modal.window="$event.detail == '{{ $name }}' ? show = true : null"
    x-on:close-modal.window="$event.detail == '{{ $name }}' ? show = false : null"
    x-show="show"
    class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50"
    style="display: {{ $show ? 'block' : 'none' }};"
    role="dialog"
    aria-modal="true"
    aria-labelledby="{{ $name }}-title"
>
    <div
        x-show="show"
        class="fixed inset-0 transform transition-all"
        x-on:click="close()"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
    </div>

    <div
        x-show="show"
        class="mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-md sm:mx-auto"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    >
        <div class="p-6">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <div class="modal-ico modal-ico--{{ $confirmVariant }}">
                        @if($confirmVariant === 'destructive')
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                        @elseif($confirmVariant === 'warning')
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @else
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @endif
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="text-base font-semibold text-gray-900" id="{{ $name }}-title">{{ $title }}</h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">{{ $message }}</p>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex gap-3 justify-end">
                <x-button variant="secondary" type="button" x-on:click="close()">{{ $cancelLabel }}</x-button>
                <x-button variant="{{ $confirmVariant }}" type="button" x-on:click="$dispatch('confirm-{{ $name }}'); close()">{{ $confirmLabel }}</x-button>
            </div>
        </div>
    </div>
</div>
