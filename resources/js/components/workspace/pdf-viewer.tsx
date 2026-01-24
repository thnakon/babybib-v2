import { useState, useRef, useEffect } from 'react';
import { Document, Page, pdfjs } from 'react-pdf';
import { 
    Upload, ZoomIn, ZoomOut, ChevronLeft, ChevronRight, 
    File as FileIcon, Sparkles, PlusCircle, Maximize2, 
    List, History, Search, ArrowRight 
} from 'lucide-react';
import { cn } from '@/lib/utils';
import 'react-pdf/dist/Page/AnnotationLayer.css';
import 'react-pdf/dist/Page/TextLayer.css';
import { useLanguage } from '@/contexts/language-context';

// Set up worker
pdfjs.GlobalWorkerOptions.workerSrc = `//unpkg.com/pdfjs-dist@${pdfjs.version}/build/pdf.worker.min.mjs`;

const translations = {
    en: {
        recentPapers: "Recent Papers",
        ready: "Ready for Reading",
        singlePage: "Single Page",
        scrollMode: "Continuous Scroll",
        zoomIn: "Zoom In",
        zoomOut: "Zoom Out",
        previous: "Previous",
        next: "Next",
        magicCite: "Magic Cite",
        syncing: "Syncing Content...",
        readerTitle: "Advanced Reader",
        readerDesc: "Jump back into your research. Your papers are stored locally for instant access across sessions.",
        importPdf: "Import New PDF",
        browseHistory: "Browse History",
        emptyLibrary: "Your digital library is empty.\nImport a PDF to start your journal.",
        statusLibrary: "Library Online",
        pageFlip: "Page Flip",
        smoothScroll: "Smooth Scroll",
        engine: "Advanced Reader Engine v4.2",
        journalArticle: "Journal Article",
        syncedLocally: "Synced Locally",
        characters: "Characters",
        view: "View"
    },
    th: {
        recentPapers: "เอกสารที่อ่านล่าสุด",
        ready: "พร้อมสำหรับการอ่าน",
        singlePage: "หน้าเดียว",
        scrollMode: "เลื่อนต่อเนื่อง",
        zoomIn: "ขยาย",
        zoomOut: "ลดขนาด",
        previous: "หน้าก่อนหน้า",
        next: "หน้าถัดไป",
        magicCite: "อ้างอิงอัจฉริยะ",
        syncing: "กำลังซิงค์เนื้อหา...",
        readerTitle: "เครื่องมืออ่านขั้นสูง",
        readerDesc: "กลับเข้าสู่การวิจัยของคุณ เอกสารจะถูกเก็บไว้ในเครื่องเพื่อการเข้าถึงที่รวดเร็ว",
        importPdf: "นำเข้าไฟล์ PDF ใหม่",
        browseHistory: "ดูประวัติการอ่าน",
        emptyLibrary: "คลังข้อมูลจำลองของคุณว่างเปล่า\nนำเข้าไฟล์ PDF เพื่อเริ่มต้นเขียนบันทึกของคุณ",
        statusLibrary: "คลังข้อมูลพร้อมใช้งาน",
        pageFlip: "เปลี่ยนหน้า",
        smoothScroll: "เลื่อนแบบนุ่มนวล",
        engine: "ระบบอ่านอัจฉริยะ v4.2",
        journalArticle: "บทความวิชาการ",
        syncedLocally: "บันทึกในเครื่องเรียบร้อย",
        characters: "ตัวอักษร",
        view: "มุมมอง"
    }
};

