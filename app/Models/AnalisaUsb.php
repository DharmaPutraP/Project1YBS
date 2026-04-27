<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnalisaUsb extends Model
{
    use HasFactory;

    protected $table = 'analisa_usb';

    protected $fillable = [
        'user_id',
        'created_by',
        'office',
        'tanggal',
        'jam',
        'shift',
        'no_rebusan',
        'diamati_jlh_janjang',
        'lolos_jlh_janjang',
        'persen_usb',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'shift' => 'integer',
        'no_rebusan' => 'integer',
        'diamati_jlh_janjang' => 'decimal:2',
        'lolos_jlh_janjang' => 'decimal:2',
        'persen_usb' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
