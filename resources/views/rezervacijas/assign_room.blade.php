<x-app-layout>
    <x-slot name="header">Dodela sobe</x-slot>

    <div class="max-w-3xl mx-auto p-6">
        @if(session('ok')) <div class="text-green-700">{{ session('ok') }}</div> @endif

        <div class="mb-4">
            <div><strong>Rezervacija #{{ $rezervacija->id }}</strong></div>
            <div>Tip sobe: {{ $rezervacija->soba->tip_sobe }}</div>
            <div>Period: {{ \Illuminate\Support\Carbon::parse($rezervacija->datum_od)->format('d.m.Y') }} -
                {{ \Illuminate\Support\Carbon::parse($rezervacija->datum_do)->format('d.m.Y') }}</div>
            <div>Trenutno dostupno kroz ceo period: <strong>{{ $available }}</strong></div>
        </div>

        @if($available < 1)
            <div class="text-red-600">Nema kapaciteta za dodelu.</div>
        @else
            <form method="POST" action="{{ route('rezervacijas.assign_room', $rezervacija) }}">
                @csrf
                <x-primary-button>Dodeli / Potvrdi</x-primary-button>
            </form>
        @endif
    </div>
</x-app-layout>
