{{-- Citation Form Modal - APA 7th Edition --}}
<div x-cloak x-show="formModalOpen" x-transition.opacity
    class="fixed inset-0 z-[60] flex items-center justify-center bg-zinc-950/50 px-4 py-6 backdrop-blur-sm">
    <div x-show="formModalOpen" x-transition
        class="flex max-h-[92vh] w-full max-w-7xl flex-col overflow-hidden rounded-[2rem] border border-pink-200 bg-white shadow-2xl shadow-pink-100/60 dark:border-zinc-700 dark:bg-zinc-900 dark:shadow-none">

        {{-- Header --}}
        <div class="flex items-center justify-between gap-4 border-b border-zinc-200 px-6 py-4 dark:border-zinc-800 lg:px-8">
            <div class="flex items-center gap-3">
                <span class="inline-flex size-10 items-center justify-center rounded-2xl bg-pink-100 text-pink-600 ring-1 ring-pink-200 dark:bg-pink-500/10 dark:text-pink-300 dark:ring-pink-400/20">
                    <flux:icon name="pencil-square" class="size-5" />
                </span>
                <div>
                    <h3 class="text-lg font-semibold tracking-tight text-zinc-900 dark:text-zinc-100">
                        กรอกข้อมูลบรรณานุกรม
                    </h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">
                        ประเภท: <span class="font-medium text-pink-600 dark:text-pink-300" x-text="formResourceType"></span>
                        <span class="ml-2 rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-semibold text-amber-700 dark:bg-amber-500/10 dark:text-amber-300">APA 7th</span>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" x-on:click="formModalOpen = false; formResourceType = ''; resetForm()"
                    class="rounded-full p-2 text-zinc-400 transition hover:bg-zinc-100 hover:text-zinc-900 dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
                    <flux:icon name="x-mark" class="size-5" />
                </button>
            </div>
        </div>

        {{-- Body: Form + Preview split --}}
        <div class="custom-scrollbar flex flex-1 overflow-hidden">
            {{-- Left: Input Form --}}
            <div class="custom-scrollbar w-full flex-1 overflow-y-auto border-r border-zinc-200 px-6 py-6 dark:border-zinc-800 lg:w-1/2 lg:px-8">
                <div class="space-y-5">

                    {{-- Author Section --}}
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex size-6 items-center justify-center rounded-lg bg-violet-100 text-violet-600 dark:bg-violet-500/10 dark:text-violet-300">
                                <flux:icon name="user" class="size-3.5" />
                            </span>
                            <h4 class="text-sm font-semibold text-zinc-800 dark:text-zinc-200">ผู้แต่ง</h4>
                        </div>
                        <template x-for="(author, index) in form.authors" :key="index">
                            <div class="flex items-start gap-2">
                                <div class="grid flex-1 gap-2 sm:grid-cols-2">
                                    <div>
                                        <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">นามสกุล <span class="text-rose-400">*</span></label>
                                        <input x-model="author.lastName" type="text" placeholder="เช่น สมิธ"
                                            class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">ชื่อ (อักษรย่อ) <span class="text-rose-400">*</span></label>
                                        <input x-model="author.firstName" type="text" placeholder="เช่น J. K."
                                            class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                    </div>
                                </div>
                                <button type="button" x-show="form.authors.length > 1" x-on:click="form.authors.splice(index, 1)"
                                    class="mt-6 inline-flex size-8 shrink-0 items-center justify-center rounded-lg text-zinc-400 transition hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-500/10 dark:hover:text-rose-400">
                                    <flux:icon name="trash" class="size-4" />
                                </button>
                            </div>
                        </template>
                        <button type="button" x-on:click="form.authors.push({ lastName: '', firstName: '' })"
                            class="inline-flex items-center gap-1.5 rounded-lg border border-dashed border-zinc-300 px-3 py-1.5 text-xs font-medium text-zinc-500 transition hover:border-pink-400 hover:text-pink-600 dark:border-zinc-600 dark:text-zinc-400 dark:hover:border-pink-500 dark:hover:text-pink-300">
                            <flux:icon name="plus" class="size-3" />
                            เพิ่มผู้แต่ง
                        </button>
                    </div>

                    <hr class="border-zinc-100 dark:border-zinc-800">

                    {{-- Date --}}
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex size-6 items-center justify-center rounded-lg bg-sky-100 text-sky-600 dark:bg-sky-500/10 dark:text-sky-300">
                                <flux:icon name="calendar" class="size-3.5" />
                            </span>
                            <h4 class="text-sm font-semibold text-zinc-800 dark:text-zinc-200">ปีที่พิมพ์</h4>
                        </div>
                        <div class="max-w-xs">
                            <input x-model="form.year" type="text" placeholder="เช่น 2025"
                                class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                        </div>
                        <div x-show="usesDetailedDate()" class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">เดือน</label>
                                <input x-model="form.month" type="text" placeholder="เช่น April"
                                    class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                            </div>
                            <div>
                                <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">วัน</label>
                                <input x-model="form.day" type="text" placeholder="เช่น 2"
                                    class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                            </div>
                        </div>
                    </div>

                    <hr class="border-zinc-100 dark:border-zinc-800">

                    {{-- Title --}}
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex size-6 items-center justify-center rounded-lg bg-amber-100 text-amber-600 dark:bg-amber-500/10 dark:text-amber-300">
                                <flux:icon name="document-text" class="size-3.5" />
                            </span>
                            <h4 class="text-sm font-semibold text-zinc-800 dark:text-zinc-200">ชื่อเรื่อง</h4>
                        </div>
                        <div>
                            <input x-model="form.title" type="text" placeholder="ชื่อหนังสือ / บทความ / เอกสาร"
                                class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                        </div>
                    </div>

                    {{-- Conditional fields based on resource type --}}

                    {{-- Book fields --}}
                    <template x-if="isBookType()">
                        <div class="space-y-5">
                            <hr class="border-zinc-100 dark:border-zinc-800">
                            <div class="space-y-3">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex size-6 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-300">
                                        <flux:icon name="building-office" class="size-3.5" />
                                    </span>
                                    <h4 class="text-sm font-semibold text-zinc-800 dark:text-zinc-200">ข้อมูลสำนักพิมพ์</h4>
                                </div>
                                <div class="grid gap-3 sm:grid-cols-2">
                                    <div>
                                        <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">สำนักพิมพ์</label>
                                        <input x-model="form.publisher" type="text" placeholder="เช่น สำนักพิมพ์จุฬาลงกรณ์"
                                            class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                    </div>
                                    <div x-show="formResourceType === 'หนังสือชุดหลายเล่มจบ'">
                                        <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">เล่มที่</label>
                                        <input x-model="form.volume" type="text" placeholder="เช่น 1-3"
                                            class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                    </div>
                                </div>
                                <div x-show="formResourceType !== 'บทความในหนังสือ'">
                                    <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">พิมพ์ครั้งที่</label>
                                    <input x-model="form.edition" type="text" placeholder="เช่น 2"
                                        class="w-full max-w-xs rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                </div>
                            </div>

                            {{-- Chapter info for บทความในหนังสือ --}}
                            <template x-if="formResourceType === 'บทความในหนังสือ'">
                                <div class="space-y-3">
                                    <hr class="border-zinc-100 dark:border-zinc-800">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex size-6 items-center justify-center rounded-lg bg-rose-100 text-rose-600 dark:bg-rose-500/10 dark:text-rose-300">
                                            <flux:icon name="bookmark" class="size-3.5" />
                                        </span>
                                        <h4 class="text-sm font-semibold text-zinc-800 dark:text-zinc-200">ข้อมูลบทความ</h4>
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">ชื่อบรรณาธิการ</label>
                                        <input x-model="form.editor" type="text" placeholder="เช่น A. B. Editor"
                                            class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">ชื่อหนังสือ (ที่บทความอยู่)</label>
                                        <input x-model="form.bookTitle" type="text" placeholder="เช่น ชื่อหนังสือรวมบทความ"
                                            class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">หน้า (pp.)</label>
                                        <input x-model="form.pages" type="text" placeholder="เช่น 100-120"
                                            class="w-full max-w-xs rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                    </div>
                                </div>
                            </template>

                            {{-- DOI/URL for e-books --}}
                            <template x-if="formResourceType.includes('อิเล็กทรอนิกส์')">
                                <div class="space-y-3">
                                    <hr class="border-zinc-100 dark:border-zinc-800">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex size-6 items-center justify-center rounded-lg bg-cyan-100 text-cyan-600 dark:bg-cyan-500/10 dark:text-cyan-300">
                                            <flux:icon name="link" class="size-3.5" />
                                        </span>
                                        <h4 class="text-sm font-semibold text-zinc-800 dark:text-zinc-200" x-text="formResourceType.includes('DOI') ? 'DOI' : 'URL'"></h4>
                                    </div>
                                    <template x-if="formResourceType.includes('DOI')">
                                        <div>
                                            <input x-model="form.doi" type="text" placeholder="เช่น https://doi.org/10.xxxx/xxxxx"
                                                class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                        </div>
                                    </template>
                                    <template x-if="!formResourceType.includes('DOI')">
                                        <div>
                                            <input x-model="form.url" type="text" placeholder="เช่น https://example.com/book"
                                                class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </template>

                    {{-- Journal fields --}}
                    <template x-if="isJournalType()">
                        <div class="space-y-5">
                            <hr class="border-zinc-100 dark:border-zinc-800">
                            <div class="space-y-3">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex size-6 items-center justify-center rounded-lg bg-sky-100 text-sky-600 dark:bg-sky-500/10 dark:text-sky-300">
                                        <flux:icon name="newspaper" class="size-3.5" />
                                    </span>
                                    <h4 class="text-sm font-semibold text-zinc-800 dark:text-zinc-200">ข้อมูลวารสาร</h4>
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">ชื่อวารสาร</label>
                                    <input x-model="form.journalName" type="text" placeholder="เช่น Journal of Digital Research"
                                        class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                </div>
                                <div class="grid gap-3 sm:grid-cols-3">
                                    <div>
                                        <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">ปีที่ (Volume)</label>
                                        <input x-model="form.volume" type="text" placeholder="เช่น 18"
                                            class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">ฉบับที่ (Issue)</label>
                                        <input x-model="form.issue" type="text" placeholder="เช่น 2"
                                            class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">หน้า</label>
                                        <input x-model="form.pages" type="text" placeholder="เช่น 44-61"
                                            class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                    </div>
                                </div>
                                <template x-if="formResourceType.includes('DOI') || formResourceType.includes('อิเล็กทรอนิกส์')">
                                    <div>
                                        <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400" x-text="formResourceType.includes('DOI') ? 'DOI' : 'URL'"></label>
                                        <template x-if="formResourceType.includes('DOI')">
                                            <input x-model="form.doi" type="text" placeholder="เช่น https://doi.org/10.xxxx/xxxxx"
                                                class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                        </template>
                                        <template x-if="!formResourceType.includes('DOI')">
                                            <input x-model="form.url" type="text" placeholder="เช่น https://example.com/article"
                                                class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <template x-if="isDictionaryType()">
                        <div class="space-y-5">
                            <hr class="border-zinc-100 dark:border-zinc-800">
                            <div class="space-y-3">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex size-6 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-300">
                                        <flux:icon name="book-open" class="size-3.5" />
                                    </span>
                                    <h4 class="text-sm font-semibold text-zinc-800 dark:text-zinc-200">ข้อมูลแหล่งอ้างอิง</h4>
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">ชื่อพจนานุกรม / สารานุกรม</label>
                                    <input x-model="form.referenceWork" type="text" placeholder="เช่น Encyclopedia Britannica"
                                        class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                </div>
                                <div class="grid gap-3 sm:grid-cols-2">
                                    <div>
                                        <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">ฉบับ / Edition</label>
                                        <input x-model="form.edition" type="text" placeholder="เช่น 15"
                                            class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">เล่ม</label>
                                        <input x-model="form.volume" type="text" placeholder="เช่น 3"
                                            class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                    </div>
                                </div>
                                <div class="grid gap-3 sm:grid-cols-2">
                                    <div>
                                        <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">สำนักพิมพ์</label>
                                        <input x-model="form.publisher" type="text" placeholder="เช่น Britannica"
                                            class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                    </div>
                                    <div x-show="formResourceType.includes('ออนไลน์')">
                                        <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">URL</label>
                                        <input x-model="form.url" type="text" placeholder="เช่น https://example.com/entry"
                                            class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <template x-if="isNewspaperType()">
                        <div class="space-y-5">
                            <hr class="border-zinc-100 dark:border-zinc-800">
                            <div class="space-y-3">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex size-6 items-center justify-center rounded-lg bg-rose-100 text-rose-600 dark:bg-rose-500/10 dark:text-rose-300">
                                        <flux:icon name="newspaper" class="size-3.5" />
                                    </span>
                                    <h4 class="text-sm font-semibold text-zinc-800 dark:text-zinc-200">ข้อมูลหนังสือพิมพ์</h4>
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">ชื่อหนังสือพิมพ์</label>
                                    <input x-model="form.newspaperName" type="text" placeholder="เช่น The New York Times"
                                        class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                </div>
                                <div class="grid gap-3 sm:grid-cols-2">
                                    <div>
                                        <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">หน้า</label>
                                        <input x-model="form.pages" type="text" placeholder="เช่น A1-A3"
                                            class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                    </div>
                                    <div x-show="formResourceType.includes('ออนไลน์')">
                                        <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">URL</label>
                                        <input x-model="form.url" type="text" placeholder="เช่น https://example.com/news"
                                            class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <template x-if="isReportType()">
                        <div class="space-y-5">
                            <hr class="border-zinc-100 dark:border-zinc-800">
                            <div class="space-y-3">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex size-6 items-center justify-center rounded-lg bg-violet-100 text-violet-600 dark:bg-violet-500/10 dark:text-violet-300">
                                        <flux:icon name="clipboard-document-list" class="size-3.5" />
                                    </span>
                                    <h4 class="text-sm font-semibold text-zinc-800 dark:text-zinc-200">ข้อมูลรายงาน</h4>
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">หน่วยงาน / องค์กร</label>
                                    <input x-model="form.organization" type="text" placeholder="เช่น World Health Organization"
                                        class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                </div>
                                <div class="grid gap-3 sm:grid-cols-2">
                                    <div>
                                        <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">Report No.</label>
                                        <input x-model="form.reportNumber" type="text" placeholder="เช่น WHO-2026-04"
                                            class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">สำนักพิมพ์ / ผู้เผยแพร่</label>
                                        <input x-model="form.publisher" type="text" placeholder="เช่น WHO Press"
                                            class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">URL</label>
                                    <input x-model="form.url" type="text" placeholder="เช่น https://example.org/report.pdf"
                                        class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                </div>
                            </div>
                        </div>
                    </template>

                    <template x-if="isConferenceType()">
                        <div class="space-y-5">
                            <hr class="border-zinc-100 dark:border-zinc-800">
                            <div class="space-y-3">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex size-6 items-center justify-center rounded-lg bg-orange-100 text-orange-600 dark:bg-orange-500/10 dark:text-orange-300">
                                        <flux:icon name="presentation-chart-bar" class="size-3.5" />
                                    </span>
                                    <h4 class="text-sm font-semibold text-zinc-800 dark:text-zinc-200">ข้อมูลงานประชุม</h4>
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">ชื่องานประชุม</label>
                                    <input x-model="form.conferenceName" type="text" placeholder="เช่น International Conference on..."
                                        class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                </div>
                                <div class="grid gap-3 sm:grid-cols-2">
                                    <div>
                                        <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">สถานที่จัด</label>
                                        <input x-model="form.conferenceLocation" type="text" placeholder="เช่น Bangkok, Thailand"
                                            class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">หน้า / เลขโปสเตอร์</label>
                                        <input x-model="form.pages" type="text" placeholder="เช่น 24-30"
                                            class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                    </div>
                                </div>
                                <div class="grid gap-3 sm:grid-cols-2">
                                    <div>
                                        <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">ผู้เผยแพร่ / Proceeding</label>
                                        <input x-model="form.publisher" type="text" placeholder="เช่น ACM"
                                            class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">URL</label>
                                        <input x-model="form.url" type="text" placeholder="เช่น https://example.com/proceeding"
                                            class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    {{-- Website fields --}}
                    <template x-if="isWebType()">
                        <div class="space-y-5">
                            <hr class="border-zinc-100 dark:border-zinc-800">
                            <div class="space-y-3">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex size-6 items-center justify-center rounded-lg bg-cyan-100 text-cyan-600 dark:bg-cyan-500/10 dark:text-cyan-300">
                                        <flux:icon name="globe-alt" class="size-3.5" />
                                    </span>
                                    <h4 class="text-sm font-semibold text-zinc-800 dark:text-zinc-200">ข้อมูลเว็บไซต์</h4>
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">ชื่อเว็บไซต์</label>
                                    <input x-model="form.websiteName" type="text" placeholder="เช่น BBC News"
                                        class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">URL</label>
                                    <input x-model="form.url" type="text" placeholder="เช่น https://example.com/article"
                                        class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                </div>
                                <div x-show="formResourceType === 'สิทธิบัตรออนไลน์'" class="grid gap-3 sm:grid-cols-2">
                                    <div>
                                        <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">Patent No.</label>
                                        <input x-model="form.patentNumber" type="text" placeholder="เช่น US1234567B2"
                                            class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">ผู้ถือสิทธิ์ / หน่วยงาน</label>
                                        <input x-model="form.websiteName" type="text" placeholder="เช่น Google LLC"
                                            class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                    </div>
                                </div>
                                <p x-show="formResourceType === 'การติดต่อสื่อสารส่วนบุคคล'" class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs leading-5 text-amber-700 dark:border-amber-500/20 dark:bg-amber-500/10 dark:text-amber-300">
                                    การติดต่อสื่อสารส่วนบุคคลตาม APA ปกติจะใช้อ้างอิงเฉพาะในเนื้อหา ไม่แสดงในบรรณานุกรมท้ายเล่ม
                                </p>
                            </div>
                        </div>
                    </template>

                    {{-- Thesis fields --}}
                    <template x-if="isThesisType()">
                        <div class="space-y-5">
                            <hr class="border-zinc-100 dark:border-zinc-800">
                            <div class="space-y-3">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex size-6 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-300">
                                        <flux:icon name="academic-cap" class="size-3.5" />
                                    </span>
                                    <h4 class="text-sm font-semibold text-zinc-800 dark:text-zinc-200">ข้อมูลวิทยานิพนธ์</h4>
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">ประเภท</label>
                                    <select x-model="form.thesisType"
                                        class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                        <option value="doctoral">ดุษฎีนิพนธ์ (Doctoral dissertation)</option>
                                        <option value="master">วิทยานิพนธ์ (Master's thesis)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">มหาวิทยาลัย</label>
                                    <input x-model="form.university" type="text" placeholder="เช่น จุฬาลงกรณ์มหาวิทยาลัย"
                                        class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                </div>
                                <template x-if="formResourceType.includes('เว็บไซต์') || formResourceType.includes('ฐานข้อมูล')">
                                    <div class="grid gap-3 sm:grid-cols-2">
                                        <div x-show="formResourceType.includes('ฐานข้อมูล')">
                                            <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">ชื่อฐานข้อมูล</label>
                                            <input x-model="form.databaseName" type="text" placeholder="เช่น ProQuest Dissertations"
                                                class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                        </div>
                                        <div x-show="formResourceType.includes('เว็บไซต์')">
                                            <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">URL</label>
                                            <input x-model="form.url" type="text" placeholder="เช่น https://thesis.example.ac.th/..."
                                                class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <template x-if="isMediaType()">
                        <div class="space-y-5">
                            <hr class="border-zinc-100 dark:border-zinc-800">
                            <div class="space-y-3">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex size-6 items-center justify-center rounded-lg bg-fuchsia-100 text-fuchsia-600 dark:bg-fuchsia-500/10 dark:text-fuchsia-300">
                                        <flux:icon name="play-circle" class="size-3.5" />
                                    </span>
                                    <h4 class="text-sm font-semibold text-zinc-800 dark:text-zinc-200">ข้อมูลสื่อ</h4>
                                </div>
                                <div class="grid gap-3 sm:grid-cols-2">
                                    <div>
                                        <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">รูปแบบสื่อ</label>
                                        <input x-model="form.medium" type="text" placeholder="เช่น Video, Podcast, Infographic"
                                            class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">แพลตฟอร์ม / ช่องทาง</label>
                                        <input x-model="form.platform" type="text" placeholder="เช่น YouTube, Spotify"
                                            class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">URL</label>
                                    <input x-model="form.url" type="text" placeholder="เช่น https://youtube.com/..."
                                        class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                </div>
                            </div>
                        </div>
                    </template>

                    <template x-if="isAiType()">
                        <div class="space-y-5">
                            <hr class="border-zinc-100 dark:border-zinc-800">
                            <div class="space-y-3">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex size-6 items-center justify-center rounded-lg bg-teal-100 text-teal-600 dark:bg-teal-500/10 dark:text-teal-300">
                                        <flux:icon name="sparkles" class="size-3.5" />
                                    </span>
                                    <h4 class="text-sm font-semibold text-zinc-800 dark:text-zinc-200">ข้อมูลเนื้อหาที่สร้างโดย AI</h4>
                                </div>
                                <div class="grid gap-3 sm:grid-cols-2">
                                    <div>
                                        <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">แพลตฟอร์ม</label>
                                        <input x-model="form.platform" type="text" placeholder="เช่น ChatGPT"
                                            class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">รุ่นโมเดล</label>
                                        <input x-model="form.model" type="text" placeholder="เช่น GPT-5.4"
                                            class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">Prompt / คำสั่งที่ใช้</label>
                                    <textarea x-model="form.prompt" rows="3" placeholder="เช่น Summarize the impact of..."
                                        class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500"></textarea>
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs text-zinc-500 dark:text-zinc-400">URL</label>
                                    <input x-model="form.url" type="text" placeholder="เช่น https://chat.openai.com/"
                                        class="w-full rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 placeholder:text-zinc-400 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500/10 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-pink-500">
                                </div>
                            </div>
                        </div>
                    </template>

                </div>
            </div>

            {{-- Right: Real-time Preview --}}
            <div class="hidden w-1/2 flex-col bg-gradient-to-br from-zinc-50 via-pink-50/30 to-zinc-50 p-6 dark:from-zinc-950 dark:via-pink-950/10 dark:to-zinc-950 lg:flex lg:px-8">
                <div class="sticky top-0 space-y-6">
                    {{-- Bibliography Preview --}}
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex size-6 items-center justify-center rounded-lg bg-pink-100 text-pink-600 dark:bg-pink-500/10 dark:text-pink-300">
                                <flux:icon name="document-text" class="size-3.5" />
                            </span>
                            <h4 class="text-sm font-semibold text-zinc-800 dark:text-zinc-200">ตัวอย่างบรรณานุกรม</h4>
                            <span class="rounded-full bg-pink-100 px-2 py-0.5 text-[10px] font-semibold text-pink-600 dark:bg-pink-500/10 dark:text-pink-300">LIVE</span>
                        </div>
                        <div class="min-h-[80px] rounded-2xl border border-pink-200 bg-white p-5 text-sm leading-8 text-zinc-700 shadow-sm dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300"
                            style="padding-left: calc(0.5in + 1.25rem); text-indent: -0.5in;">
                            <p x-text="generateBibliography() || 'กรุณากรอกข้อมูลด้านซ้ายเพื่อดูตัวอย่างบรรณานุกรมแบบเรียลไทม์...'"
                                x-bind:class="generateBibliography() ? 'text-zinc-800 dark:text-zinc-200' : 'text-zinc-400 dark:text-zinc-500 italic'">
                            </p>
                        </div>
                    </div>

                    {{-- In-text Citation Preview --}}
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex size-6 items-center justify-center rounded-lg bg-violet-100 text-violet-600 dark:bg-violet-500/10 dark:text-violet-300">
                                <flux:icon name="chat-bubble-bottom-center-text" class="size-3.5" />
                            </span>
                            <h4 class="text-sm font-semibold text-zinc-800 dark:text-zinc-200">ตัวอย่าง In-text Citation</h4>
                            <span class="rounded-full bg-violet-100 px-2 py-0.5 text-[10px] font-semibold text-violet-600 dark:bg-violet-500/10 dark:text-violet-300">LIVE</span>
                        </div>

                        <div class="space-y-2">
                            <div class="rounded-2xl border border-violet-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
                                <p class="mb-1 text-[10px] font-semibold uppercase tracking-wider text-violet-500 dark:text-violet-400">Narrative (กล่าวถึงผู้แต่ง)</p>
                                <p class="text-sm text-zinc-700 dark:text-zinc-300"
                                    x-text="generateNarrativeCitation() || '...'"
                                    x-bind:class="generateNarrativeCitation() ? '' : 'text-zinc-400 dark:text-zinc-500 italic'">
                                </p>
                            </div>
                            <div class="rounded-2xl border border-violet-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
                                <p class="mb-1 text-[10px] font-semibold uppercase tracking-wider text-violet-500 dark:text-violet-400">Parenthetical (วงเล็บท้ายประโยค)</p>
                                <p class="text-sm text-zinc-700 dark:text-zinc-300"
                                    x-text="generateParentheticalCitation() || '...'"
                                    x-bind:class="generateParentheticalCitation() ? '' : 'text-zinc-400 dark:text-zinc-500 italic'">
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- APA Format Guide --}}
                    
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="flex items-center justify-between gap-4 border-t border-zinc-200 px-6 py-4 dark:border-zinc-800 lg:px-8">
            <button type="button" x-on:click="formModalOpen = false; formResourceType = ''; resetForm()"
                class="text-sm font-medium text-zinc-500 transition hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100">
                ยกเลิก
            </button>
            <div class="flex items-center gap-3">
                <button type="button" x-on:click="formResourceType = ''; formModalOpen = false; modalOpen = true; resetForm()"
                    class="inline-flex items-center gap-2 rounded-full border border-zinc-200 px-4 py-2 text-sm font-medium text-zinc-600 transition hover:border-zinc-300 hover:text-zinc-900 dark:border-zinc-700 dark:text-zinc-300 dark:hover:border-zinc-500 dark:hover:text-zinc-100">
                    <flux:icon name="arrow-left" class="size-4" />
                    เปลี่ยนประเภท
                </button>
                <button type="button" x-on:click="addCitationFromForm()"
                    class="inline-flex items-center gap-2 rounded-full bg-pink-600 px-5 py-2 text-sm font-semibold text-white transition hover:bg-pink-700 active:scale-95 dark:bg-pink-500 dark:hover:bg-pink-400">
                    <flux:icon name="plus" class="size-4" />
                    เพิ่มรายการ
                </button>
            </div>
        </div>
    </div>
</div>
