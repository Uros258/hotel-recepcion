<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;  

class Status extends Model
{
    use HasFactory;

    protected $fillable = ['naziv_statusa'];
    protected function casts(): array { return ['id'=>'integer']; }

    public function rezervacijas(): HasMany { return $this->hasMany(Rezervacija::class); }
}

