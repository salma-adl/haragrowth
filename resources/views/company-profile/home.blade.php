@extends('layouts.app')

@php use Illuminate\Support\Facades\Storage; @endphp


@section('title', 'Hara Growth')

@section('content')

{{-- Hero Section --}}
<section id="hero" class="w-full bg-[#FFF8E7] py-14 sm:py-16 md:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-20 flex flex-col md:flex-row items-center gap-10">
        {{-- Text Area --}}
        <div class="md:w-1/2 text-left space-y-4 sm:space-y-6">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-[#3B2E2B]">Selamat Datang di</h1>
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-[#3B2E2B] leading-tight">
                <span class="text-[#00C4B4]">Hara Growth</span>,<br>
                Pusat Tumbuh Kembang Anak
            </h2>
            <a href="#form" class="inline-block bg-[#fbc02d] hover:bg-[#00a39c] text-black px-5 sm:px-6 py-2 sm:py-2.5 rounded-full font-semibold shadow transition duration-300 ease-in-out">
                Buat Janji Temu
            </a>
        </div>

        {{-- Carousel Area --}}
        <div class="md:w-1/2 w-full">
            <div
                x-data="{
                    activeSlide: 0,
                    slides: {{ Js::from(collect($news)->map(function ($item) {
                        $item['attachment'] = asset('storage/' . $item['attachment']);
                        return $item;
                    })) }},
                    init() {
                        setInterval(() => {
                            this.activeSlide = (this.activeSlide + 1) % this.slides.length;
                        }, 5000);
                    }
                }"
                class="relative w-full h-60 sm:h-72 md:h-80 lg:h-96 rounded-lg overflow-hidden shadow-md">
                {{-- Slides --}}
                <template x-for="(slide, index) in slides" :key="index">
                    <div
                        x-show="activeSlide === index"
                        class="absolute inset-0 transition-opacity duration-500"
                        x-transition:enter="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="opacity-100"
                        x-transition:leave-end="opacity-0">
                        <img :src="slide.attachment" alt="" class="w-full h-full object-cover object-center">
                        <div class="absolute inset-0 bg-black bg-opacity-40 flex flex-col justify-end p-4 sm:p-6 text-white">
                            <h3 class="text-base sm:text-lg font-bold" x-text="slide.title"></h3>
                            <p class="text-xs sm:text-sm" x-text="slide.description"></p>
                        </div>
                    </div>
                </template>

                {{-- Navigation --}}
                <button
                    @click="activeSlide = (activeSlide - 1 + slides.length) % slides.length"
                    class="absolute left-2 top-1/2 transform -translate-y-1/2 text-white bg-black/40 p-2 rounded-full hover:bg-black/60">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button
                    @click="activeSlide = (activeSlide + 1) % slides.length"
                    class="absolute right-2 top-1/2 transform -translate-y-1/2 text-white bg-black/40 p-2 rounded-full hover:bg-black/60">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</section>


{{-- Tentang Kami --}}
<section id="tentang" class="w-full bg-[#FFF8E7] py-16">
    <div class="max-w-6xl mx-auto px-4 flex flex-col md:flex-row items-center md:items-start gap-10">

        {{-- Gambar - ambil 50% lebar, tinggi otomatis --}}
        <div class="w-full md:w-1/2 aspect-[4/3] overflow-hidden rounded-xl shadow-lg">
            <img src="{{ asset('images/tentangKami.jpg') }}"
                alt="Tentang Kami"
                class="w-full h-full object-cover object-center" />
        </div>

        {{-- Teks --}}
        <div class="w-full md:w-1/2 text-center md:text-left space-y-4">
            <h2 class="text-3xl font-bold text-[#3B2E2B]">Tentang Kami</h2>
            <p class="text-[#3B2E2B]">
                Hara Growth merupakan Pusat Tumbuh Kembang Anak yang memberikan layanan di bidang psikologi dan terapi.
                Kami berfokus pada peningkatan kesehatan mental, tumbuh kembang, dan gerak anak.
            </p>
            <a href="javascript:void(0)"
                onclick="openAboutModal()"
                class="inline-block border border-[#3B2E2B] text-[#3B2E2B] px-4 py-2 rounded-md hover:bg-[#3B2E2B] hover:text-white transition">
                Selengkapnya
            </a>
        </div>
    </div>
</section>

<!-- Modal Tentang Kami -->
<div id="aboutModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex justify-center items-center">
    <div id="aboutModalContent"
        class="bg-[#FFFDFD] w-full max-w-4xl min-h-[70vh] rounded-xl overflow-hidden shadow-xl relative p-10 text-[#3B2E2B] transform transition-all scale-95 opacity-0">
        <button onclick="closeAboutModal()"
            class="absolute top-4 right-4 w-9 h-9 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center">
            <span class="text-xl">&times;</span>
        </button>

        <h2 class="text-3xl font-bold mb-6" id="aboutTitle">Tentang Kami</h2>
        <p class="mb-6 text-base leading-relaxed" id="aboutDescription">
        </p>

        <hr class="my-8">

        <h3 class="text-2xl font-bold mb-3">Visi</h3>
        <p class="mb-6 text-base leading-relaxed" id="aboutVision">
        </p>

        <h3 class="text-2xl font-bold mb-3">Misi</h3>
        <ul class="list-disc list-inside space-y-2 text-base" id="aboutMission">
        </ul>
    </div>
</div>


{{-- Layanan --}}
<section id="layanan" class="py-16 px-4 bg-[#FFF8E7] text-center">
    <h2 class="text-3xl font-bold text-[#3B2E2B] mb-4">Layanan Kami</h2>
    <p class="text-[#3B2E2B] mb-10">Berbagai layanan psikologi dan terapi yang dirancang khusus untuk mendukung tumbuh kembang anak secara optimal.</p>

    <div class="grid md:grid-cols-3 gap-6 max-w-6xl mx-auto text-left">
        @foreach($mService as $service)
        <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition">
            <img src="{{ asset('storage/' . $service->attachment) }}" alt="{{ $service['title'] }}" class="w-full h-48 object-cover" />
            <div class="p-5">
                <h3 class="text-lg font-semibold text-[#3B2E2B] mb-2">{{ $service['name'] }}</h3>
                <p class="text-[#3B2E2B] text-sm mb-4">{{ $service['summary'] }}</p>
                <a href="javascript:void(0)"
                    onclick="fetchPsychologists('{{ asset('storage/' . $service['attachment']) }}', '{{ $service['name'] }}', '{{ $service['description'] }}', {{ $service['id'] }})"
                    class="inline-block bg-yellow-400 hover:bg-yellow-500 text-[#3B2E2B] font-semibold text-sm px-4 py-2 rounded">
                    Selengkapnya
                </a>
            </div>
        </div>
        @endforeach
    </div>
</section>
<!-- Modal -->
<div id="serviceModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex justify-center items-start pt-24 overflow-y-auto">
    <div class="bg-white w-full max-w-3xl rounded-xl overflow-hidden shadow-xl transform transition-all scale-95 duration-300 opacity-0 relative" id="modalContent">
        <button onclick="closeModal()" class="absolute top-5 right-5 bg-white hover:bg-gray-300 text-[#3B2E2B] font-bold rounded-full w-9 h-9 flex items-center justify-center shadow">
            &times;
        </button>
        <div>
            <img id="modalImage" class="w-full h-64 object-cover" />
        </div>
        <div class="p-6 max-h-[70vh] overflow-y-auto">
            <h2 class="text-2xl font-bold text-[#3B2E2B]" id="modalTitle"></h2>
            <p class="text-sm text-[#3B2E2B] mt-2 mb-4" id="modalDescription"></p>

            <h3 class="text-xl font-bold text-[#3B2E2B] mb-3">Profil Terapis</h3>
            <div id="modalLoading" class="text-center text-[#3B2E2B] my-4 hidden">Memuat data...</div>
            <div id="modalPsychologists" class="grid grid-cols-1 md:grid-cols-2 gap-4"></div>
            <button onclick="closeModal()" class="mt-6 bg-[#3B2E2B] text-white px-4 py-2 rounded">Tutup</button>
        </div>
    </div>
</div>


{{-- Profile Expert --}}
<section id="profile" class="py-16 px-4 bg-[#FFF8E7] text-center">
    <h2 class="text-3xl font-bold text-[#3B2E2B] mb-4">Psikolog dan Terapis</h2>
    <p class="text-[#3B2E2B] max-w-2xl mx-auto mb-10">
        Psikolog dan terapis berpengalaman yang siap membantu tumbuh kembang anak Anda dengan pendekatan yang tepat.
    </p>

    <div class="relative max-w-7xl mx-auto flex items-center justify-between">
        {{-- Tombol panah kiri (di luar scroll container) --}}
        <button onclick="document.getElementById('carousel').scrollBy({ left: -320, behavior: 'smooth' })"
            class="w-14 h-14 rounded-full bg-[#4F3C38] text-white text-2xl flex items-center justify-center shadow-lg hover:bg-[#6A504B] transition mr-6">
            &#8249;
        </button>

        {{-- Carousel --}}
        <div id="carousel" class="flex overflow-x-auto gap-8 px-4 scroll-smooth auto-hide-scrollbar">
            @foreach($experts as $expert)
            <div onclick="fetchTherapist({{ $expert->id }})"
                class="cursor-pointer flex-shrink-0 w-[300px] bg-white rounded-xl overflow-hidden shadow-lg relative group hover:scale-105 transition duration-300">
                <img src="{{ asset('storage/...' .$expert['attachment']) }}" alt="{{ $expert['name'] }}"
                    class="w-full h-[400px] object-cover object-center" />

                <div class="absolute text-left bottom-0 w-full bg-gradient-to-t from-black/80 to-transparent px-4 py-4 text-white">
                    <span class="inline-block bg-yellow-400 text-black text-sm font-semibold px-3 py-1 rounded-full mb-2">
                        {{ $expert->services->first()?->name ?? 'Tanpa Layanan' }}
                    </span>
                    <h2 class="text-xl font-bold leading-tight mb-5">
                        {{ $expert['name'] }}
                    </h2>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Tombol panah kanan (di luar scroll container) --}}
        <button onclick="document.getElementById('carousel').scrollBy({ left: 320, behavior: 'smooth' })"
            class="w-14 h-14 rounded-full bg-[#4F3C38] text-white text-2xl flex items-center justify-center shadow-lg hover:bg-[#6A504B] transition ml-6">
            &#8250;
        </button>
    </div>
