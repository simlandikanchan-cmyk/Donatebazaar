@props([
    'message' => 'Action completed successfully.',
    'undoLabel' => 'Undo',
    'undoAction' => null,
    'type' => 'success',
    'duration' => 5000,
])

@php
    $typeClasses = [
        'success' => 'toast-ok',
        'error' => 'toast-err',
        'warning' => 'toast-warn',
    ];
    $class = $typeClasses[$type] ?? 'toast-ok';
@endphp

<div
    x-data="{
        show: false,
        timer: null,
        open() {
            this.show = true
            this.timer = setTimeout(() => this.close(), @js($duration))
        },
        close() {
            clearTimeout(this.timer)
            this.show = false
        },
        undo() {
            @if($undoAction)
                $dispatch('{{ $undoAction }}')
            @endif
            this.close()
        }
    }"
    x-init="
        $watch('show', value => {
            if (value) {
                document.body.classList.add('overflow-y-hidden')
            } else {
                document.body.classList.remove('overflow-y-hidden')
            }
        })
    "
    x-on:open-toast.window="open()"
    x-show="show"
    class="toast-wrap"
    style="display: none;"
    role="status"
    aria-live="polite"
>
    <div class="toast {{ $class }}">
        <span>{{ $message }}</span>
        @if($undoAction)
            <button type="button" class="toast-undo" x-on:click="undo()">{{ $undoLabel }}</button>
        @endif
        <button type="button" class="toast-x" x-on:click="close()" aria-label="Close notification">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
</div>
