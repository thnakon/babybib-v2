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
                box-shadow: 0 0 0 0 rgba(236, 72, 153, 0.28);
                transform: translateY(0);
            }

            50% {
                box-shadow: 0 0 0 12px rgba(236, 72, 153, 0);
                transform: translateY(-2px);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(236, 72, 153, 0);
                transform: translateY(0);
            }
        }

        .recent-citation-entry {
            animation: recent-citation-pulse 1.8s ease-out 2;
        }
    </style>
</head>

<body
    class="min-h-screen font-sans antialiased bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 overflow-x-hidden">
    <!-- Navbar (Shared) -->
    <flux:header sticky
        class="sticky top-0 w-full z-50 border-b bg-white/95 dark:bg-zinc-900/95 border-pink-100 dark:border-zinc-800 shadow-sm shadow-pink-100/40 backdrop-blur-md py-3">
        <div class="container max-w-7xl mx-auto px-6 flex items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="/" class="flex items-center gap-2 group">
                    <div class="flex gap-0.5 items-end h-5">
                        <div class="w-1 bg-pink-400 dark:bg-pink-300 h-2 rounded-full transition-all group-hover:h-4"></div>
                        <div class="w-1 bg-pink-500 dark:bg-pink-200 h-4 rounded-full transition-all group-hover:h-3"></div>
                        <div class="w-1 bg-pink-600 dark:bg-pink-100 h-5 rounded-full transition-all group-hover:h-2">
                        </div>
                    </div>
                    <span class="relative text-xl font-bold tracking-tight text-pink-700 dark:text-pink-200">
                        Babybib
                        <span class="absolute -right-5 -top-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-pink-300 dark:text-pink-400">v2</span>
                    </span>
                </a>
            </div>

            <div class="flex items-center gap-1">
                <flux:navbar class="hidden md:flex gap-1 text-sm font-medium text-zinc-500 mr-2 [&_[data-current]]:text-pink-600 dark:[&_[data-current]]:text-pink-300 [&_[data-current]]:border-pink-500">
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
                                class="bg-white dark:bg-zinc-900 rounded-xl border border-pink-100 dark:border-zinc-800 shadow-xl shadow-pink-100/40 overflow-hidden p-1.5">
                                <flux:menu.item href="#"
                                    class="!text-zinc-600 dark:!text-zinc-400 hover:!bg-pink-50 hover:!text-pink-700 dark:hover:!bg-pink-500/10 dark:hover:!text-pink-200 transition-colors">
                                    {{ __('Help Center') }}</flux:menu.item>
                                <flux:menu.item href="#"
                                    class="!text-zinc-600 dark:!text-zinc-400 hover:!bg-pink-50 hover:!text-pink-700 dark:hover:!bg-pink-500/10 dark:hover:!text-pink-200 transition-colors">
                                    {{ __('Contact Support') }}</flux:menu.item>
                                <flux:menu.separator />
                                <flux:menu.item href="#"
                                    class="!text-zinc-600 dark:!text-zinc-400 hover:!bg-pink-50 hover:!text-pink-700 dark:hover:!bg-pink-500/10 dark:hover:!text-pink-200 transition-colors">
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
                                class="bg-white dark:bg-zinc-900 rounded-xl border border-pink-100 dark:border-zinc-800 shadow-xl shadow-pink-100/40 overflow-hidden p-1.5">
                                <flux:menu.item href="#" icon="facebook"
                                    class="!text-zinc-600 dark:!text-zinc-400 hover:!bg-pink-50 hover:!text-pink-700 dark:hover:!bg-pink-500/10 dark:hover:!text-pink-200 transition-colors">
                                    Facebook</flux:menu.item>
                                <flux:menu.item href="#" icon="instagram"
                                    class="!text-zinc-600 dark:!text-zinc-400 hover:!bg-pink-50 hover:!text-pink-700 dark:hover:!bg-pink-500/10 dark:hover:!text-pink-200 transition-colors">
                                    Instagram</flux:menu.item>
                                <flux:menu.item href="#" icon="twitter"
                                    class="!text-zinc-600 dark:!text-zinc-400 hover:!bg-pink-50 hover:!text-pink-700 dark:hover:!bg-pink-500/10 dark:hover:!text-pink-200 transition-colors">
                                    X (Twitter)</flux:menu.item>
                                <flux:menu.item href="#" icon="line"
                                    class="!text-zinc-600 dark:!text-zinc-400 hover:!bg-pink-50 hover:!text-pink-700 dark:hover:!bg-pink-500/10 dark:hover:!text-pink-200 transition-colors">
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
                            class="bg-white dark:bg-zinc-900 rounded-xl border border-pink-100 dark:border-zinc-800 shadow-xl shadow-pink-100/40 overflow-hidden p-1.5">
                            <flux:menu.item href="{{ route('change-language', 'en') }}"
                                class="!text-zinc-600 dark:!text-zinc-400 hover:!bg-pink-50 hover:!text-pink-700 dark:hover:!bg-pink-500/10 dark:hover:!text-pink-200 transition-colors">
                                English (EN)</flux:menu.item>
                            <flux:menu.item href="{{ route('change-language', 'th') }}"
                                class="!text-zinc-600 dark:!text-zinc-400 hover:!bg-pink-50 hover:!text-pink-700 dark:hover:!bg-pink-500/10 dark:hover:!text-pink-200 transition-colors">
                                ไทย (TH)</flux:menu.item>
                        </div>
                    </div>
                </div>

                <flux:tooltip content="{{ __('Toggle appearance') }}" position="bottom">
                    <button type="button" onclick="window.toggleDarkMode()"
                        class="p-2 rounded-lg hover:bg-pink-50 hover:text-pink-700 dark:hover:bg-pink-500/10 dark:hover:text-pink-200 transition-colors focus:outline-none">
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
                                class="group/signin px-5 font-bold shadow-sm transition-all hover:scale-[1.02] active:scale-95 bg-pink-600 hover:bg-pink-700 dark:bg-pink-500 dark:hover:bg-pink-400 border-pink-600 dark:border-pink-500">
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
        <aside x-data="citationProjectsSidebar()"
            class="custom-scrollbar w-full shrink-0 overflow-hidden rounded-2xl border border-zinc-200/80 bg-white p-4 dark:border-zinc-800 dark:bg-zinc-900/50 lg:block lg:w-64 lg:rounded-none lg:border-0 lg:bg-transparent lg:p-0 lg:h-[calc(100vh-10rem)] lg:overflow-y-auto lg:pr-4 lg:visible-scrollbar">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold tracking-tight text-zinc-900 dark:text-zinc-100">โครงการ</h2>
                    <span class="rounded-full bg-zinc-100 px-2.5 py-1 text-[11px] font-medium text-zinc-500 dark:bg-zinc-800 dark:text-zinc-300" x-text="projects.length + ' รายการ'"></span>
                </div>

                <button type="button" x-on:click="openCreateProjectModal()"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-dashed border-pink-300 bg-white/90 px-4 py-3 text-sm font-semibold text-pink-700 transition hover:border-pink-500 hover:bg-pink-50 hover:text-pink-800 dark:border-pink-500/30 dark:bg-zinc-950 dark:text-pink-300 dark:hover:border-pink-500 dark:hover:bg-pink-500/10 dark:hover:text-pink-200">
                    <flux:icon name="plus" class="size-4" />
                    <span>สร้างโครงการใหม่</span>
                </button>

                <div class="space-y-3">
                    <template x-for="project in projects" :key="project.id">
                        <div class="group relative">
                            <button type="button" x-on:click="activeProject = project.id; broadcastProjects()"
                                class="w-full rounded-2xl p-3 pr-11 text-left transition"
                                x-bind:class="activeProject === project.id
                                    ? 'bg-zinc-100 text-zinc-900 dark:bg-zinc-800/80 dark:text-zinc-100'
                                    : 'text-zinc-600 hover:bg-zinc-100/80 hover:text-zinc-900 dark:text-zinc-300 dark:hover:bg-zinc-800/70 dark:hover:text-zinc-100'">
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex size-10 items-center justify-center rounded-2xl" x-bind:class="projectButtonClass(project.color)">
                                        <flux:icon x-show="project.icon === 'folder'" name="folder" class="size-4" />
                                        <flux:icon x-show="project.icon === 'book-open'" name="book-open" class="size-4" />
                                        <flux:icon x-show="project.icon === 'document-text'" name="document-text" class="size-4" />
                                        <flux:icon x-show="project.icon === 'academic-cap'" name="academic-cap" class="size-4" />
                                        <flux:icon x-show="project.icon === 'clipboard-document-list'" name="clipboard-document-list" class="size-4" />
                                        <flux:icon x-show="project.icon === 'sparkles'" name="sparkles" class="size-4" />
                                        <flux:icon x-show="project.icon === 'globe-alt'" name="globe-alt" class="size-4" />
                                        <flux:icon x-show="project.icon === 'light-bulb'" name="light-bulb" class="size-4" />
                                        <flux:icon x-show="project.icon === 'beaker'" name="beaker" class="size-4" />
                                        <flux:icon x-show="project.icon === 'briefcase'" name="briefcase" class="size-4" />
                                        <flux:icon x-show="project.icon === 'newspaper'" name="newspaper" class="size-4" />
                                        <flux:icon x-show="project.icon === 'presentation-chart-bar'" name="presentation-chart-bar" class="size-4" />
                                    </span>
                                    <h3 class="text-sm font-semibold" x-text="project.name"></h3>
                                </div>
                            </button>

                            <div class="absolute right-2 top-1/2 -translate-y-1/2">
                                <button type="button"
                                    x-on:click.stop="projectMenuOpen = projectMenuOpen === project.id ? null : project.id"
                                    class="inline-flex size-8 items-center justify-center rounded-full text-zinc-400 opacity-0 transition hover:bg-pink-100 hover:text-pink-700 group-hover:opacity-100 dark:hover:bg-pink-500/10 dark:hover:text-pink-200"
                                    x-bind:class="projectMenuOpen === project.id || activeProject === project.id ? 'opacity-100' : ''"
                                    aria-label="จัดการโครงการ">
                                    <flux:icon name="ellipsis-horizontal" class="size-4" />
                                </button>

                                <div x-cloak x-show="projectMenuOpen === project.id" x-transition.opacity x-on:click.outside="projectMenuOpen = null"
                                    class="absolute right-0 z-20 mt-2 w-44 rounded-2xl border border-pink-200/80 bg-white p-1.5 shadow-lg shadow-pink-100/50 dark:border-zinc-700 dark:bg-zinc-950 dark:shadow-none">
                                    <button type="button" x-on:click="openEditProjectModal(project)"
                                        class="flex w-full items-center gap-2 rounded-xl px-3 py-2 text-left text-sm text-zinc-600 transition hover:bg-pink-50 hover:text-pink-800 dark:text-zinc-300 dark:hover:bg-pink-500/10 dark:hover:text-pink-100">
                                        <flux:icon name="pencil-square" class="size-4" />
                                        แก้ไข
                                    </button>
                                    <button type="button" x-on:click="duplicateProject(project)"
                                        class="flex w-full items-center gap-2 rounded-xl px-3 py-2 text-left text-sm text-zinc-600 transition hover:bg-pink-50 hover:text-pink-800 dark:text-zinc-300 dark:hover:bg-pink-500/10 dark:hover:text-pink-100">
                                        <flux:icon name="square-2-stack" class="size-4" />
                                        คัดลอกโครงการ
                                    </button>
                                    <button type="button" x-on:click="openDeleteProjectModal(project)"
                                        class="flex w-full items-center gap-2 rounded-xl px-3 py-2 text-left text-sm text-rose-600 transition hover:bg-rose-50 hover:text-rose-700 dark:text-rose-400 dark:hover:bg-rose-500/10 dark:hover:text-rose-300">
                                        <flux:icon name="trash" class="size-4" />
                                        ลบ
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div x-cloak x-show="projectModal" x-transition.opacity
                class="fixed inset-0 z-[110] flex items-center justify-center bg-zinc-950/40 px-4 backdrop-blur-sm">
                <div x-show="projectModal" x-transition
                    class="w-full max-w-lg rounded-[1.75rem] border border-pink-200 bg-white p-6 shadow-2xl shadow-pink-100/60 dark:border-zinc-700 dark:bg-zinc-900 dark:shadow-none">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-1">
                            <h3 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100" x-text="projectFormMode === 'create' ? 'สร้างโครงการใหม่' : 'แก้ไขโครงการ'"></h3>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">ตั้งชื่อและเลือกสไตล์ที่เหมาะกับโครงการของคุณ</p>
                        </div>
                        <button type="button" x-on:click="closeProjectModals()"
                            class="rounded-full p-2 text-zinc-400 transition hover:bg-zinc-100 hover:text-zinc-900 dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
                            <flux:icon name="x-mark" class="size-5" />
                        </button>
                    </div>

                    <div class="mt-6 space-y-2">
                        <label class="text-sm font-medium text-zinc-700 dark:text-zinc-200">ชื่อโครงการ</label>
                        <input x-model="projectForm.name" type="text" placeholder="เช่น โครงการวิทยานิพนธ์ปี 2026"
                            class="w-full rounded-2xl border border-pink-200 bg-white px-4 py-3 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500 dark:focus:ring-pink-500/10">
                    </div>

                    <div class="mt-5 rounded-3xl border border-pink-100 bg-pink-50/60 p-4 dark:border-zinc-800 dark:bg-zinc-950/70">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100">ปรับสไตล์โครงการ</p>
                                <p class="text-xs text-zinc-500 dark:text-zinc-400">เลือกโทนสีและสัญลักษณ์ที่เข้ากับงานของคุณ</p>
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
                                        ? 'border-pink-400 bg-pink-50 text-pink-800 dark:border-pink-500 dark:bg-pink-500/10 dark:text-pink-100'
                                        : 'border-zinc-200 text-zinc-600 hover:border-pink-300 hover:text-pink-800 dark:border-zinc-700 dark:text-zinc-300 dark:hover:border-pink-500 dark:hover:text-pink-100'">
                                    <span class="size-5 rounded-full ring-2 ring-white/70 dark:ring-zinc-900/70" :class="color.swatch"></span>
                                </button>
                            </template>
                        </div>

                        <div class="mt-4 grid grid-cols-3 gap-2">
                            <template x-for="icon in projectIcons" :key="icon.value">
                                <button type="button" x-on:click="projectForm.icon = icon.value"
                                    class="flex items-center justify-center rounded-2xl border px-3 py-3 text-sm transition"
                                    x-bind:class="projectForm.icon === icon.value
                                        ? 'border-pink-400 bg-pink-50 text-pink-800 dark:border-pink-500 dark:bg-pink-500/10 dark:text-pink-100'
                                        : 'border-zinc-200 text-zinc-600 hover:border-pink-300 hover:text-pink-800 dark:border-zinc-700 dark:text-zinc-300 dark:hover:border-pink-500 dark:hover:text-pink-100'">
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
                            ยกเลิก
                        </button>
                        <button type="button"
                            x-on:click="saveProject()"
                            class="inline-flex items-center gap-2 rounded-full bg-pink-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-pink-700 dark:bg-pink-500 dark:text-white dark:hover:bg-pink-400">
                            <flux:icon x-show="projectFormMode === 'create'" name="plus" class="size-4" />
                            <flux:icon x-show="projectFormMode === 'edit'" name="check" class="size-4" />
                            <span x-text="projectFormMode === 'create' ? 'สร้างโครงการ' : 'บันทึกการแก้ไข'"></span>
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
                            <h3 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">ยืนยันการลบโครงการ</h3>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                พิมพ์ชื่อโครงการให้ตรงเพื่อยืนยันการลบอย่างถาวร
                            </p>
                        </div>
                        <button type="button" x-on:click="closeProjectModals()"
                            class="rounded-full p-2 text-zinc-400 transition hover:bg-zinc-100 hover:text-zinc-900 dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
                            <flux:icon name="x-mark" class="size-5" />
                        </button>
                    </div>

                    <div class="mt-5 rounded-2xl bg-rose-50 px-4 py-3 text-sm text-rose-700 dark:bg-rose-500/10 dark:text-rose-300">
                        ชื่อโครงการที่ต้องพิมพ์:
                        <span class="font-semibold" x-text="projectToDelete?.name || '-'"></span>
                    </div>

                    <div class="mt-5 space-y-2">
                        <label class="text-sm font-medium text-zinc-700 dark:text-zinc-200">พิมพ์ชื่อโครงการเพื่อยืนยัน</label>
                        <input x-model="deleteConfirmationName" type="text"
                            placeholder="กรอกชื่อโครงการให้ตรงกัน"
                            class="w-full rounded-2xl border border-rose-200 bg-white px-4 py-3 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-rose-400 focus:outline-none focus:ring-2 focus:ring-rose-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-rose-500 dark:focus:ring-rose-500/10">
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" x-on:click="closeProjectModals()"
                            class="px-4 py-2 text-sm font-medium text-zinc-500 transition hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100">
                            ยกเลิก
                        </button>
                        <button type="button" x-on:click="confirmDeleteProject()"
                            class="inline-flex items-center gap-2 rounded-full bg-rose-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-rose-700">
                            <flux:icon name="trash" class="size-4" />
                            ยืนยันการลบ
                        </button>
                    </div>
                </div>
            </div>
        </aside>

        <main class="min-w-0 flex-1">
            <div class="grid gap-3 lg:grid-cols-7">
                <section x-data="citationGeneratorPage()"
                    class="lg:col-span-6">
                    <div class="flex min-h-[calc(100vh-8rem)] flex-col pr-2">
                        <div class="mx-auto w-full max-w-3xl space-y-3">
                            <div class="group relative" x-on:click.outside="closeSmartSearch()">
                                <div x-cloak x-show="smartSearchOpen && (smartSearchLoading || smartSearchResults.length)" x-transition.opacity
                                    x-on:click="closeSmartSearch()"
                                    class="fixed inset-0 z-30 bg-zinc-950/12 backdrop-blur-[3px] dark:bg-zinc-950/30"></div>
                                <div class="absolute left-4 top-0 z-50 -translate-y-1/2 flex items-center gap-3 rounded-full bg-white px-3 py-1 dark:bg-zinc-900">
                                    <button type="button" x-on:click="modalOpen = true"
                                        class="inline-flex items-center gap-2 rounded-full bg-pink-600 px-3.5 py-1.5 text-xs font-semibold text-white shadow-sm shadow-pink-500/20 transition hover:bg-pink-700 hover:shadow-md hover:shadow-pink-500/25 focus:outline-none focus:ring-2 focus:ring-pink-500/20 dark:bg-pink-500 dark:hover:bg-pink-400"
                                        aria-label="เปิดฟอร์มกรอกข้อมูลเอง">
                                        <flux:icon name="plus" class="size-3.5" />
                                        <span>สร้างรายการอ้างอิง</span>
                                    </button>

                                    <button type="button" x-on:click="setQuickFilter('website')"
                                        class="relative border-b-2 pb-1 text-xs font-medium transition"
                                        x-bind:class="activeQuickFilter === 'website'
                                            ? 'border-pink-500 text-zinc-900 dark:border-pink-500 dark:text-zinc-100'
                                            : 'border-transparent text-zinc-500 hover:border-pink-400 hover:text-zinc-900 dark:border-transparent dark:text-zinc-400 dark:hover:border-pink-500 dark:hover:text-zinc-100'">
                                        เว็บไชต์
                                    </button>

                                    <button type="button" x-on:click="setQuickFilter('book')"
                                        class="relative border-b-2 pb-1 text-xs font-medium transition"
                                        x-bind:class="activeQuickFilter === 'book'
                                            ? 'border-pink-500 text-zinc-900 dark:border-pink-500 dark:text-zinc-100'
                                            : 'border-transparent text-zinc-500 hover:border-pink-400 hover:text-zinc-900 dark:border-transparent dark:text-zinc-400 dark:hover:border-pink-500 dark:hover:text-zinc-100'">
                                        หนังสือ
                                    </button>

                                    <button type="button" x-on:click="setQuickFilter('article')"
                                        class="relative border-b-2 pb-1 text-xs font-medium transition"
                                        x-bind:class="activeQuickFilter === 'article'
                                            ? 'border-pink-500 text-zinc-900 dark:border-pink-500 dark:text-zinc-100'
                                            : 'border-transparent text-zinc-500 hover:border-pink-400 hover:text-zinc-900 dark:border-transparent dark:text-zinc-400 dark:hover:border-pink-500 dark:hover:text-zinc-100'">
                                        บทความ
                                    </button>
                                </div>
                                <div
                                    class="pointer-events-none absolute inset-y-0 left-0 z-50 flex items-center pl-4 transition-colors group-focus-within:text-zinc-600 dark:group-focus-within:text-zinc-200">
                                    <span class="relative inline-flex size-8 items-center justify-center text-pink-500 dark:text-pink-300">
                                        <flux:icon name="magnifying-glass" class="size-4.5" />
                                    </span>
                                </div>
                                <input x-ref="smartSearchInput" x-model="smartQuery" type="text"
                                    x-on:input="queueSmartSearch()"
                                    x-on:focus="handleSmartSearchFocus()"
                                    x-on:keydown.escape.prevent="closeSmartSearch()"
                                    x-on:keydown.enter.prevent="openFirstSmartSearchResult()"
                                    x-bind:placeholder="smartSearchPlaceholder()"
                                    class="relative z-40 w-full rounded-2xl border border-pink-200 bg-white py-3.5 pl-16 pr-12 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500 dark:focus:ring-pink-500/10">
                                <div x-cloak x-show="smartSearchLoading"
                                    class="pointer-events-none absolute inset-y-0 right-4 z-40 flex items-center text-pink-500 dark:text-pink-300">
                                    <flux:icon name="arrow-path" class="size-4 animate-spin" />
                                </div>
                                <div class="absolute right-3 -top-1 z-40 -translate-y-1/2">
                                    <a href="{{ url('/manual') }}"
                                        class="inline-flex items-center gap-1.5 bg-white px-1 text-[11px] font-medium text-pink-400 transition hover:text-pink-600 focus:outline-none focus:ring-2 focus:ring-pink-500/20 dark:bg-zinc-900 dark:text-pink-300 dark:hover:text-pink-200"
                                        aria-label="เปิดหน้าช่วยเหลือ">
                                        <flux:icon name="question-mark-circle" class="size-3.5" />
                                        ช่วยเหลือ
                                    </a>
                                </div>

                                <div x-cloak x-show="smartSearchOpen && smartQuery.trim()" x-transition.opacity.origin.top
                                    class="absolute inset-x-0 top-full z-50 mt-3 overflow-hidden rounded-[1.5rem] border border-pink-200 bg-white/95 shadow-2xl shadow-pink-100/60 backdrop-blur dark:border-zinc-700 dark:bg-zinc-950/95 dark:shadow-none">
                                    

                                    <div class="custom-scrollbar max-h-[24rem] overflow-y-auto p-2">
                                        <template x-if="smartSearchLoading">
                                            <div class="flex items-center gap-3 rounded-2xl px-4 py-4 text-sm text-zinc-500 dark:text-zinc-400">
                                                <span class="inline-flex size-10 items-center justify-center rounded-2xl bg-pink-50 text-pink-500 dark:bg-pink-500/10 dark:text-pink-300">
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
                                                        class="flex w-full items-start gap-3 rounded-2xl border border-transparent px-3 py-3 text-left transition hover:border-pink-200 hover:bg-pink-50 dark:hover:border-pink-500/30 dark:hover:bg-pink-500/10">
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
                                                            <span class="mt-1 block text-xs font-medium text-pink-600 dark:text-pink-300" x-text="result.matchLabel"></span>
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
                                            <div class="rounded-[1.25rem] border border-dashed border-pink-200 bg-pink-50/60 px-4 py-5 text-center dark:border-zinc-700 dark:bg-zinc-900/70">
                                                <p class="text-sm font-semibold text-zinc-800 dark:text-zinc-100" x-text="smartSearchEmptyTitle()"></p>
                                                <p class="mt-1 text-xs leading-5 text-zinc-500 dark:text-zinc-400" x-text="smartSearchEmptyDescription()"></p>
                                                <button type="button" x-on:click="openManualResourceFormFromSearch()"
                                                    class="mt-4 inline-flex items-center gap-2 rounded-full border border-pink-200 bg-white px-4 py-2 text-xs font-medium text-pink-600 transition hover:border-pink-300 hover:text-pink-700 dark:border-zinc-700 dark:bg-zinc-950 dark:text-pink-300 dark:hover:border-pink-500">
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
                                        <span>รูปแบบบรรณานุกรม</span>
                                        <a href="{{ url('/manual') }}"
                                            class="text-[11px] font-medium text-pink-600 underline underline-offset-2 transition hover:text-pink-700 dark:text-pink-300 dark:hover:text-pink-200">
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
                                                class="inline-flex size-10 items-center justify-center rounded-xl border border-pink-200 bg-white transition dark:border-zinc-700 dark:bg-zinc-950"
                                                x-bind:class="displayMode === 'paper' ? 'border-pink-300 text-pink-700 ring-2 ring-pink-500/15 dark:border-pink-500/50 dark:text-pink-200 dark:ring-pink-500/20' : 'text-zinc-400 hover:border-pink-200 hover:text-pink-700 dark:text-zinc-500 dark:hover:text-pink-200'">
                                                <flux:icon name="document-text" class="size-4" />
                                            </button>
                                        </flux:tooltip>
                                        <flux:tooltip content="มุมมองแบบรายการ" position="top">
                                            <button type="button" x-on:click="displayMode = 'list'"
                                                class="inline-flex size-10 items-center justify-center rounded-xl border border-pink-200 bg-white transition dark:border-zinc-700 dark:bg-zinc-950"
                                                x-bind:class="displayMode === 'list' ? 'border-pink-300 text-pink-700 ring-2 ring-pink-500/15 dark:border-pink-500/50 dark:text-pink-200 dark:ring-pink-500/20' : 'text-zinc-400 hover:border-pink-200 hover:text-pink-700 dark:text-zinc-500 dark:hover:text-pink-200'">
                                                <flux:icon name="list-bullet" class="size-4" />
                                            </button>
                                        </flux:tooltip>
                                        <flux:tooltip content="แสดงเฉพาะ citation" position="top">
                                            <button type="button" x-on:click="displayMode = 'citation'"
                                                class="inline-flex size-10 items-center justify-center rounded-xl border border-pink-200 bg-white transition dark:border-zinc-700 dark:bg-zinc-950"
                                                x-bind:class="displayMode === 'citation' ? 'border-pink-300 text-pink-700 ring-2 ring-pink-500/15 dark:border-pink-500/50 dark:text-pink-200 dark:ring-pink-500/20' : 'text-zinc-400 hover:border-pink-200 hover:text-pink-700 dark:text-zinc-500 dark:hover:text-pink-200'">
                                                <flux:icon name="chat-bubble-bottom-center-text" class="size-4" />
                                            </button>
                                        </flux:tooltip>
                                    </div>
                                </div>

                                <div class="flex flex-col gap-2 lg:items-end">
                                    <span class="hidden h-[18px] text-xs font-medium text-transparent lg:block">actions</span>
                                    <div class="flex gap-2">
                                        <button type="button"
                                            x-on:click="copyCurrentView()"
                                            class="inline-flex h-10 items-center gap-2 rounded-xl border border-pink-200 bg-white px-3.5 text-sm font-medium text-zinc-700 transition hover:border-pink-300 hover:text-pink-700 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-200 dark:hover:border-pink-500 dark:hover:text-pink-200">
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
                                                class="inline-flex h-10 items-center gap-2 rounded-xl border border-pink-200 bg-white px-3.5 text-sm font-medium text-zinc-700 transition hover:border-pink-300 hover:text-pink-700 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-200 dark:hover:border-pink-500 dark:hover:text-pink-200">
                                                <flux:icon name="arrow-down-tray" class="size-4" />
                                                <span>ส่งออก</span>
                                            </button>

                                            <div x-cloak x-show="exportOpen" x-transition.opacity x-on:click.outside="exportOpen = false"
                                                class="absolute right-0 z-20 mt-2 min-w-36 rounded-xl border border-pink-200 bg-white p-1.5 shadow-lg shadow-pink-100/50 dark:border-zinc-700 dark:bg-zinc-950 dark:shadow-none">
                                                <button type="button" x-on:click="exportOpen = false"
                                                    class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm text-zinc-600 transition hover:bg-pink-50 hover:text-pink-800 dark:text-zinc-300 dark:hover:bg-pink-500/10 dark:hover:text-pink-100">
                                                    <flux:icon name="document-text" class="size-4" />
                                                    Word
                                                </button>
                                                <button type="button" x-on:click="window.print(); exportOpen = false"
                                                    class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm text-zinc-600 transition hover:bg-pink-50 hover:text-pink-800 dark:text-zinc-300 dark:hover:bg-pink-500/10 dark:hover:text-pink-100">
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
                            class="fixed inset-0 z-50 flex items-center justify-center bg-zinc-950/50 px-4 py-6 backdrop-blur-sm">
                            <div x-show="modalOpen" x-transition
                                class="flex max-h-[90vh] w-full max-w-6xl flex-col overflow-hidden rounded-[2rem] border border-pink-200 bg-white shadow-2xl shadow-pink-100/60 dark:border-zinc-700 dark:bg-zinc-900 dark:shadow-none">
                                <div class="flex items-start justify-between gap-4 border-b border-zinc-200 px-6 py-5 dark:border-zinc-800 lg:px-8">
                                    <div class="space-y-1">
                                        
                                        <h3 class="text-2xl font-semibold tracking-tight text-zinc-900 dark:text-zinc-100">
                                            เลือกประเภททรัพยากรที่ต้องการสร้างบรรณานุกรม
                                        </h3>
                                        <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                            เริ่มจากเลือกหมวดและชนิดทรัพยากรด้านล่าง ระบบจะพาคุณไปยังรูปแบบที่เหมาะกับการอ้างอิงนั้นทันที
                                        </p>
                                    </div>
                                    <button type="button" x-on:click="modalOpen = false"
                                        class="rounded-full p-2 text-zinc-400 transition hover:bg-zinc-100 hover:text-zinc-900 dark:hover:bg-zinc-800 dark:hover:text-zinc-100"
                                        aria-label="ปิดหน้าต่าง">
                                        <flux:icon name="x-mark" class="size-5" />
                                    </button>
                                </div>

                                <div class="border-b border-zinc-200 px-6 py-4 dark:border-zinc-800 lg:px-8">
                                    <div class="relative">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-zinc-400">
                                            <flux:icon name="magnifying-glass" class="size-5" />
                                        </div>
                                        <input x-model.debounce.150ms="modalSearch" type="text"
                                            placeholder="ค้นหาประเภททรัพยากร เช่น หนังสืออิเล็กทรอนิกส์, วิทยานิพนธ์, เว็บเพจ"
                                            class="w-full rounded-2xl border border-pink-200 bg-pink-50/50 py-3 pl-12 pr-4 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500 dark:focus:ring-pink-500/10">
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
                                            <div class="rounded-3xl border border-pink-100 bg-gradient-to-br from-pink-50/90 via-white to-rose-50/70 p-5 dark:border-zinc-800 dark:bg-zinc-950/50"
                                                x-show="@js(($group['label'].' '.implode(' ', $group['items']))).toLowerCase().includes(modalSearch.toLowerCase())"
                                                x-transition.opacity.duration.150ms>
                                                <div class="mb-4 flex items-center gap-3">
                                                    <span class="inline-flex size-10 items-center justify-center rounded-2xl ring-1 {{ $modalAccentClasses }}">
                                                        <flux:icon :name="$group['icon']" class="size-5" />
                                                    </span>
                                                    <div>
                                                        <h4 class="text-base font-semibold {{ $modalHeadingClasses }}">{{ $group['label'] }}</h4>
                                                        <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ count($group['items']) }} รูปแบบอ้างอิง</p>
                                                    </div>
                                                </div>

                                                <div class="space-y-1.5">
                                                    @foreach ($group['items'] as $item)
                                                        <button type="button"
                                                            x-show="@js($item).toLowerCase().includes(modalSearch.toLowerCase()) || @js($group['label']).toLowerCase().includes(modalSearch.toLowerCase())"
                                                            x-on:click="openFormModal(@js($item))"
                                                            class="flex w-full items-start justify-between gap-3 rounded-2xl border border-transparent bg-white px-4 py-3 text-left text-sm text-zinc-700 transition hover:border-pink-200 hover:bg-pink-50 hover:text-pink-800 dark:bg-zinc-900 dark:text-zinc-200 dark:hover:border-pink-500/30 dark:hover:bg-pink-500/10 dark:hover:text-pink-100">
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
                                        หากยังไม่แน่ใจประเภทที่ต้องใช้ สามารถดูรายละเอียดการอ้างอิงเพิ่มเติมได้ในคู่มือ
                                    </p>
                                    <div class="flex items-center gap-3">
                                        <a href="{{ url('/manual') }}"
                                            class="text-sm font-medium text-zinc-900 underline underline-offset-2 transition hover:text-pink-500 dark:text-zinc-100 dark:hover:text-pink-400">
                                            เปิดคู่มือ
                                        </a>
                                        <button type="button" x-on:click="modalOpen = false"
                                            class="inline-flex items-center rounded-full border border-zinc-200 px-4 py-2 text-sm font-medium text-zinc-600 transition hover:border-zinc-300 hover:text-zinc-900 dark:border-zinc-700 dark:text-zinc-300 dark:hover:border-zinc-500 dark:hover:text-zinc-100">
                                            ปิด
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Citation Form Modal --}}
                        @include('partials.citation-form-modal')

                        <div x-cloak x-show="detailModalOpen" x-transition.opacity
                            class="fixed inset-0 z-[70] flex items-center justify-center bg-zinc-950/50 px-4 py-6 backdrop-blur-sm">
                            <div x-show="detailModalOpen" x-transition
                                class="w-full max-w-5xl rounded-[2rem] border border-pink-200 bg-white p-6 shadow-2xl shadow-pink-100/60 dark:border-zinc-700 dark:bg-zinc-900 dark:shadow-none">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="space-y-1">
                                        <h3 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">รายละเอียดรายการบรรณานุกรม</h3>
                                        <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                            โครงการ:
                                            <span class="font-medium text-pink-600 dark:text-pink-300" x-text="activeEntry ? projectNameById(entryProjectId(activeEntry)) : '-' "></span>
                                            <span class="mx-2 text-zinc-300 dark:text-zinc-600">•</span>
                                            ประเภท:
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
                                        <div class="rounded-3xl border border-pink-200 bg-pink-50/50 p-5 dark:border-zinc-700 dark:bg-zinc-950">
                                            <p class="mb-2 text-xs font-semibold uppercase tracking-[0.2em] text-pink-500 dark:text-pink-300">Bibliography</p>
                                            <div class="paper-bibliography-font text-[16px] leading-[2] text-zinc-700 dark:text-zinc-300">
                                                <p class="thai-distributed" style="padding-left: 0.5in; text-indent: -0.5in; line-height: 2;" x-html="entryPaperPreview(activeEntry)"></p>
                                            </div>
                                        </div>

                                        <div class="grid gap-4 lg:grid-cols-2">
                                            <div class="rounded-3xl border border-violet-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-950">
                                                <p class="mb-2 text-xs font-semibold uppercase tracking-[0.2em] text-violet-500 dark:text-violet-300">Narrative Citation</p>
                                                <p class="text-sm leading-7 text-zinc-700 dark:text-zinc-300" x-text="entryNarrativeCitation(activeEntry)"></p>
                                            </div>
                                            <div class="rounded-3xl border border-violet-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-950">
                                                <p class="mb-2 text-xs font-semibold uppercase tracking-[0.2em] text-violet-500 dark:text-violet-300">Parenthetical Citation</p>
                                                <p class="text-sm leading-7 text-zinc-700 dark:text-zinc-300" x-text="entryParentheticalCitation(activeEntry)"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="rounded-3xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-950">
                                        <p class="mb-4 text-xs font-semibold uppercase tracking-[0.2em] text-zinc-500 dark:text-zinc-400">Field Details</p>
                                        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-1">
                                            <template x-for="(field, index) in entryDetailFields(activeEntry)" :key="'field-' + index">
                                                <div class="rounded-2xl border border-zinc-200 bg-zinc-50 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-900">
                                                    <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-zinc-500 dark:text-zinc-400" x-text="field.label"></p>
                                                    <p class="mt-1 text-sm leading-6 text-zinc-800 dark:text-zinc-100" x-text="field.value"></p>
                                                </div>
                                            </template>
                                        </div>
                                        <p x-show="entryDetailFields(activeEntry).length === 0" class="text-sm italic text-zinc-400 dark:text-zinc-500">
                                            ยังไม่มีข้อมูลฟิลด์เพิ่มเติมสำหรับรายการนี้
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end gap-3">
                                    <button type="button" x-on:click="closeEntryModals()"
                                        class="px-4 py-2 text-sm font-medium text-zinc-500 transition hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100">
                                        ปิด
                                    </button>
                                    <button type="button" x-on:click="copyEntry(activeEntry)"
                                        class="inline-flex items-center gap-2 rounded-full bg-pink-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-pink-700 dark:bg-pink-500 dark:hover:bg-pink-400">
                                        <flux:icon x-show="copiedEntryId !== activeEntry?.id" name="clipboard-document" class="size-4" />
                                        <flux:icon x-show="copiedEntryId === activeEntry?.id" name="check" class="size-4" />
                                        <span x-text="copiedEntryId === activeEntry?.id ? 'คัดลอกแล้ว' : 'คัดลอกรายการ'"></span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div x-cloak x-show="editModalOpen" x-transition.opacity
                            class="fixed inset-0 z-[70] flex items-center justify-center bg-zinc-950/50 px-4 py-6 backdrop-blur-sm">
                            <div x-show="editModalOpen" x-transition
                                class="w-full max-w-5xl rounded-[2rem] border border-pink-200 bg-white p-6 shadow-2xl shadow-pink-100/60 dark:border-zinc-700 dark:bg-zinc-900 dark:shadow-none">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="space-y-1">
                                        <h3 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">แก้ไขรายการบรรณานุกรม</h3>
                                        <p class="text-sm text-zinc-500 dark:text-zinc-400">แก้ไขบรรณานุกรม, citation และข้อมูลรายฟิลด์ของรายการนี้</p>
                                    </div>
                                    <button type="button" x-on:click="closeEntryModals()"
                                        class="rounded-full p-2 text-zinc-400 transition hover:bg-zinc-100 hover:text-zinc-900 dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
                                        <flux:icon name="x-mark" class="size-5" />
                                    </button>
                                </div>

                                <div class="mt-6 grid gap-5 xl:grid-cols-[minmax(0,1fr)_minmax(0,1fr)]">
                                    <div class="space-y-5">
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium text-zinc-700 dark:text-zinc-200">ประเภททรัพยากร</label>
                                            <input x-model="editEntryDraft.resourceType" type="text"
                                                class="w-full rounded-2xl border border-pink-200 bg-white px-4 py-3 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                        </div>

                                        <div class="space-y-2">
                                            <label class="text-sm font-medium text-zinc-700 dark:text-zinc-200">บรรณานุกรม</label>
                                            <textarea x-model="editEntryDraft.text" rows="7"
                                                class="w-full rounded-3xl border border-pink-200 bg-white px-4 py-4 text-sm leading-7 text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500"></textarea>
                                        </div>

                                        <div class="grid gap-4 lg:grid-cols-2">
                                            <div class="space-y-2">
                                                <label class="text-sm font-medium text-zinc-700 dark:text-zinc-200">Narrative Citation</label>
                                                <textarea x-model="editEntryDraft.narrativeCitation" rows="4"
                                                    class="w-full rounded-3xl border border-violet-200 bg-white px-4 py-4 text-sm leading-7 text-zinc-700 placeholder:text-zinc-400 focus:border-violet-400 focus:outline-none focus:ring-2 focus:ring-violet-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-violet-500"></textarea>
                                            </div>
                                            <div class="space-y-2">
                                                <label class="text-sm font-medium text-zinc-700 dark:text-zinc-200">Parenthetical Citation</label>
                                                <textarea x-model="editEntryDraft.parentheticalCitation" rows="4"
                                                    class="w-full rounded-3xl border border-violet-200 bg-white px-4 py-4 text-sm leading-7 text-zinc-700 placeholder:text-zinc-400 focus:border-violet-400 focus:outline-none focus:ring-2 focus:ring-violet-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-violet-500"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-4 rounded-3xl border border-zinc-200 bg-zinc-50 p-5 dark:border-zinc-700 dark:bg-zinc-950">
                                        <div class="flex items-center justify-between gap-3">
                                            <div>
                                                <h4 class="text-sm font-semibold text-zinc-800 dark:text-zinc-100">ฟิลด์ข้อมูล</h4>
                                                <p class="text-xs text-zinc-500 dark:text-zinc-400">แก้ไขหรือเพิ่มข้อมูลย่อยของรายการให้แสดงใน modal รายละเอียด</p>
                                            </div>
                                            <button type="button" x-on:click="addEditField()"
                                                class="inline-flex items-center gap-1.5 rounded-full border border-pink-200 bg-white px-3 py-1.5 text-xs font-medium text-pink-600 transition hover:border-pink-300 hover:text-pink-700 dark:border-zinc-700 dark:bg-zinc-900 dark:text-pink-300 dark:hover:border-pink-500">
                                                <flux:icon name="plus" class="size-3.5" />
                                                เพิ่มฟิลด์
                                            </button>
                                        </div>

                                        <div class="custom-scrollbar max-h-[26rem] space-y-3 overflow-y-auto pr-1">
                                            <template x-for="(field, index) in editEntryDraft.detailFields" :key="'edit-field-' + index">
                                                <div class="rounded-2xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
                                                    <div class="grid gap-3 sm:grid-cols-[minmax(0,0.8fr)_minmax(0,1.2fr)_auto] sm:items-start">
                                                        <div>
                                                            <label class="mb-1 block text-xs font-medium text-zinc-500 dark:text-zinc-400">ชื่อฟิลด์</label>
                                                            <input x-model="field.label" type="text"
                                                                class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                                        </div>
                                                        <div>
                                                            <label class="mb-1 block text-xs font-medium text-zinc-500 dark:text-zinc-400">ค่า</label>
                                                            <textarea x-model="field.value" rows="2"
                                                                class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500"></textarea>
                                                        </div>
                                                        <button type="button" x-on:click="removeEditField(index)"
                                                            class="mt-6 inline-flex size-9 items-center justify-center rounded-xl text-zinc-400 transition hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-500/10 dark:hover:text-rose-400"
                                                            aria-label="ลบฟิลด์">
                                                            <flux:icon name="trash" class="size-4" />
                                                        </button>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                        <p x-show="editEntryDraft.detailFields.length === 0" class="text-sm italic text-zinc-400 dark:text-zinc-500">
                                            ยังไม่มีฟิลด์ข้อมูล สามารถกดเพิ่มฟิลด์ได้
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end gap-3">
                                    <button type="button" x-on:click="closeEntryModals()"
                                        class="px-4 py-2 text-sm font-medium text-zinc-500 transition hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100">
                                        ยกเลิก
                                    </button>
                                    <button type="button" x-on:click="saveEntryEdit()"
                                        class="inline-flex items-center gap-2 rounded-full bg-pink-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-pink-700 dark:bg-pink-500 dark:hover:bg-pink-400">
                                        <flux:icon name="check" class="size-4" />
                                        บันทึกการแก้ไข
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div x-cloak x-show="moveModalOpen" x-transition.opacity
                            class="fixed inset-0 z-[70] flex items-center justify-center bg-zinc-950/50 px-4 py-6 backdrop-blur-sm">
                            <div x-show="moveModalOpen" x-transition
                                class="w-full max-w-2xl rounded-[2rem] border border-pink-200 bg-white p-6 shadow-2xl shadow-pink-100/60 dark:border-zinc-700 dark:bg-zinc-900 dark:shadow-none">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="space-y-1">
                                        <h3 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">ย้ายรายการไปโครงการอื่น</h3>
                                        <p class="text-sm text-zinc-500 dark:text-zinc-400">เลือกโครงการปลายทางสำหรับรายการที่เลือก</p>
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
                                                ? 'border-pink-400 bg-pink-50 dark:border-pink-500 dark:bg-pink-500/10'
                                                : 'border-zinc-200 hover:border-pink-300 dark:border-zinc-700 dark:hover:border-pink-500'">
                                            <div class="flex items-center gap-3">
                                                <input type="radio" name="move-project" x-model="moveTargetProjectId" x-bind:value="project.id"
                                                    class="border-zinc-300 text-pink-600 focus:ring-pink-500">
                                                <div>
                                                    <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100" x-text="project.name"></p>
                                                    <p class="text-xs text-zinc-500 dark:text-zinc-400" x-text="entryProjectId(activeEntry) === project.id ? 'โครงการปัจจุบัน' : 'โครงการปลายทาง'"></p>
                                                </div>
                                            </div>
                                            <flux:icon x-show="moveTargetProjectId === project.id" name="check-circle" class="size-5 text-pink-500" />
                                        </label>
                                    </template>
                                </div>

                                <div class="mt-6 flex justify-end gap-3">
                                    <button type="button" x-on:click="closeEntryModals()"
                                        class="px-4 py-2 text-sm font-medium text-zinc-500 transition hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100">
                                        ยกเลิก
                                    </button>
                                    <button type="button" x-on:click="confirmMoveEntry()"
                                        class="inline-flex items-center gap-2 rounded-full bg-pink-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-pink-700 dark:bg-pink-500 dark:hover:bg-pink-400">
                                        <flux:icon name="folder-open" class="size-4" />
                                        ย้ายรายการ
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div x-cloak x-show="deleteEntryModalOpen" x-transition.opacity
                            class="fixed inset-0 z-[70] flex items-center justify-center bg-zinc-950/50 px-4 py-6 backdrop-blur-sm">
                            <div x-show="deleteEntryModalOpen" x-transition
                                class="w-full max-w-2xl rounded-[2rem] border border-rose-200 bg-white p-6 shadow-2xl shadow-rose-100/60 dark:border-zinc-700 dark:bg-zinc-900 dark:shadow-none">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="space-y-1">
                                        <h3 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">ลบรายการบรรณานุกรม</h3>
                                        <p class="text-sm text-zinc-500 dark:text-zinc-400">ยืนยันการลบรายการนี้ออกจากโครงการปัจจุบัน</p>
                                    </div>
                                    <button type="button" x-on:click="closeEntryModals()"
                                        class="rounded-full p-2 text-zinc-400 transition hover:bg-zinc-100 hover:text-zinc-900 dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
                                        <flux:icon name="x-mark" class="size-5" />
                                    </button>
                                </div>

                                <div class="mt-5 rounded-3xl border border-rose-200 bg-rose-50/80 p-4 dark:border-rose-500/20 dark:bg-rose-500/10">
                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-rose-500 dark:text-rose-300">รายการที่จะถูกลบ</p>
                                    <p class="mt-2 text-sm leading-7 text-zinc-700 dark:text-zinc-200" x-text="activeEntry?.text || '-' "></p>
                                </div>

                                <div class="mt-6 flex justify-end gap-3">
                                    <button type="button" x-on:click="closeEntryModals()"
                                        class="px-4 py-2 text-sm font-medium text-zinc-500 transition hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100">
                                        ยกเลิก
                                    </button>
                                    <button type="button" x-on:click="confirmDeleteEntry()"
                                        class="inline-flex items-center gap-2 rounded-full bg-rose-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-rose-700 dark:bg-rose-500 dark:hover:bg-rose-400">
                                        <flux:icon name="trash" class="size-4" />
                                        ลบรายการ
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
                                            <h3 class="paper-bibliography-font text-[18px] font-semibold tracking-tight">บรรณานุกรม</h3>
                                        </div>
                                        <div class="paper-bibliography-font space-y-0 text-[16px] leading-[2] text-zinc-700 dark:text-zinc-300">
                                            <template x-for="entry in filteredCitations()" :key="'paper-' + entry.id">
                                                <div x-bind:data-entry-id="entry.id" class="group relative rounded-2xl px-3 py-1.5 transition hover:bg-pink-50 hover:ring-1 hover:ring-pink-200 dark:hover:bg-pink-500/10 dark:hover:ring-pink-500/20"
                                                    x-bind:class="entryCardClasses(entry, 'paper')">
                                                    <p class="thai-distributed" style="padding-left: 0.5in; text-indent: -0.5in; line-height: 2;" x-html="entry.paperHtml || entry.text"></p>
                                                    <div class="absolute right-3 top-3 hidden items-center gap-2 rounded-full bg-white/95 px-2 py-1.5 shadow-sm ring-1 ring-pink-200 group-hover:flex dark:bg-zinc-950/95 dark:ring-pink-500/20">
                                                        <flux:tooltip content="ดู">
                                                            <button type="button" x-on:click="viewEntry(entry)"
                                                                class="inline-flex size-8 items-center justify-center rounded-full text-zinc-500 transition hover:bg-pink-100 hover:text-pink-700 dark:text-zinc-400 dark:hover:bg-pink-500/10 dark:hover:text-pink-200"
                                                                aria-label="ดู">
                                                                <flux:icon name="eye" class="size-4" />
                                                            </button>
                                                        </flux:tooltip>
                                                        <flux:tooltip content="แก้ไข">
                                                            <button type="button" x-on:click="editEntry(entry)"
                                                                class="inline-flex size-8 items-center justify-center rounded-full text-zinc-500 transition hover:bg-pink-100 hover:text-pink-700 dark:text-zinc-400 dark:hover:bg-pink-500/10 dark:hover:text-pink-200"
                                                                aria-label="แก้ไข">
                                                                <flux:icon name="pencil-square" class="size-4" />
                                                            </button>
                                                        </flux:tooltip>
                                                        <flux:tooltip content="คัดลอก">
                                                            <button type="button" x-on:click="copyEntry(entry)"
                                                                class="inline-flex size-8 items-center justify-center rounded-full text-zinc-500 transition hover:bg-pink-100 hover:text-pink-700 dark:text-zinc-400 dark:hover:bg-pink-500/10 dark:hover:text-pink-200"
                                                                aria-label="คัดลอก">
                                                                <flux:icon x-show="copiedEntryId !== entry.id" name="clipboard-document" class="size-4" />
                                                                <flux:icon x-show="copiedEntryId === entry.id" name="check" class="size-4 text-emerald-500" />
                                                            </button>
                                                        </flux:tooltip>
                                                        <flux:tooltip content="ย้ายโปรเจค">
                                                            <button type="button" x-on:click="moveEntry(entry)"
                                                                class="inline-flex size-8 items-center justify-center rounded-full text-zinc-500 transition hover:bg-pink-100 hover:text-pink-700 dark:text-zinc-400 dark:hover:bg-pink-500/10 dark:hover:text-pink-200"
                                                                aria-label="ย้ายโปรเจค">
                                                                <flux:icon name="folder-open" class="size-4" />
                                                            </button>
                                                        </flux:tooltip>
                                                        <flux:tooltip content="ลบ">
                                                            <button type="button" x-on:click="requestDeleteEntry(entry)"
                                                                class="inline-flex size-8 items-center justify-center rounded-full text-zinc-500 transition hover:bg-rose-100 hover:text-rose-700 dark:text-zinc-400 dark:hover:bg-rose-500/10 dark:hover:text-rose-300"
                                                                aria-label="ลบรายการ">
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
                                            <li x-bind:data-entry-id="entry.id" class="group flex items-start justify-between gap-4 rounded-2xl border border-zinc-200 bg-white/95 px-4 py-3.5 shadow-sm shadow-zinc-200/50 transition hover:border-pink-300 hover:bg-pink-50 hover:shadow-pink-100/70 dark:border-zinc-700 dark:bg-zinc-950/90 dark:shadow-none dark:hover:border-pink-500/40 dark:hover:bg-pink-500/10"
                                                x-bind:class="entryCardClasses(entry, 'list')">
                                                <span class="min-w-0 flex-1 leading-8" x-text="entry.text"></span>
                                                <div class="hidden shrink-0 items-center gap-1.5 group-hover:flex">
                                                    <flux:tooltip content="ดู">
                                                        <button type="button" x-on:click="viewEntry(entry)"
                                                            class="inline-flex size-8 items-center justify-center rounded-full text-zinc-500 transition hover:bg-pink-100 hover:text-pink-700 dark:text-zinc-400 dark:hover:bg-pink-500/10 dark:hover:text-pink-200"
                                                            aria-label="ดู">
                                                            <flux:icon name="eye" class="size-4" />
                                                        </button>
                                                    </flux:tooltip>
                                                    <flux:tooltip content="แก้ไข">
                                                        <button type="button" x-on:click="editEntry(entry)"
                                                            class="inline-flex size-8 items-center justify-center rounded-full text-zinc-500 transition hover:bg-pink-100 hover:text-pink-700 dark:text-zinc-400 dark:hover:bg-pink-500/10 dark:hover:text-pink-200"
                                                            aria-label="แก้ไข">
                                                            <flux:icon name="pencil-square" class="size-4" />
                                                        </button>
                                                    </flux:tooltip>
                                                    <flux:tooltip content="คัดลอก">
                                                        <button type="button" x-on:click="copyEntry(entry)"
                                                            class="inline-flex size-8 items-center justify-center rounded-full text-zinc-500 transition hover:bg-pink-100 hover:text-pink-700 dark:text-zinc-400 dark:hover:bg-pink-500/10 dark:hover:text-pink-200"
                                                            aria-label="คัดลอก">
                                                            <flux:icon x-show="copiedEntryId !== entry.id" name="clipboard-document" class="size-4" />
                                                            <flux:icon x-show="copiedEntryId === entry.id" name="check" class="size-4 text-emerald-500" />
                                                        </button>
                                                    </flux:tooltip>
                                                    <flux:tooltip content="ย้ายโปรเจค">
                                                        <button type="button" x-on:click="moveEntry(entry)"
                                                            class="inline-flex size-8 items-center justify-center rounded-full text-zinc-500 transition hover:bg-pink-100 hover:text-pink-700 dark:text-zinc-400 dark:hover:bg-pink-500/10 dark:hover:text-pink-200"
                                                            aria-label="ย้ายโปรเจค">
                                                            <flux:icon name="folder-open" class="size-4" />
                                                        </button>
                                                    </flux:tooltip>
                                                    <flux:tooltip content="ลบ">
                                                        <button type="button" x-on:click="requestDeleteEntry(entry)"
                                                            class="inline-flex size-8 items-center justify-center rounded-full text-zinc-500 transition hover:bg-rose-100 hover:text-rose-700 dark:text-zinc-400 dark:hover:bg-rose-500/10 dark:hover:text-rose-300"
                                                            aria-label="ลบรายการ">
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
                                            <li x-bind:data-entry-id="entry.id" class="group flex items-start justify-between gap-4 rounded-2xl border border-zinc-200 bg-white/95 px-4 py-3.5 shadow-sm shadow-zinc-200/50 transition hover:border-pink-300 hover:bg-pink-50 hover:shadow-pink-100/70 dark:border-zinc-700 dark:bg-zinc-950/90 dark:shadow-none dark:hover:border-pink-500/40 dark:hover:bg-pink-500/10"
                                                x-bind:class="entryCardClasses(entry, 'citation')">
                                                <span class="min-w-0 flex-1 leading-8" x-text="entryNarrativePreview(entry)"></span>
                                                <div class="hidden shrink-0 items-center gap-1.5 group-hover:flex">
                                                    <flux:tooltip content="ดู">
                                                        <button type="button" x-on:click="viewEntry(entry)"
                                                            class="inline-flex size-8 items-center justify-center rounded-full text-zinc-500 transition hover:bg-pink-100 hover:text-pink-700 dark:text-zinc-400 dark:hover:bg-pink-500/10 dark:hover:text-pink-200"
                                                            aria-label="ดู">
                                                            <flux:icon name="eye" class="size-4" />
                                                        </button>
                                                    </flux:tooltip>
                                                    <flux:tooltip content="แก้ไข">
                                                        <button type="button" x-on:click="editEntry(entry)"
                                                            class="inline-flex size-8 items-center justify-center rounded-full text-zinc-500 transition hover:bg-pink-100 hover:text-pink-700 dark:text-zinc-400 dark:hover:bg-pink-500/10 dark:hover:text-pink-200"
                                                            aria-label="แก้ไข">
                                                            <flux:icon name="pencil-square" class="size-4" />
                                                        </button>
                                                    </flux:tooltip>
                                                    <flux:tooltip content="คัดลอก">
                                                        <button type="button" x-on:click="copyEntry(entry)"
                                                            class="inline-flex size-8 items-center justify-center rounded-full text-zinc-500 transition hover:bg-pink-100 hover:text-pink-700 dark:text-zinc-400 dark:hover:bg-pink-500/10 dark:hover:text-pink-200"
                                                            aria-label="คัดลอก">
                                                            <flux:icon x-show="copiedEntryId !== entry.id" name="clipboard-document" class="size-4" />
                                                            <flux:icon x-show="copiedEntryId === entry.id" name="check" class="size-4 text-emerald-500" />
                                                        </button>
                                                    </flux:tooltip>
                                                    <flux:tooltip content="ย้ายโปรเจค">
                                                        <button type="button" x-on:click="moveEntry(entry)"
                                                            class="inline-flex size-8 items-center justify-center rounded-full text-zinc-500 transition hover:bg-pink-100 hover:text-pink-700 dark:text-zinc-400 dark:hover:bg-pink-500/10 dark:hover:text-pink-200"
                                                            aria-label="ย้ายโปรเจค">
                                                            <flux:icon name="folder-open" class="size-4" />
                                                        </button>
                                                    </flux:tooltip>
                                                    <flux:tooltip content="ลบ">
                                                        <button type="button" x-on:click="requestDeleteEntry(entry)"
                                                            class="inline-flex size-8 items-center justify-center rounded-full text-zinc-500 transition hover:bg-rose-100 hover:text-rose-700 dark:text-zinc-400 dark:hover:bg-rose-500/10 dark:hover:text-rose-300"
                                                            aria-label="ลบรายการ">
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
                    { value: 'zinc', label: 'เทา', swatch: 'bg-zinc-500', button: 'bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-900' },
                    { value: 'sky', label: 'ฟ้า', swatch: 'bg-sky-500', button: 'bg-sky-500 text-white' },
                    { value: 'emerald', label: 'เขียว', swatch: 'bg-emerald-500', button: 'bg-emerald-500 text-white' },
                    { value: 'amber', label: 'ทอง', swatch: 'bg-amber-500', button: 'bg-amber-500 text-white' },
                    { value: 'rose', label: 'ชมพู', swatch: 'bg-rose-500', button: 'bg-rose-500 text-white' },
                    { value: 'violet', label: 'ม่วง', swatch: 'bg-violet-500', button: 'bg-violet-500 text-white' },
                    { value: 'indigo', label: 'กรม', swatch: 'bg-indigo-500', button: 'bg-indigo-500 text-white' },
                    { value: 'cyan', label: 'ฟ้าน้ำทะเล', swatch: 'bg-cyan-500', button: 'bg-cyan-500 text-white' },
                    { value: 'teal', label: 'ทีล', swatch: 'bg-teal-500', button: 'bg-teal-500 text-white' },
                    { value: 'orange', label: 'ส้ม', swatch: 'bg-orange-500', button: 'bg-orange-500 text-white' },
                    { value: 'fuchsia', label: 'บานเย็น', swatch: 'bg-fuchsia-500', button: 'bg-fuchsia-500 text-white' },
                    { value: 'lime', label: 'ไลม์', swatch: 'bg-lime-500', button: 'bg-lime-500 text-zinc-950' },
                ],
                projectIcons: [
                    { value: 'folder', label: 'โฟลเดอร์' },
                    { value: 'book-open', label: 'หนังสือ' },
                    { value: 'document-text', label: 'เอกสาร' },
                    { value: 'academic-cap', label: 'วิจัย' },
                    { value: 'clipboard-document-list', label: 'รายการ' },
                    { value: 'sparkles', label: 'พิเศษ' },
                    { value: 'globe-alt', label: 'เว็บ' },
                    { value: 'light-bulb', label: 'ไอเดีย' },
                    { value: 'beaker', label: 'ทดลอง' },
                    { value: 'briefcase', label: 'งาน' },
                    { value: 'newspaper', label: 'ข่าวสาร' },
                    { value: 'presentation-chart-bar', label: 'พรีเซนต์' },
                ],
                projects: [
                    { id: 1, name: 'โครงการวิจัยบทที่ 1', color: 'zinc', icon: 'folder' },
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
                        this.toast('ต้องมีอย่างน้อย 1 โครงการในระบบ', 'warning');
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
                        this.toast('กรุณากรอกชื่อโครงการก่อนบันทึก', 'warning');
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
                        this.toast('สร้างโครงการใหม่เรียบร้อยแล้ว', 'success');
                        return;
                    }

                    const project = this.projects.find(item => item.id === this.editingProjectId);
                    if (!project) {
                        this.toast('ไม่พบโครงการที่ต้องการแก้ไข', 'danger');
                        return;
                    }

                    project.name = this.projectForm.name.trim();
                    project.color = this.projectForm.color;
                    project.icon = this.projectForm.icon;
                    this.closeProjectModals();
                    this.broadcastProjects();
                    this.toast('อัปเดตโครงการเรียบร้อยแล้ว', 'success');
                },
                duplicateProject(project) {
                    const nextId = Date.now();
                    this.projects.unshift({
                        id: nextId,
                        name: project.name + ' (คัดลอก)',
                        color: project.color,
                        icon: project.icon,
                    });
                    this.activeProject = nextId;
                    this.projectMenuOpen = null;
                    this.broadcastProjects();
                    this.toast('คัดลอกโครงการเรียบร้อยแล้ว', 'success');
                },
                confirmDeleteProject() {
                    if (!this.projectToDelete) {
                        this.toast('ไม่พบโครงการที่ต้องการลบ', 'danger');
                        return;
                    }

                    if (this.deleteConfirmationName.trim() !== this.projectToDelete.name) {
                        this.toast('ชื่อโครงการไม่ตรงกัน กรุณาตรวจสอบอีกครั้ง', 'warning');
                        return;
                    }

                    const index = this.projects.findIndex(item => item.id === this.projectToDelete.id);
                    if (index === -1) {
                        this.toast('ไม่พบโครงการที่ต้องการลบ', 'danger');
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
                    this.toast('ลบโครงการ ' + deletedProjectName + ' เรียบร้อยแล้ว', 'danger');
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
                        resourceType: 'หนังสืออิเล็กทรอนิกส์ (มี DOI)',
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
                        formatLabel: 'หนังสือพร้อม DOI',
                        keywords: ['ก', 'บรรณานุกรม', 'งานวิจัย', 'apa'],
                    },
                    {
                        id: 'mock-book-2',
                        category: 'book',
                        resourceType: 'หนังสือ',
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
                        formatLabel: 'หนังสือ',
                        keywords: ['ก', 'ค้นคว้า', 'สืบค้น', 'อ้างอิง'],
                    },
                    {
                        id: 'mock-book-3',
                        category: 'article',
                        resourceType: 'บทความวารสารอิเล็กทรอนิกส์ (มี DOI)',
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
                        formatLabel: 'บทความ',
                        keywords: ['บทความ', 'doi', 'วารสาร', 'apa 7'],
                    },
                    {
                        id: 'mock-book-4',
                        category: 'website',
                        resourceType: 'เอกสารอิเล็กทรอนิกส์ (เว็บเพจ)',
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
                        formatLabel: 'เว็บไซต์',
                        keywords: ['เว็บไซต์', 'url', 'เว็บ', 'แหล่งข้อมูลออนไลน์'],
                    },
                    {
                        id: 'mock-book-5',
                        category: 'article',
                        resourceType: 'บทความวารสาร',
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
                        formatLabel: 'บทความวิจัย',
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
                    { id: 1, name: 'โครงการวิจัยบทที่ 1', color: 'zinc', icon: 'folder' },
                ],
                activeProjectId: 1,
                moveTargetProjectId: null,
                form: {
                    authors: [{ lastName: '', firstName: '' }],
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
                            { label: 'ประเภททรัพยากร', value: 'บทความวารสาร' },
                            { label: 'ผู้แต่ง', value: 'ธนกร พิพัฒน์' },
                            { label: 'ปีที่พิมพ์', value: '2566ก' },
                            { label: 'ชื่อบทความ', value: 'แนวปฏิบัติการอ้างอิงแหล่งข้อมูลดิจิทัลตามรูปแบบ APA 7th edition' },
                            { label: 'ชื่อวารสาร', value: 'วารสารสารสนเทศศึกษา' },
                            { label: 'Volume / Issue', value: '21(1)' },
                            { label: 'หน้า', value: '11-29' },
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
                            { label: 'ประเภททรัพยากร', value: 'หนังสือ' },
                            { label: 'ผู้แต่ง', value: 'ธนกร พิพัฒน์' },
                            { label: 'ปีที่พิมพ์', value: '2566ข' },
                            { label: 'ชื่อเรื่อง', value: 'การพัฒนาทักษะการเขียนบรรณานุกรมของนักศึกษาระดับอุดมศึกษา' },
                            { label: 'สำนักพิมพ์', value: 'สำนักพิมพ์จุฬาลงกรณ์มหาวิทยาลัย' },
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
                            { label: 'ประเภททรัพยากร', value: 'บทความวารสาร' },
                            { label: 'ผู้แต่ง', value: 'ปาริชาติ ศรีอรุณ' },
                            { label: 'ปีที่พิมพ์', value: '2567' },
                            { label: 'ชื่อบทความ', value: 'การใช้เครื่องมือดิจิทัลเพื่อสนับสนุนการเขียนอ้างอิงในงานวิจัย' },
                            { label: 'ชื่อวารสาร', value: 'วารสารวิชาการครุศาสตร์' },
                            { label: 'Volume / Issue', value: '9(3)' },
                            { label: 'หน้า', value: '101-119' },
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
                            { label: 'ประเภททรัพยากร', value: 'บทความวารสาร' },
                            { label: 'ผู้แต่ง', value: 'Adams, R. T.' },
                            { label: 'ปีที่พิมพ์', value: '2023a' },
                            { label: 'ชื่อบทความ', value: 'Academic citation practices in digital classrooms' },
                            { label: 'ชื่อวารสาร', value: 'Journal of Educational Technology' },
                            { label: 'Volume / Issue', value: '14(1)' },
                            { label: 'หน้า', value: '22-39' },
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
                            { label: 'ประเภททรัพยากร', value: 'หนังสือ' },
                            { label: 'ผู้แต่ง', value: 'Adams, R. T.' },
                            { label: 'ปีที่พิมพ์', value: '2023b' },
                            { label: 'ชื่อเรื่อง', value: 'Designing reference workflows for student research projects' },
                            { label: 'สำนักพิมพ์', value: 'Learning Design Press' },
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
                            { label: 'ประเภททรัพยากร', value: 'หนังสือ' },
                            { label: 'ผู้แต่ง', value: 'Brown, L. M.' },
                            { label: 'ปีที่พิมพ์', value: '2022' },
                            { label: 'ชื่อเรื่อง', value: 'Information literacy and source attribution in higher education' },
                            { label: 'สำนักพิมพ์', value: 'Routledge' },
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
                            { label: 'ประเภททรัพยากร', value: 'บทความวารสาร' },
                            { label: 'ผู้แต่ง', value: 'Carter, P. J.' },
                            { label: 'ปีที่พิมพ์', value: '2024' },
                            { label: 'ชื่อบทความ', value: 'Metadata quality and automated bibliography generation' },
                            { label: 'ชื่อวารสาร', value: 'International Journal of Digital Libraries' },
                            { label: 'Volume / Issue', value: '20(4)' },
                            { label: 'หน้า', value: '233-251' },
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
                            { label: 'ประเภททรัพยากร', value: 'บทความวารสาร' },
                            { label: 'ผู้แต่ง', value: 'Smith, J. A.' },
                            { label: 'ปีที่พิมพ์', value: '2025' },
                            { label: 'ชื่อบทความ', value: 'Citation management for interdisciplinary research teams' },
                            { label: 'ชื่อวารสาร', value: 'Research Methods Review' },
                            { label: 'Volume / Issue', value: '12(2)' },
                            { label: 'หน้า', value: '77-94' },
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
                    if (this.activeQuickFilter === 'website') return 'กรอก URL ของเว็บไชต์ หรือชื่อหน้าเว็บที่ต้องการอ้างอิง...';
                    if (this.activeQuickFilter === 'book') return 'กรอกชื่อหนังสือ, ISBN หรือชื่อผู้แต่ง...';
                    if (this.activeQuickFilter === 'article') return 'กรอกชื่อบทความ, DOI หรือชื่อวารสาร...';

                    return 'ค้นหาชื่อหนังสือ, ISBN, DOI หรือวาง URL...';
                },
                smartSearchCategoryLabel() {
                    return {
                        website: 'เว็บไชต์',
                        book: 'หนังสือ',
                        article: 'บทความ',
                    }[this.activeQuickFilter] || 'ทรัพยากร';
                },
                smartSearchLoadingTitle() {
                    return `กำลังค้นหา${this.smartSearchCategoryLabel()}ตัวอย่าง`;
                },
                smartSearchLoadingDescription() {
                    if (this.activeQuickFilter === 'website') return 'ระบบกำลังจับคู่ URL และชื่อหน้าเว็บกับข้อมูล mockup';
                    if (this.activeQuickFilter === 'article') return 'ระบบกำลังจับคู่ชื่อบทความ, DOI และชื่อวารสารกับข้อมูล mockup';
                    if (this.activeQuickFilter === 'book') return 'ระบบกำลังจับคู่ชื่อหนังสือ, ISBN และผู้แต่งกับข้อมูล mockup';

                    return 'ระบบกำลังจับคู่ชื่อหนังสือ, ISBN, DOI และ URL กับข้อมูล mockup';
                },
                smartSearchEmptyTitle() {
                    return `ยังไม่พบ${this.smartSearchCategoryLabel()}ตัวอย่างที่ตรงกับคำค้น`;
                },
                smartSearchEmptyDescription() {
                    if (this.activeQuickFilter === 'website') return 'ลองกรอก URL ของเว็บไชต์ให้ครบ หรือพิมพ์ชื่อหน้าเว็บที่ต้องการอ้างอิง';
                    if (this.activeQuickFilter === 'article') return 'ลองกรอกชื่อบทความ, DOI หรือชื่อวารสารให้ชัดขึ้น';
                    if (this.activeQuickFilter === 'book') return 'ลองกรอกชื่อหนังสือ, ISBN หรือชื่อผู้แต่งให้ชัดขึ้น';

                    return 'ลองพิมพ์ชื่อหนังสือให้ชัดขึ้น หรือค้นด้วย ISBN, DOI, URL เช่น ก, 978..., 10....';
                },
                smartSearchManualActionLabel() {
                    if (this.activeQuickFilter === 'website') return 'กรอกข้อมูลเว็บไชต์เอง';
                    if (this.activeQuickFilter === 'article') return 'กรอกข้อมูลบทความเอง';

                    return 'กรอกข้อมูลหนังสือเอง';
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
                    this.form.authors = (book.authors || []).map(author => ({
                        lastName: author.lastName || '',
                        firstName: author.firstName || '',
                    }));
                    if (!this.form.authors.length) {
                        this.form.authors = [{ lastName: '', firstName: '' }];
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
                        if (this.activeQuickFilter === 'website') this.form.websiteName = this.form.websiteName || 'เว็บไซต์ที่ยังไม่ระบุ';
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
                                ? 'ตรงกับชื่อบทความ'
                                : (book.category === 'website' ? 'ตรงกับชื่อหน้าเว็บ' : 'ตรงกับชื่อหนังสือ');

                            if (compact && titleText.startsWith(compact)) {
                                noteMatch('TITLE', titleLabel, 180);
                            } else if (compact && titleText.includes(compact)) {
                                noteMatch('TITLE', titleLabel, 135);
                            }

                            if (compact && subtitleText.includes(compact)) {
                                noteMatch('TITLE', 'ตรงกับคำอธิบายรายการ', 68);
                            }

                            if (compact && authorText.includes(compact)) {
                                noteMatch('AUTHOR', 'ตรงกับชื่อผู้แต่ง', 92);
                            }

                            if (identifier && isbnText.includes(identifier)) {
                                noteMatch('ISBN', 'ตรงกับ ISBN', 210);
                            }

                            if (compact && doiText.includes(compact)) {
                                noteMatch('DOI', 'ตรงกับ DOI', 215);
                            }

                            if (compact && urlText.includes(compact)) {
                                noteMatch('URL', 'ตรงกับ URL', 215);
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
                                matchLabel ||= 'ค้นแบบ ISBN';
                            }

                            if (flags.isDoi && book.doi) {
                                score += 55;
                                matchType ||= 'DOI';
                                matchLabel ||= 'ค้นแบบ DOI';
                            }

                            if (flags.isUrl && book.url) {
                                score += 55;
                                matchType ||= 'URL';
                                matchLabel ||= 'ค้นแบบ URL';
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
                                matchLabel: matchLabel || 'ใกล้เคียงกับคำค้นนี้',
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
                    return this.projectOptions.find(project => project.id === projectId)?.name || 'โครงการที่ยังไม่ระบุ';
                },
                entryProjectId(entry) {
                    return entry?.projectId ?? 1;
                },
                entryTypeLabel(entry) {
                    return entry?.resourceType || 'รายการบรรณานุกรม';
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
                        return 'recent-citation-entry bg-pink-50/90 ring-2 ring-pink-300 shadow-lg shadow-pink-100/70 dark:bg-pink-500/10 dark:ring-pink-500/40 dark:shadow-none';
                    }

                    return 'recent-citation-entry border-pink-400 bg-pink-50 shadow-lg shadow-pink-100/80 dark:border-pink-500 dark:bg-pink-500/10 dark:shadow-none';
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
                buildDetailFields() {
                    const fields = [];
                    const append = (label, value) => {
                        const normalized = String(value ?? '').trim();
                        if (normalized) fields.push({ label, value: normalized });
                    };
                    const authors = this.form.authors
                        .filter(author => author.lastName.trim() || author.firstName.trim())
                        .map(author => [author.lastName.trim(), author.firstName.trim()].filter(Boolean).join(', '))
                        .join('; ');

                    append('ประเภททรัพยากร', this.formResourceType);
                    append('ผู้แต่ง', authors);
                    append('ปีที่พิมพ์', this.form.year);
                    if (this.usesDetailedDate()) {
                        append('เดือน', this.form.month);
                        append('วัน', this.form.day);
                    }
                    append(this.isJournalType() ? 'ชื่อบทความ' : 'ชื่อเรื่อง', this.form.title || this.form.prompt);

                    if (this.isBookType()) {
                        append('สำนักพิมพ์', this.form.publisher);
                        append('เล่ม', this.form.volume);
                        append('ครั้งที่พิมพ์', this.form.edition);
                        append('บรรณาธิการ', this.form.editor);
                        append('ชื่อหนังสือ', this.form.bookTitle);
                        append('หน้า', this.form.pages);
                        append('DOI', this.form.doi);
                        append('URL', this.form.url);
                    } else if (this.isJournalType()) {
                        append('ชื่อวารสาร', this.form.journalName);
                        append('Volume', this.form.volume);
                        append('Issue', this.form.issue);
                        append('หน้า', this.form.pages);
                        append('DOI', this.form.doi);
                        append('URL', this.form.url);
                    } else if (this.isDictionaryType()) {
                        append('ชื่อพจนานุกรม / สารานุกรม', this.form.referenceWork);
                        append('ฉบับ', this.form.edition);
                        append('เล่ม', this.form.volume);
                        append('สำนักพิมพ์', this.form.publisher);
                        append('URL', this.form.url);
                    } else if (this.isNewspaperType()) {
                        append('ชื่อหนังสือพิมพ์', this.form.newspaperName);
                        append('หน้า', this.form.pages);
                        append('URL', this.form.url);
                    } else if (this.isReportType()) {
                        append('หน่วยงาน / องค์กร', this.form.organization);
                        append('Report No.', this.form.reportNumber);
                        append('ผู้เผยแพร่', this.form.publisher);
                        append('URL', this.form.url);
                    } else if (this.isConferenceType()) {
                        append('ชื่องานประชุม', this.form.conferenceName);
                        append('สถานที่จัด', this.form.conferenceLocation);
                        append('หน้า / เลขโปสเตอร์', this.form.pages);
                        append('Proceeding / ผู้เผยแพร่', this.form.publisher);
                        append('URL', this.form.url);
                    } else if (this.isWebType()) {
                        append('ชื่อเว็บไซต์', this.form.websiteName);
                        append('Patent No.', this.form.patentNumber);
                        append('ผู้ถือสิทธิ์ / หน่วยงาน', this.form.assignee || this.form.websiteName);
                        append('URL', this.form.url);
                    } else if (this.isThesisType()) {
                        append('ประเภทวิทยานิพนธ์', this.form.thesisType === 'doctoral' ? 'Doctoral dissertation' : "Master's thesis");
                        append('มหาวิทยาลัย', this.form.university);
                        append('ฐานข้อมูล', this.form.databaseName);
                        append('URL', this.form.url);
                    } else if (this.isMediaType()) {
                        append('รูปแบบสื่อ', this.form.medium);
                        append('แพลตฟอร์ม', this.form.platform);
                        append('URL', this.form.url);
                    } else if (this.isAiType()) {
                        append('แพลตฟอร์ม', this.form.platform);
                        append('รุ่นโมเดล', this.form.model);
                        append('Prompt', this.form.prompt);
                        append('URL', this.form.url);
                    }

                    return fields;
                },
                resetForm() {
                    this.form = {
                        authors: [{ lastName: '', firstName: '' }],
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
                    if (month && day) return ` (n.d., ${month} ${day}). `;

                    return ' (n.d.). ';
                },
                formatAuthors() {
                    const valid = this.form.authors.filter(author => author.lastName.trim());
                    if (!valid.length) return '';
                    return valid.map((author, index) => {
                        const last = author.lastName.trim();
                        const first = author.firstName.trim();
                        const name = first ? `${last}, ${first}` : last;
                        if (valid.length === 1) return name;
                        if (index === valid.length - 1) return `& ${name}`;
                        if (valid.length === 2) return `${name} `;
                        return `${name}, `;
                    }).join('');
                },
                formatAuthorsCitation() {
                    const valid = this.form.authors.filter(author => author.lastName.trim());
                    if (!valid.length) return '';
                    if (valid.length === 1) return valid[0].lastName.trim();
                    if (valid.length === 2) return `${valid[0].lastName.trim()} and ${valid[1].lastName.trim()}`;
                    return `${valid[0].lastName.trim()} et al.`;
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
                        return 'การติดต่อสื่อสารส่วนบุคคลในรูปแบบ APA ใช้อ้างอิงเฉพาะในเนื้อหา และไม่ต้องแสดงในบรรณานุกรมท้ายเล่ม';
                    }

                    if (creator) bibliography += creator;
                    bibliography += this.usesDetailedDate() ? this.formatDate() : (year ? ` (${year}). ` : ' (n.d.). ');

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
                        const thesisType = this.form.thesisType === 'doctoral' ? 'Doctoral dissertation' : "Master's thesis";
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
                    return `${authors} (${year || 'n.d.'}) กล่าวว่า ...`;
                },
                generateParentheticalCitation() {
                    const authors = this.formatAuthorsCitation();
                    const year = this.form.year.trim();
                    if (!authors) return '';
                    return `... (${authors}, ${year || 'n.d.'})`;
                },
                getFormatHint() {
                    if (this.isBookType()) return 'ผู้แต่ง. (ปี). ชื่อหนังสือ (ครั้งที่พิมพ์). สำนักพิมพ์. DOI/URL';
                    if (this.isJournalType()) return 'ผู้แต่ง. (ปี). ชื่อบทความ. ชื่อวารสาร, ปีที่(ฉบับที่), หน้า. DOI/URL';
                    if (this.isWebType()) return 'ผู้แต่ง. (ปี, เดือน วัน). ชื่อเรื่อง. ชื่อเว็บไซต์. URL';
                    if (this.isThesisType()) return 'ผู้แต่ง. (ปี). ชื่อวิทยานิพนธ์ [ประเภท, มหาวิทยาลัย]. URL/ฐานข้อมูล';
                    return 'เลือกประเภททรัพยากรเพื่อดูรูปแบบ APA 7th Edition ที่เหมาะสม';
                },
                addCitationFromForm() {
                    const bibliography = this.generateBibliography();
                    if (!bibliography) {
                        this.toast('กรุณากรอกข้อมูลอย่างน้อยชื่อผู้แต่งและชื่อเรื่อง', 'warning');
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
                    this.toast('เพิ่มรายการบรรณานุกรมเรียบร้อยแล้ว', 'success');
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
                        this.toast('ไม่สามารถบันทึกรายการว่างได้', 'warning');
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
                    this.toast('อัปเดตรายการเรียบร้อยแล้ว', 'success');
                },
                confirmMoveEntry() {
                    if (!this.activeEntry) return;
                    if (!this.moveTargetProjectId || this.moveTargetProjectId === this.entryProjectId(this.activeEntry)) {
                        this.toast('กรุณาเลือกโครงการปลายทางอื่น', 'warning');
                        return;
                    }
                    this.activeEntry.projectId = this.moveTargetProjectId;
                    this.closeEntryModals();
                    this.toast(`ย้ายรายการไปยังโครงการ ${this.projectNameById(this.moveTargetProjectId)} แล้ว`, 'success');
                },
                confirmDeleteEntry() {
                    if (!this.activeEntry) return;

                    const entryId = this.activeEntry.id;
                    const index = this.citations.findIndex(entry => entry.id === entryId);
                    if (index === -1) {
                        this.toast('ไม่พบรายการที่ต้องการลบ', 'danger');
                        this.closeEntryModals();
                        return;
                    }

                    this.citations.splice(index, 1);

                    if (this.recentlyAddedEntryId === entryId) {
                        this.recentlyAddedEntryId = null;
                    }

                    this.closeEntryModals();
                    this.toast('ลบรายการบรรณานุกรมเรียบร้อยแล้ว', 'danger');
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