</section>
<div id="therapistModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex justify-center items-center px-4">
    <div id="therapistModalContent" class="bg-[#FFF8E7] max-w-lg w-full rounded-2xl p-6 relative transform scale-95 opacity-0 transition duration-300">
        <!-- Tombol close -->
        <button onclick="closeTherapistModal()" class="absolute top-4 right-4 w-9 h-9 rounded-full bg-white hover:bg-gray-100 flex items-center justify-center">
            <span class="text-2xl font-bold text-gray-700">&times;</span>
        </button>

        <!-- Konten Modal -->
        <div class="flex flex-col items-center text-center">
            <img id="therapistImage" src="" class="w-36 h-36 rounded-full object-cover border-4 border-[#E4DCCF] shadow-md -mt-10" />

            <h2 id="therapistName" class="mt-4 text-xl font-bold text-[#3B2E2B]"></h2>

            <span id="therapistService"
                class="inline-block bg-yellow-400 text-[#3B2E2B] text-sm font-semibold px-3 py-1 rounded-full mt-2">
            </span>

            <div class="text-[#3B2E2B] mt-4 text-sm">
                <p><strong>No SIPP:</strong> <span id="therapistSipp"></span></p>
                <p><strong>No STR&nbsp;&nbsp;:</strong> <span id="therapistStr"></span></p>
            </div>

            <div class="text-left mt-6 w-full">
                <h3 class="text-base font-semibold text-[#3B2E2B] mb-2">Pengalaman dan Keahlian</h3>
                <div id="therapistBio" class="text-sm text-[#3B2E2B] space-y-1 list-outside list-disc pl-5"></div>
            </div>
        </div>
    </div>
