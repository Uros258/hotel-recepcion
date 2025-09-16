<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Ponuda soba</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($sobe as $soba)
                    <div class="card overflow-hidden">
                        <div class="h-40 bg-gray-100 flex items-center justify-center">
                            @if($soba->slika)
                                <img src="{{ asset($soba->slika) }}" alt="Slika sobe {{ $soba->broj_sobe }}" class="h-full w-full object-cover">
                            @else
                                <span class="text-gray-400">[ nema slike ]</span>
                            @endif
                        </div>
                        <div class="p-4">
                            <div class="text-lg font-semibold">{{ $soba->tip_sobe }}</div>
                            <div class="text-sm text-gray-500">Cena: {{ number_format($soba->cena,2) }} RSD</div>

                            <div class="mt-4 flex gap-2 justify-end">
                                @auth
                                    <a href="{{ route('rezervacijas.create', ['soba_id'=>$soba->id]) }}"
                                       class="px-3 py-1.5 rounded border text-sm">
                                        Rezerviši
                                    </a>
                                @else
                                    <a href="{{ route('login') }}"
                                       class="px-3 py-1.5 rounded border text-sm">
                                        Rezerviši
                                    </a>
                                @endauth

                                <a href="{{ route('sobas.show', $soba) }}"
                                   class="px-3 py-1.5 rounded border text-sm">
                                    Detalji
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <footer class="bg-gray-50 border-t border-gray-200 mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">UROS</h3>
                    <div class="space-y-2 text-gray-600">
                        <p>Hotel Uros, Kralja Milana 27</p>
                        <p>+381 11 36 40 425</p>
                        <p>rezervacije@hotelurosbelgrade.com</p>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Pratite nas</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="#" class="text-blue-600 hover:underline">Instagram</a>
                        <a href="#" class="text-blue-600 hover:underline">Facebook</a>
                        <a href="#" class="text-blue-600 hover:underline">X</a>
                        <a href="#" class="text-red-600 hover:underline">Youtube</a>
                    </div>
                </div>
            </div>
        </footer>

            </div>
        </div>
    </div>
</x-app-layout>
