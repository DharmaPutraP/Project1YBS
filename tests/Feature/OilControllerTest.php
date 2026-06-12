<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\OilCalculation;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class OilControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $permissions = [
            'view oil losses',
            'create oil losses',
            'edit oil losses',
            'delete oil losses',
            'view olwb',
            'export olwb reports',
            'view performance oil losses',
            'export performance reports oil losses',
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p]);
        }
        
        $role = Role::firstOrCreate(['name' => 'admin']);
        $role->givePermissionTo($permissions);
        
        $this->user = User::factory()->create(['office' => 'YBS']);
        $this->user->assignRole('admin');
    }

    public function test_index_displays_oil_calculations()
    {
        $response = $this->actingAs($this->user)->get(route('oil.index'));
        $response->assertStatus(200);
        $response->assertViewIs('oil.index');
    }

    public function test_create_displays_form()
    {
        $response = $this->actingAs($this->user)->get(route('oil.create'));
        $response->assertStatus(200);
        $response->assertViewIs('oil.create');
    }

    public function test_store_creates_new_oil_calculation()
    {
        \App\Models\OilMasterData::create([
            'office' => 'YBS',
            'kode' => 'A1',
            'column_name' => 'col1',
            'jenis' => 'TBS',
            'is_active' => true,
        ]);

        $payload = [
            'tanggal_sampel_mode1' => now()->format('Y-m-d'),
            'tanggal_sampel_mode2' => now()->format('Y-m-d'),
            'kode' => 'A1',
            'operator' => 'Test Operator',
            'jenis' => 'TBS',
            'sampel_boy' => 'Boy 1',
            'kode_mode2' => 'A1',
            'cawan_kosong' => 10,
            'berat_basah' => 20,
            'cawan_sample_kering' => 25,
            'labu_kosong' => 5,
            'oil_labu' => 8,
        ];

        $response = $this->actingAs($this->user)->post(route('oil.store'), $payload);

        $response->assertRedirect(route('oil.index'));
        $this->assertDatabaseHas('oil_calculations', [
            'kode' => 'A1',
        ]);
        
        $this->assertDatabaseHas('oil_records', [
            'kode' => 'A1',
            'sampel_boy' => 'Boy 1',
            'jenis' => 'TBS'
        ]);
    }
}
