<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold">{{ $monthName }}</h2>
            <div class="flex gap-2">
                <x-filament::button wire:click="prevMonth" color="gray" size="sm">
                    Previous
                </x-filament::button>
                <x-filament::button wire:click="nextMonth" color="gray" size="sm">
                    Next
                </x-filament::button>
            </div>
        </div>

        <div class="grid grid-cols-7 gap-px bg-gray-200 dark:bg-gray-700 border border-gray-200 dark:border-gray-700">
            @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
                <div class="bg-gray-50 dark:bg-gray-800 p-2 text-center font-semibold text-sm">
                    {{ $day }}
                </div>
            @endforeach

            @foreach($days as $day)
                <div class="min-h-[100px] bg-white dark:bg-gray-900 p-2 {{ !$day['isCurrentMonth'] ? 'bg-gray-50 dark:bg-gray-800 text-gray-400' : '' }}">
                    <div class="text-right mb-1">
                        <span class="text-sm {{ $day['isToday'] ? 'bg-primary-500 text-white rounded-full w-6 h-6 inline-flex items-center justify-center' : '' }}">
                            {{ $day['dayNumber'] }}
                        </span>
                    </div>

                    @if(count($day['events']) > 0)
                        <div class="text-xs">
                            <div class="font-bold text-primary-600 dark:text-primary-400 mb-1">
                                {{ count($day['events']) }} patients
                            </div>
                            <div class="text-gray-600 dark:text-gray-400">
                                @foreach($day['events'] as $event)
                                    <div>{{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }}</div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
