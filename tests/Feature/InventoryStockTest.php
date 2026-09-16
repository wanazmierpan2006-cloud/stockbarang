<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryStockTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $gudang;

    protected User $pimpinan;

    protected Category $category;

    protected Supplier $supplier;

    protected Item $item;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin User',
            'username' => 'admin_test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        $this->gudang = User::create([
            'name' => 'Gudang User',
            'username' => 'gudang_test',
            'email' => 'gudang@test.com',
            'password' => bcrypt('password123'),
            'role' => 'gudang',
        ]);

        $this->pimpinan = User::create([
            'name' => 'Pimpinan User',
            'username' => 'pimpinan_test',
            'email' => 'pimpinan@test.com',
            'password' => bcrypt('password123'),
            'role' => 'pimpinan',
        ]);

        $this->category = Category::create(['nama' => 'Elektronik']);

        $this->supplier = Supplier::create([
            'nama' => 'PT Supplier Utama',
            'alamat' => 'Jl. Test No. 1',
            'telepon' => '08123456789',
            'email' => 'supplier@test.com',
        ]);

        $this->item = Item::create([
            'kode_barang' => 'TEST-001',
            'barcode' => '89912345',
            'nama_barang' => 'Barang Testing',
            'category_id' => $this->category->id,
            'supplier_id' => $this->supplier->id,
            'satuan' => 'Pcs',
            'stok' => 10,
            'minimum_stok' => 5,
        ]);
    }

    public function test_user_can_login_with_username_and_password()
    {
        $response = $this->post('/login', [
            'username' => 'admin_test',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($this->admin);
    }

    public function test_gudang_role_cannot_access_user_management()
    {
        $response = $this->actingAs($this->gudang)->get('/users');
        $response->assertRedirect('/dashboard');
    }

    public function test_barcode_scan_api_returns_item_data()
    {
        $response = $this->actingAs($this->gudang)->getJson('/api/items/scan?barcode=89912345');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'item' => [
                    'barcode' => '89912345',
                    'nama_barang' => 'Barang Testing',
                ],
            ]);
    }

    public function test_barcode_scan_api_returns_404_when_not_found()
    {
        $response = $this->actingAs($this->gudang)->getJson('/api/items/scan?barcode=99999999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'barcode' => '99999999',
            ]);
    }

    public function test_multi_item_incoming_transaction_increases_stock()
    {
        $initialStock = $this->item->stok; // 10

        $response = $this->actingAs($this->gudang)
            ->from('/incoming/create')
            ->post('/incoming', [
                'tanggal' => now()->format('Y-m-d'),
                'supplier_id' => $this->supplier->id,
                'keterangan' => 'Penerimaan stok baru POS barcode',
                'items' => json_encode([
                    ['item_id' => $this->item->id, 'jumlah' => 15],
                ]),
            ]);

        $response->assertRedirect('/incoming');
        $this->item->refresh();

        $this->assertEquals(25, $this->item->stok);
    }

    public function test_multi_item_outgoing_transaction_reduces_stock()
    {
        $initialStock = $this->item->stok; // 10

        $response = $this->actingAs($this->gudang)
            ->from('/outgoing/create')
            ->post('/outgoing', [
                'tanggal' => now()->format('Y-m-d'),
                'tujuan' => 'Divisi HRD',
                'keterangan' => 'Pengeluaran alat kantor POS barcode',
                'items' => json_encode([
                    ['item_id' => $this->item->id, 'jumlah' => 4],
                ]),
            ]);

        $response->assertRedirect('/outgoing');
        $this->item->refresh();

        $this->assertEquals(6, $this->item->stok);
    }

    public function test_outgoing_item_fails_when_stock_insufficient()
    {
        $initialStock = $this->item->stok; // 10

        $response = $this->actingAs($this->gudang)
            ->from('/outgoing/create')
            ->post('/outgoing', [
                'tanggal' => now()->format('Y-m-d'),
                'tujuan' => 'Divisi IT',
                'keterangan' => 'Pengeluaran berlebih',
                'items' => json_encode([
                    ['item_id' => $this->item->id, 'jumlah' => 50], // Melebihi stok 10
                ]),
            ]);

        $response->assertSessionHas('error');
        $this->item->refresh();

        $this->assertEquals(10, $this->item->stok); // Stok tidak berubah
    }
}
