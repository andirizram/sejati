<?php

namespace App;

class PermissionHelper
{
    const LANG_PERMISSIONS = [
        'dashboard' => 'Menampilkan halaman dashboard',
        'jadwal-saya' => 'Menampilan halaman saya',
        'jadwal-tabrakan' => 'Menampilkan halaman jadwal tabrakan',
        'jadwal.ambil' => 'Memberikan akses untuk mengambil jadwal',
        'jadwal.export' => 'Memberikan akses untuk menarik jadwal',
        'jadwal.show' => 'Menampilkan halaman jadwal pada sistem',
        'jadwal-ta.index' => 'Menampilkan halaman utama jadwal TA',
        'jadwal-ta.store' => 'Menyimpan/Mengunggah/Mengunduh jadwal TA',
        'jadwal-ta.show' => 'Menampilkan jadwal TA',
        'jadwal-ta.update' => 'Mengupdate jadwal TA',
        'jadwal-ta.destroy' => 'Menghapus jadwal TA',
        'jadwal-tpb.index' => 'Menampilkan halaman utama jadwal TPB',
        'jadwal-tpb.store' => 'Menyimpan/Mengunggah/Mengunduh jadwal TPB',
        'jadwal-tpb.show' => 'Menampilkan jadwal TPB',
        'jadwal-tpb.update' => 'Mengupdate jadwal TPB',
        'jadwal-tpb.destroy' => 'Menghapus jadwal TPB',
        'jadwal-prodi.index' => 'Menampilkan halaman utama jadwal prodi',
        'jadwal-prodi.store' => 'Menyimpan/Mengunggah/Mengunduh jadwal Prodi',
        'jadwal-prodi.show' => 'Menampilkan jadwal Prodi',
        'jadwal-prodi.update' => 'Mengupdate jadwal Prodi',
        'jadwal-prodi.destroy' => 'Menghapus jadwal Prodi',
        'jadwal-lain.index' => 'Menampilkan halaman utama jadwal Lainnya',
        'jadwal-lain.store' => 'Menyimpan/Mengunggah/Mengunduh jadwal Lainnya',
        'jadwal-lain.show' => 'Menampilkan jadwal Lainnya',
        'jadwal-lain.update' => 'Mengupdate jadwal Lainnya',
        'jadwal-lain.destroy' => 'Menghapus jadwal Lainnya',
        'unggah-jadwal' => 'Menampilkan halaman unggah jadwal',
        'unggah-jadwal.store' => 'Menyimpan hasil dari unggah jadwal',
        'pengelolaan-akun' => 'Menampilkan halaman utama pengelolaan akun',
        'perubahan-jadwal.index' => 'Menampilkan halaman utama pengajuan perubahan jadwal',
        'perubahan-jadwal.setuju' => 'Memberi akses untuk menyetujui perubahan jadwal',
        'perubahan-jadwal.tolak' => 'Memberi akses untuk menolak perubahan jadwal',
        'perubahan-jadwal.create' => 'Memberi akses untuk membuat perubahan jadwal',
        'perubahan-jadwal.store' => 'Memberi akses untuk menyimpan perubahan jadwal',
        'user.index' => 'Menampilkan halaman utama daftar pengguna',
        'user.store' => 'Menyimpan perubahan pada pengguna',
        'user.show' => 'Menampilkan daftar pengguna',
        'user.update' => 'Mengupdate detail pengguna pada sistem',
        'user.destroy' => 'Menghapus pengguna sistem',
        'role.index' => 'Menampilkan halaman utama daftar role',
        'role.store' => 'Menyimpan perubahan pada role',
        'role.show' => 'Menampilkan daftar role',
        'role.update' => 'Mengupdate detail role pada sistem',
        'role.destroy' => 'Menghapus role sistem',
        'pengaturan.index' => 'Menampilkan halaman utama pengaturan sistem',
        'pengaturan.update' => 'Mengupdate pengaturan sistem/Menghapus seluruh jadwal',
        'pengaturan.clear-data' => 'Akses untuk menghapus jadwal secara banyak',
        'profile.edit' => 'Akses untuk mengupdate semua detail jadwal kecuali Deskripsi Prodi',
        'profile.update' => 'Akses untuk mengupdate detail pada Jadwal TA dan Jadwal Lainnya',
        'profile.destroy' => '',
        'register' => '',
        'password.request' => '',
        'password.email' => '',
        'password.reset' => '',
        'password.store' => '',
        'password.confirm' => '',
        'password.edit' => 'Menampilkan halaman untuk mengubah password',
        'password.update' => 'Menyimpan perubahan password',
    ];

    public static function getLang(): array
    {
        return self::LANG_PERMISSIONS;
    }
}
