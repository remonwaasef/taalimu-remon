<div class="flash-messages-container mb-6 space-y-3">
    @if(session('success'))
        <x-ui.alert type="success" :dismissible="true">
            {{ session('success') }}
        </x-ui.alert>
    @endif

    @if(session('error'))
        <x-ui.alert type="error" :dismissible="true">
            {{ session('error') }}
        </x-ui.alert>
    @endif

    @if(session('info'))
        <x-ui.alert type="info" :dismissible="true">
            {{ session('info') }}
        </x-ui.alert>
    @endif

    @if(session('warning'))
        <x-ui.alert type="warning" :dismissible="true">
            {{ session('warning') }}
        </x-ui.alert>
    @endif

    @if (isset($errors) && $errors->any())
        <x-ui.alert type="error" :title="__('center::messages.validation_error') ?? 'يرجى مراجعة الأخطاء التالية:'" :dismissible="true">
            <ul class="mt-1 space-y-1 list-disc ps-4 text-xs">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-ui.alert>
    @endif
</div>

{{-- Toast notifications trigger --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
        window.dispatchEvent(new CustomEvent('toast', {
            detail: { type: 'success', message: @json(session('success')) }
        }));
    @endif
    @if(session('error'))
        window.dispatchEvent(new CustomEvent('toast', {
            detail: { type: 'error', message: @json(session('error')) }
        }));
    @endif
    if (typeof TaalimuToast !== 'undefined') {
        @if(session('success'))
            TaalimuToast.success(@json(session('success')));
        @endif
        @if(session('error'))
            TaalimuToast.error(@json(session('error')));
        @endif
    }
});
</script>
