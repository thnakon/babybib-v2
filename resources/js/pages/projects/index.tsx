import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { Head, Link, router, useForm } from '@inertiajs/react';
import { 
    Plus, FolderOpen, FileText, MoreVertical, Edit, Trash2, Eye, 
    LayoutGrid, List, Filter, Search, CheckCircle2, Clock, 
    AlertCircle, Users, FileStack, Calendar, ArrowRight,
    TrendingUp, CheckCircle, Timer, BookOpen, GraduationCap,
    Microscope, Library, FlaskConical, Beaker, PenTool,
    BookMarked, Briefcase, Heart, Star, Cloud, Sparkles, Menu, Zap
} from 'lucide-react';
import { useState, useMemo } from 'react';
import { cn } from '@/lib/utils';
import { useLanguage } from '@/contexts/language-context';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogFooter } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { toast } from 'sonner';
import { Save, Palette as PaletteIcon, X } from 'lucide-react';
import { 
    Select, 
    SelectContent, 
    SelectItem, 
    SelectTrigger, 
    SelectValue 
} from '@/components/ui/select';

// Types
interface Project {
    id: number;
    name: string;
    description: string | null;
    citation_style: string | null;
    color: string | null;
    icon: string | null;
    status: string;
    priority: string;
    progress: number;
    due_date: string | null;
    references_count: number;
    tasks_count: number;
    members_count: number;
    files_count: number;
    created_at: string;
}

interface Props {
    projects: Project[];
    storageUsage: {
        used: number;
        limit: number;
        percentage: number;
    };
}

// Translations
const translations = {
    en: {
        title: "My Projects",
        subtitle: "Manage your research workflows and collaborations",
        addNew: "New Project",
        search: "Search projects...",
        allStatus: "All Status",
        allPriority: "All Priority",
        stats: {
            total: "Total Projects",
            active: "Active",
            completed: "Completed"
        },
        noProjects: "No projects yet",
        noProjectsDesc: "Create your first project to organize your workflows.",
        createFirst: "Create Your First Project",
        references: "References",
        tasks: "Tasks",
        members: "Members",
        files: "Files",
        view: "Open Workspace",
        edit: "Settings",
        delete: "Delete Project",
        confirmDelete: "Are you sure you want to delete this project?",
        noDescription: "No description",
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
        storageUsage: "Storage Usage",
        upgradeToPro: "Upgrade to Pro for more storage",
        goPro: "GO PRO"
    },
    th: {
        title: "โปรเจกต์ของฉัน",
        subtitle: "จัดการแผนงานวิจัยและการทำงานร่วมกัน",
        addNew: "โปรเจกต์ใหม่",
        search: "ค้นหาโปรเจกต์...",
        allStatus: "ทุกสถานะ",
        allPriority: "ทุกระดับความสำคัญ",
        stats: {
            total: "โปรเจกต์ทั้งหมด",
            active: "กำลังดำเนินงาน",
            completed: "ที่เสร็จสมบูรณ์"
        },
        noProjects: "ยังไม่มีโปรเจกต์",
        noProjectsDesc: "สร้างโปรเจกต์แรกเพื่อจัดระเบียบแผนงานของคุณ",
        createFirst: "สร้างโปรเจกต์แรกของคุณ",
        references: "แหล่งอ้างอิง",
        tasks: "งาน",
        members: "สมาชิก",
        files: "ไฟล์",
        view: "เปิดพื้นที่ทำงาน",
        edit: "ตั้งค่า",
        delete: "ลบโปรเจกต์",
        confirmDelete: "คุณแน่ใจหรือไม่ว่าต้องการลบโปรเจกต์นี้?",
        noDescription: "ไม่มีคำอธิบาย",
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
        storageUsage: "การใช้งานพื้นที่",
        upgradeToPro: "อัปเกรดเป็น Pro เพื่อพื้นที่ที่มากขึ้น",
        goPro: "อัปเกรด PRO"
    }
};

