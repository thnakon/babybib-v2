import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/react';
import { ArrowLeft, Search, Upload, FileText, Check, AlertCircle, Loader2, Plus, X } from 'lucide-react';
import { useState } from 'react';
import { cn } from '@/lib/utils';
import { useLanguage } from '@/contexts/language-context';

// Types
interface LookupResult {
    title: string;
    authors: string[];
    type: string;
    year: string | null;
    doi: string | null;
    isbn: string | null;
    url: string | null;
    publisher: string | null;
    journal_name: string | null;
    volume: string | null;
    issue: string | null;
    pages: string | null;
    abstract: string | null;
}

interface BibtexEntry extends LookupResult {
    selected: boolean;
}

// Translations
const translations = {
    en: {
        title: "Import References",
        subtitle: "Quickly add references using DOI, ISBN, or BibTeX files",
        back: "Back to References",
        // DOI/ISBN Lookup
        lookupTitle: "DOI / ISBN Lookup",
        lookupDesc: "Enter a DOI or ISBN to automatically fetch metadata",
        lookupPlaceholder: "Enter DOI (e.g., 10.1000/xyz123) or ISBN",
        lookupButton: "Lookup",
        lookupLoading: "Searching...",
        lookupSuccess: "Metadata found! Review and import below.",
        lookupError: "Could not find metadata for this identifier.",
        importThis: "Import This Reference",
        imported: "Imported!",
        // BibTeX Import
        bibtexTitle: "BibTeX / RIS Import",
        bibtexDesc: "Paste your BibTeX content or upload a .bib file",
        bibtexPlaceholder: "@article{example,\n  title = {Example Title},\n  author = {John Doe},\n  year = {2024}\n}",
        parseButton: "Parse BibTeX",
        parseLoading: "Parsing...",
        foundEntries: "entries found",
        selectAll: "Select All",
        importSelected: "Import Selected",
        importing: "Importing...",
        // General
        or: "OR",
        types: {
            book: "Book",
            journal: "Journal",
            website: "Website",
            conference: "Conference",
            thesis: "Thesis",
            report: "Report",
            other: "Other"
        }
    },
    th: {
        title: "นำเข้ารายการอ้างอิง",
        subtitle: "เพิ่มรายการอ้างอิงอย่างรวดเร็วด้วย DOI, ISBN หรือไฟล์ BibTeX",
        back: "กลับไปยังรายการอ้างอิง",
        // DOI/ISBN Lookup
        lookupTitle: "ค้นหาจาก DOI / ISBN",
        lookupDesc: "ป้อน DOI หรือ ISBN เพื่อดึงข้อมูลอัตโนมัติ",
        lookupPlaceholder: "ป้อน DOI (เช่น 10.1000/xyz123) หรือ ISBN",
        lookupButton: "ค้นหา",
        lookupLoading: "กำลังค้นหา...",
        lookupSuccess: "พบข้อมูล! ตรวจสอบและนำเข้าด้านล่าง",
        lookupError: "ไม่พบข้อมูลสำหรับตัวระบุนี้",
        importThis: "นำเข้ารายการนี้",
        imported: "นำเข้าแล้ว!",
        // BibTeX Import
        bibtexTitle: "นำเข้า BibTeX / RIS",
        bibtexDesc: "วางเนื้อหา BibTeX หรืออัปโหลดไฟล์ .bib",
        bibtexPlaceholder: "@article{example,\n  title = {Example Title},\n  author = {John Doe},\n  year = {2024}\n}",
        parseButton: "วิเคราะห์ BibTeX",
        parseLoading: "กำลังวิเคราะห์...",
        foundEntries: "รายการที่พบ",
        selectAll: "เลือกทั้งหมด",
        importSelected: "นำเข้าที่เลือก",
        importing: "กำลังนำเข้า...",
        // General
        or: "หรือ",
        types: {
            book: "หนังสือ",
            journal: "วารสาร",
            website: "เว็บไซต์",
            conference: "การประชุม",
            thesis: "วิทยานิพนธ์",
            report: "รายงาน",
            other: "อื่นๆ"
        }
    }
};

