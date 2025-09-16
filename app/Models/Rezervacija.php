<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Rezervacija extends Model
{
    use HasFactory;

    protected $fillable = ['datum_od','datum_do','broj_osoba','napomena','user_id','soba_id','status_id'];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'datum_od' => 'date',
            'datum_do' => 'date',
            'user_id' => 'integer',
            'soba_id' => 'integer',
            'status_id' => 'integer',
        ];
    }

    public function scopeOverlapping($q, int $sobaId, $from, $to)
    {
        return $q->where('soba_id', $sobaId)->where(function ($w) use ($from,$to) {
            $w->whereBetween('datum_od', [$from, $to])
              ->orWhereBetween('datum_do', [$from, $to])
              ->orWhere(function ($z) use ($from,$to) {
                  $z->where('datum_od', '<=', $from)->where('datum_do', '>=', $to);
              });
        })->whereHas('status', fn($s)=>$s->where('naziv_statusa','!=','Otkazana'));
    }

    public function user(): BelongsTo  { return $this->belongsTo(User::class); }
    public function soba(): BelongsTo  { return $this->belongsTo(Soba::class); }
    public function status(): BelongsTo{ return $this->belongsTo(Status::class); }
}