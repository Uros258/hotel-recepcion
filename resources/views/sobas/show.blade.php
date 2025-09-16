<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $soba->tip_sobe }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm rounded-xl">
                <div class="mb-4">
                    @if($soba->slika)
                        <img src="{{ asset($soba->slika) }}" alt="Slika sobe {{ $soba->broj_sobe }}" class="w-full h-64 object-cover rounded">
                    @else
                        <span class="text-gray-400">[ nema slike ]</span>
                    @endif
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm">Cena / noć</div>
                            <div class="text-2xl font-bold">{{ number_format($soba->cena,2) }} RSD</div>
                        </div>
                        <div class="text-right">
                        </div>
                    </div>
                </div>
            </div>

            @if($soba->opis)
                <p class="mt-3 text-gray-700">{{ $soba->opis }}</p>
            @endif

            <div class="flex justify-end">
                @auth
                    <a href="{{ route('rezervacijas.create', ['soba_id' => $soba->id]) }}"
                       class="px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">
                        Rezerviši
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">
                        Rezerviši
                    </a>
                @endauth
            </div>
        </div>
    </div>
</x-app-layout>
