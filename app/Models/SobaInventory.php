<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SobaInventory extends Model
{
    use HasFactory;

    protected $table = 'soba_inventories';

    protected $fillable = [
        'soba_id',    
        'date',       
        'free_count',
        'price',     
    ];

    protected $casts = [
        'date' => 'date',
        'free_count' => 'integer',
        'price' => 'decimal:2',
    ];

    public function soba()
    {
        return $this->belongsTo(Soba::class);
    }
}