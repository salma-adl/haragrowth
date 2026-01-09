<x-filament-widgets::widget>
    <x-filament::section>
        <h2 class="text-xl font-bold mb-4">Booking Summary</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-primary-50 p-4 rounded shadow">
                <h3 class="text-sm text-gray-600">Total Booking</h3>
                <p class="text-2xl font-semibold text-primary-700">{{ $this->totalBookings }}
                </p>
            </div>
            <div class="bg-yellow-50 p-4 rounded shadow">
                <h3 class="text-sm text-gray-600">Pengunjung In Session</h3>
                <p class="text-2xl font-semibold text-yellow-700">{{ $inSessionVisitors }}</p>
            </div>
            <div class="bg-green-50 p-4 rounded shadow">
                <h3 class="text-sm text-gray-600">Pasien Selesai Hari Ini</h3>
                <p class="text-2xl font-semibold text-green-700">{{ $finishedToday }}</p>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>