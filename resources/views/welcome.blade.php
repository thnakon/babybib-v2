<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head', ['title' => 'Welcome'])
    <style>
        .brand-link {
            position: relative;
        }

        .brand-mark {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 1.75rem;
            height: 1.75rem;
            color: rgb(79 70 229);
            transform-origin: center center;
            transition: transform 340ms cubic-bezier(0.22, 1, 0.36, 1), filter 260ms ease;
        }

        .brand-mark::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: -0.1rem;
            width: 1.05rem;
            height: 2px;
            border-radius: 9999px;
            background: linear-gradient(90deg, rgba(56, 189, 248, 0.2), rgba(99, 102, 241, 0.86), rgba(96, 165, 250, 0.24));
            transform: translateX(-50%) scaleX(0.4);
            opacity: 0;
            transition: transform 320ms cubic-bezier(0.16, 1, 0.3, 1), opacity 220ms ease;
        }

        .brand-mark svg {
            width: 100%;
            height: 100%;
            transition: transform 340ms cubic-bezier(0.22, 1, 0.36, 1), color 220ms ease;
        }

        .brand-label {
            position: relative;
            display: inline-flex;
            align-items: center;
            transition: transform 320ms cubic-bezier(0.22, 1, 0.36, 1), color 220ms ease, text-shadow 320ms ease;
        }

        .brand-label::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -0.18rem;
            width: 100%;
            height: 2px;
            border-radius: 9999px;
            background: linear-gradient(90deg, rgba(129, 140, 248, 0.3), rgba(79, 70, 229, 0.95), rgba(56, 189, 248, 0.3));
            transform: scaleX(0.18);
            transform-origin: left center;
            opacity: 0.16;
            transition: transform 360ms cubic-bezier(0.16, 1, 0.3, 1), opacity 220ms ease;
        }

        .brand-link:hover .brand-mark {
            transform: translateY(-1px) scale(1.04);
            filter: drop-shadow(0 10px 18px rgba(59, 130, 246, 0.18));
        }

        .brand-link:hover .brand-mark svg {
            transform: perspective(48px) rotateY(-10deg) rotateZ(-2deg);
        }

        .brand-link:hover .brand-mark::after {
            transform: translateX(-50%) scaleX(1);
            opacity: 0.95;
        }

        .brand-link:hover .brand-label {
            transform: translateY(-1px);
            text-shadow: 0 8px 22px rgba(79, 70, 229, 0.18);
        }

        .brand-link:hover .brand-label::after {
            transform: scaleX(1);
            opacity: 0.95;
        }

        @media (prefers-reduced-motion: reduce) {
            .brand-mark,
            .brand-mark::after,
            .brand-mark svg,
            .brand-label,
            .brand-label::after {
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>

<body
    class="min-h-screen overflow-x-hidden bg-slate-50 font-sans antialiased text-zinc-900 dark:bg-slate-950 dark:text-zinc-100">
    <!-- Background Decorations -->
    <div class="fixed inset-0 pointer-events-none -z-10">
        <div
            class="absolute -top-[10%] -left-[10%] h-[40%] w-[40%] rounded-full bg-indigo-200/70 blur-[110px] opacity-70 dark:bg-indigo-950/35">
        </div>
        <div
            class="absolute top-[20%] -right-[5%] h-[50%] w-[30%] rotate-12 rounded-full bg-sky-200/55 blur-[130px] opacity-60 dark:bg-sky-950/25">
        </div>
        <div
            class="absolute -bottom-[10%] left-[15%] h-[30%] w-[50%] rounded-full bg-blue-200/60 blur-[110px] opacity-65 dark:bg-blue-950/25">
        </div>
    </div>

    <flux:header x-data="{ scrolled: window.pageYOffset > 20 }" @scroll.window="scrolled = window.pageYOffset > 20" sticky
        x-bind:class="scrolled ?
            '!bg-slate-50/95 !dark:bg-slate-950/95 !border-sky-100 !dark:border-slate-800 shadow-sm shadow-indigo-200/40 backdrop-blur-md py-3' :
            '!bg-transparent !border-transparent py-5'"
        class="sticky top-0 w-full transition-all duration-500 z-50 border-b">
        <div class="container max-w-7xl mx-auto px-6 flex items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="/" class="brand-link flex items-center gap-2 group">
                    <div class="brand-mark">
                        <x-app-logo-icon />
                    </div>
                    <span class="brand-label text-xl font-bold tracking-tight text-indigo-700 dark:text-sky-200">
                        Babybib
                        <span class="absolute -right-5 -top-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-sky-400 dark:text-sky-300">v2</span>
                    </span>
                </a>
            </div>

            <div class="flex items-center gap-1">
                <flux:navbar class="mr-2 hidden gap-1 text-sm font-medium text-zinc-500 [&_[data-current]]:border-indigo-500 [&_[data-current]]:text-indigo-600 dark:[&_[data-current]]:text-sky-300 md:flex">
                    <flux:tooltip content="{{ __('User Manual') }}" position="bottom">
                        <flux:navbar.item href="{{ route('manual') }}">{{ __('Manual') }}</flux:navbar.item>
                    </flux:tooltip>

                    <flux:tooltip content="{{ __('Citation Generator') }}" position="bottom">
                        <flux:navbar.item href="{{ route('citation-generator') }}">{{ __('Generate') }}</flux:navbar.item>
                    </flux:tooltip>

                    <flux:tooltip content="{{ __('Explore templates') }}" position="bottom">
                        <flux:navbar.item href="#">{{ __('Templates') }}</flux:navbar.item>
                    </flux:tooltip>

                    <!-- Help Dropdown -->
                    <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
                        <flux:navbar.item class="cursor-pointer">
                            <div class="flex items-center gap-1.5 min-w-max">
                                <span>{{ __('Help') }}</span>
                                <flux:icon name="chevron-down" class="size-3 transition-transform duration-300"
                                    x-bind:class="open ? 'rotate-180' : ''" />
                            </div>
                        </flux:navbar.item>

                        <div x-show="open" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-2"
                            class="absolute left-0 top-full pt-2 z-50 w-48" style="display: none;">
                            <div class="overflow-hidden rounded-xl border border-sky-100 bg-white p-1.5 shadow-xl shadow-indigo-100/50 dark:border-slate-800 dark:bg-slate-900">
                                <flux:menu.item href="#" class="!text-zinc-600 transition-colors hover:!bg-sky-50 hover:!text-indigo-700 dark:!text-zinc-400 dark:hover:!bg-indigo-500/10 dark:hover:!text-sky-200">{{ __('Help Center') }}</flux:menu.item>
                                <flux:menu.item href="#" class="!text-zinc-600 transition-colors hover:!bg-sky-50 hover:!text-indigo-700 dark:!text-zinc-400 dark:hover:!bg-indigo-500/10 dark:hover:!text-sky-200">{{ __('Contact Support') }}</flux:menu.item>
                                <flux:menu.separator />
                                <flux:menu.item href="#" class="!text-zinc-600 transition-colors hover:!bg-sky-50 hover:!text-indigo-700 dark:!text-zinc-400 dark:hover:!bg-indigo-500/10 dark:hover:!text-sky-200">{{ __('Community Discord') }}</flux:menu.item>
                            </div>
                        </div>
                    </div>

                    <!-- Share Dropdown -->
                    <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
                        <flux:navbar.item class="cursor-pointer">
                            <div class="flex items-center gap-1.5 min-w-max">
                                <span>{{ __('Share') }}</span>
                                <flux:icon name="chevron-down" class="size-3 transition-transform duration-300"
                                    x-bind:class="open ? 'rotate-180' : ''" />
                            </div>
                        </flux:navbar.item>

                        <div x-show="open" x-transition ... class="absolute left-0 top-full pt-2 z-50 w-44" style="display: none;">
                            <div class="overflow-hidden rounded-xl border border-sky-100 bg-white p-1.5 shadow-xl shadow-indigo-100/50 dark:border-slate-800 dark:bg-slate-900">
                                <flux:menu.item href="#" icon="facebook" class="!text-zinc-600 transition-colors hover:!bg-sky-50 hover:!text-indigo-700 dark:!text-zinc-400 dark:hover:!bg-indigo-500/10 dark:hover:!text-sky-200">Facebook</flux:menu.item>
                                <flux:menu.item href="#" icon="instagram" class="!text-zinc-600 transition-colors hover:!bg-sky-50 hover:!text-indigo-700 dark:!text-zinc-400 dark:hover:!bg-indigo-500/10 dark:hover:!text-sky-200">Instagram</flux:menu.item>
                                <flux:menu.item href="#" icon="twitter" class="!text-zinc-600 transition-colors hover:!bg-sky-50 hover:!text-indigo-700 dark:!text-zinc-400 dark:hover:!bg-indigo-500/10 dark:hover:!text-sky-200">X (Twitter)</flux:menu.item>
                                <flux:menu.item href="#" icon="line" class="!text-zinc-600 transition-colors hover:!bg-sky-50 hover:!text-indigo-700 dark:!text-zinc-400 dark:hover:!bg-indigo-500/10 dark:hover:!text-sky-200">Line</flux:menu.item>
                            </div>
                        </div>
                    </div>
                </flux:navbar>

                <span class="mx-2 text-zinc-200 dark:text-zinc-800 font-light select-none">|</span>

                <!-- Language Switcher (Hover) -->
                <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
                    <flux:navbar.item class="cursor-pointer">
                        <div class="flex items-center gap-1.5 min-w-max">
                            <span>{{ strtoupper(app()->getLocale()) }}</span>
                            <flux:icon name="chevron-down" class="size-3 transition-transform duration-300"
                                x-bind:class="open ? 'rotate-180' : ''" />
                        </div>
                    </flux:navbar.item>

                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-2"
                        class="absolute right-0 top-full pt-2 z-50 w-32" style="display: none;">
                        <div class="overflow-hidden rounded-xl border border-sky-100 bg-white p-1.5 shadow-xl shadow-indigo-100/50 dark:border-slate-800 dark:bg-slate-900">
                            <flux:menu.item href="{{ route('change-language', 'en') }}" class="!text-zinc-600 transition-colors hover:!bg-sky-50 hover:!text-indigo-700 dark:!text-zinc-400 dark:hover:!bg-indigo-500/10 dark:hover:!text-sky-200">English (EN)</flux:menu.item>
                            <flux:menu.item href="{{ route('change-language', 'th') }}" class="!text-zinc-600 transition-colors hover:!bg-sky-50 hover:!text-indigo-700 dark:!text-zinc-400 dark:hover:!bg-indigo-500/10 dark:hover:!text-sky-200">ไทย (TH)</flux:menu.item>
                        </div>
                    </div>
                </div>

                <flux:tooltip content="{{ __('Toggle appearance') }}" position="bottom">
                    <button type="button" onclick="window.toggleDarkMode()"
                        class="rounded-lg p-2 transition-colors hover:bg-sky-50 hover:text-indigo-700 focus:outline-none dark:hover:bg-indigo-500/10 dark:hover:text-sky-200">
                        <flux:icon name="moon" class="w-5 h-5 dark:hidden" />
                        <flux:icon name="sun" class="w-5 h-5 hidden dark:block" />
                    </button>
                </flux:tooltip>

                @if (Route::has('login'))
                    <span class="mx-2 text-zinc-200 dark:text-zinc-800 font-light select-none"></span>
                    @auth
                        <flux:tooltip content="Enter your dashboard" position="bottom">
                            <flux:button href="{{ url('/dashboard') }}" variant="ghost">Dashboard</flux:button>
                        </flux:tooltip>
                    @else
                        <flux:tooltip content="Sign in to your account" position="bottom">
                            <flux:button href="{{ route('login') }}" variant="primary" size="sm"
                                class="group/signin border-indigo-600 bg-indigo-600 px-5 font-bold shadow-sm shadow-indigo-500/20 transition-all hover:scale-[1.02] hover:bg-indigo-700 active:scale-95 dark:border-indigo-500 dark:bg-indigo-500 dark:hover:bg-sky-500">
                                <span>Sign in</span>
                                <flux:icon name="arrow-right"
                                    class="w-4 h-4 ml-1.5 transition-transform group-hover/signin:translate-x-1" />
                            </flux:button>
                        </flux:tooltip>
                    @endauth
                @endif
            </div>
        </div>
    </flux:header>

    <main class="container max-w-5xl mx-auto px-6 pt-24 pb-32 text-center relative">
        <!-- Hero Section -->
        <div class="flex flex-col items-center gap-6 animate-fade-in-up">
            <a href="#"
                class="group inline-flex items-center gap-2 rounded-full border border-sky-200 bg-indigo-100/70 px-3 py-1 text-xs font-semibold text-indigo-700 transition-all hover:bg-sky-100 dark:border-indigo-800/50 dark:bg-indigo-950/30 dark:text-sky-300 dark:hover:bg-indigo-950/50">
                <div class="flex h-4 w-4 items-center justify-center rounded-full bg-sky-500">
                    <flux:icon.bolt class="w-3 h-3 text-white fill-current" />
                </div>
                {{ __('Livewire v4 is here!') }}
                <span class="opacity-70 group-hover:opacity-100 transition-opacity">{{ __('Read the upgrade guide') }}
                    &rsaquo;</span>
            </a>

            <h1 class="text-5xl md:text-7xl font-semibold tracking-tight text-zinc-900 dark:text-white leading-[1.1] relative inline-block">
                <span>{!! __('The official Livewire') !!}</span>
                <span class="relative inline-block">
                    {!! __('component library') !!}
                    <svg class="absolute -bottom-2 left-0 h-3 w-full text-sky-500/45 md:-bottom-4 md:h-6" viewBox="0 0 100 20" preserveAspectRatio="none">
                        <path d="M0 10 Q 25 0, 50 10 T 100 10" stroke="currentColor" stroke-width="4" fill="none" stroke-linecap="round" />
                    </svg>
                </span>
                <flux:icon name="pencil" class="-ml-2 inline-block size-12 -translate-y-2 -rotate-12 transform text-indigo-500 md:size-20" />
            </h1>

            <p class="max-w-2xl text-lg md:text-xl text-zinc-400 dark:text-zinc-400">
                {{ __('World-class components, built specifically for your Livewire interfaces.') }} <br class="hidden md:block">
                {{ __('Fully flexible, functional, and accessible.') }}
            </p>

            <div class="flex flex-col sm:flex-row gap-4 mt-4">
                <flux:button href="{{ route('register') }}" variant="primary"
                    class="group h-12 bg-gradient-to-r from-indigo-600 via-indigo-500 to-sky-500 px-8 text-base font-bold text-white shadow-lg shadow-indigo-500/20 transition-transform hover:scale-105 hover:shadow-indigo-500/30 dark:from-indigo-500 dark:via-indigo-400 dark:to-sky-400 dark:text-white">
                    {{ __('Get started') }}
                    <flux:icon.arrow-right class="ml-2 w-4 h-4 group-hover:translate-x-1 transition-transform" />
                </flux:button>
                <flux:button href="#" variant="ghost"
                    class="h-12 border border-sky-200 bg-white/70 px-8 text-base font-bold text-zinc-700 transition-all hover:border-indigo-300 hover:bg-sky-50 dark:border-slate-800 dark:bg-slate-900/70 dark:text-zinc-200 dark:hover:border-indigo-500 dark:hover:bg-slate-900">
                    {{ __('Browse components') }}
                    <span class="ml-2 text-zinc-400">&rsaquo;</span>
                </flux:button>
            </div>
        </div>

        <!-- UI Preview - Settings Page Mock -->
        <div
            class="group relative mx-auto mt-24 max-w-6xl overflow-hidden rounded-3xl border border-sky-100/80 bg-white/50 p-4 shadow-2xl shadow-indigo-100/50 backdrop-blur-sm dark:border-slate-800/70 dark:bg-slate-900/30 md:p-8">
            <div
                class="absolute left-0 top-0 h-1 w-full bg-gradient-to-r from-indigo-500 via-sky-500 to-cyan-400 opacity-0 transition-opacity group-hover:opacity-100">
            </div>

            <div
                class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-xl flex flex-col md:flex-row overflow-hidden aspect-[16/9] md:aspect-auto">
                <!-- Mock Sidebar -->
                <div
                    class="w-full md:w-64 border-r border-zinc-100 dark:border-zinc-800 p-6 flex flex-col gap-6 text-left">
                    <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded bg-gradient-to-br from-indigo-600 to-sky-500 font-bold text-white shadow-sm shadow-indigo-500/20">
                            A
                        </div>
                        <span class="font-bold text-sm">Acme Inc.</span>
                    </div>

                    <div class="relative">
                        <flux:icon.magnifying-glass class="absolute left-3 top-2.5 w-4 h-4 text-zinc-400" />
                        <input type="text" placeholder="Search..."
                            class="w-full pl-9 pr-3 py-2 bg-zinc-50 dark:bg-zinc-900 rounded-lg border-none text-xs focus:ring-1 focus:ring-zinc-300 dark:focus:ring-zinc-700">
                    </div>

                    <flux:navlist>
                        <flux:navlist.item icon="home" href="#" current>Home</flux:navlist.item>
                        <flux:navlist.item icon="envelope" href="#">Inbox <span
                                class="ml-auto text-xs text-zinc-400">12</span></flux:navlist.item>
                        <flux:navlist.item icon="document-text" href="#">Documents</flux:navlist.item>
                        <flux:navlist.item icon="calendar" href="#">Calendar</flux:navlist.item>

                        <div class="mt-4 mb-2 text-[10px] uppercase tracking-wider font-bold text-zinc-400">Favorites
                        </div>
                        <flux:navlist.item href="#">Marketing site</flux:navlist.item>
                        <flux:navlist.item href="#">Android app</flux:navlist.item>
                    </flux:navlist>
                </div>

                <!-- Mock Content Area -->
                <div class="flex-1 p-8 md:p-12 text-left bg-white dark:bg-zinc-900">
                    <h2 class="text-3xl font-bold mb-8">Settings</h2>

                    <div class="space-y-12">
                        <div
                            class="flex flex-col md:flex-row gap-8 pb-12 border-b border-zinc-100 dark:border-zinc-900">
                            <div class="w-full md:w-64">
                                <h3 class="font-bold text-sm">Profile</h3>
                                <p class="text-xs text-zinc-400 mt-1">This is how others will see you on the site.</p>
                            </div>
                            <div class="flex-1 space-y-6">
                                <flux:field>
                                    <flux:label>Username</flux:label>
                                    <flux:description>This is your public display name. It can be your real name or a
                                        pseudonym. You can only change this once every 30 days.</flux:description>
                                    <flux:input value="calebporzio" />
                                </flux:field>

                                <flux:field>
                                    <flux:label>Primary email</flux:label>
                                    <flux:select placeholder="Select primary email...">
                                        <flux:select.option>caleb@laravel.com</flux:select.option>
                                    </flux:select>
                                    <flux:description>You can manage verified email addresses in your email settings.
                                    </flux:description>
                                </flux:field>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <style>
        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fade-in-up 0.8s ease-out forwards;
        }
    </style>
    @fluxScripts
</body>

</html>
