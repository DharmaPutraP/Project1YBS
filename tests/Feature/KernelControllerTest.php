<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\KernelMasterData;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class KernelControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $permissions = [
            'view kernel losses',
            'create kernel losses',
            'edit kernel losses',
            'delete kernel losses',
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p]);
        }
        
        $role = Role::firstOrCreate(['name' => 'admin']);
        $role->givePermissionTo($permissions);
        
        $this->user = User::factory()->create(['office' => 'YBS']);
        $this->user->assignRole('admin');
    }

    public function test_index_displays_kernel_calculations()
    {
        $response = $this->actingAs($this->user)->get(route('kernel.index'));
        $response->assertStatus(200);
        $response->assertViewIs('kernel.index');
    }

    public function test_create_displays_form()
    {
        $response = $this->actingAs($this->user)->get(route('kernel.create'));
        $response->assertStatus(200);
        $response->assertViewIs('kernel.create');
    }

    public function test_store_creates_new_kernel_calculation()
    {
        KernelMasterData::create([
            'office' => 'YBS',
            'kode' => 'K1',
            'column_name' => 'col1',
            'jenis' => 'TBS',
            'is_active' => true,
        ]);

        $payload = [
            'tanggal_sampel' => now()->format('Y-m-d'),
            'tanggal_sampel_mode1' => now()->format('Y-m-d'),
            'tanggal_sampel_mode2' => now()->format('Y-m-d'),
            'kode' => 'K1',
            'operator' => 'Test Operator',
            'jenis' => 'TBS',
            'sampel_boy' => 'Boy 1',
            'kode_mode2' => 'K1',
            'cawan_kosong' => 10,
            'berat_basah' => 20,
            'cawan_sample_kering' => 25,
            'labu_kosong' => 5,
            'oil_labu' => 8,
            'berat_kotor_kering' => 30,
            'berat_kotor_basah' => 40,
            'cangkang_non_kalsium' => 50,
            'kotoran' => 10,
        ];

        $response = $this->actingAs($this->user)->post(route('kernel.store'), $payload);

        $response->assertRedirect(route('kernel.index'));
        $this->assertDatabaseHas('kernel_calculations', [
            'kode' => 'K1',
        ]);
        
        $this->assertDatabaseHas('kernel_records', [
            'kode' => 'K1',
            'sampel_boy' => 'Boy 1',
            'jenis' => 'TBS'
        ]);
    }
}
