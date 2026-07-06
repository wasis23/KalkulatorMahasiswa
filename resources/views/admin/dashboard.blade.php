@extends('layouts.app')

@section('title', 'Admin Dashboard - Kalkulator Mahasiswa')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Greeting -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">Dashboard Panel Kendali</h1>
            <p class="text-slate-500 font-medium">Selamat datang, Anda login sebagai Administrator.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.prodi.index') }}" class="px-4 py-2.5 rounded-xl text-sm font-semibold bg-indigo-600 text-white shadow-md shadow-indigo-100 hover:bg-indigo-700 hover:shadow-lg transition-all cursor-pointer">
                📦 Kelola Prodi & Biaya
            </a>
            <a href="{{ route('admin.beasiswa.index') }}" class="px-4 py-2.5 rounded-xl text-sm font-semibold bg-violet-600 text-white shadow-md shadow-violet-100 hover:bg-violet-700 hover:shadow-lg transition-all cursor-pointer">
                🎟️ Kelola Beasiswa & Aturan
            </a>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Stat Card 1 -->
        <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-xs flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-3xl">
                🏫
            </div>
            <div>
                <span class="text-slate-400 text-xs font-bold uppercase tracking-wider block">Total Program Studi</span>
                <span class="text-3xl font-extrabold text-slate-900 mt-1 block">{{ $total_prodi }}</span>
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-xs flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-violet-50 border border-violet-100 flex items-center justify-center text-3xl">
                🎁
            </div>
            <div>
                <span class="text-slate-400 text-xs font-bold uppercase tracking-wider block">Total Beasiswa</span>
                <span class="text-3xl font-extrabold text-slate-900 mt-1 block">{{ $total_beasiswa }}</span>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-xs flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-center text-3xl">
                ⚙️
            </div>
            <div>
                <span class="text-slate-400 text-xs font-bold uppercase tracking-wider block">Total Aturan Potongan</span>
                <span class="text-3xl font-extrabold text-slate-900 mt-1 block">{{ $total_rules }}</span>
            </div>
        </div>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Prodi Overview -->
        <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                <h3 class="font-bold text-lg text-slate-950 flex items-center gap-2">
                    <span>🏫</span> Program Studi Terbaru
                </h3>
                <a href="{{ route('admin.prodi.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 transition-colors">
                    Lihat Semua &rarr;
                </a>
            </div>
            @if($prodis->isEmpty())
                <div class="py-8 text-center text-slate-400 text-sm">
                    Belum ada data Program Studi.
                </div>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($prodis as $prodi)
                        <div class="py-3.5 flex items-center justify-between">
                            <div>
                                <span class="font-semibold text-slate-800 text-sm block">{{ $prodi->nama_prodi }}</span>
                                <span class="text-xs text-slate-400 font-medium">UKT: Rp {{ number_format($prodi->ukt, 0, ',', '.') }} | DPI: Rp {{ number_format($prodi->dpi, 0, ',', '.') }}</span>
                            </div>
                            <a href="{{ route('admin.prodi.edit', $prodi->id) }}" class="text-xs font-bold text-indigo-500 hover:text-indigo-700 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition-all">
                                Edit
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Beasiswa Overview -->
        <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                <h3 class="font-bold text-lg text-slate-950 flex items-center gap-2">
                    <span>🎁</span> Beasiswa Terbaru
                </h3>
                <a href="{{ route('admin.beasiswa.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 transition-colors">
                    Lihat Semua &rarr;
                </a>
            </div>
            @if($beasiswas->isEmpty())
                <div class="py-8 text-center text-slate-400 text-sm">
                    Belum ada data Beasiswa.
                </div>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($beasiswas as $beasiswa)
                        <div class="py-3.5 flex items-center justify-between">
                            <div>
                                <span class="font-bold text-slate-800 text-sm block">{{ $beasiswa->nama_beasiswa }}</span>
                                <span class="text-xs text-slate-400 font-medium">{{ $beasiswa->rules_count }} aturan aktif</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.beasiswa.rules', $beasiswa->id) }}" class="text-xs font-bold text-violet-600 hover:text-violet-700 bg-violet-50 hover:bg-violet-100 px-3 py-1.5 rounded-lg transition-all">
                                    Aturan
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
