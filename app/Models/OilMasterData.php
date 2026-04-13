<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OilMasterData extends Model
{
    use HasFactory;

    private const SUN_CODE_LABELS = [
        'HP1' => 'HEAVY PHASE 1',
        'HP2' => 'HEAVY PHASE 2',
        'HP3' => 'HEAVY PHASE 3',
        'HP4' => 'HEAVY PHASE 4',
        'SD1' => 'SOLID 1',
        'SD2' => 'SOLID 2',
        'SD3' => 'SOLID 3',
        'SD4' => 'SOLID 4',
        'HPL1' => 'SEPARATOR 1',
        'HPL2' => 'SEPARATOR 2',
        'HPL3' => 'SEPARATOR 3',
    ];

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
    public static function getKodeDropdown(?string $office = null)
    {
        $officeCode = strtoupper(trim((string) ($office ?? auth()->user()?->office ?? '')));

        return static::active()
            ->orderBy('kode')
            ->get(['kode', 'pivot', 'description'])
            ->mapWithKeys(function ($item) use ($officeCode) {
                $label = $item->description
                    ? "{$item->kode} - {$item->description}"
                    : ($item->pivot ? "{$item->kode} - {$item->pivot}" : $item->kode);

                if ($officeCode === 'SUN') {
                    $label = static::resolveSunLabel($item->kode, $item->pivot, $item->description);
                }

                return [$item->kode => $label];
            });
    }

    /**
     * Get display label for a single kode.
     */
    public static function getKodeDisplay(string $kode, ?string $pivot = null, ?string $office = null): string
    {
        $masterData = static::where('kode', $kode)->first(['pivot', 'description']);

        $officeCode = strtoupper(trim((string) ($office ?? auth()->user()?->office ?? '')));
        if ($officeCode === 'SUN') {
            return static::resolveSunLabel($kode, $pivot, $masterData?->description);
        }

        $resolvedLabel = $masterData?->description ?: $pivot;

        if (!$resolvedLabel) {
            $resolvedLabel = $masterData?->pivot;
        }

        return $resolvedLabel ? "{$kode} - {$resolvedLabel}" : $kode;
    }

    private static function resolveSunLabel(string $kode, ?string $pivot = null, ?string $description = null): string
    {
        $label = static::SUN_CODE_LABELS[strtoupper(trim($kode))] ?? null;

        if (!$label) {
            $label = $description ?: $pivot;
        }

        return $label ? "{$kode} - {$label}" : $kode;
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
