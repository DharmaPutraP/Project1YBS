<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class KernelRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kernel_records';

    protected $fillable = [
        'user_id',
        'kode',
        'jenis',
        'operator',
        'sampel_boy',
        'parameter_lain',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('created_at', $date);
    }

    /**
     * Static kode options for kernel losses sampling points.
     */
    public static function getKodeOptions(): array
    {
        return [
            'CWS1' => 'CWS1',
            'CWS2' => 'CWS2',
            'CWS3' => 'CWS3',
            'FC1'  => 'FC1',
            'FC2'  => 'FC2',
            'L1'   => 'L1',
            'L2'   => 'L2',
            'L3'   => 'L3',
            'L4'   => 'L4',
        ];
    }

    /**
     * Static jenis options for kernel losses.
     */
    public static function getJenisOptions(): array
    {
        return [
            'TBS'       => 'TBS',
            'Brondolan' => 'Brondolan',
        ];
    }
}
