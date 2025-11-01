<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProdukSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama_merk' => 'LUGANO',
                'deskripsi' => 'Premium Italian leather with superior durability and comfort',
                'harga' => 2500000.00,
                'stok' => 50,
            ],
            [
                'nama_merk' => 'Gallardo',
                'deskripsi' => 'Luxury leather with handcrafted finish for elite automotive interiors',
                'harga' => 3200000.00,
                'stok' => 30,
            ],
            [
                'nama_merk' => 'Ferrari',
                'deskripsi' => 'High-performance leather designed for sports car applications',
                'harga' => 4500000.00,
                'stok' => 20,
            ],
            [
                'nama_merk' => 'Lamborghini',
                'deskripsi' => 'Exclusive leather with unique texture and premium quality',
                'harga' => 5500000.00,
                'stok' => 15,
            ],
            [
                'nama_merk' => 'Basic Premium',
                'deskripsi' => 'Standard premium leather option with 3 year warranty',
                'harga' => 1800000.00,
                'stok' => 100,
            ],
        ];

        // Insert data to table
        $this->db->table('tb_produk')->insertBatch($data);
    }
}