<x-app-layout>
  <div class="max-w-3xl mx-auto p-6">
    @if (session('ok'))
      <div class="mb-4 rounded bg-green-100 p-3 text-green-800">{{ session('ok') }}</div>
    @endif

    <a href="{{ route('rezervacijas.index') }}" class="text-sm text-blue-600">&larr; Nazad na rezervacije</a>

    <h1 class="text-2xl font-semibold mt-2 mb-4">Rezervacija #{{ $rezervacija->id }}</h1>

    <div class="rounded border p-4 space-y-1 mb-4">
      <div><strong>Soba:</strong> {{ $rezervacija->soba->broj_sobe }} ({{ $rezervacija->soba->tip_sobe }})</div>
      <div><strong>Period:</strong> {{ $rezervacija->datum_od }} – {{ $rezervacija->datum_do }}</div>
      <div><strong>Broj osoba:</strong> {{ $rezervacija->broj_osoba }}</div>
      <div><strong>Status:</strong> {{ $rezervacija->status->naziv_statusa }}</div>
      <div><strong>Napomena:</strong> {{ $rezervacija->napomena ?? '—' }}</div>
    </div>

    <div class="flex gap-2">
      <a href="{{ route('rezervacijas.edit',$rezervacija) }}" class="px-3 py-2 rounded bg-gray-800 text-white text-sm">Izmeni</a>

      <form action="/rezervacije/{{ $rezervacija->id }}/otkazi" method="post" onsubmit="return confirm('Otkazati rezervaciju?')">
        @csrf
        <button class="px-3 py-2 rounded bg-red-600 text-white text-sm">Otkaži</button>
      </form>
    </div>
  </div>
</x-app-layout>
