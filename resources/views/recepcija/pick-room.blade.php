<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Odabir sobe — {{ $data['datum_od'] }} do {{ $data['datum_do'] }} ({{ $data['tip_sobe'] }})
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('recepcija.rezervacije.store') }}" class="bg-white shadow rounded p-4">
                @csrf

                @foreach($data as $k => $v)
                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                @endforeach

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr class="text-left">
                                <th class="px-4 py-2">Broj sobe</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Izbor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rooms as $room)
                                @php $free = $slobodne[$room->id]; @endphp
                                <tr class="border-t {{ $free ? '' : 'opacity-60' }}">
                                    <td class="px-4 py-2">{{ $room->broj_sobe }}</td>
                                    <td class="px-4 py-2">{{ $free ? 'slobodna' : 'zauzeta' }}</td>
                                    <td class="px-4 py-2">
                                        @if($free)
                                            <input type="radio" name="soba_id" value="{{ $room->id }}" required>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 flex justify-end gap-2">
                    <a href="{{ route('recepcija.rezervacije.create') }}"
                       class="px-4 py-2 rounded border">Nazad</a>

                    <button type="submit" class="px-4 py-2 rounded bg-emerald-600 text-black">
                        Dodeli sobu
                    </button>
                </div>
            </form>

            <div class="mt-10 border-t pt-6 text-sm text-gray-700">
                <div>Hotel Uros, Kralja Milana 27</div>
                <div>Phone: +381 11 36 40 425</div>
                <div>Email: rezervacije@hotelurosbelgrade.com</div>
            </div>
        </div>
    </div>
</x-app-layout>
