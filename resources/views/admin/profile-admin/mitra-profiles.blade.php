@extends('layouts.admin')

@section('page_title', 'Daftar Mitra')

@section('content')
<section class="px-8 py-6">
    @if(session('error'))
        <div class="mb-4 rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-600">
            {{ session('error') }}
        </div>
    @endif

    <h2 class="mb-4 text-[20px] font-semibold text-[#2f2f2f]">Daftar Akun Mitra</h2>

    {{-- Search Bar --}}
    <form method="GET" action="{{ route('admin.mitra.profiles') }}" class="mb-5">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Cari username atau alamat"
            class="w-full rounded-lg border border-[#b5ddd0] bg-white px-5 py-[10px] text-[15px] text-[#4a4a4a] shadow-sm outline-none focus:border-[#2db88b] focus:ring-2 focus:ring-[#2db88b]/20 transition"
        />
    </form>

    <div class="overflow-hidden rounded-[20px] border border-[#2db88b] bg-white">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead>
                    <tr class="border-b border-[#d5efe6] h-[52px] text-[16px] text-[#4e4e4e] bg-white">
                        <th class="w-[64px] px-4"></th>
                        <th class="px-4 font-semibold">Akun Mitra</th>
                        <th class="px-4 font-semibold">Alamat</th>
                        <th class="px-4 font-semibold w-[160px]">Status</th>
                        <th class="px-4 font-semibold w-[100px] text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($mitras as $mitra)
                        <tr class="border-t border-[#d5efe6] odd:bg-[#8fd3bd] even:bg-white text-[16px] text-[#4a4a4a] hover:brightness-95 transition">
                            {{-- Avatar --}}
                            <td class="px-4 py-3">
                                <div class="h-10 w-10 rounded-full bg-[#d9d9d9] flex items-center justify-center overflow-hidden">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#6b6b6b]" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 10a4 4 0 100-8 4 4 0 000 8Zm-7 8a7 7 0 1114 0H3Z"/>
                                    </svg>
                                </div>
                            </td>

                            {{-- Username --}}
                            <td class="px-4 py-3 font-medium">
                                {{ $mitra->username }}
                            </td>

                            {{-- Alamat --}}
                            <td class="px-4 py-3 text-[15px]">
                                {{ $mitra->alamat }}
                            </td>

                            {{-- Status --}}
                            <td class="px-4 py-3">
                                @php
                                    $badgeClass = match(strtolower($mitra->status_akun)) {
                                        'aktif'  => 'bg-[#0f8b61] text-white',
                                        'banned' => 'bg-[#d91616] text-white',
                                        default  => 'bg-gray-500 text-white',
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-1 rounded-[4px] px-2 py-[3px] text-[12px] font-semibold {{ $badgeClass }}">
                                    • {{ ucfirst($mitra->status_akun) }}
                                </span>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-4 py-3 text-center">
                                <a
                                    href="{{ route('admin.mitra.profiles.show', $mitra->id_mitra) }}"
                                    class="inline-flex items-center justify-center rounded-[6px] border border-black bg-transparent px-4 py-[5px] text-[13px] font-semibold text-black hover:bg-black hover:text-white transition"
                                >
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-[15px] text-gray-400">
                                Belum ada data profil mitra.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="flex items-center justify-between border-t border-[#2db88b] px-5 py-4">
            <p class="text-[15px] text-[#4a4a4a]">
                Menampilkan {{ $mitras->count() }} dari {{ $mitras->total() }} mitra
            </p>

            <div class="flex items-center gap-2">
                @if ($mitras->onFirstPage())
                    <span class="flex h-8 w-8 items-center justify-center rounded border border-[#ccc] text-[#aaa] cursor-not-allowed select-none">‹</span>
                @else
                    <a href="{{ $mitras->previousPageUrl() }}" class="flex h-8 w-8 items-center justify-center rounded border border-[#2db88b] text-[#2db88b] hover:bg-[#e8faf3] transition">‹</a>
                @endif

                @for ($page = 1; $page <= $mitras->lastPage(); $page++)
                    <a href="{{ $mitras->url($page) }}"
                       class="flex h-8 min-w-[32px] items-center justify-center rounded border px-2 text-[14px] transition
                       {{ $mitras->currentPage() == $page
                            ? 'border-[#2db88b] bg-[#8fd3bd] text-[#2b6d59] font-semibold'
                            : 'border-[#2db88b] text-[#2db88b] hover:bg-[#e8faf3]' }}">
                        {{ $page }}
                    </a>
                @endfor

                @if ($mitras->hasMorePages())
                    <a href="{{ $mitras->nextPageUrl() }}" class="flex h-8 w-8 items-center justify-center rounded border border-[#2db88b] text-[#2db88b] hover:bg-[#e8faf3] transition">›</a>
                @else
                    <span class="flex h-8 w-8 items-center justify-center rounded border border-[#ccc] text-[#aaa] cursor-not-allowed select-none">›</span>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
