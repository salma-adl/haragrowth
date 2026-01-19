<x-filament-panels::page>
    <form wire:submit="generateReport">
        {{ $this->form }}
        
        <div class="mt-4 flex justify-end gap-x-3">
            <x-filament::button type="submit">
                Preview Laporan
            </x-filament::button>
            
            @if($reportData)
            <x-filament::button color="gray" tag="a" href="{{ route('admin.reports.download', $data) }}" target="_blank">
                Download Excel (.xlsx)
            </x-filament::button>
            @endif
        </div>
    </form>

    @if($reportData)
        <div class="mt-8 overflow-x-auto bg-white dark:bg-gray-900 rounded-lg shadow">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        @if(($generatedReportCategory ?? 'service') === 'therapist')
                        <th class="px-6 py-3">Nama Terapis</th>
                        @endif
                        <th class="px-6 py-3">Nama Layanan</th>
                        <th class="px-6 py-3 text-center">Total Booking</th>
                        <th class="px-6 py-3 text-center">Total Klien Unik</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $totalBooking = 0;
                        $totalClient = 0;
                    @endphp
                    @forelse($reportData as $row)
                        @php 
                            $totalBooking += $row['booking_count'];
                            $totalClient += $row['client_count'];
                        @endphp
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            @if(($generatedReportCategory ?? 'service') === 'therapist')
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $row['therapist'] }}
                            </td>
                            @endif
                            <td class="px-6 py-4">
                                {{ $row['service'] }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{ $row['booking_count'] }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{ $row['client_count'] }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ (($generatedReportCategory ?? 'service') === 'therapist' ? 4 : 3) }}" class="px-6 py-4 text-center">Tidak ada data untuk periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
                @if(count($reportData) > 0)
                <tfoot>
                    <tr class="font-semibold text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700">
                        <td class="px-6 py-3" colspan="{{ (($generatedReportCategory ?? 'service') === 'therapist' ? 2 : 1) }}">Total Keseluruhan</td>
                        <td class="px-6 py-3 text-center">{{ $totalBooking }}</td>
                        <td class="px-6 py-3 text-center">{{ $totalClient }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    @endif
</x-filament-panels::page>
