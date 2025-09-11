<x-app-layout>
  <div class="max-w-xl mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-4">Nova rezervacija</h1>

    @if ($errors->any())
      <div class="mb-4 rounded bg-red-100 p-3 text-red-800">
        <ul class="list-disc ms-5">
          @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('rezervacijas.store') }}" class="space-y-4">
      @csrf

      <div>
        <label class="block text-sm font-medium mb-1">Soba</label>
        <select name="soba_id" class="w-full rounded border p-2" required>
          @foreach(\App\Models\Soba::orderBy('broj_sobe')->get() as $s)
            <option value="{{ $s->id }}" @selected(old('soba_id', request('soba_id')) == $s->id)>
              {{ $s->broj_sobe }} ({{ $s->tip_sobe }}) — {{ $s->cena }} RSD/noć
            </option>
          @endforeach
        </select>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1">Datum od</label>
          <input type="date" name="datum_od" value="{{ old('datum_od') }}" class="w-full rounded border p-2" required>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Datum do</label>
          <input type="date" name="datum_do" value="{{ old('datum_do') }}" class="w-full rounded border p-2" required>
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Broj osoba</label>
        <input type="number" name="broj_osoba" min="1" value="{{ old('broj_osoba',1) }}" class="w-full rounded border p-2" required>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Napomena (opciono)</label>
        <textarea name="napomena" class="w-full rounded border p-2" rows="3">{{ old('napomena') }}</textarea>
      </div>

      <button class="px-4 py-2 rounded bg-blue-600 text-white">Potvrdi rezervaciju</button>
    </form>
  </div>
</x-app-layout>
