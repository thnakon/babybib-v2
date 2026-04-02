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
        <aside x-data="{
            projectModal: false,
            projectMenuOpen: null,
            projectFormMode: 'create',
            editingProjectId: null,
            activeProject: 1,
            projectForm: { name: '', color: 'zinc', icon: 'folder' },
            projectColors: [
                { value: 'zinc', label: 'เทา', swatch: 'bg-zinc-500', button: 'bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-900' },
                { value: 'sky', label: 'ฟ้า', swatch: 'bg-sky-500', button: 'bg-sky-500 text-white' },
                { value: 'emerald', label: 'เขียว', swatch: 'bg-emerald-500', button: 'bg-emerald-500 text-white' },
                { value: 'amber', label: 'ทอง', swatch: 'bg-amber-500', button: 'bg-amber-500 text-white' },
                { value: 'rose', label: 'ชมพู', swatch: 'bg-rose-500', button: 'bg-rose-500 text-white' },
                { value: 'violet', label: 'ม่วง', swatch: 'bg-violet-500', button: 'bg-violet-500 text-white' }
            ],
            projectIcons: [
                { value: 'folder', label: 'โฟลเดอร์' },
                { value: 'book-open', label: 'หนังสือ' },
                { value: 'document-text', label: 'เอกสาร' },
                { value: 'academic-cap', label: 'วิจัย' },
                { value: 'clipboard-document-list', label: 'รายการ' },
                { value: 'sparkles', label: 'พิเศษ' }
            ],
            projects: [
                { id: 1, name: 'โครงการวิจัยบทที่ 1', color: 'zinc', icon: 'folder' }
            ],
            toast(text, variant = 'success') {
                window.Flux?.toast(text, { variant, position: 'bottom end' });
            },
            projectButtonClass(color) {
                return (this.projectColors.find(option => option.value === color)?.button) || 'bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-900';
            },
            openCreateProjectModal() {
                this.projectFormMode = 'create';
                this.editingProjectId = null;
                this.projectForm = { name: '', color: 'zinc', icon: 'folder' };
                this.projectModal = true;
            },
            openEditProjectModal(project) {
                this.projectFormMode = 'edit';
                this.editingProjectId = project.id;
                this.projectForm = { name: project.name, color: project.color, icon: project.icon };
                this.projectMenuOpen = null;
                this.projectModal = true;
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
                        icon: this.projectForm.icon
                    });
                    this.activeProject = nextId;
                    this.projectModal = false;
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
                this.projectModal = false;
                this.toast('อัปเดตโครงการเรียบร้อยแล้ว', 'success');
            },
            duplicateProject(project) {
                const nextId = Date.now();
                this.projects.unshift({
                    id: nextId,
                    name: project.name + ' (คัดลอก)',
                    color: project.color,
                    icon: project.icon
                });
                this.activeProject = nextId;
                this.projectMenuOpen = null;
                this.toast('คัดลอกโครงการเรียบร้อยแล้ว', 'success');
            },
            deleteProject(projectId) {
                if (this.projects.length === 1) {
                    this.projectMenuOpen = null;
                    this.toast('ต้องมีอย่างน้อย 1 โครงการในระบบ', 'warning');
                    return;
                }

                const index = this.projects.findIndex(item => item.id === projectId);
                if (index === -1) {
                    this.toast('ไม่พบโครงการที่ต้องการลบ', 'danger');
                    return;
                }

                this.projects.splice(index, 1);
                if (this.activeProject === projectId) {
                    this.activeProject = this.projects[0]?.id ?? null;
                }
                this.projectMenuOpen = null;
                this.toast('ลบโครงการเรียบร้อยแล้ว', 'danger');
            }
        }"
            class="custom-scrollbar w-full shrink-0 overflow-hidden rounded-2xl border border-zinc-200/80 bg-zinc-50/60 p-4 dark:border-zinc-800 dark:bg-zinc-900/50 lg:block lg:w-64 lg:rounded-none lg:border-0 lg:bg-transparent lg:p-0 lg:h-[calc(100vh-10rem)] lg:overflow-y-auto lg:pr-4 lg:visible-scrollbar">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold tracking-tight text-zinc-900 dark:text-zinc-100">โครงการ</h2>
                    <span class="rounded-full bg-zinc-100 px-2.5 py-1 text-[11px] font-medium text-zinc-500 dark:bg-zinc-800 dark:text-zinc-300" x-text="projects.length + ' รายการ'"></span>
                </div>

                <button type="button" x-on:click="openCreateProjectModal()"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-dashed border-zinc-300 bg-white px-4 py-3 text-sm font-semibold text-zinc-700 transition hover:border-pink-400 hover:text-zinc-900 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-200 dark:hover:border-pink-500 dark:hover:text-zinc-100">
                    <flux:icon name="plus" class="size-4" />
                    <span>สร้างโครงการใหม่</span>
                </button>

                <div class="space-y-3">
                    <template x-for="project in projects" :key="project.id">
                        <div class="group relative">
                            <button type="button" x-on:click="activeProject = project.id"
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
                                    </span>
                                    <h3 class="text-sm font-semibold" x-text="project.name"></h3>
                                </div>
                            </button>

                            <div class="absolute right-2 top-1/2 -translate-y-1/2">
                                <button type="button"
                                    x-on:click.stop="projectMenuOpen = projectMenuOpen === project.id ? null : project.id"
                                    class="inline-flex size-8 items-center justify-center rounded-full text-zinc-400 opacity-0 transition hover:bg-zinc-200 hover:text-zinc-900 group-hover:opacity-100 dark:hover:bg-zinc-700 dark:hover:text-zinc-100"
                                    x-bind:class="projectMenuOpen === project.id || activeProject === project.id ? 'opacity-100' : ''"
                                    aria-label="จัดการโครงการ">
                                    <flux:icon name="ellipsis-horizontal" class="size-4" />
                                </button>

                                <div x-cloak x-show="projectMenuOpen === project.id" x-transition.opacity x-on:click.outside="projectMenuOpen = null"
                                    class="absolute right-0 z-20 mt-2 w-44 rounded-2xl border border-zinc-200 bg-white p-1.5 shadow-lg dark:border-zinc-700 dark:bg-zinc-950">
                                    <button type="button" x-on:click="openEditProjectModal(project)"
                                        class="flex w-full items-center gap-2 rounded-xl px-3 py-2 text-left text-sm text-zinc-600 transition hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-300 dark:hover:bg-zinc-900 dark:hover:text-zinc-100">
                                        <flux:icon name="pencil-square" class="size-4" />
                                        แก้ไข
                                    </button>
                                    <button type="button" x-on:click="duplicateProject(project)"
                                        class="flex w-full items-center gap-2 rounded-xl px-3 py-2 text-left text-sm text-zinc-600 transition hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-300 dark:hover:bg-zinc-900 dark:hover:text-zinc-100">
                                        <flux:icon name="square-2-stack" class="size-4" />
                                        คัดลอกโครงการ
                                    </button>
                                    <button type="button" x-on:click="deleteProject(project.id)"
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
                class="fixed inset-0 z-50 flex items-center justify-center bg-zinc-950/40 px-4 backdrop-blur-sm">
                <div x-show="projectModal" x-transition
                    class="w-full max-w-lg rounded-[1.75rem] border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-1">
                            <h3 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100" x-text="projectFormMode === 'create' ? 'สร้างโครงการใหม่' : 'แก้ไขโครงการ'"></h3>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">ตั้งชื่อ เลือกสี และเลือกไอคอนของโครงการสำหรับจัดเก็บบรรณานุกรม</p>
                        </div>
                        <button type="button" x-on:click="projectModal = false"
                            class="rounded-full p-2 text-zinc-400 transition hover:bg-zinc-100 hover:text-zinc-900 dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
                            <flux:icon name="x-mark" class="size-5" />
                        </button>
                    </div>

                    <div class="mt-6 space-y-2">
                        <label class="text-sm font-medium text-zinc-700 dark:text-zinc-200">ชื่อโครงการ</label>
                        <input x-model="projectForm.name" type="text" placeholder="เช่น โครงการวิทยานิพนธ์ปี 2026"
                            class="w-full rounded-2xl border border-zinc-200 bg-white px-4 py-3 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-900/5 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-zinc-500 dark:focus:ring-white/5">
                    </div>

                    <div class="mt-5 space-y-3">
                        <label class="text-sm font-medium text-zinc-700 dark:text-zinc-200">สีของโครงการ</label>
                        <div class="grid grid-cols-3 gap-2">
                            <template x-for="color in projectColors" :key="color.value">
                                <button type="button" x-on:click="projectForm.color = color.value"
                                    class="flex items-center gap-2 rounded-2xl border px-3 py-2 text-sm transition"
                                    x-bind:class="projectForm.color === color.value
                                        ? 'border-zinc-900 bg-zinc-50 text-zinc-900 dark:border-zinc-100 dark:bg-zinc-800 dark:text-zinc-100'
                                        : 'border-zinc-200 text-zinc-600 hover:border-zinc-300 hover:text-zinc-900 dark:border-zinc-700 dark:text-zinc-300 dark:hover:border-zinc-500 dark:hover:text-zinc-100'">
                                    <span class="size-3 rounded-full" :class="color.swatch"></span>
                                    <span x-text="color.label"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <div class="mt-5 space-y-3">
                        <label class="text-sm font-medium text-zinc-700 dark:text-zinc-200">ไอคอนของโครงการ</label>
                        <div class="grid grid-cols-3 gap-2">
                            <template x-for="icon in projectIcons" :key="icon.value">
                                <button type="button" x-on:click="projectForm.icon = icon.value"
                                    class="flex items-center gap-2 rounded-2xl border px-3 py-2 text-sm transition"
                                    x-bind:class="projectForm.icon === icon.value
                                        ? 'border-zinc-900 bg-zinc-50 text-zinc-900 dark:border-zinc-100 dark:bg-zinc-800 dark:text-zinc-100'
                                        : 'border-zinc-200 text-zinc-600 hover:border-zinc-300 hover:text-zinc-900 dark:border-zinc-700 dark:text-zinc-300 dark:hover:border-zinc-500 dark:hover:text-zinc-100'">
                                    <span class="inline-flex size-5 items-center justify-center">
                                        <flux:icon x-show="icon.value === 'folder'" name="folder" class="size-4" />
                                        <flux:icon x-show="icon.value === 'book-open'" name="book-open" class="size-4" />
                                        <flux:icon x-show="icon.value === 'document-text'" name="document-text" class="size-4" />
                                        <flux:icon x-show="icon.value === 'academic-cap'" name="academic-cap" class="size-4" />
                                        <flux:icon x-show="icon.value === 'clipboard-document-list'" name="clipboard-document-list" class="size-4" />
                                        <flux:icon x-show="icon.value === 'sparkles'" name="sparkles" class="size-4" />
                                    </span>
                                    <span x-text="icon.label"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" x-on:click="projectModal = false"
                            class="px-4 py-2 text-sm font-medium text-zinc-500 transition hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100">
                            ยกเลิก
                        </button>
                        <button type="button"
                            x-on:click="saveProject()"
                            class="inline-flex items-center gap-2 rounded-full bg-zinc-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-zinc-800 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-white">
                            <flux:icon x-show="projectFormMode === 'create'" name="plus" class="size-4" />
                            <flux:icon x-show="projectFormMode === 'edit'" name="check" class="size-4" />
                            <span x-text="projectFormMode === 'create' ? 'สร้างโครงการ' : 'บันทึกการแก้ไข'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </aside>

        <main class="min-w-0 flex-1">
            <div class="grid gap-3 lg:grid-cols-7">
                <section x-data="{ smartQuery: '', selectedType: '', modalOpen: false, modalSearch: '', copied: false, exportOpen: false, citationStyle: 'apa7', displayMode: 'paper' }"
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
                            class="fixed inset-0 z-50 flex items-center justify-center bg-zinc-950/50 px-4 py-6 backdrop-blur-sm">
                            <div x-show="modalOpen" x-transition
                                class="flex max-h-[90vh] w-full max-w-6xl flex-col overflow-hidden rounded-[2rem] border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
                                <div class="flex items-start justify-between gap-4 border-b border-zinc-200 px-6 py-5 dark:border-zinc-800 lg:px-8">
                                    <div class="space-y-1">
                                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-zinc-400 dark:text-zinc-500">Resource Library</p>
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
                                            class="w-full rounded-2xl border border-zinc-200 bg-zinc-50 py-3 pl-12 pr-4 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-900/5 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-zinc-500 dark:focus:ring-white/5">
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
                                            <div class="rounded-3xl border border-zinc-200 bg-zinc-50/60 p-5 dark:border-zinc-800 dark:bg-zinc-950/50"
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
                                                            x-on:click="selectedType = @js($item); smartQuery = @js($item); modalOpen = false"
                                                            class="flex w-full items-start justify-between gap-3 rounded-2xl border border-transparent bg-white px-4 py-3 text-left text-sm text-zinc-700 transition hover:border-pink-200 hover:bg-pink-50 hover:text-zinc-900 dark:bg-zinc-900 dark:text-zinc-200 dark:hover:border-pink-500/30 dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
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
    <flux:toast position="bottom end" class="z-[120]" />
    @fluxScripts
</body>

</html>
