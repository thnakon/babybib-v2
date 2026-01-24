import AppLayout from '@/layouts/app-layout';
import { Head, Link } from '@inertiajs/react';
import { useState, useMemo } from 'react';
import { Card, CardContent } from '@/components/ui/card';
import { 
    Share2, Search, BookOpen, 
    ArrowUpRight, Users, Hash,
    Network, Layers, 
    ZoomIn, ZoomOut, Maximize2, Library,
    FileText, Globe, GraduationCap, Sparkles, HelpCircle
} from 'lucide-react';
import { cn } from '@/lib/utils';
import { Reference, BreadcrumbItem } from '@/types';
import { useLanguage } from '@/contexts/language-context';

const translations = {
    en: {
        title: "Literature Map",
        subtitle: "Map semantic clusters across your research repository.",
        searchPlaceholder: "Locate specific papers or authors...",
        works: "WORKS",
        clusterLeader: "Cluster Leader",
        semanticUnavailable: "Semantic Map Unavailable",
        emptyDesc: "Populate your reference library to begin generating semantic clusters and mapping knowledge networks.",
        computationTitle: "Network Computation",
        neuralDiscovery: "Neural Graph Discovery",
        compilingDesc: "Compiling relationships across {count} source documents. We are identifying cross-citations, shared methodologies, and thematic overlaps.",
        mappingActive: "Neural Mapping Active",
        graphOnline: "Visual Graph Online",
        clusters: "Author Clusters",
        nodes: "Source Nodes",
        engine: "Discovery Engine",
        viewGrid: "Grid View",
        viewNetwork: "Network View",
        help: "Help"
    },
    th: {
        title: "แผนผังงานวิจัย",
        subtitle: "สร้างแผนผังกลุ่มความสัมพันธ์จากคลังงานวิจัยของคุณ",
        searchPlaceholder: "ค้นผางานวิจัยหรือชื่อผู้แต่ง...",
        works: "ผลงาน",
        clusterLeader: "ผู้นำกลุ่มวิจัย",
        semanticUnavailable: "ไม่พบข้อมูลแผนผัง",
        emptyDesc: "กรุณาเพิ่มรายการอ้างอิงในคลังของคุณเพื่อเริ่มต้นการสร้างแผนผังความสัมพันธ์และเครือข่ายความรู้",
        computationTitle: "กำลังคำนวณโครงข่าย",
        neuralDiscovery: "การค้นหาความเชื่อมโยงระดับประสาท",
        compilingDesc: "กำลังรวบรวมความสัมพันธ์จากเอกสาร {count} ฉบับ ระบบกำลังตรวจสอบการอ้างอิงข้ามเล่ม ระเบียบวิธีวิจัยที่เหมือนกัน และหัวข้อที่ทับซ้อนกัน",
        mappingActive: "ระบบจำลองโครงข่ายกำลังทำงาน",
        graphOnline: "แผนผังพร้อมแสดงผล",
        clusters: "กลุ่มผู้แต่ง",
        nodes: "ปมแหล่งข้อมูล",
        engine: "ระบบค้นหาอัจฉริยะ",
        viewGrid: "มุมมองตาราง",
        viewNetwork: "มุมมองโครงข่าย",
        help: "ช่วยเหลือ"
    }
};

interface LiteratureMapProps {
    references: Reference[];
}

