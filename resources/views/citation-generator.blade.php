<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head', ['title' => __('Citation Generator')])
    <style>
        body.bibliography-wallpaper {
            background-image:
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='220' height='220' viewBox='0 0 220 220'%3E%3Cg fill='none' stroke='%23c7d2fe' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round' opacity='0.14'%3E%3Cpath d='M72 58h32c6 0 11 5 11 11v53c-5-4-9-5-15-5H72c-5 0-9 4-9 9V67c0-5 4-9 9-9Z'/%3E%3Cpath d='M115 69h21c6 0 11 5 11 11v43c-4-3-8-4-13-4h-19'/%3E%3Cpath d='M79 76h18M79 88h23M79 100h15'/%3E%3C/g%3E%3C/svg%3E"),
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='240' height='240' viewBox='0 0 240 240'%3E%3Cg fill='none' stroke='%23bae6fd' stroke-width='1.4' stroke-linecap='round' stroke-linejoin='round' opacity='0.12'%3E%3Crect x='90' y='64' width='56' height='72' rx='10'/%3E%3Cpath d='M101 85h34M101 97h34M101 109h22'/%3E%3Cpath d='M106 147c11-9 24-9 35 0'/%3E%3C/g%3E%3C/svg%3E"),
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='260' height='260' viewBox='0 0 260 260'%3E%3Cg fill='none' stroke='%23c4b5fd' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round' opacity='0.12'%3E%3Cpath d='M103 84h54l13 16v63c0 6-5 11-11 11h-56c-6 0-11-5-11-11V95c0-6 5-11 11-11Z'/%3E%3Cpath d='M157 84v16h16'/%3E%3Cpath d='M112 114h38M112 126h30M112 138h21'/%3E%3C/g%3E%3C/svg%3E"),
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='230' height='230' viewBox='0 0 230 230'%3E%3Cg fill='none' stroke='%23bfdbfe' stroke-width='1.4' stroke-linecap='round' stroke-linejoin='round' opacity='0.11'%3E%3Cpath d='M86 123c0-18 13-31 31-31s31 13 31 31'/%3E%3Cpath d='M92 123h50'/%3E%3Cpath d='M102 79h26'/%3E%3Cpath d='M115 68v22'/%3E%3Cpath d='M92 144h46'/%3E%3C/g%3E%3C/svg%3E");
            background-size: 520px 520px, 620px 620px, 700px 700px, 580px 580px;
            background-position: 40px 60px, 320px 260px, 120px 760px, 420px 1120px;
            background-repeat: repeat;
        }

        .dark body.bibliography-wallpaper {
            background-image:
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='220' height='220' viewBox='0 0 220 220'%3E%3Cg fill='none' stroke='%2337367d' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round' opacity='0.15'%3E%3Cpath d='M72 58h32c6 0 11 5 11 11v53c-5-4-9-5-15-5H72c-5 0-9 4-9 9V67c0-5 4-9 9-9Z'/%3E%3Cpath d='M115 69h21c6 0 11 5 11 11v43c-4-3-8-4-13-4h-19'/%3E%3Cpath d='M79 76h18M79 88h23M79 100h15'/%3E%3C/g%3E%3C/svg%3E"),
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='240' height='240' viewBox='0 0 240 240'%3E%3Cg fill='none' stroke='%23244869' stroke-width='1.4' stroke-linecap='round' stroke-linejoin='round' opacity='0.14'%3E%3Crect x='90' y='64' width='56' height='72' rx='10'/%3E%3Cpath d='M101 85h34M101 97h34M101 109h22'/%3E%3Cpath d='M106 147c11-9 24-9 35 0'/%3E%3C/g%3E%3C/svg%3E"),
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='260' height='260' viewBox='0 0 260 260'%3E%3Cg fill='none' stroke='%233f3c88' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round' opacity='0.13'%3E%3Cpath d='M103 84h54l13 16v63c0 6-5 11-11 11h-56c-6 0-11-5-11-11V95c0-6 5-11 11-11Z'/%3E%3Cpath d='M157 84v16h16'/%3E%3Cpath d='M112 114h38M112 126h30M112 138h21'/%3E%3C/g%3E%3C/svg%3E"),
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='230' height='230' viewBox='0 0 230 230'%3E%3Cg fill='none' stroke='%23254b63' stroke-width='1.4' stroke-linecap='round' stroke-linejoin='round' opacity='0.12'%3E%3Cpath d='M86 123c0-18 13-31 31-31s31 13 31 31'/%3E%3Cpath d='M92 123h50'/%3E%3Cpath d='M102 79h26'/%3E%3Cpath d='M115 68v22'/%3E%3Cpath d='M92 144h46'/%3E%3C/g%3E%3C/svg%3E");
        }

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

        .thai-distributed {
            text-align: justify;
            text-align-last: left;
            text-justify: inter-character;
            word-break: break-word;
        }

        .paper-bibliography-font {
            font-family: 'Angsana New', serif;
        }

        @keyframes recent-citation-pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.26);
                transform: translateY(0);
            }

            50% {
                box-shadow: 0 0 0 12px rgba(79, 70, 229, 0);
                transform: translateY(-2px);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(79, 70, 229, 0);
                transform: translateY(0);
            }
        }

        .recent-citation-entry {
            animation: recent-citation-pulse 1.8s ease-out 2;
        }

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

        @media (prefers-reduced-motion: reduce) {
            .brand-mark,
            .brand-mark::after,
            .brand-mark svg,
            .brand-label,
            .brand-label::after,
            .recent-citation-entry {
                animation: none !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>

<body
    class="bibliography-wallpaper min-h-screen overflow-x-hidden bg-slate-50 font-sans antialiased text-zinc-900 dark:bg-slate-950 dark:text-zinc-100">
    <!-- Navbar (Shared) -->
    <flux:header sticky
        class="sticky top-0 z-[80] w-full border-b border-sky-100 bg-slate-50/95 py-3 shadow-sm shadow-indigo-100/40 backdrop-blur-md dark:border-slate-800 dark:bg-slate-950/95">
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
                                class="overflow-hidden rounded-xl border border-sky-100 bg-white p-1.5 shadow-xl shadow-indigo-100/50 dark:border-slate-800 dark:bg-slate-900">
                                <flux:menu.item href="#"
                                    class="!text-zinc-600 transition-colors hover:!bg-sky-50 hover:!text-indigo-700 dark:!text-zinc-400 dark:hover:!bg-indigo-500/10 dark:hover:!text-sky-200">
                                    {{ __('Help Center') }}</flux:menu.item>
                                <flux:menu.item href="#"
                                    class="!text-zinc-600 transition-colors hover:!bg-sky-50 hover:!text-indigo-700 dark:!text-zinc-400 dark:hover:!bg-indigo-500/10 dark:hover:!text-sky-200">
                                    {{ __('Contact Support') }}</flux:menu.item>
                                <flux:menu.separator />
                                <flux:menu.item href="#"
                                    class="!text-zinc-600 transition-colors hover:!bg-sky-50 hover:!text-indigo-700 dark:!text-zinc-400 dark:hover:!bg-indigo-500/10 dark:hover:!text-sky-200">
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
                                class="overflow-hidden rounded-xl border border-sky-100 bg-white p-1.5 shadow-xl shadow-indigo-100/50 dark:border-slate-800 dark:bg-slate-900">
                                <flux:menu.item href="#" icon="facebook"
                                    class="!text-zinc-600 transition-colors hover:!bg-sky-50 hover:!text-indigo-700 dark:!text-zinc-400 dark:hover:!bg-indigo-500/10 dark:hover:!text-sky-200">
                                    Facebook</flux:menu.item>
                                <flux:menu.item href="#" icon="instagram"
                                    class="!text-zinc-600 transition-colors hover:!bg-sky-50 hover:!text-indigo-700 dark:!text-zinc-400 dark:hover:!bg-indigo-500/10 dark:hover:!text-sky-200">
                                    Instagram</flux:menu.item>
                                <flux:menu.item href="#" icon="twitter"
                                    class="!text-zinc-600 transition-colors hover:!bg-sky-50 hover:!text-indigo-700 dark:!text-zinc-400 dark:hover:!bg-indigo-500/10 dark:hover:!text-sky-200">
                                    X (Twitter)</flux:menu.item>
                                <flux:menu.item href="#" icon="line"
                                    class="!text-zinc-600 transition-colors hover:!bg-sky-50 hover:!text-indigo-700 dark:!text-zinc-400 dark:hover:!bg-indigo-500/10 dark:hover:!text-sky-200">
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
                            class="overflow-hidden rounded-xl border border-sky-100 bg-white p-1.5 shadow-xl shadow-indigo-100/50 dark:border-slate-800 dark:bg-slate-900">
                            <flux:menu.item href="{{ route('change-language', 'en') }}"
                                class="!text-zinc-600 transition-colors hover:!bg-sky-50 hover:!text-indigo-700 dark:!text-zinc-400 dark:hover:!bg-indigo-500/10 dark:hover:!text-sky-200">
                                {{ __('English (EN)') }}</flux:menu.item>
                            <flux:menu.item href="{{ route('change-language', 'th') }}"
                                class="!text-zinc-600 transition-colors hover:!bg-sky-50 hover:!text-indigo-700 dark:!text-zinc-400 dark:hover:!bg-indigo-500/10 dark:hover:!text-sky-200">
                                {{ __('ไทย (TH)') }}</flux:menu.item>
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
                        <flux:tooltip content="{{ __('Enter your dashboard') }}" position="bottom">
                            <flux:button href="{{ url('/dashboard') }}" variant="ghost">{{ __('Dashboard') }}
                            </flux:button>
                        </flux:tooltip>
                    @else
                        <flux:tooltip content="{{ __('Sign in to your account') }}" position="bottom">
                            <flux:button href="{{ route('login') }}" variant="primary" size="sm"
                                class="group/signin border-indigo-600 bg-indigo-600 px-5 font-bold shadow-sm shadow-indigo-500/20 transition-all hover:scale-[1.02] hover:bg-indigo-700 active:scale-95 dark:border-indigo-500 dark:bg-indigo-500 dark:hover:bg-sky-500">
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
                'label' => __('หนังสือ'),
                'icon' => 'book-open',
                'accent' => 'amber',
                'items' => [
                    __('หนังสือ'),
                    __('หนังสือชุดหลายเล่มจบ'),
                    __('บทความในหนังสือ'),
                    __('หนังสืออิเล็กทรอนิกส์ (มี DOI)'),
                    __('หนังสืออิเล็กทรอนิกส์ (ไม่มี DOI)'),
                ],
            ],
            [
                'label' => __('วารสาร'),
                'icon' => 'document-text',
                'accent' => 'sky',
                'items' => [
                    __('บทความวารสาร'),
                    __('บทความวารสารอิเล็กทรอนิกส์ (มี DOI)'),
                    __('บทความวารสารอิเล็กทรอนิกส์ (ไม่มี DOI)'),
                    __('วารสารอิเล็กทรอนิกส์ (แบบมีฉบับพิมพ์)'),
                    __('วารสารอิเล็กทรอนิกส์ (แบบไม่มีฉบับพิมพ์)'),
                ],
            ],
            [
                'label' => __('พจนานุกรม/สารานุกรม'),
                'icon' => 'language',
                'accent' => 'emerald',
                'items' => [
                    __('พจนานุกรม'),
                    __('พจนานุกรมออนไลน์'),
                    __('สารานุกรม'),
                    __('สารานุกรมออนไลน์'),
                ],
            ],
            [
                'label' => __('หนังสือพิมพ์'),
                'icon' => 'newspaper',
                'accent' => 'rose',
                'items' => [
                    __('หนังสือพิมพ์แบบรูปเล่ม'),
                    __('หนังสือพิมพ์ออนไลน์'),
                ],
            ],
            [
                'label' => __('รายงาน'),
                'icon' => 'clipboard-document-list',
                'accent' => 'violet',
                'items' => [
                    __('รายงาน'),
                    __('รายงานการวิจัย'),
                    __('รายงานที่จัดทำโดยหน่วยงานราชการหรือองค์กรอื่น'),
                    __('รายงานที่จัดทำโดยบุคคลที่สังกัดหน่วยงาน'),
                ],
            ],
            [
                'label' => __('งานประชุม'),
                'icon' => 'presentation-chart-bar',
                'accent' => 'orange',
                'items' => [
                    __('เอกสารการประชุมทางวิชาการ (ที่มี Proceeding)'),
                    __('เอกสารการประชุมทางวิชาการ (ที่ไม่มี Proceeding)'),
                    __('การนำเสนองานวิจัยหรือโปสเตอร์ในงานประชุมวิชาการ'),
                ],
            ],
            [
                'label' => __('วิทยานิพนธ์'),
                'icon' => 'academic-cap',
                'accent' => 'indigo',
                'items' => [
                    __('วิทยานิพนธ์ (ที่ไม่ได้ตีพิมพ์)'),
                    __('วิทยานิพนธ์จากเว็บไซต์'),
                    __('วิทยานิพนธ์จากฐานข้อมูลเชิงพาณิชย์'),
                ],
            ],
            [
                'label' => __('ออนไลน์'),
                'icon' => 'globe-alt',
                'accent' => 'cyan',
                'items' => [
                    __('เอกสารอิเล็กทรอนิกส์ (เว็บเพจ)'),
                    __('สื่อออนไลน์ (วิดีโอออนไลน์ บทความในโซเชียลมีเดีย)'),
                    __('ราชกิจจานุเบกษาออนไลน์'),
                    __('สิทธิบัตรออนไลน์'),
                    __('การติดต่อสื่อสารส่วนบุคคล'),
                ],
            ],
            [
                'label' => __('สื่อภาพ/เสียง'),
                'icon' => 'play-circle',
                'accent' => 'fuchsia',
                'items' => [
                    __('อินโฟกราฟิก (Infographic)'),
                    __('การนำเสนอด้วยสไลด์และเอกสารการสอนออนไลน์'),
                    __('สัมมนาออนไลน์ (Webinar)'),
                    __('วิดีโอใน Youtube หรือวิดีโอออนไลน์ต่าง ๆ'),
                    __('พอดแคสต์ภาพและเสียง (แบบจบในตอน)'),
                    __('พอดแคสต์ภาพและเสียง (แบบหลายตอน)'),
                ],
            ],
            [
                'label' => __('อื่นๆ'),
                'icon' => 'sparkles',
                'accent' => 'teal',
                'items' => [
                    __('AI (เนื้อหาที่สร้างโดย AI)'),
                ],
            ],
        ];
    @endphp

    <div class="relative mx-auto flex max-w-[1400px] flex-col gap-6 px-4 pb-24 pt-6 sm:px-6 lg:flex-row lg:gap-10 lg:pt-10">
        <aside x-data="citationProjectsSidebar()"
            class="custom-scrollbar flex w-full shrink-0 flex-col overflow-hidden rounded-2xl border border-zinc-200/80 bg-white p-4 dark:border-zinc-800 dark:bg-zinc-900/50 lg:w-64 lg:rounded-none lg:border-0 lg:bg-transparent lg:p-0 lg:h-[calc(100vh-10rem)] lg:overflow-y-auto lg:pr-4 lg:visible-scrollbar">
            <div class="space-y-4 lg:flex-1">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold tracking-tight text-zinc-900 dark:text-zinc-100">{{ __('โครงการ') }}</h2>
                    <span class="rounded-full bg-zinc-100 px-2.5 py-1 text-[11px] font-medium text-zinc-500 dark:bg-zinc-800 dark:text-zinc-300" x-text="projects.length + ' ' + @js(__('รายการ'))"></span>
                </div>

                <button type="button" x-on:click="openCreateProjectModal()"
                    class="inline-flex w-full items-center justify-center gap-1.5 rounded-xl border border-dashed border-sky-300 bg-white/90 px-3.5 py-2.5 text-[13px] font-semibold text-indigo-700 transition hover:border-indigo-500 hover:bg-sky-50 hover:text-indigo-800 dark:border-indigo-500/30 dark:bg-zinc-950 dark:text-sky-300 dark:hover:border-indigo-500 dark:hover:bg-indigo-500/10 dark:hover:text-sky-200">
                    <flux:icon name="plus" class="size-3.5" />
                    <span>{{ __('สร้างโครงการใหม่') }}</span>
                </button>

                <div class="space-y-3">
                    <template x-for="project in projects" :key="project.id">
                        <div class="group relative">
                            <button type="button" x-on:click="activeProject = project.id; broadcastProjects()"
                                class="w-full rounded-xl p-2.5 pr-10 text-left transition"
                                x-bind:class="activeProject === project.id
                                    ? 'bg-zinc-100 text-zinc-900 dark:bg-zinc-800/80 dark:text-zinc-100'
                                    : 'text-zinc-600 hover:bg-zinc-100/80 hover:text-zinc-900 dark:text-zinc-300 dark:hover:bg-zinc-800/70 dark:hover:text-zinc-100'">
                                <div class="flex items-center gap-2.5">
                                    <span class="inline-flex size-9 items-center justify-center rounded-xl" x-bind:class="projectButtonClass(project.color)">
                                        <flux:icon x-show="project.icon === 'folder'" name="folder" class="size-3.5" />
                                        <flux:icon x-show="project.icon === 'book-open'" name="book-open" class="size-3.5" />
                                        <flux:icon x-show="project.icon === 'document-text'" name="document-text" class="size-3.5" />
                                        <flux:icon x-show="project.icon === 'academic-cap'" name="academic-cap" class="size-3.5" />
                                        <flux:icon x-show="project.icon === 'clipboard-document-list'" name="clipboard-document-list" class="size-3.5" />
                                        <flux:icon x-show="project.icon === 'sparkles'" name="sparkles" class="size-3.5" />
                                        <flux:icon x-show="project.icon === 'globe-alt'" name="globe-alt" class="size-3.5" />
                                        <flux:icon x-show="project.icon === 'light-bulb'" name="light-bulb" class="size-3.5" />
                                        <flux:icon x-show="project.icon === 'beaker'" name="beaker" class="size-3.5" />
                                        <flux:icon x-show="project.icon === 'briefcase'" name="briefcase" class="size-3.5" />
                                        <flux:icon x-show="project.icon === 'newspaper'" name="newspaper" class="size-3.5" />
                                        <flux:icon x-show="project.icon === 'presentation-chart-bar'" name="presentation-chart-bar" class="size-3.5" />
                                    </span>
                                    <h3 class="text-[13px] font-semibold leading-5" x-text="project.name"></h3>
                                </div>
                            </button>

                            <div class="absolute right-2 top-1/2 -translate-y-1/2">
                                <button type="button"
                                    x-on:click.stop="projectMenuOpen = projectMenuOpen === project.id ? null : project.id"
                                    class="inline-flex size-7 items-center justify-center rounded-full text-zinc-400 opacity-0 transition hover:bg-sky-100 hover:text-indigo-700 group-hover:opacity-100 dark:hover:bg-indigo-500/10 dark:hover:text-sky-200"
                                    x-bind:class="projectMenuOpen === project.id || activeProject === project.id ? 'opacity-100' : ''"
                                    aria-label="{{ __('จัดการโครงการ') }}">
                                    <flux:icon name="ellipsis-horizontal" class="size-3.5" />
                                </button>

                                <div x-cloak x-show="projectMenuOpen === project.id" x-transition.opacity x-on:click.outside="projectMenuOpen = null"
                                    class="absolute right-0 z-20 mt-2 w-44 rounded-2xl border border-sky-200/80 bg-white p-1.5 shadow-lg shadow-indigo-100/50 dark:border-zinc-700 dark:bg-zinc-950 dark:shadow-none">
                                    <button type="button" x-on:click="openEditProjectModal(project)"
                                        class="flex w-full items-center gap-2 rounded-xl px-3 py-2 text-left text-sm text-zinc-600 transition hover:bg-sky-50 hover:text-indigo-800 dark:text-zinc-300 dark:hover:bg-indigo-500/10 dark:hover:text-sky-100">
                                        <flux:icon name="pencil-square" class="size-4" />
                                        {{ __('แก้ไข') }}
                                    </button>
                                    <button type="button" x-on:click="duplicateProject(project)"
                                        class="flex w-full items-center gap-2 rounded-xl px-3 py-2 text-left text-sm text-zinc-600 transition hover:bg-sky-50 hover:text-indigo-800 dark:text-zinc-300 dark:hover:bg-indigo-500/10 dark:hover:text-sky-100">
                                        <flux:icon name="square-2-stack" class="size-4" />
                                        {{ __('คัดลอกโครงการ') }}
                                    </button>
                                    <button type="button" x-on:click="openDeleteProjectModal(project)"
                                        class="flex w-full items-center gap-2 rounded-xl px-3 py-2 text-left text-sm text-rose-600 transition hover:bg-rose-50 hover:text-rose-700 dark:text-rose-400 dark:hover:bg-rose-500/10 dark:hover:text-rose-300">
                                        <flux:icon name="trash" class="size-4" />
                                        {{ __('ลบ') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div class="mt-8 border-t border-zinc-200/80 pt-5 text-center dark:border-zinc-800 lg:mt-6">
                <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">{{ __('จัดโดย') }} <span class="font-semibold text-zinc-800 dark:text-zinc-100">Babybib</span></p>
                <div class="mt-2 flex flex-wrap items-center justify-center gap-x-3 gap-y-1 text-[12px] text-zinc-500 dark:text-zinc-400">
                    <a href="{{ route('privacy') }}" class="transition hover:text-indigo-600 dark:hover:text-sky-300">{{ __('Privacy') }}</a>
                    <span class="text-zinc-300 dark:text-zinc-700">•</span>
                    <a href="{{ route('terms') }}" class="transition hover:text-indigo-600 dark:hover:text-sky-300">{{ __('Terms') }}</a>
                    <span class="text-zinc-300 dark:text-zinc-700">•</span>
                    <a href="{{ route('about') }}" class="transition hover:text-indigo-600 dark:hover:text-sky-300">{{ __('About') }}</a>
                </div>
            </div>

            <div x-cloak x-show="projectModal" x-transition.opacity
                class="fixed inset-0 z-[110] flex items-center justify-center bg-zinc-950/40 px-4 backdrop-blur-sm">
                <div x-show="projectModal" x-transition
                    class="w-full max-w-lg rounded-[1.75rem] border border-sky-200 bg-white p-6 shadow-2xl shadow-indigo-100/60 dark:border-zinc-700 dark:bg-zinc-900 dark:shadow-none">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-1">
                            <h3 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100" x-text="projectFormMode === 'create' ? @js(__('สร้างโครงการใหม่')) : @js(__('แก้ไขโครงการ'))"></h3>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('ตั้งชื่อและเลือกสไตล์ที่เหมาะกับโครงการของคุณ') }}</p>
                        </div>
                        <button type="button" x-on:click="closeProjectModals()"
                            class="rounded-full p-2 text-zinc-400 transition hover:bg-zinc-100 hover:text-zinc-900 dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
                            <flux:icon name="x-mark" class="size-5" />
                        </button>
                    </div>

                    <div class="mt-6 space-y-2">
                        <label class="text-sm font-medium text-zinc-700 dark:text-zinc-200">{{ __('ชื่อโครงการ') }}</label>
                        <input x-model="projectForm.name" type="text" placeholder="{{ __('เช่น โครงการวิทยานิพนธ์ปี 2026') }}"
                            class="w-full rounded-2xl border border-sky-200 bg-white px-4 py-3 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-sky-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-sky-500 dark:focus:ring-sky-400/10">
                    </div>

                    <div class="mt-5 rounded-3xl border border-sky-100 bg-gradient-to-br from-indigo-50/80 via-white to-sky-50/80 p-4 dark:border-zinc-800 dark:bg-zinc-950/70">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100">{{ __('ปรับสไตล์โครงการ') }}</p>
                                <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ __('เลือกโทนสีและสัญลักษณ์ที่เข้ากับงานของคุณ') }}</p>
                            </div>
                            <span class="inline-flex size-11 items-center justify-center rounded-2xl" x-bind:class="projectButtonClass(projectForm.color)">
                                <flux:icon x-show="projectForm.icon === 'folder'" name="folder" class="size-5" />
                                <flux:icon x-show="projectForm.icon === 'book-open'" name="book-open" class="size-5" />
                                <flux:icon x-show="projectForm.icon === 'document-text'" name="document-text" class="size-5" />
                                <flux:icon x-show="projectForm.icon === 'academic-cap'" name="academic-cap" class="size-5" />
                                <flux:icon x-show="projectForm.icon === 'clipboard-document-list'" name="clipboard-document-list" class="size-5" />
                                <flux:icon x-show="projectForm.icon === 'sparkles'" name="sparkles" class="size-5" />
                                <flux:icon x-show="projectForm.icon === 'globe-alt'" name="globe-alt" class="size-5" />
                                <flux:icon x-show="projectForm.icon === 'light-bulb'" name="light-bulb" class="size-5" />
                                <flux:icon x-show="projectForm.icon === 'beaker'" name="beaker" class="size-5" />
                                <flux:icon x-show="projectForm.icon === 'briefcase'" name="briefcase" class="size-5" />
                                <flux:icon x-show="projectForm.icon === 'newspaper'" name="newspaper" class="size-5" />
                                <flux:icon x-show="projectForm.icon === 'presentation-chart-bar'" name="presentation-chart-bar" class="size-5" />
                            </span>
                        </div>

                        <div class="mt-4 grid grid-cols-4 gap-2">
                            <template x-for="color in projectColors" :key="color.value">
                                <button type="button" x-on:click="projectForm.color = color.value"
                                    class="flex items-center justify-center rounded-2xl border px-3 py-3 text-sm transition"
                                    x-bind:class="projectForm.color === color.value
                                        ? 'border-indigo-400 bg-sky-50 text-indigo-800 dark:border-sky-500 dark:bg-indigo-500/10 dark:text-sky-100'
                                        : 'border-zinc-200 text-zinc-600 hover:border-sky-300 hover:text-indigo-800 dark:border-zinc-700 dark:text-zinc-300 dark:hover:border-sky-500 dark:hover:text-sky-100'">
                                    <span class="size-5 rounded-full ring-2 ring-white/70 dark:ring-zinc-900/70" :class="color.swatch"></span>
                                </button>
                            </template>
                        </div>

                        <div class="mt-4 grid grid-cols-3 gap-2">
                            <template x-for="icon in projectIcons" :key="icon.value">
                                <button type="button" x-on:click="projectForm.icon = icon.value"
                                    class="flex items-center justify-center rounded-2xl border px-3 py-3 text-sm transition"
                                    x-bind:class="projectForm.icon === icon.value
                                        ? 'border-indigo-400 bg-sky-50 text-indigo-800 dark:border-sky-500 dark:bg-indigo-500/10 dark:text-sky-100'
                                        : 'border-zinc-200 text-zinc-600 hover:border-sky-300 hover:text-indigo-800 dark:border-zinc-700 dark:text-zinc-300 dark:hover:border-sky-500 dark:hover:text-sky-100'">
                                    <span class="inline-flex size-5 items-center justify-center">
                                        <flux:icon x-show="icon.value === 'folder'" name="folder" class="size-4" />
                                        <flux:icon x-show="icon.value === 'book-open'" name="book-open" class="size-4" />
                                        <flux:icon x-show="icon.value === 'document-text'" name="document-text" class="size-4" />
                                        <flux:icon x-show="icon.value === 'academic-cap'" name="academic-cap" class="size-4" />
                                        <flux:icon x-show="icon.value === 'clipboard-document-list'" name="clipboard-document-list" class="size-4" />
                                        <flux:icon x-show="icon.value === 'sparkles'" name="sparkles" class="size-4" />
                                        <flux:icon x-show="icon.value === 'globe-alt'" name="globe-alt" class="size-4" />
                                        <flux:icon x-show="icon.value === 'light-bulb'" name="light-bulb" class="size-4" />
                                        <flux:icon x-show="icon.value === 'beaker'" name="beaker" class="size-4" />
                                        <flux:icon x-show="icon.value === 'briefcase'" name="briefcase" class="size-4" />
                                        <flux:icon x-show="icon.value === 'newspaper'" name="newspaper" class="size-4" />
                                        <flux:icon x-show="icon.value === 'presentation-chart-bar'" name="presentation-chart-bar" class="size-4" />
                                    </span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" x-on:click="closeProjectModals()"
                            class="px-4 py-2 text-sm font-medium text-zinc-500 transition hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100">
                            {{ __('ยกเลิก') }}
                        </button>
                        <button type="button"
                            x-on:click="saveProject()"
                            class="inline-flex items-center gap-2 rounded-full bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-700 dark:bg-indigo-500 dark:text-white dark:hover:bg-sky-500">
                            <flux:icon x-show="projectFormMode === 'create'" name="plus" class="size-4" />
                            <flux:icon x-show="projectFormMode === 'edit'" name="check" class="size-4" />
                            <span x-text="projectFormMode === 'create' ? @js(__('สร้างโครงการ')) : @js(__('บันทึกการแก้ไข'))"></span>
                        </button>
                    </div>
                </div>
            </div>

            <div x-cloak x-show="deleteProjectModal" x-transition.opacity
                class="fixed inset-0 z-[110] flex items-center justify-center bg-zinc-950/40 px-4 backdrop-blur-sm">
                <div x-show="deleteProjectModal" x-transition
                    class="w-full max-w-md rounded-[1.75rem] border border-rose-200 bg-white p-6 shadow-2xl shadow-rose-100/60 dark:border-zinc-700 dark:bg-zinc-900 dark:shadow-none">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-1">
                            <h3 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">{{ __('ยืนยันการลบโครงการ') }}</h3>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                {{ __('พิมพ์ชื่อโครงการให้ตรงเพื่อยืนยันการลบอย่างถาวร') }}
                            </p>
                        </div>
                        <button type="button" x-on:click="closeProjectModals()"
                            class="rounded-full p-2 text-zinc-400 transition hover:bg-zinc-100 hover:text-zinc-900 dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
                            <flux:icon name="x-mark" class="size-5" />
                        </button>
                    </div>

                    <div class="mt-5 rounded-2xl bg-rose-50 px-4 py-3 text-sm text-rose-700 dark:bg-rose-500/10 dark:text-rose-300">
                        {{ __('ชื่อโครงการที่ต้องพิมพ์:') }}
                        <span class="font-semibold" x-text="projectToDelete?.name || '-'"></span>
                    </div>

                    <div class="mt-5 space-y-2">
                        <label class="text-sm font-medium text-zinc-700 dark:text-zinc-200">{{ __('พิมพ์ชื่อโครงการเพื่อยืนยัน') }}</label>
                        <input x-model="deleteConfirmationName" type="text"
                            placeholder="{{ __('กรอกชื่อโครงการให้ตรงกัน') }}"
                            class="w-full rounded-2xl border border-rose-200 bg-white px-4 py-3 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-rose-400 focus:outline-none focus:ring-2 focus:ring-rose-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-rose-500 dark:focus:ring-rose-500/10">
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" x-on:click="closeProjectModals()"
                            class="px-4 py-2 text-sm font-medium text-zinc-500 transition hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100">
                            {{ __('ยกเลิก') }}
                        </button>
                        <button type="button" x-on:click="confirmDeleteProject()"
                            class="inline-flex items-center gap-2 rounded-full bg-rose-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-rose-700">
                            <flux:icon name="trash" class="size-4" />
                            {{ __('ยืนยันการลบ') }}
                        </button>
                    </div>
                </div>
            </div>
        </aside>

        <main class="min-w-0 flex-1 lg:pl-2">
            <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_288px] lg:items-start lg:gap-8">
                <section x-data="citationGeneratorPage()"
                    class="lg:min-w-0">
                    <div class="flex min-h-[calc(100vh-8rem)] flex-col lg:pr-0">
                        <div class="mx-auto w-full max-w-3xl space-y-3">
                            <div class="group relative" x-on:click.outside="closeSmartSearch()">
                                <div x-cloak x-show="smartSearchOpen && (smartSearchLoading || smartSearchResults.length)" x-transition.opacity
                                    x-on:click="closeSmartSearch()"
                                    class="fixed inset-0 z-30 bg-zinc-950/12 backdrop-blur-[3px] dark:bg-zinc-950/30"></div>
                                        <div class="absolute left-4 top-0 z-40 -translate-y-1/2 flex items-center gap-3 rounded-full border border-sky-100 bg-white/95 px-3 py-1 shadow-sm shadow-indigo-100/40 dark:border-slate-800 dark:bg-slate-900/95">
                                    <button type="button" x-on:click="modalOpen = true"
                                        class="inline-flex items-center gap-2 rounded-full bg-indigo-600 px-3.5 py-1.5 text-xs font-semibold text-white shadow-sm shadow-indigo-500/20 transition hover:bg-indigo-700 hover:shadow-md hover:shadow-indigo-500/25 focus:outline-none focus:ring-2 focus:ring-sky-500/20 dark:bg-indigo-500 dark:hover:bg-sky-500"
                                        aria-label="{{ __('เปิดฟอร์มกรอกข้อมูลเอง') }}">
                                        <flux:icon name="plus" class="size-3.5" />
                                        <span>{{ __('สร้างรายการอ้างอิง') }}</span>
                                    </button>

                                    <button type="button" x-on:click="setQuickFilter('website')"
                                        class="relative border-b-2 pb-1 text-xs font-medium transition"
                                        x-bind:class="activeQuickFilter === 'website'
                                            ? 'border-indigo-500 text-zinc-900 dark:border-sky-500 dark:text-zinc-100'
                                            : 'border-transparent text-zinc-500 hover:border-sky-400 hover:text-zinc-900 dark:border-transparent dark:text-zinc-400 dark:hover:border-sky-500 dark:hover:text-zinc-100'">
                                        {{ __('เว็บไซต์') }}
                                    </button>

                                    <button type="button" x-on:click="setQuickFilter('book')"
                                        class="relative border-b-2 pb-1 text-xs font-medium transition"
                                        x-bind:class="activeQuickFilter === 'book'
                                            ? 'border-indigo-500 text-zinc-900 dark:border-sky-500 dark:text-zinc-100'
                                            : 'border-transparent text-zinc-500 hover:border-sky-400 hover:text-zinc-900 dark:border-transparent dark:text-zinc-400 dark:hover:border-sky-500 dark:hover:text-zinc-100'">
                                        {{ __('หนังสือ') }}
                                    </button>

                                    <button type="button" x-on:click="setQuickFilter('article')"
                                        class="relative border-b-2 pb-1 text-xs font-medium transition"
                                        x-bind:class="activeQuickFilter === 'article'
                                            ? 'border-indigo-500 text-zinc-900 dark:border-sky-500 dark:text-zinc-100'
                                            : 'border-transparent text-zinc-500 hover:border-sky-400 hover:text-zinc-900 dark:border-transparent dark:text-zinc-400 dark:hover:border-sky-500 dark:hover:text-zinc-100'">
                                        {{ __('บทความ') }}
                                    </button>
                                </div>
                                <div
                                    class="pointer-events-none absolute inset-y-0 left-0 z-40 flex items-center pl-4 transition-colors group-focus-within:text-zinc-600 dark:group-focus-within:text-zinc-200">
                                    <span class="relative inline-flex size-8 items-center justify-center text-indigo-500 dark:text-sky-300">
                                        <flux:icon name="magnifying-glass" class="size-4.5" />
                                    </span>
                                </div>
                                <input x-ref="smartSearchInput" x-model="smartQuery" type="text"
                                    x-on:input="queueSmartSearch()"
                                    x-on:focus="handleSmartSearchFocus()"
                                    x-on:keydown.escape.prevent="closeSmartSearch()"
                                    x-on:keydown.enter.prevent="openFirstSmartSearchResult()"
                                    x-bind:placeholder="smartSearchPlaceholder()"
                                    class="relative z-30 w-full rounded-2xl border border-sky-200 bg-white py-3.5 pl-16 pr-12 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-sky-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-sky-500 dark:focus:ring-sky-400/10">
                                <div x-cloak x-show="smartSearchLoading"
                                    class="pointer-events-none absolute inset-y-0 right-4 z-30 flex items-center text-indigo-500 dark:text-sky-300">
                                    <flux:icon name="arrow-path" class="size-4 animate-spin" />
                                </div>
                                <div class="absolute right-3 -top-1 z-30 -translate-y-1/2">
                                    <a href="{{ url('/manual') }}"
                                        class="inline-flex items-center gap-1.5 bg-white px-1 text-[11px] font-medium text-sky-500 transition hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-sky-500/20 dark:bg-zinc-900 dark:text-sky-300 dark:hover:text-sky-200"
                                        aria-label="{{ __('เปิดหน้าช่วยเหลือ') }}">
                                        <flux:icon name="question-mark-circle" class="size-3.5" />
                                        {{ __('Help') }}
                                    </a>
                                </div>

                                <div x-cloak x-show="smartSearchOpen && smartQuery.trim()" x-transition.opacity.origin.top
                                    class="absolute inset-x-0 top-full z-40 mt-3 overflow-hidden rounded-[1.5rem] border border-sky-200 bg-white/95 shadow-2xl shadow-indigo-100/60 backdrop-blur dark:border-zinc-700 dark:bg-zinc-950/95 dark:shadow-none">
                                    

                                    <div class="custom-scrollbar max-h-[24rem] overflow-y-auto p-2">
                                        <template x-if="smartSearchLoading">
                                            <div class="flex items-center gap-3 rounded-2xl px-4 py-4 text-sm text-zinc-500 dark:text-zinc-400">
                                                <span class="inline-flex size-10 items-center justify-center rounded-2xl bg-sky-50 text-indigo-500 dark:bg-indigo-500/10 dark:text-sky-300">
                                                    <flux:icon name="arrow-path" class="size-4 animate-spin" />
                                                </span>
                                                <div>
                                                    <p class="font-medium text-zinc-700 dark:text-zinc-200" x-text="smartSearchLoadingTitle()"></p>
                                                    <p class="text-xs" x-text="smartSearchLoadingDescription()"></p>
                                                </div>
                                            </div>
                                        </template>

                                        <template x-if="!smartSearchLoading && smartSearchResults.length">
                                            <div class="space-y-1.5">
                                                <template x-for="result in smartSearchResults" :key="result.key">
                                                    <button type="button" x-on:click="openSmartSearchResult(result)"
                                                        class="flex w-full items-start gap-3 rounded-2xl border border-transparent px-3 py-3 text-left transition hover:border-sky-200 hover:bg-sky-50 dark:hover:border-sky-500/30 dark:hover:bg-indigo-500/10">
                                                        <span class="inline-flex size-10 shrink-0 items-center justify-center rounded-2xl border text-xs font-semibold"
                                                            x-bind:class="result.accentClass">
                                                            <flux:icon x-show="result.icon === 'book-open'" name="book-open" class="size-4" />
                                                            <flux:icon x-show="result.icon === 'document-text'" name="document-text" class="size-4" />
                                                            <flux:icon x-show="result.icon === 'academic-cap'" name="academic-cap" class="size-4" />
                                                            <flux:icon x-show="result.icon === 'globe-alt'" name="globe-alt" class="size-4" />
                                                        </span>
                                                        <span class="min-w-0 flex-1">
                                                            <span class="flex flex-wrap items-center gap-2">
                                                                <span class="text-sm font-semibold text-zinc-800 dark:text-zinc-100" x-text="result.title"></span>
                                                                <span class="rounded-full bg-zinc-100 px-2.5 py-1 text-[11px] font-medium text-zinc-500 dark:bg-zinc-800 dark:text-zinc-300" x-text="result.formatLabel"></span>
                                                            </span>
                                                            <span class="mt-1 block text-xs font-medium text-indigo-600 dark:text-sky-300" x-text="result.matchLabel"></span>
                                                            <span class="mt-1 block text-xs leading-5 text-zinc-700 dark:text-zinc-200" x-text="result.authorLine"></span>
                                                            <span class="mt-1 block text-xs leading-5 text-zinc-500 dark:text-zinc-400" x-text="result.metadataLine"></span>
                                                            <span class="mt-1 block text-[11px] leading-5 text-zinc-400 dark:text-zinc-500" x-text="result.preview"></span>
                                                        </span>
                                                        <flux:icon name="arrow-up-right" class="mt-1 size-4 shrink-0 text-zinc-300 dark:text-zinc-600" />
                                                    </button>
                                                </template>
                                            </div>
                                        </template>

                                        <template x-if="!smartSearchLoading && !smartSearchResults.length">
                                            <div class="rounded-[1.25rem] border border-dashed border-sky-200 bg-sky-50/70 px-4 py-5 text-center dark:border-zinc-700 dark:bg-zinc-900/70">
                                                <p class="text-sm font-semibold text-zinc-800 dark:text-zinc-100" x-text="smartSearchEmptyTitle()"></p>
                                                <p class="mt-1 text-xs leading-5 text-zinc-500 dark:text-zinc-400" x-text="smartSearchEmptyDescription()"></p>
                                                <button type="button" x-on:click="openManualResourceFormFromSearch()"
                                                    class="mt-4 inline-flex items-center gap-2 rounded-full border border-sky-200 bg-white px-4 py-2 text-xs font-medium text-indigo-600 transition hover:border-indigo-300 hover:text-indigo-700 dark:border-zinc-700 dark:bg-zinc-950 dark:text-sky-300 dark:hover:border-sky-500">
                                                    <flux:icon x-show="activeQuickFilter !== 'website'" name="book-open" class="size-3.5" />
                                                    <flux:icon x-show="activeQuickFilter === 'website'" name="globe-alt" class="size-3.5" />
                                                    <span x-text="smartSearchManualActionLabel()"></span>
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <div class="grid gap-3 p-1 lg:grid-cols-[minmax(0,0.9fr)_minmax(0,1.4fr)_auto]">
                                <label class="grid max-w-xs gap-1.5 text-xs font-medium text-zinc-500 dark:text-zinc-400">
                                    <span class="flex items-center justify-between gap-3">
                                        <span>{{ __('รูปแบบบรรณานุกรม') }}</span>
                                        <a href="{{ url('/manual') }}"
                                            class="text-[11px] font-medium text-indigo-600 underline underline-offset-2 transition hover:text-indigo-700 dark:text-sky-300 dark:hover:text-sky-200">
                                            {{ __('Learn more') }}
                                        </a>
                                    </span>
                                    <flux:select x-model="citationStyle" placeholder="">
                                        <option disabled>{{ __('เลือกรูปแบบเพื่อจัดรูปแบบบรรณานุกรมและ citation ของคุณ') }}</option>
                                        <flux:select.option value="apa7">APA 7th</flux:select.option>
                                        <flux:select.option value="mla9">MLA 9th</flux:select.option>
                                        <flux:select.option value="chicago17">Chicago 17th</flux:select.option>
                                    </flux:select>
                                </label>

                                <div class="grid items-center justify-items-center gap-1.5 text-center text-xs font-medium text-zinc-500 dark:text-zinc-400">
                                    <span>{{ __('รูปแบบการแสดง') }}</span>
                                    <div class="flex items-center justify-center gap-2">
                                        <flux:tooltip content="{{ __('มุมมองแบบกระดาษ') }}" position="top">
                                            <button type="button" x-on:click="displayMode = 'paper'"
                                                class="inline-flex size-10 items-center justify-center rounded-xl border border-sky-200 bg-white transition dark:border-zinc-700 dark:bg-zinc-950"
                                                x-bind:class="displayMode === 'paper' ? 'border-indigo-300 text-indigo-700 ring-2 ring-sky-500/15 dark:border-sky-500/50 dark:text-sky-200 dark:ring-sky-500/20' : 'text-zinc-400 hover:border-sky-200 hover:text-indigo-700 dark:text-zinc-500 dark:hover:text-sky-200'">
                                                <flux:icon name="document-text" class="size-4" />
                                            </button>
                                        </flux:tooltip>
                                        <flux:tooltip content="{{ __('มุมมองแบบรายการ') }}" position="top">
                                            <button type="button" x-on:click="displayMode = 'list'"
                                                class="inline-flex size-10 items-center justify-center rounded-xl border border-sky-200 bg-white transition dark:border-zinc-700 dark:bg-zinc-950"
                                                x-bind:class="displayMode === 'list' ? 'border-indigo-300 text-indigo-700 ring-2 ring-sky-500/15 dark:border-sky-500/50 dark:text-sky-200 dark:ring-sky-500/20' : 'text-zinc-400 hover:border-sky-200 hover:text-indigo-700 dark:text-zinc-500 dark:hover:text-sky-200'">
                                                <flux:icon name="list-bullet" class="size-4" />
                                            </button>
                                        </flux:tooltip>
                                        <flux:tooltip content="{{ __('แสดงเฉพาะ citation') }}" position="top">
                                            <button type="button" x-on:click="displayMode = 'citation'"
                                                class="inline-flex size-10 items-center justify-center rounded-xl border border-sky-200 bg-white transition dark:border-zinc-700 dark:bg-zinc-950"
                                                x-bind:class="displayMode === 'citation' ? 'border-indigo-300 text-indigo-700 ring-2 ring-sky-500/15 dark:border-sky-500/50 dark:text-sky-200 dark:ring-sky-500/20' : 'text-zinc-400 hover:border-sky-200 hover:text-indigo-700 dark:text-zinc-500 dark:hover:text-sky-200'">
                                                <flux:icon name="chat-bubble-bottom-center-text" class="size-4" />
                                            </button>
                                        </flux:tooltip>
                                    </div>
                                </div>

                                <div class="flex flex-col gap-2 lg:items-end">
                                    <span class="hidden h-[18px] text-xs font-medium text-transparent lg:block">{{ __('Actions') }}</span>
                                    <div class="flex gap-2">
                                        <button type="button"
                                            x-on:click="copyCurrentView()"
                                            class="inline-flex h-10 items-center gap-2 rounded-xl border border-sky-200 bg-white px-3.5 text-sm font-medium text-zinc-700 transition hover:border-indigo-300 hover:text-indigo-700 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-200 dark:hover:border-sky-500 dark:hover:text-sky-200">
                                            <template x-if="!copied">
                                                <flux:icon name="clipboard-document" class="size-4" />
                                            </template>
                                            <template x-if="copied">
                                                <flux:icon name="check" class="size-4 text-emerald-500" />
                                            </template>
                                                <span x-text="copied ? @js(__('คัดลอกแล้ว')) : @js(__('Copy'))"></span>
                                        </button>

                                        <div class="relative">
                                            <button type="button" x-on:click="exportOpen = !exportOpen"
                                                class="inline-flex h-10 items-center gap-2 rounded-xl border border-sky-200 bg-white px-3.5 text-sm font-medium text-zinc-700 transition hover:border-indigo-300 hover:text-indigo-700 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-200 dark:hover:border-sky-500 dark:hover:text-sky-200">
                                                <flux:icon name="arrow-down-tray" class="size-4" />
                                                <span>{{ __('ส่งออก') }}</span>
                                            </button>

                                            <div x-cloak x-show="exportOpen" x-transition.opacity x-on:click.outside="exportOpen = false"
                                                class="absolute right-0 z-20 mt-2 min-w-36 rounded-xl border border-sky-200 bg-white p-1.5 shadow-lg shadow-indigo-100/50 dark:border-zinc-700 dark:bg-zinc-950 dark:shadow-none">
                                                <button type="button" x-on:click="exportOpen = false"
                                                    class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm text-zinc-600 transition hover:bg-sky-50 hover:text-indigo-800 dark:text-zinc-300 dark:hover:bg-indigo-500/10 dark:hover:text-sky-100">
                                                    <flux:icon name="document-text" class="size-4 text-sky-600 dark:text-sky-400" />
                                                    Word
                                                </button>
                                                <button type="button" x-on:click="window.print(); exportOpen = false"
                                                    class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm text-zinc-600 transition hover:bg-sky-50 hover:text-indigo-800 dark:text-zinc-300 dark:hover:bg-indigo-500/10 dark:hover:text-sky-100">
                                                    <flux:icon name="document" class="size-4 text-rose-600 dark:text-rose-400" />
                                                    PDF
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div x-cloak x-show="modalOpen" x-transition.opacity
                            class="fixed inset-0 z-[110] flex items-center justify-center bg-zinc-950/50 px-4 py-6 backdrop-blur-sm">
                            <div x-show="modalOpen" x-transition
                                class="flex max-h-[90vh] w-full max-w-6xl flex-col overflow-hidden rounded-[2rem] border border-sky-200 bg-white shadow-2xl shadow-indigo-100/60 dark:border-zinc-700 dark:bg-zinc-900 dark:shadow-none">
                                <div class="flex items-start justify-between gap-4 border-b border-zinc-200 px-6 py-5 dark:border-zinc-800 lg:px-8">
                                    <div class="space-y-1">
                                        
                                        <h3 class="text-2xl font-semibold tracking-tight text-zinc-900 dark:text-zinc-100">
                                            {{ __('เลือกประเภททรัพยากรที่ต้องการสร้างบรรณานุกรม') }}
                                        </h3>
                                        <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                            {{ __('เริ่มจากเลือกหมวดและชนิดทรัพยากรด้านล่าง ระบบจะพาคุณไปยังรูปแบบที่เหมาะกับการอ้างอิงนั้นทันที') }}
                                        </p>
                                    </div>
                                    <button type="button" x-on:click="modalOpen = false"
                                        class="rounded-full p-2 text-zinc-400 transition hover:bg-zinc-100 hover:text-zinc-900 dark:hover:bg-zinc-800 dark:hover:text-zinc-100"
                                        aria-label="{{ __('ปิดหน้าต่าง') }}">
                                        <flux:icon name="x-mark" class="size-5" />
                                    </button>
                                </div>

                                <div class="border-b border-zinc-200 px-6 py-4 dark:border-zinc-800 lg:px-8">
                                    <div class="relative">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-zinc-400">
                                            <flux:icon name="magnifying-glass" class="size-5" />
                                        </div>
                                        <input x-model.debounce.150ms="modalSearch" type="text"
                                            placeholder="{{ __('ค้นหาประเภททรัพยากร เช่น หนังสืออิเล็กทรอนิกส์, วิทยานิพนธ์, เว็บเพจ') }}"
                                            class="w-full rounded-2xl border border-sky-200 bg-sky-50/60 py-3 pl-12 pr-4 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-sky-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-sky-500 dark:focus:ring-sky-400/10">
                                    </div>
                                </div>

                                <div class="custom-scrollbar flex-1 overflow-y-auto px-6 py-6 lg:px-8">
                                    <div class="grid gap-5 lg:grid-cols-2">
                                        @foreach ($citationGroups as $group)
                                            @php
                                                $modalHeadingClasses = match ($group['accent']) {
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

                                                $modalAccentClasses = match ($group['accent']) {
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
                                            @endphp
                                            <div class="rounded-3xl border border-sky-100 bg-gradient-to-br from-indigo-50/80 via-white to-sky-50/80 p-5 dark:border-zinc-800 dark:bg-zinc-950/50"
                                                x-show="@js(($group['label'].' '.implode(' ', $group['items']))).toLowerCase().includes(modalSearch.toLowerCase())"
                                                x-transition.opacity.duration.150ms>
                                                <div class="mb-4 flex items-center gap-3">
                                                    <span class="inline-flex size-10 items-center justify-center rounded-2xl ring-1 {{ $modalAccentClasses }}">
                                                        <flux:icon :name="$group['icon']" class="size-5" />
                                                    </span>
                                                    <div>
                                                        <h4 class="text-base font-semibold {{ $modalHeadingClasses }}">{{ $group['label'] }}</h4>
                                                        <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ count($group['items']) }} {{ __('รูปแบบอ้างอิง') }}</p>
                                                    </div>
                                                </div>

                                                <div class="space-y-1.5">
                                                    @foreach ($group['items'] as $item)
                                                        <button type="button"
                                                            x-show="@js($item).toLowerCase().includes(modalSearch.toLowerCase()) || @js($group['label']).toLowerCase().includes(modalSearch.toLowerCase())"
                                                            x-on:click="openFormModal(@js($item))"
                                                            class="flex w-full items-start justify-between gap-3 rounded-2xl border border-transparent bg-white px-4 py-3 text-left text-sm text-zinc-700 transition hover:border-sky-200 hover:bg-sky-50 hover:text-indigo-800 dark:bg-zinc-900 dark:text-zinc-200 dark:hover:border-sky-500/30 dark:hover:bg-indigo-500/10 dark:hover:text-sky-100">
                                                            <span class="leading-6">{{ $item }}</span>
                                                            <flux:icon name="arrow-up-right" class="mt-1 size-4 shrink-0 text-zinc-300 dark:text-zinc-600" />
                                                        </button>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="flex items-center justify-between gap-4 border-t border-zinc-200 px-6 py-4 dark:border-zinc-800 lg:px-8">
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                        {{ __('หากยังไม่แน่ใจประเภทที่ต้องใช้ สามารถดูรายละเอียดการอ้างอิงเพิ่มเติมได้ในคู่มือ') }}
                                    </p>
                                    <div class="flex items-center gap-3">
                                        <a href="{{ url('/manual') }}"
                                            class="text-sm font-medium text-zinc-900 underline underline-offset-2 transition hover:text-indigo-500 dark:text-zinc-100 dark:hover:text-sky-400">
                                            {{ __('Open manual') }}
                                        </a>
                                        <button type="button" x-on:click="modalOpen = false"
                                            class="inline-flex items-center rounded-full border border-zinc-200 px-4 py-2 text-sm font-medium text-zinc-600 transition hover:border-zinc-300 hover:text-zinc-900 dark:border-zinc-700 dark:text-zinc-300 dark:hover:border-zinc-500 dark:hover:text-zinc-100">
                                            {{ __('Close') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Citation Form Modal --}}
                        @include('partials.citation-form-modal')

                        <div x-cloak x-show="detailModalOpen" x-transition.opacity
                            class="fixed inset-0 z-[110] flex items-center justify-center bg-zinc-950/50 px-4 py-6 backdrop-blur-sm">
                            <div x-show="detailModalOpen" x-transition
                                class="w-full max-w-5xl rounded-[2rem] border border-sky-200 bg-white p-6 shadow-2xl shadow-indigo-100/60 dark:border-zinc-700 dark:bg-zinc-900 dark:shadow-none">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="space-y-1">
                                        <h3 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">{{ __('รายละเอียดรายการบรรณานุกรม') }}</h3>
                                        <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                            {{ __('โครงการ:') }}
                                            <span class="font-medium text-indigo-600 dark:text-sky-300" x-text="activeEntry ? projectNameById(entryProjectId(activeEntry)) : '-' "></span>
                                            <span class="mx-2 text-zinc-300 dark:text-zinc-600">•</span>
                                            {{ __('ประเภท:') }}
                                            <span class="font-medium text-zinc-700 dark:text-zinc-200" x-text="entryTypeLabel(activeEntry)"></span>
                                        </p>
                                    </div>
                                    <button type="button" x-on:click="closeEntryModals()"
                                        class="rounded-full p-2 text-zinc-400 transition hover:bg-zinc-100 hover:text-zinc-900 dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
                                        <flux:icon name="x-mark" class="size-5" />
                                    </button>
                                </div>

                                <div class="mt-6 grid gap-4 xl:grid-cols-[minmax(0,1.2fr)_minmax(0,0.8fr)]">
                                    <div class="space-y-4">
                                        <div class="rounded-3xl border border-sky-200 bg-sky-50/60 p-5 dark:border-zinc-700 dark:bg-zinc-950">
                                            <p class="mb-2 text-xs font-semibold uppercase tracking-[0.2em] text-indigo-500 dark:text-sky-300">{{ __('Bibliography') }}</p>
                                            <div class="paper-bibliography-font text-[16px] leading-[2] text-zinc-700 dark:text-zinc-300">
                                                <p class="thai-distributed" style="padding-left: 0.5in; text-indent: -0.5in; line-height: 2;" x-html="entryPaperPreview(activeEntry)"></p>
                                            </div>
                                        </div>

                                        <div class="grid gap-4 lg:grid-cols-2">
                                            <div class="rounded-3xl border border-violet-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-950">
                                                <p class="mb-2 text-xs font-semibold uppercase tracking-[0.2em] text-violet-500 dark:text-violet-300">{{ __('Narrative Citation') }}</p>
                                                <p class="text-sm leading-7 text-zinc-700 dark:text-zinc-300" x-text="entryNarrativeCitation(activeEntry)"></p>
                                            </div>
                                            <div class="rounded-3xl border border-violet-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-950">
                                                <p class="mb-2 text-xs font-semibold uppercase tracking-[0.2em] text-violet-500 dark:text-violet-300">{{ __('Parenthetical Citation') }}</p>
                                                <p class="text-sm leading-7 text-zinc-700 dark:text-zinc-300" x-text="entryParentheticalCitation(activeEntry)"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="rounded-3xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-950">
                                        <p class="mb-4 text-xs font-semibold uppercase tracking-[0.2em] text-zinc-500 dark:text-zinc-400">{{ __('Field Details') }}</p>
                                        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-1">
                                            <template x-for="(field, index) in entryDetailFields(activeEntry)" :key="'field-' + index">
                                                <div class="rounded-2xl border border-zinc-200 bg-zinc-50 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-900">
                                                    <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-zinc-500 dark:text-zinc-400" x-text="field.label"></p>
                                                    <p class="mt-1 text-sm leading-6 text-zinc-800 dark:text-zinc-100" x-text="field.value"></p>
                                                </div>
                                            </template>
                                        </div>
                                        <p x-show="entryDetailFields(activeEntry).length === 0" class="text-sm italic text-zinc-400 dark:text-zinc-500">
                                            {{ __('ยังไม่มีข้อมูลฟิลด์เพิ่มเติมสำหรับรายการนี้') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end gap-3">
                                    <button type="button" x-on:click="closeEntryModals()"
                                        class="px-4 py-2 text-sm font-medium text-zinc-500 transition hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100">
                                        {{ __('Close') }}
                                    </button>
                                    <button type="button" x-on:click="copyEntry(activeEntry)"
                                        class="inline-flex items-center gap-2 rounded-full bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-sky-500">
                                        <flux:icon x-show="copiedEntryId !== activeEntry?.id" name="clipboard-document" class="size-4" />
                                        <flux:icon x-show="copiedEntryId === activeEntry?.id" name="check" class="size-4" />
                                        <span x-text="copiedEntryId === activeEntry?.id ? @js(__('คัดลอกแล้ว')) : @js(__('คัดลอกรายการ'))"></span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div x-cloak x-show="editModalOpen" x-transition.opacity
                            class="fixed inset-0 z-[110] flex items-center justify-center bg-zinc-950/50 px-4 py-6 backdrop-blur-sm">
                            <div x-show="editModalOpen" x-transition
                                class="w-full max-w-5xl rounded-[2rem] border border-sky-200 bg-white p-6 shadow-2xl shadow-indigo-100/60 dark:border-zinc-700 dark:bg-zinc-900 dark:shadow-none">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="space-y-1">
                                        <h3 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">{{ __('แก้ไขรายการบรรณานุกรม') }}</h3>
                                        <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('แก้ไขบรรณานุกรม, citation และข้อมูลรายฟิลด์ของรายการนี้') }}</p>
                                    </div>
                                    <button type="button" x-on:click="closeEntryModals()"
                                        class="rounded-full p-2 text-zinc-400 transition hover:bg-zinc-100 hover:text-zinc-900 dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
                                        <flux:icon name="x-mark" class="size-5" />
                                    </button>
                                </div>

                                <div class="mt-6 grid gap-5 xl:grid-cols-[minmax(0,1fr)_minmax(0,1fr)]">
                                    <div class="space-y-5">
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium text-zinc-700 dark:text-zinc-200">{{ __('ประเภททรัพยากร') }}</label>
                                            <input x-model="editEntryDraft.resourceType" type="text"
                                                class="w-full rounded-2xl border border-sky-200 bg-white px-4 py-3 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-sky-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-sky-500">
                                        </div>

                                        <div class="space-y-2">
                                            <label class="text-sm font-medium text-zinc-700 dark:text-zinc-200">{{ __('บรรณานุกรม') }}</label>
                                            <textarea x-model="editEntryDraft.text" rows="7"
                                                class="w-full rounded-3xl border border-sky-200 bg-white px-4 py-4 text-sm leading-7 text-zinc-700 placeholder:text-zinc-400 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-sky-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-sky-500"></textarea>
                                        </div>

                                        <div class="grid gap-4 lg:grid-cols-2">
                                            <div class="space-y-2">
                                                <label class="text-sm font-medium text-zinc-700 dark:text-zinc-200">{{ __('Narrative Citation') }}</label>
                                                <textarea x-model="editEntryDraft.narrativeCitation" rows="4"
                                                    class="w-full rounded-3xl border border-violet-200 bg-white px-4 py-4 text-sm leading-7 text-zinc-700 placeholder:text-zinc-400 focus:border-violet-400 focus:outline-none focus:ring-2 focus:ring-violet-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-violet-500"></textarea>
                                            </div>
                                            <div class="space-y-2">
                                                <label class="text-sm font-medium text-zinc-700 dark:text-zinc-200">{{ __('Parenthetical Citation') }}</label>
                                                <textarea x-model="editEntryDraft.parentheticalCitation" rows="4"
                                                    class="w-full rounded-3xl border border-violet-200 bg-white px-4 py-4 text-sm leading-7 text-zinc-700 placeholder:text-zinc-400 focus:border-violet-400 focus:outline-none focus:ring-2 focus:ring-violet-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-violet-500"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-4 rounded-3xl border border-zinc-200 bg-zinc-50 p-5 dark:border-zinc-700 dark:bg-zinc-950">
                                        <div class="flex items-center justify-between gap-3">
                                            <div>
                                                <h4 class="text-sm font-semibold text-zinc-800 dark:text-zinc-100">{{ __('ฟิลด์ข้อมูล') }}</h4>
                                                <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ __('แก้ไขหรือเพิ่มข้อมูลย่อยของรายการให้แสดงใน modal รายละเอียด') }}</p>
                                            </div>
                                            <button type="button" x-on:click="addEditField()"
                                                class="inline-flex items-center gap-1.5 rounded-full border border-sky-200 bg-white px-3 py-1.5 text-xs font-medium text-indigo-600 transition hover:border-indigo-300 hover:text-indigo-700 dark:border-zinc-700 dark:bg-zinc-900 dark:text-sky-300 dark:hover:border-sky-500">
                                                <flux:icon name="plus" class="size-3.5" />
                                                {{ __('เพิ่มฟิลด์') }}
                                            </button>
                                        </div>

                                        <div class="custom-scrollbar max-h-[26rem] space-y-3 overflow-y-auto pr-1">
                                            <template x-for="(field, index) in editEntryDraft.detailFields" :key="'edit-field-' + index">
                                                <div class="rounded-2xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
                                                    <div class="grid gap-3 sm:grid-cols-[minmax(0,0.8fr)_minmax(0,1.2fr)_auto] sm:items-start">
                                                        <div>
                                                            <label class="mb-1 block text-xs font-medium text-zinc-500 dark:text-zinc-400">{{ __('ชื่อฟิลด์') }}</label>
                                                            <input x-model="field.label" type="text"
                                                                class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-sky-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-sky-500">
                                                        </div>
                                                        <div>
                                                            <label class="mb-1 block text-xs font-medium text-zinc-500 dark:text-zinc-400">{{ __('ค่า') }}</label>
                                                            <textarea x-model="field.value" rows="2"
                                                                class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-sky-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-sky-500"></textarea>
                                                        </div>
                                                        <button type="button" x-on:click="removeEditField(index)"
                                                            class="mt-6 inline-flex size-9 items-center justify-center rounded-xl text-zinc-400 transition hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-500/10 dark:hover:text-rose-400"
                                                            aria-label="{{ __('ลบฟิลด์') }}">
                                                            <flux:icon name="trash" class="size-4" />
                                                        </button>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                        <p x-show="editEntryDraft.detailFields.length === 0" class="text-sm italic text-zinc-400 dark:text-zinc-500">
                                            {{ __('ยังไม่มีฟิลด์ข้อมูล สามารถกดเพิ่มฟิลด์ได้') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end gap-3">
                                    <button type="button" x-on:click="closeEntryModals()"
                                        class="px-4 py-2 text-sm font-medium text-zinc-500 transition hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100">
                                        {{ __('ยกเลิก') }}
                                    </button>
                                    <button type="button" x-on:click="saveEntryEdit()"
                                        class="inline-flex items-center gap-2 rounded-full bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-sky-500">
                                        <flux:icon name="check" class="size-4" />
                                        {{ __('บันทึกการแก้ไข') }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div x-cloak x-show="moveModalOpen" x-transition.opacity
                            class="fixed inset-0 z-[110] flex items-center justify-center bg-zinc-950/50 px-4 py-6 backdrop-blur-sm">
                            <div x-show="moveModalOpen" x-transition
                                class="w-full max-w-2xl rounded-[2rem] border border-sky-200 bg-white p-6 shadow-2xl shadow-indigo-100/60 dark:border-zinc-700 dark:bg-zinc-900 dark:shadow-none">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="space-y-1">
                                        <h3 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">{{ __('ย้ายรายการไปโครงการอื่น') }}</h3>
                                        <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('เลือกโครงการปลายทางสำหรับรายการที่เลือก') }}</p>
                                    </div>
                                    <button type="button" x-on:click="closeEntryModals()"
                                        class="rounded-full p-2 text-zinc-400 transition hover:bg-zinc-100 hover:text-zinc-900 dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
                                        <flux:icon name="x-mark" class="size-5" />
                                    </button>
                                </div>

                                <div class="mt-6 space-y-3">
                                    <template x-for="project in projectOptions" :key="'move-' + project.id">
                                        <label class="flex cursor-pointer items-center justify-between gap-4 rounded-2xl border px-4 py-3 transition"
                                            x-bind:class="moveTargetProjectId === project.id
                                                ? 'border-indigo-400 bg-sky-50 dark:border-sky-500 dark:bg-indigo-500/10'
                                                : 'border-zinc-200 hover:border-sky-300 dark:border-zinc-700 dark:hover:border-sky-500'">
                                            <div class="flex items-center gap-3">
                                                <input type="radio" name="move-project" x-model="moveTargetProjectId" x-bind:value="project.id"
                                                    class="border-zinc-300 text-indigo-600 focus:ring-sky-500">
                                                <div>
                                                    <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100" x-text="project.name"></p>
                                                    <p class="text-xs text-zinc-500 dark:text-zinc-400" x-text="entryProjectId(activeEntry) === project.id ? @js(__('โครงการปัจจุบัน')) : @js(__('โครงการปลายทาง'))"></p>
                                                </div>
                                            </div>
                                            <flux:icon x-show="moveTargetProjectId === project.id" name="check-circle" class="size-5 text-indigo-500" />
                                        </label>
                                    </template>
                                </div>

                                <div class="mt-6 flex justify-end gap-3">
                                    <button type="button" x-on:click="closeEntryModals()"
                                        class="px-4 py-2 text-sm font-medium text-zinc-500 transition hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100">
                                        {{ __('ยกเลิก') }}
                                    </button>
                                    <button type="button" x-on:click="confirmMoveEntry()"
                                        class="inline-flex items-center gap-2 rounded-full bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-sky-500">
                                        <flux:icon name="folder-open" class="size-4" />
                                        {{ __('ย้ายรายการ') }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div x-cloak x-show="deleteEntryModalOpen" x-transition.opacity
                            class="fixed inset-0 z-[110] flex items-center justify-center bg-zinc-950/50 px-4 py-6 backdrop-blur-sm">
                            <div x-show="deleteEntryModalOpen" x-transition
                                class="w-full max-w-2xl rounded-[2rem] border border-rose-200 bg-white p-6 shadow-2xl shadow-rose-100/60 dark:border-zinc-700 dark:bg-zinc-900 dark:shadow-none">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="space-y-1">
                                        <h3 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">{{ __('ลบรายการบรรณานุกรม') }}</h3>
                                        <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('ยืนยันการลบรายการนี้ออกจากโครงการปัจจุบัน') }}</p>
                                    </div>
                                    <button type="button" x-on:click="closeEntryModals()"
                                        class="rounded-full p-2 text-zinc-400 transition hover:bg-zinc-100 hover:text-zinc-900 dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
                                        <flux:icon name="x-mark" class="size-5" />
                                    </button>
                                </div>

                                <div class="mt-5 rounded-3xl border border-rose-200 bg-rose-50/80 p-4 dark:border-rose-500/20 dark:bg-rose-500/10">
                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-rose-500 dark:text-rose-300">{{ __('รายการที่จะถูกลบ') }}</p>
                                    <p class="mt-2 text-sm leading-7 text-zinc-700 dark:text-zinc-200" x-text="activeEntry?.text || '-' "></p>
                                </div>

                                <div class="mt-6 flex justify-end gap-3">
                                    <button type="button" x-on:click="closeEntryModals()"
                                        class="px-4 py-2 text-sm font-medium text-zinc-500 transition hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100">
                                        {{ __('ยกเลิก') }}
                                    </button>
                                    <button type="button" x-on:click="confirmDeleteEntry()"
                                        class="inline-flex items-center gap-2 rounded-full bg-rose-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-rose-700 dark:bg-rose-500 dark:hover:bg-rose-400">
                                        <flux:icon name="trash" class="size-4" />
                                        {{ __('ลบรายการ') }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-1 justify-center pt-6 lg:pt-6">
                            <template x-if="displayMode === 'paper'">
                                <div x-ref="paperView"
                                    class="paper-bibliography-font flex min-h-[calc(100vh-15rem)] w-full max-w-3xl flex-1 flex-col border border-zinc-200 bg-white px-8 py-10 dark:border-zinc-700 dark:bg-zinc-950">
                                    <div class="paper-bibliography-font mx-auto flex h-full w-full max-w-xl flex-col text-zinc-800 dark:text-zinc-100">
                                        <div class="pb-5 text-center">
                                            <h3 class="paper-bibliography-font text-[18px] font-semibold tracking-tight">{{ __('บรรณานุกรม') }}</h3>
                                        </div>
                                        <div class="paper-bibliography-font space-y-0 text-[16px] leading-[2] text-zinc-700 dark:text-zinc-300">
                                            <template x-for="entry in filteredCitations()" :key="'paper-' + entry.id">
                                                <div x-bind:data-entry-id="entry.id" class="group relative rounded-2xl px-3 py-1.5 transition hover:bg-sky-50 hover:ring-1 hover:ring-sky-200 dark:hover:bg-indigo-500/10 dark:hover:ring-sky-500/20"
                                                    x-bind:class="entryCardClasses(entry, 'paper')">
                                                    <p class="thai-distributed" style="padding-left: 0.5in; text-indent: -0.5in; line-height: 2;" x-html="entry.paperHtml || entry.text"></p>
                                                    <div class="absolute right-3 top-3 hidden items-center gap-2 rounded-full bg-white/95 px-2 py-1.5 shadow-sm ring-1 ring-sky-200 group-hover:flex dark:bg-zinc-950/95 dark:ring-sky-500/20">
                                                        <flux:tooltip content="{{ __('ดู') }}">
                                                            <button type="button" x-on:click="viewEntry(entry)"
                                                                class="inline-flex size-8 items-center justify-center rounded-full text-zinc-500 transition hover:bg-sky-100 hover:text-indigo-700 dark:text-zinc-400 dark:hover:bg-indigo-500/10 dark:hover:text-sky-200"
                                                                aria-label="{{ __('ดู') }}">
                                                                <flux:icon name="eye" class="size-4" />
                                                            </button>
                                                        </flux:tooltip>
                                                        <flux:tooltip content="{{ __('แก้ไข') }}">
                                                            <button type="button" x-on:click="editEntry(entry)"
                                                                class="inline-flex size-8 items-center justify-center rounded-full text-zinc-500 transition hover:bg-sky-100 hover:text-indigo-700 dark:text-zinc-400 dark:hover:bg-indigo-500/10 dark:hover:text-sky-200"
                                                                aria-label="{{ __('แก้ไข') }}">
                                                                <flux:icon name="pencil-square" class="size-4" />
                                                            </button>
                                                        </flux:tooltip>
                                                        <flux:tooltip content="{{ __('คัดลอก') }}">
                                                            <button type="button" x-on:click="copyEntry(entry)"
                                                                class="inline-flex size-8 items-center justify-center rounded-full text-zinc-500 transition hover:bg-sky-100 hover:text-indigo-700 dark:text-zinc-400 dark:hover:bg-indigo-500/10 dark:hover:text-sky-200"
                                                                aria-label="{{ __('คัดลอก') }}">
                                                                <flux:icon x-show="copiedEntryId !== entry.id" name="clipboard-document" class="size-4" />
                                                                <flux:icon x-show="copiedEntryId === entry.id" name="check" class="size-4 text-emerald-500" />
                                                            </button>
                                                        </flux:tooltip>
                                                        <flux:tooltip content="{{ __('ย้ายโครงการ') }}">
                                                            <button type="button" x-on:click="moveEntry(entry)"
                                                                class="inline-flex size-8 items-center justify-center rounded-full text-zinc-500 transition hover:bg-sky-100 hover:text-indigo-700 dark:text-zinc-400 dark:hover:bg-indigo-500/10 dark:hover:text-sky-200"
                                                                aria-label="{{ __('ย้ายโครงการ') }}">
                                                                <flux:icon name="folder-open" class="size-4" />
                                                            </button>
                                                        </flux:tooltip>
                                                        <flux:tooltip content="{{ __('ลบ') }}">
                                                            <button type="button" x-on:click="requestDeleteEntry(entry)"
                                                                class="inline-flex size-8 items-center justify-center rounded-full text-zinc-500 transition hover:bg-rose-100 hover:text-rose-700 dark:text-zinc-400 dark:hover:bg-rose-500/10 dark:hover:text-rose-300"
                                                                aria-label="{{ __('ลบรายการ') }}">
                                                                <flux:icon name="trash" class="size-4" />
                                                            </button>
                                                        </flux:tooltip>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <template x-if="displayMode === 'list'">
                                <div x-ref="listView"
                                    class="w-full max-w-3xl px-2 py-4">
                                    <ul class="space-y-4 text-[15px] leading-8 text-zinc-700 dark:text-zinc-300">
                                        <template x-for="entry in filteredCitations()" :key="'list-' + entry.id">
                                            <li x-bind:data-entry-id="entry.id" class="group flex items-start justify-between gap-4 rounded-2xl border border-zinc-200 bg-white/95 px-4 py-3.5 shadow-sm shadow-zinc-200/50 transition hover:border-sky-300 hover:bg-sky-50 hover:shadow-indigo-100/70 dark:border-zinc-700 dark:bg-zinc-950/90 dark:shadow-none dark:hover:border-sky-500/40 dark:hover:bg-indigo-500/10"
                                                x-bind:class="entryCardClasses(entry, 'list')">
                                                <span class="min-w-0 flex-1 leading-8" x-text="entry.text"></span>
                                                <div class="hidden shrink-0 items-center gap-1.5 group-hover:flex">
                                                    <flux:tooltip content="{{ __('ดู') }}">
                                                        <button type="button" x-on:click="viewEntry(entry)"
                                                            class="inline-flex size-8 items-center justify-center rounded-full text-zinc-500 transition hover:bg-sky-100 hover:text-indigo-700 dark:text-zinc-400 dark:hover:bg-indigo-500/10 dark:hover:text-sky-200"
                                                            aria-label="{{ __('ดู') }}">
                                                            <flux:icon name="eye" class="size-4" />
                                                        </button>
                                                    </flux:tooltip>
                                                    <flux:tooltip content="{{ __('แก้ไข') }}">
                                                        <button type="button" x-on:click="editEntry(entry)"
                                                            class="inline-flex size-8 items-center justify-center rounded-full text-zinc-500 transition hover:bg-sky-100 hover:text-indigo-700 dark:text-zinc-400 dark:hover:bg-indigo-500/10 dark:hover:text-sky-200"
                                                            aria-label="{{ __('แก้ไข') }}">
                                                            <flux:icon name="pencil-square" class="size-4" />
                                                        </button>
                                                    </flux:tooltip>
                                                    <flux:tooltip content="{{ __('คัดลอก') }}">
                                                        <button type="button" x-on:click="copyEntry(entry)"
                                                            class="inline-flex size-8 items-center justify-center rounded-full text-zinc-500 transition hover:bg-sky-100 hover:text-indigo-700 dark:text-zinc-400 dark:hover:bg-indigo-500/10 dark:hover:text-sky-200"
                                                            aria-label="{{ __('คัดลอก') }}">
                                                            <flux:icon x-show="copiedEntryId !== entry.id" name="clipboard-document" class="size-4" />
                                                            <flux:icon x-show="copiedEntryId === entry.id" name="check" class="size-4 text-emerald-500" />
                                                        </button>
                                                    </flux:tooltip>
                                                    <flux:tooltip content="{{ __('ย้ายโครงการ') }}">
                                                        <button type="button" x-on:click="moveEntry(entry)"
                                                            class="inline-flex size-8 items-center justify-center rounded-full text-zinc-500 transition hover:bg-sky-100 hover:text-indigo-700 dark:text-zinc-400 dark:hover:bg-indigo-500/10 dark:hover:text-sky-200"
                                                            aria-label="{{ __('ย้ายโครงการ') }}">
                                                            <flux:icon name="folder-open" class="size-4" />
                                                        </button>
                                                    </flux:tooltip>
                                                    <flux:tooltip content="{{ __('ลบ') }}">
                                                        <button type="button" x-on:click="requestDeleteEntry(entry)"
                                                            class="inline-flex size-8 items-center justify-center rounded-full text-zinc-500 transition hover:bg-rose-100 hover:text-rose-700 dark:text-zinc-400 dark:hover:bg-rose-500/10 dark:hover:text-rose-300"
                                                            aria-label="{{ __('ลบรายการ') }}">
                                                            <flux:icon name="trash" class="size-4" />
                                                        </button>
                                                    </flux:tooltip>
                                                </div>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </template>

                            <template x-if="displayMode === 'citation'">
                                <div x-ref="citationView"
                                    class="w-full max-w-3xl px-2 py-4">
                                    <ul class="space-y-4 text-[15px] leading-8 text-zinc-700 dark:text-zinc-300">
                                        <template x-for="entry in filteredCitations()" :key="'citation-' + entry.id">
                                            <li x-bind:data-entry-id="entry.id" class="group flex items-start justify-between gap-4 rounded-2xl border border-zinc-200 bg-white/95 px-4 py-3.5 shadow-sm shadow-zinc-200/50 transition hover:border-sky-300 hover:bg-sky-50 hover:shadow-indigo-100/70 dark:border-zinc-700 dark:bg-zinc-950/90 dark:shadow-none dark:hover:border-sky-500/40 dark:hover:bg-indigo-500/10"
                                                x-bind:class="entryCardClasses(entry, 'citation')">
                                                <span class="min-w-0 flex-1 leading-8" x-text="entryNarrativePreview(entry)"></span>
                                                <div class="hidden shrink-0 items-center gap-1.5 group-hover:flex">
                                                    <flux:tooltip content="{{ __('ดู') }}">
                                                        <button type="button" x-on:click="viewEntry(entry)"
                                                            class="inline-flex size-8 items-center justify-center rounded-full text-zinc-500 transition hover:bg-sky-100 hover:text-indigo-700 dark:text-zinc-400 dark:hover:bg-indigo-500/10 dark:hover:text-sky-200"
                                                            aria-label="{{ __('ดู') }}">
                                                            <flux:icon name="eye" class="size-4" />
                                                        </button>
                                                    </flux:tooltip>
                                                    <flux:tooltip content="{{ __('แก้ไข') }}">
                                                        <button type="button" x-on:click="editEntry(entry)"
                                                            class="inline-flex size-8 items-center justify-center rounded-full text-zinc-500 transition hover:bg-sky-100 hover:text-indigo-700 dark:text-zinc-400 dark:hover:bg-indigo-500/10 dark:hover:text-sky-200"
                                                            aria-label="{{ __('แก้ไข') }}">
                                                            <flux:icon name="pencil-square" class="size-4" />
                                                        </button>
                                                    </flux:tooltip>
                                                    <flux:tooltip content="{{ __('คัดลอก') }}">
                                                        <button type="button" x-on:click="copyEntry(entry)"
                                                            class="inline-flex size-8 items-center justify-center rounded-full text-zinc-500 transition hover:bg-sky-100 hover:text-indigo-700 dark:text-zinc-400 dark:hover:bg-indigo-500/10 dark:hover:text-sky-200"
                                                            aria-label="{{ __('คัดลอก') }}">
                                                            <flux:icon x-show="copiedEntryId !== entry.id" name="clipboard-document" class="size-4" />
                                                            <flux:icon x-show="copiedEntryId === entry.id" name="check" class="size-4 text-emerald-500" />
                                                        </button>
                                                    </flux:tooltip>
                                                    <flux:tooltip content="{{ __('ย้ายโครงการ') }}">
                                                        <button type="button" x-on:click="moveEntry(entry)"
                                                            class="inline-flex size-8 items-center justify-center rounded-full text-zinc-500 transition hover:bg-sky-100 hover:text-indigo-700 dark:text-zinc-400 dark:hover:bg-indigo-500/10 dark:hover:text-sky-200"
                                                            aria-label="{{ __('ย้ายโครงการ') }}">
                                                            <flux:icon name="folder-open" class="size-4" />
                                                        </button>
                                                    </flux:tooltip>
                                                    <flux:tooltip content="{{ __('ลบ') }}">
                                                        <button type="button" x-on:click="requestDeleteEntry(entry)"
                                                            class="inline-flex size-8 items-center justify-center rounded-full text-zinc-500 transition hover:bg-rose-100 hover:text-rose-700 dark:text-zinc-400 dark:hover:bg-rose-500/10 dark:hover:text-rose-300"
                                                            aria-label="{{ __('ลบรายการ') }}">
                                                            <flux:icon name="trash" class="size-4" />
                                                        </button>
                                                    </flux:tooltip>
                                                </div>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </template>
                        </div>
                    </div>
                </section>

                <section class="custom-scrollbar h-[calc(100vh-10rem)] overflow-y-auto lg:min-w-0 lg:justify-self-end">
                    <div class="pr-2">
                        <flux:heading size="lg" level="2">{{ __('ยังไม่ได้บันทึก') }}</flux:heading>
                        <div class="mt-3 rounded-2xl border border-sky-200 bg-white/80 p-4 shadow-sm shadow-indigo-100/40 dark:border-zinc-700 dark:bg-zinc-950/80 dark:shadow-none">
                            <flux:text class="text-sm leading-6 text-zinc-600 dark:text-zinc-300">
                                {{ __('โครงการและรายการบรรณานุกรมของคุณยังไม่มีการบันทึก เพื่อให้สามารถใช้งานได้เต็มรูปแบบ กรุณาเข้าสู่ระบบก่อนเริ่มใช้งานต่อ') }}
                            </flux:text>

                            @if (Route::has('login'))
                                <a href="{{ route('login') }}"
                                    class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-sky-500">
                                    <flux:icon name="arrow-right-end-on-rectangle" class="size-4" />
                                    <span>{{ __('Sign in') }}</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>
    <script data-navigate-once>
        window.citationProjectsSidebar = function () {
            return {
                projectModal: false,
                deleteProjectModal: false,
                projectMenuOpen: null,
                projectFormMode: 'create',
                editingProjectId: null,
                activeProject: 1,
                projectForm: { name: '', color: 'zinc', icon: 'folder' },
                projectToDelete: null,
                deleteConfirmationName: '',
                projectColors: [
                    { value: 'zinc', label: @js(__('เทา')), swatch: 'bg-zinc-500', button: 'bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-900' },
                    { value: 'sky', label: @js(__('ฟ้า')), swatch: 'bg-sky-500', button: 'bg-sky-500 text-white' },
                    { value: 'emerald', label: @js(__('เขียว')), swatch: 'bg-emerald-500', button: 'bg-emerald-500 text-white' },
                    { value: 'amber', label: @js(__('ทอง')), swatch: 'bg-amber-500', button: 'bg-amber-500 text-white' },
                    { value: 'rose', label: @js(__('ชมพู')), swatch: 'bg-rose-500', button: 'bg-rose-500 text-white' },
                    { value: 'violet', label: @js(__('ม่วง')), swatch: 'bg-violet-500', button: 'bg-violet-500 text-white' },
                    { value: 'indigo', label: @js(__('กรม')), swatch: 'bg-indigo-500', button: 'bg-indigo-500 text-white' },
                    { value: 'cyan', label: @js(__('ฟ้าน้ำทะเล')), swatch: 'bg-cyan-500', button: 'bg-cyan-500 text-white' },
                    { value: 'teal', label: @js(__('ทีล')), swatch: 'bg-teal-500', button: 'bg-teal-500 text-white' },
                    { value: 'orange', label: @js(__('ส้ม')), swatch: 'bg-orange-500', button: 'bg-orange-500 text-white' },
                    { value: 'fuchsia', label: @js(__('บานเย็น')), swatch: 'bg-fuchsia-500', button: 'bg-fuchsia-500 text-white' },
                    { value: 'lime', label: @js(__('ไลม์')), swatch: 'bg-lime-500', button: 'bg-lime-500 text-zinc-950' },
                ],
                projectIcons: [
                    { value: 'folder', label: @js(__('โฟลเดอร์')) },
                    { value: 'book-open', label: @js(__('หนังสือ')) },
                    { value: 'document-text', label: @js(__('เอกสาร')) },
                    { value: 'academic-cap', label: @js(__('วิจัย')) },
                    { value: 'clipboard-document-list', label: @js(__('รายการ')) },
                    { value: 'sparkles', label: @js(__('พิเศษ')) },
                    { value: 'globe-alt', label: @js(__('เว็บ')) },
                    { value: 'light-bulb', label: @js(__('ไอเดีย')) },
                    { value: 'beaker', label: @js(__('ทดลอง')) },
                    { value: 'briefcase', label: @js(__('งาน')) },
                    { value: 'newspaper', label: @js(__('ข่าวสาร')) },
                    { value: 'presentation-chart-bar', label: @js(__('พรีเซนต์')) },
                ],
                projects: [
                    { id: 1, name: @js(__('โครงการวิจัยบทที่ 1')), color: 'zinc', icon: 'folder' },
                ],
                init() {
                    this.broadcastProjects();
                },
                toast(text, variant = 'success') {
                    window.Flux?.toast(text, { variant, position: 'bottom end' });
                },
                broadcastProjects() {
                    window.dispatchEvent(new CustomEvent('citation-projects-updated', {
                        detail: {
                            activeProjectId: this.activeProject,
                            projects: this.projects.map(project => ({
                                id: project.id,
                                name: project.name,
                                color: project.color,
                                icon: project.icon,
                            })),
                        },
                    }));
                },
                closeProjectModals() {
                    this.projectModal = false;
                    this.deleteProjectModal = false;
                    this.projectToDelete = null;
                    this.deleteConfirmationName = '';
                },
                projectButtonClass(color) {
                    return this.projectColors.find(option => option.value === color)?.button || 'bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-900';
                },
                openCreateProjectModal() {
                    this.closeProjectModals();
                    this.projectFormMode = 'create';
                    this.editingProjectId = null;
                    this.projectForm = { name: '', color: 'zinc', icon: 'folder' };
                    this.projectMenuOpen = null;
                    this.projectModal = true;
                },
                openEditProjectModal(project) {
                    this.closeProjectModals();
                    this.projectFormMode = 'edit';
                    this.editingProjectId = project.id;
                    this.projectForm = { name: project.name, color: project.color, icon: project.icon };
                    this.projectMenuOpen = null;
                    this.projectModal = true;
                },
                openDeleteProjectModal(project) {
                    if (this.projects.length === 1) {
                        this.projectMenuOpen = null;
                        this.toast(@js(__('ต้องมีอย่างน้อย 1 โครงการในระบบ')), 'warning');
                        return;
                    }

                    this.closeProjectModals();
                    this.projectToDelete = project;
                    this.deleteConfirmationName = '';
                    this.projectMenuOpen = null;
                    this.deleteProjectModal = true;
                },
                saveProject() {
                    if (!this.projectForm.name.trim()) {
                        this.toast(@js(__('กรุณากรอกชื่อโครงการก่อนบันทึก')), 'warning');
                        return;
                    }

                    if (this.projectFormMode === 'create') {
                        const nextId = Date.now();
                        this.projects.unshift({
                            id: nextId,
                            name: this.projectForm.name.trim(),
                            color: this.projectForm.color,
                            icon: this.projectForm.icon,
                        });
                        this.activeProject = nextId;
                        this.closeProjectModals();
                        this.broadcastProjects();
                        this.toast(@js(__('สร้างโครงการใหม่เรียบร้อยแล้ว')), 'success');
                        return;
                    }

                    const project = this.projects.find(item => item.id === this.editingProjectId);
                    if (!project) {
                        this.toast(@js(__('ไม่พบโครงการที่ต้องการแก้ไข')), 'danger');
                        return;
                    }

                    project.name = this.projectForm.name.trim();
                    project.color = this.projectForm.color;
                    project.icon = this.projectForm.icon;
                    this.closeProjectModals();
                    this.broadcastProjects();
                    this.toast(@js(__('อัปเดตโครงการเรียบร้อยแล้ว')), 'success');
                },
                duplicateProject(project) {
                    const nextId = Date.now();
                    this.projects.unshift({
                        id: nextId,
                        name: `${project.name} ${@js(__('(คัดลอก)'))}`,
                        color: project.color,
                        icon: project.icon,
                    });
                    this.activeProject = nextId;
                    this.projectMenuOpen = null;
                    this.broadcastProjects();
                    this.toast(@js(__('คัดลอกโครงการเรียบร้อยแล้ว')), 'success');
                },
                confirmDeleteProject() {
                    if (!this.projectToDelete) {
                        this.toast(@js(__('ไม่พบโครงการที่ต้องการลบ')), 'danger');
                        return;
                    }

                    if (this.deleteConfirmationName.trim() !== this.projectToDelete.name) {
                        this.toast(@js(__('ชื่อโครงการไม่ตรงกัน กรุณาตรวจสอบอีกครั้ง')), 'warning');
                        return;
                    }

                    const index = this.projects.findIndex(item => item.id === this.projectToDelete.id);
                    if (index === -1) {
                        this.toast(@js(__('ไม่พบโครงการที่ต้องการลบ')), 'danger');
                        return;
                    }

                    const deletedProjectName = this.projectToDelete.name;
                    const deletedProjectId = this.projectToDelete.id;

                    this.projects.splice(index, 1);
                    if (this.activeProject === deletedProjectId) {
                        this.activeProject = this.projects[0]?.id ?? null;
                    }
                    this.closeProjectModals();
                    this.broadcastProjects();
                    this.toast(`${@js(__('ลบโครงการ'))} ${deletedProjectName} ${@js(__('เรียบร้อยแล้ว'))}`, 'danger');
                },
            };
        };

        window.citationGeneratorPage = function () {
            return {
                smartQuery: '',
                smartSearchMockBooks: [
                    {
                        id: 'mock-book-1',
                        category: 'book',
                        resourceType: @js(__('หนังสืออิเล็กทรอนิกส์ (มี DOI)')),
                        accent: 'rose',
                        icon: 'book-open',
                        title: 'การเขียนบรรณานุกรมสำหรับงานวิจัยสมัยใหม่',
                        subtitle: 'คู่มือเริ่มต้นสำหรับนักศึกษาไทย',
                        authors: [{ lastName: 'สุริยา', firstName: 'กนกวรรณ' }],
                        authorLine: 'กนกวรรณ สุริยา',
                        year: '2567',
                        publisher: 'สำนักพิมพ์มหาวิทยาลัยธรรมศาสตร์',
                        isbn: '9786163987654',
                        doi: '10.5555/babybib.2026.011',
                        url: 'https://library.example.org/books/bibliography-modern-research',
                        formatLabel: @js(__('หนังสือพร้อม DOI')),
                        keywords: ['ก', 'บรรณานุกรม', 'งานวิจัย', 'apa'],
                    },
                    {
                        id: 'mock-book-2',
                        category: 'book',
                        resourceType: @js(__('หนังสือ')),
                        accent: 'amber',
                        icon: 'book-open',
                        title: 'กลยุทธ์การค้นคว้าและสืบค้นแหล่งอ้างอิง',
                        subtitle: 'จาก keyword สู่รายการบรรณานุกรม',
                        authors: [{ lastName: 'พิพัฒน์', firstName: 'ธนกร' }],
                        authorLine: 'ธนกร พิพัฒน์',
                        year: '2566',
                        publisher: 'สำนักพิมพ์จุฬาลงกรณ์มหาวิทยาลัย',
                        isbn: '9786164123451',
                        doi: '',
                        url: 'https://library.example.org/books/source-discovery-strategies',
                        formatLabel: @js(__('หนังสือ')),
                        keywords: ['ก', 'ค้นคว้า', 'สืบค้น', 'อ้างอิง'],
                    },
                    {
                        id: 'mock-book-3',
                        category: 'article',
                        resourceType: @js(__('บทความวารสารอิเล็กทรอนิกส์ (มี DOI)')),
                        accent: 'violet',
                        icon: 'document-text',
                        title: 'การอ้างอิงบทความดิจิทัลด้วย DOI อย่างถูกต้อง',
                        subtitle: 'ตัวอย่างบทความวารสารสำหรับงานวิชาการภาษาไทย',
                        authors: [{ lastName: 'ศรีอรุณ', firstName: 'ปาริชาติ' }],
                        authorLine: 'ปาริชาติ ศรีอรุณ',
                        year: '2568',
                        publisher: 'วารสารสารสนเทศศึกษา',
                        isbn: '9786164556789',
                        doi: '10.5555/babybib.2026.014',
                        url: 'https://journal.example.org/articles/doi-citation-guide',
                        formatLabel: @js(__('บทความ')),
                        keywords: ['บทความ', 'doi', 'วารสาร', 'apa 7'],
                    },
                    {
                        id: 'mock-book-4',
                        category: 'website',
                        resourceType: @js(__('เอกสารอิเล็กทรอนิกส์ (เว็บเพจ)')),
                        accent: 'sky',
                        icon: 'globe-alt',
                        title: 'คู่มือการอ้างอิงเว็บไซต์สำหรับงานวิชาการ',
                        subtitle: 'หน้าเว็บตัวอย่างสำหรับการคัดลอก URL ไปสร้างบรรณานุกรม',
                        authors: [{ lastName: 'Babybib', firstName: 'Editorial Team' }],
                        authorLine: 'Babybib Editorial Team',
                        year: '2026',
                        publisher: 'babybib-v2.js Docs',
                        isbn: '9786164999120',
                        doi: '',
                        url: 'https://docs.babybib.app/web-citation-guide',
                        formatLabel: @js(__('เว็บไซต์')),
                        keywords: ['เว็บไซต์', 'url', 'เว็บ', 'แหล่งข้อมูลออนไลน์'],
                    },
                    {
                        id: 'mock-book-5',
                        category: 'article',
                        resourceType: @js(__('บทความวารสาร')),
                        accent: 'emerald',
                        icon: 'academic-cap',
                        title: 'Digital Source Attribution for Student Research',
                        subtitle: 'Sample journal article for metadata-driven citation workflows',
                        authors: [{ lastName: 'Adams', firstName: 'Rachel T.' }],
                        authorLine: 'Rachel T. Adams',
                        year: '2025',
                        publisher: 'Journal of Educational Technology',
                        isbn: '9781037001124',
                        doi: '10.5555/babybib.2026.021',
                        url: 'https://journal.example.org/articles/digital-source-attribution',
                        formatLabel: @js(__('บทความวิจัย')),
                        keywords: ['article', 'metadata', 'citation', 'research', 'digital'],
                    },
                ],
                smartSearchResults: [],
                smartSearchLoading: false,
                smartSearchOpen: false,
                smartSearchTimer: null,
                smartSearchRequestId: 0,
                selectedType: '',
                activeQuickFilter: '',
                copiedEntryId: null,
                recentlyAddedEntryId: null,
                recentEntryTimer: null,
                modalOpen: false,
                modalSearch: '',
                copied: false,
                exportOpen: false,
                citationStyle: 'apa7',
                displayMode: 'paper',
                formModalOpen: false,
                detailModalOpen: false,
                editModalOpen: false,
                moveModalOpen: false,
                deleteEntryModalOpen: false,
                formResourceType: '',
                activeEntry: null,
                editEntryDraft: null,
                projectOptions: [
                    { id: 1, name: @js(__('โครงการวิจัยบทที่ 1')), color: 'zinc', icon: 'folder' },
                ],
                activeProjectId: 1,
                moveTargetProjectId: null,
                modalI18n: @js([
                    'anonymousFallback' => __('ไม่ปรากฏชื่อผู้แต่ง'),
                    'authorCitationConjunction' => __('และ'),
                    'authorEtAl' => __('และคณะ'),
                    'detailFieldResourceType' => __('ประเภททรัพยากร'),
                    'detailFieldAuthors' => __('ผู้แต่ง'),
                    'detailFieldYear' => __('ปีที่พิมพ์'),
                    'detailFieldMonth' => __('เดือน'),
                    'detailFieldDay' => __('วัน'),
                    'detailFieldTitle' => __('ชื่อเรื่อง'),
                    'detailFieldArticleTitle' => __('ชื่อบทความ'),
                    'detailFieldPublisher' => __('สำนักพิมพ์'),
                    'detailFieldVolume' => __('เล่ม'),
                    'detailFieldEdition' => __('ครั้งที่พิมพ์'),
                    'detailFieldEditor' => __('บรรณาธิการ'),
                    'detailFieldBookTitle' => __('ชื่อหนังสือ'),
                    'detailFieldPages' => __('หน้า'),
                    'detailFieldJournalName' => __('ชื่อวารสาร'),
                    'detailFieldIssue' => __('ฉบับที่'),
                    'detailFieldReferenceWork' => __('ชื่อพจนานุกรม / สารานุกรม'),
                    'detailFieldNewspaperName' => __('ชื่อหนังสือพิมพ์'),
                    'detailFieldOrganization' => __('หน่วยงาน / องค์กร'),
                    'detailFieldPublisherProceeding' => __('Proceeding / ผู้เผยแพร่'),
                    'detailFieldConferenceName' => __('ชื่องานประชุม'),
                    'detailFieldConferenceLocation' => __('สถานที่จัด'),
                    'detailFieldConferencePages' => __('หน้า / เลขโปสเตอร์'),
                    'detailFieldWebsiteName' => __('ชื่อเว็บไซต์'),
                    'detailFieldAssignee' => __('ผู้ถือสิทธิ์ / หน่วยงาน'),
                    'detailFieldThesisType' => __('ประเภทวิทยานิพนธ์'),
                    'detailFieldUniversity' => __('มหาวิทยาลัย'),
                    'detailFieldDatabase' => __('ฐานข้อมูล'),
                    'detailFieldMediaType' => __('รูปแบบสื่อ'),
                    'detailFieldPlatform' => __('แพลตฟอร์ม'),
                    'detailFieldModel' => __('รุ่นโมเดล'),
                    'detailFieldPrompt' => __('Prompt / คำสั่งที่ใช้'),
                    'noDate' => __('ม.ป.ป.'),
                    'personalCommunicationNotice' => __('การติดต่อสื่อสารส่วนบุคคลตาม APA ปกติจะใช้อ้างอิงเฉพาะในเนื้อหา ไม่แสดงในบรรณานุกรมท้ายเล่ม'),
                    'narrativeSuffix' => __('กล่าวว่า ...'),
                    'formatHintBook' => __('ผู้แต่ง. (ปี). ชื่อหนังสือ (ครั้งที่พิมพ์). สำนักพิมพ์. DOI/URL'),
                    'formatHintJournal' => __('ผู้แต่ง. (ปี). ชื่อบทความ. ชื่อวารสาร, ปีที่(ฉบับที่), หน้า. DOI/URL'),
                    'formatHintWebsite' => __('ผู้แต่ง. (ปี, เดือน วัน). ชื่อเรื่อง. ชื่อเว็บไซต์. URL'),
                    'formatHintThesis' => __('ผู้แต่ง. (ปี). ชื่อวิทยานิพนธ์ [ประเภท, มหาวิทยาลัย]. URL/ฐานข้อมูล'),
                    'formatHintDefault' => __('เลือกประเภททรัพยากรเพื่อดูรูปแบบ APA 7th Edition ที่เหมาะสม'),
                    'addCitationWarning' => __('กรุณากรอกข้อมูลอย่างน้อยชื่อเรื่อง หรือข้อมูลผู้แต่ง/หน่วยงาน'),
                    'addCitationSuccess' => __('เพิ่มรายการบรรณานุกรมเรียบร้อยแล้ว'),
                    'thesisTypeDoctoral' => __('ดุษฎีนิพนธ์'),
                    'thesisTypeMaster' => __('วิทยานิพนธ์'),
                ]),
                resourceTypeLabels: @js([
                    'หนังสือ' => __('หนังสือ'),
                    'หนังสือชุดหลายเล่มจบ' => __('หนังสือชุดหลายเล่มจบ'),
                    'บทความในหนังสือ' => __('บทความในหนังสือ'),
                    'หนังสืออิเล็กทรอนิกส์ (มี DOI)' => __('หนังสืออิเล็กทรอนิกส์ (มี DOI)'),
                    'หนังสืออิเล็กทรอนิกส์ (ไม่มี DOI)' => __('หนังสืออิเล็กทรอนิกส์ (ไม่มี DOI)'),
                    'บทความวารสาร' => __('บทความวารสาร'),
                    'บทความวารสารอิเล็กทรอนิกส์ (มี DOI)' => __('บทความวารสารอิเล็กทรอนิกส์ (มี DOI)'),
                    'บทความวารสารอิเล็กทรอนิกส์ (ไม่มี DOI)' => __('บทความวารสารอิเล็กทรอนิกส์ (ไม่มี DOI)'),
                    'วารสารอิเล็กทรอนิกส์ (แบบมีฉบับพิมพ์)' => __('วารสารอิเล็กทรอนิกส์ (แบบมีฉบับพิมพ์)'),
                    'วารสารอิเล็กทรอนิกส์ (แบบไม่มีฉบับพิมพ์)' => __('วารสารอิเล็กทรอนิกส์ (แบบไม่มีฉบับพิมพ์)'),
                    'เอกสารอิเล็กทรอนิกส์ (เว็บเพจ)' => __('เอกสารอิเล็กทรอนิกส์ (เว็บเพจ)'),
                    'สื่อออนไลน์ (วิดีโอออนไลน์ บทความในโซเชียลมีเดีย)' => __('สื่อออนไลน์ (วิดีโอออนไลน์ บทความในโซเชียลมีเดีย)'),
                    'ราชกิจจานุเบกษาออนไลน์' => __('ราชกิจจานุเบกษาออนไลน์'),
                    'สิทธิบัตรออนไลน์' => __('สิทธิบัตรออนไลน์'),
                    'การติดต่อสื่อสารส่วนบุคคล' => __('การติดต่อสื่อสารส่วนบุคคล'),
                    'วิทยานิพนธ์ (ที่ไม่ได้ตีพิมพ์)' => __('วิทยานิพนธ์ (ที่ไม่ได้ตีพิมพ์)'),
                    'วิทยานิพนธ์จากเว็บไซต์' => __('วิทยานิพนธ์จากเว็บไซต์'),
                    'วิทยานิพนธ์จากฐานข้อมูลเชิงพาณิชย์' => __('วิทยานิพนธ์จากฐานข้อมูลเชิงพาณิชย์'),
                    'พจนานุกรม' => __('พจนานุกรม'),
                    'พจนานุกรมออนไลน์' => __('พจนานุกรมออนไลน์'),
                    'สารานุกรม' => __('สารานุกรม'),
                    'สารานุกรมออนไลน์' => __('สารานุกรมออนไลน์'),
                    'หนังสือพิมพ์แบบรูปเล่ม' => __('หนังสือพิมพ์แบบรูปเล่ม'),
                    'หนังสือพิมพ์ออนไลน์' => __('หนังสือพิมพ์ออนไลน์'),
                    'รายงาน' => __('รายงาน'),
                    'รายงานการวิจัย' => __('รายงานการวิจัย'),
                    'รายงานที่จัดทำโดยหน่วยงานราชการหรือองค์กรอื่น' => __('รายงานที่จัดทำโดยหน่วยงานราชการหรือองค์กรอื่น'),
                    'รายงานที่จัดทำโดยบุคคลที่สังกัดหน่วยงาน' => __('รายงานที่จัดทำโดยบุคคลที่สังกัดหน่วยงาน'),
                    'เอกสารการประชุมทางวิชาการ (ที่มี Proceeding)' => __('เอกสารการประชุมทางวิชาการ (ที่มี Proceeding)'),
                    'เอกสารการประชุมทางวิชาการ (ที่ไม่มี Proceeding)' => __('เอกสารการประชุมทางวิชาการ (ที่ไม่มี Proceeding)'),
                    'การนำเสนองานวิจัยหรือโปสเตอร์ในงานประชุมวิชาการ' => __('การนำเสนองานวิจัยหรือโปสเตอร์ในงานประชุมวิชาการ'),
                    'อินโฟกราฟิก (Infographic)' => __('อินโฟกราฟิก (Infographic)'),
                    'การนำเสนอด้วยสไลด์และเอกสารการสอนออนไลน์' => __('การนำเสนอด้วยสไลด์และเอกสารการสอนออนไลน์'),
                    'สัมมนาออนไลน์ (Webinar)' => __('สัมมนาออนไลน์ (Webinar)'),
                    'วิดีโอใน Youtube หรือวิดีโอออนไลน์ต่าง ๆ' => __('วิดีโอใน Youtube หรือวิดีโอออนไลน์ต่าง ๆ'),
                    'พอดแคสต์ภาพและเสียง (แบบจบในตอน)' => __('พอดแคสต์ภาพและเสียง (แบบจบในตอน)'),
                    'พอดแคสต์ภาพและเสียง (แบบหลายตอน)' => __('พอดแคสต์ภาพและเสียง (แบบหลายตอน)'),
                    'AI (เนื้อหาที่สร้างโดย AI)' => __('AI (เนื้อหาที่สร้างโดย AI)'),
                ]),
                authorConditionOptions: [
                    { value: 'normal', label: @js(__('ทั่วไป (Normal)')), example: @js(__('สมชาย ใจดี / Smith, J.')) },
                    { value: 'anonymous', label: @js(__('ไม่ปรากฏชื่อผู้แต่ง (Anonymous)')), example: @js(__('ไม่ปรากฏชื่อผู้แต่ง / Anonymous')) },
                    { value: 'pseudonym', label: @js(__('ผู้แต่งใช้นามแฝง (Pseudonym)')), example: @js(__('ส. ศิวรักษ์ / Orwell, G.')) },
                    { value: 'royal_title', label: @js(__('ผู้แต่งเป็นราชสกุล (Royal Title)')), example: @js(__('ม.ร.ว. คึกฤทธิ์ ปราโมช / Queen Elizabeth II')) },
                    { value: 'noble_title', label: @js(__('ผู้แต่งมีบรรดาศักดิ์ (Noble Title)')), example: @js(__('คุณหญิง มาลัย หุวนันทน์ / Churchill, W.')) },
                    { value: 'buddhist_monk', label: @js(__('ผู้แต่งเป็นพระสงฆ์ (Buddhist Monk)')), example: @js(__('พระธรรมปิฎก (ป.อ. ปยุตโต) / Dalai Lama')) },
                    { value: 'editor', label: @js(__('บรรณาธิการ (Editor)')), example: @js(__('สมชาย ใจดี (บ.ก.) / Smith, J. (Ed.)')) },
                    { value: 'organization', label: @js(__('ชื่อหน่วยงานหรือสถาบัน (Organization)')), example: @js(__('สำนักงานราชบัณฑิตยสภา / World Health Organization')) },
                ],
                form: {
                    authors: [{ condition: 'normal', firstName: '', middleName: '', lastName: '', displayName: '' }],
                    year: '',
                    month: '',
                    day: '',
                    title: '',
                    publisher: '',
                    volume: '',
                    issue: '',
                    pages: '',
                    edition: '',
                    editor: '',
                    bookTitle: '',
                    doi: '',
                    url: '',
                    journalName: '',
                    websiteName: '',
                    thesisType: 'master',
                    university: '',
                    databaseName: '',
                    referenceWork: '',
                    newspaperName: '',
                    organization: '',
                    reportNumber: '',
                    conferenceName: '',
                    conferenceLocation: '',
                    patentNumber: '',
                    assignee: '',
                    platform: '',
                    medium: '',
                    model: '',
                    prompt: '',
                },
                citations: [
                    {
                        id: 1,
                        projectId: 1,
                        resourceType: 'หนังสือ',
                        text: 'กนกวรรณ สุริยา. (2564). การจัดการสารสนเทศเพื่อการอ้างอิงทางวิชาการ. สำนักพิมพ์มหาวิทยาลัยธรรมศาสตร์.',
                        paperHtml: 'กนกวรรณ สุริยา. (2564). <em>การจัดการสารสนเทศเพื่อการอ้างอิงทางวิชาการ</em>. สำนักพิมพ์มหาวิทยาลัยธรรมศาสตร์.',
                        narrativeCitation: 'กนกวรรณ สุริยา (2564) กล่าวว่า ...',
                        parentheticalCitation: '... (กนกวรรณ สุริยา, 2564)',
                        detailFields: [
                            { label: 'ประเภททรัพยากร', value: 'หนังสือ' },
                            { label: 'ผู้แต่ง', value: 'กนกวรรณ สุริยา' },
                            { label: 'ปีที่พิมพ์', value: '2564' },
                            { label: 'ชื่อเรื่อง', value: 'การจัดการสารสนเทศเพื่อการอ้างอิงทางวิชาการ' },
                            { label: 'สำนักพิมพ์', value: 'สำนักพิมพ์มหาวิทยาลัยธรรมศาสตร์' },
                        ],
                    },
                    {
                        id: 2,
                        projectId: 1,
                        resourceType: 'บทความวารสาร',
                        text: 'จิราภา วัฒนกุล. (2565). การออกแบบระบบช่วยสร้างบรรณานุกรมสำหรับนักศึกษาไทย. วารสารบรรณารักษศาสตร์และสารสนเทศศาสตร์, 18(2), 25-48.',
                        paperHtml: 'จิราภา วัฒนกุล. (2565). การออกแบบระบบช่วยสร้างบรรณานุกรมสำหรับนักศึกษาไทย. <em>วารสารบรรณารักษศาสตร์และสารสนเทศศาสตร์, 18</em>(2), 25-48.',
                        narrativeCitation: 'จิราภา วัฒนกุล (2565) กล่าวว่า ...',
                        parentheticalCitation: '... (จิราภา วัฒนกุล, 2565)',
                        detailFields: [
                            { label: 'ประเภททรัพยากร', value: 'บทความวารสาร' },
                            { label: 'ผู้แต่ง', value: 'จิราภา วัฒนกุล' },
                            { label: 'ปีที่พิมพ์', value: '2565' },
                            { label: 'ชื่อบทความ', value: 'การออกแบบระบบช่วยสร้างบรรณานุกรมสำหรับนักศึกษาไทย' },
                            { label: 'ชื่อวารสาร', value: 'วารสารบรรณารักษศาสตร์และสารสนเทศศาสตร์' },
                            { label: 'Volume / Issue', value: '18(2)' },
                            { label: 'หน้า', value: '25-48' },
                        ],
                    },
                    {
                        id: 3,
                        projectId: 1,
                        resourceType: 'บทความวารสาร',
                        text: 'ธนกร พิพัฒน์. (2566ก). แนวปฏิบัติการอ้างอิงแหล่งข้อมูลดิจิทัลตามรูปแบบ APA 7th edition. วารสารสารสนเทศศึกษา, 21(1), 11-29.',
                        paperHtml: 'ธนกร พิพัฒน์. (2566ก). แนวปฏิบัติการอ้างอิงแหล่งข้อมูลดิจิทัลตามรูปแบบ APA 7th edition. <em>วารสารสารสนเทศศึกษา, 21</em>(1), 11-29.',
                        narrativeCitation: 'ธนกร พิพัฒน์ (2566ก) กล่าวว่า ...',
                        parentheticalCitation: '... (ธนกร พิพัฒน์, 2566ก)',
                        detailFields: [
                            { label: @js(__('ประเภททรัพยากร')), value: @js(__('บทความวารสาร')) },
                            { label: @js(__('ผู้แต่ง')), value: 'ธนกร พิพัฒน์' },
                            { label: @js(__('ปีที่พิมพ์')), value: '2566ก' },
                            { label: @js(__('ชื่อบทความ')), value: 'แนวปฏิบัติการอ้างอิงแหล่งข้อมูลดิจิทัลตามรูปแบบ APA 7th edition' },
                            { label: @js(__('ชื่อวารสาร')), value: 'วารสารสารสนเทศศึกษา' },
                            { label: 'Volume / Issue', value: '21(1)' },
                            { label: @js(__('หน้า')), value: '11-29' },
                        ],
                    },
                    {
                        id: 4,
                        projectId: 1,
                        resourceType: 'หนังสือ',
                        text: 'ธนกร พิพัฒน์. (2566ข). การพัฒนาทักษะการเขียนบรรณานุกรมของนักศึกษาระดับอุดมศึกษา. สำนักพิมพ์จุฬาลงกรณ์มหาวิทยาลัย.',
                        paperHtml: 'ธนกร พิพัฒน์. (2566ข). <em>การพัฒนาทักษะการเขียนบรรณานุกรมของนักศึกษาระดับอุดมศึกษา</em>. สำนักพิมพ์จุฬาลงกรณ์มหาวิทยาลัย.',
                        narrativeCitation: 'ธนกร พิพัฒน์ (2566ข) กล่าวว่า ...',
                        parentheticalCitation: '... (ธนกร พิพัฒน์, 2566ข)',
                        detailFields: [
                            { label: @js(__('ประเภททรัพยากร')), value: @js(__('หนังสือ')) },
                            { label: @js(__('ผู้แต่ง')), value: 'ธนกร พิพัฒน์' },
                            { label: @js(__('ปีที่พิมพ์')), value: '2566ข' },
                            { label: @js(__('ชื่อเรื่อง')), value: 'การพัฒนาทักษะการเขียนบรรณานุกรมของนักศึกษาระดับอุดมศึกษา' },
                            { label: @js(__('สำนักพิมพ์')), value: 'สำนักพิมพ์จุฬาลงกรณ์มหาวิทยาลัย' },
                        ],
                    },
                    {
                        id: 5,
                        projectId: 1,
                        resourceType: 'บทความวารสาร',
                        text: 'ปาริชาติ ศรีอรุณ. (2567). การใช้เครื่องมือดิจิทัลเพื่อสนับสนุนการเขียนอ้างอิงในงานวิจัย. วารสารวิชาการครุศาสตร์, 9(3), 101-119.',
                        paperHtml: 'ปาริชาติ ศรีอรุณ. (2567). การใช้เครื่องมือดิจิทัลเพื่อสนับสนุนการเขียนอ้างอิงในงานวิจัย. <em>วารสารวิชาการครุศาสตร์, 9</em>(3), 101-119.',
                        narrativeCitation: 'ปาริชาติ ศรีอรุณ (2567) กล่าวว่า ...',
                        parentheticalCitation: '... (ปาริชาติ ศรีอรุณ, 2567)',
                        detailFields: [
                            { label: @js(__('ประเภททรัพยากร')), value: @js(__('บทความวารสาร')) },
                            { label: @js(__('ผู้แต่ง')), value: 'ปาริชาติ ศรีอรุณ' },
                            { label: @js(__('ปีที่พิมพ์')), value: '2567' },
                            { label: @js(__('ชื่อบทความ')), value: 'การใช้เครื่องมือดิจิทัลเพื่อสนับสนุนการเขียนอ้างอิงในงานวิจัย' },
                            { label: @js(__('ชื่อวารสาร')), value: 'วารสารวิชาการครุศาสตร์' },
                            { label: 'Volume / Issue', value: '9(3)' },
                            { label: @js(__('หน้า')), value: '101-119' },
                        ],
                    },
                    {
                        id: 6,
                        projectId: 1,
                        resourceType: 'บทความวารสาร',
                        text: 'Adams, R. T. (2023a). Academic citation practices in digital classrooms. Journal of Educational Technology, 14(1), 22-39.',
                        paperHtml: 'Adams, R. T. (2023a). Academic citation practices in digital classrooms. <em>Journal of Educational Technology, 14</em>(1), 22-39.',
                        narrativeCitation: 'Adams (2023a) stated that ...',
                        parentheticalCitation: '... (Adams, 2023a)',
                        detailFields: [
                            { label: @js(__('ประเภททรัพยากร')), value: @js(__('บทความวารสาร')) },
                            { label: @js(__('ผู้แต่ง')), value: 'Adams, R. T.' },
                            { label: @js(__('ปีที่พิมพ์')), value: '2023a' },
                            { label: @js(__('ชื่อบทความ')), value: 'Academic citation practices in digital classrooms' },
                            { label: @js(__('ชื่อวารสาร')), value: 'Journal of Educational Technology' },
                            { label: 'Volume / Issue', value: '14(1)' },
                            { label: @js(__('หน้า')), value: '22-39' },
                        ],
                    },
                    {
                        id: 7,
                        projectId: 1,
                        resourceType: 'หนังสือ',
                        text: 'Adams, R. T. (2023b). Designing reference workflows for student research projects. Learning Design Press.',
                        paperHtml: 'Adams, R. T. (2023b). <em>Designing reference workflows for student research projects</em>. Learning Design Press.',
                        narrativeCitation: 'Adams (2023b) stated that ...',
                        parentheticalCitation: '... (Adams, 2023b)',
                        detailFields: [
                            { label: @js(__('ประเภททรัพยากร')), value: @js(__('หนังสือ')) },
                            { label: @js(__('ผู้แต่ง')), value: 'Adams, R. T.' },
                            { label: @js(__('ปีที่พิมพ์')), value: '2023b' },
                            { label: @js(__('ชื่อเรื่อง')), value: 'Designing reference workflows for student research projects' },
                            { label: @js(__('สำนักพิมพ์')), value: 'Learning Design Press' },
                        ],
                    },
                    {
                        id: 8,
                        projectId: 1,
                        resourceType: 'หนังสือ',
                        text: 'Brown, L. M. (2022). Information literacy and source attribution in higher education. Routledge.',
                        paperHtml: 'Brown, L. M. (2022). <em>Information literacy and source attribution in higher education</em>. Routledge.',
                        narrativeCitation: 'Brown (2022) stated that ...',
                        parentheticalCitation: '... (Brown, 2022)',
                        detailFields: [
                            { label: @js(__('ประเภททรัพยากร')), value: @js(__('หนังสือ')) },
                            { label: @js(__('ผู้แต่ง')), value: 'Brown, L. M.' },
                            { label: @js(__('ปีที่พิมพ์')), value: '2022' },
                            { label: @js(__('ชื่อเรื่อง')), value: 'Information literacy and source attribution in higher education' },
                            { label: @js(__('สำนักพิมพ์')), value: 'Routledge' },
                        ],
                    },
                    {
                        id: 9,
                        projectId: 1,
                        resourceType: 'บทความวารสาร',
                        text: 'Carter, P. J. (2024). Metadata quality and automated bibliography generation. International Journal of Digital Libraries, 20(4), 233-251.',
                        paperHtml: 'Carter, P. J. (2024). Metadata quality and automated bibliography generation. <em>International Journal of Digital Libraries, 20</em>(4), 233-251.',
                        narrativeCitation: 'Carter (2024) stated that ...',
                        parentheticalCitation: '... (Carter, 2024)',
                        detailFields: [
                            { label: @js(__('ประเภททรัพยากร')), value: @js(__('บทความวารสาร')) },
                            { label: @js(__('ผู้แต่ง')), value: 'Carter, P. J.' },
                            { label: @js(__('ปีที่พิมพ์')), value: '2024' },
                            { label: @js(__('ชื่อบทความ')), value: 'Metadata quality and automated bibliography generation' },
                            { label: @js(__('ชื่อวารสาร')), value: 'International Journal of Digital Libraries' },
                            { label: 'Volume / Issue', value: '20(4)' },
                            { label: @js(__('หน้า')), value: '233-251' },
                        ],
                    },
                    {
                        id: 10,
                        projectId: 1,
                        resourceType: 'บทความวารสาร',
                        text: 'Smith, J. A. (2025). Citation management for interdisciplinary research teams. Research Methods Review, 12(2), 77-94.',
                        paperHtml: 'Smith, J. A. (2025). Citation management for interdisciplinary research teams. <em>Research Methods Review, 12</em>(2), 77-94.',
                        narrativeCitation: 'Smith (2025) stated that ...',
                        parentheticalCitation: '... (Smith, 2025)',
                        detailFields: [
                            { label: @js(__('ประเภททรัพยากร')), value: @js(__('บทความวารสาร')) },
                            { label: @js(__('ผู้แต่ง')), value: 'Smith, J. A.' },
                            { label: @js(__('ปีที่พิมพ์')), value: '2025' },
                            { label: @js(__('ชื่อบทความ')), value: 'Citation management for interdisciplinary research teams' },
                            { label: @js(__('ชื่อวารสาร')), value: 'Research Methods Review' },
                            { label: 'Volume / Issue', value: '12(2)' },
                            { label: @js(__('หน้า')), value: '77-94' },
                        ],
                    },
                ],
                init() {
                    window.addEventListener('citation-projects-updated', (event) => {
                        this.projectOptions = event.detail.projects || this.projectOptions;
                        this.activeProjectId = event.detail.activeProjectId ?? this.activeProjectId;

                        const fallbackProjectId = this.activeProjectId ?? this.projectOptions[0]?.id ?? 1;
                        const validProjectIds = new Set(this.projectOptions.map(project => project.id));

                        this.citations.forEach((entry) => {
                            if (!validProjectIds.has(this.entryProjectId(entry))) {
                                entry.projectId = fallbackProjectId;
                            }
                        });
                    });
                },
                toast(text, variant = 'success') {
                    window.Flux?.toast(text, { variant, position: 'bottom end' });
                },
                normalizeSmartSearchText(value) {
                    return String(value ?? '')
                        .toLowerCase()
                        .trim();
                },
                normalizeSmartSearchIdentifier(value) {
                    return String(value ?? '')
                        .toLowerCase()
                        .replace(/[\s-]+/g, '')
                        .trim();
                },
                smartSearchPlaceholder() {
                    if (this.activeQuickFilter === 'website') return @js(__('กรอก URL ของเว็บไซต์ หรือชื่อหน้าเว็บที่ต้องการอ้างอิง...'));
                    if (this.activeQuickFilter === 'book') return @js(__('กรอกชื่อหนังสือ, ISBN หรือชื่อผู้แต่ง...'));
                    if (this.activeQuickFilter === 'article') return @js(__('กรอกชื่อบทความ, DOI หรือชื่อวารสาร...'));

                    return @js(__('ค้นหาชื่อหนังสือ, ISBN, DOI หรือวาง URL...'));
                },
                smartSearchCategoryLabel() {
                    return {
                        website: @js(__('เว็บไซต์')),
                        book: @js(__('หนังสือ')),
                        article: @js(__('บทความ')),
                    }[this.activeQuickFilter] || @js(__('ทรัพยากร'));
                },
                smartSearchLoadingTitle() {
                    return `${@js(__('กำลังค้นหา'))}${this.smartSearchCategoryLabel()}${@js(__('ตัวอย่าง'))}`;
                },
                smartSearchLoadingDescription() {
                    if (this.activeQuickFilter === 'website') return @js(__('ระบบกำลังจับคู่ URL และชื่อหน้าเว็บกับข้อมูล mockup'));
                    if (this.activeQuickFilter === 'article') return @js(__('ระบบกำลังจับคู่ชื่อบทความ, DOI และชื่อวารสารกับข้อมูล mockup'));
                    if (this.activeQuickFilter === 'book') return @js(__('ระบบกำลังจับคู่ชื่อหนังสือ, ISBN และผู้แต่งกับข้อมูล mockup'));

                    return @js(__('ระบบกำลังจับคู่ชื่อหนังสือ, ISBN, DOI และ URL กับข้อมูล mockup'));
                },
                smartSearchEmptyTitle() {
                    return `${@js(__('ยังไม่พบ'))}${this.smartSearchCategoryLabel()}${@js(__('ตัวอย่างที่ตรงกับคำค้น'))}`;
                },
                smartSearchEmptyDescription() {
                    if (this.activeQuickFilter === 'website') return @js(__('ลองกรอก URL ของเว็บไซต์ให้ครบ หรือพิมพ์ชื่อหน้าเว็บที่ต้องการอ้างอิง'));
                    if (this.activeQuickFilter === 'article') return @js(__('ลองกรอกชื่อบทความ, DOI หรือชื่อวารสารให้ชัดขึ้น'));
                    if (this.activeQuickFilter === 'book') return @js(__('ลองกรอกชื่อหนังสือ, ISBN หรือชื่อผู้แต่งให้ชัดขึ้น'));

                    return @js(__('ลองพิมพ์ชื่อหนังสือให้ชัดขึ้น หรือค้นด้วย ISBN, DOI, URL เช่น ก, 978..., 10....'));
                },
                smartSearchManualActionLabel() {
                    if (this.activeQuickFilter === 'website') return @js(__('กรอกข้อมูลเว็บไซต์เอง'));
                    if (this.activeQuickFilter === 'article') return @js(__('กรอกข้อมูลบทความเอง'));

                    return @js(__('กรอกข้อมูลหนังสือเอง'));
                },
                smartSearchAccentClass(accent) {
                    return {
                        amber: 'border-amber-200 bg-amber-50 text-amber-600 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-300',
                        sky: 'border-sky-200 bg-sky-50 text-sky-600 dark:border-sky-500/30 dark:bg-sky-500/10 dark:text-sky-300',
                        emerald: 'border-emerald-200 bg-emerald-50 text-emerald-600 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-300',
                        rose: 'border-rose-200 bg-rose-50 text-rose-600 dark:border-rose-500/30 dark:bg-rose-500/10 dark:text-rose-300',
                        violet: 'border-violet-200 bg-violet-50 text-violet-600 dark:border-violet-500/30 dark:bg-violet-500/10 dark:text-violet-300',
                        orange: 'border-orange-200 bg-orange-50 text-orange-600 dark:border-orange-500/30 dark:bg-orange-500/10 dark:text-orange-300',
                        indigo: 'border-indigo-200 bg-indigo-50 text-indigo-600 dark:border-indigo-500/30 dark:bg-indigo-500/10 dark:text-indigo-300',
                        cyan: 'border-cyan-200 bg-cyan-50 text-cyan-600 dark:border-cyan-500/30 dark:bg-cyan-500/10 dark:text-cyan-300',
                        fuchsia: 'border-fuchsia-200 bg-fuchsia-50 text-fuchsia-600 dark:border-fuchsia-500/30 dark:bg-fuchsia-500/10 dark:text-fuchsia-300',
                    }[accent] || 'border-teal-200 bg-teal-50 text-teal-600 dark:border-teal-500/30 dark:bg-teal-500/10 dark:text-teal-300';
                },
                handleSmartSearchFocus() {
                    if (!this.smartQuery.trim()) return;

                    this.smartSearchOpen = true;
                    if (!this.smartSearchResults.length) {
                        this.queueSmartSearch();
                    }
                },
                closeSmartSearch() {
                    this.smartSearchOpen = false;
                    this.smartSearchLoading = false;
                    if (this.smartSearchTimer) {
                        clearTimeout(this.smartSearchTimer);
                    }
                },
                smartSearchPreview(book, matchType) {
                    if (matchType === 'ISBN') return `ISBN ${book.isbn}`;
                    if (matchType === 'DOI') return `DOI ${book.doi}`;
                    if (matchType === 'URL') return book.url;
                    if (book.subtitle) return book.subtitle;

                    return `${book.publisher} • ${book.year}`;
                },
                smartSearchResourceTypeForBook(book) {
                    if (book.resourceType) return book.resourceType;
                    if (book.category === 'website') return 'เอกสารอิเล็กทรอนิกส์ (เว็บเพจ)';
                    if (book.category === 'article' && book.doi) return 'บทความวารสารอิเล็กทรอนิกส์ (มี DOI)';
                    if (book.category === 'article') return 'บทความวารสาร';
                    if (book.doi) return 'หนังสืออิเล็กทรอนิกส์ (มี DOI)';
                    if (book.url) return 'หนังสืออิเล็กทรอนิกส์ (ไม่มี DOI)';

                    return 'หนังสือ';
                },
                fillBookFormFromSmartSearch(book) {
                    this.form.authors = (book.authors || []).map(author => this.normalizeAuthor(author));
                    if (!this.form.authors.length) {
                        this.form.authors = [this.emptyAuthor()];
                    }

                    this.form.year = book.year || '';
                    this.form.title = book.title || '';
                    this.form.publisher = book.category === 'book' ? (book.publisher || '') : '';
                    this.form.journalName = book.category === 'article' ? (book.publisher || '') : '';
                    this.form.websiteName = book.category === 'website' ? (book.publisher || '') : '';
                    this.form.doi = book.doi || '';
                    this.form.url = book.url || '';
                },
                seedBookFormFromQuery(query) {
                    const trimmed = String(query || '').trim();
                    const isUrl = /https?:\/\/|www\./i.test(trimmed);
                    const isDoi = /10\.\d{4,9}\/[\-._;()\/:a-z0-9]+/i.test(trimmed);

                    if (!trimmed) return;

                    if (isUrl) {
                        this.form.url = trimmed;
                        if (this.activeQuickFilter === 'website') this.form.websiteName = this.form.websiteName || @js(__('เว็บไซต์ที่ยังไม่ระบุ'));
                        return;
                    }

                    if (isDoi) {
                        this.form.doi = trimmed;
                        return;
                    }

                    if (!this.normalizeSmartSearchIdentifier(trimmed).match(/^(?:97[89])?(?:\d){10,13}[\dx]?$/i)) {
                        this.form.title = trimmed;
                    }
                },
                buildSmartSearchResults(query) {
                    const normalized = this.normalizeSmartSearchText(query);
                    const compact = normalized.replace(/\s+/g, ' ');
                    const identifier = this.normalizeSmartSearchIdentifier(query);
                    const tokens = compact.split(/[\s,()\/:-]+/).filter(Boolean);
                    const categoryFilter = this.activeQuickFilter;
                    const flags = {
                        isUrl: /https?:\/\/|www\./i.test(query),
                        isDoi: /10\.\d{4,9}\/[\-._;()\/:a-z0-9]+/i.test(query),
                        isIsbn: /(?:97[89][\-\s]?)?(?:\d[\-\s]?){9,12}[\dx]/i.test(query),
                    };

                    return this.smartSearchMockBooks
                        .map(book => {
                            if (categoryFilter && book.category !== categoryFilter) {
                                return null;
                            }

                            const titleText = this.normalizeSmartSearchText(book.title);
                            const subtitleText = this.normalizeSmartSearchText(book.subtitle);
                            const authorText = this.normalizeSmartSearchText(book.authorLine);
                            const publisherText = this.normalizeSmartSearchText(book.publisher);
                            const keywordText = this.normalizeSmartSearchText((book.keywords || []).join(' '));
                            const isbnText = this.normalizeSmartSearchIdentifier(book.isbn);
                            const doiText = this.normalizeSmartSearchText(book.doi);
                            const urlText = this.normalizeSmartSearchText(book.url);
                            let score = 0;
                            let matchType = '';
                            let matchLabel = '';

                            const noteMatch = (type, label, points) => {
                                score += points;
                                if (!matchType || points >= 90) {
                                    matchType = type;
                                    matchLabel = label;
                                }
                            };

                            const titleLabel = book.category === 'article'
                                ? @js(__('ตรงกับชื่อบทความ'))
                                : (book.category === 'website' ? @js(__('ตรงกับชื่อหน้าเว็บ')) : @js(__('ตรงกับชื่อหนังสือ')));

                            if (compact && titleText.startsWith(compact)) {
                                noteMatch('TITLE', titleLabel, 180);
                            } else if (compact && titleText.includes(compact)) {
                                noteMatch('TITLE', titleLabel, 135);
                            }

                            if (compact && subtitleText.includes(compact)) {
                                noteMatch('TITLE', @js(__('ตรงกับคำอธิบายรายการ')), 68);
                            }

                            if (compact && authorText.includes(compact)) {
                                noteMatch('AUTHOR', @js(__('ตรงกับชื่อผู้แต่ง')), 92);
                            }

                            if (identifier && isbnText.includes(identifier)) {
                                noteMatch('ISBN', @js(__('ตรงกับ ISBN')), 210);
                            }

                            if (compact && doiText.includes(compact)) {
                                noteMatch('DOI', @js(__('ตรงกับ DOI')), 215);
                            }

                            if (compact && urlText.includes(compact)) {
                                noteMatch('URL', @js(__('ตรงกับ URL')), 215);
                            }

                            tokens.forEach(token => {
                                if (titleText.includes(token)) score += token.length === 1 ? 18 : 26;
                                if (subtitleText.includes(token)) score += 10;
                                if (authorText.includes(token)) score += 18;
                                if (publisherText.includes(token)) score += 12;
                                if (keywordText.includes(token)) score += 14;
                            });

                            if (flags.isIsbn && book.isbn && book.category === 'book') {
                                score += 55;
                                matchType ||= 'ISBN';
                                matchLabel ||= @js(__('ค้นแบบ ISBN'));
                            }

                            if (flags.isDoi && book.doi) {
                                score += 55;
                                matchType ||= 'DOI';
                                matchLabel ||= @js(__('ค้นแบบ DOI'));
                            }

                            if (flags.isUrl && book.url) {
                                score += 55;
                                matchType ||= 'URL';
                                matchLabel ||= @js(__('ค้นแบบ URL'));
                            }

                            if (score <= 0) return null;

                            return {
                                key: book.id,
                                title: book.title,
                                icon: book.icon || 'book-open',
                                authorLine: book.authorLine,
                                metadataLine: `${book.year} • ${book.publisher}`,
                                formatLabel: book.formatLabel,
                                accentClass: this.smartSearchAccentClass(book.accent),
                                matchLabel: matchLabel || @js(__('ใกล้เคียงกับคำค้นนี้')),
                                preview: this.smartSearchPreview(book, matchType || 'TITLE'),
                                book,
                                score,
                            };
                        })
                        .filter(Boolean)
                        .sort((left, right) => right.score - left.score)
                        .slice(0, 6);
                },
                queueSmartSearch() {
                    const query = String(this.smartQuery || '').trim();

                    if (this.smartSearchTimer) {
                        clearTimeout(this.smartSearchTimer);
                    }

                    if (!query) {
                        this.smartSearchLoading = false;
                        this.smartSearchOpen = false;
                        this.smartSearchResults = [];
                        return;
                    }

                    this.smartSearchOpen = true;
                    this.smartSearchLoading = true;
                    const requestId = Date.now();
                    this.smartSearchRequestId = requestId;

                    this.smartSearchTimer = setTimeout(() => {
                        if (this.smartSearchRequestId !== requestId) return;
                        this.smartSearchResults = this.buildSmartSearchResults(query);
                        this.smartSearchLoading = false;
                        this.smartSearchOpen = true;
                    }, 380);
                },
                openSmartSearchResult(result) {
                    this.smartSearchResults = [];
                    this.smartSearchOpen = false;
                    this.smartSearchLoading = false;
                    this.openFormModal(this.smartSearchResourceTypeForBook(result.book));
                    this.fillBookFormFromSmartSearch(result.book);
                },
                openFirstSmartSearchResult() {
                    if (this.smartSearchLoading) return;

                    if (this.smartSearchResults.length) {
                        this.openSmartSearchResult(this.smartSearchResults[0]);
                        return;
                    }

                    this.openManualResourceFormFromSearch();
                },
                manualResourceTypeForQuickFilter() {
                    if (this.activeQuickFilter === 'website') return 'เอกสารอิเล็กทรอนิกส์ (เว็บเพจ)';
                    if (this.activeQuickFilter === 'article') return 'บทความวารสารอิเล็กทรอนิกส์ (มี DOI)';

                    return 'หนังสือ';
                },
                openManualResourceFormFromSearch() {
                    this.smartSearchOpen = false;
                    this.openFormModal(this.manualResourceTypeForQuickFilter());
                    this.seedBookFormFromQuery(this.smartQuery);
                },
                markCopied(entryId = null) {
                    if (entryId !== null) {
                        this.copiedEntryId = entryId;
                        setTimeout(() => {
                            if (this.copiedEntryId === entryId) this.copiedEntryId = null;
                        }, 1800);
                        return;
                    }

                    this.copied = true;
                    setTimeout(() => {
                        this.copied = false;
                    }, 1800);
                },
                async writeClipboardPayload(text, html = '') {
                    if (html && navigator.clipboard?.write && typeof ClipboardItem !== 'undefined') {
                        try {
                            await navigator.clipboard.write([
                                new ClipboardItem({
                                    'text/plain': new Blob([text], { type: 'text/plain' }),
                                    'text/html': new Blob([html], { type: 'text/html' }),
                                }),
                            ]);
                            return true;
                        } catch (error) {
                        }
                    }

                    await navigator.clipboard.writeText(text);
                    return false;
                },
                escapeHtml(value) {
                    return String(value ?? '')
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#039;');
                },
                entryPaperPreview(entry) {
                    return entry?.paperHtml || this.escapeHtml(entry?.text || '');
                },
                paperEntryClipboardHtml(entry) {
                    return `<p style="margin:0 0 0.75rem 0; line-height:2; padding-left:0.5in; text-indent:-0.5in;">${this.entryPaperPreview(entry)}</p>`;
                },
                paperViewClipboardHtml() {
                    return this.filteredCitations()
                        .map(entry => this.paperEntryClipboardHtml(entry))
                        .join('');
                },
                projectNameById(projectId) {
                    return this.projectOptions.find(project => project.id === projectId)?.name || @js(__('โครงการที่ยังไม่ระบุ'));
                },
                entryProjectId(entry) {
                    return entry?.projectId ?? 1;
                },
                entryTypeLabel(entry) {
                    return this.resourceTypeLabel(entry?.resourceType || '') || @js(__('รายการบรรณานุกรม'));
                },
                entryNarrativeCitation(entry) {
                    return entry?.narrativeCitation || '-';
                },
                entryNarrativePreview(entry) {
                    const narrative = String(entry?.narrativeCitation || '').trim();
                    if (!narrative) return '-';

                    return narrative
                        .replace(/\s+(กล่าวว่า|stated that)\s*\.\.\.$/i, '')
                        .replace(/\s+\.\.\.$/, '')
                        .trim();
                },
                entryParentheticalCitation(entry) {
                    return entry?.parentheticalCitation || '-';
                },
                entryDetailFields(entry) {
                    return entry?.detailFields || [];
                },
                emptyEditDraft() {
                    return {
                        resourceType: '',
                        text: '',
                        narrativeCitation: '',
                        parentheticalCitation: '',
                        detailFields: [],
                    };
                },
                cloneDetailFields(fields) {
                    return (fields || []).map(field => ({
                        label: field.label || '',
                        value: field.value || '',
                    }));
                },
                citationSortKey(entry) {
                    return String(entry?.text || '').trim();
                },
                compareCitationEntries(left, right) {
                    const comparison = this.citationSortKey(left).localeCompare(this.citationSortKey(right), ['th', 'en'], {
                        numeric: true,
                        sensitivity: 'base',
                    });

                    if (comparison !== 0) return comparison;

                    return Number(left?.id || 0) - Number(right?.id || 0);
                },
                filteredCitations() {
                    return this.citations
                        .filter(entry => this.entryProjectId(entry) === this.activeProjectId)
                        .slice()
                        .sort((left, right) => this.compareCitationEntries(left, right));
                },
                entryCardClasses(entry, mode) {
                    if (this.recentlyAddedEntryId !== entry?.id) return '';

                    if (mode === 'paper') {
                        return 'recent-citation-entry bg-sky-50/90 ring-2 ring-sky-300 shadow-lg shadow-indigo-100/70 dark:bg-indigo-500/10 dark:ring-sky-500/40 dark:shadow-none';
                    }

                    return 'recent-citation-entry border-indigo-400 bg-sky-50 shadow-lg shadow-indigo-100/80 dark:border-sky-500 dark:bg-indigo-500/10 dark:shadow-none';
                },
                scrollToEntry(entryId) {
                    this.$nextTick(() => {
                        const entryElement = this.$root.querySelector(`[data-entry-id="${entryId}"]`);
                        if (!entryElement) return;

                        entryElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center',
                            inline: 'nearest',
                        });
                    });
                },
                markRecentlyAddedEntry(entryId) {
                    this.recentlyAddedEntryId = entryId;
                    this.scrollToEntry(entryId);

                    if (this.recentEntryTimer) {
                        clearTimeout(this.recentEntryTimer);
                    }

                    this.recentEntryTimer = setTimeout(() => {
                        if (this.recentlyAddedEntryId === entryId) {
                            this.recentlyAddedEntryId = null;
                        }
                    }, 4200);
                },
                emptyAuthor() {
                    return {
                        condition: 'normal',
                        firstName: '',
                        middleName: '',
                        lastName: '',
                        displayName: '',
                    };
                },
                normalizeAuthor(author = {}) {
                    return {
                        condition: author.condition || 'normal',
                        firstName: author.firstName || '',
                        middleName: author.middleName || '',
                        lastName: author.lastName || '',
                        displayName: author.displayName || '',
                    };
                },
                hasAuthorData(author = {}) {
                    const condition = author.condition || 'normal';
                    return condition === 'anonymous'
                        || String(author.firstName || '').trim()
                        || String(author.middleName || '').trim()
                        || String(author.lastName || '').trim()
                        || String(author.displayName || '').trim();
                },
                authorConditionMeta(condition) {
                    return this.authorConditionOptions.find(option => option.value === condition) || this.authorConditionOptions[0];
                },
                authorConditionExample(condition) {
                    return this.authorConditionMeta(condition)?.example || '';
                },
                modalText(key) {
                    return this.modalI18n[key] || '';
                },
                resourceTypeLabel(type) {
                    return this.resourceTypeLabels[type] || type || '';
                },
                usesSpecialAuthorDisplayName(condition) {
                    return ['anonymous', 'pseudonym', 'royal_title', 'noble_title', 'buddhist_monk', 'organization'].includes(condition);
                },
                authorFullName(author = {}) {
                    return [author.firstName, author.middleName, author.lastName]
                        .map(value => String(value || '').trim())
                        .filter(Boolean)
                        .join(' ')
                        .trim();
                },
                authorDisplayName(author = {}) {
                    const condition = author.condition || 'normal';
                    const customName = String(author.displayName || '').trim();
                    const fullName = this.authorFullName(author);

                    if (condition === 'anonymous') return customName || this.modalText('anonymousFallback');
                    if (this.usesSpecialAuthorDisplayName(condition)) return customName || fullName;

                    return fullName;
                },
                authorUsesLatin(author = {}) {
                    return /[A-Za-z]/.test(this.authorDisplayName(author) || this.authorFullName(author));
                },
                authorInitials(author = {}) {
                    return [author.firstName, author.middleName]
                        .map(value => String(value || '').trim())
                        .filter(Boolean)
                        .map(name => name
                            .split(/[\s-]+/)
                            .filter(Boolean)
                            .map(part => `${part.charAt(0).toUpperCase()}.`)
                            .join(' '))
                        .join(' ')
                        .trim();
                },
                formatSingleAuthorBibliography(author = {}) {
                    const condition = author.condition || 'normal';
                    const displayName = this.authorDisplayName(author);
                    const lastName = String(author.lastName || '').trim();
                    const initials = this.authorInitials(author);
                    const fullName = this.authorFullName(author);

                    if (!this.hasAuthorData(author)) return '';

                    if (condition === 'anonymous') return displayName;
                    if (condition === 'organization') return displayName;
                    if (condition === 'pseudonym' || condition === 'royal_title' || condition === 'noble_title' || condition === 'buddhist_monk') {
                        return displayName;
                    }

                    if (this.authorUsesLatin(author) && lastName) {
                        const latinName = initials ? `${lastName}, ${initials}` : lastName;
                        return condition === 'editor' ? `${latinName} (Ed.)` : latinName;
                    }

                    const fallbackName = displayName || fullName;
                    return condition === 'editor' && fallbackName ? `${fallbackName} (บ.ก.)` : fallbackName;
                },
                formatSingleAuthorCitation(author = {}) {
                    const condition = author.condition || 'normal';
                    const displayName = this.authorDisplayName(author);
                    const lastName = String(author.lastName || '').trim();

                    if (!this.hasAuthorData(author)) return '';

                    if (condition === 'anonymous' || condition === 'organization') return displayName;
                    if (condition === 'pseudonym' || condition === 'royal_title' || condition === 'noble_title' || condition === 'buddhist_monk') {
                        return displayName;
                    }

                    if (this.authorUsesLatin(author) && lastName) return lastName;

                    return displayName;
                },
                joinAuthors(names, conjunction = '&') {
                    const valid = names.filter(Boolean);
                    if (!valid.length) return '';
                    if (valid.length === 1) return valid[0];
                    if (valid.length === 2) return `${valid[0]} ${conjunction} ${valid[1]}`;

                    return `${valid.slice(0, -1).join(', ')}, ${conjunction} ${valid[valid.length - 1]}`;
                },
                buildDetailFields() {
                    const fields = [];
                    const append = (label, value) => {
                        const normalized = String(value ?? '').trim();
                        if (normalized) fields.push({ label, value: normalized });
                    };
                    const authors = this.form.authors
                        .filter(author => this.hasAuthorData(author))
                        .map(author => this.formatSingleAuthorBibliography(author))
                        .filter(Boolean)
                        .join('; ');

                    append(this.modalText('detailFieldResourceType'), this.resourceTypeLabel(this.formResourceType));
                    append(this.modalText('detailFieldAuthors'), authors);
                    append(this.modalText('detailFieldYear'), this.form.year);
                    if (this.usesDetailedDate()) {
                        append(this.modalText('detailFieldMonth'), this.form.month);
                        append(this.modalText('detailFieldDay'), this.form.day);
                    }
                    append(this.isJournalType() ? this.modalText('detailFieldArticleTitle') : this.modalText('detailFieldTitle'), this.form.title || this.form.prompt);

                    if (this.isBookType()) {
                        append(this.modalText('detailFieldPublisher'), this.form.publisher);
                        append(this.modalText('detailFieldVolume'), this.form.volume);
                        append(this.modalText('detailFieldEdition'), this.form.edition);
                        append(this.modalText('detailFieldEditor'), this.form.editor);
                        append(this.modalText('detailFieldBookTitle'), this.form.bookTitle);
                        append(this.modalText('detailFieldPages'), this.form.pages);
                        append('DOI', this.form.doi);
                        append('URL', this.form.url);
                    } else if (this.isJournalType()) {
                        append(this.modalText('detailFieldJournalName'), this.form.journalName);
                        append('Volume', this.form.volume);
                        append(this.modalText('detailFieldIssue'), this.form.issue);
                        append(this.modalText('detailFieldPages'), this.form.pages);
                        append('DOI', this.form.doi);
                        append('URL', this.form.url);
                    } else if (this.isDictionaryType()) {
                        append(this.modalText('detailFieldReferenceWork'), this.form.referenceWork);
                        append(this.modalText('detailFieldEdition'), this.form.edition);
                        append(this.modalText('detailFieldVolume'), this.form.volume);
                        append(this.modalText('detailFieldPublisher'), this.form.publisher);
                        append('URL', this.form.url);
                    } else if (this.isNewspaperType()) {
                        append(this.modalText('detailFieldNewspaperName'), this.form.newspaperName);
                        append(this.modalText('detailFieldPages'), this.form.pages);
                        append('URL', this.form.url);
                    } else if (this.isReportType()) {
                        append(this.modalText('detailFieldOrganization'), this.form.organization);
                        append('Report No.', this.form.reportNumber);
                        append(this.modalText('detailFieldPublisher'), this.form.publisher);
                        append('URL', this.form.url);
                    } else if (this.isConferenceType()) {
                        append(this.modalText('detailFieldConferenceName'), this.form.conferenceName);
                        append(this.modalText('detailFieldConferenceLocation'), this.form.conferenceLocation);
                        append(this.modalText('detailFieldConferencePages'), this.form.pages);
                        append(this.modalText('detailFieldPublisherProceeding'), this.form.publisher);
                        append('URL', this.form.url);
                    } else if (this.isWebType()) {
                        append(this.modalText('detailFieldWebsiteName'), this.form.websiteName);
                        append('Patent No.', this.form.patentNumber);
                        append(this.modalText('detailFieldAssignee'), this.form.assignee || this.form.websiteName);
                        append('URL', this.form.url);
                    } else if (this.isThesisType()) {
                        append(this.modalText('detailFieldThesisType'), this.form.thesisType === 'doctoral' ? this.modalText('thesisTypeDoctoral') : this.modalText('thesisTypeMaster'));
                        append(this.modalText('detailFieldUniversity'), this.form.university);
                        append(this.modalText('detailFieldDatabase'), this.form.databaseName);
                        append('URL', this.form.url);
                    } else if (this.isMediaType()) {
                        append(this.modalText('detailFieldMediaType'), this.form.medium);
                        append(this.modalText('detailFieldPlatform'), this.form.platform);
                        append('URL', this.form.url);
                    } else if (this.isAiType()) {
                        append(this.modalText('detailFieldPlatform'), this.form.platform);
                        append(this.modalText('detailFieldModel'), this.form.model);
                        append(this.modalText('detailFieldPrompt'), this.form.prompt);
                        append('URL', this.form.url);
                    }

                    return fields;
                },
                resetForm() {
                    this.form = {
                        authors: [this.emptyAuthor()],
                        year: '', month: '', day: '', title: '', publisher: '', volume: '', issue: '',
                        pages: '', edition: '', editor: '', bookTitle: '', doi: '', url: '',
                        journalName: '', websiteName: '', thesisType: 'master', university: '', databaseName: '',
                        referenceWork: '', newspaperName: '', organization: '', reportNumber: '',
                        conferenceName: '', conferenceLocation: '', patentNumber: '', assignee: '',
                        platform: '', medium: '', model: '', prompt: '',
                    };
                },
                openFormModal(type) {
                    this.formResourceType = type;
                    this.selectedType = type;
                    this.activeQuickFilter = '';
                    this.smartSearchResults = [];
                    this.smartSearchOpen = false;
                    this.smartSearchLoading = false;
                    this.modalOpen = false;
                    this.resetForm();
                    this.formModalOpen = true;
                },
                setQuickFilter(filterKey) {
                    this.smartQuery = '';
                    this.selectedType = '';
                    this.activeQuickFilter = filterKey;
                    this.smartSearchResults = [];
                    this.smartSearchLoading = false;
                    this.smartSearchOpen = false;
                    this.$nextTick(() => this.$refs.smartSearchInput?.focus());
                },
                isBookType() {
                    return ['หนังสือ', 'หนังสือชุดหลายเล่มจบ', 'บทความในหนังสือ', 'หนังสืออิเล็กทรอนิกส์ (มี DOI)', 'หนังสืออิเล็กทรอนิกส์ (ไม่มี DOI)'].includes(this.formResourceType);
                },
                isJournalType() {
                    return ['บทความวารสาร', 'บทความวารสารอิเล็กทรอนิกส์ (มี DOI)', 'บทความวารสารอิเล็กทรอนิกส์ (ไม่มี DOI)', 'วารสารอิเล็กทรอนิกส์ (แบบมีฉบับพิมพ์)', 'วารสารอิเล็กทรอนิกส์ (แบบไม่มีฉบับพิมพ์)'].includes(this.formResourceType);
                },
                isWebType() {
                    return ['เอกสารอิเล็กทรอนิกส์ (เว็บเพจ)', 'สื่อออนไลน์ (วิดีโอออนไลน์ บทความในโซเชียลมีเดีย)', 'ราชกิจจานุเบกษาออนไลน์', 'สิทธิบัตรออนไลน์', 'การติดต่อสื่อสารส่วนบุคคล'].includes(this.formResourceType);
                },
                isThesisType() {
                    return ['วิทยานิพนธ์ (ที่ไม่ได้ตีพิมพ์)', 'วิทยานิพนธ์จากเว็บไซต์', 'วิทยานิพนธ์จากฐานข้อมูลเชิงพาณิชย์'].includes(this.formResourceType);
                },
                isDictionaryType() {
                    return ['พจนานุกรม', 'พจนานุกรมออนไลน์', 'สารานุกรม', 'สารานุกรมออนไลน์'].includes(this.formResourceType);
                },
                isNewspaperType() {
                    return ['หนังสือพิมพ์แบบรูปเล่ม', 'หนังสือพิมพ์ออนไลน์'].includes(this.formResourceType);
                },
                isReportType() {
                    return ['รายงาน', 'รายงานการวิจัย', 'รายงานที่จัดทำโดยหน่วยงานราชการหรือองค์กรอื่น', 'รายงานที่จัดทำโดยบุคคลที่สังกัดหน่วยงาน'].includes(this.formResourceType);
                },
                isConferenceType() {
                    return ['เอกสารการประชุมทางวิชาการ (ที่มี Proceeding)', 'เอกสารการประชุมทางวิชาการ (ที่ไม่มี Proceeding)', 'การนำเสนองานวิจัยหรือโปสเตอร์ในงานประชุมวิชาการ'].includes(this.formResourceType);
                },
                isMediaType() {
                    return ['อินโฟกราฟิก (Infographic)', 'การนำเสนอด้วยสไลด์และเอกสารการสอนออนไลน์', 'สัมมนาออนไลน์ (Webinar)', 'วิดีโอใน Youtube หรือวิดีโอออนไลน์ต่าง ๆ', 'พอดแคสต์ภาพและเสียง (แบบจบในตอน)', 'พอดแคสต์ภาพและเสียง (แบบหลายตอน)'].includes(this.formResourceType);
                },
                isAiType() {
                    return this.formResourceType === 'AI (เนื้อหาที่สร้างโดย AI)';
                },
                usesDetailedDate() {
                    return this.isWebType() || this.isNewspaperType() || this.isConferenceType() || this.isMediaType() || this.isAiType();
                },
                formatDate() {
                    const year = this.form.year.trim();
                    const month = this.form.month.trim();
                    const day = this.form.day.trim();

                    if (year && month && day) return ` (${year}, ${month} ${day}). `;
                    if (year && month) return ` (${year}, ${month}). `;
                    if (year) return ` (${year}). `;
                    if (month && day) return ` (${this.modalText('noDate')}, ${month} ${day}). `;

                    return ` (${this.modalText('noDate')}). `;
                },
                formatAuthors() {
                    return this.joinAuthors(
                        this.form.authors
                            .filter(author => this.hasAuthorData(author))
                            .map(author => this.formatSingleAuthorBibliography(author)),
                        '&'
                    );
                },
                formatAuthorsCitation() {
                    const valid = this.form.authors
                        .filter(author => this.hasAuthorData(author))
                        .map(author => this.formatSingleAuthorCitation(author))
                        .filter(Boolean);

                    if (!valid.length) return '';
                    if (valid.length === 1) return valid[0];
                    if (valid.length === 2) return `${valid[0]} ${this.modalText('authorCitationConjunction')} ${valid[1]}`;

                    return `${valid[0]} ${this.modalText('authorEtAl')}`;
                },
                generateBibliography() {
                    const authors = this.formatAuthors();
                    const year = this.form.year.trim();
                    const title = this.form.title.trim();
                    const titleOrPrompt = title || this.form.prompt.trim();
                    const organization = this.form.organization.trim();
                    const assignee = this.form.assignee.trim();
                    const platform = this.form.platform.trim();
                    const websiteName = this.form.websiteName.trim();
                    const creator = authors || organization || assignee || platform || websiteName;
                    if (!creator && !titleOrPrompt) return '';
                    let bibliography = '';
                    if (this.formResourceType === 'การติดต่อสื่อสารส่วนบุคคล') {
                        return this.modalText('personalCommunicationNotice');
                    }

                    if (creator) bibliography += creator;
                    bibliography += this.usesDetailedDate() ? this.formatDate() : (year ? ` (${year}). ` : ` (${this.modalText('noDate')}). `);

                    if (this.isBookType()) {
                        if (this.formResourceType === 'บทความในหนังสือ') {
                            bibliography += title ? `${title}. ` : '';
                            if (this.form.editor.trim()) bibliography += `In ${this.form.editor.trim()} (Ed.), `;
                            if (this.form.bookTitle.trim()) bibliography += `${this.form.bookTitle.trim()}`;
                            if (this.form.pages.trim()) bibliography += ` (pp. ${this.form.pages.trim()})`;
                            bibliography += '. ';
                            if (this.form.publisher.trim()) bibliography += `${this.form.publisher.trim()}.`;
                        } else {
                            bibliography += title ? `${title}` : '';
                            if (this.form.volume.trim()) bibliography += ` (Vols. ${this.form.volume.trim()})`;
                            if (this.form.edition.trim()) bibliography += ` (${this.form.edition.trim()} ed.)`;
                            bibliography += '. ';
                            if (this.form.publisher.trim()) bibliography += `${this.form.publisher.trim()}.`;
                            if (this.form.doi.trim()) bibliography += ` ${this.form.doi.trim()}`;
                            if (!this.form.doi.trim() && this.form.url.trim()) bibliography += ` ${this.form.url.trim()}`;
                        }
                    } else if (this.isJournalType()) {
                        bibliography += title ? `${title}. ` : '';
                        if (this.form.journalName.trim()) bibliography += `${this.form.journalName.trim()}`;
                        if (this.form.volume.trim()) bibliography += `, ${this.form.volume.trim()}`;
                        if (this.form.issue.trim()) bibliography += `(${this.form.issue.trim()})`;
                        if (this.form.pages.trim()) bibliography += `, ${this.form.pages.trim()}`;
                        bibliography += '.';
                        if (this.form.doi.trim()) bibliography += ` ${this.form.doi.trim()}`;
                        if (!this.form.doi.trim() && this.form.url.trim()) bibliography += ` ${this.form.url.trim()}`;
                    } else if (this.isDictionaryType()) {
                        bibliography += title ? `${title}. ` : '';
                        if (this.form.referenceWork.trim()) bibliography += `In ${this.form.referenceWork.trim()}`;
                        if (this.form.edition.trim()) bibliography += ` (${this.form.edition.trim()} ed.)`;
                        if (this.form.volume.trim()) bibliography += ` (Vol. ${this.form.volume.trim()})`;
                        bibliography += '. ';
                        if (this.form.publisher.trim()) bibliography += `${this.form.publisher.trim()}. `;
                        if (this.form.url.trim()) bibliography += `${this.form.url.trim()}`;
                    } else if (this.isNewspaperType()) {
                        bibliography += title ? `${title}. ` : '';
                        if (this.form.newspaperName.trim()) bibliography += `${this.form.newspaperName.trim()}`;
                        if (this.form.pages.trim()) bibliography += `, ${this.form.pages.trim()}`;
                        bibliography += '.';
                        if (this.form.url.trim()) bibliography += ` ${this.form.url.trim()}`;
                    } else if (this.isReportType()) {
                        bibliography += title ? `${title}` : '';
                        if (this.form.reportNumber.trim()) bibliography += ` (Report No. ${this.form.reportNumber.trim()})`;
                        bibliography += '. ';
                        if (this.form.publisher.trim()) bibliography += `${this.form.publisher.trim()}. `;
                        if (this.form.url.trim()) bibliography += `${this.form.url.trim()}`;
                    } else if (this.isConferenceType()) {
                        bibliography += title ? `${title}. ` : '';
                        if (this.form.conferenceName.trim()) bibliography += `${this.form.conferenceName.trim()}`;
                        if (this.form.conferenceLocation.trim()) bibliography += `, ${this.form.conferenceLocation.trim()}`;
                        if (this.form.pages.trim()) bibliography += ` (pp. ${this.form.pages.trim()})`;
                        bibliography += '. ';
                        if (this.form.publisher.trim()) bibliography += `${this.form.publisher.trim()}. `;
                        if (this.form.url.trim()) bibliography += `${this.form.url.trim()}`;
                    } else if (this.isWebType()) {
                        if (this.formResourceType === 'สิทธิบัตรออนไลน์') {
                            bibliography += title ? `${title}` : '';
                            if (this.form.patentNumber.trim()) bibliography += ` (${this.form.patentNumber.trim()})`;
                            bibliography += '. ';
                            if (websiteName) bibliography += `${websiteName}. `;
                            if (this.form.url.trim()) bibliography += `${this.form.url.trim()}`;
                            return bibliography.replace(/\.\./g, '.').replace(/\s{2,}/g, ' ').trim();
                        }
                        bibliography += title ? `${title}. ` : '';
                        if (websiteName) bibliography += `${websiteName}. `;
                        if (this.form.url.trim()) bibliography += `${this.form.url.trim()}`;
                    } else if (this.isThesisType()) {
                        bibliography += title ? `${title} ` : '';
                        const thesisType = this.form.thesisType === 'doctoral' ? this.modalText('thesisTypeDoctoral') : this.modalText('thesisTypeMaster');
                        if (this.form.university.trim()) bibliography += `[${thesisType}, ${this.form.university.trim()}]. `;
                        else bibliography += `[${thesisType}]. `;
                        if (this.form.databaseName.trim()) bibliography += `${this.form.databaseName.trim()}. `;
                        if (this.form.url.trim()) bibliography += `${this.form.url.trim()}`;
                    } else if (this.isMediaType()) {
                        bibliography += title ? `${title}. ` : '';
                        if (this.form.medium.trim()) bibliography += `[${this.form.medium.trim()}]. `;
                        if (platform) bibliography += `${platform}. `;
                        if (this.form.url.trim()) bibliography += `${this.form.url.trim()}`;
                    } else if (this.isAiType()) {
                        bibliography += titleOrPrompt ? `${titleOrPrompt}. ` : '';
                        if (this.form.model.trim()) bibliography += `[${this.form.model.trim()}]. `;
                        if (this.form.url.trim()) bibliography += `${this.form.url.trim()}`;
                    } else {
                        bibliography += title ? `${title}.` : '';
                    }

                    return bibliography.replace(/\.\./g, '.').replace(/\s{2,}/g, ' ').trim();
                },
                generateNarrativeCitation() {
                    const authors = this.formatAuthorsCitation();
                    const year = this.form.year.trim();
                    if (!authors) return '';
                    return `${authors} (${year || this.modalText('noDate')}) ${this.modalText('narrativeSuffix')}`;
                },
                generateParentheticalCitation() {
                    const authors = this.formatAuthorsCitation();
                    const year = this.form.year.trim();
                    if (!authors) return '';
                    return `... (${authors}, ${year || this.modalText('noDate')})`;
                },
                getFormatHint() {
                    if (this.isBookType()) return this.modalText('formatHintBook');
                    if (this.isJournalType()) return this.modalText('formatHintJournal');
                    if (this.isWebType()) return this.modalText('formatHintWebsite');
                    if (this.isThesisType()) return this.modalText('formatHintThesis');
                    return this.modalText('formatHintDefault');
                },
                addCitationFromForm() {
                    const bibliography = this.generateBibliography();
                    if (!bibliography) {
                        this.toast(this.modalText('addCitationWarning'), 'warning');
                        return;
                    }
                    const newEntry = {
                        id: Date.now(),
                        projectId: this.activeProjectId,
                        resourceType: this.formResourceType,
                        text: bibliography,
                        paperHtml: this.escapeHtml(bibliography),
                        narrativeCitation: this.generateNarrativeCitation(),
                        parentheticalCitation: this.generateParentheticalCitation(),
                        detailFields: this.buildDetailFields(),
                    };

                    this.citations.push(newEntry);
                    this.formModalOpen = false;
                    this.resetForm();
                    this.markRecentlyAddedEntry(newEntry.id);
                    this.toast(this.modalText('addCitationSuccess'), 'success');
                },
                async copyCurrentView() {
                    const targetRef = this.displayMode === 'list'
                        ? this.$refs.listView
                        : (this.displayMode === 'citation' ? this.$refs.citationView : this.$refs.paperView);
                    const plainText = String(targetRef?.innerText || '').trim();

                    if (!plainText) return;

                    if (this.displayMode === 'paper') {
                        await this.writeClipboardPayload(
                            this.filteredCitations().map(entry => entry.text).join('\n\n'),
                            this.paperViewClipboardHtml(),
                        );
                    } else {
                        await this.writeClipboardPayload(plainText);
                    }

                    this.markCopied();
                },
                async copyEntry(entry) {
                    await this.writeClipboardPayload(
                        entry.text,
                        this.paperEntryClipboardHtml(entry),
                    );
                    this.markCopied(entry.id);
                },
                viewEntry(entry) {
                    this.activeEntry = entry;
                    this.detailModalOpen = true;
                },
                moveEntry(entry) {
                    this.activeEntry = entry;
                    this.moveTargetProjectId = this.projectOptions.find(project => project.id !== this.entryProjectId(entry))?.id ?? this.entryProjectId(entry);
                    this.moveModalOpen = true;
                },
                requestDeleteEntry(entry) {
                    this.activeEntry = entry;
                    this.deleteEntryModalOpen = true;
                },
                editEntry(entry) {
                    this.activeEntry = entry;
                    this.editEntryDraft = {
                        resourceType: entry.resourceType || '',
                        text: entry.text || '',
                        narrativeCitation: entry.narrativeCitation || '',
                        parentheticalCitation: entry.parentheticalCitation || '',
                        detailFields: this.cloneDetailFields(entry.detailFields),
                    };
                    this.editModalOpen = true;
                },
                addEditField() {
                    this.editEntryDraft.detailFields.push({ label: '', value: '' });
                },
                removeEditField(index) {
                    this.editEntryDraft.detailFields.splice(index, 1);
                },
                saveEntryEdit() {
                    if (!this.activeEntry) return;
                    if (!this.editEntryDraft?.text?.trim()) {
                        this.toast(@js(__('ไม่สามารถบันทึกรายการว่างได้')), 'warning');
                        return;
                    }
                    this.activeEntry.resourceType = this.editEntryDraft.resourceType.trim() || this.activeEntry.resourceType;
                    this.activeEntry.text = this.editEntryDraft.text.trim();
                    this.activeEntry.paperHtml = this.escapeHtml(this.editEntryDraft.text.trim());
                    this.activeEntry.narrativeCitation = this.editEntryDraft.narrativeCitation.trim();
                    this.activeEntry.parentheticalCitation = this.editEntryDraft.parentheticalCitation.trim();
                    this.activeEntry.detailFields = this.editEntryDraft.detailFields
                        .map(field => ({
                            label: String(field.label || '').trim(),
                            value: String(field.value || '').trim(),
                        }))
                        .filter(field => field.label || field.value);
                    this.closeEntryModals();
                    this.toast(@js(__('อัปเดตรายการเรียบร้อยแล้ว')), 'success');
                },
                confirmMoveEntry() {
                    if (!this.activeEntry) return;
                    if (!this.moveTargetProjectId || this.moveTargetProjectId === this.entryProjectId(this.activeEntry)) {
                        this.toast(@js(__('กรุณาเลือกโครงการปลายทางอื่น')), 'warning');
                        return;
                    }
                    this.activeEntry.projectId = this.moveTargetProjectId;
                    this.closeEntryModals();
                    this.toast(`${@js(__('ย้ายรายการไปยังโครงการ'))} ${this.projectNameById(this.moveTargetProjectId)} ${@js(__('แล้ว'))}`, 'success');
                },
                confirmDeleteEntry() {
                    if (!this.activeEntry) return;

                    const entryId = this.activeEntry.id;
                    const index = this.citations.findIndex(entry => entry.id === entryId);
                    if (index === -1) {
                        this.toast(@js(__('ไม่พบรายการที่ต้องการลบ')), 'danger');
                        this.closeEntryModals();
                        return;
                    }

                    this.citations.splice(index, 1);

                    if (this.recentlyAddedEntryId === entryId) {
                        this.recentlyAddedEntryId = null;
                    }

                    this.closeEntryModals();
                    this.toast(@js(__('ลบรายการบรรณานุกรมเรียบร้อยแล้ว')), 'danger');
                },
                closeEntryModals() {
                    this.detailModalOpen = false;
                    this.editModalOpen = false;
                    this.moveModalOpen = false;
                    this.deleteEntryModalOpen = false;
                    this.activeEntry = null;
                    this.editEntryDraft = this.emptyEditDraft();
                    this.moveTargetProjectId = null;
                },
            };
        };
    </script>
    <flux:toast position="bottom end" class="z-[120]" />
    @fluxScripts
</body>

</html>
