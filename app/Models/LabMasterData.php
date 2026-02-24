<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabMasterData extends Model
{
    use HasFactory;

    protected $table = 'lab_master_data';

    protected $fillable = [
        'kode',
        'column_name',
        'jenis',
        'persen',
        'persen4',
        'pivot',
        'limit',
        'limit2',
        'limit3',
        'description',
        'is_active',
    ];

    protected $casts = [
        'persen' => 'decimal:4',
        'persen4' => 'decimal:4',
        'limit' => 'decimal:2',
        'limit2' => 'decimal:2',
        'limit3' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Scope untuk filter data aktif saja
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get dropdown list untuk kode
     */
    public static function getKodeDropdown()
    {
        return static::active()
            ->orderBy('kode')
            ->pluck('kode', 'kode');
    }

    /**
     * Get dropdown list untuk jenis (Fixed: TBS dan Brondolan)
     * Jenis adalah input manual user, bukan dari master data
     */
    public static function getJenisDropdown()
    {
        return collect([
            'TBS' => 'TBS',
            'Brondolan' => 'Brondolan',
        ]);
    }
}
