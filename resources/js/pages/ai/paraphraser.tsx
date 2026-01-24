import AppLayout from '@/layouts/app-layout';
import { Head } from '@inertiajs/react';
import { useState } from 'react';
import { Button } from '@/components/ui/button';
import { Textarea } from '@/components/ui/textarea';
import { Card, CardContent } from '@/components/ui/card';
import { 
    Sparkles, Copy, RefreshCcw, Check, 
    ArrowRight, MessageSquare, BookOpen, 
    Zap, ListFilter, PenTool, Eraser,
    BookMarked, Languages
} from 'lucide-react';
import { cn } from '@/lib/utils';
import axios from 'axios';
import { toast } from 'sonner';
import { useLanguage } from '@/contexts/language-context';

const translations = {
    en: {
        title: "AI Paraphraser",
        subtitle: "Refine your academic writing with professional AI editing.",
        academic: "Academic",
        concise: "Concise",
        detailed: "Detailed",
        natural: "Natural",
        draftInput: "Draft Input",
        clear: "CLEAR",
        placeholder: "Paste your research text here to begin refining...",
        refineBtn: "Refine Text",
        processing: "Processing",
        polishedResult: "Polished Result",
        copyResult: "COPY RESULT",
        copied: "COPIED",
        awaiting: "Awaiting your draft\nto begin translation",
        aiEngine: "AI Engine Responsive",
        mode: "Mode",
        insightTitle: "Editor's Insight",
        insightDesc: "Your content is processed using {tone} logic. This ensures terminology remains consistent while improving syntactic complexity for peer-review standards.",
        toastNoInput: "Please enter some text to paraphrase",
        toastSuccess: "Successfully paraphrased!",
        toastError: "Failed to paraphrase. Please try again.",
        characters: "Characters"
    },
    th: {
        title: "ระบบเรียบเรียงสำนวน AI",
        subtitle: "ขัดเกลาสำนวนงานวิจัยของคุณด้วยการปรับแต่งระดับมืออาชีพ",
        academic: "วิชาการ",
        concise: "กระชับ",
        detailed: "ละเอียด",
        natural: "ธรรมชาติ",
        draftInput: "ข้อความต้นฉบับ",
        clear: "ล้างข้อมูล",
        placeholder: "วางข้อความงานวิจัยของคุณที่นี่เพื่อเริ่มการปรับแต่ง...",
        refineBtn: "ปรับแต่งข้อความ",
        processing: "กำลังประมวลผล",
        polishedResult: "ผลลัพธ์ที่ปรับปรุงแล้ว",
        copyResult: "คัดลอกผลลัพธ์",
        copied: "คัดลอกแล้ว",
        awaiting: "รอข้อความของคุณ\nเพื่อเริ่มการปรับปรุง",
        aiEngine: "ระบบ AI พร้อมทำงาน",
        mode: "โหมด",
        insightTitle: "มุมมองจากบรรณาธิการ",
        insightDesc: "เนื้อหาของคุณถูกประมวลผลด้วยตรรกะแบบ {tone} เพื่อให้มั่นใจว่าคำศัพท์ทางวิชาการยังคงความหมายเดิมในขณะที่เพิ่มความสละสลวยของประโยคตามมาตรฐานสากล",
        toastNoInput: "กรุณาใส่ข้อความที่ต้องการเรียบเรียง",
        toastSuccess: "เรียบเรียงสำนวนสำเร็จ!",
        toastError: "เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง",
        characters: "ตัวอักษร"
    }
};

const TONES = [
    { id: 'academic', label: 'academic', icon: BookOpen },
    { id: 'concise', label: 'concise', icon: Zap },
    { id: 'detailed', label: 'detailed', icon: ListFilter },
    { id: 'natural', label: 'natural', icon: MessageSquare },
];

