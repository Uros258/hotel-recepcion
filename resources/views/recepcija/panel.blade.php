<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Rezervacije hotela</h2>
            <form method="GET" class="flex items-center gap-2">
                <input type="date" name="date" value="{{ $date }}" class="border rounded px-2 py-1 text-sm">
                <button class="px-3 py-1.5 text-sm rounded border">Filter</button>
            </form>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('ok'))
                <div class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-2">
                    {{ session('ok') }}
                </div>
            @endif
            @if($errors->any())
                <div class="mb-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 px-4 py-2">
                    <ul class="list-disc ms-5">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="card p-0 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr class="text-left">
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">Gost</th>
                            <th class="px-4 py-2">Datum Od/Do</th>
                            <th class="px-4 py-2">Noćenja</th>
                            <th class="px-4 py-2">Tip sobe</th>
                            <th class="px-4 py-2">Broj osoba</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2 text-right">Akcije</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rezervacijas as $r)
                            <tr class="border-t">
                                <td class="px-4 py-2">{{ $r->id }}</td>
                                <td class="px-4 py-2">{{ $r->user->name }} {{ $r->user->surname }}</td>
                                <td class="px-4 py-2">{{ $r->datum_od->format('d.m.Y') }} – {{ $r->datum_do->format('d.m.Y') }}</td>
                                <td class="px-4 py-2">{{ $r->datum_od->diffInDays($r->datum_do) }}</td>
                                <td class="px-4 py-2">{{ $r->soba?->tip_sobe ?? '—' }}</td>
                                <td class="px-4 py-2">{{ $r->broj_osoba }}</td>
                                <td class="px-4 py-2">{{ $r->status->naziv_statusa }}</td>
                                <td class="px-4 py-2 text-right space-x-2">
                                    <form method="POST" action="{{ route('rezervacije.cancel', $r) }}" class="inline">
                                        @csrf
                                        <button class="px-3 py-1.5 text-sm rounded bg-rose-600 text-white"
                                                onclick="return confirm('Otkazati rezervaciju?')">
                                            Otkaži
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('rezervacije.checkin',$r) }}" class="inline">
                                        @csrf
                                        <button class="px-3 py-1.5 text-sm rounded border">Prijavi gosta</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td class="px-4 py-6 text-center text-gray-500" colspan="8">Nema rezervacija.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $rezervacijas->withQueryString()->links() }}</div>

            <div class="mt-10 border-t pt-6 text-sm text-gray-700">
                <div class="font-semibold">UROS</div>
                <div>Hotel Uros, Kralja Milana 27</div>
                <div>Phone: +381 11 36 40 425</div>
                <div>Email: rezervacije@hotelurosbelgrade.com</div>
            </div>
        </div>
    </div>
</x-app-layout>
