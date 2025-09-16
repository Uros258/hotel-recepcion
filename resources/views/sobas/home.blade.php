<x-app-layout>
  <div class="max-w-3xl mx-auto p-6">
    <a href="{{ route('sobas.index') }}" class="text-sm text-blue-600">&larr; Nazad na sve sobe</a>
    <h1 class="text-2xl font-semibold mt-2 mb-4">
      Soba {{ $soba->broj_sobe }} ({{ $soba->tip_sobe }})
    </h1>

    <ul class="mb-4 space-y-1">
      <li><strong>Cena:</strong> {{ $soba->cena }} RSD/noć</li>
      <li><strong>Status sobe:</strong> {{ $soba->status_sobe }}</li>
    </ul>

    @auth
      <a href="{{ route('rezervacijas.create', ['soba_id'=>$soba->id]) }}" class="px-4 py-2 rounded bg-blue-600 text-white">Rezerviši</a>
    @else
      <a href="{{ route('login') }}" class="px-4 py-2 rounded bg-blue-600 text-white">Prijavi se za rezervaciju</a>
    @endauth
  </div>
</x-app-layout>
