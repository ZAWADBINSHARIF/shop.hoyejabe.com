<x-filament-panels::page>
    <form wire:submit="send">
        {{ $this->form }}
        
        <div class="mt-6 flex justify-end gap-x-3">
            <x-filament::button type="submit" icon="heroicon-o-paper-airplane">
                Send Message
            </x-filament::button>
        </div>
    </form>
    
    @push('scripts')
    <script>
        // Additional character counter functionality if needed
        document.addEventListener('DOMContentLoaded', function() {
            const messageField = document.querySelector('textarea[wire\\:model\\.live="data.message"]');
            if (messageField) {
                messageField.addEventListener('input', function() {
                    // Character counter is handled by Filament's reactive helper text
                });
            }
        });
    </script>
    @endpush
</x-filament-panels::page>