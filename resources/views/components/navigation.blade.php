<nav x-data="{ open: false }" class="sticky top-0 z-50 w-full group/nav">
    <!-- Paper-like Backdrop -->
    <div class="absolute inset-0 transition-all duration-500 border-b bg-white/80 dark:bg-zinc-950/80 backdrop-blur-xl border-zinc-200/60 dark:border-white/5"></div>
    
    <div class="relative px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-14"> <!-- Slightly thinner for a tool feel -->
            <!-- Logo & Title Section -->
            <div class="flex items-center flex-shrink-0 gap-6">
                <a href="{{ url('/') }}" class="flex items-center gap-3 group/logo">
                    <div class="relative flex items-center justify-center w-8 h-8 transition-all duration-500 rounded-md bg-zinc-900 dark:bg-white group-hover/logo:ring-2 group-hover/logo:ring-zinc-400 dark:group-hover/logo:ring-zinc-500 group-hover/logo:scale-105">
                        <x-app-logo-icon class="w-5 h-5 text-white dark:text-zinc-900" />
                    </div>
                    <div class="flex flex-col leading-none">
                        <span class="text-sm font-bold tracking-tight text-zinc-900 dark:text-white">
                            {{ config('app.name', 'Babybib') }}
                        </span>
                        <span class="text-[10px] font-medium text-zinc-500 uppercase tracking-widest mt-0.5">Citation Tool</span>
                    </div>
                </a>

                <!-- Desktop Sidebar-style Links -->
                <div class="hidden lg:flex items-center gap-1 border-l border-zinc-200 dark:border-white/10 pl-6 ml-0">
                    <a href="#" class="px-3 py-1.5 text-[13px] font-medium text-zinc-500 hover:text-zinc-950 dark:hover:text-white transition-colors">Generator</a>
                    <a href="#" class="px-3 py-1.5 text-[13px] font-medium text-zinc-500 hover:text-zinc-950 dark:hover:text-white transition-colors">Citation Styles</a>
                    <a href="#" class="px-3 py-1.5 text-[13px] font-medium text-zinc-500 hover:text-zinc-950 dark:hover:text-white transition-colors">Resources</a>
                </div>
            </div>

            <!-- Right Actions -->
            <div class="flex items-center gap-2">
                <!-- Theme Toggle (Clean Style) -->
                <div class="hidden sm:flex items-center mr-2">
                    <flux:dropdown align="end">
                        <flux:button variant="ghost" size="sm" icon="sun" />
                        <flux:menu>
                            <flux:menu.item icon="sun" x-on:click="Flux.appearance = 'light'">Light</flux:menu.item>
                            <flux:menu.item icon="moon" x-on:click="Flux.appearance = 'dark'">Dark</flux:menu.item>
                            <flux:menu.item icon="computer-desktop" x-on:click="Flux.appearance = 'system'">System</flux:menu.item>
                        </flux:menu>
                    </flux:dropdown>
                </div>

                @if (Route::has('login'))
                    <div class="flex items-center gap-3">
                        @auth
                            <a href="{{ route('dashboard') }}" 
                               class="inline-flex items-center px-4 py-1.5 text-[13px] font-semibold transition-all duration-200 border rounded-lg bg-zinc-900 text-white dark:bg-white dark:text-zinc-950 border-zinc-900 dark:border-white hover:opacity-90">
                                My Library
                                <flux:icon.arrow-right class="w-3.5 h-3.5 ml-1.5" />
                            </a>
                        @else
                            <a href="{{ route('login') }}" 
                               class="hidden md:inline-flex px-3 py-1.5 text-[13px] font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-950 dark:hover:text-white">
                                Sign in
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" 
                                   class="inline-flex items-center px-4 py-1.5 text-[13px] font-semibold text-white bg-indigo-600 dark:bg-indigo-500 rounded-lg shadow-sm hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-all">
                                    Create Account
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif

                <!-- Mobile menu button -->
                <div class="flex items-center lg:hidden ml-2">
                    <button @click="open = !open" 
                            type="button" 
                            class="p-1.5 text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white transition-colors">
                        <svg :class="{'hidden': open, 'block': !open }" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                        <svg :class="{'block': open, 'hidden': !open }" class="hidden w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="lg:hidden border-b bg-white dark:bg-zinc-950 border-zinc-200 dark:border-white/10" 
         id="mobile-menu">
        <div class="px-4 py-4 space-y-3">
            <a href="#" class="block text-sm font-medium text-zinc-600 dark:text-zinc-400">Generator</a>
            <a href="#" class="block text-sm font-medium text-zinc-600 dark:text-zinc-400">Citation Styles</a>
            <hr class="border-zinc-100 dark:border-white/5">
            @if (Route::has('login'))
                @auth
                    <a href="{{ route('dashboard') }}" class="block text-sm font-bold text-zinc-900 dark:text-white">My Library</a>
                @else
                    <a href="{{ route('login') }}" class="block text-sm font-medium text-zinc-600 dark:text-zinc-400">Sign in</a>
                    <a href="{{ route('register') }}" class="block text-sm font-bold text-indigo-600 dark:text-indigo-400">Create Account</a>
                @endauth
            @endif
        </div>
    </div>
</nav>