</div>

{{-- Form Buat Janji --}}
<section id="form" class="py-16 px-4 bg-[#0ABAB5]">
    <div class="max-w-6xl mx-auto bg-white rounded-xl shadow-md overflow-hidden flex flex-col md:flex-row">

        {{-- Kiri: Logo / Ilustrasi --}}
        <div class="w-full md:w-1/2 bg-[#FFF8E7] flex items-center justify-center p-8">
            <img src="{{ asset('images/logo-hara.png') }}" alt="Hara Growth Logo" class="max-w-[80%] h-auto">
        </div>

        {{-- Kanan: Formulir --}}
        <div class="w-full md:w-1/2 p-8 space-y-6">

            {{-- Tampilkan popup pesan sukses di sini --}}
            @if(session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                {{ session('success') }}
            </div>
            @endif

            <h2 class="text-2xl font-bold text-[#3B2E2B]">Isi Data Diri</h2>
            <form method="POST" action="{{ route('appointment.store') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" name="name" placeholder="Nama lengkap" required class="p-3 border rounded w-full md:col-span-2" />

                    <input type="number" name="age" placeholder="Usia" required class="p-3 border rounded w-full" />

                    <select name="gender" required class="p-3 border rounded w-full">
                        <option value="">Pilih jenis kelamin</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>

                    <input type="email" name="email" placeholder="Email" required class="p-3 border rounded w-full" />
                    <input type="tel" name="phone" placeholder="08123xxxxxxx" required class="p-3 border rounded w-full" />

                    <select id="serviceSelect" name="service_id" required class="p-3 border rounded w-full">
                        <option value="">Pilih layanan</option>
                        @foreach ($mService as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>

                    <select id="scheduleSelect" name="schedule_id" required class="p-3 border rounded w-full">
                        <option value="">Pilih Jadwal</option>
                        @foreach ($schedules as $schedule)
                        <option value="{{ $schedule->id }}">{{ $schedule->available_day }}, {{ $schedule->start_time }} - {{ $schedule->end_time }}</option>
                        @endforeach
                    </select>

                </div>

                <textarea name="notes" placeholder="Silakan isi dengan tujuan atau keluhan anda" class="w-full p-3 border rounded min-h-[120px]"></textarea>

                <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                @error('g-recaptcha-response')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
                <button type="submit"
                    class="w-full bg-[#FDC800] text-black font-semibold py-3 rounded hover:bg-[#e5b700] transition">
                    Buat Janji Sekarang
                </button>
            </form>
        </div>
    </div>
</section>

{{-- Lokasi --}}
<section id="lokasi" class="py-16 px-4 bg-[#FFF8E7] text-center">
    <h2 class="text-3xl font-bold text-[#3B2E2B] mb-10">Lokasi Kami</h2>

    {{-- Peta --}}
    <div class="max-w-4xl mx-auto rounded-xl overflow-hidden shadow-lg mb-6">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1979.1451079813032!2d107.88673094829323!3d-7.207695039966856!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68b167133bf72d%3A0x7fd721dda242a893!2sHara%20Growth%20(Konsultasi%20Psikologi%2C%20Fisioterapi%2C%20Terapi%20Wicara%2C%20Terapi%20Perilaku)!5e0!3m2!1sid!2sid!4v1748801926851!5m2!1sid!2sid"
            width="100%" height="500" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>

        <div class="bg-white px-4 py-3 flex items-center justify-center gap-2 text-[#3B2E2B]">
            <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C8.14 2 5 5.14 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.86-3.14-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z" />
            </svg>
            <span>Jalan Pembangunan No.159A</span>
        </div>
    </div>

    {{-- Kontak --}}
    <h3 id="contact" class="text-2xl font-semibold text-[#3B2E2B] mb-6">Hubungi Kami</h3>
    <div class="flex flex-col md:flex-row justify-center items-center gap-6">
        <a href="https://wa.me/6282111400390" target="_blank"
            class="flex items-center gap-3 bg-[#25D366] hover:bg-[#1ebd5a] text-white px-6 py-3 rounded-lg font-semibold transition">
            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                <path d="M20.52 3.48A11.93 11.93 0 0 0 12 .04C5.58.04.36 5.25.36 11.68c0 2.06.54 4.07 1.57 5.86L.04 24l6.62-1.73a11.78 11.78 0 0 0 5.96 1.55h.05c6.41 0 11.63-5.21 11.63-11.64 0-3.1-1.2-6.02-3.38-8.2zM12 21.03c-1.79 0-3.54-.47-5.08-1.35l-.36-.2-3.93 1.03 1.05-3.83-.23-.39a9.82 9.82 0 0 1-1.44-5.05c0-5.43 4.42-9.84 9.85-9.84a9.8 9.8 0 0 1 6.94 2.88 9.77 9.77 0 0 1 2.88 6.94c0 5.43-4.42 9.84-9.84 9.84zm5.6-7.6c-.3-.15-1.76-.87-2.03-.97-.27-.1-.46-.15-.66.15-.2.3-.76.97-.93 1.17-.17.2-.34.22-.63.07-.3-.15-1.26-.46-2.4-1.48-.89-.79-1.5-1.76-1.67-2.06-.17-.3-.02-.46.13-.6.13-.13.3-.34.46-.5.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.07-.15-.66-1.59-.91-2.18-.24-.6-.48-.52-.66-.52h-.56c-.2 0-.52.07-.8.37s-1.05 1.02-1.05 2.5 1.07 2.9 1.22 3.1c.15.2 2.1 3.2 5.1 4.48.71.3 1.26.48 1.7.61.72.23 1.38.2 1.9.13.58-.09 1.76-.72 2-1.42.25-.7.25-1.3.18-1.42-.07-.13-.26-.2-.56-.34z" />
            </svg>
            082111400390
        </a>

        <a href="https://www.instagram.com/haragrowthid/" target="_blank"
            class="flex items-center gap-3 bg-[#FFCC00] hover:bg-[#f2be00] text-black px-6 py-3 rounded-lg font-semibold transition">
            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                <path d="M12 2.2c3.2 0 3.6.01 4.85.07 1.17.05 1.97.24 2.42.4.59.22 1.02.5 1.47.95.45.45.73.88.95 1.47.16.45.35 1.25.4 2.42.06 1.26.07 1.65.07 4.85s-.01 3.6-.07 4.85c-.05 1.17-.24 1.97-.4 2.42-.22.59-.5 1.02-.95 1.47-.45.45-.88.73-1.47.95-.45.16-1.25.35-2.42.4-1.26.06-1.65.07-4.85.07s-3.6-.01-4.85-.07c-1.17-.05-1.97-.24-2.42-.4-.59-.22-1.02-.5-1.47-.95-.45-.45-.73-.88-.95-1.47-.16-.45-.35-1.25-.4-2.42C2.21 15.6 2.2 15.2 2.2 12s.01-3.6.07-4.85c.05-1.17.24-1.97.4-2.42.22-.59.5-1.02.95-1.47.45-.45.88-.73 1.47-.95.45-.16 1.25-.35 2.42-.4C8.4 2.21 8.8 2.2 12 2.2zm0-2.2C8.74 0 8.32.01 7.05.07c-1.27.06-2.15.27-2.91.57a5.4 5.4 0 0 0-1.96 1.26A5.4 5.4 0 0 0 .92 4.8c-.3.76-.51 1.64-.57 2.91C.3 8.06.29 8.48.29 12c0 3.52.01 3.94.07 5.21.06 1.27.27 2.15.57 2.91.33.86.79 1.57 1.44 2.22a5.4 5.4 0 0 0 2.22 1.44c.76.3 1.64.51 2.91.57 1.27.06 1.69.07 5.21.07s3.94-.01 5.21-.07c1.27-.06 2.15-.27 2.91-.57a5.4 5.4 0 0 0 2.22-1.44 5.4 5.4 0 0 0 1.44-2.22c.3-.76.51-1.64.57-2.91.06-1.27.07-1.69.07-5.21s-.01-3.94-.07-5.21c-.06-1.27-.27-2.15-.57-2.91a5.4 5.4 0 0 0-1.44-2.22A5.4 5.4 0 0 0 19.86.64c-.76-.3-1.64-.51-2.91-.57C15.6.01 15.18 0 12 0zM12 5.84A6.16 6.16 0 1 0 18.16 12 6.17 6.17 0 0 0 12 5.84zm0 10.16A4 4 0 1 1 16 12a4 4 0 0 1-4 4zm6.4-10.92a1.44 1.44 0 1 1-1.44-1.44 1.44 1.44 0 0 1 1.44 1.44z" />
            </svg>
            @haragrowthid
        </a>
    </div>
</section>

<footer style="background-color: #ffffff; padding: 3rem 2rem; ">
    <div style="max-width: 1200px; margin: 0 auto; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-start; gap: 2rem;">
        <!-- Logo dan Sosial Media -->
        <div style="flex: 1; min-width: 250px; text-align: center;">
            <h2 style="font-family: Georgia, serif; font-size: 1.8rem; color: #6d4c41;">
                Hara <span style="color: #00bfa5;">Growth</span>
            </h2>
            <div style="width: 60px; margin: 0.2rem auto; border-bottom: 3px solid #00bfa5;"></div>
            <div style="margin-top: 1rem; display: flex; justify-content: center; gap: 1rem;">
                <a href="https://www.instagram.com/haragrowthid/"><img src="{{ asset('images/facebook.svg') }}" alt="Facebook" style="height: 24px;"></a>
                <a href="https://www.instagram.com/haragrowthid/"><img src="{{ asset('images/instagram.svg') }}" alt="Instagram" style="height: 24px;"></a>
            </div>
        </div>

        <!-- Layanan -->
        <div class="text-left" style="flex: 1; min-width: 200px;">
            <h4 style="font-weight: bold; margin-bottom: 1rem;">Layanan</h4>
            <ul style="list-style: none; padding: 0; margin: 0;">
                @foreach ($mService as $item)
                <li>{{ $item->name }}</li>
                @endforeach
            </ul>
        </div>

        <!-- Tautan -->
        <div class="text-left" style="flex: 1; min-width: 200px;">
            <h4 style="font-weight: bold; margin-bottom: 1rem;">Tautan</h4>
            <ul style="list-style: none; padding: 0; margin: 0;">
                <li><a href="#tentang" style="text-decoration: none; color: #333;">Tentang Kami</a></li>
                <li><a href="#layanan" style="text-decoration: none; color: #333;">Layanan</a></li>
                <li><a href="#profile" style="text-decoration: none; color: #333;">Profile Expert</a></li>
                <li><a href="#contact" style="text-decoration: none; color: #333;">Kontak</a></li>
                <li><a href="#lokasi" style="text-decoration: none; color: #333;">Lokasi Kami</a></li>
            </ul>
        </div>
    </div>
</footer>

@endsection

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        new Swiper(".mySwiper", {
            loop: true,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });
    });
