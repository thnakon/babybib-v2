<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head', ['title' => 'Welcome'])
</head>

<body
    class="min-h-screen font-sans antialiased bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 overflow-x-hidden">
    <!-- Background Decorations -->
    <div class="fixed inset-0 pointer-events-none -z-10">
        <div
            class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-zinc-100 dark:bg-zinc-900/50 rounded-full blur-[100px] opacity-60">
        </div>
        <div
            class="absolute top-[20%] -right-[5%] w-[30%] h-[50%] bg-zinc-50 dark:bg-zinc-900/30 rounded-full blur-[120px] opacity-40 transform rotate-12">
        </div>
        <div
            class="absolute -bottom-[10%] left-[15%] w-[50%] h-[30%] bg-zinc-100/80 dark:bg-zinc-900/40 rounded-full blur-[100px] opacity-50">
        </div>
    </div>

    <flux:header x-data="{ scrolled: window.pageYOffset > 20 }" @scroll.window="scrolled = window.pageYOffset > 20" sticky
        x-bind:class="scrolled ?
            '!bg-white/95 !dark:bg-zinc-900/95 !border-zinc-200 !dark:border-zinc-800 shadow-sm backdrop-blur-md py-3' :
            '!bg-transparent !border-transparent py-5'"
        class="sticky top-0 w-full transition-all duration-500 z-50 border-b">
        <div class="container max-w-7xl mx-auto px-6 flex items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="/" class="flex items-center gap-2 group">
                    <div class="flex gap-0.5 items-end h-5">
                        <div class="w-1 bg-zinc-900 dark:bg-white h-2 rounded-full transition-all group-hover:h-4">
                        </div>
                        <div class="w-1 bg-zinc-900 dark:bg-white h-4 rounded-full transition-all group-hover:h-3">
                        </div>
                        <div class="w-1 bg-zinc-900 dark:bg-white h-5 rounded-full transition-all group-hover:h-2">
                        </div>
                    </div>
                    <span class="text-xl font-bold tracking-tight">flux</span>
                </a>
            </div>

            <div class="flex items-center gap-1">
                <flux:navbar class="hidden md:flex gap-1 text-sm font-medium text-zinc-500 mr-2">
                    <flux:tooltip content="{{ __('User Manual') }}" position="bottom">
                        <flux:navbar.item href="#">{{ __('Manual') }}</flux:navbar.item>
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
                            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-xl overflow-hidden p-1.5">
                                <flux:menu.item href="#" class="!text-zinc-600 dark:!text-zinc-400 hover:!bg-zinc-100 dark:hover:!bg-zinc-800 transition-colors">{{ __('Help Center') }}</flux:menu.item>
                                <flux:menu.item href="#" class="!text-zinc-600 dark:!text-zinc-400 hover:!bg-zinc-100 dark:hover:!bg-zinc-800 transition-colors">{{ __('Contact Support') }}</flux:menu.item>
                                <flux:menu.separator />
                                <flux:menu.item href="#" class="!text-zinc-600 dark:!text-zinc-400 hover:!bg-zinc-100 dark:hover:!bg-zinc-800 transition-colors">{{ __('Community Discord') }}</flux:menu.item>
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
                            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-xl overflow-hidden p-1.5">
                                <flux:menu.item href="#" icon="facebook" class="!text-zinc-600 dark:!text-zinc-400 hover:!bg-zinc-100 dark:hover:!bg-zinc-800 transition-colors">Facebook</flux:menu.item>
                                <flux:menu.item href="#" icon="instagram" class="!text-zinc-600 dark:!text-zinc-400 hover:!bg-zinc-100 dark:hover:!bg-zinc-800 transition-colors">Instagram</flux:menu.item>
                                <flux:menu.item href="#" icon="twitter" class="!text-zinc-600 dark:!text-zinc-400 hover:!bg-zinc-100 dark:hover:!bg-zinc-800 transition-colors">X (Twitter)</flux:menu.item>
                                <flux:menu.item href="#" icon="line" class="!text-zinc-600 dark:!text-zinc-400 hover:!bg-zinc-100 dark:hover:!bg-zinc-800 transition-colors">Line</flux:menu.item>
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
                        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-xl overflow-hidden p-1.5">
                            <flux:menu.item href="{{ route('change-language', 'en') }}" class="!text-zinc-600 dark:!text-zinc-400 hover:!bg-zinc-100 dark:hover:!bg-zinc-800 transition-colors">English (EN)</flux:menu.item>
                            <flux:menu.item href="{{ route('change-language', 'th') }}" class="!text-zinc-600 dark:!text-zinc-400 hover:!bg-zinc-100 dark:hover:!bg-zinc-800 transition-colors">ไทย (TH)</flux:menu.item>
                        </div>
                    </div>
                </div>

                <flux:tooltip content="{{ __('Toggle appearance') }}" position="bottom">
                    <button type="button" onclick="window.toggleDarkMode()"
                        class="p-2 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors focus:outline-none">
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
                                class="group/signin px-5 font-bold shadow-sm transition-all hover:scale-[1.02] active:scale-95">
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
                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-pink-100 dark:bg-pink-900/30 text-pink-600 dark:text-pink-400 text-xs font-semibold border border-pink-200 dark:border-pink-800/50 hover:bg-pink-200 dark:hover:bg-pink-900/50 transition-all group">
                <div class="flex items-center justify-center w-4 h-4 bg-pink-500 rounded-full">
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
                    <svg class="absolute -bottom-2 md:-bottom-4 left-0 w-full h-3 md:h-6 text-pink-500/40" viewBox="0 0 100 20" preserveAspectRatio="none">
                        <path d="M0 10 Q 25 0, 50 10 T 100 10" stroke="currentColor" stroke-width="4" fill="none" stroke-linecap="round" />
                    </svg>
                </span>
                <flux:icon name="pencil" class="inline-block size-12 md:size-20 text-pink-500 rotate-12 transform translate-y-1 md:translate-y-2 ml-1 md:ml-3 select-none" />
            </h1>

            <p class="max-w-2xl text-lg md:text-xl text-zinc-400 dark:text-zinc-400">
                {{ __('World-class components, built specifically for your Livewire interfaces.') }} <br class="hidden md:block">
                {{ __('Fully flexible, functional, and accessible.') }}
            </p>

            <div class="flex flex-col sm:flex-row gap-4 mt-4">
                <flux:button href="{{ route('register') }}" variant="primary"
                    class="h-12 px-8 bg-zinc-900 dark:bg-zinc-100 dark:text-zinc-900 font-bold text-base hover:scale-105 transition-transform group">
                    {{ __('Get started') }}
                    <flux:icon.arrow-right class="ml-2 w-4 h-4 group-hover:translate-x-1 transition-transform" />
                </flux:button>
                <flux:button href="#" variant="ghost"
                    class="h-12 px-8 border border-zinc-200 dark:border-zinc-800 font-bold text-base hover:bg-zinc-50 dark:hover:bg-zinc-900 transition-all">
                    {{ __('Browse components') }}
                    <span class="ml-2 text-zinc-400">&rsaquo;</span>
                </flux:button>
            </div>
        </div>

        <!-- UI Preview - Settings Page Mock -->
        <div
            class="mt-24 max-w-6xl mx-auto p-4 md:p-8 bg-zinc-50/50 dark:bg-zinc-900/20 rounded-3xl border border-zinc-200/50 dark:border-zinc-800/50 shadow-2xl backdrop-blur-sm relative overflow-hidden group">
            <div
                class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-pink-500 to-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity">
            </div>

            <div
                class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-xl flex flex-col md:flex-row overflow-hidden aspect-[16/9] md:aspect-auto">
                <!-- Mock Sidebar -->
                <div
                    class="w-full md:w-64 border-r border-zinc-100 dark:border-zinc-800 p-6 flex flex-col gap-6 text-left">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-zinc-900 rounded flex items-center justify-center text-white font-bold">
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
