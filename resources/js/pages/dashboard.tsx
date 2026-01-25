import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import AppLayout from '@/layouts/app-layout';
import { dashboard } from '@/routes';
import type { BreadcrumbItem, User } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';
import { BookOpen, FileText, Plus, Search, Star, Clock, List, LayoutGrid, Brain, Library, FolderOpen, GraduationCap, Microscope, FlaskConical, Beaker, PenTool, BookMarked, Briefcase, Heart, Cloud, Sparkles, Folder } from 'lucide-react';
import { cn } from '@/lib/utils';
import { formatDistanceToNow } from 'date-fns';

interface Project {
    id: number;
    name: string;
    description: string | null;
    color: string | null;
    icon: string | null;
    updated_at: string;
    members: { user: User }[];
}

interface Activity {
    id: string;
    text: string;
    time: string;
    type: string;
}

interface Props {
    stats: {
        references_count: number;
        projects_count: number;
        manuscripts_count: number;
        notifications_count: number;
    };
    recentProjects: Project[];
    activities: Activity[];
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

const availableIcons: Record<string, any> = {
    Folder: FolderOpen,
    BookOpen: BookOpen,
    GraduationCap: GraduationCap,
    Microscope: Microscope,
    Library: Library,
    FlaskConical: FlaskConical,
    Beaker: Beaker,
    PenTool: PenTool,
    BookMarked: BookMarked,
    Briefcase: Briefcase,
    Heart: Heart,
    Star: Star,
    Cloud: Cloud,
    Sparkles: Sparkles,
};

const projectColors: Record<string, string> = {
    blue: 'bg-blue-500',
    green: 'bg-emerald-500',
    purple: 'bg-purple-500',
    orange: 'bg-orange-500',
    pink: 'bg-pink-500',
    cyan: 'bg-cyan-500',
    red: 'bg-red-500',
    amber: 'bg-amber-500',
};

export default function Dashboard({ stats, recentProjects, activities }: Props) {
    const { auth } = usePage<any>().props;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard - ScribeHub" />
            
            <div className="flex h-full flex-1 flex-col gap-6 overflow-x-auto bg-scribehub-paper/50 p-6 dark:bg-[#050505]/50 animate-in fade-in duration-700">
                {/* Welcome Header */}
                <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 className="text-3xl font-extrabold text-scribehub-blue dark:text-white">
                            Welcome back, {auth.user.name.split(' ')[0]}
                        </h1>
                        <p className="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">Ready to dive back into your research?</p>
                    </div>
                    <Link 
                        href="/projects" 
                        className="inline-flex items-center gap-2 rounded-xl bg-scribehub-blue px-6 py-3 text-sm font-bold text-white shadow-lg shadow-blue-900/20 hover:opacity-90 active:scale-95 transition-all"
                    >
                        <Plus className="h-4 w-4" />
                        New Project
                    </Link>
                </div>

                {/* Quick Stats / Summary Cards */}
                <div className="grid gap-6 md:grid-cols-3 lg:grid-cols-4">
                    <div className="group rounded-3xl border border-white bg-white p-6 shadow-sm transition-all hover:shadow-md dark:border-gray-800 dark:bg-gray-900/50">
                        <div className="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-scribehub-blue dark:bg-blue-900/20"><Library className="h-5 w-5" /></div>
                        <div className="text-2xl font-extrabold text-scribehub-blue dark:text-white">{stats.references_count}</div>
                        <div className="text-[10px] font-bold uppercase tracking-widest text-gray-400 mt-1">Saved Sources</div>
                    </div>
                    <div className="group rounded-3xl border border-white bg-white p-6 shadow-sm transition-all hover:shadow-md dark:border-gray-800 dark:bg-gray-900/50">
                        <div className="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-green-50 text-emerald-600 dark:bg-green-900/20"><FileText className="h-5 w-5" /></div>
                        <div className="text-2xl font-extrabold text-scribehub-blue dark:text-white">{stats.manuscripts_count}</div>
                        <div className="text-[10px] font-bold uppercase tracking-widest text-gray-400 mt-1">Active Drafts</div>
                    </div>
                    <div className="group rounded-3xl border border-white bg-white p-6 shadow-sm transition-all hover:shadow-md dark:border-gray-800 dark:bg-gray-900/50">
                        <div className="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-purple-50 text-purple-600 dark:bg-purple-900/20"><Brain className="h-5 w-5" /></div>
                        <div className="text-2xl font-extrabold text-scribehub-blue dark:text-white">{stats.projects_count}</div>
                        <div className="text-[10px] font-bold uppercase tracking-widest text-gray-400 mt-1">Active Projects</div>
                    </div>
                    <div className="group rounded-3xl border border-scribehub-blue/10 bg-scribehub-blue/5 p-6 shadow-sm transition-all hover:shadow-md dark:border-scribehub-blue/20 dark:bg-scribehub-blue/10">
                        <div className="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-scribehub-blue text-white"><Star className="h-5 w-5" /></div>
                        <div className="text-2xl font-extrabold text-scribehub-blue dark:text-white">Pro Plan</div>
                        <div className="text-[10px] font-bold uppercase tracking-widest text-blue-400 mt-1">Premium Tier</div>
                    </div>
                </div>

                {/* Main Content Area */}
                <div className="grid gap-6 lg:grid-cols-3">
                    {/* Recent Projects */}
                    <div className="lg:col-span-2 space-y-4">
                        <div className="flex items-center justify-between">
                            <h2 className="text-lg font-extrabold text-scribehub-blue dark:text-white">Recent Projects</h2>
                            <Link href="/projects" className="text-[11px] font-bold text-gray-400 hover:text-scribehub-blue transition-colors">View All</Link>
                        </div>
                        <div className="grid gap-4 sm:grid-cols-2">
                            {recentProjects.length > 0 ? (
                                recentProjects.map((project) => {
                                    const IconComponent = availableIcons[project.icon || 'Folder'] || Folder;
                                    const colorClass = projectColors[project.color || 'blue'] || 'bg-blue-500';
                                    return (
                                        <Link 
                                            key={project.id} 
                                            href={`/projects/${project.id}`}
                                            className="group rounded-3xl border border-white bg-white p-5 shadow-sm transition-all hover:shadow-lg hover:-translate-y-1 dark:border-gray-800 dark:bg-gray-900/50"
                                        >
                                            <div className="mb-4 flex items-center justify-between">
                                                <div className={cn("h-8 w-8 rounded-xl flex items-center justify-center text-white", colorClass)}>
                                                    <IconComponent className="h-4 w-4" />
                                                </div>
                                                <div className="text-[10px] font-bold text-gray-400 flex items-center gap-1">
                                                    <Clock className="h-3 w-3" /> 
                                                    {formatDistanceToNow(new Date(project.updated_at), { addSuffix: true })}
                                                </div>
                                            </div>
                                            <h3 className="font-bold text-scribehub-blue dark:text-white transition-colors group-hover:text-scribehub-blue/80 truncate">
                                                {project.name}
                                            </h3>
                                            <p className="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed line-clamp-2">
                                                {project.description || 'No description provided'}
                                            </p>
                                            <div className="mt-6 flex items-center gap-2">
                                                <div className="flex -space-x-2">
                                                    {project.members?.slice(0, 3).map((member, j) => (
                                                        <div key={j} className="h-6 w-6 rounded-full border-2 border-white bg-gray-100 dark:border-gray-900 overflow-hidden shadow-sm">
                                                            <img 
                                                                src={member.user.avatar || `https://ui-avatars.com/api/?name=${encodeURIComponent(member.user.name)}&color=7F9CF5&background=EBF4FF`} 
                                                                alt={member.user.name} 
                                                                className="h-full w-full object-cover"
                                                            />
                                                        </div>
                                                    ))}
                                                </div>
                                                {project.members && project.members.length > 3 && (
                                                    <span className="text-[10px] font-bold text-gray-400">+{project.members.length - 3} more</span>
                                                )}
                                            </div>
                                        </Link>
                                    );
                                })
                            ) : (
                                <div className="col-span-full py-12 flex flex-col items-center justify-center rounded-3xl border-2 border-dashed border-gray-100 dark:border-gray-800 bg-white/50">
                                    <Folder className="h-10 w-10 text-gray-200 mb-2" />
                                    <p className="text-sm font-bold text-gray-400">No projects yet</p>
                                    <Link href="/projects" className="mt-4 text-[10px] font-black uppercase text-scribehub-blue underline">Create your first project</Link>
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Activity Feed */}
                    <div className="space-y-4">
                        <div className="flex items-center justify-between">
                            <h2 className="text-lg font-extrabold text-scribehub-blue dark:text-white">Activity</h2>
                            <button className="text-[11px] font-bold text-gray-400 hover:text-scribehub-blue transition-colors">Clear</button>
                        </div>
                        <div className="rounded-3xl border border-white bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900/50 min-h-[400px]">
                            <div className="space-y-6">
                                {activities.length > 0 ? (
                                    activities.map((item) => (
                                        <div key={item.id} className="flex gap-4">
                                            <div className="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-gray-50 text-gray-400 dark:bg-gray-800">
                                                <ActivityIcon type={item.type} />
                                            </div>
                                            <div className="space-y-1">
                                                <p className="text-xs font-bold text-scribehub-blue dark:text-white leading-snug">{item.text}</p>
                                                <p className="text-[10px] font-medium text-gray-400">{item.time}</p>
                                            </div>
                                        </div>
                                    ))
                                ) : (
                                    <div className="flex h-full flex-col items-center justify-center py-20 opacity-30">
                                        <Clock className="h-10 w-10 mb-2" />
                                        <p className="text-[10px] font-bold uppercase tracking-widest text-center">No recent activity</p>
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}

function ActivityIcon({ type }: { type: string }) {
    if (type.includes('Project')) return <Folder className="h-4 w-4" />;
    if (type.includes('AI') || type.includes('Summary')) return <Brain className="h-4 w-4" />;
    if (type.includes('Reference') || type.includes('Import')) return <Plus className="h-4 w-4" />;
    return <Clock className="h-4 w-4" />;
}


