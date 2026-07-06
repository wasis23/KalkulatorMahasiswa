<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use Illuminate\Http\Request;

class AdminProdiController extends Controller
{
    /**
     * Display a listing of Prodis.
     */
    public function index()
    {
        $prodis = Prodi::orderBy('nama_prodi')->get();
        return view('admin.prodi.index', compact('prodis'));
    }

    /**
     * Show the form for creating a new Prodi.
     */
    public function create()
    {
        return view('admin.prodi.create');
    }

    /**
     * Store a newly created Prodi.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_prodi' => 'required|string|max:255|unique:prodis,nama_prodi',
            'jenjang' => 'required|string|in:S1,D4,D3',
            'ukt' => 'required|integer|min:0',
            'dpi' => 'required|integer|min:0',
            'seragam' => 'required|integer|min:0',
            'atribut' => 'required|integer|min:0',
            'pkl' => 'required|integer|min:0',
            'ta' => 'required|integer|min:0',
            'wisuda' => 'required|integer|min:0',
        ], [
            'nama_prodi.unique' => 'Nama Program Studi sudah terdaftar.',
            '*.min' => 'Nominal biaya tidak boleh kurang dari 0.',
            '*.integer' => 'Nominal biaya harus berupa angka bulat positif.',
        ]);

        Prodi::create($validated);

        return redirect()->route('admin.prodi.index')
            ->with('success', 'Program Studi berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified Prodi.
     */
    public function edit(Prodi $prodi)
    {
        return view('admin.prodi.edit', compact('prodi'));
    }

    /**
     * Update the specified Prodi.
     */
    public function update(Request $request, Prodi $prodi)
    {
        $validated = $request->validate([
            'nama_prodi' => 'required|string|max:255|unique:prodis,nama_prodi,' . $prodi->id,
            'jenjang' => 'required|string|in:S1,D4,D3',
            'ukt' => 'required|integer|min:0',
            'dpi' => 'required|integer|min:0',
            'seragam' => 'required|integer|min:0',
            'atribut' => 'required|integer|min:0',
            'pkl' => 'required|integer|min:0',
            'ta' => 'required|integer|min:0',
            'wisuda' => 'required|integer|min:0',
        ], [
            'nama_prodi.unique' => 'Nama Program Studi sudah terdaftar.',
            '*.min' => 'Nominal biaya tidak boleh kurang dari 0.',
            '*.integer' => 'Nominal biaya harus berupa angka bulat positif.',
        ]);

        $prodi->update($validated);

        return redirect()->route('admin.prodi.index')
            ->with('success', 'Program Studi berhasil diperbarui.');
    }

    /**
     * Remove the specified Prodi.
     */
    public function destroy(Prodi $prodi)
    {
        $prodi->delete();
        return redirect()->route('admin.prodi.index')
            ->with('success', 'Program Studi berhasil dihapus.');
    }
}
