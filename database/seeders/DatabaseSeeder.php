<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Prodi;
use App\Models\Beasiswa;
use App\Models\BeasiswaRule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Admin User
        User::updateOrCreate(
            ['email' => 'admin@kalkulator.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
            ]
        );

        // 2. Seed Default Prodis
        $prodis = [
            [
                'nama_prodi' => 'Teknologi Rekayasa Otomotif',
                'jenjang' => 'D4',
                'ukt' => 4500000,
                'dpi' => 10000000,
                'seragam' => 600000,
                'atribut' => 200000,
                'pkl' => 800000,
                'ta' => 1200000,
                'wisuda' => 1500000,
            ],
            [
                'nama_prodi' => 'Teknologi Rekayasa Perangkat Lunak',
                'jenjang' => 'D4',
                'ukt' => 4800000,
                'dpi' => 11000000,
                'seragam' => 500000,
                'atribut' => 200000,
                'pkl' => 750000,
                'ta' => 1200000,
                'wisuda' => 1500000,
            ],
            [
                'nama_prodi' => 'Produksi Media',
                'jenjang' => 'D4',
                'ukt' => 4200000,
                'dpi' => 9000000,
                'seragam' => 500000,
                'atribut' => 200000,
                'pkl' => 750000,
                'ta' => 1200000,
                'wisuda' => 1500000,
            ],
            [
                'nama_prodi' => 'Bisnis Manajemen Ritel',
                'jenjang' => 'D4',
                'ukt' => 4000000,
                'dpi' => 8000000,
                'seragam' => 500000,
                'atribut' => 200000,
                'pkl' => 600000,
                'ta' => 1000000,
                'wisuda' => 1500000,
            ],
            [
                'nama_prodi' => 'Akuntansi Perpajakan',
                'jenjang' => 'D4',
                'ukt' => 4100000,
                'dpi' => 8500000,
                'seragam' => 500000,
                'atribut' => 200000,
                'pkl' => 600000,
                'ta' => 1000000,
                'wisuda' => 1500000,
            ],
            [
                'nama_prodi' => 'Perhotelan',
                'jenjang' => 'D3',
                'ukt' => 3800000,
                'dpi' => 7500000,
                'seragam' => 800000,
                'atribut' => 200000,
                'pkl' => 1000000,
                'ta' => 1000000,
                'wisuda' => 1500000,
            ],
            [
                'nama_prodi' => 'Farmasi',
                'jenjang' => 'D3',
                'ukt' => 3900000,
                'dpi' => 8500000,
                'seragam' => 600000,
                'atribut' => 200000,
                'pkl' => 800000,
                'ta' => 1000000,
                'wisuda' => 1500000,
            ],
            [
                'nama_prodi' => 'Manajemen Informasi Kesehatan',
                'jenjang' => 'D4',
                'ukt' => 4300000,
                'dpi' => 9500000,
                'seragam' => 600000,
                'atribut' => 200000,
                'pkl' => 800000,
                'ta' => 1200000,
                'wisuda' => 1500000,
            ],
            [
                'nama_prodi' => 'Teknologi Laboratorium Medis',
                'jenjang' => 'D4',
                'ukt' => 4400000,
                'dpi' => 9800000,
                'seragam' => 600000,
                'atribut' => 200000,
                'pkl' => 800000,
                'ta' => 1200000,
                'wisuda' => 1500000,
            ],
            [
                'nama_prodi' => 'Informatika',
                'jenjang' => 'S1',
                'ukt' => 4500000,
                'dpi' => 10000000,
                'seragam' => 500000,
                'atribut' => 200000,
                'pkl' => 750000,
                'ta' => 1200000,
                'wisuda' => 1500000,
            ],
            [
                'nama_prodi' => 'PGSD',
                'jenjang' => 'S1',
                'ukt' => 3500000,
                'dpi' => 7000000,
                'seragam' => 450000,
                'atribut' => 200000,
                'pkl' => 500000,
                'ta' => 1000000,
                'wisuda' => 1500000,
            ],
            [
                'nama_prodi' => 'Pendidikan Jasmani',
                'jenjang' => 'S1',
                'ukt' => 3600000,
                'dpi' => 7200000,
                'seragam' => 500000,
                'atribut' => 200000,
                'pkl' => 500000,
                'ta' => 1000000,
                'wisuda' => 1500000,
            ],
            [
                'nama_prodi' => 'Fisioterapi',
                'jenjang' => 'S1',
                'ukt' => 4200000,
                'dpi' => 9000000,
                'seragam' => 600000,
                'atribut' => 200000,
                'pkl' => 750000,
                'ta' => 1200000,
                'wisuda' => 1500000,
            ],
            [
                'nama_prodi' => 'Kesehatan Lingkungan',
                'jenjang' => 'S1',
                'ukt' => 3800000,
                'dpi' => 8000000,
                'seragam' => 500000,
                'atribut' => 200000,
                'pkl' => 600000,
                'ta' => 1000000,
                'wisuda' => 1500000,
            ],
            [
                'nama_prodi' => 'Manajemen',
                'jenjang' => 'S1',
                'ukt' => 3700000,
                'dpi' => 7500000,
                'seragam' => 450000,
                'atribut' => 200000,
                'pkl' => 500000,
                'ta' => 1000000,
                'wisuda' => 1500000,
            ],
            [
                'nama_prodi' => 'Psikologi',
                'jenjang' => 'S1',
                'ukt' => 3800000,
                'dpi' => 8000000,
                'seragam' => 450000,
                'atribut' => 200000,
                'pkl' => 500000,
                'ta' => 1000000,
                'wisuda' => 1500000,
            ],
            [
                'nama_prodi' => 'Hukum',
                'jenjang' => 'S1',
                'ukt' => 3700000,
                'dpi' => 7500000,
                'seragam' => 450000,
                'atribut' => 200000,
                'pkl' => 500000,
                'ta' => 1000000,
                'wisuda' => 1500000,
            ],
            [
                'nama_prodi' => 'Kebidanan',
                'jenjang' => 'D3',
                'ukt' => 4000000,
                'dpi' => 8500000,
                'seragam' => 700000,
                'atribut' => 200000,
                'pkl' => 800000,
                'ta' => 1000000,
                'wisuda' => 1500000,
            ],
        ];

        foreach ($prodis as $prodiData) {
            Prodi::updateOrCreate(
                ['nama_prodi' => $prodiData['nama_prodi']],
                $prodiData
            );
        }

        // 3. Seed Default Beasiswas
        $beasiswas = [
            'HAFIDZ',
            'PRESTASI',
            'YATIM',
            'KIP',
            'DIFABEL',
            'BEASISWA BIAYA PENDIDIKAN'
        ];

        $beasiswaModels = [];
        foreach ($beasiswas as $nama) {
            $beasiswaModels[$nama] = Beasiswa::updateOrCreate(
                ['nama_beasiswa' => $nama]
            );
        }

        // 4. Seed Default Rules for Beasiswas
        // KIP: UKT persen 100, DPI persen 100
        $kip = $beasiswaModels['KIP'];
        BeasiswaRule::updateOrCreate(
            ['beasiswa_id' => $kip->id, 'komponen_biaya' => 'ukt'],
            ['jenis_potongan' => 'persen', 'nilai_potongan' => 100]
        );
        BeasiswaRule::updateOrCreate(
            ['beasiswa_id' => $kip->id, 'komponen_biaya' => 'dpi'],
            ['jenis_potongan' => 'persen', 'nilai_potongan' => 100]
        );

        // HAFIDZ: UKT persen 50
        $hafidz = $beasiswaModels['HAFIDZ'];
        BeasiswaRule::updateOrCreate(
            ['beasiswa_id' => $hafidz->id, 'komponen_biaya' => 'ukt'],
            ['jenis_potongan' => 'persen', 'nilai_potongan' => 50]
        );

        // PRESTASI: DPI nominal 2.500.000
        $prestasi = $beasiswaModels['PRESTASI'];
        BeasiswaRule::updateOrCreate(
            ['beasiswa_id' => $prestasi->id, 'komponen_biaya' => 'dpi'],
            ['jenis_potongan' => 'nominal', 'nilai_potongan' => 2500000]
        );

        // YATIM: UKT persen 30
        $yatim = $beasiswaModels['YATIM'];
        BeasiswaRule::updateOrCreate(
            ['beasiswa_id' => $yatim->id, 'komponen_biaya' => 'ukt'],
            ['jenis_potongan' => 'persen', 'nilai_potongan' => 30]
        );

        // DIFABEL: UKT persen 40
        $difabel = $beasiswaModels['DIFABEL'];
        BeasiswaRule::updateOrCreate(
            ['beasiswa_id' => $difabel->id, 'komponen_biaya' => 'ukt'],
            ['jenis_potongan' => 'persen', 'nilai_potongan' => 40]
        );

        // BEASISWA BIAYA PENDIDIKAN: Seragam persen 100, Atribut persen 100
        $biayaPend = $beasiswaModels['BEASISWA BIAYA PENDIDIKAN'];
        BeasiswaRule::updateOrCreate(
            ['beasiswa_id' => $biayaPend->id, 'komponen_biaya' => 'seragam'],
            ['jenis_potongan' => 'persen', 'nilai_potongan' => 100]
        );
        BeasiswaRule::updateOrCreate(
            ['beasiswa_id' => $biayaPend->id, 'komponen_biaya' => 'atribut'],
            ['jenis_potongan' => 'persen', 'nilai_potongan' => 100]
        );
    }
}
