import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/react';
import { toast } from 'sonner';
import { 
    Plus, FolderOpen, FileText, MoreVertical, Edit, Trash2, Eye, 
    LayoutGrid, List, Filter, Search, CheckCircle2, Clock, 
    AlertCircle, Users, FileStack, Calendar, ArrowRight,
    TrendingUp, CheckCircle, Timer, BookOpen, GraduationCap,
    Microscope, Library, FlaskConical, Beaker, PenTool,
    BookMarked, Briefcase, Heart, Star, Cloud, Sparkles, Menu, Zap,
    Quote, Copy, Check
} from 'lucide-react';
import { useState, useMemo } from 'react';
import { cn } from '@/lib/utils';
import { useLanguage } from '@/contexts/language-context';

// Types
interface Project {
    id: number;
    name: string;
    description: string | null;
    color: string | null;
    icon: string | null;
    status: string;
    priority: string;
    progress: number;
    references_count: number;
    tasks_count: number;
    members_count: number;
    files_count: number;
    created_at: string;
    user: {
        id: number;
        name: string;
        avatar?: string;
    };
    membership_status: string;
}

interface Props {
    projects: Project[];
}

const translations = {
    en: {
        title: "Team Spaces",
        subtitle: "Collaborate on shared research projects",
        search: "Search shared projects...",
        noProjects: "No shared projects",
        noProjectsDesc: "Projects shared with you will appear here once you accept the invitation.",
        references: "Refs",
        tasks: "Tasks",
        members: "Team",
        owner: "Owner",
        view: "Open Space",
        status: {
            planning: "Planning",
            active: "Active",
            completed: "Completed",
            on_hold: "On Hold"
        },
        invitation: {
            title: "Project Invitation",
            desc: "wants to collaborate with you on this project",
            accept: "Accept",
            decline: "Decline"
        }
    },
    th: {
        title: "พื้นที่ทำงานร่วม",
        subtitle: "ทำงานวิจัยร่วมกับทีมในโปรเจกต์ที่แชร์ไว้",
        search: "ค้นหาพื้นที่ทำงาน...",
        noProjects: "ยังไม่มีโปรเจกต์ที่แชร์",
        noProjectsDesc: "โปรเจกต์ที่เพื่อนแชร์กับคุณจะปรากฏที่นี่เมื่อคุณตอบรับคำเชิญแล้ว",
        references: "อ้างอิง",
        tasks: "งาน",
        members: "ทีมงาน",
        owner: "เจ้าของ",
        view: "เข้าสู่พื้นที่",
        status: {
            planning: "กำลังวางแผน",
            active: "กำลังทำ",
            completed: "เสร็จสิ้น",
            on_hold: "พักไว้ก่อน"
        },
        invitation: {
            title: "คำเชิญเข้าร่วมโปรเจกต์",
            desc: "ต้องการทำงานร่วมกับคุณในโปรเจกต์นี้",
            accept: "ยอมรับ",
            decline: "ปฏิเสธ"
        }
    }
};

