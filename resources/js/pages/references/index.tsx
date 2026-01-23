import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/react';
import { Plus, Search, BookOpen, FileText, Globe, Calendar, MoreVertical, Edit, Trash2, Eye, Filter, Download } from 'lucide-react';
import { useState } from 'react';
import { cn } from '@/lib/utils';
import { useLanguage } from '@/contexts/language-context';

// Types
interface Reference {
    id: number;
    title: string;
    authors: string[] | null;
    type: string;
    year: string | null;
    doi: string | null;
    created_at: string;
}

interface PaginatedReferences {
    data: Reference[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

interface Props {
    references: PaginatedReferences;
}

// Translations
const translations = {
    en: {
        title: "My References",
        subtitle: "Manage your bibliographic references",
        addNew: "Add Reference",
        search: "Search references...",
        noReferences: "No references yet",
        noReferencesDesc: "Start building your library by adding your first reference.",
        addFirst: "Add Your First Reference",
        type: "Type",
        year: "Year",
        actions: "Actions",
        view: "View",
        edit: "Edit",
        delete: "Delete",
        confirmDelete: "Are you sure you want to delete this reference?",
        total: "Total",
        references: "references",
        export: "Export",
        exportBibtex: "Export BibTeX",
        exportRis: "Export RIS",
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
        title: "รายการอ้างอิงของฉัน",
        subtitle: "จัดการข้อมูลบรรณานุกรมของคุณ",
        addNew: "เพิ่มรายการอ้างอิง",
        search: "ค้นหารายการอ้างอิง...",
        noReferences: "ยังไม่มีรายการอ้างอิง",
        noReferencesDesc: "เริ่มต้นสร้างคลังข้อมูลของคุณโดยเพิ่มรายการอ้างอิงแรก",
        addFirst: "เพิ่มรายการอ้างอิงแรกของคุณ",
        type: "ประเภท",
        year: "ปี",
        actions: "การดำเนินการ",
        view: "ดู",
        edit: "แก้ไข",
        delete: "ลบ",
        confirmDelete: "คุณแน่ใจหรือไม่ว่าต้องการลบรายการอ้างอิงนี้?",
        total: "ทั้งหมด",
        references: "รายการ",
        export: "ส่งออก",
        exportBibtex: "ส่งออก BibTeX",
        exportRis: "ส่งออก RIS",
        types: {
            book: "หนังสือ",
            journal: "วารสาร",
            website: "เว็บไซต์",
            conference: "การประชุมวิชาการ",
            thesis: "วิทยานิพนธ์",
            report: "รายงาน",
            other: "อื่นๆ"
        }
    }
};

const typeColors: Record<string, string> = {
    book: 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
    journal: 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
    website: 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
    conference: 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
    thesis: 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400',
    report: 'bg-cyan-100 text-cyan-700 dark:bg-cyan-900/30 dark:text-cyan-400',
    other: 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400',
};

const typeIcons: Record<string, typeof BookOpen> = {
    book: BookOpen,
    journal: FileText,
    website: Globe,
    conference: Calendar,
    thesis: FileText,
    report: FileText,
    other: FileText,
};

export default function ReferencesIndex({ references }: Props) {
    const { language } = useLanguage();
    const [searchQuery, setSearchQuery] = useState('');
    const [openMenu, setOpenMenu] = useState<number | null>(null);
    const [exportOpen, setExportOpen] = useState(false);
    const t = translations[language];

    const breadcrumbs: BreadcrumbItem[] = [
        { title: t.title, href: '/references' },
    ];

    const formatAuthors = (authors: string[] | null): string => {
        if (!authors || authors.length === 0) return 'Unknown Author';
        if (authors.length === 1) return authors[0];
        if (authors.length === 2) return `${authors[0]} & ${authors[1]}`;
        return `${authors[0]} et al.`;
    };

    const handleDelete = (id: number) => {
        if (confirm(t.confirmDelete)) {
            router.delete(`/references/${id}`);
        }
    };

    const handleExport = (format: 'bibtex' | 'ris') => {
        window.location.href = `/export/all/${format}`;
        setExportOpen(false);
    };

    const filteredReferences = references.data.filter(ref =>
        ref.title.toLowerCase().includes(searchQuery.toLowerCase()) ||
        (ref.authors && ref.authors.some(a => a.toLowerCase().includes(searchQuery.toLowerCase())))
    );

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t.title} />

            <div className="flex h-full flex-1 flex-col gap-6 bg-scribehub-paper/50 p-6 dark:bg-[#050505]/50 animate-in fade-in duration-700">
                {/* Header */}
                <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 className="text-3xl font-extrabold text-scribehub-blue dark:text-white">{t.title}</h1>
                        <p className="mt-1 text-sm font-medium text-gray-500 dark:text-gray-400">{t.subtitle}</p>
                    </div>
                    <div className="flex items-center gap-2">
                        {/* Export Button */}
                        <div className="relative">
                            <button
                                onClick={() => setExportOpen(!exportOpen)}
                                className="inline-flex items-center gap-2 rounded-xl border border-gray-200 px-4 py-3 text-sm font-bold text-gray-600 transition-colors hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800"
                            >
                                <Download className="h-4 w-4" />
                                {t.export}
                            </button>
                            {exportOpen && (
                                <div className="absolute right-0 top-full z-20 mt-1 w-44 rounded-xl border border-gray-100 bg-white py-2 shadow-xl dark:border-gray-800 dark:bg-gray-900">
                                    <button
                                        onClick={() => handleExport('bibtex')}
                                        className="flex w-full items-center gap-3 px-4 py-2 text-xs font-bold text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-800"
                                    >
                                        <FileText className="h-4 w-4" />
                                        {t.exportBibtex}
                                    </button>
                                    <button
                                        onClick={() => handleExport('ris')}
                                        className="flex w-full items-center gap-3 px-4 py-2 text-xs font-bold text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-800"
                                    >
                                        <FileText className="h-4 w-4" />
                                        {t.exportRis}
                                    </button>
                                </div>
                            )}
                        </div>
                        <Link
                            href="/references/create"
                            className="inline-flex items-center gap-2 rounded-xl bg-scribehub-blue px-6 py-3 text-sm font-bold text-white shadow-lg shadow-blue-900/20 transition-all hover:opacity-90 active:scale-95"
                        >
                            <Plus className="h-4 w-4" />
                            {t.addNew}
                        </Link>
                    </div>
                </div>

                {/* Search & Filter Bar */}
                <div className="flex flex-col gap-4 md:flex-row md:items-center">
                    <div className="relative flex-1">
                        <Search className="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" />
                        <input
                            type="text"
                            placeholder={t.search}
                            value={searchQuery}
                            onChange={(e) => setSearchQuery(e.target.value)}
                            className="h-11 w-full rounded-xl border border-gray-100 bg-white pl-11 pr-4 text-sm font-medium focus:border-scribehub-blue/50 focus:outline-none focus:ring-4 focus:ring-scribehub-blue/5 dark:border-gray-800 dark:bg-gray-900/50"
                        />
                    </div>
                    <div className="flex items-center gap-2">
                        <span className="text-xs font-bold text-gray-400">{t.total}: {references.total} {t.references}</span>
                    </div>
                </div>

                {/* References List */}
                {filteredReferences.length === 0 ? (
                    <div className="flex flex-1 flex-col items-center justify-center rounded-3xl border border-dashed border-gray-200 bg-white p-12 dark:border-gray-800 dark:bg-gray-900/30">
                        <div className="mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-gray-50 dark:bg-gray-800">
                            <BookOpen className="h-10 w-10 text-gray-300 dark:text-gray-600" />
                        </div>
                        <h3 className="text-lg font-bold text-scribehub-blue dark:text-white">{t.noReferences}</h3>
                        <p className="mt-2 max-w-sm text-center text-sm text-gray-500 dark:text-gray-400">{t.noReferencesDesc}</p>
                        <Link
                            href="/references/create"
                            className="mt-6 inline-flex items-center gap-2 rounded-xl bg-scribehub-blue px-6 py-3 text-sm font-bold text-white shadow-lg transition-all hover:opacity-90"
                        >
                            <Plus className="h-4 w-4" />
                            {t.addFirst}
                        </Link>
                    </div>
                ) : (
                    <div className="space-y-3">
                        {filteredReferences.map((ref) => {
                            const TypeIcon = typeIcons[ref.type] || FileText;
                            return (
                                <div
                                    key={ref.id}
                                    className="group relative flex items-start gap-4 rounded-2xl border border-white bg-white p-5 shadow-sm transition-all hover:shadow-md dark:border-gray-800 dark:bg-gray-900/50"
                                >
                                    {/* Type Icon */}
                                    <div className={cn("flex h-12 w-12 shrink-0 items-center justify-center rounded-xl", typeColors[ref.type])}>
                                        <TypeIcon className="h-5 w-5" />
                                    </div>

                                    {/* Content */}
                                    <div className="min-w-0 flex-1">
                                        <Link href={`/references/${ref.id}`} className="block">
                                            <h3 className="truncate text-base font-bold text-scribehub-blue transition-colors group-hover:text-scribehub-blue/80 dark:text-white">
                                                {ref.title}
                                            </h3>
                                        </Link>
                                        <p className="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            {formatAuthors(ref.authors)}
                                            {ref.year && <span className="ml-2">({ref.year})</span>}
                                        </p>
                                        <div className="mt-2 flex items-center gap-2">
                                            <span className={cn("inline-flex items-center rounded-lg px-2 py-1 text-[10px] font-bold uppercase", typeColors[ref.type])}>
                                                {t.types[ref.type as keyof typeof t.types] || ref.type}
                                            </span>
                                            {ref.doi && (
                                                <span className="text-[10px] font-medium text-gray-400">DOI: {ref.doi}</span>
                                            )}
                                        </div>
                                    </div>

                                    {/* Actions */}
                                    <div className="relative">
                                        <button
                                            onClick={() => setOpenMenu(openMenu === ref.id ? null : ref.id)}
                                            className="flex h-9 w-9 items-center justify-center rounded-lg text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800"
                                        >
                                            <MoreVertical className="h-4 w-4" />
                                        </button>
                                        {openMenu === ref.id && (
                                            <div className="absolute right-0 top-full z-10 mt-1 w-40 rounded-xl border border-gray-100 bg-white py-2 shadow-xl dark:border-gray-800 dark:bg-gray-900">
                                                <Link
                                                    href={`/references/${ref.id}`}
                                                    className="flex w-full items-center gap-3 px-4 py-2 text-xs font-bold text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-800"
                                                >
                                                    <Eye className="h-4 w-4" /> {t.view}
                                                </Link>
                                                <Link
                                                    href={`/references/${ref.id}/edit`}
                                                    className="flex w-full items-center gap-3 px-4 py-2 text-xs font-bold text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-800"
                                                >
                                                    <Edit className="h-4 w-4" /> {t.edit}
                                                </Link>
                                                <button
                                                    onClick={() => handleDelete(ref.id)}
                                                    className="flex w-full items-center gap-3 px-4 py-2 text-xs font-bold text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20"
                                                >
                                                    <Trash2 className="h-4 w-4" /> {t.delete}
                                                </button>
                                            </div>
                                        )}
                                    </div>
                                </div>
                            );
                        })}
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
