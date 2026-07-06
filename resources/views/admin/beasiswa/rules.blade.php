@extends('layouts.app')

@section('title', 'Aturan Potongan Beasiswa ' . $beasiswa->nama_beasiswa . ' - Panel Admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.beasiswa.index') }}" class="text-slate-400 hover:text-indigo-600 transition-colors text-sm font-semibold">Beasiswa</a>
                <span class="text-slate-300">/</span>
                <span class="font-extrabold text-slate-800 text-sm bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded">
                    {{ $beasiswa->nama_beasiswa }}
                </span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 mt-2">Kelola Aturan Potongan</h1>
            <p class="text-slate-500 text-sm font-medium">Tentukan komponen biaya mana saja yang dipotong atau digratiskan oleh beasiswa ini.</p>
        </div>
        <a href="{{ route('admin.beasiswa.index') }}" class="px-4 py-2 rounded-xl text-sm font-semibold border border-slate-200 text-slate-600 bg-white hover:bg-slate-50 transition-colors">
            Kembali
        </a>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
        
        <!-- Left: Rule Config Form (Takes 2 cols) -->
        <div class="md:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-xs p-6 h-fit">
            <h3 class="font-bold text-slate-900 text-base mb-4 flex items-center gap-2">
                <span>⚙️</span> Buat / Update Aturan
            </h3>

            <form action="{{ route('admin.beasiswa.storeRule', $beasiswa->id) }}" method="POST" class="space-y-4">
                @csrf

                <!-- Cost Component Selection -->
                <div>
                    <label for="komponen_biaya" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Komponen Biaya</label>
                    <select name="komponen_biaya" id="komponen_biaya" required
                        class="block w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white text-slate-900 font-medium text-sm transition-all outline-none">
                        <option value="">-- Pilih Komponen --</option>
                        @foreach($komponen_list as $key => $label)
                            <option value="{{ $key }}" {{ old('komponen_biaya') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Discount Type -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Jenis Potongan</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center justify-center p-3 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer select-none">
                            <input type="radio" name="jenis_potongan" value="persen" required {{ old('jenis_potongan', 'persen') == 'persen' ? 'checked' : '' }}
                                class="h-4 w-4 border-slate-300 text-indigo-600 focus:ring-indigo-500 mr-2">
                            <span class="text-xs font-bold text-slate-700">Persentase (%)</span>
                        </label>
                        <label class="flex items-center justify-center p-3 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer select-none">
                            <input type="radio" name="jenis_potongan" value="nominal" required {{ old('jenis_potongan') == 'nominal' ? 'checked' : '' }}
                                class="h-4 w-4 border-slate-300 text-indigo-600 focus:ring-indigo-500 mr-2">
                            <span class="text-xs font-bold text-slate-700">Nominal (Rp)</span>
                        </label>
                    </div>
                </div>

                <!-- Discount Value -->
                <div>
                    <label for="nilai_potongan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nilai Potongan</label>
                    <div class="relative">
                        <input type="number" name="nilai_potongan" id="nilai_potongan" value="{{ old('nilai_potongan', 0) }}" min="0" required
                            class="block w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white text-slate-900 font-semibold text-sm transition-all outline-none">
                    </div>
                    <span class="text-slate-400 text-[10px] font-semibold mt-1 block">
                        * Persentase: 1 - 100 (Contoh: 100 untuk gratis sepenuhnya).<br>
                        * Nominal: Masukkan nominal rupiah (Contoh: 2000000 untuk potongan Rp 2.000.000).
                    </span>
                </div>

                <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-100 hover:shadow-lg transition-all cursor-pointer">
                    Simpan Aturan
                </button>
            </form>
        </div>

        <!-- Right: Current Rules List (Takes 3 cols) -->
        <div class="md:col-span-3 bg-white rounded-2xl border border-slate-200 shadow-xs p-6">
            <h3 class="font-bold text-slate-900 text-base mb-4 flex items-center gap-2">
                <span>📋</span> Aturan Potongan Aktif
            </h3>

            @if($beasiswa->rules->isEmpty())
                <div class="py-12 text-center text-slate-400">
                    <span class="text-4xl block mb-2">🤷‍♂️</span>
                    <p class="font-semibold text-xs">Belum ada aturan potongan untuk beasiswa ini.</p>
                    <p class="text-[10px] mt-1">Gunakan formulir disamping untuk menambahkan aturan.</p>
                </div>
            @else
                <div class="overflow-hidden border border-slate-100 rounded-xl">
                    <table class="w-full text-left border-collapse text-xs font-semibold">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 uppercase tracking-wider font-bold">
                                <th class="py-3 px-4">Komponen</th>
                                <th class="py-3 px-4">Jenis</th>
                                <th class="py-3 px-4 text-right">Potongan</th>
                                <th class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($beasiswa->rules as $rule)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="py-3.5 px-4 font-bold text-slate-900 uppercase">
                                        {{ $rule->komponen_biaya }}
                                    </td>
                                    <td class="py-3.5 px-4 font-medium">
                                        @if($rule->jenis_potongan === 'persen')
                                            <span class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-2 py-0.5 rounded-md">Persentase</span>
                                        @else
                                            <span class="bg-sky-50 border border-sky-100 text-sky-700 px-2 py-0.5 rounded-md">Nominal</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-right font-bold text-slate-900">
                                        @if($rule->jenis_potongan === 'persen')
                                            {{ $rule->nilai_potongan }}%
                                        @else
                                            Rp {{ number_format($rule->nilai_potongan, 0, ',', '.') }}
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        <form action="{{ route('admin.beasiswa.destroyRule', [$beasiswa->id, $rule->id]) }}" method="POST" class="inline" data-confirm="Apakah Anda yakin ingin menghapus aturan potongan ini?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold bg-rose-50 hover:bg-rose-100 px-2 py-1 rounded-md transition-colors cursor-pointer">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
