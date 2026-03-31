<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head', ['title' => __('Manual')])
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e4e4e7; border-radius: 10px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #27272a; }
    </style>
</head>
<body class="min-h-screen font-sans antialiased bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 overflow-x-hidden">
    <!-- Navbar (Shared) -->
    <flux:header 
        sticky 
        class="sticky top-0 w-full z-50 border-b bg-white/95 dark:bg-zinc-900/95 border-zinc-200 dark:border-zinc-800 shadow-sm backdrop-blur-md py-3"
    >
        <div class="container max-w-7xl mx-auto px-6 flex items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="/" class="flex items-center gap-2 group">
                    <div class="flex gap-0.5 items-end h-5">
                        <div class="w-1 bg-zinc-900 dark:bg-white h-2 rounded-full transition-all group-hover:h-4"></div>
                        <div class="w-1 bg-zinc-900 dark:bg-white h-4 rounded-full transition-all group-hover:h-3"></div>
                        <div class="w-1 bg-zinc-900 dark:bg-white h-5 rounded-full transition-all group-hover:h-2"></div>
                    </div>
                    <span class="text-xl font-bold tracking-tight">flux</span>
                </a>
            </div>

            <div class="flex items-center gap-1">
                <flux:navbar class="hidden md:flex gap-1 text-sm font-medium text-zinc-500 mr-2">
                    <flux:tooltip content="{{ __('User Manual') }}" position="bottom">
                        <flux:navbar.item href="{{ route('manual') }}" current>{{ __('Manual') }}</flux:navbar.item>
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

                        <div x-show="open" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-2"
                            class="absolute left-0 top-full pt-2 z-50 w-44" style="display: none;">
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
                        <flux:tooltip content="{{ __('Enter your dashboard') }}" position="bottom">
                            <flux:button href="{{ url('/dashboard') }}" variant="ghost">{{ __('Dashboard') }}</flux:button>
                        </flux:tooltip>
                    @else
                        <flux:tooltip content="{{ __('Sign in to your account') }}" position="bottom">
                            <flux:button href="{{ route('login') }}" variant="primary" size="sm" class="group/signin px-5 font-bold shadow-sm transition-all hover:scale-[1.02] active:scale-95">
                                <span>{{ __('Sign in') }}</span>
                                <flux:icon name="arrow-right" class="w-4 h-4 ml-1.5 transition-transform group-hover/signin:translate-x-1" />
                            </flux:button>
                        </flux:tooltip>
                    @endauth
                @endif
            </div>
        </div>
    </flux:header>

    <div class="max-w-7xl mx-auto px-6 flex gap-8 pt-12 pb-24 relative">
        <!-- Left Sidebar (Nav) -->
        <aside class="w-56 shrink-0 sticky top-24 h-[calc(100vh-8rem)] overflow-y-auto hidden lg:block custom-scrollbar pr-4">
            <nav class="space-y-8">
                <div>
                    <h3 class="text-xs font-bold text-zinc-400 uppercase tracking-widest mb-4">{{ __('Guides') }}</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="block text-sm font-semibold text-zinc-900 dark:text-white border-l-2 border-zinc-900 dark:border-white pl-4 -ml-px">{{ __('Installation') }}</a></li>
                        <li><a href="#" class="block text-sm font-medium text-zinc-500 hover:text-zinc-900 dark:hover:text-white pl-4 border-l-2 border-transparent transition-colors">{{ __('Upgrade guide') }}</a></li>
                        <li><a href="#" class="block text-sm font-medium text-zinc-500 hover:text-zinc-900 dark:hover:text-white pl-4 border-l-2 border-transparent transition-colors">{{ __('Principles') }}</a></li>
                        <li><a href="#" class="block text-sm font-medium text-zinc-500 hover:text-zinc-900 dark:hover:text-white pl-4 border-l-2 border-transparent transition-colors">{{ __('Patterns') }}</a></li>
                        <li><a href="#" class="block text-sm font-medium text-zinc-500 hover:text-zinc-900 dark:hover:text-white pl-4 border-l-2 border-transparent transition-colors">{{ __('Theming') }}</a></li>
                        <li><a href="#" class="block text-sm font-medium text-zinc-500 hover:text-zinc-900 dark:hover:text-white pl-4 border-l-2 border-transparent transition-colors">{{ __('Dark mode') }}</a></li>
                        <li><a href="#" class="block text-sm font-medium text-zinc-500 hover:text-zinc-900 dark:hover:text-white pl-4 border-l-2 border-transparent transition-colors">{{ __('Customization') }}</a></li>
                        <li><a href="#" class="block text-sm font-medium text-zinc-500 hover:text-zinc-900 dark:hover:text-white pl-4 border-l-2 border-transparent transition-colors">{{ __('Help') }}</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xs font-bold text-zinc-400 uppercase tracking-widest mb-4">{{ __('Layouts') }}</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="block text-sm font-medium text-zinc-500 hover:text-zinc-900 dark:hover:text-white pl-4 border-l-2 border-transparent transition-colors">{{ __('Header') }}</a></li>
                        <li><a href="#" class="block text-sm font-medium text-zinc-500 hover:text-zinc-900 dark:hover:text-white pl-4 border-l-2 border-transparent transition-colors">{{ __('Sidebar') }}</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xs font-bold text-zinc-400 uppercase tracking-widest mb-4">{{ __('Components') }}</h3>
                    <ul class="space-y-2 pb-8">
                        @foreach(['Accordion', 'Autocomplete', 'Avatar', 'Badge', 'Brand', 'Button', 'Breadcrumbs', 'Calendar'] as $comp)
                            <li><a href="#" class="block text-sm font-medium text-zinc-500 hover:text-zinc-900 dark:hover:text-white pl-4 border-l-2 border-transparent transition-colors">{{ __($comp) }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 min-w-0">
            <div class="prose prose-zinc dark:prose-invert max-w-none">
                <h1 class="text-4xl font-bold tracking-tight mb-4">{{ __('Installation') }}</h1>
                <p class="text-lg text-zinc-500 dark:text-zinc-400 leading-relaxed max-w-3xl mb-8">
                    {{ __('Flux is a robust, hand-crafted, UI component library for your Livewire applications.') }}
                </p>

                <!-- Box Tip -->
                <div class="bg-zinc-50 dark:bg-zinc-900/50 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6 flex gap-4 mb-12">
                    <flux:icon name="light-bulb" class="size-6 text-zinc-400 shrink-0" />
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">
                        {{ __('Starting a new project?') }} Flux comes baked into the new <a href="#" class="text-zinc-900 dark:text-white font-semibold underline underline-offset-4 decoration-zinc-300">Livewire starter kit &rarr;</a>
                    </p>
                </div>

                <h2 class="text-2xl font-bold mb-6 pt-8 border-t border-zinc-100 dark:border-zinc-800">{{ __('Prerequisites') }}</h2>
                <p class="text-zinc-500 dark:text-zinc-400 mb-8">{{ __('Flux requires the following before installing:') }}</p>

                <!-- Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
                    <div class="p-6 bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm relative group hover:border-zinc-400 dark:hover:border-zinc-600 transition-all">
                        <flux:icon name="heart" class="size-8 text-red-500 mb-4" />
                        <h4 class="font-bold mb-1">Laravel</h4>
                        <p class="text-xs text-zinc-500">{{ __('Version 10 or later') }}</p>
                        <flux:icon name="arrow-up-right" class="absolute top-4 right-4 size-4 text-zinc-300 opacity-0 group-hover:opacity-100 transition-opacity" />
                    </div>

                    <div class="p-6 bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm relative group hover:border-zinc-400 dark:hover:border-zinc-600 transition-all">
                        <flux:icon name="bolt" class="size-8 text-pink-500 mb-4" />
                        <h4 class="font-bold mb-1">Livewire</h4>
                        <p class="text-xs text-zinc-500">{{ __('Version 3.7.0 or later') }}</p>
                        <flux:icon name="arrow-up-right" class="absolute top-4 right-4 size-4 text-zinc-300 opacity-0 group-hover:opacity-100 transition-opacity" />
                    </div>

                    <div class="p-6 bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm relative group hover:border-zinc-400 dark:hover:border-zinc-600 transition-all">
                        <flux:icon name="cloud" class="size-8 text-blue-500 mb-4" />
                        <h4 class="font-bold mb-1">Tailwind CSS</h4>
                        <p class="text-xs text-zinc-500">{{ __('Version 4.2 or later') }}</p>
                        <flux:icon name="arrow-up-right" class="absolute top-4 right-4 size-4 text-zinc-300 opacity-0 group-hover:opacity-100 transition-opacity" />
                    </div>
                </div>

                <h2 class="text-2xl font-bold mb-6 pt-8 border-t border-zinc-100 dark:border-zinc-800">{{ __('Getting started') }}</h2>
                
                <div class="relative pl-12 pb-12 border-l border-zinc-100 dark:border-zinc-800 ml-4">
                    <div class="absolute -left-[17px] top-0 size-8 rounded-full border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 flex items-center justify-center text-xs font-bold text-zinc-400 shadow-sm">1</div>
                    <h3 class="text-lg font-bold mb-2">{{ __('Install Flux') }}</h3>
                    <p class="text-zinc-500 dark:text-zinc-400 mb-4">{{ __('Flux can be installed via composer from your project root:') }}</p>
                    <div class="bg-zinc-900 rounded-xl p-4 font-mono text-sm text-zinc-100 overflow-x-auto mb-4 border border-zinc-800">
                        <code>composer require livewire/flux</code>
                    </div>
                </div>
            </div>
        </main>

        <!-- Right Sidebar (On this page) -->
        <aside class="w-56 shrink-0 sticky top-24 h-[calc(100vh-8rem)] overflow-y-auto hidden xl:block custom-scrollbar pl-4 border-l border-zinc-100 dark:border-zinc-800">
            <h3 class="text-xs font-bold text-zinc-900 dark:text-white uppercase tracking-widest mb-6">{{ __('On this page') }}</h3>
            <ul class="space-y-4 text-sm font-medium text-zinc-500">
                <li><a href="#" class="hover:text-zinc-900 dark:hover:text-white transition-colors">{{ __('Introduction') }}</a></li>
                <li><a href="#" class="text-zinc-900 dark:text-white border-l-2 border-zinc-900 dark:border-white pl-4 -ml-px">{{ __('Prerequisites') }}</a></li>
                <li><a href="#" class="hover:text-zinc-900 dark:hover:text-white transition-colors pl-4 border-l-2 border-transparent">{{ __('Getting started') }}</a></li>
                <li><a href="#" class="hover:text-zinc-900 dark:hover:text-white transition-colors pl-4 border-l-2 border-transparent">{{ __('Theming') }}</a></li>
                <li><a href="#" class="hover:text-zinc-900 dark:hover:text-white transition-colors pl-4 border-l-2 border-transparent">{{ __('Disable dark mode') }}</a></li>
                <li><a href="#" class="hover:text-zinc-900 dark:hover:text-white transition-colors pl-4 border-l-2 border-transparent">{{ __('Publishing components') }}</a></li>
                <li><a href="#" class="hover:text-zinc-900 dark:hover:text-white transition-colors pl-4 border-l-2 border-transparent">{{ __('Keeping Flux updated') }}</a></li>
                <li><a href="#" class="hover:text-zinc-900 dark:hover:text-white transition-colors pl-4 border-l-2 border-transparent">{{ __('Cloning an existing project') }}</a></li>
            </ul>
        </aside>
    </div>

    <!-- Scripts for Scroll animations/TOC (Placeholder) -->
    <script>
        // Smooth scroll logic can be added here
    </script>
</body>
</html>
