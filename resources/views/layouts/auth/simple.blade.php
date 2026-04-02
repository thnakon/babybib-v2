<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
        {{-- Top Navigation Bar --}}
        <div class="fixed top-0 left-0 right-0 z-50 flex items-center justify-between px-4 sm:px-6 py-3">
            {{-- Back Button (Top Left) --}}
            <a href="{{ route('home') }}" wire:navigate
                class="flex items-center gap-1.5 text-sm font-medium text-zinc-500 hover:text-zinc-900 dark:hover:text-white transition-colors group">
                <flux:icon name="arrow-left" class="size-4 group-hover:-translate-x-0.5 transition-transform" />
                <span class="hidden sm:inline">{{ __('Back') }}</span>
            </a>

            {{-- Theme & Language Controls (Top Right) --}}
            <div class="flex items-center gap-1">
                {{-- Language Switcher --}}
                <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
                    <button type="button" class="flex items-center gap-1 px-2 py-1.5 text-sm font-medium text-zinc-500 hover:text-zinc-900 dark:hover:text-white rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors cursor-pointer">
                        <span>{{ strtoupper(app()->getLocale()) }}</span>
                        <flux:icon name="chevron-down" class="size-3 transition-transform duration-300"
                            x-bind:class="open ? 'rotate-180' : ''" />
                    </button>

                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-2"
                        class="absolute right-0 top-full pt-1 z-50 w-32" style="display: none;">
                        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-xl overflow-hidden p-1.5">
                            <a href="{{ route('change-language', 'en') }}"
                                class="block px-3 py-1.5 text-sm text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg transition-colors">
                                English (EN)
                            </a>
                            <a href="{{ route('change-language', 'th') }}"
                                class="block px-3 py-1.5 text-sm text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg transition-colors">
                                ไทย (TH)
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Theme Toggle --}}
                <flux:tooltip content="{{ __('Toggle appearance') }}" position="bottom">
                    <button type="button" onclick="window.toggleDarkMode()"
                        class="p-2 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors focus:outline-none text-zinc-500 hover:text-zinc-900 dark:hover:text-white">
                        <flux:icon name="moon" class="w-4 h-4 dark:hidden" />
                        <flux:icon name="sun" class="w-4 h-4 hidden dark:block" />
                    </button>
                </flux:tooltip>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="bg-background flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="flex w-full max-w-lg flex-col gap-2">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                    <span class="flex h-9 w-9 mb-1 items-center justify-center rounded-md">
                        <x-app-logo-icon class="size-9 fill-current text-black dark:text-white" />
                    </span>
                    <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                </a>
                <div class="flex flex-col gap-6">
                    {{ $slot }}
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
