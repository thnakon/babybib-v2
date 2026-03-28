<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head', ['title' => 'Welcome'])
</head>
<body class="min-h-screen font-sans antialiased bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 overflow-x-hidden">
    <!-- Background Decorations -->
    <div class="fixed inset-0 pointer-events-none -z-10">
        <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-zinc-100 dark:bg-zinc-900/50 rounded-full blur-[100px] opacity-60"></div>
        <div class="absolute top-[20%] -right-[5%] w-[30%] h-[50%] bg-zinc-50 dark:bg-zinc-900/30 rounded-full blur-[120px] opacity-40 transform rotate-12"></div>
        <div class="absolute -bottom-[10%] left-[15%] w-[50%] h-[30%] bg-zinc-100/80 dark:bg-zinc-900/40 rounded-full blur-[100px] opacity-50"></div>
    </div>

    <flux:header class="container max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-8">
            <a href="/" class="flex items-center gap-2 group">
                <div class="flex gap-0.5 items-end h-5">
                    <div class="w-1 bg-zinc-900 dark:bg-white h-2 rounded-full transition-all group-hover:h-4"></div>
                    <div class="w-1 bg-zinc-900 dark:bg-white h-4 rounded-full transition-all group-hover:h-3"></div>
                    <div class="w-1 bg-zinc-900 dark:bg-white h-5 rounded-full transition-all group-hover:h-2"></div>
                </div>
                <span class="text-xl font-bold tracking-tight">flux</span>
            </a>

            <flux:navbar class="hidden md:flex gap-3 text-sm font-medium text-zinc-500">
                <flux:navbar.item href="#">Docs</flux:navbar.item>
                <flux:navbar.item href="#">Demos</flux:navbar.item>
                <flux:navbar.item href="#">Blog</flux:navbar.item>
                <flux:navbar.item href="#">Themes</flux:navbar.item>
                <flux:navbar.item href="#">Charts</flux:navbar.item>
                <flux:navbar.item href="#">Pricing</flux:navbar.item>
            </flux:navbar>
        </div>
        
        <div class="flex items-center gap-1">
            <button type="button" onclick="window.toggleDarkMode()" class="p-2 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors focus:outline-none">
                <flux:icon name="moon" class="w-5 h-5 dark:hidden" />
                <flux:icon name="sun" class="w-5 h-5 hidden dark:block" />
            </button>
            
            <span class="mx-2 text-zinc-200 dark:text-zinc-800 font-light select-none">|</span>

            @if (Route::has('login'))
                @auth
                    <flux:button href="{{ url('/dashboard') }}" variant="ghost">Dashboard</flux:button>
                @else
                    <flux:button href="{{ route('login') }}" variant="primary" size="sm" class="px-5 font-bold shadow-sm transition-all hover:scale-105 active:scale-95">Log in</flux:button>
                @endauth
            @endif
        </div>
    </flux:header>

    <main class="container max-w-5xl mx-auto px-6 pt-24 pb-32 text-center relative">
        <!-- Hero Section -->
        <div class="flex flex-col items-center gap-6 animate-fade-in-up">
            <a href="#" class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-pink-100 dark:bg-pink-900/30 text-pink-600 dark:text-pink-400 text-xs font-semibold border border-pink-200 dark:border-pink-800/50 hover:bg-pink-200 dark:hover:bg-pink-900/50 transition-all group">
                <div class="flex items-center justify-center w-4 h-4 bg-pink-500 rounded-full">
                    <flux:icon.bolt class="w-3 h-3 text-white fill-current" />
                </div>
                Livewire v4 is here!
                <span class="opacity-70 group-hover:opacity-100 transition-opacity">Read the upgrade guide &rsaquo;</span>
            </a>

            

            <h1 class="text-5xl md:text-7xl font-semibold tracking-tight text-zinc-900 dark:text-white leading-[1.1]">
    The official Livewire <br> component library
