<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OilMasterData extends Model
{
    use HasFactory;

    protected $table = 'oil_master_data';

    protected $fillable = [
        'office',
        'kode',
        'column_name',
        'jenis',
        'persen',
        'persen4',
        'pivot',
        'limitOLWB',
        'limitOLDB',
        'limitOL',
        'limit_operator',
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
    public static function getKodeDropdown(?string $office = null)
    {
        $officeCode = strtoupper(trim((string) ($office ?? auth()->user()?->office ?? '')));

        return static::query()
            ->active()
            ->when($officeCode !== '' && $officeCode !== 'ALL', fn($q) => $q->where('office', $officeCode))
            ->orderBy('kode')
            ->get(['kode', 'pivot', 'description'])
            ->mapWithKeys(function ($item) {
                $label = $item->description
                    ? "{$item->kode} - {$item->description}"
                    : ($item->pivot ? "{$item->kode} - {$item->pivot}" : $item->kode);

                return [$item->kode => $label];
            });
    }

    /**
     * Get display label for a single kode.
     */
    public static function getKodeDisplay(string $kode, ?string $pivot = null, ?string $office = null): string
    {
        $officeCode = strtoupper(trim((string) ($office ?? auth()->user()?->office ?? '')));

        $masterData = static::query()
            ->where('kode', $kode)
            ->when($officeCode !== '' && $officeCode !== 'ALL', fn($q) => $q->where('office', $officeCode))
            ->first(['pivot', 'description']);

        $resolvedLabel = $masterData?->description ?: $pivot;

        if (!$resolvedLabel) {
            $resolvedLabel = $masterData?->pivot;
        }

        return $resolvedLabel ? "{$kode} - {$resolvedLabel}" : $kode;
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
