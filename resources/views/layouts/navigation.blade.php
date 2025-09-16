<nav class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('home') }}" class="text-xl font-bold text-blue-600">
                    HOTEL UROS
                    </a>
                 </div>
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        Početna
                    </x-nav-link>

                    @auth
                        @if(in_array(auth()->user()->role?->naziv_role, ['recepcioner','menadzer']))
                            <x-nav-link :href="route('recepcija.index')" :active="request()->routeIs('recepcija.index')">
                                Recepcionerski panel
                            </x-nav-link>
                            <x-nav-link :href="route('recepcija.rezervacije.create')" :active="request()->routeIs('recepcija.rezervacije.create')">
                                Dodaj rezervaciju
                            </x-nav-link>
                        @endif
                        @if(auth()->user()->role?->naziv_role === 'gost')
                            <x-nav-link :href="route('rezervacijas.my')" :active="request()->routeIs('rezervacijas.my')">
                                Moje rezervacije
                            </x-nav-link>
                        @endif

                        @if(in_array(Auth::user()->role->naziv_role, ['menadzer','admin']))
                            <x-nav-link :href="route('menadzer.sobe.index')" :active="request()->routeIs('menadzer.sobe.*')">
                                Sobe hotela
                            </x-nav-link>
                        @endif
                    @endauth
                </div>

            @auth
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                        <path d="M5.516 7.548L10 12.032l4.484-4.484L16 8.564l-6 6-6-6z"/>
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                Profil
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    Odjava
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            @endauth

            @guest
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <a href="{{ route('login') }}" class="text-sm me-4">Prijava</a>
                    <a href="{{ route('register') }}" class="text-sm">Registracija</a>
                </div>
            @endguest

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                Početna
            </x-responsive-nav-link>
        </div>

        @auth
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        Profil
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            Odjava
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @endauth

        @guest
            <div class="flex items-center space-x-6">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-gray-900">Početna</a>
                @guest
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800">Prijava</a>
                    <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800">Registracija</a>
                @else
                    <a href="{{ route('rezervacijas.my') }}" class="text-gray-700 hover:text-gray-900">Moje rezervacije</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-red-600 hover:text-red-800">Logout</button>
                    </form>
                @endguest
            </div>
        @endguest
    </div>
</nav>
