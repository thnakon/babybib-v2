import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/react';
import { Plus, FolderOpen, FileText, MoreVertical, Edit, Trash2, Eye } from 'lucide-react';
import { useState } from 'react';
import { cn } from '@/lib/utils';
import { useLanguage } from '@/contexts/language-context';

// Types
interface Project {
    id: number;
    name: string;
    description: string | null;
    citation_style: string | null;
    color: string | null;
    references_count: number;
    created_at: string;
}

interface Props {
    projects: Project[];
}

// Translations
const translations = {
    en: {
        title: "My Projects",
        subtitle: "Organize your references into research projects",
        addNew: "New Project",
        noProjects: "No projects yet",
        noProjectsDesc: "Create your first project to organize your references by topic or research area.",
        createFirst: "Create Your First Project",
        references: "references",
        view: "View",
        edit: "Edit",
        delete: "Delete",
        confirmDelete: "Are you sure you want to delete this project?",
        noDescription: "No description",
    },
    th: {
        title: "โปรเจกต์ของฉัน",
        subtitle: "จัดระเบียบรายการอ้างอิงเป็นโปรเจกต์วิจัย",
        addNew: "โปรเจกต์ใหม่",
        noProjects: "ยังไม่มีโปรเจกต์",
        noProjectsDesc: "สร้างโปรเจกต์แรกเพื่อจัดระเบียบรายการอ้างอิงตามหัวข้อหรือสาขาวิจัย",
        createFirst: "สร้างโปรเจกต์แรกของคุณ",
        references: "รายการอ้างอิง",
        view: "ดู",
        edit: "แก้ไข",
        delete: "ลบ",
        confirmDelete: "คุณแน่ใจหรือไม่ว่าต้องการลบโปรเจกต์นี้?",
        noDescription: "ไม่มีคำอธิบาย",
    }
};

// Predefined color options
const projectColors = [
    { name: 'blue', bg: 'bg-blue-500', light: 'bg-blue-100 dark:bg-blue-900/30', text: 'text-blue-600 dark:text-blue-400' },
    { name: 'green', bg: 'bg-emerald-500', light: 'bg-emerald-100 dark:bg-emerald-900/30', text: 'text-emerald-600 dark:text-emerald-400' },
    { name: 'purple', bg: 'bg-purple-500', light: 'bg-purple-100 dark:bg-purple-900/30', text: 'text-purple-600 dark:text-purple-400' },
    { name: 'orange', bg: 'bg-orange-500', light: 'bg-orange-100 dark:bg-orange-900/30', text: 'text-orange-600 dark:text-orange-400' },
    { name: 'pink', bg: 'bg-pink-500', light: 'bg-pink-100 dark:bg-pink-900/30', text: 'text-pink-600 dark:text-pink-400' },
    { name: 'cyan', bg: 'bg-cyan-500', light: 'bg-cyan-100 dark:bg-cyan-900/30', text: 'text-cyan-600 dark:text-cyan-400' },
    { name: 'red', bg: 'bg-red-500', light: 'bg-red-100 dark:bg-red-900/30', text: 'text-red-600 dark:text-red-400' },
    { name: 'amber', bg: 'bg-amber-500', light: 'bg-amber-100 dark:bg-amber-900/30', text: 'text-amber-600 dark:text-amber-400' },
];

const getColorStyle = (colorName: string | null) => {
    const color = projectColors.find(c => c.name === colorName) || projectColors[0];
    return color;
};

