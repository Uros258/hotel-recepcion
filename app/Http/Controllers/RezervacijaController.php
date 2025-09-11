<?php

namespace App\Http\Controllers;

use App\Models\Rezervacija;
use App\Models\Soba;
use App\Models\Status;
use Illuminate\Http\Request;

class RezervacijaController extends Controller
{
    public function index()
    {
        $rezervacijas = Rezervacija::with(['soba','user','status'])->latest()->paginate(15);
        return view('rezervacijas.index', compact('rezervacijas'));
    }

    public function create()
    {
        $sobe = Soba::orderBy('broj_sobe')->get();
        return view('rezervacijas.create', compact('sobe'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'soba_id'    => ['required','exists:sobas,id'],
            'datum_od'   => ['required','date','after_or_equal:today'],
            'datum_do'   => ['required','date','after:datum_od'],
            'broj_osoba' => ['required','integer','min:1'],
            'napomena'   => ['nullable','string'],
        ]);

        if ($this->hasConflict($data['soba_id'], $data['datum_od'], $data['datum_do'])) {
            return back()->withErrors(['datum_od' => 'Soba je zauzeta za izabrani period.'])->withInput();
        }

        $rez = Rezervacija::create([
            ...$data,
            'user_id'   => $request->user()->id,
            'status_id' => Status::where('naziv_statusa','Kreirana')->value('id'),
        ]);

        return redirect()->route('rezervacijas.show', $rez)->with('ok', 'Rezervacija kreirana');
    }

    public function show(Rezervacija $rezervacija)
    {
        $rezervacija->load(['soba','status','user']);
        return view('rezervacijas.show', compact('rezervacija'));
    }

    public function edit(Rezervacija $rezervacija)
    {
        if (!auth()->check()) abort(403);
        $u = auth()->user();
        if ($u->role?->naziv_role === 'gost' && $rezervacija->user_id !== $u->id) abort(403);

        $sobe = Soba::orderBy('broj_sobe')->get();
        return view('rezervacijas.edit', compact('rezervacija','sobe'));
    }

    public function update(Request $request, Rezervacija $rezervacija)
    {
        $data = $request->validate([
            'soba_id'    => ['required','exists:sobas,id'],
            'datum_od'   => ['required','date','after_or_equal:today'],
            'datum_do'   => ['required','date','after:datum_od'],
            'broj_osoba' => ['required','integer','min:1'],
            'napomena'   => ['nullable','string'],
        ]);

        if ($this->hasConflict($data['soba_id'], $data['datum_od'], $data['datum_do'], $rezervacija->id)) {
            return back()->withErrors(['datum_od' => 'Soba je zauzeta za izabrani period.'])->withInput();
        }

        $rezervacija->update($data);
        return redirect()->route('rezervacijas.show', $rezervacija)->with('ok', 'Sačuvano');
    }

    public function destroy(Rezervacija $rezervacija)
    {
        $u = auth()->user();
        if ($u->role?->naziv_role === 'gost' && $rezervacija->user_id !== $u->id) abort(403);

        $rezervacija->delete();
        return redirect()->route('rezervacijas.index')->with('ok','Obrisano');
    }


    public function myReservations()
    {
        $list = Rezervacija::with(['soba','status'])
            ->where('user_id', auth()->id())
            ->latest()->paginate(10);

        return view('rezervacijas.my', compact('list'));
    }

    public function cancel(Rezervacija $rezervacija)
    {
        abort_unless($rezervacija->user_id === auth()->id() || auth()->user()->role->naziv_role !== 'gost', 403);

        if (in_array($rezervacija->status->naziv_statusa, ['Prijavljen','Odjavljen'])) {
            abort(422, 'Ne može se otkazati nakon prijave/odjave.');
        }

        $rezervacija->update([
            'status_id' => Status::where('naziv_statusa','Otkazana')->value('id')
        ]);

        return back()->with('ok','Otkazano');
    }

    public function changeStatus(Request $request, Rezervacija $rezervacija)
    {
        $this->guardRole(['recepcioner','admin']);

        $data = $request->validate([
            'status' => ['required','string','in:Kreirana,Potvrđena,Prijavljen,Odjavljen,Otkazana'],
        ]);

        $statusId = Status::where('naziv_statusa', $data['status'])->value('id');
        abort_if(!$statusId, 422, 'Nepoznat status.');

        $rezervacija->update(['status_id' => $statusId]);
        return back()->with('ok','Status ažuriran');
    }

    public function checkin(Rezervacija $rezervacija)
    {
        return $this->changeTo($rezervacija, 'Prijavljen');
    }

    public function checkout(Rezervacija $rezervacija)
    {
        return $this->changeTo($rezervacija, 'Odjavljen');
    }


    protected function changeTo(Rezervacija $rez, string $name)
    {
        $this->guardRole(['recepcioner','admin']);
        if ($rez->status->naziv_statusa === 'Otkazana') abort(422, 'Otkazana rezervacija.');
        $rez->update(['status_id' => Status::where('naziv_statusa',$name)->value('id')]);
        return back()->with('ok', "Status: {$name}");
    }

    protected function guardRole(array $roles)
    {
        if (!auth()->check() || !in_array(auth()->user()->role->naziv_role, $roles)) abort(403);
    }

    protected function hasConflict(int $sobaId, string $from, string $to, ?int $ignoreId = null): bool
    {
        return Rezervacija::when($ignoreId, fn($q)=>$q->where('id','!=',$ignoreId))
            ->where('soba_id', $sobaId)
            ->whereHas('status', fn($q)=>$q->where('naziv_statusa','!=','Otkazana'))
            ->where(function($q) use ($from,$to){
                $q->whereBetween('datum_od', [$from,$to])
                  ->orWhereBetween('datum_do', [$from,$to])
                  ->orWhere(fn($z)=>$z->where('datum_od','<=',$from)->where('datum_do','>=',$to));
            })
            ->exists();
    }
}
