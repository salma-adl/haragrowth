@extends('layouts.app')

@section('title', 'Layanan Kami - Hara Growth')

@section('content')

{{-- Header --}}
<section class="bg-[#FFF8E7] py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-[#3B2E2B] hover:text-[#00a39c] font-semibold mb-8 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Kembali
        </a>
        <div class="max-w-xl mx-auto text-center">
            <h2 class="text-3xl font-bold text-[#3B2E2B] mb-4">Layanan Kami</h2>
            <p class="text-[#3B2E2B] text-xl leading-relaxed">
                Temukan layanan terbaik untuk mendukung tumbuh kembang anak Anda bersama para ahli kami.
            </p>
        </div>
    </div>
</section>

{{-- List Layanan --}}
<section class="py-10 px-6 bg-[#FFF8E7] min-h-screen">
    <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8">
        @foreach($services as $service)
        <div class="bg-white rounded-3xl shadow-sm hover:shadow-md transition duration-300 overflow-hidden flex flex-col h-full">
            {{-- Gambar Layanan --}}
            <div class="w-full relative h-64">
                <img src="{{ asset('storage/' . $service->attachment) }}" alt="{{ $service->name }}" class="absolute inset-0 w-full h-full object-cover">
            </div>

            {{-- Detail Layanan --}}
            <div class="w-full p-6 flex flex-col flex-grow">
                <div class="flex justify-between items-start mb-2 gap-2">
                    <h2 class="text-xl font-bold text-[#3B2E2B]">{{ $service->name }}</h2>
                    @if($service->price)
                    <span class="whitespace-nowrap bg-[#FFF8E7] text-[#3B2E2B] border border-[#3B2E2B] font-bold px-3 py-1 rounded-full text-sm">
                        Rp {{ number_format($service->price, 0, ',', '.') }}
                    </span>
                    @endif
                </div>
                
                <div class="prose prose-sm text-gray-600 mb-4 line-clamp-3">
                    {!! $service->description !!}
                </div>
                
                <button 
                    onclick="openServiceModal({{ \Illuminate\Support\Js::from([
                        'name' => $service->name,
                        'description' => $service->description,
                        'price' => $service->price ? number_format($service->price, 0, ',', '.') : null,
                        'image' => asset('storage/' . $service->attachment),
                        'therapists' => $service->userProfiles->map(function($t) {
                            return [
                                'name' => $t->name,
                                'str' => $t->str_number ?? '-',
                                'image' => asset('storage/' . $t->attachment)
                            ];
                        })
                    ]) }})"
                    class="text-[#00a39c] hover:text-[#008f8a] font-semibold text-sm mb-4 inline-flex items-center gap-1 transition">
                    Lihat Detail
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                {{-- Daftar Terapis --}}
                <div class="mt-auto pt-4 border-t border-gray-100">
                    <h3 class="text-sm font-semibold text-[#3B2E2B] mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Terapis:
                    </h3>
                    @if($service->userProfiles->count() > 0)
                    <div class="space-y-2">
                        @foreach($service->userProfiles as $therapist)
                        <div class="flex items-center gap-3 bg-gray-50 p-2 rounded-lg border border-gray-100">
                            <img src="{{ asset('storage/' . $therapist->attachment) }}" alt="{{ $therapist->name }}" class="w-10 h-10 rounded-full object-cover border border-white shadow-sm">
                            <div class="overflow-hidden">
                                <p class="font-bold text-[#3B2E2B] text-sm truncate">{{ $therapist->name }}</p>
                                <p class="text-[10px] text-gray-500 font-medium tracking-wide uppercase truncate">STR: {{ $therapist->str_number ?? '-' }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="bg-gray-50 p-2 rounded-lg border border-gray-100 text-gray-500 italic text-xs">
                        Belum ada terapis.
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>

{{-- Footer --}}
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
                @foreach ($services as $item)
                <li>{{ $item->name }}</li>
                @endforeach
            </ul>
        </div>

        <!-- Tautan -->
        <div class="text-left" style="flex: 1; min-width: 200px;">
            <h4 style="font-weight: bold; margin-bottom: 1rem;">Tautan</h4>
            <ul style="list-style: none; padding: 0; margin: 0;">
                <li><a href="{{ url('/') }}#tentang" style="text-decoration: none; color: #333;">Tentang Kami</a></li>
                <li><a href="{{ url('/') }}#layanan" style="text-decoration: none; color: #333;">Layanan</a></li>
                <li><a href="{{ url('/') }}#profile" style="text-decoration: none; color: #333;">Profile Expert</a></li>
                <li><a href="{{ url('/') }}#contact" style="text-decoration: none; color: #333;">Kontak</a></li>
                <li><a href="{{ url('/') }}#lokasi" style="text-decoration: none; color: #333;">Lokasi Kami</a></li>
            </ul>
        </div>
    </div>
</footer>

<!-- Service Detail Modal -->
<div id="serviceDetailModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeServiceModal()"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full">
            <div class="relative">
                <!-- Close button -->
                <button onclick="closeServiceModal()" class="absolute top-4 right-4 bg-white/80 hover:bg-white text-gray-800 rounded-full p-2 z-10 transition">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                
                <img id="modalImage" src="" alt="" class="w-full h-64 sm:h-80 object-cover">
            </div>

            <div class="px-6 py-6 sm:px-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-2">
                    <h3 class="text-2xl font-bold text-[#3B2E2B]" id="modalTitle">Service Name</h3>
                    <span id="modalPrice" class="bg-[#FFF8E7] text-[#3B2E2B] border border-[#3B2E2B] font-bold px-4 py-1.5 rounded-full text-sm whitespace-nowrap"></span>
                </div>

                <div id="modalDescription" class="prose prose-sm sm:prose-base max-w-none text-gray-600 mb-8 leading-relaxed"></div>

                <div class="border-t border-gray-100 pt-6">
                    <h4 class="font-bold text-[#3B2E2B] mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Terapis Tersedia
                    </h4>
                    <div id="modalTherapists" class="grid grid-cols-1 sm:grid-cols-2 gap-4"></div>
                </div>
            </div>
            
            <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="closeServiceModal()" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-6 py-2.5 bg-[#3B2E2B] text-base font-medium text-white hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3B2E2B] sm:ml-3 sm:w-auto sm:text-sm transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function openServiceModal(data) {
        document.getElementById('modalTitle').textContent = data.name;
        document.getElementById('modalImage').src = data.image;
        document.getElementById('modalDescription').innerHTML = data.description;
        
        const priceEl = document.getElementById('modalPrice');
        if (data.price) {
            priceEl.textContent = 'Rp ' + data.price;
            priceEl.classList.remove('hidden');
        } else {
            priceEl.classList.add('hidden');
        }

        const therapistsContainer = document.getElementById('modalTherapists');
        therapistsContainer.innerHTML = '';
        
        if (data.therapists && data.therapists.length > 0) {
            data.therapists.forEach(therapist => {
                const div = document.createElement('div');
                div.className = 'flex items-center gap-3 p-3 bg-white border border-gray-100 rounded-xl shadow-sm';
                div.innerHTML = `
                    <img src="${therapist.image}" class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-sm">
                    <div class="overflow-hidden">
                        <p class="font-bold text-[#3B2E2B] text-sm truncate">${therapist.name}</p>
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">STR: ${therapist.str}</p>
                    </div>
                `;
                therapistsContainer.appendChild(div);
            });
        } else {
            therapistsContainer.innerHTML = '<p class="text-sm text-gray-500 italic col-span-2">Belum ada terapis untuk layanan ini.</p>';
        }

        document.getElementById('serviceDetailModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeServiceModal() {
        document.getElementById('serviceDetailModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") {
            closeServiceModal();
        }
    });
</script>

@endsection