const projectColors: Record<string, { bg: string, light: string, text: string, border: string }> = {
    blue: { bg: 'bg-blue-500', light: 'bg-blue-50 dark:bg-blue-900/20', text: 'text-blue-600 dark:text-blue-400', border: 'border-blue-100 dark:border-blue-800' },
    green: { bg: 'bg-emerald-500', light: 'bg-emerald-50 dark:bg-emerald-900/20', text: 'text-emerald-600 dark:text-emerald-400', border: 'border-emerald-100 dark:border-emerald-800' },
    purple: { bg: 'bg-purple-500', light: 'bg-purple-50 dark:bg-purple-900/20', text: 'text-purple-600 dark:text-purple-400', border: 'border-purple-100 dark:border-purple-800' },
    orange: { bg: 'bg-orange-500', light: 'bg-orange-50 dark:bg-orange-900/20', text: 'text-orange-600 dark:text-orange-400', border: 'border-orange-100 dark:border-orange-800' },
    pink: { bg: 'bg-pink-500', light: 'bg-pink-50 dark:bg-pink-900/20', text: 'text-pink-600 dark:text-pink-400', border: 'border-pink-100 dark:border-pink-800' },
    cyan: { bg: 'bg-cyan-500', light: 'bg-cyan-50 dark:bg-cyan-900/20', text: 'text-cyan-600 dark:text-cyan-400', border: 'border-cyan-100 dark:border-cyan-800' },
    red: { bg: 'bg-red-500', light: 'bg-red-50 dark:bg-red-900/20', text: 'text-red-600 dark:text-red-400', border: 'border-red-100 dark:border-red-800' },
    amber: { bg: 'bg-amber-500', light: 'bg-amber-50 dark:bg-amber-900/20', text: 'text-amber-600 dark:text-amber-400', border: 'border-amber-100 dark:border-amber-800' },
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

export default function TeamSpacesIndex({ projects }: Props) {
    const { language } = useLanguage();
    const [searchQuery, setSearchQuery] = useState('');
    const t = translations[language];

    const breadcrumbs: BreadcrumbItem[] = [
        { title: t.title, href: '/team-spaces' },
    ];

    const handleAccept = (e: React.MouseEvent, projectName: string, projectId: number) => {
        e.preventDefault();
        e.stopPropagation();
        router.post(`/projects/${projectId}/accept`, {}, {
            onSuccess: () => {
                toast.success(language === 'en' 
                    ? `Welcome! You have joined "${projectName}".` 
                    : `ยินดีต้อนรับ! คุณได้เข้าร่วมโปรเจกต์ "${projectName}" เรียบร้อยแล้ว`
                );
            },
            preserveScroll: true
        });
    };

    const handleDecline = (e: React.MouseEvent, projectId: number) => {
        e.preventDefault();
        e.stopPropagation();
        if (confirm('Are you sure you want to decline this invitation?')) {
            router.post(`/projects/${projectId}/decline`);
        }
    };

    const filteredProjects = useMemo(() => {
        return projects.filter(p => 
            p.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
            p.user.name.toLowerCase().includes(searchQuery.toLowerCase())
        );
    }, [projects, searchQuery]);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t.title} />

            <div className="flex h-full flex-1 flex-col gap-6 bg-gray-50/50 p-8 dark:bg-[#050505]/50 animate-in fade-in duration-700">
                {/* Header Container - Inspired by References page */}
                <div className="flex flex-col gap-6 md:flex-row md:items-center md:justify-between border-b border-gray-100 dark:border-gray-800 pb-6">
                    <div>
                        <div className="flex items-center gap-3 mb-1">
                            <div className="flex h-10 w-10 items-center justify-center rounded-2xl bg-indigo-500 text-white shadow-lg shadow-indigo-500/20">
                                <Users className="h-5 w-5" />
                            </div>
                            <h1 className="text-3xl font-black tracking-tight text-scribehub-blue dark:text-white">
                                {t.title}
                            </h1>
                        </div>
                        <p className="text-xs font-semibold text-gray-400 dark:text-gray-500 ml-1">
                            {t.subtitle}
                        </p>
                    </div>

                    <div className="relative w-full md:w-[320px]">
                        <Search className="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" />
                        <input
                            type="text"
                            placeholder={t.search}
                            value={searchQuery}
                            onChange={(e) => setSearchQuery(e.target.value)}
                            className="w-full rounded-[1.25rem] border-none bg-white py-3.5 pl-11 pr-4 text-xs font-bold shadow-sm transition-all focus:ring-2 focus:ring-indigo-500 dark:bg-gray-900"
                        />
                    </div>
                </div>

                {/* Projects List - Designing it with a "Reference" soul (cleaner, more structured) */}
                {filteredProjects.length === 0 ? (
                    <div className="flex flex-1 flex-col items-center justify-center py-20">
                        <div className="mb-6 flex h-24 w-24 items-center justify-center rounded-[2.5rem] bg-indigo-50 text-indigo-500 dark:bg-indigo-900/20 animate-bounce">
                            <Users className="h-10 w-10" />
                        </div>
                        <h3 className="text-2xl font-black text-scribehub-blue dark:text-white">{t.noProjects}</h3>
                        <p className="mt-3 max-w-sm text-center text-sm font-medium text-gray-500 dark:text-gray-400">{t.noProjectsDesc}</p>
                    </div>
                ) : (
                    <div className="space-y-4 max-w-5xl">
                        {filteredProjects.map((project) => {
                            const theme = projectColors[project.color || 'blue'] || projectColors.blue;
                            return (
                                <Link 
                                    key={project.id}
                                    href={`/projects/${project.id}`}
                                    className="group relative flex flex-col md:flex-row items-center gap-6 rounded-[2rem] border border-white bg-white p-6 shadow-sm shadow-blue-900/5 transition-all hover:shadow-xl hover:-translate-y-1 dark:border-gray-800 dark:bg-gray-900/50"
                                >
                                    <div className={cn("flex h-16 w-16 shrink-0 items-center justify-center rounded-[1.25rem] shadow-inner", theme.light)}>
                                        <IconRenderer iconName={project.icon || 'Folder'} className={cn("h-8 w-8", theme.text)} />
                                    </div>

                                    <div className="flex-1 min-w-0">
                                        <div className="flex items-center gap-3 mb-1">
                                            <h3 className="text-lg font-black text-scribehub-blue dark:text-white truncate group-hover:text-indigo-600 transition-colors">
                                                {project.name}
                                            </h3>
                                            <span className={cn("inline-block rounded-full px-2 py-0.5 text-[9px] font-black uppercase tracking-widest", theme.light, theme.text)}>
                                                {t.status[project.status as keyof typeof t.status]}
                                            </span>
                                        </div>
                                        <p className="text-[11px] font-medium text-gray-400 line-clamp-1 mb-3">{project.description || 'No description provided.'}</p>
                                        
                                        <div className="flex items-center gap-4">
                                            <div className="flex items-center gap-1.5 rounded-lg bg-gray-50 px-2.5 py-1 dark:bg-gray-800/50">
                                                <div className="h-5 w-5 rounded-md overflow-hidden bg-indigo-100 flex items-center justify-center text-[10px] font-black text-indigo-600">
                                                    {project.user.avatar ? (
                                                        <img src={project.user.avatar} className="h-full w-full object-cover" />
                                                    ) : project.user.name[0]}
                                                </div>
                                                <span className="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">{project.user.name}</span>
                                            </div>

                                            <div className="h-4 w-px bg-gray-100 dark:bg-gray-800" />

                                            <div className="flex items-center gap-3">
                                                <div className="flex items-center gap-1">
                                                    <FileText className="h-3 w-3 text-gray-300" />
                                                    <span className="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{project.references_count} {t.references}</span>
                                                </div>
                                                <div className="flex items-center gap-1">
                                                    <CheckCircle2 className="h-3 w-3 text-gray-300" />
                                                    <span className="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{project.tasks_count} {t.tasks}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div className="flex flex-col items-end gap-3 shrink-0">
                                        <div className="text-right">
                                            <div className="text-[9px] font-black text-gray-300 uppercase tracking-[0.2em] mb-1">{t.owner}</div>
                                            <div className="text-xs font-black text-indigo-500/80 italic">{project.user.name}</div>
                                        </div>
                                        
                                        <div className="flex items-center gap-3">
                                            <div className="w-24 h-1.5 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                                                <div 
                                                    className="h-full bg-indigo-500 transition-all duration-1000" 
                                                    style={{ width: `${project.progress || 0}%` }} 
                                                />
                                            </div>
                                            <span className="text-[10px] font-black text-indigo-500">{project.progress || 0}%</span>
                                        </div>
                                    </div>

                                    <div className="absolute right-6 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 group-hover:translate-x-2 transition-all duration-300">
                                        <div className="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-600 text-white shadow-xl shadow-indigo-600/20">
                                            <ArrowRight className="h-5 w-5" />
                                        </div>
                                    </div>

                                    {project.membership_status === 'pending' && (
                                        <div className="absolute inset-0 z-20 flex items-center justify-center rounded-[2rem] bg-indigo-900/40 backdrop-blur-[2px] opacity-0 group-hover:opacity-100 transition-all duration-300">
                                            <div className="flex flex-col items-center gap-4 bg-white dark:bg-gray-900 p-8 rounded-3xl shadow-2xl scale-95 group-hover:scale-100 transition-transform">
                                                <div className="flex flex-col items-center text-center max-w-[200px]">
                                                    <div className="h-12 w-12 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 mb-2">
                                                        <Users className="h-6 w-6" />
                                                    </div>
                                                    <h4 className="text-sm font-black text-scribehub-blue dark:text-white uppercase tracking-wider">{t.invitation.title}</h4>
                                                    <p className="text-[10px] font-bold text-gray-400 mt-1">
                                                        <span className="text-indigo-500">{project.user.name}</span> {t.invitation.desc}
                                                    </p>
                                                </div>
                                                <div className="flex gap-2 w-full">
                                                    <button 
                                                        onClick={(e) => handleDecline(e, project.id)}
                                                        className="flex-1 px-4 py-2 rounded-xl bg-gray-50 dark:bg-gray-800 text-[10px] font-black text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all"
                                                    >
                                                        {t.invitation.decline}
                                                    </button>
                                                    <button 
                                                        onClick={(e) => handleAccept(e, project.name, project.id)}
                                                        className="flex-[2] px-4 py-2 rounded-xl bg-scribehub-blue text-[10px] font-black text-white shadow-lg shadow-blue-500/20 hover:scale-105 active:scale-95 transition-all"
                                                    >
                                                        {t.invitation.accept}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    )}

                                    {project.membership_status === 'pending' && (
                                        <div className="absolute top-4 right-4 z-10 flex items-center gap-1.5 rounded-full bg-amber-500 px-3 py-1 text-[9px] font-black text-white shadow-lg shadow-amber-500/20 animate-pulse">
                                            <Sparkles className="h-3 w-3" />
                                            NEW INVITE
                                        </div>
                                    )}
                                </Link>
                            );
                        })}
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
