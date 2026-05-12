@extends('layouts.admin')

@section('page_title', 'Welcome to your dashboard, ' . ($adminName ?? 'Admin'))

@section('content')
    <section class="px-8 py-5">
        <div class="mb-8">
            <h2 class="mb-2 text-[18px] font-medium text-[#1d9d73]">
                Monitoring Siklus Maggot
            </h2>

            <div class="rounded-[18px] border border-[#2db88b] bg-[#d9efe7] px-6 py-7">
                <p class="text-[17px] font-medium text-[#4a4a4a]">
                    Data monitoring belum tersedia
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-10 xl:grid-cols-[420px_1fr]">
            <div>
                <div class="mb-2 flex items-center justify-between">
                    <h2 class="text-[18px] font-medium text-[#1d9d73]">
                        Stok Produk
                    </h2>
                </div>

                <div class="overflow-hidden rounded-[16px] border border-[#2db88b] bg-white">
                    <div class="flex items-center justify-between px-5 py-3">
                        <p class="text-[15px] font-medium text-[#4a4a4a]">
                            Stok tersedia
                        </p>

                        <a href="{{ route('admin.stok.index') }}"
                           class="inline-flex items-center rounded-[7px] border border-[#555] px-3 py-1 text-[13px] font-medium text-[#4a4a4a] hover:bg-gray-50">
                            Kelola
                            <span class="ml-1">→</span>
                        </a>
                    </div>

                    @if(isset($stocks) && $stocks->count() > 0)
                        <div class="border-t border-[#2db88b]">
                            <div class="grid grid-cols-2 px-5 py-2 text-[15px] font-medium text-[#4a4a4a]">
                                <div>Jenis Produk</div>
                                <div>Jumlah Stok</div>
                            </div>

                            @foreach($stocks as $stock)
                                <div class="grid grid-cols-2 px-5 py-3 text-[16px] text-[#4a4a4a] {{ $loop->odd ? 'bg-[#9fd8c8]' : 'bg-white' }}">
                                    <div>{{ $stock->jenis_produk }}</div>
                                    <div>
                                        {{ rtrim(rtrim(number_format((float) $stock->jumlah_stok, 2, '.', ''), '0'), '.') }}
                                        {{ $stock->satuan ?? 'kg' }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="border-t border-[#2db88b] px-5 py-7">
                            <p class="text-[17px] font-medium text-[#4a4a4a]">
                                Data stok produk belum tersedia
                            </p>
                        </div>
                    @endif
                </div>
            </div>


            <div>
                <div class="mb-2 flex items-center justify-between">
                    <h2 class="text-[18px] font-medium text-[#1d9d73]">
                        Jadwal Pengambilan Sampah
                    </h2>
                    <a href="{{ route('admin.pengambilan-sampah.index') }}"
                       class="inline-flex items-center rounded-[7px] border border-[#555] px-3 py-1 text-[13px] font-medium text-[#4a4a4a] hover:bg-gray-50">
                        Kelola
                        <span class="ml-1">→</span>
                    </a>
                </div>

                <div class="overflow-hidden rounded-[16px] border border-[#2db88b] bg-white">
                    @if(isset($jadwalSampah) && $jadwalSampah->count() > 0)
                        <div class="border-b border-[#2db88b]">
                            <div class="grid grid-cols-[1fr_130px_110px] px-5 py-2 text-[15px] font-medium text-[#4a4a4a]">
                                <div>Mitra</div>
                                <div>Tanggal</div>
                                <div>Status</div>
                            </div>
                        </div>

                        @foreach($jadwalSampah as $jadwal)
                            <div class="grid grid-cols-[1fr_130px_110px] items-center px-5 py-3 text-[15px] text-[#4a4a4a] {{ $loop->odd ? 'bg-[#9fd8c8]' : 'bg-white' }}">
                                <div>{{ $jadwal->username ?? '-' }}</div>
                                <div>{{ \Carbon\Carbon::parse($jadwal->tanggal_pengambilan)->translatedFormat('d M Y') }}</div>
                                <div>
                                    @if($jadwal->status_text === 'menunggu konfirmasi')
                                        <span class="inline-flex items-center rounded bg-[#d38b00] px-2 py-0.5 text-[11px] font-medium text-white leading-tight">
                                            Menunggu
                                        </span>
                                    @elseif($jadwal->status_text === 'disetujui')
                                        <span class="inline-flex items-center rounded bg-[#087250] px-2 py-0.5 text-[11px] font-medium text-white">
                                            Disetujui
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="px-5 py-7">
                            <p class="text-[15px] font-medium text-[#4a4a4a]">
                                Data jadwal pengambilan sampah belum tersedia
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