const projectColors: Record<string, { bg: string, light: string, text: string, border: string, ring: string }> = {
    blue: { bg: 'bg-blue-500', light: 'bg-blue-50 dark:bg-blue-900/20', text: 'text-blue-600 dark:text-blue-400', border: 'border-blue-200 dark:border-blue-800', ring: 'ring-blue-500' },
    green: { bg: 'bg-emerald-500', light: 'bg-emerald-50 dark:bg-emerald-900/20', text: 'text-emerald-600 dark:text-emerald-400', border: 'border-emerald-200 dark:border-emerald-800', ring: 'ring-emerald-500' },
    purple: { bg: 'bg-purple-500', light: 'bg-purple-50 dark:bg-purple-900/20', text: 'text-purple-600 dark:text-purple-400', border: 'border-purple-200 dark:border-purple-800', ring: 'ring-purple-500' },
    orange: { bg: 'bg-orange-500', light: 'bg-orange-50 dark:bg-orange-900/20', text: 'text-orange-600 dark:text-orange-400', border: 'border-orange-200 dark:border-orange-800', ring: 'ring-orange-500' },
    pink: { bg: 'bg-pink-500', light: 'bg-pink-50 dark:bg-pink-900/20', text: 'text-pink-600 dark:text-pink-400', border: 'border-pink-200 dark:border-pink-800', ring: 'ring-pink-500' },
    cyan: { bg: 'bg-cyan-500', light: 'bg-cyan-50 dark:bg-cyan-900/20', text: 'text-cyan-600 dark:text-cyan-400', border: 'border-cyan-200 dark:border-cyan-800', ring: 'ring-cyan-500' },
    red: { bg: 'bg-red-500', light: 'bg-red-50 dark:bg-red-900/20', text: 'text-red-600 dark:text-red-400', border: 'border-red-200 dark:border-red-800', ring: 'ring-red-500' },
    amber: { bg: 'bg-amber-500', light: 'bg-amber-50 dark:bg-amber-900/20', text: 'text-amber-600 dark:text-amber-400', border: 'border-amber-200 dark:border-amber-800', ring: 'ring-amber-500' },
};

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

const IconRenderer = ({ iconName, className, style }: { iconName: string | null, className?: string, style?: any }) => {
    const iconObj = availableIcons.find(i => i.name === iconName) || availableIcons[0];
    const IconComponent = iconObj.icon;
    return <IconComponent className={className} style={style} />;
};

const statusConfig: Record<string, { icon: any, color: string }> = {
    planning: { icon: Clock, color: 'text-blue-500' },
    active: { icon: TrendingUp, color: 'text-emerald-500' },
    completed: { icon: CheckCircle2, color: 'text-purple-500' },
    on_hold: { icon: AlertCircle, color: 'text-orange-500' },
};

const priorityConfig: Record<string, { color: string }> = {
    low: { color: 'bg-gray-100 text-gray-600' },
    medium: { color: 'bg-blue-100 text-blue-600' },
    high: { color: 'bg-orange-100 text-orange-600' },
    urgent: { color: 'bg-red-100 text-red-600' },
};

