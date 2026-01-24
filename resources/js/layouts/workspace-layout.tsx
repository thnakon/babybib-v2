import AppLogo from '@/components/app-logo';
import { NavUser } from '@/components/nav-user';
import { SidebarProvider } from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { Link, router } from '@inertiajs/react';
import { 
    ArrowLeft, Moon, Sun, Monitor, Check, 
    FileText, BookOpen, Library, Plus, ChevronDown, 
    Trash2, Download, Sparkles, FileDown, Search, Quote, Save
} from 'lucide-react';
import { useAppearance } from '@/hooks/use-appearance';
import { cn } from '@/lib/utils';
import { useState, useEffect, useRef } from 'react';
import { 
    DropdownMenu, 
    DropdownMenuContent, 
    DropdownMenuItem, 
    DropdownMenuTrigger, 
    DropdownMenuLabel, 
    DropdownMenuSeparator 
} from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import axios from 'axios';
import { Reference } from '@/types';
import { toast } from 'sonner';
import { useLanguage } from '@/contexts/language-context';

const translations = {
    en: {
        back: "Back to Dashboard",
        switchManuscript: "Switch Manuscript",
        newManuscript: "New Manuscript",
        deleteManuscript: "Delete Manuscript",
        deleteConfirm: "Are you sure you want to delete this manuscript?",
        deleteWarning: "This action cannot be undone. All content within this manuscript will be permanently removed.",
        editor: "Editor",
        pdf: "PDF",
        library: "Library",
        sources: "Sources",
        quickCite: "Quick Citation",
        clickToInsert: "Click to insert",
        findSource: "Find a source...",
        noSources: "No sources found",
        save: "Save",
        titleUpdated: "Title updated",
        titleFailed: "Failed to update title",
        export: "Export",
        download: "Download",
        word: "Word (.docx)",
        pdfFile: "PDF (.pdf)",
        user: "User",
        promptTitle: "Enter manuscript title:",
        create: "Create",
        cancel: "Cancel",
        delete: "Delete",
        manuscriptName: "Manuscript Name",
        placeholderTitle: "e.g., My Research Paper"
    },
    th: {
        back: "กลับไปยังแดชบอร์ด",
        switchManuscript: "สลับต้นฉบับ",
        newManuscript: "เพิ่มต้นฉบับใหม่",
        deleteManuscript: "ลบต้นฉบับ",
        deleteConfirm: "คุณแน่ใจหรือไม่ว่าต้องการลบต้นฉบับนี้?",
        deleteWarning: "การดำเนินการนี้ไม่สามารถย้อนกลับได้ เนื้อหาทั้งหมดในต้นฉบับนี้จะถูกลบออกอย่างถาวร",
        editor: "เครื่องมือเขียน",
        pdf: "เปิดไฟล์ PDF",
        library: "คลังข้อมูล",
        sources: "แหล่งอ้างอิง",
        quickCite: "อ้างอิงด่วน",
        clickToInsert: "คลิกเพื่อแทรกการอ้างอิง",
        findSource: "ค้นหาแหล่งข้อมูล...",
        noSources: "ไม่พบแหล่งข้อมูล",
        save: "บันทึก",
        titleUpdated: "อัปเดตชื่อเรื่องแล้ว",
        titleFailed: "อัปเดตชื่อเรื่องไม่สำเร็จ",
        export: "ส่งออก",
        download: "ดาวน์โหลด",
        word: "ไฟล์ Word (.docx)",
        pdfFile: "ไฟล์ PDF (.pdf)",
        user: "ผู้ใช้",
        promptTitle: "ระบุชื่อเรื่องของต้นฉบับ:",
        create: "สร้าง",
        cancel: "ยกเลิก",
        delete: "ลบ",
        manuscriptName: "ชื่อต้นฉบับ",
        placeholderTitle: "เช่น งานวิจัยของฉัน"
    }
};

interface Manuscript {
    id: number;
    title: string;
}

