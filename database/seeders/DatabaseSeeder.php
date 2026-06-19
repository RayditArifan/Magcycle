<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. provinsi
        $provinsi = [
            ['id_provinsi' => 1, 'nama_provinsi' => 'Jawa Timur'],
        ];
        foreach ($provinsi as $p) {
            DB::table('provinsi')->updateOrInsert(['id_provinsi' => $p['id_provinsi']], $p);
        }

        // 2. kab_kota
        $kabKota = [
            ['id_kota' => 1, 'nama_kab_kota' => 'Jember', 'id_provinsi' => 1],
            ['id_kota' => 2, 'nama_kab_kota' => 'Kediri', 'id_provinsi' => 1],
            ['id_kota' => 3, 'nama_kab_kota' => 'Malang', 'id_provinsi' => 1],
        ];
        foreach ($kabKota as $kk) {
            DB::table('kab_kota')->updateOrInsert(['id_kota' => $kk['id_kota']], $kk);
        }

        // 3. kecamatan
        $kecamatan = [
            ['id_kecamatan' => 1, 'nama_kecamatan' => 'Sumbersari', 'id_kota' => 1],
            ['id_kecamatan' => 2, 'nama_kecamatan' => 'Kaliwates', 'id_kota' => 1],
            ['id_kecamatan' => 3, 'nama_kecamatan' => 'Patrang', 'id_kota' => 1],
            ['id_kecamatan' => 4, 'nama_kecamatan' => 'Ajung', 'id_kota' => 1],
            ['id_kecamatan' => 5, 'nama_kecamatan' => 'Ambulu', 'id_kota' => 1],
            ['id_kecamatan' => 6, 'nama_kecamatan' => 'Mojoroto', 'id_kota' => 2],
            ['id_kecamatan' => 7, 'nama_kecamatan' => 'Kota Kediri', 'id_kota' => 2],
            ['id_kecamatan' => 8, 'nama_kecamatan' => 'Pesantren', 'id_kota' => 2],
            ['id_kecamatan' => 9, 'nama_kecamatan' => 'Pare', 'id_kota' => 2],
            ['id_kecamatan' => 10, 'nama_kecamatan' => 'Gurah', 'id_kota' => 2],
            ['id_kecamatan' => 11, 'nama_kecamatan' => 'Klojen', 'id_kota' => 3],
            ['id_kecamatan' => 12, 'nama_kecamatan' => 'Blimbing', 'id_kota' => 3],
            ['id_kecamatan' => 13, 'nama_kecamatan' => 'Lowokwaru', 'id_kota' => 3],
            ['id_kecamatan' => 14, 'nama_kecamatan' => 'Sukun', 'id_kota' => 3],
            ['id_kecamatan' => 15, 'nama_kecamatan' => 'Kedungkandang', 'id_kota' => 3],
        ];
        foreach ($kecamatan as $kec) {
            DB::table('kecamatan')->updateOrInsert(['id_kecamatan' => $kec['id_kecamatan']], $kec);
        }

        // 4. status_siklus
        $statusSiklus = [
            ['id' => 1, 'status_siklus' => 'telur'],
            ['id' => 2, 'status_siklus' => 'bayi larva'],
            ['id' => 3, 'status_siklus' => 'larva'],
            ['id' => 4, 'status_siklus' => 'selesai'],
        ];
        foreach ($statusSiklus as $ss) {
            DB::table('status_siklus')->updateOrInsert(['id' => $ss['id']], $ss);
        }

        // 5. status_pengambilan_sampah
        $statusPengambilan = [
            ['id' => 1, 'status_pengambilan' => 'menunggu konfirmasi'],
            ['id' => 2, 'status_pengambilan' => 'disetujui'],
            ['id' => 3, 'status_pengambilan' => 'ditolak'],
            ['id' => 4, 'status_pengambilan' => 'batal'],
            ['id' => 5, 'status_pengambilan' => 'selesai'],
        ];
        foreach ($statusPengambilan as $sp) {
            DB::table('status_pengambilan_sampah')->updateOrInsert(['id' => $sp['id']], $sp);
        }

        // 6. status_penukaran
        $statusPenukaran = [
            ['id' => 1, 'status_penukaran' => 'menunggu konfirmasi'],
            ['id' => 2, 'status_penukaran' => 'dikonfirmasi'],
            ['id' => 3, 'status_penukaran' => 'ditolak'],
        ];
        foreach ($statusPenukaran as $spn) {
            DB::table('status_penukaran')->updateOrInsert(['id' => $spn['id']], $spn);
        }

        // 7. status_akun
        $statusAkun = [
            ['id_status' => 'ST01', 'status_akun' => 'aktif'],
            ['id_status' => 'ST02', 'status_akun' => 'banned'],
        ];
        foreach ($statusAkun as $sa) {
            DB::table('status_akun')->updateOrInsert(['id_status' => $sa['id_status']], $sa);
        }

        // 8. e_wallet
        $eWallet = [
            ['id' => 1, 'nama_ewallet' => 'shopeepay'],
            ['id' => 2, 'nama_ewallet' => 'dana'],
            ['id' => 3, 'nama_ewallet' => 'ovo'],
            ['id' => 4, 'nama_ewallet' => 'gopay'],
        ];
        foreach ($eWallet as $ew) {
            DB::table('e_wallet')->updateOrInsert(['id' => $ew['id']], $ew);
        }

        // 9. konversi_poin
        $konversiPoin = [
            ['id' => 1, 'berat_sampah' => 1.00, 'konversi_poin' => 200],
            ['id' => 2, 'berat_sampah' => 5.00, 'konversi_poin' => 1500],
            ['id' => 3, 'berat_sampah' => 10.00, 'konversi_poin' => 4000],
            ['id' => 4, 'berat_sampah' => 20.00, 'konversi_poin' => 10000],
            ['id' => 7, 'berat_sampah' => 35.00, 'konversi_poin' => 20000],
        ];
        foreach ($konversiPoin as $kp) {
            DB::table('konversi_poin')->updateOrInsert(['id' => $kp['id']], $kp);
        }

        // 10. data_admin
        $dataAdmin = [
            [
                'id' => 1,
                'username' => 'Admin 1',
                'password' => 'admj321', // Plaintext sesuai mekanisme di AuthController
                'email' => 'ptwelltrshj@gmail.com',
                'no_hp' => '08987651321',
                'alamat' => 'Jl Sumatra no.5',
                'id_kecamatan' => 1,
            ]
        ];
        foreach ($dataAdmin as $da) {
            DB::table('data_admin')->updateOrInsert(['id' => $da['id']], $da);
        }

        // 11. stok_produk
        $stokProduk = [
            ['id_stok' => 1, 'jenis_produk' => 'Telur BSF', 'jumlah_stok' => 5, 'satuan' => 'kg', 'tanggal_input' => '2026-04-29'],
            ['id_stok' => 2, 'jenis_produk' => 'Maggot', 'jumlah_stok' => 6767, 'satuan' => 'kg', 'tanggal_input' => '2026-05-05'],
            ['id_stok' => 3, 'jenis_produk' => 'Kasgot', 'jumlah_stok' => 100.25, 'satuan' => 'kg', 'tanggal_input' => '2026-04-22'],
            ['id_stok' => 4, 'jenis_produk' => 'Telur BSF Batch 2', 'jumlah_stok' => 1000, 'satuan' => 'kg', 'tanggal_input' => '1951-01-22'],
            ['id_stok' => 5, 'jenis_produk' => 'Telur BSF Batch 3', 'jumlah_stok' => 69, 'satuan' => 'kg', 'tanggal_input' => '2026-05-04'],
            ['id_stok' => 6, 'jenis_produk' => 'Lalat Dewasa', 'jumlah_stok' => 100, 'satuan' => 'kg', 'tanggal_input' => '2026-05-02'],
            ['id_stok' => 7, 'jenis_produk' => 'Lalat Tidak Dewasa', 'jumlah_stok' => 6767, 'satuan' => 'kg', 'tanggal_input' => '2026-05-04'],
            ['id_stok' => 8, 'jenis_produk' => 'Lalat Dewasa Batch 2', 'jumlah_stok' => 1, 'satuan' => 'kg', 'tanggal_input' => '2026-05-04'],
            ['id_stok' => 9, 'jenis_produk' => 'Radhit', 'jumlah_stok' => 67, 'satuan' => 'kg', 'tanggal_input' => '2026-05-04'],
            ['id_stok' => 10, 'jenis_produk' => 'Telur bsf', 'jumlah_stok' => 10, 'satuan' => 'kg', 'tanggal_input' => '2026-05-05'],
            ['id_stok' => 11, 'jenis_produk' => 'Carmelia', 'jumlah_stok' => 1, 'satuan' => 'kg', 'tanggal_input' => '2026-05-05'],
            ['id_stok' => 12, 'jenis_produk' => 'fetgwgw', 'jumlah_stok' => 676767, 'satuan' => 'kg', 'tanggal_input' => '2026-05-06'],
            ['id_stok' => 13, 'jenis_produk' => 'Telur BSF Batch 3', 'jumlah_stok' => 78, 'satuan' => 'kg', 'tanggal_input' => '2026-05-06'],
        ];
        foreach ($stokProduk as $sp) {
            DB::table('stok_produk')->updateOrInsert(['id_stok' => $sp['id_stok']], $sp);
        }
    }
}
