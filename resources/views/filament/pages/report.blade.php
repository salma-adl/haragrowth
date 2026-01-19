<x-filament-panels::page>
    <form wire:submit="generateReport">
        {{ $this->form }}
        
        <div class="mt-4 flex justify-end gap-x-3">
            <x-filament::button type="submit">
                Preview Laporan
            </x-filament::button>
            
            @if($reportData)
            <x-filament::button color="gray" tag="a" href="{{ route('admin.reports.download', ['data' => json_encode($data)]) }}" target="_blank">
                Download CSV (Excel)
            </x-filament::button>
            @endif
        </div>
    </form>

    @if($reportData)
        @php
            $showBooking = in_array('booking_count', $data['report_types'] ?? []);
            $showClient = in_array('client_count', $data['report_types'] ?? []);
        @endphp
        <div class="mt-8 overflow-x-auto bg-white dark:bg-gray-900 rounded-lg shadow">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-3">Nama Terapis</th>
                        <th class="px-6 py-3">Layanan</th>
                        @if($showBooking)
                        <th class="px-6 py-3 text-center">Jumlah Booking</th>
                        @endif
                        @if($showClient)
                        <th class="px-6 py-3 text-center">Jumlah Klien Unik</th>
                        @endif
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
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $row['therapist'] }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $row['service'] }}
                            </td>
                            @if($showBooking)
                            <td class="px-6 py-4 text-center">
                                {{ $row['booking_count'] }}
                            </td>
                            @endif
                            @if($showClient)
                            <td class="px-6 py-4 text-center">
                                {{ $row['client_count'] }}
                            </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 2 + ($showBooking ? 1 : 0) + ($showClient ? 1 : 0) }}" class="px-6 py-4 text-center">Tidak ada data untuk periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
                @if(count($reportData) > 0)
                <tfoot>
                    <tr class="font-semibold text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700">
                        <td class="px-6 py-3" colspan="2">Total Keseluruhan</td>
                        @if($showBooking)
                        <td class="px-6 py-3 text-center">{{ $totalBooking }}</td>
                        @endif
                        @if($showClient)
                        <td class="px-6 py-3 text-center">{{ $totalClient }}</td>
                        @endif
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    @endif
</x-filament-panels::page>