</script>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#serviceSelect').on('change', function() {
            let serviceId = $(this).val();
            $('#scheduleSelect').empty().append('<option value="">Loading...</option>');

            if (serviceId) {
                $.ajax({
                    url: `/get-schedules/${serviceId}`,
                    type: 'GET',
                    success: function(schedules) {
                        $('#scheduleSelect').empty().append('<option value="">Pilih Jadwal</option>');
                        if (schedules.length > 0) {
                            $.each(schedules, function(i, schedule) {
                                $('#scheduleSelect').append(
                                    `<option value="${schedule.id}">
                                        ${schedule.available_day}, ${schedule.start_time} - ${schedule.end_time}
                                     </option>`
                                );
                            });
                        } else {
                            $('#scheduleSelect').append('<option value="">Tidak ada jadwal tersedia</option>');
                        }
                    },
                    error: function() {
                        $('#scheduleSelect').empty().append('<option value="">Gagal memuat jadwal</option>');
                    }
                });
            } else {
                $('#scheduleSelect').empty().append('<option value="">Pilih Jadwal</option>');
            }
        });
    });
</script>

<script>
    // Contoh data JSON (ini bisa diganti dengan hasil fetch dari endpoint API nanti)
    const aboutData = {
        about_us: "Hara Growth merupakan Pusat Tumbuh Kembang Anak yang memberikan layanan dibidang psikologi dan terapi. Kami berfokus pada peningkatan kesehatan mental, tumbuh kembang, dan gerak anak.",
        about_vision: "Menjadi pusat tumbuh-kembang yang mewujudkan kesehatan fisik dan psikologis pada individu maupun keluarga, sehingga individu mampu mengembangkan potensi pribadi yang baik dan bermanfaat bagi diri sendiri maupun lingkungan.",
        about_mission: [
            "Optimalisasi perkembangan individu untuk tumbuh sesuai dengan tahapan perkembangannya.",
            "Optimalisasi peran lingkungan sekitar (keluarga) untuk mendukung perkembangan individu.",
            "Mendorong keterlibatan orangtua yang efektif dalam mengikuti tuntutan perubahan zaman."
        ]
    };

    function openAboutModal() {
        // Isi konten dari JSON
        document.getElementById('aboutDescription').textContent = aboutData.about_us;
        document.getElementById('aboutVision').textContent = aboutData.about_vision;

        const missionList = document.getElementById('aboutMission');
        missionList.innerHTML = '';
        aboutData.about_mission.forEach(item => {
            missionList.innerHTML += `<li>${item}</li>`;
        });

        // Tampilkan modal
        const modal = document.getElementById('aboutModal');
        const content = document.getElementById('aboutModalContent');
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeAboutModal() {
        const modal = document.getElementById('aboutModal');
        const content = document.getElementById('aboutModalContent');
        content.classList.remove('scale-100', 'opacity-100');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('aboutModal');
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeAboutModal();
            }
        });
    });
