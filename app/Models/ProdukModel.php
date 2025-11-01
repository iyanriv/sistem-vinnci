<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdukModel extends Model
{
    protected $table = 'tb_produk';
    protected $primaryKey = 'id_produk';
    protected $allowedFields = ['nama_merk', 'deskripsi', 'harga', 'stok'];
    protected $useTimestamps = true;
    
    public function getProducts()
    {
        // Check if data is already cached
        $cache = \Config\Services::cache();
        $products = $cache->get('products_list');
        
        if ($products === null) {
            // Data not in cache, fetch from database
            $products = $this->findAll();
            
            // Save to cache for 5 minutes
            $cache->save('products_list', $products, 300);
        }
        
        return $products;
    }
    
    public function getLeatherMaterials()
    {
        // Mengambil hanya material kulit khusus (LUGANO, Gallardo, Ferrari, Lamborghini)
        $leatherMaterials = [
            [
                'id_produk' => 1,
                'nama_merk' => 'LUGANO',
                'deskripsi' => 'Premium Italian leather with superior durability and comfort',
                'gambar' => 'lugano.jpg'
            ],
            [
                'id_produk' => 2,
                'nama_merk' => 'Gallardo',
                'deskripsi' => 'Luxury leather with handcrafted finish for elite automotive interiors',
                'gambar' => 'gallardo.jpg'
            ],
            [
                'id_produk' => 3,
                'nama_merk' => 'Ferrari',
                'deskripsi' => 'High-performance leather designed for sports car applications',
                'gambar' => 'ferrari.jpg'
            ],
            [
                'id_produk' => 4,
                'nama_merk' => 'Lamborghini',
                'deskripsi' => 'Exclusive leather with unique texture and premium quality',
                'gambar' => 'lamborghini.jpg'
            ]
        ];

        return $leatherMaterials;
    }

}