<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="bg-gradient-to-r from-white to-blue-100 border-b rounded-b-3xl sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-1 sm:px-2 lg:px-4">
        <div class="flex justify-between h-20">
            <div class="flex">
                <!-- Logo -->
                <div href="{{ route('public.dashboard') }}" wire:navigate class="shrink-0 flex flex-none items-center">
                    <a><x-application-logo class="block h-9 w-auto fill-current text-gray-800" /></a>
                    <a class="text-xl font-bold text-gray-800">SIMBA</a>
                </div>

                <!-- Navigation Links (Desktop) -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('public.dashboard')" :active="request()->routeIs('public.dashboard')" wire:navigate>
                        <button class="text-base font-medium hover:text-blue-500">{{ __('Beranda') }}</button>
                    </x-nav-link>

                    <x-nav-link :href="route('public.report.create')" :active="request()->routeIs('public.report.create')" wire:navigate>
                        <button class="text-base font-medium hover:text-blue-500">{{ __('Lapor') }}</button>
                    </x-nav-link>
                    
                    <x-nav-link :href="route('public.incidents')" :active="request()->routeIs('public.incidents')" wire:navigate>
                        <button class="text-base font-medium hover:text-blue-500">{{ __('Bencana') }}</button>
                    </x-nav-link>
                    

                    @auth
                        <x-nav-link :href="route('admin.reports.index')" :active="request()->routeIs('admin.reports.index')" wire:navigate>
                            <button class="text-base font-medium hover:text-blue-500">{{ __('Kelola Laporan') }}</button>
                        </x-nav-link>
                    @endauth
                </div>
            </div>

            <!-- Right Side (Desktop) -->
            <div class="hidden sm:flex sm:ms-6">
                @auth
                    <div class="flex items-center">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none transition ease-in-out duration-150">
                                    <div>{{ auth()->user()->name }}</div>
                                    <div class="ms-2">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('admin.profile')" wire:navigate>
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                <button wire:click="logout" class="w-full text-left">
                                    <x-dropdown-link>
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </button>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @else
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">   
                        <x-nav-link :href="route('login')" :active="request()->routeIs('login')" wire:navigate>
                            <button class="text-base font-medium hover:text-blue-500">{{ __('Login') }}</button>
                        </x-nav-link>    
                    </div>
                @endauth
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }"
                              class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }"
                              class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('public.dashboard')" :active="request()->routeIs('public.dashboard')" wire:navigate>
                    <button class="hover:text-blue-500">{{ __('Beranda') }}</button>
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('public.report.create')" :active="request()->routeIs('public.report.create')" wire:navigate>
                <button class="hover:text-blue-500">{{ __('Lapor') }}</button>
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('public.incidents')" :active="request()->routeIs('public.incidents')" wire:navigate>
                <button class="hover:text-blue-500">{{ __('Bencana') }}</button>
            </x-responsive-nav-link>
        </div>

        @auth
            <div class="pt-2 pb-3 space-y-1 border-t border-gray-200">
                <x-responsive-nav-link :href="route('admin.reports.index')" :active="request()->routeIs('admin.reports.index')" wire:navigate>
                    <button class="hover:text-blue-500">{{ __('Kelola Laporan') }}</button>
                </x-responsive-nav-link>
            </div>
        @endauth

        @auth
            <!-- Responsive Settings Options (Mobile - Authenticated) -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800"
                         x-data="{{ json_encode(['name' => auth()->user()->name]) }}"
                         x-text="name"
                         x-on:profile-updated.window="name = $event.detail.name">
                    </div>
                    <div class="font-medium text-sm text-gray-500">
                        {{ auth()->user()->email }}
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('admin.profile')" wire:navigate>
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <button wire:click="logout" class="w-full text-start">
                        <x-responsive-nav-link>
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </button>
                </div>
            </div>
        @else
            <!-- Responsive Settings Options (Mobile - Guest) -->
            <div class="pt-1 pb-1 border-t border-gray-200">
                <div class="pt-2 pb-3 space-y-1">
                    <x-responsive-nav-link :href="route('login')" wire:navigate>
                        {{ __('Login') }}
                    </x-responsive-nav-link>
                </div>
            </div>
        @endauth
    </div>
</nav>
