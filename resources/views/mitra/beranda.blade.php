<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda Mitra - MagCycle</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#f6f6f6] text-[#4a4a4a]">
    <div class="flex min-h-screen">
        @include('partials.mitra-sidebar')

        <div class="flex min-h-screen flex-1 flex-col">
            {{-- Topbar --}}
            <header class="border-b border-[#c8c8c8] bg-white">
                <div class="flex items-center justify-between" style="height: 70px; padding-left: 50px; padding-right: 50px;">
                    <h1 class="text-[38px] font-medium text-[#4a4a4a]">
                        Welcome back, {{ $username }}!
                    </h1>

                    @include('partials.mitra-topbar-actions', [
                        'notificationCount' => $notificationCount ?? 0
                    ])
                </div>
            </header>

            {{-- Content --}}
            <main class="flex-1 px-10 pb-10">
                <div class="mt-8">
                    <h2 class="mb-6 text-[22px] font-medium text-[#0f9b72]">
                        Panduan penyetoran sampah
                    </h2>

                    <section class="rounded-[24px] border border-[#37c793] bg-white px-8 py-8">
                        @if ($panduanSetor->isEmpty())
                            <div class="mx-auto max-w-[520px] rounded-[18px] bg-[#a9dece] px-6 py-6 text-center text-[#3f6f62]">
                                <p class="text-[18px] font-bold">
                                    Belum ada panduan setor sampah.
                                </p>
                            </div>
                        @else
                            <div class="space-y-6">
                                @foreach ($panduanSetor as $panduan)
                                    <article class="rounded-[20px] bg-[#a9dece] px-6 py-5 text-[#3f6f62]">
                                        <h3 class="mb-4 text-[22px] font-bold">
                                            {{ $panduan->judul }}
                                        </h3>

                                        @if (!empty($panduan->deskripsi))
                                            <p class="mb-4 text-[17px] font-semibold leading-relaxed">
                                                {{ $panduan->deskripsi }}
                                            </p>
                                        @endif

                                        @if (!empty($panduan->video_panduan))
                                            @php
                                                $videoPath = $panduan->video_panduan;
                                                $videoUrl = \Illuminate\Support\Str::startsWith($videoPath, ['http://', 'https://'])
                                                    ? $videoPath
                                                    : asset('storage/' . $videoPath);
                                                $videoExtension = strtolower(pathinfo($videoPath, PATHINFO_EXTENSION));
                                                $videoMime = $videoExtension === 'webm' ? 'video/webm' : 'video/mp4';
                                            @endphp

                                            <video controls preload="metadata" class="w-full max-w-[640px] rounded-xl bg-black shadow-sm">
                                                <source src="{{ $videoUrl }}" type="{{ $videoMime }}">
                                                Browser kamu tidak mendukung video.
                                            </video>
                                        @else
                                            <p class="text-[15px] font-medium">
                                                Video panduan belum tersedia.
                                            </p>
                                        @endif
                                    </article>
                                @endforeach
                            </div>
                        @endif
                    </section>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
