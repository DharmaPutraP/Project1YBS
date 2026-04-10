<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessMachineCatalog extends Model
{
    use HasFactory;

    protected $table = 'process_machine_catalogs';

    protected $fillable = [
        'office',
        'machine_group',
        'machine_name',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];
}
