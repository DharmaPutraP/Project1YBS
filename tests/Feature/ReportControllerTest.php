<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ReportControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup permissions
        $viewPerm = Permission::firstOrCreate(['name' => 'view laporan oil losses']);
        $exportPerm = Permission::firstOrCreate(['name' => 'export laporan oil losses']);
        
        $role = Role::firstOrCreate(['name' => 'admin']);
        $role->givePermissionTo([$viewPerm, $exportPerm]);
        
        $this->user = User::factory()->create(['office' => 'YBS']);
        $this->user->assignRole('admin');
    }

    public function test_index_displays_reports()
    {
        $response = $this->actingAs($this->user)->get(route('reports.index'));

        $response->assertStatus(200);
        $response->assertViewIs('reports.index');
        $response->assertViewHas('calculations');
    }

    public function test_export_downloads_excel()
    {
        $response = $this->actingAs($this->user)->post(route('reports.export'), [
            'start_date' => now()->startOfMonth()->format('Y-m-d'),
            'end_date' => now()->format('Y-m-d'),
        ]);

        $response->assertStatus(200);
        $response->assertHeader('content-disposition');
        
        // Ensure the downloaded file is an excel format
        $this->assertStringContainsString('attachment; filename=Laporan_Oil_Losses', $response->headers->get('content-disposition'));
    }
}
