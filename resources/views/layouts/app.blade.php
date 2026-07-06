<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kalkulator Biaya Kuliah Mahasiswa')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind v4 -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }
        .glassmorphism {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col text-slate-800">

    <!-- Header / Navigation -->
    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200/80 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-500 flex items-center justify-center text-white font-bold shadow-md shadow-indigo-200 group-hover:scale-105 transition-transform duration-200">
                            🎓
                        </div>
                        <div>
                            <span class="font-extrabold text-lg bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">Kalkulator</span>
                            <span class="font-semibold text-slate-600 text-sm block -mt-1">Biaya & Beasiswa</span>
                        </div>
                    </a>
                </div>

                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors">
                            Dashboard Admin
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 rounded-xl text-sm font-semibold bg-rose-50 text-rose-600 hover:bg-rose-100 transition-all cursor-pointer">
                                Keluar
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl text-sm font-semibold text-indigo-600 hover:bg-indigo-50 border border-indigo-100 transition-all">
                            Login Admin
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Alert Notifications -->
    @if(session('success') || session('error') || $errors->any())
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
            @if(session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-800 flex items-center gap-3">
                    <span class="text-xl">✅</span>
                    <span class="font-medium text-sm">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-100 text-rose-800 flex items-center gap-3">
                    <span class="text-xl">⚠️</span>
                    <span class="font-medium text-sm">{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any() && !session('error'))
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-100 text-rose-800 flex flex-col gap-1">
                    <div class="flex items-center gap-3 font-semibold text-sm">
                        <span class="text-xl">⚠️</span>
                        <span>Terjadi kesalahan input:</span>
                    </div>
                    <ul class="list-disc pl-8 text-xs font-medium mt-1 space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    @endif

    <!-- Main Content -->
    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-xs text-slate-500">
            <p>&copy; {{ date('Y') }} Kalkulator Biaya Kuliah Mahasiswa. Dibuat dengan presisi dan standar profesional.</p>
        </div>
    </footer>

    <script>
        document.addEventListener('submit', function(e) {
            const confirmMsg = e.target.getAttribute('data-confirm');
            if (confirmMsg) {
                if (!confirm(confirmMsg)) {
                    e.preventDefault();
                }
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