export default function ProjectsIndex({ projects, storageUsage }: Props) {
    const { language } = useLanguage();
    const [searchQuery, setSearchQuery] = useState('');
    const [statusFilter, setStatusFilter] = useState('all');
    const [priorityFilter, setPriorityFilter] = useState('all');
    const [viewMode, setViewMode] = useState<'grid' | 'list' | 'compact'>('grid');
    const [openMenu, setOpenMenu] = useState<number | null>(null);
    const [isCreateModalOpen, setIsCreateModalOpen] = useState(false);
    const [isDeleteModalOpen, setIsDeleteModalOpen] = useState(false);
    const [projectToDelete, setProjectToDelete] = useState<Project | null>(null);
    const t = translations[language];

    const { data, setData, post, processing, reset, errors } = useForm({
        name: '',
        description: '',
        color: 'blue',
        icon: 'Folder',
    });

    const breadcrumbs: BreadcrumbItem[] = [
        { title: t.title, href: '/projects' },
    ];

    const filteredProjects = useMemo(() => {
        return projects.filter(p => {
            const matchesSearch = p.name.toLowerCase().includes(searchQuery.toLowerCase());
            const matchesStatus = statusFilter === 'all' || p.status === statusFilter;
            const matchesPriority = priorityFilter === 'all' || p.priority === priorityFilter;
            return matchesSearch && matchesStatus && matchesPriority;
        });
    }, [projects, searchQuery, statusFilter, priorityFilter]);

    const activeCount = projects.filter(p => p.status === 'active').length;
    const completedCount = projects.filter(p => p.status === 'completed').length;

    const handleDelete = (project: Project) => {
        setProjectToDelete(project);
        setIsDeleteModalOpen(true);
        setOpenMenu(null);
    };

    const confirmDelete = () => {
        if (projectToDelete) {
            router.delete(`/projects/${projectToDelete.id}`, {
                onSuccess: () => {
                    setIsDeleteModalOpen(false);
                    setProjectToDelete(null);
                    toast.success(language === 'en' ? 'Project deleted successfully' : 'ลบโปรเจกต์สำเร็จแล้ว');
                }
            });
        }
    };

    const handleCreateProject = (e: React.FormEvent) => {
        e.preventDefault();
        post('/projects', {
            onSuccess: () => {
                setIsCreateModalOpen(false);
                reset();
                toast.success(language === 'en' ? 'Project created successfully' : 'สร้างโปรเจกต์สำเร็จแล้ว');
            },
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t.title} />

            <div className="flex h-full flex-1 flex-col gap-5 bg-gray-50/50 p-6 dark:bg-[#050505]/50 animate-in fade-in duration-700">
                {/* Header Section */}
                <div className="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                    <div>
                        <h1 className="text-3xl font-black tracking-tight text-scribehub-blue dark:text-white">
                            {t.title}
                        </h1>
                        <p className="mt-1 text-xs font-medium text-gray-400 dark:text-gray-500">
                            {t.subtitle}
                        </p>
                    </div>
                    <button
                        onClick={() => setIsCreateModalOpen(true)}
                        className="group flex items-center gap-2 rounded-xl bg-scribehub-blue px-6 py-2.5 text-xs font-black text-white shadow-lg shadow-blue-900/10 transition-all hover:translate-y-[-1px] hover:shadow-xl active:scale-95"
                    >
                        <Plus className="h-4 w-4 transition-transform group-hover:rotate-90" />
                        {t.addNew}
                    </button>
                </div>

                {/* Quick Stats */}
                <div className="grid grid-cols-1 gap-4 md:grid-cols-4">
                    {[
                        { label: t.stats.total, value: projects.length, icon: FileStack, color: 'text-blue-500', bg: 'bg-blue-50' },
                        { label: t.stats.active, value: activeCount, icon: Timer, color: 'text-emerald-500', bg: 'bg-emerald-50' },
                        { label: t.stats.completed, value: completedCount, icon: CheckCircle, color: 'text-purple-500', bg: 'bg-purple-50' }
                    ].map((stat, i) => (
                        <div key={i} className="flex items-center justify-between rounded-2xl border border-white bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900/50 transition-all hover:shadow-md">
                            <div>
                                <p className="text-[10px] font-black uppercase tracking-widest text-gray-400">{stat.label}</p>
                                <p className="mt-0.5 text-2xl font-black text-scribehub-blue dark:text-white">{stat.value}</p>
                            </div>
                            <div className={cn("flex h-10 w-10 items-center justify-center rounded-xl", stat.bg, "dark:bg-opacity-10")}>
                                <stat.icon className={cn("h-5 w-5", stat.color)} />
                            </div>
                        </div>
                    ))}
                    
                    {/* Storage Usage Stat */}
                    <div className="flex flex-col rounded-2xl border border-white bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900/50 transition-all hover:shadow-md">
                        <div className="flex items-center justify-between mb-2">
                            <p className="text-[10px] font-black uppercase tracking-widest text-gray-400">{t.storageUsage}</p>
                            <span className="text-[10px] font-black text-scribehub-blue dark:text-white">{storageUsage.used} MB / {storageUsage.limit} MB</span>
                        </div>
                        <div className="h-1.5 w-full bg-gray-50 dark:bg-gray-800 rounded-full overflow-hidden mb-2">
                            <div className="h-full bg-scribehub-blue rounded-full transition-all duration-1000" style={{ width: `${storageUsage.percentage}%` }}></div>
                        </div>
                        <div className="flex items-center justify-between">
                            <p className="text-[8px] font-bold text-gray-400 truncate max-w-[120px]">{t.upgradeToPro}</p>
                            <Link href="/billing" className="text-[8px] font-black uppercase tracking-widest text-scribehub-blue hover:underline flex items-center gap-1">
                                <Zap className="h-2 w-2 fill-scribehub-blue" /> {t.goPro}
                            </Link>
                        </div>
                    </div>
                </div>

                {/* Filters & Actions */}
                <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div className="flex flex-1 items-center gap-4">
                        <div className="relative w-[240px]">
                            <Search className="absolute left-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-gray-400" />
                            <input
                                type="text"
                                placeholder={t.search}
                                value={searchQuery}
                                onChange={(e) => setSearchQuery(e.target.value)}
                                className="w-full rounded-xl border-none bg-white py-2.5 pl-9 pr-3 text-[10px] font-black shadow-sm transition-all focus:ring-2 focus:ring-scribehub-blue dark:bg-gray-900"
                            />
                        </div>

                        {/* View Switcher */}
                        <div className="flex items-center gap-1 rounded-xl bg-white p-1 shadow-sm dark:bg-gray-900/50">
                            <button
                                onClick={() => setViewMode('grid')}
                                className={cn(
                                    "flex h-8 w-8 items-center justify-center rounded-lg transition-all",
                                    viewMode === 'grid' ? "bg-scribehub-blue text-white shadow-md shadow-blue-900/10" : "text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"
                                )}
                            >
                                <LayoutGrid className="h-4 w-4" />
                            </button>
                            <button
                                onClick={() => setViewMode('list')}
                                className={cn(
                                    "flex h-8 w-8 items-center justify-center rounded-lg transition-all",
                                    viewMode === 'list' ? "bg-scribehub-blue text-white shadow-md shadow-blue-900/10" : "text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"
                                )}
                            >
                                <List className="h-4 w-4" />
                            </button>
                            <button
                                onClick={() => setViewMode('compact')}
                                className={cn(
                                    "flex h-8 w-8 items-center justify-center rounded-lg transition-all",
                                    viewMode === 'compact' ? "bg-scribehub-blue text-white shadow-md shadow-blue-900/10" : "text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"
                                )}
                            >
                                <Menu className="h-4 w-4" />
                            </button>
                        </div>
                    </div>

                    <div className="flex items-center gap-3">
                        <Select value={statusFilter} onValueChange={setStatusFilter}>
                            <SelectTrigger className="w-[150px] rounded-xl border-none bg-white text-xs font-black shadow-sm dark:bg-gray-900">
                                <SelectValue placeholder={t.allStatus} />
                            </SelectTrigger>
                            <SelectContent className="rounded-xl border-none font-bold shadow-xl">
                                <SelectItem value="all">{t.allStatus}</SelectItem>
                                <SelectItem value="planning">
                                    <div className="flex items-center gap-2"><div className="h-2 w-2 rounded-full bg-blue-500" /> {t.status.planning}</div>
                                </SelectItem>
                                <SelectItem value="active">
                                    <div className="flex items-center gap-2"><div className="h-2 w-2 rounded-full bg-emerald-500" /> {t.status.active}</div>
                                </SelectItem>
                                <SelectItem value="completed">
                                    <div className="flex items-center gap-2"><div className="h-2 w-2 rounded-full bg-purple-500" /> {t.status.completed}</div>
                                </SelectItem>
                                <SelectItem value="on_hold">
                                    <div className="flex items-center gap-2"><div className="h-2 w-2 rounded-full bg-orange-500" /> {t.status.on_hold}</div>
                                </SelectItem>
                            </SelectContent>
                        </Select>

                        <Select value={priorityFilter} onValueChange={setPriorityFilter}>
                            <SelectTrigger className="w-[150px] rounded-xl border-none bg-white text-xs font-black shadow-sm dark:bg-gray-900">
                                <SelectValue placeholder={t.allPriority} />
                            </SelectTrigger>
                            <SelectContent className="rounded-xl border-none font-bold shadow-xl">
                                <SelectItem value="all">{t.allPriority}</SelectItem>
                                <SelectItem value="low">
                                    <div className="flex items-center gap-2"><div className="h-2 w-2 rounded-full bg-gray-400" /> {t.priority.low}</div>
                                </SelectItem>
                                <SelectItem value="medium">
                                    <div className="flex items-center gap-2"><div className="h-2 w-2 rounded-full bg-blue-500" /> {t.priority.medium}</div>
                                </SelectItem>
                                <SelectItem value="high">
                                    <div className="flex items-center gap-2"><div className="h-2 w-2 rounded-full bg-orange-500" /> {t.priority.high}</div>
                                </SelectItem>
                                <SelectItem value="urgent">
                                    <div className="flex items-center gap-2"><div className="h-2 w-2 rounded-full bg-red-600" /> {t.priority.urgent}</div>
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                </div>

                {/* Projects Display */}
                {filteredProjects.length === 0 ? (
                    <div className="flex flex-1 flex-col items-center justify-center rounded-[2.5rem] border border-dashed border-gray-200 bg-white/50 p-12 dark:border-gray-800 dark:bg-gray-900/20">
                        <div className="mb-6 flex h-20 w-20 items-center justify-center rounded-3xl bg-blue-50 text-blue-500 dark:bg-blue-900/20">
                            <FolderOpen className="h-10 w-10" />
                        </div>
                        <h3 className="text-xl font-black text-scribehub-blue dark:text-white">{t.noProjects}</h3>
                        <p className="mt-3 max-w-sm text-center text-sm font-medium text-gray-500 dark:text-gray-400">{t.noProjectsDesc}</p>
                        <button
                            onClick={() => setIsCreateModalOpen(true)}
                            className="mt-8 rounded-2xl bg-scribehub-blue px-8 py-4 text-sm font-bold text-white shadow-lg transition-all hover:scale-105 active:scale-95"
                        >
                            {t.createFirst}
                        </button>
                    </div>
                ) : viewMode === 'grid' ? (
                    <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        {filteredProjects.map((project) => {
                            const theme = projectColors[project.color || 'blue'] || projectColors.blue;
                            return (
                                <div key={project.id} className="group relative flex flex-col items-stretch transition-all duration-300">
                                    <Link 
                                        href={`/projects/${project.id}`}
                                        className={cn("flex flex-1 flex-col rounded-[1.5rem] p-6", theme.light)}
                                    >
                                        {/* Badge & Menu */}
                                        <div className="flex items-center justify-between">
                                            <div className="flex items-center gap-3">
                                                <div className={cn("flex h-10 w-10 items-center justify-center rounded-xl bg-white shadow-sm dark:bg-gray-800")}>
                                                    <IconRenderer iconName={project.icon || 'Folder'} className={cn("h-5 w-5", theme.text)} />
                                                </div>
                                                <div className={cn("flex items-center gap-1.5 rounded-full px-3 py-1 text-[10px] font-black uppercase tracking-wider", priorityConfig[project.priority]?.color || 'bg-gray-100')}>
                                                    {t.priority[project.priority as keyof typeof t.priority] || project.priority}
                                                </div>
                                            </div>
                                            <div className="relative">
                                                <button
                                                    onClick={(e) => {
                                                        e.preventDefault();
                                                        setOpenMenu(openMenu === project.id ? null : project.id);
                                                    }}
                                                    className="flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 hover:bg-white/50 dark:hover:bg-black/20"
                                                >
                                                    <MoreVertical className="h-4 w-4" />
                                                </button>
                                            </div>
                                        </div>

                                        {/* Project Info */}
                                        <div className="mt-6 flex-1">
                                            <h3 className="text-xl font-black text-scribehub-blue dark:text-white line-clamp-1">{project.name}</h3>
                                            <p className="mt-2 text-xs font-medium text-gray-500 dark:text-gray-400 line-clamp-2">
                                                {project.description || (language === 'en' ? 'No description provided' : 'ไม่มีคำอธิบาย')}
                                            </p>
                                        </div>

                                        {/* Footer / Stats */}
                                        <div className="mt-8 flex items-center justify-between border-t border-black/5 pt-6 dark:border-white/5">
                                            <div className="flex items-center gap-4">
                                                <div className="flex items-center gap-1.5">
                                                    <FileText className="h-3.5 w-3.5 text-gray-400" />
                                                    <span className="text-[10px] font-black text-gray-400 uppercase tracking-widest">{project.references_count || 0}</span>
                                                </div>
                                                <div className="flex items-center gap-1.5">
                                                    <CheckCircle2 className="h-3.5 w-3.5 text-gray-400" />
                                                    <span className="text-[10px] font-black text-gray-400 uppercase tracking-widest">{project.tasks_count || 0}</span>
                                                </div>
                                            </div>
                                            <div className="flex items-center gap-2">
                                                {statusConfig[project.status] && (
                                                    <div className={cn("flex items-center gap-1.5 rounded-full bg-white/30 px-2 py-1 dark:bg-black/10")}>
                                                        <IconRenderer iconName={project.icon} className={cn("h-3 w-3", statusConfig[project.status].color)} />
                                                        <span className="text-[10px] font-black uppercase tracking-widest text-scribehub-blue dark:text-white">
                                                            {t.status[project.status as keyof typeof t.status]}
                                                        </span>
                                                    </div>
                                                )}
                                                <div className="flex h-6 w-6 items-center justify-center rounded-full bg-scribehub-blue text-white shadow-lg">
                                                    <ArrowRight className="h-3 w-3" />
                                                </div>
                                            </div>
                                        </div>
                                    </Link>

                                    {/* Action Menu */}
                                    {openMenu === project.id && (
                                        <>
                                            <div className="fixed inset-0 z-10" onClick={() => setOpenMenu(null)} />
                                            <div className="absolute right-4 top-14 z-20 w-48 rounded-2xl border border-gray-100 bg-white p-2 shadow-2xl animate-in fade-in zoom-in-95 dark:border-gray-800 dark:bg-gray-950">
                                                <Link
                                                    href={`/projects/${project.id}/edit`}
                                                    className="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-xs font-black text-gray-600 transition-colors hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-900"
                                                >
                                                    <Edit className="h-4 w-4 text-blue-500" />
                                                    {language === 'en' ? 'Edit Project' : 'แก้ไขโปรเจกต์'}
                                                </Link>
                                                <button
                                                    onClick={() => handleDelete(project)}
                                                    className="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-xs font-black text-red-600 transition-colors hover:bg-red-50 dark:hover:bg-red-950/30"
                                                >
                                                    <Trash2 className="h-4 w-4 text-red-500" />
                                                    {language === 'en' ? 'Delete Project' : 'ลบโปรเจกต์'}
                                                </button>
                                            </div>
                                        </>
                                    )}
                                </div>
                            );
                        })}
                    </div>
                ) : viewMode === 'list' ? (
                    <div className="space-y-3">
                        {filteredProjects.map((project) => {
                            const theme = projectColors[project.color || 'blue'] || projectColors.blue;
                            return (
                                <Link 
                                    key={project.id}
                                    href={`/projects/${project.id}`}
                                    className="flex items-center gap-4 rounded-[1.25rem] border border-white bg-white p-4 shadow-sm transition-all hover:shadow-md dark:border-gray-800 dark:bg-gray-900/50"
                                >
                                    <div className={cn("flex h-12 w-12 shrink-0 items-center justify-center rounded-xl", theme.light)}>
                                        <IconRenderer iconName={project.icon || 'Folder'} className={cn("h-6 w-6", theme.text)} />
                                    </div>
                                    <div className="flex-1 min-w-0">
                                        <h3 className="text-sm font-black text-scribehub-blue dark:text-white truncate">{project.name}</h3>
                                        <p className="mt-0.5 text-[10px] font-medium text-gray-400 truncate">{project.description}</p>
                                    </div>
                                    <div className="hidden md:flex items-center gap-8 px-6 border-l border-gray-50 dark:border-gray-800">
                                        <div className="flex flex-col items-center">
                                            <span className="text-[10px] font-black text-gray-300 uppercase tracking-widest">{language === 'en' ? 'Status' : 'สถานะ'}</span>
                                            <span className={cn("mt-1 text-[10px] font-black uppercase text-scribehub-blue dark:text-white", statusConfig[project.status]?.color)}>
                                                {t.status[project.status as keyof typeof t.status]}
                                            </span>
                                        </div>
                                        <div className="flex flex-col items-center">
                                            <span className="text-[10px] font-black text-gray-300 uppercase tracking-widest">{language === 'en' ? 'References' : 'อ้างอิง'}</span>
                                            <span className="mt-1 text-[10px] font-black text-scribehub-blue dark:text-white">{project.references_count || 0}</span>
                                        </div>
                                        <div className="flex flex-col items-center">
                                            <span className="text-[10px] font-black text-gray-300 uppercase tracking-widest">{language === 'en' ? 'Progress' : 'ความคืบหน้า'}</span>
                                            <span className="mt-1 text-[10px] font-black text-scribehub-blue dark:text-white">{project.progress || 0}%</span>
                                        </div>
                                    </div>
                                    <div className="flex h-8 w-8 items-center justify-center rounded-full bg-gray-50 text-gray-400 transition-all hover:bg-scribehub-blue hover:text-white dark:bg-gray-800">
                                        <ArrowRight className="h-4 w-4" />
                                    </div>
                                </Link>
                            );
                        })}
                    </div>
                ) : (
                    <div className="rounded-[1.5rem] border border-white bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900/50 overflow-hidden">
                        <table className="w-full text-left">
                            <thead>
                                <tr className="border-b border-gray-50 dark:border-gray-800">
                                    <th className="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-gray-400">{language === 'en' ? 'Project' : 'โปรเจกต์'}</th>
                                    <th className="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-gray-400">{language === 'en' ? 'Status' : 'สถานะ'}</th>
                                    <th className="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-gray-400">{language === 'en' ? 'Priority' : 'ความสำคัญ'}</th>
                                    <th className="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-gray-400 text-right">{language === 'en' ? 'Progress' : 'ความคืบหน้า'}</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-50 dark:divide-gray-800">
                                {filteredProjects.map((project) => {
                                    const theme = projectColors[project.color || 'blue'] || projectColors.blue;
                                    return (
                                        <tr key={project.id} className="group hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-colors">
                                            <td className="px-6 py-3">
                                                <Link href={`/projects/${project.id}`} className="flex items-center gap-3">
                                                    <div className={cn("flex h-8 w-8 items-center justify-center rounded-lg", theme.light)}>
                                                        <IconRenderer iconName={project.icon || 'Folder'} className={cn("h-4 w-4", theme.text)} />
                                                    </div>
                                                    <span className="text-xs font-black text-scribehub-blue dark:text-white truncate max-w-[200px]">{project.name}</span>
                                                </Link>
                                            </td>
                                            <td className="px-6 py-3">
                                                <div className="flex items-center gap-2">
                                                    <div className={cn("h-1.5 w-1.5 rounded-full", statusConfig[project.status]?.color.replace('text', 'bg'))} />
                                                    <span className="text-[10px] font-black uppercase tracking-widest text-gray-600 dark:text-gray-300">
                                                        {t.status[project.status as keyof typeof t.status]}
                                                    </span>
                                                </div>
                                            </td>
                                            <td className="px-6 py-3">
                                                <span className={cn("inline-block rounded-full px-2 py-0.5 text-[9px] font-black uppercase tracking-widest", priorityConfig[project.priority]?.color || 'bg-gray-100')}>
                                                    {t.priority[project.priority as keyof typeof t.priority] || project.priority}
                                                </span>
                                            </td>
                                            <td className="px-6 py-3">
                                                <div className="flex items-center justify-end gap-3 font-mono text-[10px] font-black text-scribehub-blue dark:text-white">
                                                    <div className="w-20 h-1 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                                                        <div className="h-full bg-scribehub-blue transition-all" style={{ width: `${project.progress || 0}%` }} />
                                                    </div>
                                                    <span>{project.progress || 0}%</span>
                                                </div>
                                            </td>
                                        </tr>
                                    );
                                })}
                            </tbody>
                        </table>
                    </div>
                )}
            </div>

            {/* Create Project Modal */}
            <Dialog open={isCreateModalOpen} onOpenChange={setIsCreateModalOpen}>
                <DialogContent className="max-w-md overflow-hidden rounded-[2rem] border-none bg-white p-0 shadow-2xl dark:bg-gray-900">
                    <DialogHeader className="p-8 pb-4">
                        <DialogTitle className="text-2xl font-black text-scribehub-blue dark:text-white">
                            {language === 'en' ? 'Create New Project' : 'สร้างโปรเจกต์ใหม่'}
                        </DialogTitle>
                    </DialogHeader>

                    <form onSubmit={handleCreateProject} className="p-8 pt-0">
                        <div className="space-y-6">
                            <div>
                                <Label className="mb-2 block text-[10px] font-black uppercase tracking-widest text-gray-400">
                                    {language === 'en' ? 'Project Name' : 'ชื่อโปรเจกต์'} *
                                </Label>
                                <Input
                                    value={data.name}
                                    onChange={e => setData('name', e.target.value)}
                                    placeholder={language === 'en' ? 'e.g., Thesis Research' : 'เช่น งานวิจัยวิทยานิพนธ์'}
                                    className="h-12 rounded-xl border-gray-100 bg-gray-50/50 font-bold focus:ring-scribehub-blue dark:border-gray-800 dark:bg-gray-800/50"
                                    required
                                />
                                {errors.name && <p className="mt-1 text-[10px] font-bold text-red-500">{errors.name}</p>}
                            </div>

                            <div>
                                <Label className="mb-2 block text-[10px] font-black uppercase tracking-widest text-gray-400">
                                    {language === 'en' ? 'Description' : 'คำอธิบาย'}
                                </Label>
                                <textarea
                                    value={data.description}
                                    onChange={e => setData('description', e.target.value)}
                                    placeholder={language === 'en' ? 'Brief description...' : 'คำอธิบายสั้นๆ...'}
                                    rows={3}
                                    className="w-full rounded-xl border-gray-100 bg-gray-50/50 p-4 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-scribehub-blue dark:border-gray-800 dark:bg-gray-800/50 dark:text-white"
                                />
                            </div>

                            <div>
                                <Label className="mb-4 block text-[10px] font-black uppercase tracking-widest text-gray-400">
                                    {language === 'en' ? 'Project Color' : 'สีโปรเจกต์'}
                                </Label>
                                <div className="flex flex-wrap gap-2.5">
                                    {Object.entries(projectColors).map(([name, color]) => (
                                        <button
                                            key={name}
                                            type="button"
                                            onClick={() => setData('color', name)}
                                            className={cn(
                                                "h-8 w-8 rounded-full transition-all hover:scale-110 active:scale-95",
                                                color.bg,
                                                data.color === name ? `ring-2 ring-offset-2 ${color.ring} dark:ring-offset-gray-900 scale-110` : ""
                                            )}
                                        />
                                    ))}
                                </div>
                            </div>

                            <div>
                                <Label className="mb-4 block text-[10px] font-black uppercase tracking-widest text-gray-400">
                                    {language === 'en' ? 'Project Icon' : 'ไอคอนโปรเจกต์'}
                                </Label>
                                <div className="grid grid-cols-7 gap-2">
                                    {availableIcons.map((icon) => (
                                        <button
                                            key={icon.name}
                                            type="button"
                                            onClick={() => setData('icon', icon.name as any)}
                                            className={cn(
                                                "flex h-10 w-10 items-center justify-center rounded-xl transition-all hover:bg-gray-100 dark:hover:bg-gray-800",
                                                data.icon === icon.name 
                                                    ? "bg-scribehub-blue text-white shadow-lg shadow-blue-900/20 scale-110" 
                                                    : "bg-gray-50 text-gray-400 dark:bg-gray-800/50"
                                            )}
                                        >
                                            <icon.icon className="h-5 w-5" />
                                        </button>
                                    ))}
                                </div>
                            </div>
                        </div>

                        <div className="mt-10 flex gap-3">
                            <button
                                type="button"
                                onClick={() => setIsCreateModalOpen(false)}
                                className="flex-1 rounded-xl bg-gray-50 py-3 text-xs font-black uppercase tracking-widest text-gray-400 transition-all hover:bg-gray-100 active:scale-95 dark:bg-gray-800 dark:hover:bg-gray-700"
                            >
                                {language === 'en' ? 'Cancel' : 'ยกเลิก'}
                            </button>
                            <button
                                type="submit"
                                disabled={processing}
                                className="flex-[2] rounded-xl bg-scribehub-blue py-3 text-xs font-black uppercase tracking-widest text-white shadow-lg shadow-blue-900/20 transition-all hover:opacity-90 active:scale-95 disabled:opacity-50"
                            >
                                {processing ? (
                                    <div className="flex items-center justify-center gap-2">
                                        <div className="h-3 w-3 animate-spin rounded-full border-2 border-white/30 border-t-white" />
                                        <span>...</span>
                                    </div>
                                ) : (
                                    <div className="flex items-center justify-center gap-2">
                                        <Save className="h-4 w-4" />
                                        <span>{language === 'en' ? 'Create Project' : 'สร้างโปรเจกต์'}</span>
                                    </div>
                                )}
                            </button>
                        </div>
                    </form>
                </DialogContent>
            </Dialog>

            {/* Delete Confirmation Modal */}
            <Dialog open={isDeleteModalOpen} onOpenChange={setIsDeleteModalOpen}>
                <DialogContent className="max-w-sm rounded-[2rem] border-none bg-white p-8 shadow-2xl dark:bg-gray-900">
                    <div className="flex flex-col items-center text-center">
                        <div className="mb-6 flex h-20 w-20 items-center justify-center rounded-3xl bg-red-50 text-red-500 dark:bg-red-900/20">
                            <Trash2 className="h-10 w-10" />
                        </div>
                        <DialogTitle className="text-xl font-black text-scribehub-blue dark:text-white">
                            {language === 'en' ? 'Delete Project?' : 'ลบโปรเจกต์หรือไม่?'}
                        </DialogTitle>
                        <p className="mt-3 text-sm font-medium text-gray-500 dark:text-gray-400">
                            {language === 'en' 
                                ? `Are you sure you want to delete "${projectToDelete?.name}"? This action cannot be undone.` 
                                : `คุณแน่ใจหรือไม่ที่จะลบ "${projectToDelete?.name}"? การดำเนินการนี้ไม่สามารถย้อนกลับได้`}
                        </p>
                        
                        <div className="mt-8 flex w-full gap-3">
                            <button
                                onClick={() => setIsDeleteModalOpen(false)}
                                className="flex-1 rounded-xl bg-gray-50 py-3 text-xs font-black uppercase tracking-widest text-gray-400 transition-all hover:bg-gray-100 active:scale-95 dark:bg-gray-800 dark:hover:bg-gray-700"
                            >
                                {language === 'en' ? 'Cancel' : 'ยกเลิก'}
                            </button>
                            <button
                                onClick={confirmDelete}
                                className="flex-1 rounded-xl bg-red-500 py-3 text-xs font-black uppercase tracking-widest text-white shadow-lg shadow-red-900/20 transition-all hover:opacity-90 active:scale-95"
                            >
                                {language === 'en' ? 'Delete' : 'ลบ'}
                            </button>
                        </div>
                    </div>
                </DialogContent>
            </Dialog>
        </AppLayout>
    );
}
