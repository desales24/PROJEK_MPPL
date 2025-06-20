<x-filament::page>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Chart Customer Baru -->
        <x-filament::widget>
            <x-filament::card>
                <livewire:widgets.customer-chart />
            </x-filament::card>
        </x-filament::widget>

        <!-- Chart Pemasukan -->
        <x-filament::widget>
            <x-filament::card>
                <livewire:widgets.income-chart />
            </x-filament::card>
        </x-filament::widget>
    </div>
</x-filament::page>
