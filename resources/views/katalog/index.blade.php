@extends('layouts.app')

@section('title', 'Daftar Barang - POS System')

@section('content')

{{-- Font Google Inter agar mirip dengan desain --}}
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<div class="pos-wrapper">
    {{-- SIDEBAR KIRI --}}
    <aside class="pos-sidebar">
        <div class="sidebar-top">
            <div class="brand">
                <div class="brand-logo">🛒</div>
                <div class="brand-info">
                    <span class="brand-title">Pos System</span>
                    <span class="brand-subtitle">Sistem kasir</span>
                </div>
                <button class="collapse-btn" title="Sembunyikan Sidebar">&laquo;</button>
            </div>

            <nav class="sidebar-menu">
                <a href="#" class="menu-item">
                    <span class="icon">🛍️</span> Kasir
                </a>
                <a href="{{ route('katalog.index') }}" class="menu-item active">
                    <span class="icon">📦</span> Barang & Stok
                </a>
                <a href="#" class="menu-item">
                    <span class="icon">🕒</span> Riwayat Transaksi
                </a>
                <a href="#" class="menu-item">
                    <span class="icon">📊</span> Laporan
                </a>
                <a href="#" class="menu-item">
                    <span class="icon">💳</span> Pengeluaran
                </a>
                <a href="#" class="menu-item">
                    <span class="icon">⚙️</span> Pengaturan
                </a>
            </nav>
        </div>

        <div class="sidebar-bottom">
            <div class="theme-toggle">
                <span>💡 Mode Tampilan</span>
                <button class="theme-btn">🌙</button>
            </div>
            <a href="#" class="logout-btn">
                <span>🚪</span> Keluar
            </a>
        </div>
    </aside>

    {{-- KONTEN UTAMA KANAN --}}
    <main class="pos-main">
        {{-- TOP HEADER --}}
        <header class="pos-header">
            <div class="page-title">
                <h1>Daftar Barang</h1>
                <p>Kelola data barang & inventori</p>
            </div>

            <div class="header-widgets">
                <button class="widget-icon-btn" title="Notifikasi">🔔</button>
                <div class="widget-box time-box">
                    <span class="widget-label">Waktu</span>
                    <span class="widget-value">{{ date('H:i:s') }} WIB</span>
                </div>
                <div class="widget-box profile-box">
                    <img src="https://ui-avatars.com/api/?name=Akbar+Hidayat&background=0D8ABC&color=fff" alt="Avatar" class="avatar">
                    <div class="profile-info">
                        <span class="profile-name">Akbar Hidayat</span>
                        <span class="profile-role">Kasir</span>
                    </div>
                </div>
            </div>
        </header>

        {{-- KARTU PUTIH UTAMA --}}
        <section class="content-card">
            {{-- Tombol Aksi Atas --}}
            <div class="card-top-actions">
                <button type="button" class="btn btn-outline">
                    📥 Ekspor PDF
                </button>
                <a href="#" class="btn btn-primary">
                    + Tambah Barang
                </a>
            </div>

            {{-- Alert Stok Minimum (Otomatis menghitung jika ada stok <= 20) --}}
            @php
                $stokMenipis = collect($produk)->where('stok', '<=', 20)->count();
            @endphp
            @if($stokMenipis > 0)
            <div class="alert-warning">
                <span class="alert-icon">📦</span>
                <span><strong>{{ $stokMenipis }} barang</strong> memiliki stok di bawah minimum</span>
            </div>
            @endif

            {{-- Form Filter & Pencarian --}}
            <form method="GET" action="{{ route('katalog.index') }}" class="filter-bar">
                <div class="filter-left">
                    <select name="kategori" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua kategori</option>
                        <option value="Minuman" {{ request('kategori') == 'Minuman' ? 'selected' : '' }}>Minuman</option>
                        <option value="Makanan" {{ request('kategori') == 'Makanan' ? 'selected' : '' }}>Makanan</option>
                    </select>
                </div>

                <div class="filter-right">
                    <div class="search-input-wrapper">
                        <span class="search-icon">🔍</span>
                        <input id="q" name="q" value="{{ $q ?? '' }}" placeholder="Cari nama atau kode barang..." class="form-search">
                    </div>
                </div>
            </form>

            {{-- Tabel Data Produk --}}
            <div class="table-responsive">
                <table class="pos-table">
                    <thead>
                        <tr>
                            <th>Kode barang</th>
                            <th>Nama barang</th>
                            <th>Kategori</th>
                            <th>Harga jual</th>
                            <th class="text-center">Stok</th>
                            <th class="text-center">Min. stok</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($produk as $index => $item)
                            @php
                                $minStok = $item['min_stok'] ?? 20;
                                $isLowStock = $item['stok'] <= $minStok;
                            @endphp
                            <tr>
                                <td class="text-muted">{{ $item['kode'] ?? 'BRG-00' . ($index + 1) }}</td>
                                <td class="font-medium">{{ $item['nama_barang'] }}</td>
                                <td>{{ $item['kategori'] }}</td>
                                <td>Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                                <td class="text-center {{ $isLowStock ? 'text-danger font-bold' : '' }}">
                                    {{ $item['stok'] }}
                                </td>
                                <td class="text-center">{{ $minStok }}</td>
                                <td class="text-center">
                                    <span class="badge-aktif">Aktif</span>
                                </td>
                                <td class="text-center action-cell">
                                    <a href="#" class="action-btn edit" title="Edit">✏️</a>
                                    <a href="#" class="action-btn delete" title="Hapus">🗑️</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="empty-state">
                                    Produk tidak ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer Tabel --}}
            <div class="table-footer">
                Menampilkan {{ count($produk) > 0 ? '1 - ' . count($produk) : '0' }} dari {{ count($produk) }} data
            </div>
        </section>
    </main>
</div>
@endsection