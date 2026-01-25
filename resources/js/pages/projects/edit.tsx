import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/react';
import { ArrowLeft, Save, Palette } from 'lucide-react';
import { cn } from '@/lib/utils';
import { useLanguage } from '@/contexts/language-context';
import { toast } from 'sonner';

// Types
interface Project {
    id: number;
    name: string;
    description: string | null;
    status: string;
    priority: string;
    due_date: string | null;
    progress: number;
    visibility: string;
    color: string | null;
    icon: string | null;
    citation_style: string | null;
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
        updateSuccess: "Project updated successfully",
        // Form labels
        nameLabel: "Project Name",
        namePlaceholder: "e.g., Thesis Research",
        descriptionLabel: "Description",
        descriptionPlaceholder: "Brief description of your project...",
        colorLabel: "Project Color",
        colorDesc: "Choose a color to easily identify this project",
        citationStyleLabel: "Default Citation Style",
        statusLabel: "Status",
        priorityLabel: "Priority",
        dueDateLabel: "Due Date",
        progressLabel: "Progress",
        visibilityLabel: "Visibility",
        visibilityDesc: "Choose who can see this project",
        iconLabel: "Project Icon",
        iconDesc: "Select an icon for visual identification",
        status: {
            planning: "Planning",
            active: "Active",
            completed: "Completed",
            on_hold: "On Hold"
        },
        priority: {
            low: "Low",
            medium: "Medium",
            high: "High",
            urgent: "Urgent"
        },
        visibility: {
            private: "Private (Just me)",
            team: "Team Workspace"
        },
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
        updateSuccess: "อัปเดตโปรเจกต์สำเร็จแล้ว",
        // Form labels
        nameLabel: "ชื่อโปรเจกต์",
        namePlaceholder: "เช่น งานวิจัยวิทยานิพนธ์",
        descriptionLabel: "คำอธิบาย",
        descriptionPlaceholder: "คำอธิบายสั้นๆ เกี่ยวกับโปรเจกต์ของคุณ...",
        colorLabel: "สีโปรเจกต์",
        colorDesc: "เลือกสีเพื่อให้จำแนกโปรเจกต์ได้ง่าย",
        citationStyleLabel: "รูปแบบการอ้างอิงเริ่มต้น",
        statusLabel: "สถานะ",
        priorityLabel: "ความสำคัญ",
        dueDateLabel: "วันครบกำหนด",
        progressLabel: "ความคืบหน้า",
        visibilityLabel: "การมองเห็น",
        visibilityDesc: "เลือกผู้ที่สามารถมองเห็นโปรเจกต์นี้",
        iconLabel: "ไอคอนโปรเจกต์",
        iconDesc: "เลือกไอคอนเพื่อการจำแนกที่ง่ายขึ้น",
        status: {
            planning: "กำลังวางแผน",
            active: "กำลังทำ",
            completed: "เสร็จสิ้น",
            on_hold: "พักไว้ก่อน"
        },
        priority: {
            low: "ต่ำ",
            medium: "ปานกลาง",
            high: "สูง",
            urgent: "เร่งด่วน"
        },
        visibility: {
            private: "ส่วนตัว (เฉพาะฉัน)",
            team: "พื้นที่ทีม"
        },
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

import { 
    FolderOpen, BookOpen, GraduationCap, Microscope, Library, 
    FlaskConical, Beaker, PenTool, BookMarked, Briefcase, 
    Heart, Star, Cloud, Sparkles, LayoutGrid, List, FileText,
    Shield, Globe, Calendar, TrendingUp, CheckCircle, Clock, Timer,
    AlertCircle, Users, Settings
} from 'lucide-react';

const availableIcons = [
    { name: 'Folder', icon: FolderOpen },
    { name: 'BookOpen', icon: BookOpen },
    { name: 'GraduationCap', icon: GraduationCap },
    { name: 'Microscope', icon: Microscope },
    { name: 'Library', icon: Library },
    { name: 'FlaskConical', icon: FlaskConical },
    { name: 'Beaker', icon: Beaker },
    { name: 'PenTool', icon: PenTool },
    { name: 'BookMarked', icon: BookMarked },
    { name: 'Briefcase', icon: Briefcase },
    { name: 'Heart', icon: Heart },
    { name: 'Star', icon: Star },
    { name: 'Cloud', icon: Cloud },
    { name: 'Sparkles', icon: Sparkles },
];

const citationStyles = ['apa7', 'mla9', 'chicago', 'ieee', 'harvard'];

export default function ProjectsEdit({ project }: Props) {
    const { language } = useLanguage();
    const t = translations[language];

    const { data, setData, put, processing, errors } = useForm({
        name: project.name,
        description: project.description || '',
        status: project.status || 'planning',
        priority: project.priority || 'medium',
        due_date: project.due_date || '',
        progress: project.progress || 0,
        visibility: project.visibility || 'private',
        color: project.color || 'blue',
        icon: project.icon || 'Folder',
        citation_style: project.citation_style || 'apa7',
    });

    const breadcrumbs: BreadcrumbItem[] = [
        { title: language === 'en' ? 'Projects' : 'โปรเจกต์', href: '/projects' },
        { title: project.name, href: `/projects/${project.id}` },
        { title: t.title, href: `/projects/${project.id}/edit` },
    ];

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        put(`/projects/${project.id}`, {
            onSuccess: () => toast.success(t.updateSuccess),
        });
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
                <form onSubmit={handleSubmit} className="mx-auto w-full max-w-4xl grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
                    {/* Left Column: Core Data */}
                    <div className="md:col-span-12 space-y-6">
                        <div className="rounded-3xl border border-white bg-white p-8 shadow-sm dark:border-gray-800 dark:bg-gray-900/50">
                            <div className="grid gap-6 md:grid-cols-2">
                                <div className="space-y-4">
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
                                            rows={6}
                                            className={cn(inputClass, 'h-auto py-3')}
                                        />
                                    </div>
                                </div>

                                <div className="space-y-6 lg:pl-6 border-l border-gray-50 dark:border-gray-800">
                                    <div className="grid grid-cols-2 gap-4">
                                        <div>
                                            <label className={labelClass}>{t.statusLabel}</label>
                                            <select 
                                                value={data.status} 
                                                onChange={e => setData('status', e.target.value)}
                                                className={inputClass}
                                            >
                                                <option value="planning">{t.status.planning}</option>
                                                <option value="active">{t.status.active}</option>
                                                <option value="completed">{t.status.completed}</option>
                                                <option value="on_hold">{t.status.on_hold}</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label className={labelClass}>{t.priorityLabel}</label>
                                            <select 
                                                value={data.priority} 
                                                onChange={e => setData('priority', e.target.value)}
                                                className={inputClass}
                                            >
                                                <option value="low">{t.priority.low}</option>
                                                <option value="medium">{t.priority.medium}</option>
                                                <option value="high">{t.priority.high}</option>
                                                <option value="urgent">{t.priority.urgent}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div className="grid grid-cols-2 gap-4">
                                        <div>
                                            <label className={labelClass}>{t.dueDateLabel}</label>
                                            <input
                                                type="date"
                                                value={data.due_date}
                                                onChange={e => setData('due_date', e.target.value)}
                                                className={inputClass}
                                            />
                                        </div>
                                        <div>
                                            <label className={labelClass}>{t.visibilityLabel}</label>
                                            <select 
                                                value={data.visibility} 
                                                onChange={e => setData('visibility', e.target.value)}
                                                className={inputClass}
                                            >
                                                <option value="private">{t.visibility.private}</option>
                                                <option value="team">{t.visibility.team}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div>
                                        <div className="flex items-center justify-between mb-2">
                                            <label className={labelClass}>{t.progressLabel}</label>
                                            <span className="text-xs font-black text-scribehub-blue dark:text-white">{data.progress}%</span>
                                        </div>
                                        <input
                                            type="range"
                                            min="0"
                                            max="100"
                                            value={data.progress}
                                            onChange={e => setData('progress', parseInt(e.target.value))}
                                            className="w-full h-2 bg-gray-100 dark:bg-gray-800 rounded-lg appearance-none cursor-pointer accent-scribehub-blue"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Visual Customization */}
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {/* Color Selection */}
                            <div className="rounded-3xl border border-white bg-white p-8 shadow-sm dark:border-gray-800 dark:bg-gray-900/50">
                                <div className="mb-6 flex items-center gap-3">
                                    <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-emerald-500">
                                        <Palette className="h-5 w-5 text-white" />
                                    </div>
                                    <div>
                                        <h3 className="font-bold text-scribehub-blue dark:text-white">{t.colorLabel}</h3>
                                        <p className="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{t.colorDesc}</p>
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
                                                    ? `ring-4 ring-offset-4 ring-scribehub-blue/20 dark:ring-offset-gray-900 scale-110 shadow-lg`
                                                    : "hover:scale-105"
                                            )}
                                        />
                                    ))}
                                </div>
                            </div>

                            {/* Icon Selection */}
                            <div className="rounded-3xl border border-white bg-white p-8 shadow-sm dark:border-gray-800 dark:bg-gray-900/50">
                                <div className="mb-6 flex items-center gap-3">
                                    <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-scribehub-blue">
                                        <Settings className="h-5 w-5 text-white" />
                                    </div>
                                    <div>
                                        <h3 className="font-bold text-scribehub-blue dark:text-white">{t.iconLabel}</h3>
                                        <p className="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{t.iconDesc}</p>
                                    </div>
                                </div>

                                <div className="grid grid-cols-7 gap-3">
                                    {availableIcons.map((icon) => (
                                        <button
                                            key={icon.name}
                                            type="button"
                                            onClick={() => setData('icon', icon.name)}
                                            className={cn(
                                                "flex h-10 w-10 items-center justify-center rounded-xl transition-all",
                                                data.icon === icon.name 
                                                    ? "bg-scribehub-blue text-white shadow-lg shadow-blue-900/20 scale-110" 
                                                    : "bg-gray-50 text-gray-400 hover:bg-gray-100 dark:bg-gray-800/50 dark:hover:bg-gray-800"
                                            )}
                                        >
                                            <icon.icon className="h-5 w-5" />
                                        </button>
                                    ))}
                                </div>
                            </div>
                        </div>

                        {/* Extra Settings */}
                        <div className="rounded-3xl border border-white bg-white p-8 shadow-sm dark:border-gray-800 dark:bg-gray-900/50">
                            <div className="flex items-center gap-4">
                                <div className="flex-1">
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
                                <div className="pt-6">
                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="inline-flex items-center gap-2 rounded-xl bg-scribehub-blue px-12 py-3 text-sm font-black uppercase tracking-widest text-white shadow-xl shadow-blue-900/20 transition-all hover:opacity-90 active:scale-95 disabled:opacity-50"
                                    >
                                        <Save className="h-4 w-4" />
                                        {t.save}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
}
