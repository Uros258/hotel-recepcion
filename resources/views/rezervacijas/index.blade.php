<x-app-layout>
  <div class="max-w-6xl mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-4">Sve rezervacije</h1>

    @if($rezervacijas->count())
      <div class="overflow-x-auto rounded border">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-50">
            <tr>
              <th class="text-left p-3">ID</th>
              <th class="text-left p-3">Gost</th>
              <th class="text-left p-3">Soba</th>
              <th class="text-left p-3">Period</th>
              <th class="text-left p-3">Status</th>
              <th class="text-left p-3">Akcije</th>
            </tr>
          </thead>
          <tbody>
            @foreach($rezervacijas as $r)
              <tr class="border-t">
                <td class="p-3">{{ $r->id }}</td>
                <td class="p-3">{{ $r->user?->name }} {{ $r->user?->surname }}</td>
                <td class="p-3">{{ $r->soba->broj_sobe }} ({{ $r->soba->tip_sobe }})</td>
                <td class="p-3">{{ $r->datum_od }} – {{ $r->datum_do }}</td>
                <td class="p-3">{{ $r->status->naziv_statusa }}</td>
                <td class="p-3">
                  <a href="{{ route('rezervacijas.show',$r) }}" class="px-3 py-1 rounded bg-gray-800 text-white">Detalji</a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="mt-4">{{ $rezervacijas->links() }}</div>
    @else
      <p>Nema rezervacija.</p>
    @endif
  </div>
</x-app-layout>