</h1>
            
            <p class="max-w-2xl text-lg md:text-xl text-zinc-400 dark:text-zinc-400">
                World-class components, built specifically for your Livewire <br class="hidden md:block"> interfaces. Fully flexible, functional, and accessible.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 mt-4">
                <flux:button href="{{ route('register') }}" variant="primary" class="h-12 px-8 bg-zinc-900 dark:bg-zinc-100 dark:text-zinc-900 font-bold text-base hover:scale-105 transition-transform group">
                    Get started
                    <flux:icon.arrow-right class="ml-2 w-4 h-4 group-hover:translate-x-1 transition-transform" />
                </flux:button>
                <flux:button href="#" variant="ghost" class="h-12 px-8 border border-zinc-200 dark:border-zinc-800 font-bold text-base hover:bg-zinc-50 dark:hover:bg-zinc-900 transition-all">
                    Browse components
                    <span class="ml-2 text-zinc-400">&rsaquo;</span>
                </flux:button>
            </div>
        </div>

        <!-- UI Preview - Settings Page Mock -->
        <div class="mt-24 max-w-6xl mx-auto p-4 md:p-8 bg-zinc-50/50 dark:bg-zinc-900/20 rounded-3xl border border-zinc-200/50 dark:border-zinc-800/50 shadow-2xl backdrop-blur-sm relative overflow-hidden group">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-pink-500 to-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            
            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-xl flex flex-col md:flex-row overflow-hidden aspect-[16/9] md:aspect-auto">
                <!-- Mock Sidebar -->
                <div class="w-full md:w-64 border-r border-zinc-100 dark:border-zinc-800 p-6 flex flex-col gap-6 text-left">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-zinc-900 rounded flex items-center justify-center text-white font-bold">A</div>
                        <span class="font-bold text-sm">Acme Inc.</span>
                    </div>

                    <div class="relative">
                        <flux:icon.magnifying-glass class="absolute left-3 top-2.5 w-4 h-4 text-zinc-400" />
                        <input type="text" placeholder="Search..." class="w-full pl-9 pr-3 py-2 bg-zinc-50 dark:bg-zinc-900 rounded-lg border-none text-xs focus:ring-1 focus:ring-zinc-300 dark:focus:ring-zinc-700">
                    </div>

                    <flux:navlist>
                        <flux:navlist.item icon="home" href="#" current>Home</flux:navlist.item>
                        <flux:navlist.item icon="envelope" href="#">Inbox <span class="ml-auto text-xs text-zinc-400">12</span></flux:navlist.item>
                        <flux:navlist.item icon="document-text" href="#">Documents</flux:navlist.item>
                        <flux:navlist.item icon="calendar" href="#">Calendar</flux:navlist.item>
                        
                        <div class="mt-4 mb-2 text-[10px] uppercase tracking-wider font-bold text-zinc-400">Favorites</div>
                        <flux:navlist.item href="#">Marketing site</flux:navlist.item>
                        <flux:navlist.item href="#">Android app</flux:navlist.item>
                    </flux:navlist>
                </div>

                <!-- Mock Content Area -->
                <div class="flex-1 p-8 md:p-12 text-left bg-white dark:bg-zinc-900">
                    <h2 class="text-3xl font-bold mb-8">Settings</h2>
                    
                    <div class="space-y-12">
                        <div class="flex flex-col md:flex-row gap-8 pb-12 border-b border-zinc-100 dark:border-zinc-900">
                            <div class="w-full md:w-64">
                                <h3 class="font-bold text-sm">Profile</h3>
                                <p class="text-xs text-zinc-400 mt-1">This is how others will see you on the site.</p>
                            </div>
                            <div class="flex-1 space-y-6">
                                <flux:field>
                                    <flux:label>Username</flux:label>
                                    <flux:description>This is your public display name. It can be your real name or a pseudonym. You can only change this once every 30 days.</flux:description>
                                    <flux:input value="calebporzio" />
                                </flux:field>

                                <flux:field>
                                    <flux:label>Primary email</flux:label>
                                    <flux:select placeholder="Select primary email...">
                                        <flux:select.option>caleb@laravel.com</flux:select.option>
                                    </flux:select>
                                    <flux:description>You can manage verified email addresses in your email settings.</flux:description>
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
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fade-in-up 0.8s ease-out forwards;
        }
    </style>
</body>
</html>
