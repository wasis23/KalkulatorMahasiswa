@extends('layouts.app')

@section('title', 'Tambah Program Studi Baru - Panel Admin')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between pb-4 border-b border-slate-200">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Tambah Program Studi Baru</h1>
            <p class="text-slate-500 text-sm font-medium">Buat program studi baru beserta rincian 7 komponen biaya wajib.</p>
        </div>
        <a href="{{ route('admin.prodi.index') }}" class="px-4 py-2 rounded-xl text-sm font-semibold border border-slate-200 text-slate-600 bg-white hover:bg-slate-50 transition-colors">
            Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-xs">
        <form action="{{ route('admin.prodi.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Program Studi Name -->
                <div class="md:col-span-2">
                    <label for="nama_prodi" class="block text-sm font-semibold text-slate-700 mb-2">Nama Program Studi</label>
                    <input type="text" name="nama_prodi" id="nama_prodi" value="{{ old('nama_prodi') }}" required
                        class="block w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white text-slate-900 placeholder-slate-400 font-medium text-sm transition-all outline-none"
                        placeholder="Contoh: Teknologi Rekayasa Perangkat Lunak">
                </div>

                <!-- Jenjang -->
                <div>
                    <label for="jenjang" class="block text-sm font-semibold text-slate-700 mb-2">Jenjang</label>
                    <select name="jenjang" id="jenjang" required
                        class="block w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white text-slate-900 font-medium text-sm transition-all outline-none">
                        <option value="S1" {{ old('jenjang') == 'S1' ? 'selected' : '' }}>S1</option>
                        <option value="D4" {{ old('jenjang') == 'D4' ? 'selected' : '' }}>D4</option>
                        <option value="D3" {{ old('jenjang') == 'D3' ? 'selected' : '' }}>D3</option>
                    </select>
                </div>
            </div>

            <div class="border-t border-slate-100 my-6 pt-6">
                <h3 class="font-bold text-slate-900 text-base mb-4">Konfigurasi Nominal Biaya Wajib (Rupiah)</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- UKT -->
                    <div>
                        <label for="ukt" class="block text-sm font-semibold text-slate-700 mb-2">UKT (Uang Kuliah Tunggal) / Semester</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 font-bold text-xs">Rp</span>
                            <input type="number" name="ukt" id="ukt" value="{{ old('ukt', 0) }}" min="0" required
                                class="block w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white text-slate-900 font-medium text-sm transition-all outline-none">
                        </div>
                    </div>

                    <!-- DPI -->
                    <div>
                        <label for="dpi" class="block text-sm font-semibold text-slate-700 mb-2">DPI (Uang Pangkal / Gedung)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 font-bold text-xs">Rp</span>
                            <input type="number" name="dpi" id="dpi" value="{{ old('dpi', 0) }}" min="0" required
                                class="block w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white text-slate-900 font-medium text-sm transition-all outline-none">
                        </div>
                    </div>

                    <!-- Seragam -->
                    <div>
                        <label for="seragam" class="block text-sm font-semibold text-slate-700 mb-2">Seragam</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 font-bold text-xs">Rp</span>
                            <input type="number" name="seragam" id="seragam" value="{{ old('seragam', 0) }}" min="0" required
                                class="block w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white text-slate-900 font-medium text-sm transition-all outline-none">
                        </div>
                    </div>

                    <!-- Atribut -->
                    <div>
                        <label for="atribut" class="block text-sm font-semibold text-slate-700 mb-2">Atribut (Almamater, KTM, dll)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 font-bold text-xs">Rp</span>
                            <input type="number" name="atribut" id="atribut" value="{{ old('atribut', 0) }}" min="0" required
                                class="block w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white text-slate-900 font-medium text-sm transition-all outline-none">
                        </div>
                    </div>

                    <!-- PKL -->
                    <div>
                        <label for="pkl" class="block text-sm font-semibold text-slate-700 mb-2">PKL (Praktik Kerja Lapangan)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 font-bold text-xs">Rp</span>
                            <input type="number" name="pkl" id="pkl" value="{{ old('pkl', 0) }}" min="0" required
                                class="block w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white text-slate-900 font-medium text-sm transition-all outline-none">
                        </div>
                    </div>

                    <!-- TA -->
                    <div>
                        <label for="ta" class="block text-sm font-semibold text-slate-700 mb-2">TA (Tugas Akhir / Skripsi)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 font-bold text-xs">Rp</span>
                            <input type="number" name="ta" id="ta" value="{{ old('ta', 0) }}" min="0" required
                                class="block w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white text-slate-900 font-medium text-sm transition-all outline-none">
                        </div>
                    </div>

                    <!-- Wisuda -->
                    <div class="md:col-span-2">
                        <label for="wisuda" class="block text-sm font-semibold text-slate-700 mb-2">Wisuda</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 font-bold text-xs">Rp</span>
                            <input type="number" name="wisuda" id="wisuda" value="{{ old('wisuda', 0) }}" min="0" required
                                class="block w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white text-slate-900 font-medium text-sm transition-all outline-none">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end gap-3 border-t border-slate-100 pt-6">
                <a href="{{ route('admin.prodi.index') }}" class="px-5 py-2.5 rounded-xl text-sm font-semibold border border-slate-200 text-slate-600 bg-white hover:bg-slate-50 transition-colors">
                    Batalkan
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-100 hover:shadow-lg transition-all cursor-pointer">
                    Simpan Program Studi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
