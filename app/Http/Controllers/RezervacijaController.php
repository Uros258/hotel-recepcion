<?php

namespace App\Http\Controllers;

use App\Models\Rezervacija;
use App\Models\Soba;
use App\Models\Status;
use App\Models\SobaInventory;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RezervacijaController extends Controller
{

    public function index()
    {
        $rezervacijas = Rezervacija::with('soba','user','status')->latest()->paginate(15);
        return view('rezervacijas.index', compact('rezervacijas'));
    }

    public function create()
    {
        $sobe = Soba::orderBy('tip_sobe')->get();
        return view('rezervacijas.create', compact('sobe'));
    }

    public function store(Request $req)
    {
        $data = $req->validate([
            'soba_id'    => ['required','exists:sobas,id'],
            'datum_od'   => ['required','date','after_or_equal:today'],
            'datum_do'   => ['required','date','after:datum_od'],
            'broj_osoba' => ['required','integer','min:1'],
            'napomena'   => ['nullable','string'],
        ]);

        $this->assertCapacity($data['soba_id'], $data['datum_od'], $data['datum_do']);

        $rez = Rezervacija::create([
            ...$data,
            'user_id'   => $req->user()->id,
            'status_id' => Status::where('naziv_statusa','Kreirana')->value('id'),
        ]);

        return redirect()->route('rezervacijas.show', $rez)->with('ok','Rezervacija kreirana');
    }


    public function panel()
    {
        $this->guardRole(['recepcioner','menadzer']);
        $rezervacijas = Rezervacija::with('soba','user','status')->latest()->paginate(15);
        $date = now()->toDateString(); // used by the panel view footer/header if needed
        return view('recepcija.panel', compact('rezervacijas','date'));
    }

    public function createByStaff()
    {
        $this->guardRole(['recepcioner','menadzer']);

        $tipovi = Soba::query()->select('tip_sobe')->distinct()->orderBy('tip_sobe')->pluck('tip_sobe');
        return view('recepcija.create', compact('tipovi'));
    }

    public function pickRoom(Request $req)
    {
        $this->guardRole(['recepcioner','menadzer']);

        $data = $req->validate([
            'tip_sobe'   => ['required','string'],
            'datum_od'   => ['required','date','after_or_equal:today'],
            'datum_do'   => ['required','date','after:datum_od'],
            'broj_osoba' => ['required','integer','min:1'],
            'napomena'   => ['nullable','string'],
            'ime'        => ['nullable','string'],
            'telefon'    => ['nullable','string'],
            'email'      => ['nullable','email'],
        ]);

        $rooms = Soba::where('tip_sobe',$data['tip_sobe'])->orderBy('broj_sobe')->get();
        $slobodne = [];
        foreach ($rooms as $room) {
            $slobodne[$room->id] = $this->availableForRange($room->id, $data['datum_od'], $data['datum_do']) > 0;
        }

        return view('recepcija.pick-room', [
            'data'     => $data,
            'rooms'    => $rooms,
            'slobodne' => $slobodne,
        ]);
    }

    public function storeByStaff(Request $req)
    {
        $this->guardRole(['recepcioner','menadzer']);

        $data = $req->validate([
            'soba_id'    => ['required','exists:sobas,id'],
            'datum_od'   => ['required','date','after_or_equal:today'],
            'datum_do'   => ['required','date','after:datum_od'],
            'broj_osoba' => ['required','integer','min:1'],
            'napomena'   => ['nullable','string'],
            'ime'        => ['nullable','string'],
            'telefon'    => ['nullable','string'],
            'email'      => ['nullable','email'],
        ]);

        $this->assertCapacity($data['soba_id'], $data['datum_od'], $data['datum_do']);

        Rezervacija::create([
            'soba_id'    => $data['soba_id'],
            'datum_od'   => $data['datum_od'],
            'datum_do'   => $data['datum_do'],
            'broj_osoba' => $data['broj_osoba'],
            'napomena'   => $data['napomena'] ?? null,
            'user_id'    => $req->user()->id, // staff user creating it
            'status_id'  => Status::where('naziv_statusa','Kreirana')->value('id'),
        ]);

        return redirect()->route('recepcija.index')->with('ok','Rezervacija sačuvana.');
    }


    public function show(Rezervacija $rezervacija)
    {
        $rezervacija->load('soba','status','user');
        return view('rezervacijas.show', compact('rezervacija'));
    }

    public function edit(Rezervacija $rezervacija)
    {
        $this->authorize('update', $rezervacija);
        $sobe = Soba::orderBy('tip_sobe')->get();
        return view('rezervacijas.edit', compact('rezervacija','sobe'));
    }

    public function update(Request $req, Rezervacija $rezervacija)
    {
        $data = $req->validate([
            'soba_id'    => ['required','exists:sobas,id'],
            'datum_od'   => ['required','date','after_or_equal:today'],
            'datum_do'   => ['required','date','after:datum_od'],
            'broj_osoba' => ['required','integer','min:1'],
            'napomena'   => ['nullable','string'],
        ]);

        $this->assertCapacity($data['soba_id'], $data['datum_od'], $data['datum_do'], $rezervacija->id);

        $rezervacija->update($data);
        return redirect()->route('rezervacijas.show',$rezervacija)->with('ok','Sačuvano');
    }


    public function myReservations()
    {
        $list = Rezervacija::with('soba','status')
            ->where('user_id', auth()->id())
            ->latest()->paginate(10);

        return view('rezervacijas.my', compact('list'));
    }

    public function cancel(Rezervacija $rezervacija)
    {
        abort_unless($rezervacija->user_id === auth()->id()
            || auth()->user()->role->naziv_role !== 'gost', 403);

        if (in_array($rezervacija->status->naziv_statusa, ['Prijavljen','Odjavljen'])) {
            abort(422, 'Ne može se otkazati nakon prijave/odjave.');
        }

        $rezervacija->update(['status_id'=> Status::where('naziv_statusa','Otkazana')->value('id')]);
        return back()->with('ok','Otkazano');
    }

    public function changeStatus(Request $req, Rezervacija $rezervacija)
    {
        $this->guardRole(['recepcioner','menadzer','admin']);
        $status = $req->validate(['status'=>'required|string'])['status'];
        $rezervacija->update(['status_id'=> Status::where('naziv_statusa',$status)->value('id')]);
        return back()->with('ok','Status ažuriran');
    }

    public function checkin(Rezervacija $rezervacija)  { return $this->changeTo($rezervacija,'Prijavljen'); }
    public function checkout(Rezervacija $rezervacija) { return $this->changeTo($rezervacija,'Odjavljen'); }

    protected function changeTo(Rezervacija $rez, string $name)
    {
        $this->guardRole(['recepcioner','menadzer','admin']);
        if ($rez->status->naziv_statusa==='Otkazana') abort(422,'Otkazana rezervacija.');
        $rez->update(['status_id'=> Status::where('naziv_statusa',$name)->value('id')]);
        return back()->with('ok',"Status: {$name}");
    }


    protected function guardRole(array $roles)
    {
        if (!auth()->check() || !in_array(optional(auth()->user()->role)->naziv_role, $roles)) {
            abort(403);
        }
    }

    protected function assertCapacity(int $sobaId, string $od, string $do, int $ignoreRezId = 0): void
    {
        $available = $this->availableForRange($sobaId, $od, $do, $ignoreRezId);

            if ($available === null) {
                return;
            }

            if ($available < 1) {
                abort(422, 'Nema dostupnog kapaciteta za izabrani period.');
            }

    }

    protected function availableForRange(int $sobaId, string $od, string $do, int $ignoreRezId = 0): int
    {
        $soba = Soba::findOrFail($sobaId);
        $from = Carbon::parse($od);
        $to   = Carbon::parse($do)->subDay(); 

        $minLeft = PHP_INT_MAX;

        for ($d = $from->copy(); $d <= $to; $d->addDay()) {
            $date = $d->toDateString();

                 $inv = SobaInventory::where('soba_id',$sobaId)
                    ->whereDate('date',$date)
                    ->first();

                    if ($inv && $inv->free_rooms !== null) {
                        $baseFree = (int)$inv->free_rooms;
                    } else {
                        $baseFree = (int)$soba->ukupno_soba;
                    }


            $busy = Rezervacija::where('soba_id',$sobaId)
                ->whereHas('status', fn($q)=>$q->whereNotIn('naziv_statusa',['Otkazana']))
                ->when($ignoreRezId, fn($q)=>$q->where('id','!=',$ignoreRezId))
                ->whereDate('datum_od','<=',$date)
                ->whereDate('datum_do','>',  $date)
                ->count();

            $left = max(0, $baseFree - $busy);
            $minLeft = min($minLeft, $left);
            if ($minLeft === 0) break;
        }

        return $minLeft === PHP_INT_MAX ? 0 : $minLeft;
    }
}
