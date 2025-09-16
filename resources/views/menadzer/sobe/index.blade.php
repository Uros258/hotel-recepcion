<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Sobe hotela') }}
            </h2>
            <form method="get" class="flex items-center gap-2">
                <input type="date" name="date" value="{{ $date->toDateString() }}" class="border rounded-md px-2 py-1 text-sm">
                <button class="px-3 py-1.5 rounded-md bg-gray-800 text-white text-sm">Prikaži</button>
            </form>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if (session('ok'))
                <div class="mb-4 rounded-md bg-emerald-50 border border-emerald-200 px-4 py-3 text-emerald-800">
                    {{ session('ok') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm rounded-xl">
                <div class="p-6">
                    <table class="w-full text-sm">
                        <thead>
                        <tr class="text-left text-gray-500">
                            <th class="py-2">Tip sobe</th>
                            <th class="py-2">Cena (RSD)</th>
                            <th class="py-2">Ukupno</th>
                            <th class="py-2">Slobodne</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($sobas as $r)
                            <tr class="border-t">
                                <td class="py-2">{{ $r->tip_sobe }}</td>
                                <td class="py-2">{{ number_format($r->cena,2) }}</td>
                                <td class="py-2">{{ $r->ukupno_soba }}</td>
                                <td class="py-2">{{ $r->free_today }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="mt-6">
                        <a href="{{ route('menadzer.sobe.edit', ['date' => $date->toDateString()]) }}"
                           class="px-3 py-1.5 rounded border text-sm">
                            Izmeni
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
