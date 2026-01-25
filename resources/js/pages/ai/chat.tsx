import AppLayout from '@/layouts/app-layout';
import { Head, usePage } from '@inertiajs/react';
import { 
    Sparkles, Send, Bot, User, Loader2, RefreshCw, 
    BookOpen, MessageSquare, Brain, Zap, Trash2, 
    Volume2, Copy, Check, Quote, Wand2, Star
} from 'lucide-react';
import { useState, useRef, useEffect } from 'react';
import { cn } from '@/lib/utils';
import { useLanguage } from '@/contexts/language-context';
import axios from 'axios';
import { toast } from 'sonner';

interface Message {
    id: string;
    role: 'user' | 'assistant';
    content: string;
    timestamp: Date;
    type?: 'text' | 'ref_summary' | 'draft';
}

const translations = {
    en: {
        title: "AI Researcher Assistant",
        subtitle: "Your intelligent partner for research synthesis and academic writing.",
        placeholder: "Ask me anything about your references or research...",
        welcome: "Hello! I am your Babybib AI Assistant. How can I help you with your research today?",
        suggestTopic: "Summarize my references on Artificial Intelligence",
        suggestRephrase: "Help me rephrase a paragraph for a journal",
        suggestSynthesis: "What are the common themes in my library?",
        thinking: "Analyzing context...",
        clearChat: "Clear Conversation",
        noMessages: "Start a conversation to see the magic happen.",
        copied: "Copied to clipboard",
        proFeature: "AI Synthesis is a Pro feature",
        agentName: "AI Agent",
    },
    th: {
        title: "ผู้ช่วยวิจัย AI",
        subtitle: "คู่คิดอัจฉริยะสำหรับการสังเคราะห์ข้อมูลและงานเขียนวิชาการ",
        placeholder: "ถามอะไรเกี่ยวกับรายการอ้างอิงหรืองานวิจัยได้เลย...",
        welcome: "สวัสดีครับ! ผมคือผู้ช่วย AI ของ Babybib วันนี้มีอะไรให้ผมช่วยดูแลงานวิจัยของคุณไหมครับ?",
        suggestTopic: "สรุปรายการอ้างอิงเกี่ยวกับปัญญาประดิษฐ์ให้หน่อย",
        suggestRephrase: "ช่วยเกลาสำนวนภาษาสำหรับส่งวารสารวิชาการ",
        suggestSynthesis: "ห้องสมุดของฉันมีประเด็นอะไรที่น่าสนใจบ้าง?",
        thinking: "กำลังวิเคราะห์ข้อมูล...",
        clearChat: "ล้างการสนทนา",
        noMessages: "เริ่มการสนทนาเพื่อสัมผัสประสบการณ์อัจฉริยะ",
        copied: "คัดลอกลงคลิปบอร์ดแล้ว",
        proFeature: "การสังเคราะห์ขั้นสูงเป็นฟีเจอร์ระดับ Pro",
        agentName: "ระบบอัจฉริยะ AI",
    }
};