export default function LiteratureMap({ references }: LiteratureMapProps) {
    const { language, t: globalT } = useLanguage();
    const [searchQuery, setSearchQuery] = useState('');
    const [viewMode, setViewMode] = useState<'grid' | 'network'>('grid');

    const txt = (key: string) => {
        const path = key.split('.');
        let current: any = translations[language as keyof typeof translations];
        for (const p of path) {
            if (current[p]) current = current[p];
            else return key;
        }
        return current;
    };

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: txt('title'),
            href: '/literature-map',
        },
    ];

    const filteredRefs = useMemo(() => {
        return references.filter(ref => 
            ref.title.toLowerCase().includes(searchQuery.toLowerCase()) ||
            ref.authors?.some(a => a.toLowerCase().includes(searchQuery.toLowerCase()))
        );
    }, [references, searchQuery]);

    // Grouping logic for the "Map" feel
    const authorGroups = useMemo(() => {
        const groups: { [key: string]: Reference[] } = {};
        filteredRefs.forEach(ref => {
            const primaryAuthor = ref.authors?.[0] || 'Unknown Author';
            if (!groups[primaryAuthor]) groups[primaryAuthor] = [];
            groups[primaryAuthor].push(ref);
        });
        return Object.entries(groups).sort((a, b) => b[1].length - a[1].length).slice(0, 10);
    }, [filteredRefs]);

    const getIconForType = (type: string) => {
        switch (type.toLowerCase()) {
            case 'book': return <BookOpen className="h-4 w-4" />;
            case 'journal': return <FileText className="h-4 w-4" />;
            case 'website': return <Globe className="h-4 w-4" />;
            case 'thesis': return <GraduationCap className="h-4 w-4" />;
            default: return <Library className="h-4 w-4" />;
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={txt('title')} />
            
            <div className="flex h-full flex-1 flex-col gap-6 bg-scribehub-paper/50 p-6 dark:bg-[#050505]/50 animate-in fade-in duration-700 overflow-hidden">
                {/* Header Section - Matches Reference Page Style */}
                <div className="flex flex-col gap-8 lg:flex-row lg:items-start lg:justify-between">
                    <div className="shrink-0 pt-2">
                        <h1 className="text-3xl font-extrabold text-scribehub-blue dark:text-white leading-none">{txt('title')}</h1>
                        <p className="mt-2 text-sm font-medium text-gray-400 dark:text-gray-500">{txt('subtitle')}</p>
                    </div>

                    <div className="flex-1 max-w-4xl w-full">
                        {/* Search and Help Row */}
                        <div className="flex items-center justify-between mb-2 px-1">
                            <div className="flex items-center gap-6">
                                <span className="text-[10px] font-black uppercase tracking-[0.1em] text-scribehub-blue dark:text-white pb-1 border-b-2 border-scribehub-blue dark:border-white">
                                    {txt('engine')}
                                </span>
                            </div>

                            <Link href="/help" className="flex items-center gap-1.5 text-[10px] font-black uppercase tracking-wider text-gray-400 hover:text-scribehub-blue transition-colors">
                                <HelpCircle className="h-3 w-3" />
                                {txt('help')}
                            </Link>
                        </div>

                        {/* Control Bar */}
                        <div className="relative flex items-center gap-3 rounded-xl border border-gray-100 bg-white p-1.5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:border-gray-800 dark:bg-gray-900/80 dark:shadow-none z-10 transition-all focus-within:ring-4 focus-within:ring-scribehub-blue/5">
                            <div className="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-emerald-50 dark:bg-emerald-900/20">
                                <Search className="h-4 w-4 text-emerald-600" />
                            </div>
                            <input 
                                type="text"
                                placeholder={txt('searchPlaceholder')}
                                value={searchQuery}
                                onChange={(e) => setSearchQuery(e.target.value)}
                                className="flex-1 bg-transparent border-none py-1.5 text-xs font-semibold text-gray-700 placeholder:text-gray-300 focus:ring-0 dark:text-gray-200 dark:placeholder:text-gray-600"
                            />
                            
                            <div className="flex bg-gray-50 dark:bg-gray-800 p-1 rounded-lg border dark:border-gray-700">
                                <button 
                                    onClick={() => setViewMode('grid')}
                                    className={cn("p-1.5 rounded-md transition-all flex items-center gap-2", viewMode === 'grid' ? "bg-white shadow-sm text-emerald-600 dark:bg-gray-700 dark:text-white" : "text-gray-400 hover:text-gray-600")}
                                    title={txt('viewGrid')}
                                >
                                    <Layers className="h-4 w-4" />
                                </button>
                                <button 
                                    onClick={() => setViewMode('network')}
                                    className={cn("p-1.5 rounded-md transition-all flex items-center gap-2", viewMode === 'network' ? "bg-white shadow-sm text-emerald-600 dark:bg-gray-700 dark:text-white" : "text-gray-400 hover:text-gray-600")}
                                    title={txt('viewNetwork')}
                                >
                                    <Network className="h-4 w-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Content Area */}
                <div className="flex-1 overflow-auto custom-scrollbar rounded-2xl">
                    {viewMode === 'grid' ? (
                        <div className="space-y-12 pb-20 max-w-[1400px]">
                            {authorGroups.map(([author, refs], idx) => (
                                <section key={author} className="animate-in fade-in slide-in-from-bottom-4 duration-500" style={{ animationDelay: `${idx * 100}ms` }}>
                                    <div className="flex items-center gap-4 mb-6">
                                        <div className="flex items-center gap-3 px-5 py-2 rounded-xl bg-white dark:bg-gray-900 border dark:border-gray-800 shadow-sm">
                                            <div className="h-7 w-7 rounded-full bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center">
                                                <Users className="h-3.5 w-3.5 text-emerald-600" />
                                            </div>
                                            <div className="flex flex-col">
                                                <span className="text-[11px] font-black uppercase tracking-[0.1em] text-gray-900 dark:text-gray-100">{author}</span>
                                                <span className="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">{txt('clusterLeader')}</span>
                                            </div>
                                            <div className="ml-3 h-5 w-px bg-gray-100 dark:bg-gray-800" />
                                            <span className="text-[10px] font-black text-emerald-600 dark:text-emerald-400 ml-1">{refs.length} {txt('works')}</span>
                                        </div>
                                        <div className="h-px flex-1 bg-gray-100/50 dark:bg-gray-800/50" />
                                    </div>

                                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                                        {refs.map((ref) => (
                                            <Card key={ref.id} className="group hover:border-emerald-500/30 hover:shadow-xl hover:shadow-emerald-500/5 transition-all duration-300 border-none shadow-sm bg-white dark:bg-[#0a0a0a] ring-1 ring-gray-100 dark:ring-gray-800 overflow-hidden rounded-xl">
                                                <CardContent className="p-5">
                                                    <div className="flex justify-between items-start mb-3">
                                                        <div className="flex items-center gap-2">
                                                            <div className="h-7 w-7 rounded-lg bg-gray-50 dark:bg-gray-800 flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                                                                {getIconForType(ref.type)}
                                                            </div>
                                                            <div className="text-[9px] font-black text-gray-400 uppercase tracking-widest px-1">
                                                                {ref.type} • {ref.year || 'N/A'}
                                                            </div>
                                                        </div>
                                                        <button className="h-7 w-7 rounded-full flex items-center justify-center text-gray-300 hover:bg-emerald-50 hover:text-emerald-600 transition-all">
                                                            <ArrowUpRight className="h-3.5 w-3.5" />
                                                        </button>
                                                    </div>
                                                    <h3 className="font-bold text-[13px] leading-relaxed line-clamp-2 mb-4 group-hover:text-emerald-600 transition-colors dark:text-gray-200 h-10">
                                                        {ref.title}
                                                    </h3>
                                                    <div className="flex items-center gap-4 pt-3 border-t border-gray-50 dark:border-gray-800">
                                                        <div className="flex items-center gap-2 text-[9px] text-gray-500 dark:text-gray-400 font-bold uppercase tracking-tighter">
                                                            <Users className="h-3 w-3 text-gray-300" /> 
                                                            <span className="truncate max-w-[100px]">
                                                                {ref.authors?.slice(0, 1).join(', ')}{ref.authors && ref.authors.length > 1 && ' et al.'}
                                                            </span>
                                                        </div>
                                                        {ref.journal_name && (
                                                            <div className="flex items-center gap-2 text-[9px] text-gray-400 dark:text-gray-500 font-bold uppercase tracking-tighter truncate max-w-[120px]">
                                                                <BookOpen className="h-3 w-3 text-gray-300" /> {ref.journal_name}
                                                            </div>
                                                        )}
                                                    </div>
                                                </CardContent>
                                            </Card>
                                        ))}
                                    </div>
                                </section>
                            ))}

                            {authorGroups.length === 0 && (
                                <div className="flex flex-col items-center justify-center py-40 text-center animate-in fade-in duration-1000">
                                    <div className="h-20 w-20 rounded-[2rem] bg-gray-50 dark:bg-gray-800 flex items-center justify-center mb-8 ring-1 ring-gray-100 dark:ring-gray-700 shadow-xl">
                                        <Share2 className="h-8 w-8 text-gray-200 dark:text-gray-700 animate-pulse" />
                                    </div>
                                    <h3 className="text-lg font-black uppercase tracking-[0.2em] text-gray-400">{txt('semanticUnavailable')}</h3>
                                    <p className="text-xs text-gray-500 dark:text-gray-400 mt-4 max-w-xs leading-relaxed font-medium">{txt('emptyDesc')}</p>
                                </div>
                            )}
                        </div>
                    ) : (
                        <div className="h-full w-full relative bg-white dark:bg-black/20 rounded-[2rem] border border-gray-100 dark:border-gray-800 flex items-center justify-center overflow-hidden group/network">
                            <div className="absolute inset-0 bg-[radial-gradient(#f1f1f1_1px,transparent_1px)] dark:bg-[radial-gradient(#1f2937_1px,transparent_1px)] [background-size:32px_32px] opacity-100 group-hover/network:opacity-80 transition-opacity" />
                            
                            <div className="relative flex flex-col items-center gap-6 z-10 max-w-md text-center p-12 bg-white/95 dark:bg-gray-900/95 backdrop-blur-2xl rounded-[3rem] border-none shadow-[0_32px_64px_-16px_rgba(0,0,0,0.08)] dark:shadow-none ring-1 ring-gray-100 dark:ring-gray-800 animate-in zoom-in duration-700">
                                <div className="relative">
                                    <div className="h-16 w-16 bg-emerald-600 rounded-2xl flex items-center justify-center text-white shadow-2xl shadow-emerald-500/30 animate-bounce transition-all duration-1000">
                                        <Network className="h-8 w-8" />
                                    </div>
                                    <div className="absolute -top-1 -right-1 h-5 w-5 rounded-full bg-white dark:bg-gray-800 border-2 border-emerald-500 flex items-center justify-center">
                                        <Sparkles className="h-2.5 w-2.5 text-emerald-500 animate-pulse" />
                                    </div>
                                </div>
                                <div className="space-y-1">
                                    <h3 className="text-lg font-black uppercase tracking-[0.1em] dark:text-gray-100">{txt('computationTitle')}</h3>
                                    <p className="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-4">{txt('neuralDiscovery')}</p>
                                </div>
                                <p className="text-[11px] text-gray-500 dark:text-gray-400 font-medium leading-relaxed px-4">
                                    {txt('compilingDesc').replace('{count}', references.length.toString())}
                                </p>
                                <div className="w-full h-1 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden mt-2">
                                    <div className="h-full bg-emerald-500 w-[65%] animate-[progress_3s_infinite]" />
                                </div>
                                <div className="flex items-center gap-2 text-[9px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-tighter">
                                    <div className="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse" />
                                    {txt('mappingActive')}
                                </div>
                            </div>

                            <div className="absolute bottom-6 right-6 flex items-center bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl rounded-xl p-1.5 border dark:border-gray-800 shadow-xl gap-1.5 animate-in slide-in-from-right-8 duration-700">
                                <button className="h-9 w-9 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 flex items-center justify-center dark:text-gray-300 transition-all active:scale-90"><ZoomIn className="h-4 w-4" /></button>
                                <button className="h-9 w-9 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 flex items-center justify-center dark:text-gray-300 transition-all active:scale-90"><ZoomOut className="h-4 w-4" /></button>
                                <div className="h-5 w-px bg-gray-100 dark:bg-gray-800 mx-1" />
                                <button className="h-9 w-9 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 flex items-center justify-center dark:text-gray-300 transition-all active:scale-90"><Maximize2 className="h-4 w-4" /></button>
                            </div>
                        </div>
                    )}
                </div>

                {/* Footer Status Bar */}
                <div className="h-8 shrink-0 bg-white/80 border-t dark:bg-[#0a0a0a]/80 dark:border-gray-800 flex items-center justify-between px-6 text-[8px] font-black text-gray-400 uppercase tracking-widest transition-all rounded-b-2xl">
                    <div className="flex items-center gap-4">
                        <span className="flex items-center gap-1.5 text-emerald-600 dark:text-emerald-400">
                            <div className="h-1 w-1 rounded-full bg-emerald-500 animate-pulse" /> {txt('graphOnline')}
                        </span>
                        <span className="flex items-center gap-1.5">
                            <Hash className="h-2.5 w-2.5" /> {txt('clusters')}: {authorGroups.length}
                        </span>
                        <span className="flex items-center gap-1.5">
                            <BookOpen className="h-2.5 w-2.5" /> {txt('nodes')}: {references.length}
                        </span>
                    </div>
                    <div className="flex items-center gap-2">
                        {txt('engine')} v1.8 <div className="h-1 w-1 rounded-full bg-gray-200 dark:bg-gray-800" /> {txt('mappingActive')}
                    </div>
                </div>
            </div>
            
            <style dangerouslySetInnerHTML={{ __html: `
                @keyframes progress {
                    0% { transform: translateX(-100%); }
                    100% { transform: translateX(150%); }
                }
                .custom-scrollbar::-webkit-scrollbar {
                    width: 4px;
                }
                .custom-scrollbar::-webkit-scrollbar-track {
                    background: transparent;
                }
                .custom-scrollbar::-webkit-scrollbar-thumb {
                    background: #e5e7eb;
                    border-radius: 10px;
                }
                .dark .custom-scrollbar::-webkit-scrollbar-thumb {
                    background: #374151;
                }
            `}} />
        </AppLayout>
    );
}
