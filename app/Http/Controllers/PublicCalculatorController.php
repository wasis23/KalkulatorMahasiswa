<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use App\Models\Beasiswa;
use App\Models\BeasiswaRule;
use Illuminate\Http\Request;

class PublicCalculatorController extends Controller
{
    /**
     * Show the public calculator form and results.
     */
    public function index(Request $request)
    {
        $prodis = Prodi::orderBy('nama_prodi')->get();
        $beasiswas = Beasiswa::with('rules')->orderBy('nama_beasiswa')->get();

        $selected_prodi_id = $request->input('prodi_id');
        $selected_beasiswa_ids = $request->input('beasiswa_ids', []);
        $jumlah_semester = 8; // Default

        $result = null;

        if ($selected_prodi_id) {
            $prodi = Prodi::find($selected_prodi_id);

            if ($prodi) {
                // Automatically set study duration based on level (jenjang)
                $jumlah_semester = ($prodi->jenjang === 'D3') ? 6 : 8;

                // Fetch selected scholarships and their rules
                $chosenBeasiswas = Beasiswa::whereIn('id', $selected_beasiswa_ids)
                    ->with('rules')
                    ->get();

                // Define the 7 components
                $components = [
                    'ukt' => [
                        'nama' => 'UKT (Uang Kuliah Tunggal)',
                        'deskripsi' => 'Biaya kuliah per semester',
                        'is_semester' => true,
                        'harga_satuan' => $prodi->ukt,
                    ],
                    'dpi' => [
                        'nama' => 'DPI (Dana Pengembangan Institusi)',
                        'deskripsi' => 'Uang gedung / uang pangkal (sekali bayar)',
                        'is_semester' => false,
                        'harga_satuan' => $prodi->dpi,
                    ],
                    'seragam' => [
                        'nama' => 'Seragam',
                        'deskripsi' => 'Biaya seragam perkuliahan (sekali bayar)',
                        'is_semester' => false,
                        'harga_satuan' => $prodi->seragam,
                    ],
                    'atribut' => [
                        'nama' => 'Atribut Mahasiswa',
                        'deskripsi' => 'Almamater, KTM, dan perlengkapan (sekali bayar)',
                        'is_semester' => false,
                        'harga_satuan' => $prodi->atribut,
                    ],
                    'pkl' => [
                        'nama' => 'PKL (Praktik Kerja Lapangan)',
                        'deskripsi' => 'Biaya pelaksanaan PKL/magang (sekali bayar)',
                        'is_semester' => false,
                        'harga_satuan' => $prodi->pkl,
                    ],
                    'ta' => [
                        'nama' => 'TA (Tugas Akhir / Skripsi)',
                        'deskripsi' => 'Bimbingan dan sidang skripsi (sekali bayar)',
                        'is_semester' => false,
                        'harga_satuan' => $prodi->ta,
                    ],
                    'wisuda' => [
                        'nama' => 'Wisuda',
                        'deskripsi' => 'Pelaksanaan wisuda (sekali bayar)',
                        'is_semester' => false,
                        'harga_satuan' => $prodi->wisuda,
                    ],
                ];

                $rincian = [];
                $grand_total_biaya_kotor = 0;
                $grand_total_potongan = 0;
                $grand_total_biaya_bersih = 0;

                foreach ($components as $key => $comp) {
                    $harga_satuan = $comp['harga_satuan'];
                    $qty = $comp['is_semester'] ? $jumlah_semester : 1;
                    $total_kotor = $harga_satuan * $qty;

                    // 1. Gather all rules targeting this component
                    $persen_potongan_total = 0;
                    $nominal_potongan_total = 0;
                    $beasiswa_applied = [];

                    foreach ($chosenBeasiswas as $beasiswa) {
                        $rule = $beasiswa->rules->firstWhere('komponen_biaya', $key);
                        if ($rule) {
                            $beasiswa_applied[] = [
                                'nama' => $beasiswa->nama_beasiswa,
                                'jenis' => $rule->jenis_potongan,
                                'nilai' => $rule->nilai_potongan,
                            ];

                            if ($rule->jenis_potongan === 'persen') {
                                $persen_potongan_total += $rule->nilai_potongan;
                            } else {
                                $nominal_potongan_total += $rule->nilai_potongan;
                            }
                        }
                    }

                    // 2. Calculate discounts
                    // If percentage total exceeds 100%, cap at 100%
                    if ($persen_potongan_total > 100) {
                        $persen_potongan_total = 100;
                    }

                    // Discount is applied to the original cost.
                    // For semesterly costs (UKT), does the scholarship apply to every semester? Yes, UKT discounts apply per semester.
                    $potongan_persen = $harga_satuan * ($persen_potongan_total / 100);
                    $potongan_nominal = $nominal_potongan_total;
                    
                    $potongan_satuan_total = $potongan_persen + $potongan_nominal;
                    
                    // Anti-minus logic on unit cost:
                    $net_satuan = $harga_satuan - $potongan_satuan_total;
                    if ($net_satuan < 0) {
                        $net_satuan = 0;
                        $potongan_satuan_total = $harga_satuan; // capped discount
                    }

                    $total_potongan_item = $potongan_satuan_total * $qty;
                    $total_bersih_item = $net_satuan * $qty;

                    $rincian[$key] = [
                        'nama' => $comp['nama'],
                        'deskripsi' => $comp['deskripsi'],
                        'is_semester' => $comp['is_semester'],
                        'harga_satuan' => $harga_satuan,
                        'qty' => $qty,
                        'total_kotor' => $total_kotor,
                        'beasiswa_applied' => $beasiswa_applied,
                        'total_potongan' => $total_potongan_item,
                        'total_bersih' => $total_bersih_item,
                    ];

                    $grand_total_biaya_kotor += $total_kotor;
                    $grand_total_potongan += $total_potongan_item;
                    $grand_total_biaya_bersih += $total_bersih_item;
                }

                $result = [
                    'prodi' => $prodi,
                    'jumlah_semester' => $jumlah_semester,
                    'rincian' => $rincian,
                    'grand_total_biaya_kotor' => $grand_total_biaya_kotor,
                    'grand_total_potongan' => $grand_total_potongan,
                    'grand_total_biaya_bersih' => $grand_total_biaya_bersih,
                    'beasiswas_applied' => $chosenBeasiswas->pluck('nama_beasiswa')->toArray(),
                ];
            }
        }

        $tab = $request->input('tab', 'biaya');

        return view('welcome', compact('prodis', 'beasiswas', 'selected_prodi_id', 'selected_beasiswa_ids', 'jumlah_semester', 'result', 'tab'));
    }
}
