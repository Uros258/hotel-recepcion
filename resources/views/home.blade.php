<x-app-layout>
  <div class="max-w-5xl mx-auto p-6">
    @if (session('ok'))
      <div class="mb-4 rounded bg-green-100 p-3 text-green-800">{{ session('ok') }}</div>
    @endif

    <h1 class="text-2xl font-semibold mb-4">Sobe</h1>

    @if($sobe->count())
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach($sobe as $soba)
          <div class="rounded border p-4">
            <div class="font-medium">Soba {{ $soba->broj_sobe }} ({{ $soba->tip_sobe }})</div>
            <div class="text-sm text-gray-600 mb-2">Cena: {{ $soba->cena }} RSD/noć</div>
            <div class="flex gap-2">
              <a href="{{ route('sobas.show',$soba) }}" class="px-3 py-1 rounded bg-gray-800 text-white text-sm">Detalji</a>
              @auth
                <a href="{{ route('rezervacijas.create', ['soba_id'=>$soba->id]) }}" class="px-3 py-1 rounded bg-blue-600 text-white text-sm">Rezerviši</a>
              @else
                <a href="{{ route('login') }}" class="px-3 py-1 rounded bg-blue-600 text-white text-sm">Prijavi se za rezervaciju</a>
              @endauth
            </div>
          </div>
        @endforeach
      </div>

      <div class="mt-6">{{ $sobe->links() }}</div>
    @else
      <p>Nema unetih soba.</p>
    @endif
  </div>
</x-app-layout>