export default function ProjectsIndex({ projects }: Props) {
    const { language } = useLanguage();
    const [openMenu, setOpenMenu] = useState<number | null>(null);
    const t = translations[language];

    const breadcrumbs: BreadcrumbItem[] = [
        { title: t.title, href: '/projects' },
    ];

    const handleDelete = (id: number) => {
        if (confirm(t.confirmDelete)) {
            router.delete(`/projects/${id}`);
        }
    };

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
                    <Link
                        href="/projects/create"
                        className="inline-flex items-center gap-2 rounded-xl bg-scribehub-blue px-6 py-3 text-sm font-bold text-white shadow-lg shadow-blue-900/20 transition-all hover:opacity-90 active:scale-95"
                    >
                        <Plus className="h-4 w-4" />
                        {t.addNew}
                    </Link>
                </div>

                {/* Projects Grid */}
                {projects.length === 0 ? (
                    <div className="flex flex-1 flex-col items-center justify-center rounded-3xl border border-dashed border-gray-200 bg-white p-12 dark:border-gray-800 dark:bg-gray-900/30">
                        <div className="mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-gray-50 dark:bg-gray-800">
                            <FolderOpen className="h-10 w-10 text-gray-300 dark:text-gray-600" />
                        </div>
                        <h3 className="text-lg font-bold text-scribehub-blue dark:text-white">{t.noProjects}</h3>
                        <p className="mt-2 max-w-sm text-center text-sm text-gray-500 dark:text-gray-400">{t.noProjectsDesc}</p>
                        <Link
                            href="/projects/create"
                            className="mt-6 inline-flex items-center gap-2 rounded-xl bg-scribehub-blue px-6 py-3 text-sm font-bold text-white shadow-lg transition-all hover:opacity-90"
                        >
                            <Plus className="h-4 w-4" />
                            {t.createFirst}
                        </Link>
                    </div>
                ) : (
                    <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        {projects.map((project) => {
                            const colorStyle = getColorStyle(project.color);
                            return (
                                <div
                                    key={project.id}
                                    className="group relative overflow-hidden rounded-2xl border border-white bg-white shadow-sm transition-all hover:shadow-md dark:border-gray-800 dark:bg-gray-900/50"
                                >
                                    {/* Color Bar */}
                                    <div className={cn("h-2", colorStyle.bg)} />

                                    <div className="p-5">
                                        {/* Header */}
                                        <div className="flex items-start justify-between">
                                            <div className="flex items-start gap-3">
                                                <div className={cn("flex h-10 w-10 shrink-0 items-center justify-center rounded-xl", colorStyle.light)}>
                                                    <FolderOpen className={cn("h-5 w-5", colorStyle.text)} />
                                                </div>
                                                <div className="min-w-0">
                                                    <Link href={`/projects/${project.id}`}>
                                                        <h3 className="truncate font-bold text-scribehub-blue transition-colors hover:text-scribehub-blue/80 dark:text-white">
                                                            {project.name}
                                                        </h3>
                                                    </Link>
                                                    <p className="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                                                        {project.references_count} {t.references}
                                                    </p>
                                                </div>
                                            </div>

                                            {/* Menu */}
                                            <div className="relative">
                                                <button
                                                    onClick={() => setOpenMenu(openMenu === project.id ? null : project.id)}
                                                    className="flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800"
                                                >
                                                    <MoreVertical className="h-4 w-4" />
                                                </button>
                                                {openMenu === project.id && (
                                                    <div className="absolute right-0 top-full z-10 mt-1 w-36 rounded-xl border border-gray-100 bg-white py-2 shadow-xl dark:border-gray-800 dark:bg-gray-900">
                                                        <Link
                                                            href={`/projects/${project.id}`}
                                                            className="flex w-full items-center gap-3 px-4 py-2 text-xs font-bold text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-800"
                                                        >
                                                            <Eye className="h-4 w-4" /> {t.view}
                                                        </Link>
                                                        <Link
                                                            href={`/projects/${project.id}/edit`}
                                                            className="flex w-full items-center gap-3 px-4 py-2 text-xs font-bold text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-800"
                                                        >
                                                            <Edit className="h-4 w-4" /> {t.edit}
                                                        </Link>
                                                        <button
                                                            onClick={() => handleDelete(project.id)}
                                                            className="flex w-full items-center gap-3 px-4 py-2 text-xs font-bold text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20"
                                                        >
                                                            <Trash2 className="h-4 w-4" /> {t.delete}
                                                        </button>
                                                    </div>
                                                )}
                                            </div>
                                        </div>

                                        {/* Description */}
                                        <p className="mt-3 line-clamp-2 text-sm text-gray-500 dark:text-gray-400">
                                            {project.description || t.noDescription}
                                        </p>

                                        {/* Citation Style */}
                                        {project.citation_style && (
                                            <div className="mt-4 flex items-center gap-2">
                                                <FileText className="h-3.5 w-3.5 text-gray-400" />
                                                <span className="text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                                    {project.citation_style}
                                                </span>
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
