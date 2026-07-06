# Panduan Pengembangan Aplikasi Kalkulator Biaya dan Beasiswa Mahasiswa

Dokumen ini berisi spesifikasi kebutuhan sistem, aturan bisnis, dan instruksi teknis untuk membangun aplikasi kalkulator biaya perkuliahan berbasis Laravel. Panduan ini dirancang agar aplikasi yang dihasilkan memenuhi standar profesional, memiliki arsitektur kode yang bersih, serta memberikan pengalaman pengguna (UX) yang optimal.

---

## 1. Konsep Sistem dan Alur Kerja Utama

Aplikasi ini dirancang dengan arsitektur dua sisi, yaitu antarmuka publik untuk pengguna (mahasiswa) dan panel kendali khusus untuk administrator. 

Sistem bekerja dengan cara mencocokkan pilihan Program Studi (Prodi) mahasiswa dengan master data biaya perkuliahan yang berlaku. Ketika mahasiswa memilih satu atau beberapa opsi beasiswa, sistem secara dinamis akan memotong komponen biaya tertentu berdasarkan aturan persentase atau nominal yang telah dikonfigurasi oleh admin. Hasil akhir yang disajikan kepada mahasiswa adalah rincian transparan mengenai biaya awal, jumlah potongan, dan sisa biaya bersih yang wajib dibayarkan selama masa studi.

---

## 2. Aturan Bisnis dan Parameter Input

Untuk memastikan keakuratan perhitungan, sistem wajib memetakan seluruh variabel input sesuai dengan struktur di bawah ini:

### Komponen Biaya Wajib (Sisi Input Admin & Output User)
Setiap Program Studi harus memiliki konfigurasi nominal untuk 7 komponen biaya utama berikut:
1. **Nama Prodi**: Identitas program studi sebagai pengikat utama data biaya.
2. **UKT (Uang Kuliah Tunggal)**: Biaya rutin per semester.
3. **DPI (Dana Pengembangan Institusi)**: Biaya gedung atau uang pangkal.
4. **Seragam**: Biaya pengadaan pakaian seragam mahasiswa.
5. **Atribut**: Biaya perlengkapan penunjang (jas almamater, KTM, dll).
6. **PKL (Praktik Kerja Lapangan)**: Biaya pelaksanaan magang atau praktik industri.
7. **TA (Tugas Akhir)**: Biaya bimbingan dan ujian skripsi/tugas akhir.
8. **Wisuda**: Biaya pelaksanaan upacara kelulusan.

### Entitas Beasiswa Default
Sistem harus menyediakan atau mengizinkan admin menambah jenis beasiswa. Sebagai data awal, sistem wajib menyediakan opsi berikut:
* HAFIDZ
* PRESTASI
* YATIM
* KIP
* DIFABEL
* BEASISWA BIAYA PENDIDIKAN

---

## 3. Instruksi Fitur dan Hak Akses Antarmuka

### Hak Akses dan Fitur Halaman Admin
Halaman admin adalah pusat kendali aplikasi yang harus dilindungi oleh sistem otentikasi (login) yang aman. Fitur wajib di sisi admin meliputi:
* **Manajemen Biaya Prodi**: Admin dapat menginput, memperbarui, dan menghapus nominal dari ke-7 komponen biaya untuk setiap Program Studi baru maupun lama.
* **Manajemen Beasiswa Fleksibel**: Admin dapat menambah jenis beasiswa baru di luar daftar default yang sudah ada.
* **Mesin Pengatur Aturan (Rule Engine)**: Admin harus bisa memilih beasiswa tertentu, kemudian menentukan komponen biaya apa saja yang digratiskan atau dipotong. Aturan ini harus mendukung dua jenis potongan, yaitu berbentuk **Persentase (%)** (contoh: KIP gratis UKT 100%) atau berbentuk **Nominal (Rp)** (contoh: Beasiswa Prestasi memotong DPI sebesar Rp 2.000.000).

### Hak Akses dan Fitur Halaman User (Mahasiswa)
Halaman user dirancang tanpa memerlukan login (akses publik) agar memudahkan calon mahasiswa melakukan simulasi. Fitur wajib di sisi user meliputi:
* **Formulir Interaktif**: Mahasiswa disajikan pilihan *dropdown* Program Studi dan daftar *checkbox* berisi beasiswa yang ingin mereka ajukan.
* **Sistem Pilihan Ganda Beasiswa (Multi-select)**: Mahasiswa dapat mencentang lebih dari satu beasiswa (misal: Yatim sekaligus Hafidz) jika peraturan kampus mengizinkan akumulasi potongan.
* **Halaman Hasil (Invoice Simulation)**: Menampilkan tabel rincian per komponen biaya, jumlah potongan yang didapatkan, dan total akhir yang harus dibayarkan secara transparan.

---

## 4. Instruksi Teknis untuk Menjamin Website Profesional

Agar aplikasi ini memiliki standar industri dan profesional, pengembang wajib menerapkan instruksi teknis berikut dalam *framework* Laravel:

* **Penerapan Relasi Database yang Tepat**: Gunakan relasi *Many-to-Many* antara tabel Beasiswa dan Tabel Komponen Biaya menggunakan tabel *pivot* (misal: `beasiswa_rules`). Hal ini penting agar admin bisa dengan bebas mengatur satu beasiswa memotong banyak komponen biaya tanpa merusak struktur database.
* **Validasi Sisi Server (Server-Side Validation)**: Semua inputan admin terkait nominal uang wajib divalidasi menggunakan tipe data numerik positif. Pastikan persentase potongan tidak boleh melebihi 100% dan nilai nominal potongan tidak boleh melebihi harga asli biaya tersebut.
* **Logika Batas Bawah (Anti-Minus)**: Di dalam Controller Laravel, buatlah logika matematika yang mengunci angka terendah adalah nol ($0$). Jika seorang mahasiswa mendapatkan akumulasi beberapa beasiswa yang total potongannya melebihi biaya asli, sistem harus otomatis mengonversi total biaya akhir komponen tersebut menjadi Rp 0, bukan menjadi angka minus.
* **Keamanan Data**: Lindungi seluruh rute (`routes`) halaman admin menggunakan *Middleware Auth*. Gunakan *token* CSRF pada setiap formulir input untuk mencegah serangan siber.
* **Penyajian Data (Localization & UX)**: Format seluruh angka mata uang di halaman user menggunakan standar Rupiah (contoh: `Rp 5.000.000,00`) menggunakan *helper* atau *library* PHP/Laravel. Gunakan transisi visual yang halus (seperti AJAX atau visualisasi tabel yang rapi) saat menampilkan hasil perhitungan agar website terlihat modern dan kredibel.