<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head', ['title' => __('About Babybib')])
</head>

<body class="min-h-screen bg-gradient-to-br from-slate-50 via-indigo-50/40 to-sky-50 font-sans text-zinc-900 antialiased dark:from-slate-950 dark:via-indigo-950/20 dark:to-slate-950 dark:text-zinc-100">
    <main class="mx-auto flex min-h-screen w-full max-w-3xl items-center px-6 py-16">
        <div class="w-full rounded-[2rem] border border-sky-100 bg-white/90 p-8 shadow-xl shadow-indigo-100/50 backdrop-blur-sm dark:border-slate-800 dark:bg-slate-950/90 dark:shadow-none">
            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-indigo-500 dark:text-sky-300">Babybib</p>
            <h1 class="mt-3 text-3xl font-semibold tracking-tight">เกี่ยวกับ</h1>
            <p class="mt-4 text-sm leading-7 text-zinc-600 dark:text-zinc-300">
                Babybib คือเครื่องมือช่วยจัดรูปแบบบรรณานุกรมและ citation ให้ทำงานได้เร็วขึ้นสำหรับผู้ใช้ที่ต้องการอ้างอิงงานวิชาการในรูปแบบที่ชัดเจนและใช้งานง่าย
            </p>
            <a href="{{ route('citation-generator') }}" class="mt-8 inline-flex items-center gap-2 text-sm font-medium text-indigo-600 transition hover:text-indigo-700 dark:text-sky-300 dark:hover:text-sky-200">
                <flux:icon name="arrow-left" class="size-4" />
                กลับไปหน้าสร้างบรรณานุกรม
            </a>
        </div>
    </main>
    @fluxScripts
</body>

</html>