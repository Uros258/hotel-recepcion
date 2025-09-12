<?php

namespace App\Http\Controllers;

use App\Models\Soba;
use App\Models\SobaInventory;
use App\Models\Rezervacija;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SobaController extends Controller
{
    /* ---------- Public pages ---------- */

    public function index()
    {
        $sobas = Soba::orderBy('tip_sobe')->paginate(12);
        return view('sobas.index', compact('sobas'));
    }

    public function show(Soba $soba, Request $req)
    {
        $date = $req->query('date', now()->toDateString());

        $preview = [];
        for ($i = 0; $i < 7; $i++) {
            $d = Carbon::parse($date)->copy()->addDays($i);
            $preview[$d->toDateString()] = $this->remainingForDate($soba, $d);
        }

        return view('sobas.show', compact('soba','preview','date'));
    }

    /* ---------- Manager pages ---------- */

    // If your routes use indexManager/editManager/updateManager, these wrappers keep both styles working.
    public function indexManager(Request $req)  { return $this->manager($req); }
    public function editManager(Request $req)   { return $this->managerEdit($req); }
    public function updateManager(Request $req) { return $this->managerSave($req); }

    public function manager(Request $req)
    {
        $this->guardRole(['menadzer','admin']);
        $date = Carbon::parse($req->query('date', now()->toDateString()));

        $sobas = Soba::orderBy('tip_sobe')->get()->map(function($soba) use ($date) {
            return (object)[
                'id'          => $soba->id,
                'tip_sobe'    => $soba->tip_sobe,
                'cena'        => $soba->cena,
                'ukupno_soba' => (int)$soba->ukupno_soba,
                'free_today'  => $this->dayCapacityBaseline($soba, $date),
                'remaining'   => $this->remainingForDate($soba, $date),
                'opis'        => $soba->opis,
            ];
        });

        return view('menadzer.sobe.index', compact('sobas','date'));
    }

    public function managerEdit(Request $req)
    {
        $this->guardRole(['menadzer','admin']);
        $date = Carbon::parse($req->query('date', now()->toDateString()));
        $sobas = Soba::orderBy('tip_sobe')->get();

        $values = [];
        foreach ($sobas as $soba) {
            $values[$soba->id] = [
                'price' => (float)$soba->cena,
                'free'  => $this->dayCapacityBaseline($soba, $date),
                'max'   => (int)$soba->ukupno_soba,
                'opis'  => (string)($soba->opis ?? ''),
            ];
        }

        return view('menadzer.sobe.edit', compact('sobas','date','values'));
    }

    public function managerSave(Request $req)
    {
        $this->guardRole(['menadzer','admin']);

        $data = $req->validate([
            'date'    => ['required','date'],
            'price'   => ['required','array'],
            'price.*' => ['required','numeric','min:0'],
            'free'    => ['required','array'],
            'free.*'  => ['required','integer','min:0'],
            'opis'    => ['nullable','array'],
            'opis.*'  => ['nullable','string'],
        ]);

        $date = Carbon::parse($data['date'])->toDateString();

        foreach ($data['price'] as $sobaId => $cena) {
            $soba = Soba::findOrFail($sobaId);

            // clamp free to [0, ukupno_soba]
            $free = (int) ($data['free'][$sobaId] ?? $soba->ukupno_soba);
            $free = max(0, min($free, (int)$soba->ukupno_soba));

            // update cena + (optional) opis
            $payload = [];
            if ((float)$soba->cena !== (float)$cena) $payload['cena'] = $cena;
            if (array_key_exists($sobaId, $data['opis']) && $soba->opis !== $data['opis'][$sobaId]) {
                $payload['opis'] = $data['opis'][$sobaId];
            }
            if ($payload) $soba->update($payload);

            // per-day capacity override
            SobaInventory::updateOrCreate(
                ['soba_id' => $soba->id, 'date' => $date],
                ['free_rooms' => $free]
            );
        }

        return redirect()
            ->route('menadzer.sobe.index', ['date' => $date])
            ->with('ok','Izmene sačuvane.');
    }

    /* ---------- Helpers ---------- */

    protected function guardRole(array $roles)
    {
        if (!auth()->check() || !in_array(optional(auth()->user()->role)->naziv_role, $roles)) {
            abort(403);
        }
    }

    /** How many rooms this type has available BEFORE booked reservations on a date. */
    protected function dayCapacityBaseline(Soba $soba, Carbon $date): int
    {
        $inv = SobaInventory::where('soba_id',$soba->id)
            ->whereDate('date',$date)->value('free_rooms');

        return (int) ($inv === null ? $soba->ukupno_soba : $inv);
    }

    /** Remaining capacity after subtracting active bookings for that date. */
    protected function remainingForDate(Soba $soba, Carbon $date): int
    {
        $cap = $this->dayCapacityBaseline($soba, $date);

        $booked = Rezervacija::where('soba_id', $soba->id)
            ->whereHas('status', fn($q)=>$q->where('naziv_statusa','!=','Otkazana'))
            ->whereDate('datum_od','<=',$date)
            ->whereDate('datum_do','>',$date)
            ->count();

        return max(0, $cap - $booked);
    }
}
