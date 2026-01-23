import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/react';
import { ArrowLeft, Save, Palette } from 'lucide-react';
import { cn } from '@/lib/utils';
import { useLanguage } from '@/contexts/language-context';

// Types
interface Project {
    id: number;
    name: string;
    description: string | null;
    citation_style: string | null;
    color: string | null;
}

interface Props {
    project: Project;
}

// Translations
const translations = {
    en: {
        title: "Edit Project",
        subtitle: "Update your project details",
        back: "Back to Project",
        save: "Save Changes",
        cancel: "Cancel",
        // Form labels
        nameLabel: "Project Name",
        namePlaceholder: "e.g., Thesis Research",
        descriptionLabel: "Description",
        descriptionPlaceholder: "Brief description of your project...",
        colorLabel: "Project Color",
        colorDesc: "Choose a color to easily identify this project",
        citationStyleLabel: "Default Citation Style",
        styles: {
            apa7: "APA 7th Edition",
            mla9: "MLA 9th Edition",
            chicago: "Chicago 17th",
            ieee: "IEEE",
            harvard: "Harvard",
        }
    },
    th: {
        title: "แก้ไขโปรเจกต์",
        subtitle: "อัปเดตรายละเอียดโปรเจกต์ของคุณ",
        back: "กลับไปยังโปรเจกต์",
        save: "บันทึกการเปลี่ยนแปลง",
        cancel: "ยกเลิก",
        // Form labels
        nameLabel: "ชื่อโปรเจกต์",
        namePlaceholder: "เช่น งานวิจัยวิทยานิพนธ์",
        descriptionLabel: "คำอธิบาย",
        descriptionPlaceholder: "คำอธิบายสั้นๆ เกี่ยวกับโปรเจกต์ของคุณ...",
        colorLabel: "สีโปรเจกต์",
        colorDesc: "เลือกสีเพื่อให้จำแนกโปรเจกต์ได้ง่าย",
        citationStyleLabel: "รูปแบบการอ้างอิงเริ่มต้น",
        styles: {
            apa7: "APA ฉบับที่ 7",
            mla9: "MLA ฉบับที่ 9",
            chicago: "Chicago ฉบับที่ 17",
            ieee: "IEEE",
            harvard: "Harvard",
        }
    }
};

// Color options
const colorOptions = [
    { name: 'blue', bg: 'bg-blue-500', ring: 'ring-blue-500' },
    { name: 'green', bg: 'bg-emerald-500', ring: 'ring-emerald-500' },
    { name: 'purple', bg: 'bg-purple-500', ring: 'ring-purple-500' },
    { name: 'orange', bg: 'bg-orange-500', ring: 'ring-orange-500' },
    { name: 'pink', bg: 'bg-pink-500', ring: 'ring-pink-500' },
    { name: 'cyan', bg: 'bg-cyan-500', ring: 'ring-cyan-500' },
    { name: 'red', bg: 'bg-red-500', ring: 'ring-red-500' },
    { name: 'amber', bg: 'bg-amber-500', ring: 'ring-amber-500' },
];

const citationStyles = ['apa7', 'mla9', 'chicago', 'ieee', 'harvard'];

