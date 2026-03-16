<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OilMasterData extends Model
{
    use HasFactory;

    protected $table = 'oil_master_data';

    protected $fillable = [
        'kode',
        'column_name',
        'jenis',
        'persen',
        'persen4',
        'pivot',
        'limitOLWB',
        'limitOLDB',
        'limitOL',
        'description',
        'is_active',
    ];

    protected $casts = [
        'persen' => 'decimal:4',
        'persen4' => 'decimal:4',
        'limitOLWB' => 'decimal:2',
        'limitOLDB' => 'decimal:2',
        'limitOL' => 'decimal:2',
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
            ->get(['kode', 'pivot'])
            ->mapWithKeys(function ($item) {
                $label = $item->pivot
                    ? "{$item->kode} - {$item->pivot}"
                    : $item->kode;

                return [$item->kode => $label];
            });
    }

    /**
     * Get display label for a single kode.
     */
    public static function getKodeDisplay(string $kode, ?string $pivot = null): string
    {
        $resolvedPivot = $pivot;

        if (!$resolvedPivot) {
            $resolvedPivot = static::where('kode', $kode)->value('pivot');
        }

        return $resolvedPivot ? "{$kode} - {$resolvedPivot}" : $kode;
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
