@extends('layouts.app')

@section('title', 'Manajemen Beasiswa - Panel Admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Manajemen Beasiswa & Aturan Potongan</h1>
            <p class="text-slate-500 text-sm font-medium">Tambah beasiswa baru dan kelola aturan potongan biaya kuliah.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="self-start px-4 py-2 rounded-xl text-sm font-semibold border border-slate-200 text-slate-600 bg-white hover:bg-slate-50 transition-colors">
            &larr; Dashboard
        </a>
    </div>

    <!-- Main Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: List of Scholarships (Takes 2 cols on large screen) -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-xs p-6">
            <h3 class="font-bold text-slate-900 text-lg mb-4 flex items-center gap-2">
                <span>🎟️</span> Daftar Beasiswa Terdaftar
            </h3>

            @if($beasiswas->isEmpty())
                <div class="py-16 text-center text-slate-400">
                    <span class="text-5xl block mb-2">🎁</span>
                    <p class="font-medium text-sm">Belum ada beasiswa yang terdaftar.</p>
                </div>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($beasiswas as $beasiswa)
                        <div class="py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <span class="font-extrabold text-slate-900 tracking-wide text-sm bg-slate-100 border border-slate-200 px-2.5 py-1 rounded-lg">
                                    {{ $beasiswa->nama_beasiswa }}
                                </span>
                                <span class="text-xs text-slate-500 font-semibold block mt-2">
                                    ⚙️ {{ $beasiswa->rules_count }} aturan potongan dikonfigurasi
                                </span>
                            </div>

                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.beasiswa.rules', $beasiswa->id) }}" class="px-3.5 py-2 rounded-xl text-xs font-bold bg-violet-50 text-violet-600 hover:bg-violet-100 transition-colors">
                                    🛠️ Kelola Aturan Potongan
                                </a>

                                <!-- Edit Form Trigger / Form directly -->
                                <button onclick="toggleEdit('{{ $beasiswa->id }}', '{{ $beasiswa->nama_beasiswa }}')" class="px-3.5 py-2 rounded-xl text-xs font-semibold bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-colors cursor-pointer">
                                    ✏️ Edit
                                </button>

                                <form action="{{ route('admin.beasiswa.destroy', $beasiswa->id) }}" method="POST" class="inline" data-confirm="Apakah Anda yakin ingin menghapus beasiswa ini? Semua aturan potongan yang terkait dengannya juga akan dihapus.">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3.5 py-2 rounded-xl text-xs font-semibold bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors cursor-pointer">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Hidden inline edit form -->
                        <div id="edit-box-{{ $beasiswa->id }}" class="hidden py-4 px-4 bg-slate-50 rounded-xl border border-slate-100 mt-2 mb-4">
                            <form action="{{ route('admin.beasiswa.update', $beasiswa->id) }}" method="POST" class="flex flex-col sm:flex-row items-end gap-3">
                                @csrf
                                @method('PUT')
                                <div class="flex-1">
                                    <label class="block text-xs font-semibold text-slate-500 mb-1">Nama Beasiswa Baru</label>
                                    <input type="text" name="nama_beasiswa" id="input-{{ $beasiswa->id }}" required
                                        class="block w-full px-3 py-2 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white text-slate-900 font-semibold text-xs transition-all outline-none">
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="submit" class="px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors cursor-pointer">
                                        Simpan
                                    </button>
                                    <button type="button" onclick="toggleEdit('{{ $beasiswa->id }}')" class="px-4 py-2.5 rounded-xl text-xs font-bold border border-slate-200 text-slate-600 bg-white hover:bg-slate-50 transition-colors cursor-pointer">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Right: Add Scholarship Form (Takes 1 col) -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 h-fit">
            <h3 class="font-bold text-slate-900 text-lg mb-4 flex items-center gap-2">
                <span>➕</span> Tambah Beasiswa
            </h3>
            
            <form action="{{ route('admin.beasiswa.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label for="nama_beasiswa" class="block text-sm font-semibold text-slate-700 mb-2">Nama Beasiswa</label>
                    <input type="text" name="nama_beasiswa" id="nama_beasiswa" required value="{{ old('nama_beasiswa') }}"
                        class="block w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white text-slate-900 placeholder-slate-400 font-semibold text-sm transition-all outline-none"
                        placeholder="Contoh: KIP, YATIM, HAFIDZ">
                    <span class="text-slate-400 text-xs mt-1 block">Nama beasiswa akan otomatis disimpan dalam huruf besar (Caps).</span>
                </div>

                <button type="submit" class="w-full py-3 px-4 rounded-xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-100 hover:shadow-lg transition-all cursor-pointer">
                    Simpan Beasiswa
                </button>
            </form>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleEdit(id, name = '') {
        const box = document.getElementById(`edit-box-${id}`);
        const input = document.getElementById(`input-${id}`);
        
        if (box.classList.contains('hidden')) {
            box.classList.remove('hidden');
            input.value = name;
            input.focus();
        } else {
            box.classList.add('hidden');
        }
    }
</script>
@endsection