export default function ProjectsEdit({ project }: Props) {
    const { language } = useLanguage();
    const t = translations[language];

    const { data, setData, put, processing, errors } = useForm({
        name: project.name,
        description: project.description || '',
        color: project.color || 'blue',
        citation_style: project.citation_style || 'apa7',
    });

    const breadcrumbs: BreadcrumbItem[] = [
        { title: language === 'en' ? 'Projects' : 'โปรเจกต์', href: '/projects' },
        { title: project.name, href: `/projects/${project.id}` },
        { title: t.title, href: `/projects/${project.id}/edit` },
    ];

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        put(`/projects/${project.id}`);
    };

    const inputClass = "h-11 w-full rounded-xl border border-gray-100 bg-white px-4 text-sm font-medium focus:border-scribehub-blue/50 focus:outline-none focus:ring-4 focus:ring-scribehub-blue/5 dark:border-gray-800 dark:bg-gray-900/50 dark:text-white";
    const labelClass = "block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2";

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`${t.title} - ${project.name}`} />

            <div className="flex h-full flex-1 flex-col gap-6 bg-scribehub-paper/50 p-6 dark:bg-[#050505]/50 animate-in fade-in duration-700">
                {/* Header */}
                <div>
                    <Link
                        href={`/projects/${project.id}`}
                        className="mb-2 inline-flex items-center gap-2 text-sm font-bold text-gray-500 transition-colors hover:text-scribehub-blue dark:text-gray-400"
                    >
                        <ArrowLeft className="h-4 w-4" />
                        {t.back}
                    </Link>
                    <h1 className="text-3xl font-extrabold text-scribehub-blue dark:text-white">{t.title}</h1>
                    <p className="mt-1 text-sm font-medium text-gray-500 dark:text-gray-400">{t.subtitle}</p>
                </div>

                {/* Form */}
                <form onSubmit={handleSubmit} className="max-w-2xl space-y-6">
                    {/* Project Name */}
                    <div className="rounded-3xl border border-white bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900/50">
                        <div className="space-y-5">
                            <div>
                                <label className={labelClass}>{t.nameLabel} *</label>
                                <input
                                    type="text"
                                    value={data.name}
                                    onChange={e => setData('name', e.target.value)}
                                    placeholder={t.namePlaceholder}
                                    className={cn(inputClass, errors.name && 'border-red-500')}
                                    required
                                />
                                {errors.name && <p className="mt-1 text-xs text-red-500">{errors.name}</p>}
                            </div>

                            <div>
                                <label className={labelClass}>{t.descriptionLabel}</label>
                                <textarea
                                    value={data.description}
                                    onChange={e => setData('description', e.target.value)}
                                    placeholder={t.descriptionPlaceholder}
                                    rows={3}
                                    className={cn(inputClass, 'h-auto py-3')}
                                />
                            </div>
                        </div>
                    </div>

                    {/* Color Selection */}
                    <div className="rounded-3xl border border-white bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900/50">
                        <div className="mb-4 flex items-center gap-3">
                            <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-pink-500 via-purple-500 to-blue-500">
                                <Palette className="h-5 w-5 text-white" />
                            </div>
                            <div>
                                <h3 className="font-bold text-scribehub-blue dark:text-white">{t.colorLabel}</h3>
                                <p className="text-xs text-gray-500">{t.colorDesc}</p>
                            </div>
                        </div>

                        <div className="flex flex-wrap gap-3">
                            {colorOptions.map(color => (
                                <button
                                    key={color.name}
                                    type="button"
                                    onClick={() => setData('color', color.name)}
                                    className={cn(
                                        "h-10 w-10 rounded-xl transition-all",
                                        color.bg,
                                        data.color === color.name
                                            ? `ring-2 ${color.ring} ring-offset-2 dark:ring-offset-gray-900 scale-110`
                                            : "hover:scale-105"
                                    )}
                                />
                            ))}
                        </div>
                    </div>

                    {/* Citation Style */}
                    <div className="rounded-3xl border border-white bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900/50">
                        <label className={labelClass}>{t.citationStyleLabel}</label>
                        <select
                            value={data.citation_style}
                            onChange={e => setData('citation_style', e.target.value)}
                            className={inputClass}
                        >
                            {citationStyles.map(style => (
                                <option key={style} value={style}>
                                    {t.styles[style as keyof typeof t.styles]}
                                </option>
                            ))}
                        </select>
                    </div>

                    {/* Submit */}
                    <div className="flex items-center justify-end gap-4">
                        <Link
                            href={`/projects/${project.id}`}
                            className="rounded-xl border border-gray-200 px-6 py-3 text-sm font-bold text-gray-600 transition-colors hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800"
                        >
                            {t.cancel}
                        </Link>
                        <button
                            type="submit"
                            disabled={processing}
                            className="inline-flex items-center gap-2 rounded-xl bg-scribehub-blue px-8 py-3 text-sm font-bold text-white shadow-lg shadow-blue-900/20 transition-all hover:opacity-90 active:scale-95 disabled:opacity-50"
                        >
                            <Save className="h-4 w-4" />
                            {t.save}
                        </button>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
}
