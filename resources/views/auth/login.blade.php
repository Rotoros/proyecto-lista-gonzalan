<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-white to-indigo-100">

        <div class="w-full max-w-md bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/40">

            {{-- TÍTOL --}}
            <div class="text-center mb-6">
                <h2 class="text-3xl font-bold text-gray-800">
                    Iniciar sessió
                </h2>
                <p class="text-sm text-gray-500 mt-2">
                    Accedeix a les teves llistes
                </p>
            </div>

            {{-- STATUS --}}
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- EMAIL --}}
                <div>
                    <x-input-label for="email" value="Correu electrònic" class="text-sm text-gray-700" />

                    <x-text-input
                        id="email"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required
                        autofocus
                        autocomplete="username"
                        class="mt-1 w-full rounded-2xl border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-sm shadow-sm"
                        placeholder="exemple@mail.com"
                    />

                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-600 text-xs" />
                </div>

                {{-- PASSWORD --}}
                <div>
                    <x-input-label for="password" value="Contrasenya" class="text-sm text-gray-700" />

                    <x-text-input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        class="mt-1 w-full rounded-2xl border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-sm shadow-sm"
                        placeholder="••••••••"
                    />

                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-600 text-xs" />
                </div>

                {{-- RECORDAR --}}
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-gray-600">Recorda’m</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-blue-600 hover:underline">
                            Has oblidat la contrasenya?
                        </a>
                    @endif
                </div>

                {{-- BOTÓ --}}
                <div class="pt-2">
                    <x-primary-button class="w-full py-2.5 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold text-sm shadow-md transition transform hover:-translate-y-0.5">
                        Iniciar sessió
                    </x-primary-button>
                </div>

                {{-- REGISTRE --}}
                <div class="text-center text-sm">
                    <span class="text-gray-500">Encara no tens compte?</span>
                    <a href="{{ route('register') }}" class="text-blue-600 font-medium hover:underline">
                        Registra’t
                    </a>
                </div>

            </form>
        </div>
    </div>
</x-guest-layout>