</script>


<!-- Modal Layanan Psikologi -->
<script>
    function fetchPsychologists(image, title, desc, id) {
        const loading = document.getElementById('modalLoading');
        const container = document.getElementById('modalPsychologists');

        // Reset & tampilkan loading
        container.innerHTML = '';
        loading.classList.remove('hidden');

        document.getElementById('modalImage').src = image;
        document.getElementById('modalTitle').textContent = title;
        document.getElementById('modalDescription').innerHTML = desc;


        // Tampilkan modal (tapi belum scale/opacity penuh)
        const modal = document.getElementById('serviceModal');
        const content = document.getElementById('modalContent');
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.add('scale-100', 'opacity-100');
        }, 10);

        fetch(`/psychologists/${id}`)
            .then(res => res.json())
            .then(psychologists => {
                loading.classList.add('hidden');

                psychologists.forEach(p => {
                    container.innerHTML += `
                <div class="bg-yellow-400 rounded-xl p-4 flex items-center gap-4">
                    <img src="${p.attachment}" class="w-16 h-16 rounded-full object-cover" />
                    <div>
                        <p class="font-bold text-[#3B2E2B]">${p.name}</p>
                        <p class="text-sm text-[#3B2E2B]">${title}</p>
                    </div>
                </div>`;
                });

                if (psychologists.length === 0) {
                    container.innerHTML = `<p class="text-sm text-[#3B2E2B]">Tidak ada terapis untuk layanan ini.</p>`;
                }
            })
            .catch(err => {
                loading.classList.add('hidden');
                container.innerHTML = `<p class="text-sm text-red-600">Gagal memuat data psikolog.</p>`;
                console.error(err);
            });
    }

    function closeModal() {
        const modal = document.getElementById('serviceModal');
        const content = document.getElementById('modalContent');
        content.classList.remove('scale-100', 'opacity-100');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('serviceModal');
        const content = document.getElementById('modalContent');

        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });
    });
