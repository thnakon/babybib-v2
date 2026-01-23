import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/react';
import { ArrowLeft, Edit, Trash2, ExternalLink, BookOpen, FileText, Globe, Calendar, Copy, Check } from 'lucide-react';
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
    isbn: string | null;
    url: string | null;
    publisher: string | null;
    journal_name: string | null;
    volume: string | null;
    issue: string | null;
    pages: string | null;
    edition: string | null;
    abstract: string | null;
    notes: string | null;
    created_at: string;
    updated_at: string;
}

interface Props {
    reference: Reference;
}

// Translations
const translations = {
    en: {
        back: "Back to References",
        edit: "Edit",
        delete: "Delete",
        confirmDelete: "Are you sure you want to delete this reference?",
        copyCitation: "Copy Citation",
        copied: "Copied!",
        // Sections
        basicInfo: "Basic Information",
        identifiers: "Identifiers",
        publication: "Publication Details",
        additional: "Additional Information",
        // Labels
        authors: "Authors",
        type: "Type",
        year: "Year",
        doi: "DOI",
        isbn: "ISBN",
        url: "URL",
        publisher: "Publisher",
        journal: "Journal",
        volume: "Volume",
        issue: "Issue",
        pages: "Pages",
        edition: "Edition",
        abstract: "Abstract",
        notes: "Notes",
        createdAt: "Added on",
        updatedAt: "Last updated",
        noData: "Not specified",
        types: {
            book: "Book",
            journal: "Journal Article",
            website: "Website",
            conference: "Conference Paper",
            thesis: "Thesis/Dissertation",
            report: "Report",
            other: "Other"
        }
    },
    th: {
        back: "กลับไปยังรายการอ้างอิง",
        edit: "แก้ไข",
        delete: "ลบ",
        confirmDelete: "คุณแน่ใจหรือไม่ว่าต้องการลบรายการอ้างอิงนี้?",
        copyCitation: "คัดลอกการอ้างอิง",
        copied: "คัดลอกแล้ว!",
        // Sections
        basicInfo: "ข้อมูลพื้นฐาน",
        identifiers: "ตัวระบุ",
        publication: "รายละเอียดการตีพิมพ์",
        additional: "ข้อมูลเพิ่มเติม",
        // Labels
        authors: "ผู้แต่ง",
        type: "ประเภท",
        year: "ปี",
        doi: "DOI",
        isbn: "ISBN",
        url: "URL",
        publisher: "สำนักพิมพ์",
        journal: "วารสาร",
        volume: "เล่มที่",
        issue: "ฉบับที่",
        pages: "หน้า",
        edition: "ครั้งที่พิมพ์",
        abstract: "บทคัดย่อ",
        notes: "บันทึก",
        createdAt: "เพิ่มเมื่อ",
        updatedAt: "แก้ไขล่าสุด",
        noData: "ไม่ระบุ",
        types: {
            book: "หนังสือ",
            journal: "บทความวารสาร",
            website: "เว็บไซต์",
            conference: "บทความการประชุมวิชาการ",
            thesis: "วิทยานิพนธ์/ดุษฎีนิพนธ์",
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

export default function ReferencesShow({ reference }: Props) {
    const { language } = useLanguage();
    const [copied, setCopied] = useState(false);
    const t = translations[language];
    const TypeIcon = typeIcons[reference.type] || FileText;

    const breadcrumbs: BreadcrumbItem[] = [
        { title: language === 'en' ? 'References' : 'รายการอ้างอิง', href: '/references' },
        { title: reference.title.substring(0, 30) + (reference.title.length > 30 ? '...' : ''), href: `/references/${reference.id}` },
    ];

    const formatAuthors = (authors: string[] | null): string => {
        if (!authors || authors.length === 0) return t.noData;
        return authors.join(', ');
    };

    const handleDelete = () => {
        if (confirm(t.confirmDelete)) {
            router.delete(`/references/${reference.id}`);
        }
    };

    const handleCopyCitation = () => {
        // Simple APA-style citation
        const authors = reference.authors?.join(', ') || 'Unknown';
        const year = reference.year || 'n.d.';
        const title = reference.title;
        const citation = `${authors} (${year}). ${title}.`;
        
        navigator.clipboard.writeText(citation);
        setCopied(true);
        setTimeout(() => setCopied(false), 2000);
    };

    const formatDate = (date: string) => {
        return new Date(date).toLocaleDateString(language === 'th' ? 'th-TH' : 'en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    };

    const InfoRow = ({ label, value, isLink = false }: { label: string; value: string | null; isLink?: boolean }) => (
        <div className="flex flex-col gap-1 py-3 md:flex-row md:items-start">
            <span className="w-32 shrink-0 text-xs font-bold uppercase tracking-wider text-gray-400">{label}</span>
            {isLink && value ? (
                <a
                    href={value.startsWith('http') ? value : `https://doi.org/${value}`}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="flex items-center gap-1 text-sm font-medium text-scribehub-blue hover:underline"
                >
                    {value}
                    <ExternalLink className="h-3 w-3" />
                </a>
            ) : (
                <span className="text-sm font-medium text-gray-700 dark:text-gray-300">{value || t.noData}</span>
            )}
        </div>
    );

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={reference.title} />

            <div className="flex h-full flex-1 flex-col gap-6 bg-scribehub-paper/50 p-6 dark:bg-[#050505]/50 animate-in fade-in duration-700">
                {/* Header */}
                <div className="flex flex-col gap-4">
                    <Link
                        href="/references"
                        className="inline-flex w-fit items-center gap-2 text-sm font-bold text-gray-500 transition-colors hover:text-scribehub-blue dark:text-gray-400"
                    >
                        <ArrowLeft className="h-4 w-4" />
                        {t.back}
                    </Link>

                    <div className="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                        <div className="flex items-start gap-4">
                            <div className={cn("flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl", typeColors[reference.type])}>
                                <TypeIcon className="h-6 w-6" />
                            </div>
                            <div>
                                <h1 className="text-2xl font-extrabold text-scribehub-blue dark:text-white">{reference.title}</h1>
                                <p className="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    {formatAuthors(reference.authors)}
                                    {reference.year && <span className="ml-2">({reference.year})</span>}
                                </p>
                                <span className={cn("mt-2 inline-flex items-center rounded-lg px-2 py-1 text-[10px] font-bold uppercase", typeColors[reference.type])}>
                                    {t.types[reference.type as keyof typeof t.types] || reference.type}
                                </span>
                            </div>
                        </div>

                        <div className="flex items-center gap-2">
                            <button
                                onClick={handleCopyCitation}
                                className="inline-flex items-center gap-2 rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-bold text-gray-600 transition-colors hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800"
                            >
                                {copied ? <Check className="h-4 w-4 text-green-500" /> : <Copy className="h-4 w-4" />}
                                {copied ? t.copied : t.copyCitation}
                            </button>
                            <Link
                                href={`/references/${reference.id}/edit`}
                                className="inline-flex items-center gap-2 rounded-xl bg-scribehub-blue px-4 py-2.5 text-sm font-bold text-white transition-all hover:opacity-90"
                            >
                                <Edit className="h-4 w-4" />
                                {t.edit}
                            </Link>
                            <button
                                onClick={handleDelete}
                                className="inline-flex items-center gap-2 rounded-xl border border-red-200 px-4 py-2.5 text-sm font-bold text-red-600 transition-colors hover:bg-red-50 dark:border-red-900/30 dark:hover:bg-red-900/20"
                            >
                                <Trash2 className="h-4 w-4" />
                                {t.delete}
                            </button>
                        </div>
                    </div>
                </div>

                {/* Content */}
                <div className="grid gap-6 lg:grid-cols-3">
                    {/* Main Info */}
                    <div className="space-y-6 lg:col-span-2">
                        {/* Basic Information */}
                        <div className="rounded-3xl border border-white bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900/50">
                            <h2 className="mb-4 text-lg font-extrabold text-scribehub-blue dark:text-white">{t.basicInfo}</h2>
                            <div className="divide-y divide-gray-100 dark:divide-gray-800">
                                <InfoRow label={t.authors} value={formatAuthors(reference.authors)} />
                                <InfoRow label={t.year} value={reference.year} />
                                <InfoRow label={t.type} value={t.types[reference.type as keyof typeof t.types] || reference.type} />
                            </div>
                        </div>

                        {/* Identifiers */}
                        {(reference.doi || reference.isbn || reference.url) && (
                            <div className="rounded-3xl border border-white bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900/50">
                                <h2 className="mb-4 text-lg font-extrabold text-scribehub-blue dark:text-white">{t.identifiers}</h2>
                                <div className="divide-y divide-gray-100 dark:divide-gray-800">
                                    {reference.doi && <InfoRow label={t.doi} value={reference.doi} isLink />}
                                    {reference.isbn && <InfoRow label={t.isbn} value={reference.isbn} />}
                                    {reference.url && <InfoRow label={t.url} value={reference.url} isLink />}
                                </div>
                            </div>
                        )}

                        {/* Publication Details */}
                        {(reference.publisher || reference.journal_name || reference.volume || reference.pages) && (
                            <div className="rounded-3xl border border-white bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900/50">
                                <h2 className="mb-4 text-lg font-extrabold text-scribehub-blue dark:text-white">{t.publication}</h2>
                                <div className="divide-y divide-gray-100 dark:divide-gray-800">
                                    {reference.publisher && <InfoRow label={t.publisher} value={reference.publisher} />}
                                    {reference.journal_name && <InfoRow label={t.journal} value={reference.journal_name} />}
                                    {reference.volume && <InfoRow label={t.volume} value={reference.volume} />}
                                    {reference.issue && <InfoRow label={t.issue} value={reference.issue} />}
                                    {reference.pages && <InfoRow label={t.pages} value={reference.pages} />}
                                    {reference.edition && <InfoRow label={t.edition} value={reference.edition} />}
                                </div>
                            </div>
                        )}

                        {/* Abstract */}
                        {reference.abstract && (
                            <div className="rounded-3xl border border-white bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900/50">
                                <h2 className="mb-4 text-lg font-extrabold text-scribehub-blue dark:text-white">{t.abstract}</h2>
                                <p className="text-sm leading-relaxed text-gray-700 dark:text-gray-300">{reference.abstract}</p>
                            </div>
                        )}
                    </div>

                    {/* Sidebar */}
                    <div className="space-y-6">
                        {/* Notes */}
                        {reference.notes && (
                            <div className="rounded-3xl border border-white bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900/50">
                                <h2 className="mb-4 text-lg font-extrabold text-scribehub-blue dark:text-white">{t.notes}</h2>
                                <p className="text-sm leading-relaxed text-gray-700 dark:text-gray-300">{reference.notes}</p>
                            </div>
                        )}

                        {/* Meta */}
                        <div className="rounded-3xl border border-white bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900/50">
                            <div className="space-y-4 text-sm">
                                <div>
                                    <span className="text-xs font-bold uppercase tracking-wider text-gray-400">{t.createdAt}</span>
                                    <p className="mt-1 font-medium text-gray-700 dark:text-gray-300">{formatDate(reference.created_at)}</p>
                                </div>
                                <div>
                                    <span className="text-xs font-bold uppercase tracking-wider text-gray-400">{t.updatedAt}</span>
                                    <p className="mt-1 font-medium text-gray-700 dark:text-gray-300">{formatDate(reference.updated_at)}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
