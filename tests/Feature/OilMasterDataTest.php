<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\OilMasterData;
use App\Models\User;

class OilMasterDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_scope_active()
    {
        OilMasterData::create(['office' => 'YBS', 'kode' => 'A1', 'column_name' => 'col1', 'is_active' => true]);
        OilMasterData::create(['office' => 'YBS', 'kode' => 'A2', 'column_name' => 'col2', 'is_active' => false]);

        $activeData = OilMasterData::active()->get();

        $this->assertCount(1, $activeData);
        $this->assertEquals('A1', $activeData->first()->kode);
    }

    public function test_get_kode_dropdown_filters_by_office()
    {
        OilMasterData::create(['office' => 'YBS', 'kode' => 'A1', 'column_name' => 'col1', 'is_active' => true, 'description' => 'Desc A1']);
        OilMasterData::create(['office' => 'SUN', 'kode' => 'A2', 'column_name' => 'col2', 'is_active' => true, 'description' => 'Desc A2']);

        $dropdownYbs = OilMasterData::getKodeDropdown('YBS');
        $this->assertCount(1, $dropdownYbs);
        $this->assertArrayHasKey('A1', $dropdownYbs->toArray());

        $dropdownSun = OilMasterData::getKodeDropdown('SUN');
        $this->assertCount(1, $dropdownSun);
        $this->assertArrayHasKey('A2', $dropdownSun->toArray());
    }

    public function test_get_kode_dropdown_uses_auth_user_office()
    {
        $user = User::factory()->create(['office' => 'SJN']);
        $this->actingAs($user);

        OilMasterData::create(['office' => 'SJN', 'kode' => 'A3', 'column_name' => 'col3', 'is_active' => true]);
        OilMasterData::create(['office' => 'YBS', 'kode' => 'A1', 'column_name' => 'col1', 'is_active' => true]);

        $dropdown = OilMasterData::getKodeDropdown();
        
        $this->assertCount(1, $dropdown);
        $this->assertArrayHasKey('A3', $dropdown->toArray());
    }

    public function test_get_kode_display()
    {
        OilMasterData::create(['office' => 'YBS', 'kode' => 'C1', 'column_name' => 'c1', 'description' => 'Test Desc', 'is_active' => true]);

        $display = OilMasterData::getKodeDisplay('C1', null, 'YBS');
        $this->assertEquals('C1 - Test Desc', $display);

        // Fallback to pivot if description is null
        OilMasterData::create(['office' => 'YBS', 'kode' => 'C2', 'column_name' => 'c2', 'pivot' => 'Test Pivot', 'is_active' => true]);
        $displayPivot = OilMasterData::getKodeDisplay('C2', null, 'YBS');
        $this->assertEquals('C2 - Test Pivot', $displayPivot);
    }
}