export default function AiChat() {
    const { auth } = usePage<any>().props;
    const { t, language } = useLanguage();
    const txt = (key: string) => t(key, translations) as string;

    const [input, setInput] = useState('');
    const [messages, setMessages] = useState<Message[]>([
        {
            id: 'welcome',
            role: 'assistant',
            content: txt('welcome'),
            timestamp: new Date()
        }
    ]);
    const [loading, setLoading] = useState(false);
    const messagesEndRef = useRef<HTMLDivElement>(null);

    const scrollToBottom = () => {
        messagesEndRef.current?.scrollIntoView({ behavior: "smooth" });
    };

    useEffect(() => {
        scrollToBottom();
    }, [messages]);

    const handleSend = async (e?: React.FormEvent) => {
        e?.preventDefault();
        if (!input.trim() || loading) return;

        const userMsg: Message = {
            id: Date.now().toString(),
            role: 'user',
            content: input,
            timestamp: new Date()
        };

        setMessages(prev => [...prev, userMsg]);
        setInput('');
        setLoading(true);

        try {
            const response = await axios.post('/ai/chat', {
                message: input,
                history: messages.slice(-5).map(m => ({ role: m.role, content: m.content }))
            });

            const assistantMsg: Message = {
                id: (Date.now() + 1).toString(),
                role: 'assistant',
                content: response.data.result,
                timestamp: new Date()
            };

            setMessages(prev => [...prev, assistantMsg]);
        } catch (error) {
            toast.error("Failed to connect to AI server.");
        } finally {
            setLoading(false);
        }
    };

    const handleSuggestion = (suggestion: string) => {
        setInput(suggestion);
        // Explicitly calling send after a small delay to allow state update if needed, 
        // but better to just trigger it
    };

    const [copiedId, setCopiedId] = useState<string | null>(null);

    const handleCopy = (content: string, id: string) => {
        navigator.clipboard.writeText(content);
        setCopiedId(id);
        toast.success(txt('copied'));
        setTimeout(() => setCopiedId(null), 2000);
    };

    const clearChat = () => {
        setMessages([
            {
                id: 'welcome',
                role: 'assistant',
                content: txt('welcome'),
                timestamp: new Date()
            }
        ]);
    };

    return (
        <AppLayout breadcrumbs={[{ title: txt('title'), href: '/ai/chat' }]}>
            <Head title={`${txt('title')} - ScribeHub`} />

            <div className="flex h-[calc(100vh-64px)] flex-col bg-scribehub-paper/30 dark:bg-[#050505]/50 animate-in fade-in duration-500">
                {/* Chat Header */}
                <div className="flex items-center justify-between border-b bg-white/50 px-6 py-4 backdrop-blur dark:border-gray-800 dark:bg-gray-900/50">
                    <div className="flex items-center gap-4">
                        <div className="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-scribehub-blue to-purple-600 shadow-lg shadow-blue-500/20">
                            <Sparkles className="h-6 w-6 text-white" />
                        </div>
                        <div>
                            <h1 className="text-xl font-black text-scribehub-blue dark:text-white leading-tight">{txt('agentName')}</h1>
                            <div className="flex items-center gap-2 mt-0.5">
                                <span className="flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse" />
                                <span className="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Active & Ready</span>
                            </div>
                        </div>
                    </div>
                    
                    <button 
                        onClick={clearChat}
                        className="flex items-center gap-2 rounded-xl border border-gray-100 bg-white px-4 py-2 text-[10px] font-bold uppercase tracking-widest text-gray-500 shadow-sm transition-all hover:bg-gray-50 hover:text-red-500 dark:border-gray-800 dark:bg-gray-900"
                    >
                        <Trash2 className="h-3.5 w-3.5" />
                        {txt('clearChat')}
                    </button>
                </div>

                {/* Messages Area */}
                <div className="flex-1 overflow-y-auto custom-scrollbar p-6 space-y-8">
                    {messages.length === 1 && (
                        <div className="mx-auto max-w-2xl text-center space-y-8 py-12">
                            <div className="inline-flex h-20 w-20 items-center justify-center rounded-full bg-blue-50 dark:bg-blue-900/20 text-scribehub-blue">
                                <Brain className="h-10 w-10 animate-pulse" />
                            </div>
                            <div className="space-y-4">
                                <h2 className="text-2xl font-black text-scribehub-blue dark:text-white">{txt('title')}</h2>
                                <p className="text-sm text-gray-500 dark:text-gray-400">{txt('subtitle')}</p>
                            </div>

                            <div className="grid gap-3 sm:grid-cols-2">
                                {[
                                    { icon: BookOpen, text: txt('suggestTopic'), color: 'text-blue-500' },
                                    { icon: Wand2, text: txt('suggestRephrase'), color: 'text-purple-500' },
                                    { icon: Zap, text: txt('suggestSynthesis'), color: 'text-amber-500' },
                                    { icon: Star, text: "Show me my top references", color: 'text-emerald-500' }
                                ].map((s, i) => (
                                    <button 
                                        key={i}
                                        onClick={() => handleSuggestion(s.text)}
                                        className="flex flex-col items-center gap-3 rounded-2xl border border-white bg-white/50 p-4 text-center text-xs font-bold text-gray-700 shadow-sm transition-all hover:border-scribehub-blue hover:shadow-md dark:border-gray-800 dark:bg-gray-900/50 dark:text-gray-300"
                                    >
                                        <s.icon className={cn("h-5 w-5", s.color)} />
                                        {s.text}
                                    </button>
                                ))}
                            </div>
                        </div>
                    )}

                    <div className="mx-auto max-w-3xl space-y-8 pb-32">
                        {messages.map((msg) => (
                            <div key={msg.id} className={cn(
                                "flex gap-4 group animate-in slide-in-from-bottom-2 duration-300",
                                msg.role === 'assistant' ? "items-start" : "items-start flex-row-reverse"
                            )}>
                                <div className={cn(
                                    "flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl shadow-sm overflow-hidden",
                                    msg.role === 'assistant' ? "bg-gradient-to-br from-scribehub-blue to-purple-600 text-white" : "bg-gray-100 dark:bg-gray-800"
                                )}>
                                    {msg.role === 'assistant' ? (
                                        <Sparkles className="h-5 w-5" />
                                    ) : (
                                        <img src={auth.user.avatar} className="h-full w-full object-cover" />
                                    )}
                                </div>
                                <div className={cn(
                                    "flex max-w-[85%] flex-col gap-2",
                                    msg.role === 'user' ? "items-end" : "items-start"
                                )}>
                                    <div className={cn(
                                        "rounded-3xl px-6 py-4 text-sm leading-relaxed shadow-sm",
                                        msg.role === 'assistant' 
                                            ? "rounded-tl-none bg-white font-medium text-gray-800 dark:bg-gray-900 dark:text-gray-100" 
                                            : "rounded-tr-none bg-scribehub-blue font-bold text-white shadow-blue-900/10"
                                    )}>
                                        <div className="prose prose-sm dark:prose-invert max-w-none prose-p:leading-relaxed prose-pre:bg-gray-950 prose-pre:text-white prose-a:text-blue-500">
                                            {msg.content.split('\n').map((line, i) => (
                                                <p key={i}>{line}</p>
                                            ))}
                                        </div>
                                    </div>
                                    <div className="flex items-center gap-3 px-2">
                                        <span className="text-[9px] font-bold text-gray-400 uppercase tracking-widest">
                                            {new Intl.DateTimeFormat(language, { hour: '2-digit', minute: '2-digit' }).format(msg.timestamp)}
                                        </span>
                                        {msg.role === 'assistant' && (
                                            <div className="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-all">
                                                <button 
                                                    onClick={() => handleCopy(msg.content, msg.id)}
                                                    className="p-1 text-gray-400 hover:text-blue-500 transition-colors"
                                                >
                                                    {copiedId === msg.id ? <Check className="h-3 w-3 text-emerald-500" /> : <Copy className="h-3 w-3" />}
                                                </button>
                                                <button className="p-1 text-gray-400 hover:text-blue-500 transition-colors"><RefreshCw className="h-3 w-3" /></button>
                                            </div>
                                        )}
                                    </div>
                                </div>
                            </div>
                        ))}

                        {loading && (
                            <div className="flex gap-4 items-start animate-in fade-in duration-300">
                                <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-scribehub-blue to-purple-600 text-white shadow-sm">
                                    <Sparkles className="h-5 w-5 animate-pulse" />
                                </div>
                                <div className="rounded-3xl rounded-tl-none bg-white px-6 py-4 dark:bg-gray-900 shadow-sm">
                                    <div className="flex gap-1.5 items-center">
                                        <span className="h-1.5 w-1.5 rounded-full bg-blue-300 animate-bounce [animation-delay:-0.3s]" />
                                        <span className="h-1.5 w-1.5 rounded-full bg-blue-400 animate-bounce [animation-delay:-0.15s]" />
                                        <span className="h-1.5 w-1.5 rounded-full bg-blue-500 animate-bounce" />
                                        <span className="ml-2 text-[10px] font-black uppercase tracking-widest text-gray-400">{txt('thinking')}</span>
                                    </div>
                                </div>
                            </div>
                        )}
                        <div ref={messagesEndRef} />
                    </div>
                </div>

                {/* Input Area */}
                <div className="sticky bottom-0 bg-gradient-to-t from-scribehub-paper via-scribehub-paper/95 to-transparent px-6 pb-8 pt-4 dark:from-[#050505] dark:via-[#050505]/95">
                    <div className="mx-auto max-w-3xl relative">
                        <form onSubmit={handleSend} className="relative group">
                            <input
                                type="text"
                                value={input}
                                onChange={(e) => setInput(e.target.value)}
                                placeholder={txt('placeholder')}
                                disabled={loading}
                                className="h-16 w-full rounded-2xl border border-white bg-white/80 pr-16 pl-6 text-sm font-bold shadow-xl backdrop-blur transition-all placeholder:text-gray-400 focus:border-scribehub-blue focus:outline-none focus:ring-4 focus:ring-scribehub-blue/5 dark:border-gray-800 dark:bg-gray-900/80 dark:text-white"
                            />
                            <button 
                                type="submit"
                                disabled={!input.trim() || loading}
                                className="absolute right-3 top-3 flex h-10 w-10 items-center justify-center rounded-xl bg-scribehub-blue text-white shadow-lg transition-all hover:scale-105 active:scale-95 disabled:opacity-30 disabled:hover:scale-100"
                            >
                                <Send className="h-5 w-5" />
                            </button>
                        </form>
                        <p className="mt-2 text-center text-[9px] font-bold uppercase tracking-widest text-gray-400">
                            Babybib AI can make mistakes. Always verify academic citations.
                        </p>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
