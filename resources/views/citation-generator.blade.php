<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head', ['title' => __('Citation Generator')])
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: transparent;
            border-radius: 10px;
            border: 2px solid transparent;
            background-clip: padding-box;
        }

        .custom-scrollbar:hover::-webkit-scrollbar-thumb {
            background: #e4e4e7;
        }

        .dark .custom-scrollbar:hover::-webkit-scrollbar-thumb {
            background: #27272a;
        }

        .visible-scrollbar::-webkit-scrollbar-thumb {
            background: #d4d4d8;
        }

        .dark .visible-scrollbar::-webkit-scrollbar-thumb {
            background: #3f3f46;
        }
    </style>
</head>

<body
    class="min-h-screen font-sans antialiased bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 overflow-x-hidden">
    <!-- Navbar (Shared) -->
    <flux:header sticky
        class="sticky top-0 w-full z-50 border-b bg-white/95 dark:bg-zinc-900/95 border-zinc-200 dark:border-zinc-800 shadow-sm backdrop-blur-md py-3">
        <div class="container max-w-7xl mx-auto px-6 flex items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="/" class="flex items-center gap-2 group">
                    <div class="flex gap-0.5 items-end h-5">
                        <div class="w-1 bg-zinc-900 dark:bg-white h-2 rounded-full transition-all group-hover:h-4"></div>
                        <div class="w-1 bg-zinc-900 dark:bg-white h-4 rounded-full transition-all group-hover:h-3"></div>
                        <div class="w-1 bg-zinc-900 dark:bg-white h-5 rounded-full transition-all group-hover:h-2">
                        </div>
                    </div>
                    <span class="relative text-xl font-bold tracking-tight">
                        Babybib
                        <span class="absolute -right-5 -top-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-zinc-400 dark:text-zinc-500">v2</span>
                    </span>
                </a>
            </div>

            <div class="flex items-center gap-1">
                <flux:navbar class="hidden md:flex gap-1 text-sm font-medium text-zinc-500 mr-2">
                    <flux:tooltip content="{{ __('User Manual') }}" position="bottom">
                        <flux:navbar.item href="{{ route('manual') }}">{{ __('Manual') }}</flux:navbar.item>
                    </flux:tooltip>

                    <flux:tooltip content="{{ __('Citation Generator') }}" position="bottom">
                        <flux:navbar.item href="{{ route('citation-generator') }}" current>{{ __('สร้างบรรณานุกรม') }}
                        </flux:navbar.item>
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
                            <div
                                class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-xl overflow-hidden p-1.5">
                                <flux:menu.item href="#"
                                    class="!text-zinc-600 dark:!text-zinc-400 hover:!bg-zinc-100 dark:hover:!bg-zinc-800 transition-colors">
                                    {{ __('Help Center') }}</flux:menu.item>
                                <flux:menu.item href="#"
                                    class="!text-zinc-600 dark:!text-zinc-400 hover:!bg-zinc-100 dark:hover:!bg-zinc-800 transition-colors">
                                    {{ __('Contact Support') }}</flux:menu.item>
                                <flux:menu.separator />
                                <flux:menu.item href="#"
                                    class="!text-zinc-600 dark:!text-zinc-400 hover:!bg-zinc-100 dark:hover:!bg-zinc-800 transition-colors">
                                    {{ __('Community Discord') }}</flux:menu.item>
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
                            <div
                                class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-xl overflow-hidden p-1.5">
                                <flux:menu.item href="#" icon="facebook"
                                    class="!text-zinc-600 dark:!text-zinc-400 hover:!bg-zinc-100 dark:hover:!bg-zinc-800 transition-colors">
                                    Facebook</flux:menu.item>
                                <flux:menu.item href="#" icon="instagram"
                                    class="!text-zinc-600 dark:!text-zinc-400 hover:!bg-zinc-100 dark:hover:!bg-zinc-800 transition-colors">
                                    Instagram</flux:menu.item>
                                <flux:menu.item href="#" icon="twitter"
                                    class="!text-zinc-600 dark:!text-zinc-400 hover:!bg-zinc-100 dark:hover:!bg-zinc-800 transition-colors">
                                    X (Twitter)</flux:menu.item>
                                <flux:menu.item href="#" icon="line"
                                    class="!text-zinc-600 dark:!text-zinc-400 hover:!bg-zinc-100 dark:hover:!bg-zinc-800 transition-colors">
                                    Line</flux:menu.item>
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
                        <div
                            class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-xl overflow-hidden p-1.5">
                            <flux:menu.item href="{{ route('change-language', 'en') }}"
                                class="!text-zinc-600 dark:!text-zinc-400 hover:!bg-zinc-100 dark:hover:!bg-zinc-800 transition-colors">
                                English (EN)</flux:menu.item>
                            <flux:menu.item href="{{ route('change-language', 'th') }}"
                                class="!text-zinc-600 dark:!text-zinc-400 hover:!bg-zinc-100 dark:hover:!bg-zinc-800 transition-colors">
                                ไทย (TH)</flux:menu.item>
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
                            <flux:button href="{{ url('/dashboard') }}" variant="ghost">{{ __('Dashboard') }}
                            </flux:button>
                        </flux:tooltip>
                    @else
                        <flux:tooltip content="{{ __('Sign in to your account') }}" position="bottom">
                            <flux:button href="{{ route('login') }}" variant="primary" size="sm"
                                class="group/signin px-5 font-bold shadow-sm transition-all hover:scale-[1.02] active:scale-95">
                                <span>{{ __('Sign in') }}</span>
                                <flux:icon name="arrow-right"
                                    class="w-4 h-4 ml-1.5 transition-transform group-hover/signin:translate-x-1" />
                            </flux:button>
                        </flux:tooltip>
                    @endauth
                @endif
            </div>
        </div>
    </flux:header>
    @php
        $citationGroups = [
            [
                'label' => 'หนังสือ',
                'icon' => 'book-open',
                'accent' => 'amber',
                'items' => [
                    'หนังสือ',
                    'หนังสือชุดหลายเล่มจบ',
                    'บทความในหนังสือ',
                    'หนังสืออิเล็กทรอนิกส์ (มี DOI)',
                    'หนังสืออิเล็กทรอนิกส์ (ไม่มี DOI)',
                ],
            ],
            [
                'label' => 'วารสาร',
                'icon' => 'document-text',
                'accent' => 'sky',
                'items' => [
                    'บทความวารสาร',
                    'บทความวารสารอิเล็กทรอนิกส์ (มี DOI)',
                    'บทความวารสารอิเล็กทรอนิกส์ (ไม่มี DOI)',
                    'วารสารอิเล็กทรอนิกส์ (แบบมีฉบับพิมพ์)',
                    'วารสารอิเล็กทรอนิกส์ (แบบไม่มีฉบับพิมพ์)',
                ],
            ],
            [
                'label' => 'พจนานุกรม/สารานุกรม',
                'icon' => 'language',
                'accent' => 'emerald',
                'items' => [
                    'พจนานุกรม',
                    'พจนานุกรมออนไลน์',
                    'สารานุกรม',
                    'สารานุกรมออนไลน์',
                ],
            ],
            [
                'label' => 'หนังสือพิมพ์',
                'icon' => 'newspaper',
                'accent' => 'rose',
                'items' => [
                    'หนังสือพิมพ์แบบรูปเล่ม',
                    'หนังสือพิมพ์ออนไลน์',
                ],
            ],
            [
                'label' => 'รายงาน',
                'icon' => 'clipboard-document-list',
                'accent' => 'violet',
                'items' => [
                    'รายงาน',
                    'รายงานการวิจัย',
                    'รายงานที่จัดทำโดยหน่วยงานราชการหรือองค์กรอื่น',
                    'รายงานที่จัดทำโดยบุคคลที่สังกัดหน่วยงาน',
                ],
            ],
            [
                'label' => 'งานประชุม',
                'icon' => 'presentation-chart-bar',
                'accent' => 'orange',
                'items' => [
                    'เอกสารการประชุมทางวิชาการ (ที่มี Proceeding)',
                    'เอกสารการประชุมทางวิชาการ (ที่ไม่มี Proceeding)',
                    'การนำเสนองานวิจัยหรือโปสเตอร์ในงานประชุมวิชาการ',
                ],
            ],
            [
                'label' => 'วิทยานิพนธ์',
                'icon' => 'academic-cap',
                'accent' => 'indigo',
                'items' => [
                    'วิทยานิพนธ์ (ที่ไม่ได้ตีพิมพ์)',
                    'วิทยานิพนธ์จากเว็บไซต์',
                    'วิทยานิพนธ์จากฐานข้อมูลเชิงพาณิชย์',
                ],
            ],
            [
                'label' => 'ออนไลน์',
                'icon' => 'globe-alt',
                'accent' => 'cyan',
                'items' => [
                    'เอกสารอิเล็กทรอนิกส์ (เว็บเพจ)',
                    'สื่อออนไลน์ (วิดีโอออนไลน์ บทความในโซเชียลมีเดีย)',
                    'ราชกิจจานุเบกษาออนไลน์',
                    'สิทธิบัตรออนไลน์',
                    'การติดต่อสื่อสารส่วนบุคคล',
                ],
            ],
            [
                'label' => 'สื่อภาพ/เสียง',
                'icon' => 'play-circle',
                'accent' => 'fuchsia',
                'items' => [
                    'อินโฟกราฟิก (Infographic)',
                    'การนำเสนอด้วยสไลด์และเอกสารการสอนออนไลน์',
                    'สัมมนาออนไลน์ (Webinar)',
                    'วิดีโอใน Youtube หรือวิดีโอออนไลน์ต่าง ๆ',
                    'พอดแคสต์ภาพและเสียง (แบบจบในตอน)',
                    'พอดแคสต์ภาพและเสียง (แบบหลายตอน)',
                ],
            ],
            [
                'label' => 'อื่นๆ',
                'icon' => 'sparkles',
                'accent' => 'teal',
                'items' => [
                    'AI (เนื้อหาที่สร้างโดย AI)',
                ],
            ],
        ];
    @endphp

    <div class="relative mx-auto flex max-w-[1400px] flex-col gap-6 px-4 pb-24 pt-6 sm:px-6 lg:flex-row lg:gap-10 lg:pt-10">
        <aside x-data="{ search: '', shortcut: navigator.platform.toUpperCase().indexOf('MAC') >= 0 ? '⌘K' : 'Ctrl+K' }"
            class="custom-scrollbar w-full shrink-0 overflow-hidden rounded-2xl border border-zinc-200/80 bg-zinc-50/60 p-4 dark:border-zinc-800 dark:bg-zinc-900/50 lg:block lg:w-64 lg:rounded-none lg:border-0 lg:bg-transparent lg:p-0 lg:h-[calc(100vh-10rem)] lg:overflow-y-auto lg:pr-4 lg:visible-scrollbar">
            <nav class="max-h-[52vh] space-y-7 overflow-y-auto pr-1 custom-scrollbar lg:max-h-none lg:overflow-visible lg:pr-0">
                <div class="mb-4">
                    <div class="group relative" x-on:keydown.window.prevent.cmd.k="$refs.searchInput.focus()"
                        x-on:keydown.window.prevent.ctrl.k="$refs.searchInput.focus()">
                        <div
                            class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-zinc-400 transition-colors group-focus-within:text-zinc-600 dark:group-focus-within:text-zinc-200">
                            <flux:icon name="magnifying-glass" class="size-4" />
                        </div>
                        <input x-ref="searchInput" x-model.debounce.150ms="search" type="text"
                            placeholder="ค้นหาประเภททรัพยากร..."
                            class="w-full rounded-xl border border-zinc-200 bg-zinc-50 py-2 pl-9 pr-12 text-sm placeholder:text-zinc-400 focus:border-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-900/5 dark:border-zinc-800 dark:bg-zinc-950 dark:focus:border-zinc-600 dark:focus:ring-white/5">
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                            <kbd class="hidden rounded border border-zinc-200 bg-white px-1.5 py-0.5 font-sans text-[10px] font-medium text-zinc-400 dark:border-zinc-800 dark:bg-zinc-950 lg:inline-flex"
                                x-text="shortcut"></kbd>
                        </div>
                    </div>
                </div>

                @foreach ($citationGroups as $group)
                    @php
                        $searchableText = $group['label'].' '.implode(' ', $group['items']);
                    @endphp
                    <div class="space-y-3"
                        x-show="@js($searchableText).toLowerCase().includes(search.toLowerCase())"
                        x-transition.opacity.duration.150ms>
                        @php
                            $accentClasses = match ($group['accent']) {
                                'amber' => 'bg-amber-50 text-amber-700 ring-amber-200 dark:bg-amber-500/10 dark:text-amber-300 dark:ring-amber-400/20',
                                'sky' => 'bg-sky-50 text-sky-700 ring-sky-200 dark:bg-sky-500/10 dark:text-sky-300 dark:ring-sky-400/20',
                                'emerald' => 'bg-emerald-50 text-emerald-700 ring-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-300 dark:ring-emerald-400/20',
                                'rose' => 'bg-rose-50 text-rose-700 ring-rose-200 dark:bg-rose-500/10 dark:text-rose-300 dark:ring-rose-400/20',
                                'violet' => 'bg-violet-50 text-violet-700 ring-violet-200 dark:bg-violet-500/10 dark:text-violet-300 dark:ring-violet-400/20',
                                'orange' => 'bg-orange-50 text-orange-700 ring-orange-200 dark:bg-orange-500/10 dark:text-orange-300 dark:ring-orange-400/20',
                                'indigo' => 'bg-indigo-50 text-indigo-700 ring-indigo-200 dark:bg-indigo-500/10 dark:text-indigo-300 dark:ring-indigo-400/20',
                                'cyan' => 'bg-cyan-50 text-cyan-700 ring-cyan-200 dark:bg-cyan-500/10 dark:text-cyan-300 dark:ring-cyan-400/20',
                                'fuchsia' => 'bg-fuchsia-50 text-fuchsia-700 ring-fuchsia-200 dark:bg-fuchsia-500/10 dark:text-fuchsia-300 dark:ring-fuchsia-400/20',
                                default => 'bg-teal-50 text-teal-700 ring-teal-200 dark:bg-teal-500/10 dark:text-teal-300 dark:ring-teal-400/20',
                            };

                            $headingClasses = match ($group['accent']) {
                                'amber' => 'text-amber-700 dark:text-amber-300',
                                'sky' => 'text-sky-700 dark:text-sky-300',
                                'emerald' => 'text-emerald-700 dark:text-emerald-300',
                                'rose' => 'text-rose-700 dark:text-rose-300',
                                'violet' => 'text-violet-700 dark:text-violet-300',
                                'orange' => 'text-orange-700 dark:text-orange-300',
                                'indigo' => 'text-indigo-700 dark:text-indigo-300',
                                'cyan' => 'text-cyan-700 dark:text-cyan-300',
                                'fuchsia' => 'text-fuchsia-700 dark:text-fuchsia-300',
                                default => 'text-teal-700 dark:text-teal-300',
                            };

                            $activeClasses = match ($group['accent']) {
                                'amber' => 'border-amber-600 text-amber-800 dark:border-amber-300 dark:text-amber-100',
                                'sky' => 'border-sky-600 text-sky-800 dark:border-sky-300 dark:text-sky-100',
                                'emerald' => 'border-emerald-600 text-emerald-800 dark:border-emerald-300 dark:text-emerald-100',
                                'rose' => 'border-rose-600 text-rose-800 dark:border-rose-300 dark:text-rose-100',
                                'violet' => 'border-violet-600 text-violet-800 dark:border-violet-300 dark:text-violet-100',
                                'orange' => 'border-orange-600 text-orange-800 dark:border-orange-300 dark:text-orange-100',
                                'indigo' => 'border-indigo-600 text-indigo-800 dark:border-indigo-300 dark:text-indigo-100',
                                'cyan' => 'border-cyan-600 text-cyan-800 dark:border-cyan-300 dark:text-cyan-100',
                                'fuchsia' => 'border-fuchsia-600 text-fuchsia-800 dark:border-fuchsia-300 dark:text-fuchsia-100',
                                default => 'border-teal-600 text-teal-800 dark:border-teal-300 dark:text-teal-100',
                            };
                        @endphp

                        <h3 class="flex items-center gap-2 text-sm font-medium {{ $headingClasses }}">
                            <span class="inline-flex size-7 items-center justify-center rounded-lg ring-1 {{ $accentClasses }}">
                                <flux:icon :name="$group['icon']" class="size-4" />
                            </span>
                            {{ $group['label'] }}
                        </h3>

                        <div class="border-l border-zinc-200 pl-5 dark:border-zinc-800">
                            <div class="space-y-1">
                                @foreach ($group['items'] as $item)
                                    <button type="button"
                                        x-show="@js($item).toLowerCase().includes(search.toLowerCase()) || @js($group['label']).toLowerCase().includes(search.toLowerCase())"
                                        class="{{ $loop->first ? "border-l-2 pl-4 -ml-[21px] font-semibold {$activeClasses}" : 'border-l-2 border-transparent pl-4 text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100' }} block w-full text-left text-sm leading-6 transition">
                                        {{ $item }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </nav>
        </aside>

        <main class="min-w-0 flex-1">
            <div class="grid gap-3 lg:grid-cols-7">
                <section x-data="{ smartQuery: '', selectedType: '', modalOpen: false, copied: false, exportOpen: false, citationStyle: 'apa7', displayMode: 'paper' }"
                    class="lg:col-span-6">
                    <div class="flex min-h-[calc(100vh-8rem)] flex-col pr-2">
                        <div class="mx-auto w-full max-w-3xl space-y-3">
                            <div class="group relative">
                                <div class="absolute left-4 top-0 z-10 flex -translate-y-1/2 items-center gap-3 bg-white px-1 dark:bg-zinc-900">
                                    <button type="button" x-on:click="modalOpen = true"
                                        class="inline-flex items-center gap-1.5 border-b-2 border-transparent pb-1 text-xs font-semibold text-zinc-900 transition hover:border-pink-400 hover:text-zinc-600 focus:outline-none dark:text-zinc-100 dark:hover:border-pink-500 dark:hover:text-zinc-300"
                                        aria-label="เปิดฟอร์มกรอกข้อมูลเอง">
                                        <flux:icon name="plus" class="size-3.5" />
                                        <span>เลือกทรัพยากร</span>
                                    </button>

                                    <button type="button" x-on:click="smartQuery = 'เว็บเพจ'; selectedType = 'เว็บเพจ'"
                                        class="relative border-b-2 border-transparent pb-1 text-xs font-medium transition hover:border-pink-400 dark:hover:border-pink-500"
                                        x-bind:class="selectedType === 'เว็บเพจ'
                                            ? 'border-pink-500 text-zinc-900 dark:border-pink-500 dark:text-zinc-100'
                                            : 'text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100'">
                                        เว็บเพจ
                                    </button>

                                    <button type="button" x-on:click="smartQuery = 'หนังสือ'; selectedType = 'หนังสือ'"
                                        class="relative border-b-2 border-transparent pb-1 text-xs font-medium transition hover:border-pink-400 dark:hover:border-pink-500"
                                        x-bind:class="selectedType === 'หนังสือ'
                                            ? 'border-pink-500 text-zinc-900 dark:border-pink-500 dark:text-zinc-100'
                                            : 'text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100'">
                                        หนังสือ
                                    </button>

                                    <button type="button" x-on:click="smartQuery = 'บทความ'; selectedType = 'บทความ'"
                                        class="relative border-b-2 border-transparent pb-1 text-xs font-medium transition hover:border-pink-400 dark:hover:border-pink-500"
                                        x-bind:class="selectedType === 'บทความ'
                                            ? 'border-pink-500 text-zinc-900 dark:border-pink-500 dark:text-zinc-100'
                                            : 'text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100'">
                                        บทความ
                                    </button>
                                </div>
                                <div
                                    class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-zinc-400 transition-colors group-focus-within:text-zinc-600 dark:group-focus-within:text-zinc-200">
                                    <flux:icon name="magnifying-glass" class="size-5" />
                                </div>
                                <input x-model="smartQuery" type="text"
                                    placeholder="Smart search: ค้นหาประเภทอ้างอิง ผู้แต่ง DOI หรือคำสำคัญ..."
                                    class="w-full rounded-2xl border border-zinc-200 bg-white py-3.5 pl-12 pr-28 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-900/5 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-zinc-500 dark:focus:ring-white/5">
                                <div class="absolute right-3 top-0 -translate-y-1/2">
                                    <span class="inline-flex items-center gap-1.5 bg-white px-1 text-[11px] font-medium text-zinc-400 dark:bg-zinc-900 dark:text-zinc-500">
                                        <flux:icon name="question-mark-circle" class="size-3.5" />
                                        ช่วยเหลือ
                                    </span>
                                </div>
                            </div>

                            <div class="grid gap-3 p-1 lg:grid-cols-[minmax(0,0.9fr)_minmax(0,1.4fr)_auto]">
                                <label class="grid max-w-xs gap-1.5 text-xs font-medium text-zinc-500 dark:text-zinc-400">
                                    <span class="flex items-center justify-between gap-3">
                                        <span>รูปแบบบรรณานุกรม</span>
                                        <a href="{{ url('/manual') }}"
                                            class="text-[11px] font-medium text-zinc-900 underline underline-offset-2 transition hover:text-pink-500 dark:text-zinc-100 dark:hover:text-pink-400">
                                            เรียนรู้เพิ่มเติม
                                        </a>
                                    </span>
                                    <flux:select x-model="citationStyle" placeholder="">
                                        <option disabled>เลือกรูปแบบเพื่อจัดรูปแบบบรรณานุกรมและ citation ของคุณ</option>
                                        <flux:select.option value="apa7">APA 7th</flux:select.option>
                                        <flux:select.option value="mla9">MLA 9th</flux:select.option>
                                        <flux:select.option value="chicago17">Chicago 17th</flux:select.option>
                                    </flux:select>
                                </label>

                                <div class="grid items-center justify-items-center gap-1.5 text-center text-xs font-medium text-zinc-500 dark:text-zinc-400">
                                    <span>รูปแบบการแสดง</span>
                                    <div class="flex items-center justify-center gap-2">
                                        <flux:tooltip content="มุมมองแบบกระดาษ" position="top">
                                            <button type="button" x-on:click="displayMode = 'paper'"
                                                class="inline-flex size-10 items-center justify-center rounded-xl border border-zinc-200 bg-white transition dark:border-zinc-700 dark:bg-zinc-950"
                                                x-bind:class="displayMode === 'paper' ? 'text-zinc-900 ring-2 ring-zinc-900/10 dark:text-zinc-100 dark:ring-white/10' : 'text-zinc-400 hover:text-zinc-900 dark:text-zinc-500 dark:hover:text-zinc-100'">
                                                <flux:icon name="document-text" class="size-4" />
                                            </button>
                                        </flux:tooltip>
                                        <flux:tooltip content="มุมมองแบบรายการ" position="top">
                                            <button type="button" x-on:click="displayMode = 'list'"
                                                class="inline-flex size-10 items-center justify-center rounded-xl border border-zinc-200 bg-white transition dark:border-zinc-700 dark:bg-zinc-950"
                                                x-bind:class="displayMode === 'list' ? 'text-zinc-900 ring-2 ring-zinc-900/10 dark:text-zinc-100 dark:ring-white/10' : 'text-zinc-400 hover:text-zinc-900 dark:text-zinc-500 dark:hover:text-zinc-100'">
                                                <flux:icon name="list-bullet" class="size-4" />
                                            </button>
                                        </flux:tooltip>
                                        <flux:tooltip content="แสดงเฉพาะ citation" position="top">
                                            <button type="button" x-on:click="displayMode = 'citation'"
                                                class="inline-flex size-10 items-center justify-center rounded-xl border border-zinc-200 bg-white transition dark:border-zinc-700 dark:bg-zinc-950"
                                                x-bind:class="displayMode === 'citation' ? 'text-zinc-900 ring-2 ring-zinc-900/10 dark:text-zinc-100 dark:ring-white/10' : 'text-zinc-400 hover:text-zinc-900 dark:text-zinc-500 dark:hover:text-zinc-100'">
                                                <flux:icon name="chat-bubble-bottom-center-text" class="size-4" />
                                            </button>
                                        </flux:tooltip>
                                    </div>
                                </div>

                                <div class="flex flex-col gap-2 lg:items-end">
                                    <span class="hidden h-[18px] text-xs font-medium text-transparent lg:block">actions</span>
                                    <div class="flex gap-2">
                                        <button type="button"
                                            x-on:click="navigator.clipboard.writeText(((displayMode === 'list' ? $refs.listView : (displayMode === 'citation' ? $refs.citationView : $refs.paperView)).innerText || '').trim()); copied = true; setTimeout(() => copied = false, 1800)"
                                            class="inline-flex h-10 items-center gap-2 rounded-xl border border-zinc-200 bg-white px-3.5 text-sm font-medium text-zinc-700 transition hover:border-zinc-300 hover:text-zinc-900 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-200 dark:hover:border-zinc-500 dark:hover:text-zinc-100">
                                            <template x-if="!copied">
                                                <flux:icon name="clipboard-document" class="size-4" />
                                            </template>
                                            <template x-if="copied">
                                                <flux:icon name="check" class="size-4 text-emerald-500" />
                                            </template>
                                                <span x-text="copied ? 'คัดลอกแล้ว' : 'Copy'"></span>
                                        </button>

                                        <div class="relative">
                                            <button type="button" x-on:click="exportOpen = !exportOpen"
                                                class="inline-flex h-10 items-center gap-2 rounded-xl border border-zinc-200 bg-white px-3.5 text-sm font-medium text-zinc-700 transition hover:border-zinc-300 hover:text-zinc-900 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-200 dark:hover:border-zinc-500 dark:hover:text-zinc-100">
                                                <flux:icon name="arrow-down-tray" class="size-4" />
                                                <span>ส่งออก</span>
                                            </button>

                                            <div x-cloak x-show="exportOpen" x-transition.opacity x-on:click.outside="exportOpen = false"
                                                class="absolute right-0 z-20 mt-2 min-w-36 rounded-xl border border-zinc-200 bg-white p-1.5 shadow-lg dark:border-zinc-700 dark:bg-zinc-950">
                                                <button type="button" x-on:click="exportOpen = false"
                                                    class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm text-zinc-600 transition hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-300 dark:hover:bg-zinc-900 dark:hover:text-zinc-100">
                                                    <flux:icon name="document-text" class="size-4" />
                                                    Word
                                                </button>
                                                <button type="button" x-on:click="window.print(); exportOpen = false"
                                                    class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm text-zinc-600 transition hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-300 dark:hover:bg-zinc-900 dark:hover:text-zinc-100">
                                                    <flux:icon name="document" class="size-4" />
                                                    PDF
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div x-cloak x-show="modalOpen" x-transition.opacity
                            class="fixed inset-0 z-50 flex items-center justify-center bg-zinc-950/40 px-4 backdrop-blur-sm">
                            <div x-show="modalOpen" x-transition
                                class="w-full max-w-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="space-y-1">
                                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">กรอกข้อมูลบรรณานุกรมเอง</h3>
                                        <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                            เพิ่มข้อมูลอ้างอิงด้วยตนเองในกรณีที่ smart search ยังไม่ตรงกับสิ่งที่ต้องการ
                                        </p>
                                    </div>
                                    <button type="button" x-on:click="modalOpen = false"
                                        class="text-zinc-400 transition hover:text-zinc-900 dark:hover:text-zinc-100"
                                        aria-label="ปิดหน้าต่าง">
                                        <flux:icon name="x-mark" class="size-5" />
                                    </button>
                                </div>

                                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                                    <label class="grid gap-2 text-sm text-zinc-600 dark:text-zinc-300">
                                        <span>ประเภททรัพยากร</span>
                                        <input type="text" placeholder="เช่น หนังสือ, บทความวารสาร"
                                            class="w-full border border-zinc-200 bg-white px-3 py-2.5 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-900/5 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-zinc-500 dark:focus:ring-white/5">
                                    </label>

                                    <label class="grid gap-2 text-sm text-zinc-600 dark:text-zinc-300">
                                        <span>ปีที่พิมพ์</span>
                                        <input type="text" placeholder="2026"
                                            class="w-full border border-zinc-200 bg-white px-3 py-2.5 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-900/5 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-zinc-500 dark:focus:ring-white/5">
                                    </label>

                                    <label class="grid gap-2 text-sm text-zinc-600 dark:text-zinc-300 sm:col-span-2">
                                        <span>ชื่อเรื่อง / ชื่อบทความ</span>
                                        <input type="text" placeholder="กรอกชื่อทรัพยากร"
                                            class="w-full border border-zinc-200 bg-white px-3 py-2.5 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-900/5 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-zinc-500 dark:focus:ring-white/5">
                                    </label>

                                    <label class="grid gap-2 text-sm text-zinc-600 dark:text-zinc-300 sm:col-span-2">
                                        <span>ผู้แต่ง</span>
                                        <input type="text" placeholder="เช่น นามสกุล, ชื่อย่อ"
                                            class="w-full border border-zinc-200 bg-white px-3 py-2.5 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-900/5 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-zinc-500 dark:focus:ring-white/5">
                                    </label>
                                </div>

                                <div class="mt-6 flex justify-end gap-3">
                                    <button type="button" x-on:click="modalOpen = false"
                                        class="px-4 py-2 text-sm font-medium text-zinc-500 transition hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100">
                                        ยกเลิก
                                    </button>
                                    <button type="button"
                                        class="inline-flex items-center gap-2 bg-zinc-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-zinc-800 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-white">
                                        <flux:icon name="plus" class="size-4" />
                                        เพิ่มรายการ
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-1 justify-center pt-6 lg:pt-8">
                            <template x-if="displayMode === 'paper'">
                                <div x-ref="paperView"
                                    class="flex min-h-[calc(100vh-15rem)] w-full max-w-3xl flex-1 flex-col border border-zinc-200 bg-white px-8 py-10 dark:border-zinc-700 dark:bg-zinc-950">
                                    <div class="mx-auto flex h-full w-full max-w-xl flex-col space-y-6 text-zinc-800 dark:text-zinc-100">
                                        <div class="space-y-2 border-b border-dashed border-zinc-200 pb-5 dark:border-zinc-700">
                                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-zinc-400 dark:text-zinc-500"
                                                x-text="citationStyle === 'apa7' ? 'APA 7th Preview' : (citationStyle === 'mla9' ? 'MLA 9th Preview' : 'Chicago 17th Preview')"></p>
                                            <h3 class="text-2xl font-semibold tracking-tight">Your formatted reference will appear here</h3>
                                        </div>

                                        <div class="space-y-4 text-[15px] leading-8 text-zinc-600 dark:text-zinc-300">
                                            <p>
                                                กรอกข้อมูลของทรัพยากรทางด้านซ้าย แล้วระบบจะแสดงรูปแบบบรรณานุกรมที่จัดเรียงแล้วในพื้นที่นี้
                                            </p>
                                            <p>
                                                คุณสามารถใช้พื้นที่นี้เพื่อตรวจสอบการสะกด ชื่อผู้แต่ง ปีที่พิมพ์ DOI และองค์ประกอบอื่น ๆ ก่อนคัดลอกไปใช้งานจริง
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <template x-if="displayMode === 'list'">
                                <div x-ref="listView"
                                    class="w-full max-w-3xl border border-zinc-200 bg-white px-8 py-8 dark:border-zinc-700 dark:bg-zinc-950">
                                    <div class="space-y-5">
                                        <div class="border-b border-zinc-200 pb-4 dark:border-zinc-700">
                                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">Reference List</h3>
                                            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">แสดงผลรายการอ้างอิงแบบลิสต์ตามรูปแบบที่เลือก</p>
                                        </div>
                                        <ul class="space-y-4 text-[15px] leading-8 text-zinc-700 dark:text-zinc-300">
                                            <li>Smith, J. (2026). <em>Designing better citations</em>. Babybib Press.</li>
                                            <li>Johnson, A. (2025). Smart search for citation workflows. <em>Journal of Digital Research</em>, 18(2), 44-61.</li>
                                            <li>Babybib Team. (2026, April 2). Citation generator manual. https://babybib.app/manual</li>
                                        </ul>
                                    </div>
                                </div>
                            </template>

                            <template x-if="displayMode === 'citation'">
                                <div x-ref="citationView"
                                    class="w-full max-w-3xl border border-zinc-200 bg-white px-8 py-10 dark:border-zinc-700 dark:bg-zinc-950">
                                    <div class="space-y-4">
                                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-zinc-400 dark:text-zinc-500">Single Citation</p>
                                        <p class="text-lg leading-9 text-zinc-800 dark:text-zinc-100">
                                            Smith, J. (2026). <em>Designing better citations</em>. Babybib Press.
                                        </p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </section>

                <section class="custom-scrollbar h-[calc(100vh-10rem)] overflow-y-auto lg:col-span-1">
                    <div class="pr-2">
                        <flux:heading size="lg" level="2">Saved Items</flux:heading>
                        <flux:text class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                            Keep recent citations nearby so you can reuse or export them later.
                        </flux:text>
                    </div>
                </section>
            </div>
        </main>
    </div>
    @fluxScripts
</body>

</html>