export default function PdfViewer() {
    const { language, t } = useLanguage();
    const [file, setFile] = useState<File | Blob | null>(null);
    const [fileName, setFileName] = useState<string>('');
    const [numPages, setNumPages] = useState<number>(0);
    const [pageNumber, setPageNumber] = useState<number>(1);
    const [scale, setScale] = useState<number>(1.1);
    const [viewMode, setViewMode] = useState<'single' | 'scroll'>('single');
    const [jumpPage, setJumpPage] = useState<string>('1');
    const [selection, setSelection] = useState<{ text: string, x: number, y: number } | null>(null);
    const [history, setHistory] = useState<{name: string, id: string}[]>([]);
    const [showHistory, setShowHistory] = useState(false);
    
    const containerRef = useRef<HTMLDivElement>(null);
    const scrollContainerRef = useRef<HTMLDivElement>(null);
    const pageRefs = useRef<{[key: number]: HTMLDivElement | null}>({});

    const txt = (key: string) => t(key, translations) as string;

    // Load history metadata from localStorage on mount
    useEffect(() => {
        const savedHistory = localStorage.getItem('pdf_history');
        if (savedHistory) {
            setHistory(JSON.parse(savedHistory));
        }
    }, []);

    // Intersection Observer to detect current visible page in scroll mode
    useEffect(() => {
        if (viewMode !== 'scroll' || !file) return;

        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        const page = parseInt(entry.target.getAttribute('data-page-index') || '1');
                        setPageNumber(page);
                        setJumpPage(page.toString());
                    }
                });
            },
            { threshold: 0.5, root: scrollContainerRef.current }
        );

        const currentRefs = pageRefs.current;
        Object.values(currentRefs).forEach((ref) => {
            if (ref) observer.observe(ref);
        });

        return () => {
            Object.values(currentRefs).forEach((ref) => {
                if (ref) observer.unobserve(ref);
            });
        };
    }, [viewMode, file, numPages]);

    // Function to save PDF to IndexedDB
    const saveToHistory = async (blob: Blob, name: string) => {
        const id = Date.now().toString();
        const request = indexedDB.open('PdfStorage', 1);
        
        request.onupgradeneeded = (e: any) => {
            const db = e.target.result;
            if (!db.objectStoreNames.contains('pdfs')) {
                db.createObjectStore('pdfs', { keyPath: 'id' });
            }
        };

        request.onsuccess = (e: any) => {
            const db = e.target.result;
            const transaction = db.transaction('pdfs', 'readwrite');
            const store = transaction.objectStore('pdfs');
            store.put({ id, name, blob, date: new Date().toISOString() });
            
            const newHistory = [{ id, name }, ...history.filter(h => h.name !== name)].slice(0, 10);
            setHistory(newHistory);
            localStorage.setItem('pdf_history', JSON.stringify(newHistory));
        };
    };

    // Function to load from history
    const loadFromHistory = (id: string) => {
        const request = indexedDB.open('PdfStorage', 1);
        request.onsuccess = (e: any) => {
            const db = e.target.result;
            const transaction = db.transaction('pdfs', 'readonly');
            const store = transaction.objectStore('pdfs');
            const getRequest = store.get(id);
            getRequest.onsuccess = () => {
                if (getRequest.result) {
                    setFile(getRequest.result.blob);
                    setFileName(getRequest.result.name);
                    setShowHistory(false);
                }
            };
        };
    };

    function onDocumentLoadSuccess({ numPages }: { numPages: number }) {
        setNumPages(numPages);
        setPageNumber(1);
    }

    const handleFileUpload = (event: React.ChangeEvent<HTMLInputElement>) => {
        const files = event.target.files;
        if (files && files[0]) {
            const selectedFile = files[0];
            setFile(selectedFile);
            setFileName(selectedFile.name);
            saveToHistory(selectedFile, selectedFile.name);
        }
    };

    const handleJumpPage = (e: React.FormEvent) => {
        e.preventDefault();
        const val = parseInt(jumpPage);
        if (val >= 1 && val <= numPages) {
            setPageNumber(val);
            if (viewMode === 'scroll') {
                pageRefs.current[val]?.scrollIntoView({ behavior: 'smooth' });
            }
        }
    };

    const handleMouseUp = () => {
        const selectedText = window.getSelection()?.toString();
        if (selectedText && selectedText.trim().length > 0) {
            const range = window.getSelection()?.getRangeAt(0);
            const rect = range?.getClientRects()[0];
            if (rect && containerRef.current) {
                const containerRect = containerRef.current.getBoundingClientRect();
                setSelection({
                    text: selectedText,
                    x: rect.left - containerRect.left + rect.width / 2,
                    y: rect.top - containerRect.top - 40
                });
            }
        } else {
            setSelection(null);
        }
    };

    const citeSelection = () => {
        if (!selection) return;
        const event = new CustomEvent('PDF_SELECTION_INSERT', {
            detail: {
                text: selection.text,
                citation: fileName.replace('.pdf', '') || 'Source'
            }
        });
        window.dispatchEvent(event);
        setSelection(null);
        window.getSelection()?.removeAllRanges();
    };

    return (
        <div className="flex h-full flex-col bg-gray-100 dark:bg-gray-900 border-r dark:border-gray-800" ref={containerRef}>
            <div className="flex flex-wrap h-auto lg:h-12 items-center justify-between border-b bg-white px-2 lg:px-4 py-2 shadow-sm dark:border-gray-800 dark:bg-[#0a0a0a] z-20 gap-2">
                <div className="flex items-center gap-2">
                    <button 
                        onClick={() => setShowHistory(!showHistory)}
                        className={cn(
                            "p-1.5 rounded-lg transition-colors border dark:border-gray-700",
                            showHistory ? "bg-blue-50 text-blue-600 dark:bg-blue-900/20" : "hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-400"
                        )}
                        title={txt('recentPapers')}
                    >
                        <History className="h-4 w-4" />
                    </button>
                    
                    <div className="flex items-center gap-2 text-sm font-bold text-scribehub-blue dark:text-blue-400">
                        <FileIcon className="h-4 w-4" />
                        <span className="max-w-[100px] lg:max-w-[150px] truncate">{file ? fileName : txt('ready')}</span>
                    </div>
                </div>
                
                {file && (
                    <div className="flex items-center gap-1 sm:gap-2">
                        <div className="flex items-center rounded-full bg-gray-100 p-0.5 dark:bg-gray-800 border dark:border-gray-700 shadow-sm">
                            <button 
                                onClick={() => {
                                    const next = Math.max(pageNumber - 1, 1);
                                    setPageNumber(next);
                                    if (viewMode === 'scroll') {
                                        pageRefs.current[next]?.scrollIntoView({ behavior: 'smooth' });
                                    }
                                }}
                                disabled={pageNumber <= 1}
                                className="rounded-full p-1.5 hover:bg-white disabled:opacity-30 dark:hover:bg-gray-700 transition-colors text-gray-500 dark:text-gray-400"
                            >
                                <ChevronLeft className="h-3.5 w-3.5" />
                            </button>
                            
                            <form onSubmit={handleJumpPage} className="flex items-center px-1">
                                <input 
                                    type="text" 
                                    value={jumpPage}
                                    onChange={(e) => setJumpPage(e.target.value)}
                                    className="w-8 bg-transparent text-center text-[11px] font-black focus:outline-none dark:text-gray-100"
                                />
                                <span className="text-[10px] text-gray-400 font-bold mx-0.5">/</span>
                                <span className="text-[10px] text-gray-400 font-bold mr-1">{numPages}</span>
                            </form>

                            <button 
                                onClick={() => {
                                    const next = Math.min(pageNumber + 1, numPages);
                                    setPageNumber(next);
                                    if (viewMode === 'scroll') {
                                        pageRefs.current[next]?.scrollIntoView({ behavior: 'smooth' });
                                    }
                                }}
                                disabled={pageNumber >= numPages}
                                className="rounded-full p-1.5 hover:bg-white disabled:opacity-30 dark:hover:bg-gray-700 transition-colors text-gray-500 dark:text-gray-400"
                            >
                                <ChevronRight className="h-3.5 w-3.5" />
                            </button>
                        </div>

                        <div className="flex items-center bg-gray-100 rounded-full dark:bg-gray-800 p-0.5 border dark:border-gray-700">
                            <button 
                                onClick={() => setViewMode('single')} 
                                className={cn("rounded-full p-1.5 transition-all text-gray-400", viewMode === 'single' ? "bg-white text-blue-600 shadow-sm dark:bg-gray-700 dark:text-white" : "hover:bg-white/50 dark:hover:bg-gray-700")}
                                title={txt('singlePage')}
                            >
                                <Maximize2 className="h-3.5 w-3.5" />
                            </button>
                            <button 
                                onClick={() => setViewMode('scroll')} 
                                className={cn("rounded-full p-1.5 transition-all text-gray-400", viewMode === 'scroll' ? "bg-white text-blue-600 shadow-sm dark:bg-gray-700 dark:text-white" : "hover:bg-white/50 dark:hover:bg-gray-700")}
                                title={txt('scrollMode')}
                            >
                                <List className="h-3.5 w-3.5" />
                            </button>
                        </div>

                        <div className="flex items-center bg-gray-100 rounded-full dark:bg-gray-800 p-0.5 border dark:border-gray-700">
                            <button onClick={() => setScale(s => Math.max(s - 0.1, 0.5))} className="rounded-full p-1.5 hover:bg-white dark:hover:bg-gray-700 transition-colors text-gray-500 dark:text-gray-400"><ZoomOut className="h-3.5 w-3.5" /></button>
                            <span className="text-[10px] font-bold w-10 text-center dark:text-gray-200">{Math.round(scale * 100)}%</span>
                            <button onClick={() => setScale(s => Math.min(s + 0.1, 2.0))} className="rounded-full p-1.5 hover:bg-white dark:hover:bg-gray-700 transition-colors text-gray-500 dark:text-gray-400"><ZoomIn className="h-3.5 w-3.5" /></button>
                        </div>
                    </div>
                )}
            </div>

            <div className="flex-1 relative overflow-hidden bg-gray-200/50 dark:bg-black/20">
                {showHistory && (
                    <div className="absolute inset-0 z-30 bg-white/95 dark:bg-[#0a0a0a]/95 backdrop-blur-sm p-6 overflow-y-auto animate-in fade-in duration-200">
                        <div className="max-w-md mx-auto">
                            <div className="flex items-center justify-between mb-8">
                                <h4 className="text-sm font-black uppercase tracking-widest text-gray-400 flex items-center gap-2">
                                    <History className="h-4 w-4" /> {txt('recentPapers')}
                                </h4>
                                <button onClick={() => setShowHistory(false)} className="text-gray-400 hover:text-gray-600 transition-colors">
                                    <PlusCircle className="h-5 w-5 rotate-45" />
                                </button>
                            </div>

                            {history.length > 0 ? (
                                <div className="grid grid-cols-1 gap-3">
                                    {history.map((h) => (
                                        <button 
                                            key={h.id}
                                            onClick={() => loadFromHistory(h.id)}
                                            className="w-full text-left flex items-center justify-between p-4 rounded-2xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-800 hover:border-blue-500 hover:shadow-lg transition-all group"
                                        >
                                            <div className="flex items-center gap-4">
                                                <div className="h-12 w-12 rounded-xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all">
                                                    <FileIcon className="h-6 w-6" />
                                                </div>
                                                <div className="overflow-hidden">
                                                    <p className="font-bold text-sm truncate dark:text-gray-200 mb-0.5">{h.name}</p>
                                                    <p className="text-[10px] font-medium text-gray-400 uppercase tracking-tighter">{txt('journalArticle')} • {txt('syncedLocally')}</p>
                                                </div>
                                            </div>
                                            <ArrowRight className="h-4 w-4 text-gray-300 group-hover:translate-x-1 group-hover:text-blue-500 transition-all" />
                                        </button>
                                    ))}
                                </div>
                            ) : (
                                <div className="text-center py-20 bg-gray-50 dark:bg-gray-800/50 rounded-3xl border-2 border-dashed border-gray-100 dark:border-gray-700">
                                    <Search className="h-10 w-10 text-gray-200 mx-auto mb-4" />
                                    <p className="text-sm text-gray-400 font-bold uppercase tracking-widest leading-relaxed px-10 whitespace-pre-wrap">{txt('emptyLibrary')}</p>
                                </div>
                            )}
                        </div>
                    </div>
                )}

                <div 
                    className="h-full overflow-auto p-4 lg:p-8 flex flex-col items-center custom-scrollbar relative scroll-smooth"
                    onMouseUp={handleMouseUp}
                    ref={scrollContainerRef}
                >
                    {selection && (
                        <button
                            onClick={citeSelection}
                            className="absolute z-40 flex items-center gap-2 rounded-full bg-scribehub-blue px-4 py-2 text-xs font-bold text-white shadow-xl animate-in zoom-in slide-in-from-bottom-2 duration-200 hover:scale-105 active:scale-95"
                            style={{ top: selection.y, left: selection.x, transform: 'translateX(-50%)' }}
                        >
                            <Sparkles className="h-3.5 w-3.5" />
                            {txt('magicCite')}
                        </button>
                    )}

                    {file ? (
                        <Document
                            file={file}
                            onLoadSuccess={onDocumentLoadSuccess}
                            className="shadow-2xl flex flex-col items-center gap-6"
                            loading={
                                <div className="flex flex-col items-center justify-center p-20 text-gray-400">
                                    <div className="h-10 w-10 animate-spin rounded-full border-2 border-dashed border-scribehub-blue mb-4"></div>
                                    <span className="text-xs font-bold uppercase tracking-widest animate-pulse">{txt('syncing')}</span>
                                </div>
                            }
                        >
                            {viewMode === 'single' ? (
                                <div className="bg-white">
                                    <Page 
                                        pageNumber={pageNumber} 
                                        scale={scale} 
                                        renderAnnotationLayer={true}
                                        renderTextLayer={true}
                                    />
                                </div>
                            ) : (
                                <div className="flex flex-col gap-8 pb-32">
                                    {Array.from(new Array(numPages), (el, index) => (
                                        <div 
                                            key={`page_${index + 1}`} 
                                            data-page-index={index + 1}
                                            ref={(el) => { pageRefs.current[index + 1] = el }}
                                            className="bg-white shadow-xl ring-1 ring-gray-200 dark:ring-gray-800"
                                        >
                                            <Page 
                                                pageNumber={index + 1} 
                                                scale={scale}
                                                renderAnnotationLayer={true}
                                                renderTextLayer={true}
                                                loading={<div style={{ height: 800 * scale, width: 600 * scale }} className="bg-white animate-pulse" />}
                                            />
                                        </div>
                                    ))}
                                </div>
                            )}
                        </Document>
                    ) : (
                        <div className="flex flex-col items-center justify-center min-h-full text-center max-w-sm mx-auto">
                            <div className="mb-8 relative scale-90 sm:scale-100">
                                <div className="absolute -inset-4 bg-scribehub-blue/10 blur-2xl rounded-full"></div>
                                <div className="relative rounded-3xl bg-white p-8 shadow-xl dark:bg-gray-800 ring-1 ring-gray-100 dark:ring-gray-700 border border-gray-50 dark:border-gray-700">
                                    <Upload className="h-12 w-12 text-scribehub-blue mx-auto mb-4" />
                                    <h3 className="text-xl font-black text-scribehub-blue dark:text-white leading-tight">{txt('readerTitle')}</h3>
                                </div>
                            </div>
                            <p className="text-sm font-medium text-gray-500 mb-8 dark:text-gray-400 leading-relaxed font-serif">{txt('readerDesc')}</p>
                            
                            <div className="flex flex-col w-full gap-3">
                                <label className="group relative cursor-pointer overflow-hidden rounded-2xl bg-scribehub-blue px-10 py-4 text-sm font-bold text-white shadow-lg transition-all hover:shadow-scribehub-blue/25 hover:-translate-y-1 active:scale-95">
                                    <span className="relative z-10 flex items-center justify-center gap-2">
                                        <PlusCircle className="h-5 w-5" />
                                        {txt('importPdf')}
                                    </span>
                                    <input type="file" accept=".pdf" onChange={handleFileUpload} className="hidden" />
                                </label>

                                {history.length > 0 && (
                                    <button 
                                        onClick={() => setShowHistory(true)}
                                        className="group relative flex items-center justify-center gap-2 rounded-2xl border-2 border-gray-100 bg-white/50 px-10 py-4 text-sm font-bold text-gray-500 hover:bg-white hover:text-blue-600 hover:border-blue-500 transition-all dark:border-gray-800 dark:bg-gray-800 dark:text-gray-400 dark:hover:text-white"
                                    >
                                        <History className="h-5 w-5" />
                                        {txt('browseHistory')} ({history.length})
                                    </button>
                                )}
                            </div>
                        </div>
                    )}
                </div>
            </div>
            
            <div className="h-6 shrink-0 bg-white border-t dark:bg-[#0a0a0a] dark:border-gray-800 flex items-center justify-between px-4 text-[9px] font-bold text-gray-400 uppercase tracking-tighter transition-all">
                <div className="flex items-center gap-3">
                    <span className="flex items-center gap-1.5 text-emerald-600 dark:text-emerald-400">
                        <span className="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        {txt('statusLibrary')}
                    </span>
                    <span>{txt('view')}: {viewMode === 'single' ? txt('pageFlip') : txt('smoothScroll')}</span>
                </div>
                <div>{txt('engine')}</div>
            </div>
        </div>
    );
}
