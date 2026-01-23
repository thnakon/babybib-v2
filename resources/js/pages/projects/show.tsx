import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/react';
import { ArrowLeft, Edit, Trash2, FolderOpen, FileText, Plus, X, BookOpen } from 'lucide-react';
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
}

interface Project {
    id: number;
    name: string;
    description: string | null;
    citation_style: string | null;
    color: string | null;
    created_at: string;
    references: Reference[];
}

interface Props {
    project: Project;
    availableReferences: Reference[];
}

// Translations
const translations = {
    en: {
        back: "Back to Projects",
        edit: "Edit Project",
        delete: "Delete Project",
        confirmDelete: "Are you sure you want to delete this project and remove all reference associations?",
        references: "References",
        addReference: "Add Reference",
        noReferences: "No references in this project",
        noReferencesDesc: "Add references from your library to this project.",
        addFirst: "Add Your First Reference",
        availableReferences: "Available References",
        noAvailable: "All your references are already in this project.",
        remove: "Remove",
        add: "Add",
        citationStyle: "Citation Style",
        createdAt: "Created",
    },
    th: {
        back: "กลับไปยังโปรเจกต์",
        edit: "แก้ไขโปรเจกต์",
        delete: "ลบโปรเจกต์",
        confirmDelete: "คุณแน่ใจหรือไม่ว่าต้องการลบโปรเจกต์นี้และลบการเชื่อมโยงรายการอ้างอิงทั้งหมด?",
        references: "รายการอ้างอิง",
        addReference: "เพิ่มรายการอ้างอิง",
        noReferences: "ไม่มีรายการอ้างอิงในโปรเจกต์นี้",
        noReferencesDesc: "เพิ่มรายการอ้างอิงจากคลังของคุณเข้าสู่โปรเจกต์นี้",
        addFirst: "เพิ่มรายการอ้างอิงแรก",
        availableReferences: "รายการอ้างอิงที่มี",
        noAvailable: "รายการอ้างอิงทั้งหมดของคุณอยู่ในโปรเจกต์นี้แล้ว",
        remove: "ลบออก",
        add: "เพิ่ม",
        citationStyle: "รูปแบบการอ้างอิง",
        createdAt: "สร้างเมื่อ",
    }
};

// Color styles
const projectColors: Record<string, { bg: string; light: string; text: string }> = {
    blue: { bg: 'bg-blue-500', light: 'bg-blue-100 dark:bg-blue-900/30', text: 'text-blue-600 dark:text-blue-400' },
    green: { bg: 'bg-emerald-500', light: 'bg-emerald-100 dark:bg-emerald-900/30', text: 'text-emerald-600 dark:text-emerald-400' },
    purple: { bg: 'bg-purple-500', light: 'bg-purple-100 dark:bg-purple-900/30', text: 'text-purple-600 dark:text-purple-400' },
    orange: { bg: 'bg-orange-500', light: 'bg-orange-100 dark:bg-orange-900/30', text: 'text-orange-600 dark:text-orange-400' },
    pink: { bg: 'bg-pink-500', light: 'bg-pink-100 dark:bg-pink-900/30', text: 'text-pink-600 dark:text-pink-400' },
    cyan: { bg: 'bg-cyan-500', light: 'bg-cyan-100 dark:bg-cyan-900/30', text: 'text-cyan-600 dark:text-cyan-400' },
    red: { bg: 'bg-red-500', light: 'bg-red-100 dark:bg-red-900/30', text: 'text-red-600 dark:text-red-400' },
    amber: { bg: 'bg-amber-500', light: 'bg-amber-100 dark:bg-amber-900/30', text: 'text-amber-600 dark:text-amber-400' },
};

const getColorStyle = (colorName: string | null) => projectColors[colorName || 'blue'] || projectColors.blue;

