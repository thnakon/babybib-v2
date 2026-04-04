<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head', ['title' => __('Privacy')])
</head>

<body class="min-h-screen bg-zinc-50 font-sans text-zinc-900 antialiased dark:bg-zinc-900 dark:text-zinc-100">
    <main class="mx-auto flex min-h-screen w-full max-w-3xl items-center px-6 py-16">
        <div class="w-full rounded-[2rem] border border-zinc-200 bg-white p-8 shadow-sm dark:border-zinc-800 dark:bg-zinc-950">
            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-pink-500 dark:text-pink-300">Babybib</p>
            <h1 class="mt-3 text-3xl font-semibold tracking-tight">ส่วนตัว</h1>
            <p class="mt-4 text-sm leading-7 text-zinc-600 dark:text-zinc-300">
                หน้านี้ใช้สำหรับแสดงนโยบายความเป็นส่วนตัวของระบบ Babybib ในภายหลัง ขณะนี้ยังอยู่ระหว่างการจัดเตรียมรายละเอียดอย่างเป็นทางการ
            </p>
            <a href="{{ route('citation-generator') }}" class="mt-8 inline-flex items-center gap-2 text-sm font-medium text-pink-600 transition hover:text-pink-700 dark:text-pink-300 dark:hover:text-pink-200">
                <flux:icon name="arrow-left" class="size-4" />
                กลับไปหน้าสร้างบรรณานุกรม
            </a>
        </div>
    </main>
    @fluxScripts
</body>

</html>