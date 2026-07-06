<?php

namespace App\Http\Controllers;

use App\Models\Beasiswa;
use App\Models\BeasiswaRule;
use Illuminate\Http\Request;

class AdminBeasiswaController extends Controller
{
    /**
     * Display a listing of Beasiswas.
     */
    public function index()
    {
        $beasiswas = Beasiswa::withCount('rules')->orderBy('nama_beasiswa')->get();
        return view('admin.beasiswa.index', compact('beasiswas'));
    }

    /**
     * Store a newly created Beasiswa.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_beasiswa' => 'required|string|max:255|unique:beasiswas,nama_beasiswa',
        ], [
            'nama_beasiswa.unique' => 'Nama Beasiswa sudah terdaftar.',
        ]);

        $validated['nama_beasiswa'] = strtoupper($validated['nama_beasiswa']);

        Beasiswa::create($validated);

        return redirect()->route('admin.beasiswa.index')
            ->with('success', 'Beasiswa berhasil ditambahkan.');
    }

    /**
     * Update the specified Beasiswa.
     */
    public function update(Request $request, Beasiswa $beasiswa)
    {
        $validated = $request->validate([
            'nama_beasiswa' => 'required|string|max:255|unique:beasiswas,nama_beasiswa,' . $beasiswa->id,
        ], [
            'nama_beasiswa.unique' => 'Nama Beasiswa sudah terdaftar.',
        ]);

        $validated['nama_beasiswa'] = strtoupper($validated['nama_beasiswa']);

        $beasiswa->update($validated);

        return redirect()->route('admin.beasiswa.index')
            ->with('success', 'Nama Beasiswa berhasil diperbarui.');
    }

    /**
     * Remove the specified Beasiswa.
     */
    public function destroy(Beasiswa $beasiswa)
    {
        $beasiswa->delete();
        return redirect()->route('admin.beasiswa.index')
            ->with('success', 'Beasiswa berhasil dihapus.');
    }

    /**
     * Show the rules management page for a Beasiswa.
     */
    public function manageRules(Beasiswa $beasiswa)
    {
        $beasiswa->load('rules');
        $komponen_list = [
            'ukt' => 'UKT (Uang Kuliah Tunggal)',
            'dpi' => 'DPI (Dana Pengembangan Institusi)',
            'seragam' => 'Seragam',
            'atribut' => 'Atribut',
            'pkl' => 'PKL (Praktik Kerja Lapangan)',
            'ta' => 'TA (Tugas Akhir)',
            'wisuda' => 'Wisuda',
        ];
        return view('admin.beasiswa.rules', compact('beasiswa', 'komponen_list'));
    }

    /**
     * Store or update a rule for a Beasiswa.
     */
    public function storeRule(Request $request, Beasiswa $beasiswa)
    {
        $validated = $request->validate([
            'komponen_biaya' => 'required|string|in:ukt,dpi,seragam,atribut,pkl,ta,wisuda',
            'jenis_potongan' => 'required|string|in:persen,nominal',
            'nilai_potongan' => 'required|integer|min:0',
        ], [
            'nilai_potongan.min' => 'Nilai potongan tidak boleh kurang dari 0.',
            'nilai_potongan.integer' => 'Nilai potongan harus berupa angka.',
        ]);

        // Specific server-side check for percentage max 100%
        if ($validated['jenis_potongan'] === 'persen' && $validated['nilai_potongan'] > 100) {
            return back()->withErrors(['nilai_potongan' => 'Potongan persentase tidak boleh melebihi 100%.'])->withInput();
        }

        // Upsert rule: only one rule per cost component per scholarship
        BeasiswaRule::updateOrCreate(
            [
                'beasiswa_id' => $beasiswa->id,
                'komponen_biaya' => $validated['komponen_biaya'],
            ],
            [
                'jenis_potongan' => $validated['jenis_potongan'],
                'nilai_potongan' => $validated['nilai_potongan'],
            ]
        );

        return redirect()->route('admin.beasiswa.rules', $beasiswa->id)
            ->with('success', 'Aturan potongan berhasil disimpan.');
    }

    /**
     * Delete a rule.
     */
    public function destroyRule(Beasiswa $beasiswa, BeasiswaRule $rule)
    {
        $rule->delete();
        return redirect()->route('admin.beasiswa.rules', $beasiswa->id)
            ->with('success', 'Aturan potongan berhasil dihapus.');
    }
}
