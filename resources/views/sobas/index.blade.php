<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sobe') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($sobas as $soba)
                    <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6 flex flex-col">
                        <div class="h-40 w-full bg-gray-100 rounded-lg mb-4 flex items-center justify-center">
                            <span class="text-gray-400">Slika sobe</span>
                        </div>
                        <h3 class="text-lg font-semibold mb-1">{{ $soba->tip_sobe }}</h3>
                        <p class="text-sm text-gray-500 mb-4">Cena / noć: <span class="font-medium">{{ number_format($soba->cena,2) }} €</span></p>

                        <div class="mt-auto flex items-center justify-between">
                            <a href="{{ route('sobas.show', $soba) }}"
                               class="px-3 py-2 rounded-lg border text-sm hover:bg-gray-50">Detalji</a>

                            @auth
                                <a href="{{ route('rezervacijas.create', ['soba_id' => $soba->id]) }}"
                                   class="px-3 py-2 rounded-lg bg-emerald-600 text-white text-sm hover:bg-emerald-700">
                                    Rezerviši
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                   class="px-3 py-2 rounded-lg bg-emerald-600 text-white text-sm hover:bg-emerald-700">
                                    Rezerviši
                                </a>
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $sobas->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