export default function Paraphraser() {
    const { language, t } = useLanguage();
    const [input, setInput] = useState('');
    const [output, setOutput] = useState('');
    const [isLoading, setIsLoading] = useState(false);
    const [selectedTone, setSelectedTone] = useState('academic');
    const [isCopied, setIsCopied] = useState(false);

    const txt = (key: string) => t(key, translations) as string;

    const handleParaphrase = async () => {
        if (!input.trim()) {
            toast.error(txt('toastNoInput'));
            return;
        }

        setIsLoading(true);
        try {
            const response = await axios.post('/paraphraser/action', {
                text: input,
                tone: selectedTone
            });
            setOutput(response.data.result);
            toast.success(txt('toastSuccess'));
        } catch (error) {
            toast.error(txt('toastError'));
        } finally {
            setIsLoading(false);
        }
    };

    const copyToClipboard = () => {
        if (!output) return;
        navigator.clipboard.writeText(output);
        setIsCopied(true);
        toast.success(txt('copied'));
        setTimeout(() => setIsCopied(false), 2000);
    };

    return (
        <AppLayout>
            <Head title={txt('title')} />
            
            <div className="flex h-full flex-col bg-[#fdfdfd] dark:bg-[#050505]">
                <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between p-6 lg:p-8 gap-4 border-b dark:border-gray-800 bg-white/50 dark:bg-black/50 backdrop-blur-md z-10">
                    <div>
                        <div className="flex items-center gap-2 mb-1">
                            <div className="bg-scribehub-blue p-1.5 rounded-lg shadow-lg shadow-blue-500/20">
                                <PenTool className="h-5 w-5 text-white" />
                            </div>
                            <h1 className="text-2xl font-black tracking-tight dark:text-gray-100">{txt('title')}</h1>
                        </div>
                        <p className="text-sm text-gray-500 dark:text-gray-400 font-medium">{txt('subtitle')}</p>
                    </div>

                    <div className="flex items-center gap-3 w-full sm:w-auto">
                         <div className="flex bg-gray-100 dark:bg-gray-800 p-1 rounded-full border dark:border-gray-700">
                            {TONES.map((tone) => (
                                <button
                                    key={tone.id}
                                    onClick={() => setSelectedTone(tone.id)}
                                    className={cn(
                                        "px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest transition-all",
                                        selectedTone === tone.id 
                                            ? "bg-white text-blue-600 shadow-sm dark:bg-gray-700 dark:text-white" 
                                            : "text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                                    )}
                                >
                                    {txt(tone.label)}
                                </button>
                            ))}
                        </div>
                    </div>
                </div>

                <div className="flex-1 overflow-auto p-6 lg:p-8 custom-scrollbar">
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 h-full min-h-[500px]">
                        <div className="flex flex-col gap-4">
                            <div className="flex items-center justify-between px-1">
                                <div className="flex items-center gap-2">
                                    <div className="h-2 w-2 rounded-full bg-blue-500 animate-pulse" />
                                    <h3 className="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">{txt('draftInput')}</h3>
                                </div>
                                <button 
                                    onClick={() => setInput('')}
                                    className="text-[10px] font-bold text-gray-400 hover:text-red-500 transition-colors flex items-center gap-1"
                                >
                                    <Eraser className="h-3 w-3" /> {txt('clear')}
                                </button>
                            </div>
                            
                            <Card className="flex-1 border-none shadow-2xl shadow-gray-200/50 dark:shadow-none dark:bg-[#0a0a0a] ring-1 ring-gray-100 dark:ring-gray-800 overflow-hidden relative">
                                <Textarea 
                                    value={input}
                                    onChange={(e) => setInput(e.target.value)}
                                    placeholder={txt('placeholder')}
                                    className="h-full min-h-[350px] border-none focus-visible:ring-0 text-[15px] leading-relaxed p-8 bg-transparent resize-none font-serif dark:text-gray-200 placeholder:dark:text-gray-600"
                                />
                                <div className="absolute bottom-4 right-4 text-[9px] font-black text-gray-300 dark:text-gray-700 uppercase underline decoration-blue-500/30 underline-offset-4">
                                    {input.length} {txt('characters')}
                                </div>
                            </Card>

                            <Button 
                                onClick={handleParaphrase}
                                disabled={isLoading || !input.trim()}
                                className="h-14 rounded-2xl bg-gray-900 hover:bg-black text-white font-black uppercase tracking-widest text-xs shadow-xl transition-all active:scale-[0.98] dark:bg-blue-600 dark:hover:bg-blue-700 mt-2"
                            >
                                {isLoading ? <RefreshCcw className="mr-2 h-4 w-4 animate-spin" /> : <Sparkles className="mr-2 h-4 w-4" />}
                                {isLoading ? txt('processing') : txt('refineBtn')}
                            </Button>
                        </div>

                        <div className="flex flex-col gap-4 h-full">
                            <div className="flex items-center justify-between px-1">
                                <div className="flex items-center gap-2">
                                    <div className="h-2 w-2 rounded-full bg-emerald-500" />
                                    <h3 className="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">{txt('polishedResult')}</h3>
                                </div>
                                {output && (
                                    <button 
                                        onClick={copyToClipboard}
                                        className="text-[10px] font-bold text-emerald-600 hover:text-emerald-700 transition-colors flex items-center gap-1.5 bg-emerald-50 dark:bg-emerald-900/20 px-3 py-1 rounded-full border border-emerald-100 dark:border-emerald-800"
                                    >
                                        {isCopied ? <Check className="h-3 w-3" /> : <Copy className="h-3 w-3" />}
                                        {isCopied ? txt('copied').toUpperCase() : txt('copyResult').toUpperCase()}
                                    </button>
                                )}
                            </div>

                            <Card className={cn(
                                "flex-1 border-none shadow-2xl dark:bg-[#0a0a0a] ring-1 ring-gray-100 dark:ring-gray-800 p-8 overflow-y-auto relative transition-all duration-500",
                                isLoading ? "opacity-40 blur-[1px]" : "opacity-100",
                                output ? "bg-white" : "bg-gray-50/30 flex items-center justify-center border-dashed border-2 border-gray-100 dark:border-gray-800"
                            )}>
                                {output ? (
                                    <div className="animate-in fade-in slide-in-from-bottom-2 duration-700">
                                        <p className="text-[16px] leading-[1.8] text-gray-800 dark:text-gray-200 font-serif whitespace-pre-wrap">
                                            {output}
                                        </p>
                                    </div>
                                ) : (
                                    <div className="text-center text-gray-300 dark:text-gray-800 max-w-xs scale-90">
                                        <div className="h-16 w-16 rounded-3xl bg-gray-50 dark:bg-gray-800 flex items-center justify-center mx-auto mb-6 ring-1 ring-gray-100 dark:ring-gray-700">
                                            <BookMarked className="h-8 w-8 text-gray-200 dark:text-gray-700" />
                                        </div>
                                        <p className="text-xs font-black uppercase tracking-widest leading-loose whitespace-pre-wrap">{txt('awaiting')}</p>
                                    </div>
                                )}

                                {isLoading && (
                                    <div className="absolute inset-0 flex items-center justify-center z-10 backdrop-blur-[1px]">
                                        <div className="flex flex-col items-center gap-4 bg-white/50 dark:bg-black/50 p-8 rounded-3xl border border-white/50 dark:border-gray-800 shadow-2xl">
                                            <div className="relative">
                                                <div className="h-14 w-14 rounded-full border-4 border-blue-50 border-t-blue-600 animate-spin" />
                                                <Languages className="absolute inset-0 m-auto h-6 w-6 text-blue-600" />
                                            </div>
                                            <div className="flex flex-col items-center">
                                                <span className="text-[10px] font-black uppercase tracking-[0.2em] text-blue-600">{txt('academic')} AI</span>
                                                <span className="text-xs font-bold text-gray-500 dark:text-gray-400">{txt('processing')}...</span>
                                            </div>
                                        </div>
                                    </div>
                                )}
                            </Card>

                            <div className="p-5 rounded-3xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 shadow-xl flex items-start gap-4 group/info">
                                <div className="h-10 w-10 shrink-0 rounded-2xl bg-blue-50 dark:bg-blue-900/40 flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover/info:scale-110 transition-transform">
                                    <Zap className="h-5 w-5" />
                                </div>
                                <div className="flex-1">
                                    <h4 className="text-[11px] font-black uppercase tracking-widest text-gray-900 dark:text-gray-100 mb-1">{txt('insightTitle')}</h4>
                                    <p className="text-[11px] text-gray-500 dark:text-gray-400 font-medium leading-relaxed">
                                        {txt('insightDesc').replace('{tone}', txt(selectedTone))}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div className="h-10 bg-white border-t dark:bg-[#0a0a0a] dark:border-gray-800 flex items-center justify-between px-8 text-[9px] font-black text-gray-400 uppercase tracking-widest transition-all">
                    <div className="flex items-center gap-4">
                        <span className="flex items-center gap-2">
                             <div className="h-1.5 w-1.5 rounded-full bg-emerald-500" /> {txt('aiEngine')}
                        </span>
                        <span>{txt('mode')}: {txt(selectedTone)}</span>
                    </div>
                    <div className="flex items-center gap-1">
                        ScribeHub Writing Assistant <div className="h-1 w-1 rounded-full bg-gray-300 dark:bg-gray-800" /> v2.4
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
