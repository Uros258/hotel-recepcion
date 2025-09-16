<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Izmena soba') }}
            </h2>
            <div class="text-sm text-gray-500">
                Datum: <span class="font-medium">{{ $date->toDateString() }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <form method="post" action="{{ route('menadzer.sobe.update') }}" class="bg-white overflow-hidden shadow-sm rounded-xl">
                @csrf
                <input type="hidden" name="date" value="{{ $date->toDateString() }}">

                <div class="p-6">
                    <table class="w-full text-sm">
                        <thead>
                        <tr class="text-left text-gray-500">
                            <th class="py-2">Tip sobe</th>
                            <th class="py-2">Cena (RSD)</th>
                            <th class="py-2">Ukupno</th>
                            <th class="py-2">Slobodne sobe (na datum)</th>
                            <th class="py-2">Opis</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($sobas as $soba)
                            @php
                                $v = $values[$soba->id];
                            @endphp
                            <tr class="border-t">
                                <td class="py-2">{{ $soba->tip_sobe }}</td>
                                <td class="py-2">
                                    <input type="number" step="0.01" name="price[{{ $soba->id }}]"
                                        value="{{ old("price.{$soba->id}", $v['price']) }}"
                                        class="w-24 border rounded-md px-2 py-1">
                                </td>
                                <td class="py-2">{{ $soba->ukupno_soba }}</td>
                                <td class="py-2">
                                    <select name="free[{{ $soba->id }}]" class="border rounded-md px-2 py-1">
                                        @for ($i = 0; $i <= $v['max']; $i++)
                                            <option value="{{ $i }}" @selected((int)old("free.{$soba->id}", $v['free']) === $i)>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </td>
                                <td class="py-2">
                                    <textarea name="opis[{{ $soba->id }}]"
                                            class="w-full border rounded-md px-2 py-1"
                                            rows="1">{{ old("opis.{$soba->id}", $v['opis']) }}</textarea>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="mt-6 flex items-center gap-3">
                        <a href="{{ route('menadzer.sobe.index', ['date' => $date->toDateString()]) }}"
                           class="px-4 py-2 rounded-lg border text-gray-700 hover:bg-gray-50">
                            Otkaži izmene
                        </a>
                        <button class="px-3 py-1.5 rounded border text-sm">
                            Sačuvaj izmene
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
