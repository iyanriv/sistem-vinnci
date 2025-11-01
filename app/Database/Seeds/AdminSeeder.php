<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'username'      => 'adminvinnci',
            // Gunakan password_hash() untuk keamanan!
            // 'admin123' di hash untuk contoh:
            'password'      => password_hash('admin123', PASSWORD_DEFAULT), 
            'nama_lengkap'  => 'Admin Utama Vinnci',
        ];

        // Memasukkan data ke tabel tb_admin
        $this->db->table('tb_admin')->insert($data);
        
        // Data Kategori Material
        $kategori = [
            ['nama_kategori' => 'SYNTHETIC LEATHER', 'gambar_card' => 'synthetic.jpg'],
            ['nama_kategori' => 'MICROFIBER LEATHER', 'gambar_card' => 'microfiber.jpg'],
            ['nama_kategori' => 'PREMIUM MICROFIBER LEATHER', 'gambar_card' => 'premium_microfiber.jpg'],
            ['nama_kategori' => 'GENUINE LEATHER', 'gambar_card' => 'genuine.jpg'],
        ];
        $this->db->table('tb_kategori_material')->insertBatch($kategori);
        
        // Tambahkan perintah seeding untuk tb_produk, tb_varian_material, dan tb_konten_statis di file lain,
        // lalu panggil di DatabaseSeeder.php
    }
}
