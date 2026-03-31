<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head', ['title' => 'Citation Generator'])
</head>
<body class="min-h-screen font-sans antialiased bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 overflow-x-hidden">
    <!-- Navbar (Same as Welcome but sticky background by default for clarity) -->
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
                    <flux:tooltip content="User Manual" position="bottom">
                        <flux:navbar.item href="#">Manual</flux:navbar.item>
                    </flux:tooltip>

                    <flux:tooltip content="Citation Generator" position="bottom">
                        <flux:navbar.item href="{{ route('citation-generator') }}" current>Generate</flux:navbar.item>
                    </flux:tooltip>

                    <flux:tooltip content="Explore templates" position="bottom">
                        <flux:navbar.item href="#">Templates</flux:navbar.item>
                    </flux:tooltip>

                    <!-- Help Dropdown -->
                    <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
                        <flux:navbar.item class="cursor-pointer">
                            <div class="flex items-center gap-1.5 min-w-max">
                                <span>Help</span>
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
                            <div
                                class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-xl overflow-hidden p-1.5">
                                <flux:menu.item href="#"
                                    class="!text-zinc-600 dark:!text-zinc-400 hover:!bg-zinc-100 dark:hover:!bg-zinc-800 transition-colors">
                                    Help Center</flux:menu.item>
                                <flux:menu.item href="#"
                                    class="!text-zinc-600 dark:!text-zinc-400 hover:!bg-zinc-100 dark:hover:!bg-zinc-800 transition-colors">
                                    Contact Support</flux:menu.item>
                                <flux:menu.separator />
                                <flux:menu.item href="#"
                                    class="!text-zinc-600 dark:!text-zinc-400 hover:!bg-zinc-100 dark:hover:!bg-zinc-800 transition-colors">
                                    Community Discord</flux:menu.item>
                            </div>
                        </div>
                    </div>

                    <!-- Share Dropdown -->
                    <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
                        <flux:navbar.item class="cursor-pointer">
                            <div class="flex items-center gap-1.5 min-w-max">
                                <span>Share</span>
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

                        <div x-show="open" x-transition ... class="absolute left-0 top-full pt-2 z-50 w-48" style="display: none;">
                            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-xl overflow-hidden p-1.5">
                                <flux:menu.item href="#" class="!text-zinc-600 dark:!text-zinc-400 hover:!bg-zinc-100 dark:hover:!bg-zinc-800 transition-colors">Help Center</flux:menu.item>
                                <flux:menu.item href="#" class="!text-zinc-600 dark:!text-zinc-400 hover:!bg-zinc-100 dark:hover:!bg-zinc-800 transition-colors">Contact Support</flux:menu.item>
                                <flux:menu.separator />
                                <flux:menu.item href="#" class="!text-zinc-600 dark:!text-zinc-400 hover:!bg-zinc-100 dark:hover:!bg-zinc-800 transition-colors">Community Discord</flux:menu.item>
                            </div>
                        </div>
                    </div>
                </flux:navbar>

                <span class="mx-2 text-zinc-200 dark:text-zinc-800 font-light select-none">|</span>

                <flux:tooltip content="Toggle appearance" position="bottom">
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
                            <flux:button href="{{ route('login') }}" variant="primary" size="sm" class="group/signin px-5 font-bold shadow-sm transition-all hover:scale-[1.02] active:scale-95">
                                <span>Sign in</span>
                                <flux:icon name="arrow-right" class="w-4 h-4 ml-1.5 transition-transform group-hover/signin:translate-x-1" />
                            </flux:button>
                        </flux:tooltip>
                    @endauth
                @endif
            </div>
        </div>
    </flux:header>

    <main class="container max-w-7xl mx-auto px-6 py-24 text-center">
        <h1 class="text-4xl font-bold">Citation Generator</h1>
        <p class="mt-4 text-zinc-500">Coming soon...</p>
    </main>

    @fluxScripts
</body>
</html>
