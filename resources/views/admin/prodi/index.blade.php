@extends('layouts.app')

@section('title', 'Manajemen Program Studi - Panel Admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Manajemen Program Studi</h1>
            <p class="text-slate-500 text-sm font-medium">Kelola data program studi beserta konfigurasi 7 komponen biaya wajib.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-xl text-sm font-semibold border border-slate-200 text-slate-600 bg-white hover:bg-slate-50 transition-colors">
                &larr; Dashboard
            </a>
            <a href="{{ route('admin.prodi.create') }}" class="px-4 py-2 rounded-xl text-sm font-semibold bg-indigo-600 text-white hover:bg-indigo-700 shadow-md shadow-indigo-100 hover:shadow-lg transition-all cursor-pointer">
                ➕ Tambah Prodi Baru
            </a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        @if($prodis->isEmpty())
            <div class="py-16 text-center text-slate-400">
                <span class="text-5xl block mb-4">🏫</span>
                <p class="font-medium text-sm">Belum ada Program Studi yang terdaftar.</p>
                <a href="{{ route('admin.prodi.create') }}" class="mt-4 inline-block text-sm font-bold text-indigo-600 hover:underline">
                    Tambah Prodi Pertama Anda &rarr;
                </a>
            </div>
        @else
            <div>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold uppercase tracking-wider text-slate-500">
                            <th class="py-3 px-2 whitespace-nowrap">Nama Prodi</th>
                            <th class="py-3 px-2 text-center whitespace-nowrap">Jenjang</th>
                            <th class="py-3 px-2 text-right whitespace-nowrap">UKT (per sem)</th>
                            <th class="py-3 px-2 text-right whitespace-nowrap">DPI (Gedung)</th>
                            <th class="py-3 px-2 text-right whitespace-nowrap">Seragam</th>
                            <th class="py-3 px-2 text-right whitespace-nowrap">Atribut</th>
                            <th class="py-3 px-2 text-right whitespace-nowrap">PKL</th>
                            <th class="py-3 px-2 text-right whitespace-nowrap">Tugas Akhir</th>
                            <th class="py-3 px-2 text-right whitespace-nowrap">Wisuda</th>
                            <th class="py-3 px-2 text-center whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                        @foreach($prodis as $prodi)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-3 px-2 font-bold text-slate-900">
                                    {{ $prodi->nama_prodi }}
                                </td>
                                <td class="py-3 px-2 text-center">
                                    <span class="px-2 py-0.5 rounded-md text-xs font-extrabold tracking-wide uppercase 
                                        {{ $prodi->jenjang === 'D3' ? 'bg-amber-50 text-amber-700 border border-amber-200' : ($prodi->jenjang === 'D4' ? 'bg-violet-50 text-violet-700 border border-violet-200' : 'bg-sky-50 text-sky-700 border border-sky-200') }}">
                                        {{ $prodi->jenjang }}
                                    </span>
                                </td>
                                <td class="py-3 px-2 text-right text-indigo-600 font-semibold whitespace-nowrap">
                                    Rp {{ number_format($prodi->ukt, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-2 text-right text-slate-600 whitespace-nowrap">
                                    Rp {{ number_format($prodi->dpi, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-2 text-right text-slate-600 whitespace-nowrap">
                                    Rp {{ number_format($prodi->seragam, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-2 text-right text-slate-600 whitespace-nowrap">
                                    Rp {{ number_format($prodi->atribut, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-2 text-right text-slate-600 whitespace-nowrap">
                                    Rp {{ number_format($prodi->pkl, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-2 text-right text-slate-600 whitespace-nowrap">
                                    Rp {{ number_format($prodi->ta, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-2 text-right text-slate-600 whitespace-nowrap">
                                    Rp {{ number_format($prodi->wisuda, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-2">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.prodi.edit', $prodi->id) }}" class="p-1.5 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-colors" title="Edit">
                                            ✏️ Edit
                                        </a>
                                        <form action="{{ route('admin.prodi.destroy', $prodi->id) }}" method="POST" class="inline" data-confirm="Apakah Anda yakin ingin menghapus program studi ini? Seluruh data biaya prodi ini akan dihapus.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors cursor-pointer" title="Hapus">
                                                🗑️ Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
