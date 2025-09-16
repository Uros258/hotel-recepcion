<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;  

class Soba extends Model
{
    use HasFactory;

    protected $fillable = ['broj_sobe','tip_sobe','cena','status_sobe','slika' ,"opis"];
    protected function casts(): array { return ['id'=>'integer','cena'=>'decimal:2']; }

    public function rezervacijas(): HasMany { return $this->hasMany(Rezervacija::class); }

    public function inventories()
    {
    return $this->hasMany(SobaInventory::class);
    }
}
