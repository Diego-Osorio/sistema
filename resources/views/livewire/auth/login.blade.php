<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}; ?>

<div class="w-full max-w-md mx-auto p-8 bg-white/80 dark:bg-zinc-800/60 backdrop-blur-md border border-zinc-200 dark:border-zinc-700 rounded-3xl shadow-2xl flex flex-col gap-8 transition-all">
    <x-auth-header 
        :title="__('Iniciar Sesion')" 
        :description="__('Ingresa tu Correo  y Contraseña para Iniciar Sesion')" 
    />

    <!-- Session Status -->
    <x-auth-session-status 
        class="text-center text-sm text-emerald-600 dark:text-emerald-400" 
        :status="session('status')" 
    />

    <form wire:submit="login" class="flex flex-col gap-5">
        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('Correo Electrónico')"
            type="email"
            required
            autofocus
            autocomplete="email"
            placeholder="correo@example.com"
            class="bg-white/70 dark:bg-zinc-900/70 border-0 shadow-inner rounded-xl focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-700 transition-all"
        />

        <!-- Password -->
        <div class="relative">
            <flux:input
                wire:model="password"
                :label="__('Contraseña')"
                type="password"
                required
                autocomplete="current-password"
                placeholder="••••••••"
                class="bg-white/70 dark:bg-zinc-900/70 border-0 shadow-inner rounded-xl focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-700 transition-all"
            />

            @if (Route::has('password.request'))
                <flux:link 
                    class="absolute right-0 top-0 mt-1 text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 transition-all" 
                    :href="route('password.request')" 
                    wire:navigate
                >
                    {{ __('Olvidaste tu Contraseña?') }}
                </flux:link>
            @endif
        </div>

        <!-- Remember Me -->
        <flux:checkbox 
            wire:model="remember" 
            :label="__('Recuerdame')" 
            class="text-sm text-zinc-700 dark:text-zinc-300"
        />

        <div class="pt-2">
            <flux:button 
                variant="primary" 
                type="submit" 
                class="w-full py-3 text-base font-semibold rounded-xl bg-gradient-to-tr from-indigo-500 to-purple-600 text-white shadow-lg hover:from-indigo-600 hover:to-purple-700 transition-all"
            >
                {{ __('Ingresar') }}
            </flux:button>
        </div>
    </form>

    @if (Route::has('register'))
        <div class="text-center text-sm text-zinc-700 dark:text-zinc-400">
            {{ __("No estas Registardo?") }}
            <flux:link 
                :href="route('register')" 
                wire:navigate 
                class="text-indigo-600 hover:underline dark:text-indigo-400"
            >
                {{ __('Registrate aqui') }}
            </flux:link>
        </div>
    @endif
</div>