export default function ImportPage() {
    const { language } = useLanguage();
    const t = translations[language];

    // DOI/ISBN Lookup State
    const [identifier, setIdentifier] = useState('');
    const [lookupLoading, setLookupLoading] = useState(false);
    const [lookupResult, setLookupResult] = useState<LookupResult | null>(null);
    const [lookupError, setLookupError] = useState('');
    const [importingLookup, setImportingLookup] = useState(false);
    const [lookupImported, setLookupImported] = useState(false);

    // BibTeX State
    const [bibtexContent, setBibtexContent] = useState('');
    const [parseLoading, setParseLoading] = useState(false);
    const [bibtexEntries, setBibtexEntries] = useState<BibtexEntry[]>([]);
    const [importingBibtex, setImportingBibtex] = useState(false);

    const breadcrumbs: BreadcrumbItem[] = [
        { title: language === 'en' ? 'References' : 'รายการอ้างอิง', href: '/references' },
        { title: t.title, href: '/import' },
    ];

    // DOI/ISBN Lookup Handler
    const handleLookup = async () => {
        if (!identifier.trim()) return;

        setLookupLoading(true);
        setLookupError('');
        setLookupResult(null);
        setLookupImported(false);

        try {
            const response = await fetch('/import/lookup', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                },
                body: JSON.stringify({ identifier }),
            });

            const data = await response.json();

            if (data.success) {
                setLookupResult(data.data);
            } else {
                setLookupError(t.lookupError);
            }
        } catch (error) {
            setLookupError(t.lookupError);
        } finally {
            setLookupLoading(false);
        }
    };

    // Import from Lookup Handler
    const handleImportFromLookup = async () => {
        if (!lookupResult) return;

        setImportingLookup(true);

        try {
            const response = await fetch('/import/from-lookup', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                },
                body: JSON.stringify(lookupResult),
            });

            const data = await response.json();

            if (data.success) {
                setLookupImported(true);
            }
        } catch (error) {
            console.error('Import error:', error);
        } finally {
            setImportingLookup(false);
        }
    };

    // Parse BibTeX Handler
    const handleParseBibtex = async () => {
        if (!bibtexContent.trim()) return;

        setParseLoading(true);

        try {
            const response = await fetch('/import/parse-bibtex', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                },
                body: JSON.stringify({ content: bibtexContent }),
            });

            const data = await response.json();

            if (data.success) {
                setBibtexEntries(data.entries.map((e: LookupResult) => ({ ...e, selected: true })));
            }
        } catch (error) {
            console.error('Parse error:', error);
        } finally {
            setParseLoading(false);
        }
    };

    // Toggle Entry Selection
    const toggleEntry = (index: number) => {
        const newEntries = [...bibtexEntries];
        newEntries[index].selected = !newEntries[index].selected;
        setBibtexEntries(newEntries);
    };

    // Select/Deselect All
    const toggleSelectAll = () => {
        const allSelected = bibtexEntries.every(e => e.selected);
        setBibtexEntries(bibtexEntries.map(e => ({ ...e, selected: !allSelected })));
    };

    // Import Selected BibTeX
    const handleImportBibtex = async () => {
        const selected = bibtexEntries.filter(e => e.selected);
        if (selected.length === 0) return;

        setImportingBibtex(true);

        try {
            const response = await fetch('/import/bibtex', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                },
                body: JSON.stringify({ entries: selected }),
            });

            const data = await response.json();

            if (data.success) {
                router.visit('/references');
            }
        } catch (error) {
            console.error('Import error:', error);
        } finally {
            setImportingBibtex(false);
        }
    };

    const inputClass = "h-12 w-full rounded-xl border border-gray-100 bg-white px-4 text-sm font-medium focus:border-scribehub-blue/50 focus:outline-none focus:ring-4 focus:ring-scribehub-blue/5 dark:border-gray-800 dark:bg-gray-900/50 dark:text-white";

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t.title} />

            <div className="flex h-full flex-1 flex-col gap-6 bg-scribehub-paper/50 p-6 dark:bg-[#050505]/50 animate-in fade-in duration-700">
                {/* Header */}
                <div>
                    <Link
                        href="/references"
                        className="mb-2 inline-flex items-center gap-2 text-sm font-bold text-gray-500 transition-colors hover:text-scribehub-blue dark:text-gray-400"
                    >
                        <ArrowLeft className="h-4 w-4" />
                        {t.back}
                    </Link>
                    <h1 className="text-3xl font-extrabold text-scribehub-blue dark:text-white">{t.title}</h1>
                    <p className="mt-1 text-sm font-medium text-gray-500 dark:text-gray-400">{t.subtitle}</p>
                </div>

                <div className="grid gap-6 lg:grid-cols-2">
                    {/* DOI / ISBN Lookup */}
                    <div className="rounded-3xl border border-white bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900/50">
                        <div className="mb-6 flex items-center gap-3">
                            <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-scribehub-blue/10 text-scribehub-blue">
                                <Search className="h-5 w-5" />
                            </div>
                            <div>
                                <h2 className="text-lg font-extrabold text-scribehub-blue dark:text-white">{t.lookupTitle}</h2>
                                <p className="text-xs text-gray-500 dark:text-gray-400">{t.lookupDesc}</p>
                            </div>
                        </div>

                        <div className="space-y-4">
                            <div className="flex gap-2">
                                <input
                                    type="text"
                                    value={identifier}
                                    onChange={e => setIdentifier(e.target.value)}
                                    onKeyDown={e => e.key === 'Enter' && handleLookup()}
                                    placeholder={t.lookupPlaceholder}
                                    className={inputClass}
                                />
                                <button
                                    onClick={handleLookup}
                                    disabled={lookupLoading || !identifier.trim()}
                                    className="shrink-0 rounded-xl bg-scribehub-blue px-6 text-sm font-bold text-white transition-all hover:opacity-90 disabled:opacity-50"
                                >
                                    {lookupLoading ? <Loader2 className="h-4 w-4 animate-spin" /> : t.lookupButton}
                                </button>
                            </div>

                            {lookupError && (
                                <div className="flex items-center gap-2 rounded-xl bg-red-50 p-4 text-sm text-red-600 dark:bg-red-900/20">
                                    <AlertCircle className="h-4 w-4" />
                                    {lookupError}
                                </div>
                            )}

                            {lookupResult && (
                                <div className="space-y-4 rounded-2xl border border-green-100 bg-green-50/50 p-4 dark:border-green-900/30 dark:bg-green-900/10">
                                    <div className="flex items-start gap-2 text-sm text-green-600 dark:text-green-400">
                                        <Check className="h-4 w-4 mt-0.5" />
                                        {t.lookupSuccess}
                                    </div>
                                    <div className="space-y-2">
                                        <h3 className="font-bold text-scribehub-blue dark:text-white">{lookupResult.title}</h3>
                                        <p className="text-sm text-gray-600 dark:text-gray-400">
                                            {lookupResult.authors?.join(', ') || 'Unknown Author'}
                                            {lookupResult.year && ` (${lookupResult.year})`}
                                        </p>
                                        <span className="inline-block rounded-lg bg-gray-100 px-2 py-1 text-[10px] font-bold uppercase text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                                            {t.types[lookupResult.type as keyof typeof t.types] || lookupResult.type}
                                        </span>
                                    </div>
                                    <button
                                        onClick={handleImportFromLookup}
                                        disabled={importingLookup || lookupImported}
                                        className={cn(
                                            "w-full rounded-xl py-3 text-sm font-bold transition-all",
                                            lookupImported
                                                ? "bg-green-500 text-white"
                                                : "bg-scribehub-blue text-white hover:opacity-90 disabled:opacity-50"
                                        )}
                                    >
                                        {importingLookup ? (
                                            <Loader2 className="mx-auto h-4 w-4 animate-spin" />
                                        ) : lookupImported ? (
                                            <span className="flex items-center justify-center gap-2"><Check className="h-4 w-4" /> {t.imported}</span>
                                        ) : (
                                            <span className="flex items-center justify-center gap-2"><Plus className="h-4 w-4" /> {t.importThis}</span>
                                        )}
                                    </button>
                                </div>
                            )}
                        </div>
                    </div>

                    {/* BibTeX Import */}
                    <div className="rounded-3xl border border-white bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900/50">
                        <div className="mb-6 flex items-center gap-3">
                            <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-100 text-purple-600 dark:bg-purple-900/30">
                                <FileText className="h-5 w-5" />
                            </div>
                            <div>
                                <h2 className="text-lg font-extrabold text-scribehub-blue dark:text-white">{t.bibtexTitle}</h2>
                                <p className="text-xs text-gray-500 dark:text-gray-400">{t.bibtexDesc}</p>
                            </div>
                        </div>

                        <div className="space-y-4">
                            <textarea
                                value={bibtexContent}
                                onChange={e => setBibtexContent(e.target.value)}
                                placeholder={t.bibtexPlaceholder}
                                rows={6}
                                className={cn(inputClass, "h-auto py-3 font-mono text-xs")}
                            />
                            <button
                                onClick={handleParseBibtex}
                                disabled={parseLoading || !bibtexContent.trim()}
                                className="w-full rounded-xl bg-purple-600 py-3 text-sm font-bold text-white transition-all hover:opacity-90 disabled:opacity-50"
                            >
                                {parseLoading ? <Loader2 className="mx-auto h-4 w-4 animate-spin" /> : t.parseButton}
                            </button>

                            {bibtexEntries.length > 0 && (
                                <div className="space-y-3">
                                    <div className="flex items-center justify-between">
                                        <span className="text-sm font-bold text-gray-600 dark:text-gray-400">
                                            {bibtexEntries.length} {t.foundEntries}
                                        </span>
                                        <button
                                            onClick={toggleSelectAll}
                                            className="text-xs font-bold text-scribehub-blue hover:underline"
                                        >
                                            {t.selectAll}
                                        </button>
                                    </div>

                                    <div className="max-h-48 space-y-2 overflow-y-auto">
                                        {bibtexEntries.map((entry, index) => (
                                            <div
                                                key={index}
                                                onClick={() => toggleEntry(index)}
                                                className={cn(
                                                    "flex cursor-pointer items-start gap-3 rounded-xl border p-3 transition-colors",
                                                    entry.selected
                                                        ? "border-scribehub-blue/30 bg-scribehub-blue/5"
                                                        : "border-gray-100 dark:border-gray-800"
                                                )}
                                            >
                                                <div className={cn(
                                                    "mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded border-2 transition-colors",
                                                    entry.selected ? "border-scribehub-blue bg-scribehub-blue text-white" : "border-gray-300"
                                                )}>
                                                    {entry.selected && <Check className="h-3 w-3" />}
                                                </div>
                                                <div className="min-w-0 flex-1">
                                                    <p className="truncate text-sm font-bold text-gray-800 dark:text-white">{entry.title}</p>
                                                    <p className="text-xs text-gray-500">{entry.authors?.join(', ')}</p>
                                                </div>
                                            </div>
                                        ))}
                                    </div>

                                    <button
                                        onClick={handleImportBibtex}
                                        disabled={importingBibtex || !bibtexEntries.some(e => e.selected)}
                                        className="w-full rounded-xl bg-scribehub-blue py-3 text-sm font-bold text-white transition-all hover:opacity-90 disabled:opacity-50"
                                    >
                                        {importingBibtex ? <Loader2 className="mx-auto h-4 w-4 animate-spin" /> : t.importSelected}
                                    </button>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
