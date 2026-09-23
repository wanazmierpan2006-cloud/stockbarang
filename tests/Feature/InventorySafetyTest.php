<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\User;
use App\Services\InventoryService;
use App\Services\ItemService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventorySafetyTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $gudang;

    protected Category $category;

    protected Supplier $supplier;

    protected Item $item;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Safety',
            'username' => 'admin_safety',
            'email' => 'admin_safety@test.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        $this->gudang = User::create([
            'name' => 'Gudang Safety',
            'username' => 'gudang_safety',
            'email' => 'gudang_safety@test.com',
            'password' => bcrypt('password123'),
            'role' => 'gudang',
        ]);

        $this->category = Category::create(['nama' => 'Kategori Safety']);
        $this->supplier = Supplier::create([
            'nama' => 'Supplier Safety',
            'alamat' => 'Jl. Safety No. 1',
            'telepon' => '08111111111',
            'email' => 'safety@supplier.com',
        ]);

        $this->item = Item::create([
            'kode_barang' => 'SAFE-001',
            'barcode' => '89900001',
            'nama_barang' => 'Barang Safety',
            'category_id' => $this->category->id,
            'supplier_id' => $this->supplier->id,
            'satuan' => 'Pcs',
            'stok' => 10,
            'minimum_stok' => 5,
        ]);
    }

    public function test_deleting_item_preserves_transaction_and_adjustment_history()
    {
        $inventory = app(InventoryService::class);
        $incoming = $inventory->processIncomingTransaction([
            'supplier_id' => $this->supplier->id,
            'tanggal' => now()->toDateString(),
        ], [['item_id' => $this->item->id, 'jumlah' => 5]], $this->admin->id);
        $outgoing = $inventory->processOutgoingTransaction([
            'tujuan' => 'Gudang Cabang',
            'tanggal' => now()->toDateString(),
        ], [['item_id' => $this->item->id, 'jumlah' => 2]], $this->admin->id);
        $adjustment = $inventory->processStockAdjustment([
            'item_id' => $this->item->id,
            'stok_sesudah' => 12,
            'alasan' => 'Koreksi stok fisik',
        ], $this->admin->id);

        $this->actingAs($this->admin)->delete("/items/{$this->item->id}")
            ->assertRedirect('/items')->assertSessionHas('success');

        $this->assertSoftDeleted($this->item);
        $this->assertNull(Item::find($this->item->id));
        $this->assertFalse(app(ItemService::class)->getAllItems()->contains('id', $this->item->id));
        $this->assertModelExists($incoming);
        $this->assertModelExists($outgoing);
        $this->assertModelExists($adjustment);
        $this->assertEquals(5, $incoming->fresh()->details->sole()->jumlah);
        $this->assertEquals(2, $outgoing->fresh()->details->sole()->jumlah);
        $this->assertSame($this->item->nama_barang, $incoming->fresh()->details->sole()->item->nama_barang);
        $this->assertSame($this->item->nama_barang, $outgoing->fresh()->details->sole()->item->nama_barang);
        $this->assertSame($this->item->nama_barang, $adjustment->fresh()->item->nama_barang);

        $this->withoutVite();
        $this->get('/items')->assertOk()->assertDontSee($this->item->nama_barang);
        $this->get('/incoming')->assertOk()->assertSee($this->item->nama_barang);
        $this->get('/outgoing')->assertOk()->assertSee($this->item->nama_barang);
    }

    public function test_deleted_item_cannot_be_scanned_or_used_in_new_transactions()
    {
        $this->actingAs($this->admin)->delete("/items/{$this->item->id}")
            ->assertSessionHas('success');

        foreach ([$this->item->barcode, $this->item->kode_barang] as $code) {
            $this->getJson('/api/items/scan?barcode='.$code)->assertNotFound();
        }

        foreach (['incoming', 'outgoing'] as $type) {
            $this->from("/{$type}/create")->post("/{$type}", [
                'tanggal' => now()->toDateString(),
                'supplier_id' => $this->supplier->id,
                'tujuan' => 'Gudang Cabang',
                'items' => json_encode([['item_id' => $this->item->id, 'jumlah' => 1]]),
            ])->assertSessionHas('error');
            $this->assertDatabaseCount($type.'_transactions', 0);
        }

        $this->assertEquals(10, Item::withTrashed()->findOrFail($this->item->id)->stok);
    }

    public function test_gudang_cannot_delete_an_item()
    {
        $this->actingAs($this->gudang)->delete("/items/{$this->item->id}")
            ->assertRedirect('/dashboard');

        $this->assertNotSoftDeleted($this->item);
    }

    public function test_category_in_use_cannot_be_deleted()
    {
        $response = $this->actingAs($this->admin)->delete("/categories/{$this->category->id}");

        $response->assertRedirect('/categories');
        $response->assertSessionHas('error');

        $this->assertModelExists($this->category);
        $this->assertModelExists($this->item);
    }

    public function test_supplier_in_use_cannot_be_deleted()
    {
        $response = $this->actingAs($this->admin)->delete("/suppliers/{$this->supplier->id}");

        $response->assertRedirect('/suppliers');
        $response->assertSessionHas('error');

        $this->assertModelExists($this->supplier);
    }

    public function test_duplicate_item_rows_are_aggregated_before_stock_validation()
    {
        // Total permintaan 12 (6+6) melebihi stok 10, meski tiap baris <= stok
        $response = $this->actingAs($this->gudang)
            ->from('/outgoing/create')
            ->post('/outgoing', [
                'tanggal' => now()->format('Y-m-d'),
                'tujuan' => 'Divisi QA',
                'keterangan' => 'Tes agregasi baris duplikat',
                'items' => json_encode([
                    ['item_id' => $this->item->id, 'jumlah' => 6],
                    ['item_id' => $this->item->id, 'jumlah' => 6],
                ]),
            ]);

        $response->assertSessionHas('error');
        $this->item->refresh();
        $this->assertEquals(10, $this->item->stok);
    }

    public function test_duplicate_item_rows_aggregate_into_single_transaction()
    {
        $response = $this->actingAs($this->gudang)
            ->from('/outgoing/create')
            ->post('/outgoing', [
                'tanggal' => now()->format('Y-m-d'),
                'tujuan' => 'Divisi QA',
                'keterangan' => 'Tes agregasi sukses',
                'items' => json_encode([
                    ['item_id' => $this->item->id, 'jumlah' => 3],
                    ['item_id' => $this->item->id, 'jumlah' => 4],
                ]),
            ]);

        $response->assertRedirect('/outgoing');
        $this->item->refresh();
        $this->assertEquals(3, $this->item->stok);
    }

    public function test_login_is_rate_limited_after_too_many_attempts()
    {
        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/login', [
                'username' => 'hacker_test',
                'password' => 'wrong-password',
            ]);
        }

        $response = $this->postJson('/login', [
            'username' => 'hacker_test',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(429);
    }
}
