<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Soba extends Model
{
    use HasFactory;

    protected $table = 'Soba';

    protected $guarded = [];

    public function rezervacijas()
    {
        return $this->hasMany(Rezervacija::class);
    }
}
