<!DOCTYPE html>
<html lang="en">

<head>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    <meta charset="UTF-8">
    <title>@yield('title', 'Detail Booking')</title>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

</head>

<body class="bg-[#FFF8E7]">

    <section id="hero" class="w-full py-20">
        <div class="max-w-5xl mx-auto bg-white rounded-2xl shadow-xl p-10 space-y-10">

            {{-- Judul --}}
            <h1 class="text-3xl font-extrabold text-teal-700 border-b pb-2">DETAIL BOOKING</h1>

            {{-- Booking Code --}}
            <div x-data="{ copied: false }" class="flex items-center gap-4">
                <p class="text-sm font-semibold text-gray-600">Kode Booking:</p>

                <span
                    id="bookingCode"
                    class="bg-yellow-400 text-white font-bold px-4 py-1 rounded-full tracking-widest text-sm shadow">
                    {{ $booking->booking_code }}
                </span>

                <button
                    @click="
            navigator.clipboard.writeText('{{ $booking->booking_code }}'); 
            copied = true; 
            setTimeout(() => copied = false, 2000);
        "
                    class="text-yellow-600 hover:text-yellow-800 transition"
                    title="Salin kode">
                    📋
                </button>

                <span x-show="copied" class="text-green-600 text-sm font-medium">Disalin!</span>
            </div>


            {{-- Grid Informasi Pasien --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-800">
                <div>
                    <p class="text-sm font-semibold text-gray-600">Status</p>
                    <span class="
                        inline-block px-3 py-1 rounded-full text-sm font-semibold
                        @if($booking->status == 'booked') bg-yellow-400 text-white
                        @elseif($booking->status == 'completed') bg-teal-600 text-white
                        @elseif($booking->status == 'in_session') bg-red-500 text-white
                        @else bg-gray-300 text-gray-800
                        @endif
                    ">
                        {{ strtoupper(str_replace('_', ' ', $booking->status)) }}
                    </span>
                </div>

                <div>
                    <p class="text-sm font-semibold text-gray-600">Email</p>
                    <p class="text-lg">{{ $booking->customer->email ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm font-semibold text-gray-600">Nama Pasien</p>
                    <p class="text-lg">{{ $booking->customer->name ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm font-semibold text-gray-600">Telepon</p>
                    <p class="text-lg">{{ $booking->customer->phone ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm font-semibold text-gray-600">Umur</p>
                    <p class="text-lg">{{ $booking->customer->age ?? 'N/A' }} tahun</p>
                </div>

                <div>
                    <p class="text-sm font-semibold text-gray-600">Jenis Kelamin</p>
                    <p class="text-lg capitalize">{{ $booking->customer->gender ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm font-semibold text-gray-600">Layanan</p>
                    <p class="text-lg">{{ $booking->service->name ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm font-semibold text-gray-600">Terapis</p>
                    <p class="text-lg">{{ $booking->userProfile->name ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm font-semibold text-gray-600">Jadwal</p>
                    <p class="text-lg">
                        {{ $booking->schedule->available_day ?? '-' }}
                        (
                        {{ $booking->schedule?->start_time ? \Carbon\Carbon::parse($booking->schedule->start_time)->format('H:i') : '' }}
                        -
                        {{ $booking->schedule?->end_time ? \Carbon\Carbon::parse($booking->schedule->end_time)->format('H:i') : '' }}
                        )
                    </p>
                </div>

                <div>
                    <p class="text-sm font-semibold text-gray-600">Tanggal Booking</p>
                    <p class="text-lg">{{ $booking->created_at }}</p>
                </div>

            </div>

            {{-- Keterangan Pasien --}}
            <div>
                <p class="text-sm font-semibold text-gray-600 mb-1">Keterangan Pasien</p>
                <div class="bg-gray-50 p-4 rounded-md border text-gray-700">
                    {{ $booking->notes ?? 'Tidak ada keterangan' }}
                </div>
            </div>

            {{-- Pemisah --}}
            <hr class="border-t-2 border-yellow-300 my-6">

            {{-- Data dari Terapis --}}
            <div class="space-y-8">
                <div>
                    <h2 class="text-xl font-bold text-teal-700 mb-2">Diagnosa</h2>
                    <div class="prose max-w-none">
                        {!! $booking->diagnosis ? $booking->diagnosis : '<p class="text-gray-500"><em>*Belum mendapatkan keterangan medis</em></p>' !!}
                    </div>
                </div>

                <div>
                    <h2 class="text-xl font-bold text-teal-700 mb-2">Rekomendasi</h2>
                    <div class="prose max-w-none">
                        {!! $booking->recommendation ? $booking->recommendation : '<p class="text-gray-500"><em>*Belum mendapatkan keterangan medis</em></p>' !!}
                    </div>
                </div>

                <div>
                    <h2 class="text-xl font-bold text-teal-700 mb-2">Catatan Terapis</h2>
                    <div class="prose max-w-none">
                        {!! $booking->therapist_notes ? $booking->therapist_notes : '<p class="text-gray-500"><em>*Belum mendapatkan keterangan medis</em></p>' !!}
                    </div>
                </div>
            </div>

        </div>
    </section>

</body>

</html>