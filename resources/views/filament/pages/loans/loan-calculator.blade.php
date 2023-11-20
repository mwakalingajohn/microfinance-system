<x-filament-panels::page>
    <x-filament::section>
        <form wire:submit="calculate">
            {{ $this->form }}

            <x-filament::button type="submit" class="mt-3">
                Calculate
            </x-filament::button>
        </form>
        {{-- Content --}}
    </x-filament::section>
    <div>
        {{ $this->table }}
    </div>
</x-filament-panels::page>
