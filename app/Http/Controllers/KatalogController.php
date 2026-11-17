<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class KatalogController extends Controller
{
    public function index(Request $request): View
    {
        // 1. Pindahkan array data ke dalam method index
        $semuaProduk = [
            [
                'id' => 1,
                'nama_barang' => 'Kopi Kapal Api',
                'satuan' => 'sachet',
                'kategori' => 'Minuman',
                'harga' => 3000,
                'stok' => 20
            ],
            [
                'id' => 2,
                'nama_barang' => 'Minyak Goreng',
                'satuan' => 'Botol',
                'kategori' => 'K. Rumah Tangga',
                'harga' => 15000,
                'stok' => 12
            ],
            [
                'id' => 3,
                'nama_barang' => 'Roti Beta',
                'satuan' => 'Per Bungkus',
                'kategori' => 'Makanan',
                'harga' => 2000,
                'stok' => 20
            ],
            [
                'id' => 4,
                'nama_barang' => 'Susu Dancow',
                'satuan' => 'Sachet',
                'kategori' => 'Minuman',
                'harga' => 5000,
                'stok' => 12
            ],
        ];

        // 2. Ambil kata kunci pencarian dari URL (?q=...)
        $q = $request->input('q');

        // 3. Filter produk jika ada pencarian
        if ($q) {
            $produk = array_filter($semuaProduk, function ($item) use ($q) {
                return stripos($item['nama_barang'], $q) !== false;
            });
        } else {
            $produk = $semuaProduk;
        }

        // 4. Kirim variabel 'produk' dan 'q' ke view
        return view('katalog.index', compact('produk', 'q'));
    }
}
