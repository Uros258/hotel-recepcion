<x-app-layout>
  <div class="max-w-5xl mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-4">Moje rezervacije</h1>

    @if($list->count())
      <div class="overflow-x-auto rounded border">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-50">
            <tr>
              <th class="text-left p-3">Period</th>
              <th class="text-left p-3">Soba</th>
              <th class="text-left p-3">Osoba</th>
              <th class="text-left p-3">Status</th>
              <th class="text-left p-3">Akcije</th>
            </tr>
          </thead>
          <tbody>
            @foreach($list as $r)
              <tr class="border-t">
                <td class="p-3">{{ $r->datum_od }} – {{ $r->datum_do }}</td>
                <td class="p-3">{{ $r->soba->broj_sobe }} ({{ $r->soba->tip_sobe }})</td>
                <td class="p-3">{{ $r->broj_osoba }}</td>
                <td class="p-3">{{ $r->status->naziv_statusa }}</td>
                <td class="p-3">
                  <a href="{{ route('rezervacijas.show',$r) }}" class="px-3 py-1 rounded bg-gray-800 text-white">Detalji</a>
                  <form action="/rezervacije/{{ $r->id }}/otkazi" method="post" class="inline-block ms-2" onsubmit="return confirm('Otkazati rezervaciju?')">
                    @csrf
                    <button class="px-3 py-1 rounded bg-red-600 text-white">Otkaži</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="mt-4">{{ $list->links() }}</div>
    @else
      <p>Nemate rezervacija.</p>
    @endif
  </div>
</x-app-layout>
