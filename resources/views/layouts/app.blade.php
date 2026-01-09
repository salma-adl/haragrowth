<!DOCTYPE html>
<html lang="en">

<head>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Company Profile')</title>

    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body class="font-sans">

    <!-- Header / Navbar -->
    <header class="sticky top-0 z-50 bg-[#FBF6E6] shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="#">
                        <img src="{{ asset('images/logo-hara.png') }}" alt="Hara Growth Logo" class="h-12">
                    </a>
                </div>

                <!-- Hamburger Menu (mobile) -->
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="text-gray-800 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

                <!-- Navigation Menu -->
                <nav id="nav-menu" class="hidden md:flex gap-6 items-center">
                    <a href="#tentang" class="font-semibold text-gray-800 hover:text-[#00a39c]">Tentang Kami</a>
                    <a href="#layanan" class="font-semibold text-gray-800 hover:text-[#00a39c]">Layanan</a>
                    <a href="#profile" class="font-semibold text-gray-800 hover:text-[#00a39c]">Profile Expert</a>
                    <a href="#contact" class="font-semibold text-gray-800 hover:text-[#00a39c]">Kontak</a>
                    <a href="#lokasi" class="font-semibold text-gray-800 hover:text-[#00a39c]">Lokasi Kami</a>
                </nav>

                <!-- Buat Janji Button -->
                <div class="hidden md:block">
                    <a href="#form"
                        class="bg-[#fbc02d] hover:bg-[#00a39c] text-white px-6 py-2 rounded-full font-semibold shadow transition">
                        Buat Janji
                    </a>
                </div>
            </div>

            <!-- Mobile Nav -->
            <div id="mobile-nav" class="md:hidden hidden flex-col gap-3 mt-4 pb-4 border-t border-gray-300">
                <a href="#tentang" class="block font-semibold text-gray-800 hover:text-[#00a39c]">Tentang Kami</a>
                <a href="#layanan" class="block font-semibold text-gray-800 hover:text-[#00a39c]">Layanan</a>
                <a href="#profile" class="block font-semibold text-gray-800 hover:text-[#00a39c]">Profile Expert</a>
                <a href="#contact" class="block font-semibold text-gray-800 hover:text-[#00a39c]">Kontak</a>
                <a href="#lokasi" class="block font-semibold text-gray-800 hover:text-[#00a39c]">Lokasi Kami</a>
                <a href="#form"
                    class="mt-2 block bg-[#fbc02d] hover:bg-[#00a39c] text-white px-4 py-2 rounded-full font-semibold text-center transition">
                    Buat Janji
                </a>
            </div>
        </div>
    </header>

    <!-- Spacer agar konten tidak tertutup navbar -->
    <!-- <div class="h-20"></div> -->

    <main>
        @yield('content')
    </main>

    <!-- Script Toggle Hamburger -->
    <script>
        const btn = document.getElementById('mobile-menu-button');
        const nav = document.getElementById('mobile-nav');

        btn.addEventListener('click', () => {
            nav.classList.toggle('hidden');
        });
    </script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>

</html>