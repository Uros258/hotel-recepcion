<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Dodaj rezervaciju</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('recepcija.rezervacije.pick') }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-7 gap-3 items-end">
                    <div class="md:col-span-2">
                        <x-input-label for="ime" value="Ime" />
                        <x-text-input id="ime" type="text" name="ime" class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('ime')" class="mt-1" />
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label for="prezime" value="Prezime" />
                        <x-text-input id="prezime" type="text" name="prezime" class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('prezime')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="phone" value="Telefon" />
                        <x-text-input id="phone" type="text" name="phone" class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label for="email" value="Email" />
                        <x-text-input id="email" type="email" name="email" class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="broj_osoba" value="Broj osoba" />
                        <x-text-input id="broj_osoba" type="number" min="1" name="broj_osoba" class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('broj_osoba')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="tip_sobe" value="Tip sobe" />
                        <select id="tip_sobe" name="tip_sobe" class="mt-1 block w-full border rounded px-2 py-2" required>
                            <option value="" hidden>— odaberite —</option>
                            @foreach($tipovi as $t)
                                <option value="{{ $t }}">{{ $t }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('tip_sobe')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="datum_od" value="Datum od" />
                        <x-text-input id="datum_od" type="date" name="datum_od" class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('datum_od')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="datum_do" value="Datum do" />
                        <x-text-input id="datum_do" type="date" name="datum_do" class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('datum_do')" class="mt-1" />
                    </div>

                    <div class="md:col-span-7">
                        <x-input-label for="napomena" value="Napomena" />
                        <textarea id="napomena" name="napomena" rows="2" class="mt-1 block w-full border rounded px-3 py-2"></textarea>
                        <x-input-error :messages="$errors->get('napomena')" class="mt-1" />
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('recepcija.index') }}" class="px-4 py-2 rounded bg-rose-100 text-rose-700">Otkaži</a>
                        <button type="submit" class="px-4 py-2 rounded bg-emerald-600 text-black">
                            Dodeli sobu
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
