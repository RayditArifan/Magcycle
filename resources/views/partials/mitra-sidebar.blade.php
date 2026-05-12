<aside class="w-[280px] min-h-screen bg-[#1fa16f] text-white flex flex-col shrink-0">
    {{-- Logo --}}
    <div class="px-8 py-10 border-b border-white/25">
        <div class="flex flex-col items-center">
            <img
                src="{{ asset('assets/img/logo-magcycle.png') }}"
                alt="Logo MagCycle"
                class="w-[95px] h-auto object-contain mb-3"
            >

            <h1 class="text-[26px] font-semibold leading-none text-white">
                MagCycle
            </h1>
        </div>
    </div>

    {{-- Menu --}}
    <nav class="flex-1 py-4">
        <div class="px-7 mb-3">
            <p class="text-[15px] font-bold uppercase tracking-wide text-white">
                UTAMA
            </p>
        </div>

        <div class="space-y-1">
            <a href="{{ route('mitra.beranda') }}"
               class="flex items-center gap-4 px-10 py-4 text-[17px] font-medium transition
               {{ request()->routeIs('mitra.beranda') ? 'bg-[#37c793] text-white' : 'text-white hover:bg-white/10' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 10.25 12 3l9 7.25V20a1 1 0 0 1-1 1h-5.75v-6h-4.5v6H4a1 1 0 0 1-1-1v-9.75Z"/>
                </svg>
                <span>Beranda</span>
            </a>

            <a href="#"
               class="flex items-center gap-4 px-10 py-4 text-[17px] font-medium text-white hover:bg-white/10 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0 1 18 14.158V11a6.002 6.002 0 0 0-4-5.659V5a2 2 0 1 0-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 1 1-6 0m6 0H9"/>
                </svg>
                <span>Notifikasi</span>
            </a>
        </div>

        <div class="px-7 mt-6 mb-3">
            <p class="text-[15px] font-bold uppercase tracking-wide text-white">
                LAYANAN
            </p>
        </div>

        <div class="space-y-1">
            <a href="{{ route('mitra.pengambilan-sampah.index') }}"
               class="flex items-center gap-4 px-10 py-4 text-[17px] font-medium text-white hover:bg-white/10 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M8 7V3m8 4V3m-9 8h10m-12 9h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2Z"/>
                </svg>
                <span>Pengambilan Sampah</span>
            </a>

            <a href="#"
               class="flex items-center gap-4 px-10 py-4 text-[17px] font-medium text-white hover:bg-white/10 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M10 14.25h2.25a1.75 1.75 0 0 0 0-3.5H11.5a1.75 1.75 0 0 1 0-3.5H14m-2-1.25V18"/>
                </svg>
                <span>MagPoin</span>
            </a>
        </div>
    </nav>
</aside>
