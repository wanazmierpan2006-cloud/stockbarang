<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\IncomingTransaction;
use App\Models\Item;
use App\Models\OutgoingTransaction;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (User::where('username', 'admin')->exists()) {
            return;
        }

        // 1. Users Seeder (Admin, Gudang, Pimpinan)
        $admin = User::create([
            'name' => 'Administrator STH',
            'username' => 'admin',
            'email' => 'admin@sthnetwork.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $gudang = User::create([
            'name' => 'Staf Gudang STH',
            'username' => 'gudang',
            'email' => 'gudang@sthnetwork.id',
            'password' => Hash::make('password123'),
            'role' => 'gudang',
        ]);

        $pimpinan = User::create([
            'name' => 'Bapak Pimpinan STH',
            'username' => 'pimpinan',
            'email' => 'pimpinan@sthnetwork.id',
            'password' => Hash::make('password123'),
            'role' => 'pimpinan',
        ]);

        // 2. Categories Seeder
        $catElectronics = Category::create(['nama' => 'Peralatan Jaringan & IT']);
        $catStationery = Category::create(['nama' => 'Alat Tulis Kantor (ATK)']);
        $catFurniture = Category::create(['nama' => 'Furniture & Mebel']);
        $catPackaging = Category::create(['nama' => 'Kabel & Aksesoris Network']);

        // 3. Suppliers Seeder
        $supplier1 = Supplier::create([
            'nama' => 'PT. Nusantara Fiber Tech',
            'alamat' => 'Jl. Jendral Sudirman No. 45, Jakarta Selatan',
            'telepon' => '081298765432',
            'email' => 'contact@nusantarafiber.co.id',
        ]);

        $supplier2 = Supplier::create([
            'nama' => 'CV. Mitra Network Supply',
            'alamat' => 'Jl. Gatot Subroto No. 12, Bandung',
            'telepon' => '082134567890',
            'email' => 'sales@mitranetwork.com',
        ]);

        $supplier3 = Supplier::create([
            'nama' => 'PT. Mega Hardware Kreasindo',
            'alamat' => 'Kawasan Industri Jababeka Blok C2, Cikarang',
            'telepon' => '085711223344',
            'email' => 'info@megahardware.co.id',
        ]);

        // 4. Items Seeder (With Barcodes!)
        $item1 = Item::create([
            'kode_barang' => 'NET-001',
            'barcode' => '89912345',
            'nama_barang' => 'Router Mikrotik CCR2004-16G-2S+',
            'category_id' => $catElectronics->id,
            'supplier_id' => $supplier1->id,
            'satuan' => 'Unit',
            'stok' => 25,
            'minimum_stok' => 5,
            'keterangan' => 'Router core gigabit untuk infrastruktur STH Network',
        ]);

        $item2 = Item::create([
            'kode_barang' => 'KBL-002',
            'barcode' => '89912346',
            'nama_barang' => 'Kabel Fiber Optic Dropcore 1 Core 1000m',
            'category_id' => $catPackaging->id,
            'supplier_id' => $supplier2->id,
            'satuan' => 'Roll',
            'stok' => 120,
            'minimum_stok' => 20,
            'keterangan' => 'Stok kabel FO penarikan jaringan baru pelanggan',
        ]);

        $item3 = Item::create([
            'kode_barang' => 'SWT-003',
            'barcode' => '89912347',
            'nama_barang' => 'Switch Managed 24 Port Gigabit PoE+',
            'category_id' => $catElectronics->id,
            'supplier_id' => $supplier3->id,
            'satuan' => 'Unit',
            'stok' => 4,
            'minimum_stok' => 15,
            'keterangan' => 'Switch PoE distribusi POP area (Stok Menipis!)',
        ]);

        $item4 = Item::create([
            'kode_barang' => 'ATK-004',
            'barcode' => '89912348',
            'nama_barang' => 'Kertas HVS A4 80gr Sidu',
            'category_id' => $catStationery->id,
            'supplier_id' => $supplier2->id,
            'satuan' => 'Rim',
            'stok' => 45,
            'minimum_stok' => 10,
            'keterangan' => 'Kertas cetak invoice dan surat jalan pelanggan',
        ]);

        $item5 = Item::create([
            'kode_barang' => 'ONT-005',
            'barcode' => '89912349',
            'nama_barang' => 'ONT GPON Fiberhome HG6245D Dualband',
            'category_id' => $catElectronics->id,
            'supplier_id' => $supplier1->id,
            'satuan' => 'Unit',
            'stok' => 3,
            'minimum_stok' => 10,
            'keterangan' => 'Modem ONT Wi-Fi Dualband untuk pasang baru',
        ]);

        // 5. Multi-Item Incoming Transactions Seeder
        $txIn1 = IncomingTransaction::create([
            'kode_transaksi' => 'TRX-IN-20260728-0001',
            'supplier_id' => $supplier1->id,
            'user_id' => $gudang->id,
            'tanggal' => now()->subDays(2)->format('Y-m-d'),
            'keterangan' => 'Pengadaan rutin Mikrotik & ONT batch 1 2026',
        ]);
        $txIn1->details()->createMany([
            ['item_id' => $item1->id, 'jumlah' => 20],
            ['item_id' => $item5->id, 'jumlah' => 10],
        ]);

        $txIn2 = IncomingTransaction::create([
            'kode_transaksi' => 'TRX-IN-20260730-0002',
            'supplier_id' => $supplier2->id,
            'user_id' => $admin->id,
            'tanggal' => now()->format('Y-m-d'),
            'keterangan' => 'Restok kabel FO 1000 meter & ATK',
        ]);
        $txIn2->details()->createMany([
            ['item_id' => $item2->id, 'jumlah' => 50],
            ['item_id' => $item4->id, 'jumlah' => 20],
        ]);

        // 6. Multi-Item Outgoing Transactions Seeder
        $txOut1 = OutgoingTransaction::create([
            'kode_transaksi' => 'TRX-OUT-20260729-0001',
            'user_id' => $gudang->id,
            'tanggal' => now()->subDays(1)->format('Y-m-d'),
            'tujuan' => 'POP Cabang Bandung',
            'keterangan' => 'Pemasangan router & kabel FO distribusi',
        ]);
        $txOut1->details()->createMany([
            ['item_id' => $item1->id, 'jumlah' => 3],
            ['item_id' => $item2->id, 'jumlah' => 10],
        ]);
    }
}