</script>

<!-- Modal Psikolog -->
<script>
    function fetchTherapist(id) {
        fetch(`/therapist/${id}`)
            .then(res => res.json())
            .then(data => {
                const therapist = data;
                if (!therapist) return;

                document.getElementById('therapistImage').src = therapist.attachment || '';
                document.getElementById('therapistName').textContent = therapist.name;
                document.getElementById('therapistStr').textContent = therapist.str_number || '-';
                document.getElementById('therapistSipp').textContent = therapist.sipp_number || '-';

                // Tampilkan semua layanan sebagai daftar badge
                const serviceElement = document.getElementById('therapistService');
                if (therapist.services && therapist.services.length > 0) {
                    serviceElement.innerHTML = therapist.services
                        .map(name => `<span class="block bg-yellow-400 text-[#3B2E2B] text-sm font-semibold px-3 py-1 rounded-full my-1">${name}</span>`)
                        .join('');
                } else {
                    serviceElement.innerHTML = `<span class="text-sm text-gray-600">Tanpa Layanan</span>`;
                }

                // Render bio HTML
                const bioContainer = document.getElementById('therapistBio');
                bioContainer.innerHTML = therapist.bio || '<p>-</p>';

                // Tampilkan modal
                const modal = document.getElementById('therapistModal');
                const content = document.getElementById('therapistModalContent');
                modal.classList.remove('hidden');
                setTimeout(() => {
                    content.classList.add('scale-100', 'opacity-100');
                }, 10);
            });
    }

    function closeTherapistModal() {
        const modal = document.getElementById('therapistModal');
        const content = document.getElementById('therapistModalContent');
        content.classList.remove('scale-100', 'opacity-100');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('therapistModal');
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeTherapistModal();
            }
        });
    });
</script>

<style>
    .auto-hide-scrollbar::-webkit-scrollbar {
        display: none;
        /* Chrome, Safari, Opera */
    }

    .auto-hide-scrollbar::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.2);
        border-radius: 4px;
    }

    .auto-hide-scrollbar:hover::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.4);
    }

    .auto-hide-scrollbar {
        -ms-overflow-style: none;
        /* Internet Explorer */
        scrollbar-width: none;
        /* Firefox */
    }
</style>