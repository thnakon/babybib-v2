import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import AppLayout from '@/layouts/app-layout';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { BookOpen, FileText, Plus, Search, Star, Clock, List, LayoutGrid, Brain, Library } from 'lucide-react';
import { cn } from '@/lib/utils';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Work Desk',
        href: dashboard().url,
    },
];

export default function Dashboard() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Work Desk - ScribeHub" />
            
            <div className="flex h-full flex-1 flex-col gap-6 overflow-x-auto bg-scribehub-paper/50 p-6 dark:bg-[#050505]/50 animate-in fade-in duration-700">
                {/* Welcome Header */}
                <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 className="text-3xl font-extrabold text-scribehub-blue dark:text-white">Good Morning, Researcher</h1>
                        <p className="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">Pick up where you left off or start something new.</p>
                    </div>
                    <button className="inline-flex items-center gap-2 rounded-xl bg-scribehub-blue px-6 py-3 text-sm font-bold text-white shadow-lg shadow-blue-900/20 hover:opacity-90 active:scale-95 transition-all">
                        <Plus className="h-4 w-4" />
                        New Project
                    </button>
                </div>

                {/* Quick Stats / Summary Cards */}
                <div className="grid gap-6 md:grid-cols-3 lg:grid-cols-4">
                    <div className="group rounded-3xl border border-white bg-white p-6 shadow-sm transition-all hover:shadow-md dark:border-gray-800 dark:bg-gray-900/50">
                        <div className="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-scribehub-blue dark:bg-blue-900/20"><Library className="h-5 w-5" /></div>
                        <div className="text-2xl font-extrabold text-scribehub-blue dark:text-white">24</div>
                        <div className="text-[10px] font-bold uppercase tracking-widest text-gray-400 mt-1">Saved Sources</div>
                    </div>
                    <div className="group rounded-3xl border border-white bg-white p-6 shadow-sm transition-all hover:shadow-md dark:border-gray-800 dark:bg-gray-900/50">
                        <div className="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-green-50 text-emerald-600 dark:bg-green-900/20"><FileText className="h-5 w-5" /></div>
                        <div className="text-2xl font-extrabold text-scribehub-blue dark:text-white">8</div>
                        <div className="text-[10px] font-bold uppercase tracking-widest text-gray-400 mt-1">Active Drafts</div>
                    </div>
                    <div className="group rounded-3xl border border-white bg-white p-6 shadow-sm transition-all hover:shadow-md dark:border-gray-800 dark:bg-gray-900/50">
                        <div className="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-purple-50 text-purple-600 dark:bg-purple-900/20"><Brain className="h-5 w-5" /></div>
                        <div className="text-2xl font-extrabold text-scribehub-blue dark:text-white">12</div>
                        <div className="text-[10px] font-bold uppercase tracking-widest text-gray-400 mt-1">AI Insights</div>
                    </div>
                    <div className="group rounded-3xl border border-scribehub-blue/10 bg-scribehub-blue/5 p-6 shadow-sm transition-all hover:shadow-md dark:border-scribehub-blue/20 dark:bg-scribehub-blue/10">
                        <div className="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-scribehub-blue text-white"><Star className="h-5 w-5" /></div>
                        <div className="text-2xl font-extrabold text-scribehub-blue dark:text-white">Pro Plan</div>
                        <div className="text-[10px] font-bold uppercase tracking-widest text-blue-400 mt-1">Researcher Tier</div>
                    </div>
                </div>

                {/* Main Content Area */}
                <div className="grid gap-6 lg:grid-cols-3">
                    {/* Recent Projects */}
                    <div className="lg:col-span-2 space-y-4">
                        <div className="flex items-center justify-between">
                            <h2 className="text-lg font-extrabold text-scribehub-blue dark:text-white">Recent Projects</h2>
                            <button className="text-[11px] font-bold text-gray-400 hover:text-scribehub-blue transition-colors">View All</button>
                        </div>
                        <div className="grid gap-4 sm:grid-cols-2">
                            {[
                                { title: "Thesis Chapter 1", desc: "Introduction and Literature Review", date: "2 hours ago", color: "bg-blue-500" },
                                { title: "Research Methodology", desc: "Data collection methods and sampling", date: "Yesterday", color: "bg-emerald-500" },
                                { title: "Literature Analysis", desc: "Comparative study of recent works", date: "3 days ago", color: "bg-amber-500" },
                                { title: "Paper Draft #2", desc: "Final revisions for submission", date: "1 week ago", color: "bg-purple-500" }
                            ].map((project, i) => (
                                <div key={i} className="group rounded-3xl border border-white bg-white p-5 shadow-sm transition-all hover:shadow-lg hover:-translate-y-1 dark:border-gray-800 dark:bg-gray-900/50">
                                    <div className="mb-4 flex items-center justify-between">
                                        <div className={cn("h-8 w-8 rounded-xl flex items-center justify-center text-white", project.color)}>
                                            <Folder className="h-4 w-4" />
                                        </div>
                                        <div className="text-[10px] font-bold text-gray-400 flex items-center gap-1"><Clock className="h-3 w-3" /> {project.date}</div>
                                    </div>
                                    <h3 className="font-bold text-scribehub-blue dark:text-white transition-colors group-hover:text-scribehub-blue/80">{project.title}</h3>
                                    <p className="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed line-clamp-2">{project.desc}</p>
                                    <div className="mt-6 flex items-center gap-2">
                                        <div className="flex -space-x-2">
                                            {[...Array(3)].map((_, j) => <div key={j} className="h-6 w-6 rounded-full border-2 border-white bg-gray-100 dark:border-gray-900" />)}
                                        </div>
                                        <span className="text-[10px] font-bold text-gray-400">+2 more</span>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>

                    {/* Activity Feed */}
                    <div className="space-y-4">
                        <div className="flex items-center justify-between">
                            <h2 className="text-lg font-extrabold text-scribehub-blue dark:text-white">Activity</h2>
                            <button className="text-[11px] font-bold text-gray-400 hover:text-scribehub-blue transition-colors">Clear</button>
                        </div>
                        <div className="rounded-3xl border border-white bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900/50 h-full">
                            <div className="space-y-6">
                                {[
                                    { text: "Imported APA 7th Edition style", time: "10m ago", icon: List },
                                    { text: "AI summarized 'Market Trends 2024'", time: "45m ago", icon: Brain },
                                    { text: "Added 5 new references via DOI", time: "2h ago", icon: Plus },
                                    { text: "Shared 'Thesis' with Dr. Smith", time: "1d ago", icon: Search },
                                    { text: "Project 'Review' backed up", time: "2d ago", icon: Clock }
                                ].map((item, i) => (
                                    <div key={i} className="flex gap-4">
                                        <div className="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-gray-50 text-gray-400 dark:bg-gray-800"><item.icon className="h-4 w-4" /></div>
                                        <div className="space-y-1">
                                            <p className="text-xs font-bold text-scribehub-blue dark:text-white leading-snug">{item.text}</p>
                                            <p className="text-[10px] font-medium text-gray-400">{item.time}</p>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}

// Re-using Folder icon
function Folder(props: any) {
    return (
        <svg
            {...props}
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="24"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            strokeWidth="2"
            strokeLinecap="round"
            strokeLinejoin="round"
        >
            <path d="M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z" />
        </svg>
    )
}
