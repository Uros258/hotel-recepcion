<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rezervacija extends Model
{
    use HasFactory;

    protected $table = 'Rezervacija';

    protected $guarded = [];

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function soba()
    {
        return $this->belongsTo(Soba::class);
    }
}