interface WorkspaceLayoutProps {
    children: React.ReactNode;
    manuscriptId?: number;
    manuscripts?: Manuscript[];
    references?: Reference[];
    activeTab: 'editor' | 'pdf';
    setActiveTab: (tab: 'editor' | 'pdf') => void;
    onCite?: (ref: Reference) => void;
    onSave?: () => void;
}

import { 
    Dialog, 
    DialogContent, 
    DialogHeader, 
    DialogTitle, 
    DialogFooter,
    DialogDescription 
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';

export default function WorkspaceLayout({ 
    children, 
    manuscriptId, 
    manuscripts = [],
    references = [],
    activeTab,
    setActiveTab,
    onCite,
    onSave
}: WorkspaceLayoutProps) {
    const { language, t } = useLanguage();
    const { appearance, updateAppearance } = useAppearance();
    const [title, setTitle] = useState('');
    const [isSavingTitle, setIsSavingTitle] = useState(false);
    const [refSearch, setRefSearch] = useState('');
    const titleTimeoutRef = useRef<NodeJS.Timeout | null>(null);

    // Dialog states
    const [showNewModal, setShowNewModal] = useState(false);
    const [newManuscriptTitle, setNewManuscriptTitle] = useState('');
    const [showDeleteModal, setShowDeleteModal] = useState(false);
    const [deletingManuscriptId, setDeletingManuscriptId] = useState<number | null>(null);

    const txt = (key: string) => t(key, translations) as string;

    const currentManuscript = manuscripts.find(m => m.id === manuscriptId);

    useEffect(() => {
        if (currentManuscript) {
            setTitle(currentManuscript.title);
        }
    }, [manuscriptId, currentManuscript]);

    const handleTitleChange = (newTitle: string) => {
        setTitle(newTitle);
        if (titleTimeoutRef.current) clearTimeout(titleTimeoutRef.current);
        
        titleTimeoutRef.current = setTimeout(() => {
            saveTitle(newTitle);
        }, 1500);
    };

    const saveTitle = async (val: string) => {
        if (!manuscriptId || !val.trim()) return;
        setIsSavingTitle(true);
        try {
            await axios.patch(`/workspace/${manuscriptId}`, { title: val });
            toast.success(txt('titleUpdated'));
        } catch (error) {
            toast.error(txt('titleFailed'));
        } finally {
            setIsSavingTitle(false);
        }
    };

    const handleCreateSubmit = () => {
        if (!newManuscriptTitle.trim()) return;
        router.post('/workspace', { title: newManuscriptTitle }, {
            onSuccess: () => {
                setShowNewModal(false);
                setNewManuscriptTitle('');
            }
        });
    };

    const confirmDelete = () => {
        if (deletingManuscriptId) {
            router.delete(`/workspace/${deletingManuscriptId}`, {
                onSuccess: () => setShowDeleteModal(false)
            });
        }
    };

    const handleExport = (format: 'pdf' | 'word') => {
        if (!manuscriptId) return;
        window.location.href = `/workspace/${manuscriptId}/export/${format}`;
    };

    const filteredRefs = references.filter(ref => 
        ref.title.toLowerCase().includes(refSearch.toLowerCase()) ||
        ref.authors?.some(a => a.toLowerCase().includes(refSearch.toLowerCase()))
    );

    return (
        <SidebarProvider defaultOpen={false}>
            <div className="flex h-svh w-full flex-col bg-white dark:bg-[#050505] overflow-hidden">
                <header className="flex h-14 shrink-0 items-center justify-between border-b bg-white px-2 shadow-sm dark:border-gray-800 dark:bg-[#0a0a0a] lg:px-4">
                    <div className="flex items-center gap-1 sm:gap-2 overflow-hidden flex-1">
                        <Link 
                            href={dashboard()} 
                            className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800"
                            title={txt('back')}
                        >
                            <ArrowLeft className="h-5 w-5" />
                        </Link>
                        
                        <div className="flex items-center gap-1 overflow-hidden max-w-[300px] lg:max-w-[400px]">
                            <Input
                                value={title}
                                onChange={(e) => handleTitleChange(e.target.value)}
                                className={cn(
                                    "h-8 border-transparent bg-transparent px-2 font-bold focus:border-blue-500 focus:bg-white dark:focus:bg-gray-800 dark:text-gray-100 transition-all truncate",
                                    isSavingTitle && "opacity-50"
                                )}
                            />
                            
                            <DropdownMenu>
                                <DropdownMenuTrigger asChild>
                                    <button className="h-8 w-8 flex items-center justify-center rounded-md hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                        <ChevronDown className="h-4 w-4 opacity-50 dark:text-gray-400" />
                                    </button>
                                </DropdownMenuTrigger>
                                <DropdownMenuContent align="start" className="w-64 dark:bg-gray-900 dark:border-gray-800">
                                    <DropdownMenuLabel className="dark:text-gray-200">{txt('switchManuscript')}</DropdownMenuLabel>
                                    <DropdownMenuSeparator className="dark:bg-gray-800" />
                                    {manuscripts.map((m) => (
                                        <DropdownMenuItem key={m.id} asChild>
                                            <div className="flex w-full items-center justify-between gap-2 group cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 px-2 py-1.5 rounded-md">
                                                <Link href={`/workspace?manuscript_id=${m.id}`} className="flex-1 truncate py-0.5 text-xs font-medium dark:text-gray-300">
                                                    {m.title}
                                                </Link>
                                                {manuscripts.length > 1 && (
                                                    <button 
                                                        onClick={(e) => { 
                                                            e.preventDefault(); 
                                                            setDeletingManuscriptId(m.id);
                                                            setShowDeleteModal(true);
                                                        }} 
                                                        className="text-red-500 p-1 opacity-0 group-hover:opacity-100 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-md transition-all"
                                                    >
                                                        <Trash2 className="h-3.5 w-3.5" />
                                                    </button>
                                                )}
                                            </div>
                                        </DropdownMenuItem>
                                    ))}
                                    <DropdownMenuSeparator className="dark:bg-gray-800" />
                                    <DropdownMenuItem onClick={() => setShowNewModal(true)} className="text-blue-600 font-bold dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 cursor-pointer">
                                        <Plus className="mr-2 h-4 w-4" /> {txt('newManuscript')}
                                    </DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </div>
                    </div>

                    <div className="flex items-center gap-1 sm:gap-2">
                        <div className="hidden sm:flex items-center gap-1 rounded-full bg-gray-100 p-1 dark:bg-gray-800">
                            <button 
                                onClick={() => setActiveTab('editor')}
                                className={cn(
                                    "flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-bold transition-all",
                                    activeTab === 'editor' ? "bg-white text-blue-600 shadow-sm dark:bg-gray-700 dark:text-white" : "text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                                )}
                            >
                                <FileText className="h-4 w-4" /> {txt('editor')}
                            </button>
                            <button 
                                onClick={() => setActiveTab('pdf')}
                                className={cn(
                                    "flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-bold transition-all",
                                    activeTab === 'pdf' ? "bg-white text-blue-600 shadow-sm dark:bg-gray-700 dark:text-white" : "text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                                )}
                            >
                                <BookOpen className="h-4 w-4" /> {txt('pdf')}
                            </button>
                        </div>

                        <DropdownMenu>
                            <DropdownMenuTrigger asChild>
                                <button className="flex h-9 items-center gap-1.5 rounded-full border px-3 text-xs font-bold transition-all hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-800 dark:text-gray-300">
                                    <Library className="h-4 w-4 text-emerald-500" />
                                    <span>{txt('sources')}</span>
                                    <ChevronDown className="h-3 w-3 opacity-50" />
                                </button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent align="end" className="w-80 max-h-[70vh] flex flex-col dark:bg-gray-900 dark:border-gray-800">
                                <DropdownMenuLabel className="flex items-center justify-between dark:text-gray-200">
                                    {txt('quickCite')}
                                    <span className="text-[10px] font-normal text-gray-400">{txt('clickToInsert')}</span>
                                </DropdownMenuLabel>
                                <div className="p-2 pt-0">
                                    <div className="relative">
                                        <Search className="absolute left-2.5 top-2.5 h-3.5 w-3.5 text-gray-400" />
                                        <Input
                                            placeholder={txt('findSource')}
                                            className="h-8 pl-8 text-xs dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200"
                                            value={refSearch}
                                            onChange={(e) => setRefSearch(e.target.value)}
                                            onClick={(e) => e.stopPropagation()}
                                        />
                                    </div>
                                </div>
                                <DropdownMenuSeparator className="dark:bg-gray-800" />
                                <div className="flex-1 overflow-y-auto overflow-x-hidden min-h-0 py-1 px-1 custom-scrollbar">
                                    {filteredRefs.length === 0 ? (
                                        <div className="p-4 text-center text-xs text-gray-400">{txt('noSources')}</div>
                                    ) : (
                                        filteredRefs.map((ref) => (
                                            <DropdownMenuItem 
                                                key={ref.id} 
                                                onClick={() => onCite?.(ref)}
                                                className="flex flex-col items-start gap-0.5 p-2 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 rounded-md"
                                            >
                                                <div className="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-tighter">
                                                    {ref.year || 'n.d.'} • {ref.type}
                                                </div>
                                                <div className="text-xs font-bold line-clamp-1 dark:text-gray-200">{ref.title}</div>
                                                <div className="text-[10px] text-gray-500 dark:text-gray-400 truncate w-full">
                                                    {ref.authors?.join(', ')}
                                                </div>
                                            </DropdownMenuItem>
                                        ))
                                    )}
                                </div>
                            </DropdownMenuContent>
                        </DropdownMenu>

                        <button 
                            onClick={onSave}
                            className="flex h-9 items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-3 text-xs font-bold text-emerald-700 transition-all hover:bg-emerald-100 active:scale-95 dark:border-emerald-900/30 dark:bg-emerald-900/20 dark:text-emerald-400"
                        >
                            <Save className="h-4 w-4" />
                            <span className="hidden md:inline">{txt('save')}</span>
                        </button>

                        <DropdownMenu>
                            <DropdownMenuTrigger asChild>
                                <button className="flex h-9 items-center gap-1.5 rounded-full bg-blue-600 px-3 lg:px-4 text-xs font-bold text-white shadow-md transition-all hover:bg-blue-700 active:scale-95">
                                    <FileDown className="h-4 w-4" /> 
                                    <span className="hidden xs:inline lg:inline">{txt('export')}</span>
                                </button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent align="end" className="w-56 dark:bg-gray-900 dark:border-gray-800">
                                <DropdownMenuLabel className="dark:text-gray-200">{txt('download')}</DropdownMenuLabel>
                                <DropdownMenuSeparator className="dark:bg-gray-800" />
                                <DropdownMenuItem onClick={() => handleExport('word')} className="cursor-pointer dark:hover:bg-gray-800 px-2 py-2">
                                    <FileText className="mr-2 h-4 w-4 text-blue-500" /> <span className="text-xs font-medium dark:text-gray-300">{txt('word')}</span>
                                </DropdownMenuItem>
                                <DropdownMenuItem onClick={() => handleExport('pdf')} className="cursor-pointer dark:hover:bg-gray-800 px-2 py-2">
                                    <FileDown className="mr-2 h-4 w-4 text-red-500" /> <span className="text-xs font-medium dark:text-gray-300">{txt('pdfFile')}</span>
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </div>
                </header>

                <main className="flex-1 min-h-0 relative overflow-hidden flex flex-col">
                    {children}
                </main>

                <nav className="flex sm:hidden h-16 shrink-0 items-center justify-around border-t bg-white px-2 shadow-[0_-4px_10px_rgba(0,0,0,0.05)] dark:border-gray-800 dark:bg-[#0a0a0a]">
                    <button 
                        onClick={() => setActiveTab('editor')}
                        className={cn(
                            "flex flex-col items-center justify-center gap-1 grow py-2 transition-all",
                            activeTab === 'editor' ? "text-blue-600" : "text-gray-400"
                        )}
                    >
                        <FileText className={cn("h-5 w-5", activeTab === 'editor' && "scale-110")} />
                        <span className="text-[10px] font-bold uppercase">{txt('editor')}</span>
                    </button>
                    <button 
                        onClick={() => setActiveTab('pdf')}
                        className={cn(
                            "flex flex-col items-center justify-center gap-1 grow py-2 transition-all",
                            activeTab === 'pdf' ? "text-blue-600" : "text-gray-400"
                        )}
                    >
                        <BookOpen className={cn("h-5 w-5", activeTab === 'pdf' && "scale-110")} />
                        <span className="text-[10px] font-bold uppercase">{txt('library')}</span>
                    </button>
                    <div className="flex flex-col items-center justify-center gap-1 grow py-2 text-gray-400">
                        <NavUser />
                        <span className="text-[10px] font-bold uppercase">{txt('user')}</span>
                    </div>
                </nav>

                {/* Modals */}
                <Dialog open={showNewModal} onOpenChange={setShowNewModal}>
                    <DialogContent className="sm:max-w-md dark:bg-gray-900 dark:border-gray-800">
                        <DialogHeader>
                            <DialogTitle className="dark:text-gray-100">{txt('newManuscript')}</DialogTitle>
                            <DialogDescription className="dark:text-gray-400">
                                {txt('promptTitle')}
                            </DialogDescription>
                        </DialogHeader>
                        <div className="grid gap-4 py-4">
                            <div className="grid gap-2">
                                <Label htmlFor="name" className="dark:text-gray-300">{txt('manuscriptName')}</Label>
                                <Input
                                    id="name"
                                    value={newManuscriptTitle}
                                    onChange={(e) => setNewManuscriptTitle(e.target.value)}
                                    placeholder={txt('placeholderTitle')}
                                    className="dark:bg-gray-800 dark:border-gray-700 dark:text-gray-100"
                                    autoFocus
                                    onKeyDown={(e) => e.key === 'Enter' && handleCreateSubmit()}
                                />
                            </div>
                        </div>
                        <DialogFooter>
                            <Button variant="outline" onClick={() => setShowNewModal(false)} className="dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                                {txt('cancel')}
                            </Button>
                            <Button onClick={handleCreateSubmit} disabled={!newManuscriptTitle.trim()} className="bg-blue-600 hover:bg-blue-700 text-white">
                                {txt('create')}
                            </Button>
                        </DialogFooter>
                    </DialogContent>
                </Dialog>

                <Dialog open={showDeleteModal} onOpenChange={setShowDeleteModal}>
                    <DialogContent className="sm:max-w-md dark:bg-gray-900 dark:border-gray-800">
                        <DialogHeader>
                            <DialogTitle className="text-red-600 dark:text-red-500">{txt('deleteManuscript')}</DialogTitle>
                            <DialogDescription className="dark:text-gray-400">
                                {txt('deleteConfirm')}
                                <div className="mt-2 p-3 bg-red-50 dark:bg-red-900/10 rounded-lg text-[11px] text-red-600 dark:text-red-400 font-medium">
                                    {txt('deleteWarning')}
                                </div>
                            </DialogDescription>
                        </DialogHeader>
                        <DialogFooter className="mt-4">
                            <Button variant="outline" onClick={() => setShowDeleteModal(false)} className="dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                                {txt('cancel')}
                            </Button>
                            <Button variant="destructive" onClick={confirmDelete} className="bg-red-600 hover:bg-red-700 text-white">
                                {txt('delete')}
                            </Button>
                        </DialogFooter>
                    </DialogContent>
                </Dialog>
            </div>
        </SidebarProvider>
    );
}
