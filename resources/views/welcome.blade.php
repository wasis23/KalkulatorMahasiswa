@extends('layouts.app')

@section('title', 'Kalkulator Estimasi Biaya Kuliah Mahasiswa')

@section('content')
<div class="space-y-10">
    <!-- Hero Section -->
    <div class="text-center max-w-3xl mx-auto space-y-4">
        <span id="hero-badge" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 border border-indigo-100 text-indigo-700 uppercase tracking-wider">
            ⚡ Simulasi Biaya Transparan
        </span>
        <h1 class="text-4xl sm:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight" id="hero-title">
            Kalkulator Biaya Kuliah <span class="bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent font-extrabold">Sampai Lulus</span>
        </h1>
        <p class="text-slate-500 font-medium text-base sm:text-lg" id="hero-desc">
            Hitung perkiraan total biaya kuliah Anda dari semester pertama hingga wisuda, disesuaikan dengan beasiswa potongan yang Anda dapatkan secara otomatis.
        </p>
    </div>

    <!-- Tab Selector -->
    <div class="flex justify-center">
        <div class="inline-flex p-1 bg-slate-100/80 rounded-2xl border border-slate-200/50 backdrop-blur-md shadow-xs">
            <button type="button" id="btn-tab-biaya" onclick="switchTab('biaya')" class="flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 cursor-pointer bg-white text-indigo-600 shadow-xs">
                💰 Simulasi Biaya Kuliah
            </button>
            <button type="button" id="btn-tab-ipk" onclick="switchTab('ipk')" class="flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 cursor-pointer text-slate-500 hover:text-slate-800">
                📈 Target IPK Wisuda
            </button>
        </div>
    </div>

    <!-- Main Workspace Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left: Input Form (Takes 4 cols) -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Container Form Biaya -->
            <div id="form-container-biaya" class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs">
                <h3 class="font-bold text-slate-900 text-lg mb-6 flex items-center gap-2 pb-3 border-b border-slate-100">
                    <span>📝</span> Parameter Simulasi
                </h3>

                <form action="{{ route('home') }}" method="GET" class="space-y-6">
                    <input type="hidden" name="tab" value="biaya">
                <!-- Dropdown Prodi -->
                <div>
                    <label for="prodi_id" class="block text-sm font-semibold text-slate-700 mb-2">Program Studi</label>
                    <select name="prodi_id" id="prodi_id" required onchange="updateSemesterCount()"
                        class="block w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white text-slate-900 font-medium text-sm transition-all outline-none">
                        <option value="">-- Pilih Program Studi --</option>
                        @foreach($prodis as $prodi)
                            <option value="{{ $prodi->id }}" data-jenjang="{{ $prodi->jenjang }}" {{ $selected_prodi_id == $prodi->id ? 'selected' : '' }}>
                                {{ $prodi->nama_prodi }} ({{ $prodi->jenjang }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-slate-400 font-semibold mt-1">Biaya tiap komponen disesuaikan dengan program studi pilihan.</p>
                </div>

                <!-- Masa Studi (Otomatis) -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/60">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm font-semibold text-slate-700">Masa Studi (Semester)</span>
                        <span class="text-xs font-bold text-indigo-600 bg-indigo-50 border border-indigo-100 px-2.5 py-1 rounded-lg" id="semesters-badge">
                            {{ $jumlah_semester }} Semester
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 font-semibold leading-relaxed" id="semesters-desc">
                        @if($selected_prodi_id)
                            @php
                                $selectedProdi = $prodis->firstWhere('id', $selected_prodi_id);
                            @endphp
                            @if($selectedProdi)
                                Masa studi otomatis disesuaikan dengan jenjang {{ $selectedProdi->jenjang }} ({{ $jumlah_semester }} Semester)
                            @endif
                        @else
                            Pilih program studi untuk menyesuaikan masa studi secara otomatis.
                        @endif
                    </p>
                </div>

                <!-- Checkboxes Beasiswa (Single-select Radio) -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-3">Beasiswa / Potongan yang Diajukan</label>
                    
                    @if($beasiswas->isEmpty())
                        <div class="p-4 rounded-xl bg-slate-50 text-slate-400 text-center text-xs">
                            Belum ada opsi beasiswa tersedia dari admin.
                        </div>
                    @else
                        <div class="space-y-2.5 max-h-60 overflow-y-auto pr-2">
                            <!-- Option: Tanpa Beasiswa -->
                            <label class="flex items-start p-3 rounded-xl border border-slate-200 hover:bg-slate-50/50 cursor-pointer transition-colors select-none">
                                <input type="radio" name="beasiswa_ids[]" value="" 
                                    {{ empty($selected_beasiswa_ids) || (count($selected_beasiswa_ids) === 1 && $selected_beasiswa_ids[0] === '') ? 'checked' : '' }}
                                    class="h-4.5 w-4.5 rounded-full border-slate-300 text-indigo-600 focus:ring-indigo-500 mt-0.5 mr-3 cursor-pointer">
                                <div>
                                    <span class="text-xs font-extrabold text-slate-800 tracking-wide block">
                                        Tanpa Beasiswa
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-semibold block mt-0.5">Tidak menggunakan potongan biaya kuliah</span>
                                </div>
                            </label>

                            @foreach($beasiswas as $beasiswa)
                                <label class="flex items-start p-3 rounded-xl border border-slate-200 hover:bg-slate-50/50 cursor-pointer transition-colors select-none">
                                    <input type="radio" name="beasiswa_ids[]" value="{{ $beasiswa->id }}" 
                                        {{ in_array($beasiswa->id, $selected_beasiswa_ids) ? 'checked' : '' }}
                                        class="h-4.5 w-4.5 rounded-full border-slate-300 text-indigo-600 focus:ring-indigo-500 mt-0.5 mr-3 cursor-pointer">
                                    <div>
                                        <span class="text-xs font-extrabold text-slate-800 tracking-wide block">
                                            {{ $beasiswa->nama_beasiswa }}
                                        </span>
                                        @if($beasiswa->rules->isEmpty())
                                            <span class="text-[10px] text-slate-400 font-semibold block mt-0.5">Tidak ada potongan</span>
                                        @else
                                            <div class="flex flex-wrap gap-1 mt-1">
                                                @foreach($beasiswa->rules as $rule)
                                                    <span class="text-[9px] font-bold uppercase px-1.5 py-0.5 rounded bg-indigo-50 border border-indigo-100/50 text-indigo-600">
                                                        {{ $rule->komponen_biaya }}: 
                                                        {{ $rule->jenis_potongan === 'persen' ? $rule->nilai_potongan.'%' : 'Rp '.number_format($rule->nilai_potongan, 0, ',', '.') }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @endif
                    <p class="text-xs text-slate-400 font-semibold mt-2">Pilih salah satu opsi beasiswa yang sesuai.</p>
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white rounded-xl font-bold text-sm shadow-md hover:shadow-lg transition-all duration-300 flex items-center justify-center gap-2 cursor-pointer">
                        <span>⚡</span> Hitung Estimasi Biaya
                    </button>
                </div>
            </form>
            </div> <!-- Closing form-container-biaya -->

            <!-- Container Form IPK -->
            <div id="form-container-ipk" class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs hidden">
                <h3 class="font-bold text-slate-900 text-lg mb-6 flex items-center gap-2 pb-3 border-b border-slate-100">
                    <span>📈</span> Parameter IPK
                </h3>
                
                <div class="space-y-6">
                    <!-- Mode Perhitungan Toggle -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Metode Perhitungan</label>
                        <div class="grid grid-cols-2 gap-1.5 p-1 bg-slate-100 rounded-xl border border-slate-200/50">
                            <button type="button" id="btn-mode-semester" onclick="switchIPKMode('semester')" class="py-2 px-3 rounded-lg text-[11px] font-bold text-center transition-all bg-white text-indigo-600 shadow-xs cursor-pointer">
                                📅 Rata-rata Semester
                            </button>
                            <button type="button" id="btn-mode-sks" onclick="switchIPKMode('sks')" class="py-2 px-3 rounded-lg text-[11px] font-bold text-center transition-all text-slate-500 hover:text-slate-800 cursor-pointer">
                                🎓 Detail Bobot SKS
                            </button>
                        </div>
                    </div>

                    <!-- Semester Mode Fields -->
                    <div id="ipk-mode-semester-fields" class="space-y-6">
                        <!-- Jenjang Dropdown -->
                        <div>
                            <label for="ipk_jenjang" class="block text-sm font-semibold text-slate-700 mb-2">Jenjang Studi</label>
                            <select id="ipk_jenjang" onchange="updateIPKSemesterOptions(); calculateIPKTarget();"
                                class="block w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white text-slate-900 font-medium text-sm transition-all outline-none">
                                <option value="D4_S1">D4 / S1 (8 Semester)</option>
                                <option value="D3">D3 (6 Semester)</option>
                            </select>
                        </div>

                        <!-- Semester Berjalan -->
                        <div>
                            <label for="ipk_semester" class="block text-sm font-semibold text-slate-700 mb-2">Semester Berjalan</label>
                            <select id="ipk_semester" onchange="calculateIPKTarget()"
                                class="block w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white text-slate-900 font-medium text-sm transition-all outline-none">
                                <!-- JS will populate options 1 to 7 -->
                            </select>
                            <p class="text-xs text-slate-400 font-semibold mt-1">Semester yang baru saja atau sedang Anda jalani.</p>
                        </div>
                    </div>

                    <!-- SKS Mode Fields -->
                    <div id="ipk-mode-sks-fields" class="space-y-6 hidden">
                        <!-- SKS Ditempuh -->
                        <div>
                            <label for="ipk_sks_sekarang" class="block text-sm font-semibold text-slate-700 mb-2">Total SKS Telah Ditempuh</label>
                            <input type="number" id="ipk_sks_sekarang" min="1" max="200" value="72" oninput="calculateIPKTarget()"
                                class="block w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white text-slate-900 font-medium text-sm transition-all outline-none">
                            <p class="text-xs text-slate-400 font-semibold mt-1">Jumlah SKS kumulatif yang nilainya sudah keluar.</p>
                        </div>

                        <!-- Total Target SKS Kelulusan -->
                        <div>
                            <label for="ipk_sks_target" class="block text-sm font-semibold text-slate-700 mb-2">Target SKS Kelulusan (Wisuda)</label>
                            <input type="number" id="ipk_sks_target" min="1" max="200" value="144" oninput="calculateIPKTarget()"
                                class="block w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white text-slate-900 font-medium text-sm transition-all outline-none">
                            <p class="text-xs text-slate-400 font-semibold mt-1">Biasanya D3 = 110-120 SKS, S1/D4 = 140-146 SKS.</p>
                        </div>
                    </div>

                    <!-- IPK Saat Ini -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label for="ipk_sekarang" class="text-sm font-semibold text-slate-700">IPK Saat Ini</label>
                            <span class="text-xs font-bold text-slate-500" id="val-ipk-sekarang">3.00</span>
                        </div>
                        <input type="range" id="ipk_sekarang" min="0.00" max="4.00" step="0.01" value="3.00" oninput="document.getElementById('val-ipk-sekarang').innerText = parseFloat(this.value).toFixed(2); calculateIPKTarget();"
                            class="w-full h-1.5 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-indigo-600">
                        <div class="flex justify-between text-[10px] text-slate-400 font-bold mt-1">
                            <span>0.00</span>
                            <span>2.00</span>
                            <span>3.00</span>
                            <span>4.00</span>
                        </div>
                    </div>

                    <!-- Target IPK Akhir -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label for="ipk_target" class="text-sm font-semibold text-slate-700">Target IPK Kelulusan (Wisuda)</label>
                            <span class="text-xs font-bold text-indigo-600" id="val-ipk-target">3.50</span>
                        </div>
                        <input type="range" id="ipk_target" min="0.00" max="4.00" step="0.01" value="3.50" oninput="document.getElementById('val-ipk-target').innerText = parseFloat(this.value).toFixed(2); calculateIPKTarget();"
                            class="w-full h-1.5 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-indigo-600">
                        <div class="flex justify-between text-[10px] text-slate-400 font-bold mt-1">
                            <span>0.00</span>
                            <span>2.00</span>
                            <span>3.00</span>
                            <span>4.00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Results Output (Takes 8 cols) -->
        <div class="lg:col-span-8 space-y-6">
            <!-- Container Result Biaya -->
            <div id="result-container-biaya" class="space-y-6">
                @if(!$result)
                <!-- Empty Placeholder -->
                <div class="bg-white p-8 sm:p-12 rounded-3xl border border-slate-200/80 shadow-xs text-center flex flex-col items-center justify-center min-h-[400px]">
                    <div class="w-20 h-20 rounded-2xl bg-indigo-50 flex items-center justify-center text-4xl mb-6 shadow-md shadow-indigo-50">
                        🧮
                    </div>
                    <h3 class="font-bold text-slate-900 text-xl mb-2">Belum Ada Hasil Simulasi</h3>
                    <p class="text-slate-500 font-medium text-sm max-w-md">
                        Silakan pilih Program Studi dan beasiswa di kolom sebelah kiri, lalu klik tombol <strong>Hitung Estimasi Biaya</strong> untuk melihat rincian biaya kuliah Anda.
                    </p>
                </div>
            @else
                <!-- Results Header Statistics -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-gradient-to-br from-slate-900 to-slate-800 p-5 rounded-2xl text-white shadow-md">
                        <span class="text-slate-400 text-[10px] font-extrabold uppercase tracking-wider block">Total Biaya Kotor (Sebelum Potongan)</span>
                        <span class="text-xl sm:text-2xl font-black block mt-2 text-slate-100">
                            Rp {{ number_format($result['grand_total_biaya_kotor'], 0, ',', '.') }}
                        </span>
                        <span class="text-[10px] text-slate-400 font-semibold block mt-1">Estimasi kotor selama masa studi</span>
                    </div>

                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex flex-col justify-between">
                        <div>
                            <span class="text-slate-400 text-[10px] font-extrabold uppercase tracking-wider block">Total Potongan (Beasiswa)</span>
                            <span class="text-xl sm:text-2xl font-black text-emerald-600 block mt-2">
                                - Rp {{ number_format($result['grand_total_potongan'], 0, ',', '.') }}
                            </span>
                        </div>
                        @if(!empty($result['beasiswas_applied']))
                            <div class="flex gap-1.5 flex-wrap mt-2">
                                @foreach($result['beasiswas_applied'] as $applied)
                                    <span class="text-[9px] font-bold bg-emerald-50 border border-emerald-100 text-emerald-700 px-1.5 py-0.5 rounded">
                                        {{ $applied }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-[10px] text-slate-400 font-semibold block mt-2">Tidak ada potongan beasiswa</span>
                        @endif
                    </div>
                </div>

                <!-- Net Cost Card -->
                <div class="bg-gradient-to-r from-indigo-600 to-violet-600 p-6 sm:p-8 rounded-3xl text-white shadow-xl shadow-indigo-100">
                    <span class="text-indigo-200 text-xs font-bold uppercase tracking-wider block">Total Estimasi Bersih Wajib Dibayar</span>
                    <span class="text-3xl sm:text-4xl font-extrabold block mt-2 tracking-tight">
                        Rp {{ number_format($result['grand_total_biaya_bersih'], 0, ',', '.') }}
                    </span>
                    <p class="text-indigo-100 text-xs font-medium mt-3 leading-relaxed">
                        Total biaya bersih di atas mencakup seluruh komponen wajib (seperti DPI, Seragam, Atribut, PKL, TA, Wisuda) dan UKT rutin dikalikan <strong>{{ $result['jumlah_semester'] }} semester</strong>.
                    </p>
                </div>

                <!-- Breakdown Invoice Table -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                    <div class="p-6 border-b border-slate-100">
                        <h4 class="font-bold text-slate-900 text-base">Rincian Komponen Biaya Kuliah</h4>
                        <p class="text-slate-400 text-xs mt-0.5 font-medium">Berdasarkan program studi {{ $result['prodi']->nama_prodi }}</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs font-semibold">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200/60 text-slate-500 uppercase tracking-wider font-bold">
                                    <th class="py-4 px-4 whitespace-nowrap">Nama Komponen</th>
                                    <th class="py-4 px-3 text-right whitespace-nowrap">Harga Satuan</th>
                                    <th class="py-4 px-2 text-center whitespace-nowrap">Jumlah / Kali</th>
                                    <th class="py-4 px-3 text-right whitespace-nowrap">Potongan Satuan</th>
                                    <th class="py-4 px-4 text-right whitespace-nowrap">Total Bersih</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-700">
                                @foreach($result['rincian'] as $key => $item)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="py-4 px-4">
                                            <span class="font-extrabold text-slate-900 block">{{ $item['nama'] }}</span>
                                            <span class="text-[10px] text-slate-400 font-medium block mt-0.5">{{ $item['deskripsi'] }}</span>
                                            
                                            <!-- Applied discounts for this item -->
                                            @if(!empty($item['beasiswa_applied']))
                                                <div class="mt-2 flex flex-col gap-1">
                                                    @foreach($item['beasiswa_applied'] as $applied)
                                                        <span class="text-[9px] text-slate-400 font-semibold block">
                                                            ↳ Potongan {{ $applied['nama'] }}: 
                                                            <strong class="text-emerald-600">
                                                                {{ $applied['jenis'] === 'persen' ? $applied['nilai'].'%' : 'Rp '.number_format($applied['nilai'], 0, ',', '.') }}
                                                            </strong>
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                        <td class="py-4 px-3 text-right text-slate-500 whitespace-nowrap">
                                            Rp {{ number_format($item['harga_satuan'], 0, ',', '.') }}
                                        </td>
                                        <td class="py-4 px-2 text-center text-slate-500 font-bold whitespace-nowrap">
                                            {{ $item['qty'] }}x
                                        </td>
                                        <td class="py-4 px-3 text-right text-rose-500 whitespace-nowrap">
                                            - Rp {{ number_format($item['total_potongan'] / $item['qty'], 0, ',', '.') }}
                                        </td>
                                        <td class="py-4 px-4 text-right font-extrabold text-slate-900 whitespace-nowrap">
                                            Rp {{ number_format($item['total_bersih'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Disclaimer info -->
                <div class="p-4 rounded-2xl bg-indigo-50 border border-indigo-100/50 text-indigo-800 text-[10px] font-semibold leading-relaxed">
                    <span class="font-bold block mb-1">📢 Catatan & Disclaimer:</span>
                    <span>1. Perhitungan ini merupakan simulasi perkiraan biaya kuliah berdasarkan nominal tarif master yang berlaku saat ini.</span><br>
                    <span>2. Potongan biaya bersifat akumulatif sesuai aturan beasiswa. Apabila total potongan melebihi biaya asli, biaya akhir komponen tersebut dihitung Rp 0,00 (tidak bernilai negatif).</span>
                </div>
            @endif
            </div> <!-- Closing result-container-biaya -->

            <!-- Container Result IPK -->
            <div id="result-container-ipk" class="space-y-6 hidden">
                <!-- IPK Results Output -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Left Result Widget: Required IPS -->
                    <div class="bg-gradient-to-br from-slate-900 to-slate-800 p-5 rounded-2xl text-white shadow-md flex flex-col justify-between">
                        <div>
                            <span class="text-slate-400 text-[10px] font-extrabold uppercase tracking-wider block" id="ipk-result-label">Target IPS Sisa Semester</span>
                            <div class="text-3xl sm:text-4xl font-black mt-2 tracking-tight text-white" id="ipk-result-ips">4.00</div>
                        </div>
                        <p class="text-[10px] text-slate-300 font-semibold mt-4 leading-relaxed" id="ipk-result-desc">
                            Rata-rata Indeks Prestasi Semester (IPS) minimal yang wajib Anda raih di setiap semester sisa.
                        </p>
                    </div>

                    <!-- Right Result Widget: Status / Kelayakan -->
                    <div class="p-5 rounded-2xl border flex flex-col justify-between" id="ipk-result-status-card">
                        <div>
                            <span class="text-slate-500 text-[10px] font-extrabold uppercase tracking-wider block font-semibold">Status Kelayakan Target</span>
                            <div class="text-lg font-extrabold mt-2 flex items-center gap-2" id="ipk-result-status-badge">
                                <span>🔴</span> Hampir Mustahil
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-600 font-semibold mt-4 leading-relaxed" id="ipk-result-status-desc">
                            Target IPK Anda memerlukan IPS rata-rata di atas 4.00 yang secara normal tidak dapat dicapai.
                        </p>
                    </div>
                </div>

                <!-- Maximum Achievable GPA Widget -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex justify-between items-center">
                    <div>
                        <h4 class="text-[10px] font-extrabold uppercase tracking-wider text-slate-500" id="ipk-max-label">Batas Maksimum IPK (Straight A's)</h4>
                        <p class="text-[10px] text-slate-400 font-semibold mt-1" id="ipk-max-desc">IPK tertinggi yang dapat Anda capai jika mendapat IPS 4.00 di semua sisa semester.</p>
                    </div>
                    <div class="text-2xl font-black text-slate-800 px-4 py-2 bg-slate-50 border border-slate-100 rounded-xl" id="ipk-result-max-gpa">
                        3.50
                    </div>
                </div>

                <!-- Simulation Scenario & Strategy -->
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-6">
                    <h3 class="font-bold text-slate-900 text-base flex items-center gap-2 pb-3 border-b border-slate-100">
                        <span>📋</span> Strategi & Analisis Akademis
                    </h3>

                    <!-- Scenarios Table -->
                    <div class="space-y-3">
                        <h4 class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Tabel Proyeksi Skenario Perbaikan Nilai</h4>
                        <div class="border border-slate-200/60 rounded-xl overflow-hidden">
                            <table class="w-full text-left border-collapse text-xs font-semibold">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider font-bold text-[10px]">
                                        <th class="py-3 px-4">Skenario</th>
                                        <th class="py-3 px-4 text-center" id="scenario-col-gain">Asumsi Kenaikan IPK Lama</th>
                                        <th class="py-3 px-4 text-right" id="scenario-col-target">Rata-rata Target IPS Baru</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-slate-700">
                                    <tr>
                                        <td class="py-3 px-4 font-bold">1. Tanpa Perbaikan Nilai</td>
                                        <td class="py-3 px-4 text-center text-slate-500 font-bold">-</td>
                                        <td class="py-3 px-4 text-right text-slate-900 font-extrabold" id="scenario-1-ips">4.00</td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 px-4 font-bold text-indigo-600">2. Mengulang Nilai C &rarr; A (+0.10)</td>
                                        <td class="py-3 px-4 text-center text-indigo-600 font-bold">+0.10 Poin IPK</td>
                                        <td class="py-3 px-4 text-right text-indigo-600 font-extrabold" id="scenario-2-ips">3.80</td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 px-4 font-bold text-emerald-600">3. Mengulang Nilai D/C &rarr; A (+0.20)</td>
                                        <td class="py-3 px-4 text-center text-emerald-600 font-bold">+0.20 Poin IPK</td>
                                        <td class="py-3 px-4 text-right text-emerald-600 font-extrabold" id="scenario-3-ips">3.60</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Strategy Advice Card -->
                    <div class="space-y-3">
                        <h4 class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Rekomendasi Langkah Nyata</h4>
                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/60 text-xs font-semibold text-slate-600 space-y-4 leading-relaxed" id="ipk-strategy-advice">
                            <!-- Custom HTML advice will be inserted here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
    function updateSemesterCount() {
        const select = document.getElementById('prodi_id');
        if (!select) return;
        
        const selectedOption = select.options[select.selectedIndex];
        if (!selectedOption || select.value === "") {
            const badge = document.getElementById('semesters-badge');
            if (badge) badge.innerText = '8 Semester';
            const desc = document.getElementById('semesters-desc');
            if (desc) desc.innerText = 'Pilih program studi untuk menyesuaikan masa studi secara otomatis.';
            return;
        }
        
        const jenjang = selectedOption.getAttribute('data-jenjang');
        let semesters = 8; // Default
        if (jenjang === 'D3') {
            semesters = 6;
        } else {
            semesters = 8; // S1 & D4
        }
        
        const badge = document.getElementById('semesters-badge');
        if (badge) {
            badge.innerText = semesters + ' Semester';
        }
        
        const descText = document.getElementById('semesters-desc');
        if (descText) {
            descText.innerText = `Masa studi otomatis disesuaikan dengan jenjang ${jenjang} (${semesters} Semester)`;
        }
    }

    // Tab switching function
    function switchTab(mode) {
        // Elements
        const btnBiaya = document.getElementById('btn-tab-biaya');
        const btnIpk = document.getElementById('btn-tab-ipk');
        const formBiaya = document.getElementById('form-container-biaya');
        const formIpk = document.getElementById('form-container-ipk');
        const resultBiaya = document.getElementById('result-container-biaya');
        const resultIpk = document.getElementById('result-container-ipk');
        
        // Hero texts
        const heroBadge = document.getElementById('hero-badge');
        const heroTitle = document.getElementById('hero-title');
        const heroDesc = document.getElementById('hero-desc');

        if (!btnBiaya || !btnIpk || !formBiaya || !formIpk || !resultIpk) return;

        if (mode === 'biaya') {
            // Update buttons styling
            btnBiaya.className = "flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 cursor-pointer bg-white text-indigo-600 shadow-xs";
            btnIpk.className = "flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 cursor-pointer text-slate-500 hover:text-slate-800";
            
            // Show/Hide forms & results
            formBiaya.classList.remove('hidden');
            formIpk.classList.add('hidden');
            if (resultBiaya) resultBiaya.classList.remove('hidden');
            resultIpk.classList.add('hidden');

            // Hero texts
            if (heroBadge) {
                heroBadge.innerHTML = "⚡ Simulasi Biaya Transparan";
                heroBadge.className = "inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 border border-indigo-100 text-indigo-700 uppercase tracking-wider";
            }
            if (heroTitle) {
                heroTitle.innerHTML = "Kalkulator Biaya Kuliah <span class='bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent font-extrabold'>Sampai Lulus</span>";
            }
            if (heroDesc) {
                heroDesc.innerText = "Hitung perkiraan total biaya kuliah Anda dari semester pertama hingga wisuda, disesuaikan dengan beasiswa potongan yang Anda dapatkan secara otomatis.";
            }
            
            // Update URL query parameter without reloading
            updateQueryStringParameter('tab', 'biaya');
        } else {
            // Update buttons styling
            btnBiaya.className = "flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 cursor-pointer text-slate-500 hover:text-slate-800";
            btnIpk.className = "flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 cursor-pointer bg-white text-indigo-600 shadow-xs";
            
            // Show/Hide forms & results
            formBiaya.classList.add('hidden');
            formIpk.classList.remove('hidden');
            if (resultBiaya) resultBiaya.classList.add('hidden');
            resultIpk.classList.remove('hidden');

            // Hero texts
            if (heroBadge) {
                heroBadge.innerHTML = "📈 Rencana Strategi Akademik";
                heroBadge.className = "inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 border border-emerald-100 text-emerald-700 uppercase tracking-wider";
            }
            if (heroTitle) {
                heroTitle.innerHTML = "Kalkulator Target <span class='bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent font-extrabold'>IPK Wisuda</span>";
            }
            if (heroDesc) {
                heroDesc.innerText = "Hitung target Indeks Prestasi Semester (IPS) yang harus Anda capai di sisa semester untuk mendapatkan IPK wisuda impian Anda.";
            }
            
            // Populate and calculate IPK on first load of this tab
            updateIPKSemesterOptions();
            calculateIPKTarget();

            // Update URL query parameter without reloading
            updateQueryStringParameter('tab', 'ipk');
        }
    }

    function updateQueryStringParameter(key, value) {
        const url = new URL(window.location.href);
        url.searchParams.set(key, value);
        window.history.replaceState({}, '', url.toString());
    }

    // IPK calculation modes
    let ipkMode = 'semester';

    function switchIPKMode(mode) {
        const btnSemester = document.getElementById('btn-mode-semester');
        const btnSks = document.getElementById('btn-mode-sks');
        const fieldsSemester = document.getElementById('ipk-mode-semester-fields');
        const fieldsSks = document.getElementById('ipk-mode-sks-fields');

        if (!btnSemester || !btnSks || !fieldsSemester || !fieldsSks) return;

        ipkMode = mode;

        if (mode === 'semester') {
            btnSemester.className = "py-2 px-3 rounded-lg text-[11px] font-bold text-center transition-all bg-white text-indigo-600 shadow-xs cursor-pointer";
            btnSks.className = "py-2 px-3 rounded-lg text-[11px] font-bold text-center transition-all text-slate-500 hover:text-slate-800 cursor-pointer";
            fieldsSemester.classList.remove('hidden');
            fieldsSks.classList.add('hidden');
        } else {
            btnSemester.className = "py-2 px-3 rounded-lg text-[11px] font-bold text-center transition-all text-slate-500 hover:text-slate-800 cursor-pointer";
            btnSks.className = "py-2 px-3 rounded-lg text-[11px] font-bold text-center transition-all bg-white text-indigo-600 shadow-xs cursor-pointer";
            fieldsSemester.classList.add('hidden');
            fieldsSks.classList.remove('hidden');
        }

        calculateIPKTarget();
    }

    // Populate semester options based on Jenjang
    function updateIPKSemesterOptions() {
        const ipkJenjang = document.getElementById('ipk_jenjang');
        const semSelect = document.getElementById('ipk_semester');
        if (!ipkJenjang || !semSelect) return;
        
        const jenjang = ipkJenjang.value;
        const prevVal = semSelect.value;
        
        const totalSemesters = (jenjang === 'D3') ? 6 : 8;
        
        semSelect.innerHTML = '';
        for (let i = 1; i < totalSemesters; i++) {
            const opt = document.createElement('option');
            opt.value = i;
            opt.innerText = `Semester ${i}`;
            semSelect.appendChild(opt);
        }
        
        // Restore value if within range
        if (prevVal && prevVal < totalSemesters) {
            semSelect.value = prevVal;
        } else {
            semSelect.value = Math.min(4, totalSemesters - 1); // Default to semester 4 for S1, or 3 for D3
        }
    }

    // IPK Target calculator logic
    function calculateIPKTarget() {
        const ipkSekarangEl = document.getElementById('ipk_sekarang');
        const ipkTargetEl = document.getElementById('ipk_target');
        
        const labelEl = document.getElementById('ipk-result-label');
        const descEl = document.getElementById('ipk-result-desc');
        const maxLabelEl = document.getElementById('ipk-max-label');
        const maxDescEl = document.getElementById('ipk-max-desc');
        const colGainEl = document.getElementById('scenario-col-gain');
        const colTargetEl = document.getElementById('scenario-col-target');

        if (!ipkSekarangEl || !ipkTargetEl) return;

        const ipkSekarang = parseFloat(ipkSekarangEl.value) || 0;
        const ipkTarget = parseFloat(ipkTargetEl.value) || 0;

        let targetIPS = 0;
        let maxIPKMungkin = 4.00;
        let remainingMeasureText = ""; 
        let requiredTitleText = "";
        let requiredDescText = "";
        let maxTitleText = "";
        let maxDescText = "";
        let colTargetText = "";

        // Calculations & UI labels depending on mode
        if (ipkMode === 'semester') {
            const ipkJenjang = document.getElementById('ipk_jenjang');
            const ipkSemester = document.getElementById('ipk_semester');
            if (!ipkJenjang || !ipkSemester) return;

            const jenjang = ipkJenjang.value;
            const semesterBerjalan = parseInt(ipkSemester.value) || 1;
            const totalSemester = (jenjang === 'D3') ? 6 : 8;
            const sisaSemester = totalSemester - semesterBerjalan;

            const totalPoinTarget = ipkTarget * totalSemester;
            const totalPoinSekarang = ipkSekarang * semesterBerjalan;
            const sisaPoinTarget = totalPoinTarget - totalPoinSekarang;
            
            targetIPS = sisaPoinTarget / sisaSemester;
            const maxPoinMungkin = totalPoinSekarang + (4.00 * sisaSemester);
            maxIPKMungkin = maxPoinMungkin / totalSemester;

            remainingMeasureText = `${sisaSemester} semester`;
            requiredTitleText = "Target IPS Sisa Semester";
            requiredDescText = "Rata-rata Indeks Prestasi Semester (IPS) minimal yang wajib Anda raih di setiap semester sisa.";
            maxTitleText = "Batas Maksimum IPK (Straight A's)";
            maxDescText = "IPK tertinggi yang dapat Anda capai jika mendapat IPS 4.00 di semua sisa semester.";
            colTargetText = "Rata-rata Target IPS Baru";
        } else {
            const sksSekarangEl = document.getElementById('ipk_sks_sekarang');
            const sksTargetEl = document.getElementById('ipk_sks_target');
            if (!sksSekarangEl || !sksTargetEl) return;

            const sksSekarang = parseInt(sksSekarangEl.value) || 0;
            const sksTarget = parseInt(sksTargetEl.value) || 1;
            const sisaSKS = sksTarget - sksSekarang;

            if (sisaSKS <= 0) {
                targetIPS = Infinity;
                maxIPKMungkin = ipkSekarang;
            } else {
                const totalPoinTarget = ipkTarget * sksTarget;
                const totalPoinSekarang = ipkSekarang * sksSekarang;
                const sisaPoinTarget = totalPoinTarget - totalPoinSekarang;

                targetIPS = sisaPoinTarget / sisaSKS;
                const maxPoinMungkin = totalPoinSekarang + (4.00 * sisaSKS);
                maxIPKMungkin = maxPoinMungkin / sksTarget;
            }

            remainingMeasureText = `${sisaSKS} SKS`;
            requiredTitleText = "Target IP Rata-rata Sisa SKS";
            requiredDescText = `Rata-rata Indeks Prestasi (IP) minimal yang wajib Anda raih untuk sisa ${sisaSKS} SKS.`;
            maxTitleText = "Batas Maksimum IPK (Straight A's)";
            maxDescText = `IPK tertinggi jika mendapat nilai A (4.00) di semua sisa ${sisaSKS} SKS.`;
            colTargetText = "Rata-rata IP Sisa Baru";
        }

        // Dynamically update labels
        if (labelEl) labelEl.innerText = requiredTitleText;
        if (descEl) descEl.innerText = requiredDescText;
        if (maxLabelEl) maxLabelEl.innerText = maxTitleText;
        if (maxDescEl) maxDescEl.innerText = maxDescText;
        if (colTargetEl) colTargetEl.innerText = colTargetText;

        // Update Max IPK display
        const maxGpaEl = document.getElementById('ipk-result-max-gpa');
        if (maxGpaEl) maxGpaEl.innerText = maxIPKMungkin.toFixed(2);
        
        // Display required IPS
        const ipsDisplay = document.getElementById('ipk-result-ips');
        
        // Scenarios
        const scenario1 = targetIPS;
        let scenario2 = 0;
        let scenario3 = 0;

        if (ipkMode === 'semester') {
            const ipkJenjang = document.getElementById('ipk_jenjang');
            const ipkSemester = document.getElementById('ipk_semester');
            if (ipkJenjang && ipkSemester) {
                const jenjang = ipkJenjang.value;
                const semesterBerjalan = parseInt(ipkSemester.value) || 1;
                const totalSemester = (jenjang === 'D3') ? 6 : 8;
                const sisaSemester = totalSemester - semesterBerjalan;

                const totalPoinTarget = ipkTarget * totalSemester;

                const ipkSekarangS2 = Math.min(4.00, ipkSekarang + 0.10);
                const totalPoinSekarangS2 = ipkSekarangS2 * semesterBerjalan;
                scenario2 = (totalPoinTarget - totalPoinSekarangS2) / sisaSemester;
                
                const ipkSekarangS3 = Math.min(4.00, ipkSekarang + 0.20);
                const totalPoinSekarangS3 = ipkSekarangS3 * semesterBerjalan;
                scenario3 = (totalPoinTarget - totalPoinSekarangS3) / sisaSemester;
            }
        } else {
            const sksSekarangEl = document.getElementById('ipk_sks_sekarang');
            const sksTargetEl = document.getElementById('ipk_sks_target');
            if (sksSekarangEl && sksTargetEl) {
                const sksSekarang = parseInt(sksSekarangEl.value) || 0;
                const sksTarget = parseInt(sksTargetEl.value) || 1;
                const sisaSKS = sksTarget - sksSekarang;

                if (sisaSKS > 0) {
                    const totalPoinTarget = ipkTarget * sksTarget;

                    const ipkSekarangS2 = Math.min(4.00, ipkSekarang + 0.10);
                    const totalPoinSekarangS2 = ipkSekarangS2 * sksSekarang;
                    scenario2 = (totalPoinTarget - totalPoinSekarangS2) / sisaSKS;

                    const ipkSekarangS3 = Math.min(4.00, ipkSekarang + 0.20);
                    const totalPoinSekarangS3 = ipkSekarangS3 * sksSekarang;
                    scenario3 = (totalPoinTarget - totalPoinSekarangS3) / sisaSKS;
                } else {
                    scenario2 = Infinity;
                    scenario3 = Infinity;
                }
            }
        }

        // Render scenario values
        renderScenario('scenario-1-ips', scenario1);
        renderScenario('scenario-2-ips', scenario2);
        renderScenario('scenario-3-ips', scenario3);

        // Status Card, Badge, and Advice text
        const statusCard = document.getElementById('ipk-result-status-card');
        const statusBadge = document.getElementById('ipk-result-status-badge');
        const statusDesc = document.getElementById('ipk-result-status-desc');
        const adviceContainer = document.getElementById('ipk-strategy-advice');

        if (!statusCard || !statusBadge || !statusDesc || !adviceContainer || !ipsDisplay) return;

        if (targetIPS === Infinity || isNaN(targetIPS)) {
            ipsDisplay.innerText = "N/A";
            statusCard.className = "p-5 rounded-2xl border border-rose-100 bg-rose-50/50 flex flex-col justify-between";
            statusBadge.innerHTML = "<span class='text-rose-600'>⚠️ Input Tidak Valid</span>";
            statusDesc.innerHTML = "SKS Ditempuh tidak boleh lebih besar atau sama dengan SKS Target Wisuda.";
            adviceContainer.innerHTML = "<p class='font-bold text-rose-800'>Silakan masukkan SKS Target Wisuda yang lebih besar dari SKS yang telah ditempuh.</p>";
        } else if (targetIPS > 4.00) {
            ipsDisplay.innerText = "N/A";
            statusCard.className = "p-5 rounded-2xl border border-rose-100 bg-rose-50/50 flex flex-col justify-between";
            statusBadge.innerHTML = "<span class='text-rose-600'>🔴 Hampir Mustahil</span>";
            statusDesc.innerHTML = `Beban target sisa tidak cukup untuk mencapai target IPK kelulusan <strong>${ipkTarget.toFixed(2)}</strong>. Anda membutuhkan rata-rata nilai sebesar <strong>${targetIPS.toFixed(2)}</strong> untuk ${remainingMeasureText} (melebihi batas maksimal 4.00).`;
            
            adviceContainer.innerHTML = `
                <div class="space-y-3">
                    <p class="font-bold text-rose-800">⚠️ Rekomendasi Solusi:</p>
                    <ul class="list-disc list-inside space-y-2 text-rose-700">
                        <li><strong>Strategi Utama: Mengulang Mata Kuliah Lama</strong>. Anda memiliki nilai C, D, atau E di masa lalu? Dengan mengulang beberapa mata kuliah tersebut menjadi nilai A, IPK berjalan Anda akan langsung melonjak sehingga menurunkan tekanan target sisa ke tingkat realistis.</li>
                        <li><strong>Gunakan Semester Pendek (SP):</strong> Hubungi bagian akademik kampus untuk mengetahui ketersediaan kelas pendek guna melakukan perbaikan nilai.</li>
                        <li><strong>Sesuaikan Target IPK:</strong> Pertimbangkan untuk menurunkan ekspektasi target IPK wisuda Anda ke batas maksimum yang memungkinkan saat ini, yaitu <strong>${maxIPKMungkin.toFixed(2)}</strong>.</li>
                    </ul>
                </div>
            `;
        } else if (targetIPS > 3.50) {
            ipsDisplay.innerText = targetIPS.toFixed(2);
            statusCard.className = "p-5 rounded-2xl border border-amber-100 bg-amber-50/50 flex flex-col justify-between";
            statusBadge.innerHTML = "<span class='text-amber-600'>🟡 Sangat Menantang</span>";
            statusDesc.innerHTML = `Dibutuhkan konsistensi akademik tingkat tinggi. Anda harus memperoleh rata-rata nilai minimal <strong>${targetIPS.toFixed(2)}</strong> untuk ${remainingMeasureText}.`;
            
            adviceContainer.innerHTML = `
                <div class="space-y-3">
                    <p class="font-bold text-amber-800">💡 Langkah Strategis:</p>
                    <ul class="list-disc list-inside space-y-2 text-amber-700">
                        <li><strong>Mengulang Mata Kuliah Terpilih:</strong> Prioritaskan mengulang mata kuliah berbobot SKS besar (misal 3 atau 4 SKS) yang mendapat nilai C. Kenaikan nilai satu mata kuliah ini saja akan meredakan beban target Anda secara signifikan.</li>
                        <li><strong>Fokus pada Mata Kuliah SKS Besar:</strong> Pastikan Anda memprioritaskan waktu belajar Anda pada mata kuliah dengan bobot SKS tinggi di masa yang akan datang.</li>
                        <li><strong>Semester Pendek:</strong> Gunakan kelas Semester Pendek khusus untuk memperbaiki nilai guna menstabilkan IPK.</li>
                    </ul>
                </div>
            `;
        } else {
            // Safe / realistic target
            const roundedIPS = Math.max(0.00, targetIPS);
            ipsDisplay.innerText = roundedIPS.toFixed(2);
            statusCard.className = "p-5 rounded-2xl border border-emerald-100 bg-emerald-50/50 flex flex-col justify-between";
            statusBadge.innerHTML = "<span class='text-emerald-600'>🟢 Sangat Realistis</span>";
            statusDesc.innerHTML = `Target IPK wisuda <strong>${ipkTarget.toFixed(2)}</strong> sangat mungkin dicapai secara normal dengan rata-rata nilai sisa sebesar <strong>${roundedIPS.toFixed(2)}</strong>.`;
            
            adviceContainer.innerHTML = `
                <div class="space-y-3">
                    <p class="font-bold text-emerald-800">✨ Rekomendasi Pemeliharaan:</p>
                    <ul class="list-disc list-inside space-y-2 text-emerald-700">
                        <li><strong>Jaga Konsistensi Belajar:</strong> Pertahankan pola belajar Anda saat ini dan hindari nilai di bawah B.</li>
                        <li><strong>Maksimalkan Nilai Tugas Akhir / Skripsi:</strong> Karena Skripsi dan PKL memiliki bobot SKS yang sangat besar (biasanya 4-6 SKS), pastikan Anda memperoleh nilai A. Ini akan menjadi pendorong akhir yang mengamankan kelulusan Anda dengan predikat terbaik.</li>
                    </ul>
                </div>
            `;
        }
    }

    function renderScenario(elementId, val) {
        const el = document.getElementById(elementId);
        if (!el) return;
        if (val > 4.00) {
            el.innerHTML = "<span class='text-rose-500 font-bold'>N/A</span>";
        } else {
            const roundedVal = Math.max(0.00, val);
            el.innerText = roundedVal.toFixed(2);
        }
    }

    // Run on load
    document.addEventListener('DOMContentLoaded', function() {
        updateSemesterCount();
        
        // Initial tab set from server
        const activeTab = "{{ $tab }}";
        switchTab(activeTab);
    });
</script>
@endsection
