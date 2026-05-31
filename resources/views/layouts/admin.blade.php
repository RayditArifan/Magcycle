<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin MagCycle' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/notifikasi.css') }}">
</head>
@include('partials.success-popup')
@include('partials.confirm-change-popup')
@include('partials.error-popup')
@include('partials.success-popup')
<body class="min-h-screen bg-[#f5f5f5]">
    <div class="flex min-h-screen">
        @include('partials.admin-sidebar')

        <main class="flex-1 min-w-0">
            <header class="h-[105px] bg-white border-b border-[#cfcfcf] flex items-center justify-between px-8 md:px-10">
                <h1 class="text-[30px] md:text-[36px] font-semibold text-[#4a4a4a] tracking-tight">
                    @yield('page_title')
                </h1>

                    @include('partials.topbar-actions', [
                        'notificationCount' => $notificationCount ?? 0,
                        'profileRoute' => route('admin.profile'),
                        'mitraProfilesRoute' => route('admin.mitra.profiles')
                    ])
            </header>

            @yield('content')
        </main>
    </div>
    <script src="{{ asset('js/notifikasi.js') }}"></script>
</body>
</html>
