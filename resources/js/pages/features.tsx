import { login } from '@/routes';
import type { SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';
import { 
    MoveRight, Zap, Layout, Sparkles, Users, 
    Check, Mail, Languages, Moon, Sun, 
    Monitor, BookOpen, ShieldCheck, ChevronRight,
    Brain, Smartphone, Cloud, Globe, Library,
    Search, MousePointer2, FileText, Share2, 
    BarChart3, Layers, Lock, Archive
} from 'lucide-react';
import AppLogo from '@/components/app-logo';
import { useState } from 'react';
import { useAppearance } from '@/hooks/use-appearance';
import { cn } from '@/lib/utils';

const translations = {
    en: {
        title: "ScribeHub Features - The Research Operating System",
        tagline: "Everything you need to master your research",
        subtitle: "A comprehensive toolset designed for modern academic workflows. From initial discovery to final publication.",
        startResearch: "Start Writing Free",
        backToHome: "Back to Home",
        categories: [
            {
                title: "Core Workspace",
                icon: Layout,
                features: [
                    { name: "Integrated Split-View Editor", desc: "Read your research PDFs on the left while drafting your paper on the right. No more context switching." },
                    { name: "Magic Highlight", desc: "Highlight any text in your PDF to instantly create a note that carries the full citation metadata." },
                    { name: "Focus Mode", desc: "A distraction-free writing environment that keeps you in the flow of your research." },
                    { name: "Smart Cite-as-you-write", desc: "Simply type '@' to find and insert any reference from your library in real-time." }
                ]
            },
            {
                title: "Reference Management",
                icon: Archive,
                features: [
                    { name: "Instant DOI/ISBN Import", desc: "Paste a link or ID, and ScribeHub fetches perfectly formatted metadata automatically." },
                    { name: "Global Citation Engine", desc: "Support for 10,000+ styles including APA 7, MLA 9, IEEE, and Chicago." },
                    { name: "Thai University Standards", desc: "Pre-configured styles for CU, TU, MU, KU, and more, verified by academic librarians." },
                    { name: "Bulk RIS/BibTeX Migration", desc: "Moving from Zotero or Mendeley? Import your entire library in seconds." }
                ]
            },
            {
                title: "AI & Intelligence",
                icon: Brain,
                features: [
                    { name: "AI Reference Discovery", desc: "Our AI analyzes your draft and suggests relevant articles you might have missed." },
                    { name: "PDF Intelligent Summary", desc: "Get the core hypothesis, methodology, and results of any PDF in one click." },
                    { name: "Academic Tone Refiner", desc: "Polish your English to meet international journal standards with our AI-powered style engine." },
                    { name: "Knowledge Graph", desc: "Visualize how your sources are connected through authors, citations, and themes." }
                ]
            },
            {
                title: "Collaboration & Sync",
                icon: Users,
                features: [
                    { name: "Real-time Co-authoring", desc: "Work together on the same document and bibliography with your research team." },
                    { name: "Cloud Library Sync", desc: "Your research follows you everywhere. Start on a desktop, continue on an iPad." },
                    { name: "Shared Folders", desc: "Organize sources for group projects with granular permission controls." },
                    { name: "Smart Version Control", desc: "Never lose a draft. Track changes and revert to previous research states easily." }
                ]
            }
        ],
        ctaTitle: "Ready to upgrade your workflow?",
        ctaButton: "Get Started for Free"
    },
    th: {
        title: "ฟีเจอร์ของ ScribeHub - ระบบปฏิบัติการเพื่องานวิจัย",
        tagline: "ทุกสิ่งที่คุณต้องการเพื่อจัดการงานวิจัยให้ทรงพลัง",
        subtitle: "ชุดเครื่องมือที่สมบูรณ์แบบที่ถูกออกแบบมาเพื่อกระบวนการทำงานวิชาการยุคใหม่ ตั้งแต่การค้นคว้าไปจนถึงการตีพิมพ์",
        startResearch: "เริ่มต้นใช้งานฟรี",
        backToHome: "กลับหน้าหลัก",
        categories: [
            {
                title: "พื้นที่ทำงานหลัก (Core Workspace)",
                icon: Layout,
                features: [
                    { name: "Integrated Split-View Editor", desc: "อ่านไฟล์ PDF งานวิจัยที่ฝั่งซ้าย พร้อมพิมพ์งานที่ฝั่งขวาในหน้าจอเดียว ไม่ต้องสลับแท็บไปมา" },
                    { name: "Magic Highlight", desc: "ไฮไลท์ข้อความใน PDF เพื่อสร้างโน้ตอ้างอิงอัตโนมัติที่เก็บข้อมูลแหล่งที่มาอย่างครบถ้วน" },
                    { name: "Focus Mode", desc: "โหมดการเขียนที่ปราศจากการรบกวน ช่วยให้คุณจดจ่อกับเนื้อหางานวิจัยได้อย่างเต็มที่" },
                    { name: "Smart Cite-as-you-write", desc: "พิมพ์ '@' เพื่อค้นหาและแทรกการอ้างอิงจากคลังของคุณเข้าสู่บทความได้ทันทีแบบ Real-time" }
                ]
            },
            {
                title: "การจัดการแหล่งอ้างอิง (Reference Management)",
                icon: Archive,
                features: [
                    { name: "ดึงข้อมูลจาก DOI/ISBN ทันที", desc: "เพียงวางลิงก์หรือรหัส ระบบจะดึงข้อมูลเมตามาจัดรูปแบบให้อย่างสมบูรณ์แบบอัตโนมัติ" },
                    { name: "Global Citation Engine", desc: "รองรับรูปแบบการอ้างอิงกว่า 10,000 สไตล์ รวมถึง APA 7, MLA 9, IEEE และ Chicago" },
                    { name: "รูปแบบเฉพาะมหาวิทยาลัยไทย", desc: "สไตล์การอ้างอิงของ จุฬาฯ, ธรรมศาสตร์, มหิดล, เกษตรฯ และอื่นๆ ที่ตรวจสอบโดยบรรณารักษ์" },
                    { name: "ย้ายข้อมูล RIS/BibTeX ได้รวดเร็ว", desc: "ย้ายจาก Zotero หรือ Mendeley ได้ในไม่กี่วินาทีด้วยการนำเข้าไฟล์มาตรฐาน" }
                ]
            },
            {
                title: "ระบบอัจฉริยะ & AI",
                icon: Brain,
                features: [
                    { name: "AI ค้นหาแหล่งอ้างอิง", desc: "AI จะวิเคราะห์งานเขียนของคุณและแนะนำบทความที่เกี่ยวข้องที่คุณอาจมองข้ามไป" },
                    { name: "สรุปเนื้อหา PDF อัจฉริยะ", desc: "รับสรุปสมมติฐาน วิธีการวิจัย และผลลัพธ์ของ PDF ใดๆ ได้ในคลิกเดียว" },
                    { name: "ขัดเกลาสำนวนภาษาอังกฤษวิชาการ", desc: "ปรับปรุงภาษาอังกฤษของคุณให้ได้มาตรฐานวารสารนานาชาติด้วยระบบ AI Style Engine" },
                    { name: "Knowledge Graph", desc: "เห็นภาพรวมความเชื่อมโยงของแหล่งข้อมูลผ่านเครือข่ายผู้แต่ง การอ้างอิง และหัวข้อ" }
                ]
            },
            {
                title: "การทำงานร่วมกัน & การซิงค์",
                icon: Users,
                features: [
                    { name: "เขียนงานร่วมกันแบบ Real-time", desc: "ทำงานในเอกสารและจัดการบรรณานุกรมร่วมกับทีมวิจัยของคุณได้พร้อมกัน" },
                    { name: "Cloud Library Sync", desc: "คลังงานวิจัยตามคุณไปทุกที่ เริ่มต้นบนคอมพิวเตอร์ และทำต่อบน iPad ได้อย่างไร้รอยต่อ" },
                    { name: "โฟลเดอร์โครงการร่วม", desc: "จัดระเบียบแหล่งข้อมูลสำหรับโปรเจกต์กลุ่มพร้อมระบบควบคุมสิทธิ์การเข้าถึง" },
                    { name: "ระบบควบคุมเวอร์ชัน (Version Control)", desc: "ไม่ต้องกลัวงานหาย ติดตามการเปลี่ยนแปลงและย้อนกลับไปยังเวอร์ชันก่อนหน้าได้ง่ายดาย" }
                ]
            }
        ],
        ctaTitle: "พร้อมอัปเกรดกระบวนการทำงานของคุณหรือยัง?",
        ctaButton: "เริ่มต้นใช้งานฟรีวันนี้"
    }
};

export default function Features() {
    const [lang, setLang] = useState<'en' | 'th'>('en');
    const { appearance, updateAppearance } = useAppearance();
    const t = translations[lang];

    return (
        <div className="min-h-screen bg-scribehub-paper font-overpass text-scribehub-grey antialiased dark:bg-[#0a0a0a] dark:text-gray-300">
            <Head title={t.title} />

            {/* Header */}
            <header className="sticky top-0 z-50 w-full border-b border-scribehub-border bg-white/80 backdrop-blur-md dark:border-gray-800 dark:bg-black/80">
                <div className="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
                    <div className="flex items-center gap-8">
                        <Link href="/" className="flex items-center gap-2 hover:opacity-80 transition-opacity">
                            <ArrowLeft className="h-5 w-5 text-scribehub-blue" />
                            <span className="text-sm font-bold text-scribehub-blue">{t.backToHome}</span>
                        </Link>
                    </div>
                    
                    <div className="flex items-center gap-4">
                        <div className="group relative">
                            <button className="flex h-9 w-9 items-center justify-center rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"><Languages className="h-5 w-5" /></button>
                            <div className="absolute right-0 top-full hidden w-32 origin-top-right rounded-xl border border-gray-100 bg-white py-2 shadow-xl ring-1 ring-black ring-opacity-5 group-hover:block dark:border-gray-800 dark:bg-gray-900 z-[60]">
                                <button onClick={() => setLang('en')} className={cn("flex w-full items-center justify-between px-4 py-2 text-xs font-bold", lang === 'en' ? "bg-gray-50 text-scribehub-blue dark:bg-gray-800" : "text-gray-700 dark:text-gray-300")}>English {lang === 'en' && <Check className="h-3 w-3" />}</button>
                                <button onClick={() => setLang('th')} className={cn("flex w-full items-center justify-between px-4 py-2 text-xs font-bold", lang === 'th' ? "bg-gray-50 text-scribehub-blue dark:bg-gray-800" : "text-gray-700 dark:text-gray-300")}>ภาษาไทย {lang === 'th' && <Check className="h-3 w-3" />}</button>
                            </div>
                        </div>
                        <div className="group relative">
                            <button className="flex h-9 w-9 items-center justify-center rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">{appearance === 'dark' ? <Moon className="h-5 w-5" /> : appearance === 'light' ? <Sun className="h-5 w-5" /> : <Monitor className="h-5 w-5" />}</button>
                            <div className="absolute right-0 top-full hidden w-32 origin-top-right rounded-xl border border-gray-100 bg-white py-2 shadow-xl ring-1 ring-black ring-opacity-5 group-hover:block dark:border-gray-800 dark:bg-gray-900 z-[60]">
                                <button onClick={() => updateAppearance('light')} className={cn("flex w-full items-center justify-between px-4 py-2 text-xs font-bold", appearance === 'light' ? "bg-gray-50 text-scribehub-blue dark:bg-gray-800" : "text-gray-700 dark:text-gray-300")}>Light {appearance === 'light' && <Check className="h-3 w-3" />}</button>
                                <button onClick={() => updateAppearance('dark')} className={cn("flex w-full items-center justify-between px-4 py-2 text-xs font-bold", appearance === 'dark' ? "bg-gray-50 text-scribehub-blue dark:bg-gray-800" : "text-gray-700 dark:text-gray-300")}>Dark {appearance === 'dark' && <Check className="h-3 w-3" />}</button>
                                <button onClick={() => updateAppearance('system')} className={cn("flex w-full items-center justify-between px-4 py-2 text-xs font-bold", appearance === 'system' ? "bg-gray-50 text-scribehub-blue dark:bg-gray-800" : "text-gray-700 dark:text-gray-300")}>System {appearance === 'system' && <Check className="h-3 w-3" />}</button>
                            </div>
                        </div>
                        <Link href={login()} className="rounded-full bg-scribehub-blue px-6 py-2 text-sm font-bold text-white hover:bg-opacity-90 transition-all shadow-md">{t.startResearch}</Link>
                    </div>
                </div>
            </header>

            <main className="pb-24">
                {/* Hero Section */}
                <section className="relative overflow-hidden pt-24 pb-16 text-center">
                    <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div className="mb-8 inline-flex items-center rounded-full bg-scribehub-blue/10 px-4 py-1.5 text-xs font-bold tracking-wider text-scribehub-blue ring-1 ring-scribehub-blue/20">FEATURES EXPLORER</div>
                        <h1 className="mb-6 mx-auto max-w-4xl text-5xl font-extrabold tracking-tight text-scribehub-blue sm:text-6xl dark:text-white">
                            {t.tagline}
                        </h1>
                        <p className="mx-auto mb-12 max-w-2xl text-lg font-medium text-scribehub-grey/70 dark:text-gray-400">
                            {t.subtitle}
                        </p>
                    </div>
                </section>

                {/* Feature Grid */}
                <section className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div className="space-y-24">
                        {t.categories.map((category, idx) => (
                            <div key={idx} className="animate-in fade-in slide-in-from-bottom-10 duration-700" style={{ animationDelay: `${idx * 150}ms` }}>
                                <div className="mb-12 flex items-center gap-4">
                                    <div className="flex h-12 w-12 items-center justify-center rounded-2xl bg-white border border-gray-100 text-scribehub-blue shadow-sm dark:bg-gray-900 dark:border-gray-800">
                                        <category.icon className="h-6 w-6" />
                                    </div>
                                    <h2 className="text-3xl font-bold text-scribehub-blue dark:text-white">{category.title}</h2>
                                    <div className="h-px flex-1 bg-gradient-to-r from-gray-200 to-transparent dark:from-gray-800"></div>
                                </div>
                                <div className="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4">
                                    {category.features.map((feature, fIdx) => (
                                        <div key={fIdx} className="group flex flex-col rounded-3xl border border-gray-100 bg-white p-8 transition-all hover:bg-scribehub-paper hover:shadow-xl hover:-translate-y-1 dark:border-gray-800 dark:bg-gray-900 dark:hover:bg-gray-800/50">
                                            <div className="mb-6 flex h-10 w-10 items-center justify-center rounded-xl bg-gray-50 text-scribehub-blue transition-colors group-hover:bg-white dark:bg-gray-800">
                                                <div className="h-1.5 w-1.5 rounded-full bg-scribehub-blue ring-4 ring-scribehub-blue/10 animate-pulse"></div>
                                            </div>
                                            <h3 className="mb-3 text-lg font-bold text-scribehub-blue dark:text-white transition-colors group-hover:text-scribehub-blue">{feature.name}</h3>
                                            <p className="text-sm font-medium leading-relaxed text-scribehub-grey/70 dark:text-gray-400">{feature.desc}</p>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        ))}
                    </div>
                </section>

                {/* Final CTA */}
                <section className="mx-auto mt-32 max-w-5xl px-4 text-center sm:px-6">
                    <div className="rounded-[3rem] bg-scribehub-blue px-8 py-20 text-white shadow-2xl relative overflow-hidden">
                        <div className="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
                        <div className="absolute bottom-0 left-0 translate-y-1/2 -translate-x-1/2 h-64 w-64 rounded-full bg-scribehub-mint/20 blur-3xl"></div>
                        
                        <div className="relative z-10">
                            <h2 className="mb-6 text-4xl font-extrabold sm:text-5xl tracking-tight">{t.ctaTitle}</h2>
                            <div className="flex flex-col items-center gap-4 sm:flex-row justify-center mt-12">
                                <Link href={login()} className="group inline-flex items-center justify-center rounded-full bg-white px-10 py-5 text-base font-bold text-scribehub-blue shadow-xl transition-all hover:scale-105 hover:shadow-2xl active:scale-95">
                                    {t.ctaButton}
                                    <MoveRight className="ml-2 h-5 w-5 transition-transform group-hover:translate-x-1" />
                                </Link>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    );
}

// Re-using icon because of import
function ArrowLeft(props: any) {
    return (
        <svg
            {...props}
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="24"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            strokeWidth="2"
            strokeLinecap="round"
            strokeLinejoin="round"
        >
            <path d="m12 19-7-7 7-7" />
            <path d="M19 12H5" />
        </svg>
    )
}