export default function ProjectsShow({ project, availableReferences }: Props) {
    const { language } = useLanguage();
    const [showAddPanel, setShowAddPanel] = useState(false);
    const t = translations[language];
    const colorStyle = getColorStyle(project.color);

    const breadcrumbs: BreadcrumbItem[] = [
        { title: language === 'en' ? 'Projects' : 'โปรเจกต์', href: '/projects' },
        { title: project.name, href: `/projects/${project.id}` },
    ];

    const handleDelete = () => {
        if (confirm(t.confirmDelete)) {
            router.delete(`/projects/${project.id}`);
        }
    };

    const handleAddReference = (referenceId: number) => {
        router.post(`/projects/${project.id}/add-reference`, { reference_id: referenceId }, {
            preserveScroll: true,
        });
    };

    const handleRemoveReference = (referenceId: number) => {
        router.post(`/projects/${project.id}/remove-reference`, { reference_id: referenceId }, {
            preserveScroll: true,
        });
    };

    const formatDate = (date: string) => {
        return new Date(date).toLocaleDateString(language === 'th' ? 'th-TH' : 'en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={project.name} />

            <div className="flex h-full flex-1 flex-col gap-6 bg-scribehub-paper/50 p-6 dark:bg-[#050505]/50 animate-in fade-in duration-700">
                {/* Header */}
                <div>
                    <Link
                        href="/projects"
                        className="mb-2 inline-flex items-center gap-2 text-sm font-bold text-gray-500 transition-colors hover:text-scribehub-blue dark:text-gray-400"
                    >
                        <ArrowLeft className="h-4 w-4" />
                        {t.back}
                    </Link>

                    <div className="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                        <div className="flex items-start gap-4">
                            <div className={cn("flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl", colorStyle.light)}>
                                <FolderOpen className={cn("h-7 w-7", colorStyle.text)} />
                            </div>
                            <div>
                                <h1 className="text-2xl font-extrabold text-scribehub-blue dark:text-white">{project.name}</h1>
                                {project.description && (
                                    <p className="mt-1 text-sm text-gray-500 dark:text-gray-400">{project.description}</p>
                                )}
                                <div className="mt-2 flex flex-wrap items-center gap-3">
                                    {project.citation_style && (
                                        <span className="inline-flex items-center gap-1.5 rounded-lg bg-gray-100 px-2.5 py-1 text-[10px] font-bold uppercase text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                                            <FileText className="h-3 w-3" />
                                            {project.citation_style}
                                        </span>
                                    )}
                                    <span className="text-xs text-gray-400">
                                        {t.createdAt}: {formatDate(project.created_at)}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div className="flex items-center gap-2">
                            <Link
                                href={`/projects/${project.id}/edit`}
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
                    {/* References List */}
                    <div className="lg:col-span-2">
                        <div className="rounded-3xl border border-white bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900/50">
                            <div className="mb-4 flex items-center justify-between">
                                <h2 className="text-lg font-extrabold text-scribehub-blue dark:text-white">
                                    {t.references} ({project.references.length})
                                </h2>
                                <button
                                    onClick={() => setShowAddPanel(!showAddPanel)}
                                    className="inline-flex items-center gap-2 rounded-lg bg-scribehub-blue/10 px-3 py-2 text-xs font-bold text-scribehub-blue transition-colors hover:bg-scribehub-blue/20"
                                >
                                    <Plus className="h-3.5 w-3.5" />
                                    {t.addReference}
                                </button>
                            </div>

                            {project.references.length === 0 ? (
                                <div className="flex flex-col items-center justify-center py-12 text-center">
                                    <div className="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-50 dark:bg-gray-800">
                                        <BookOpen className="h-8 w-8 text-gray-300 dark:text-gray-600" />
                                    </div>
                                    <h3 className="font-bold text-gray-600 dark:text-gray-400">{t.noReferences}</h3>
                                    <p className="mt-1 max-w-xs text-sm text-gray-400">{t.noReferencesDesc}</p>
                                </div>
                            ) : (
                                <div className="space-y-2">
                                    {project.references.map(ref => (
                                        <div
                                            key={ref.id}
                                            className="group flex items-center justify-between rounded-xl border border-gray-100 p-4 transition-colors hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-800/50"
                                        >
                                            <div className="min-w-0 flex-1">
                                                <Link href={`/references/${ref.id}`} className="font-bold text-scribehub-blue hover:underline dark:text-white">
                                                    {ref.title}
                                                </Link>
                                                <p className="mt-0.5 text-sm text-gray-500">
                                                    {ref.authors?.join(', ') || 'Unknown'} {ref.year && `(${ref.year})`}
                                                </p>
                                            </div>
                                            <button
                                                onClick={() => handleRemoveReference(ref.id)}
                                                className="ml-4 shrink-0 rounded-lg p-2 text-gray-400 opacity-0 transition-all hover:bg-red-50 hover:text-red-500 group-hover:opacity-100 dark:hover:bg-red-900/20"
                                            >
                                                <X className="h-4 w-4" />
                                            </button>
                                        </div>
                                    ))}
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Add References Panel */}
                    <div className={cn("lg:col-span-1", !showAddPanel && "hidden lg:block")}>
                        <div className="rounded-3xl border border-white bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900/50">
                            <h2 className="mb-4 text-lg font-extrabold text-scribehub-blue dark:text-white">
                                {t.availableReferences}
                            </h2>

                            {availableReferences.length === 0 ? (
                                <p className="text-sm text-gray-500">{t.noAvailable}</p>
                            ) : (
                                <div className="max-h-96 space-y-2 overflow-y-auto">
                                    {availableReferences.map(ref => (
                                        <div
                                            key={ref.id}
                                            className="flex items-center justify-between rounded-xl border border-gray-100 p-3 dark:border-gray-800"
                                        >
                                            <div className="min-w-0 flex-1">
                                                <p className="truncate text-sm font-bold text-gray-700 dark:text-gray-300">{ref.title}</p>
                                                <p className="text-xs text-gray-500">{ref.year}</p>
                                            </div>
                                            <button
                                                onClick={() => handleAddReference(ref.id)}
                                                className="ml-2 shrink-0 rounded-lg bg-scribehub-blue px-3 py-1.5 text-xs font-bold text-white transition-all hover:opacity-90"
                                            >
                                                {t.add}
                                            </button>
                                        </div>
                                    ))}
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
