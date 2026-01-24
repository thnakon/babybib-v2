import { useEditor, EditorContent } from '@tiptap/react';
import StarterKit from '@tiptap/starter-kit';
import Mention from '@tiptap/extension-mention';
import Placeholder from '@tiptap/extension-placeholder';
import CharacterCount from '@tiptap/extension-character-count';
import { 
    Bold, Italic, List, ListOrdered, Heading1, Heading2, 
    Quote, Undo, Redo, Save, CheckCircle, Loader2, Sparkles,
    Type, Languages, Wand2
} from 'lucide-react';
import { cn } from '@/lib/utils';
import { useEffect, useState, useRef, forwardRef, useImperativeHandle } from 'react';
import axios from 'axios';
import { DateTime } from 'luxon';
import { toast } from 'sonner';
import { useLanguage } from '@/contexts/language-context';

const translations = {
    en: {
        bold: "Bold",
        italic: "Italic",
        h1: "H1",
        h2: "H2",
        bullets: "Bullets",
        quote: "Quote",
        aiAssistant: "AI Assistant",
        saving: "saving...",
        justNow: "just now",
        placeholder: "Start your research. Use @ to cite or AI to refine...",
        citationAdded: "Citation added",
        savedSuccess: "Manuscript saved successfully",
        selectText: "Please select a sentence or paragraph to use AI helper.",
        aiThinking: "AI is thinking...",
        aiSuccess: "AI suggestions added below selection.",
        aiFailed: "AI Assistant failed. Please check your API key.",
        aiDraft: "AI Draft",
        aiCrafting: "AI is crafting your text...",
        words: "Words",
        autoFormatting: "Auto-formatting enabled",
        pageBreak: "Add Page Break"
    },
    th: {
        bold: "ตัวหนา",
        italic: "ตัวเอียง",
        h1: "หัวข้อ 1",
        h2: "หัวข้อ 2",
        bullets: "รายการ",
        quote: "อ้างอิง",
        aiAssistant: "ผู้ช่วย AI",
        saving: "กำลังบันทึก...",
        justNow: "เมื่อสักครู่",
        placeholder: "เริ่มต้นเขียนงานวิจัย... พิมพ์ @ เพื่ออ้างอิง หรือใช้ AI ช่วยเกลา",
        citationAdded: "เพิ่มการอ้างอิงแล้ว",
        savedSuccess: "บันทึกต้นฉบับเสร็จสมบูรณ์",
        selectText: "กรุณาเลือกข้อความที่ต้องการให้ AI ช่วยจัดการ",
        aiThinking: "AI กำลังวิเคราะห์...",
        aiSuccess: "เพิ่มข้อเสนอแนะจาก AI ไว้ใต้ข้อความที่เลือกแล้ว",
        aiFailed: "AI ไม่สามารถทำงานได้ กรุณาตรวจสอบการตั้งค่า",
        aiDraft: "คำแนะนำจาก AI",
        aiCrafting: "AI กำลังเรียบเรียงข้อความให้คุณ...",
        words: "จำนวนคำ",
        autoFormatting: "เปิดใช้งานการจัดรูปแบบอัตโนมัติ",
        pageBreak: "เพิ่มหน้ากระดาษ"
    }
};

interface Reference {
    id: number;
    title: string;
    authors: string[];
    year?: string;
    type: string;
}

interface ResearchEditorProps {
    manuscript: {
        id: number;
        title: string;
        content: string;
    };
    references: Reference[];
}

export interface ResearchEditorRef {
    insertCitation: (reference: Reference) => void;
    manualSave: () => Promise<void>;
}

import { Layout } from 'lucide-react';

const MenuBar = ({ editor, lastSaved, isSaving, onAIAction, txt, language }: { 
    editor: any, 
    lastSaved: string | null, 
    isSaving: boolean, 
    onAIAction: (type: string) => void,
    txt: (key: string) => string,
    language: string
}) => {
    if (!editor) return null;

    const Button = ({ onClick, isActive, disabled, children, tooltip }: any) => (
        <button
            onClick={onClick}
            disabled={disabled}
            title={tooltip}
            className={cn(
                "rounded p-1.5 transition-colors hover:bg-gray-100 disabled:opacity-50 dark:hover:bg-gray-800",
                isActive ? "bg-gray-200 text-scribehub-blue dark:bg-gray-700 dark:text-white" : "text-gray-600 dark:text-gray-400"
            )}
        >
            {children}
        </button>
    );

    return (
        <div className="sticky top-0 z-20 flex flex-wrap items-center justify-between border-b bg-white/80 backdrop-blur px-2 py-1 dark:border-gray-800 dark:bg-[#0a0a0a]/80 lg:px-4">
            <div className="flex flex-wrap items-center gap-0.5">
                <Button onClick={() => editor.chain().focus().toggleBold().run()} isActive={editor.isActive('bold')} tooltip={txt('bold')}>
                    <Bold className="h-4 w-4" />
                </Button>
                <Button onClick={() => editor.chain().focus().toggleItalic().run()} isActive={editor.isActive('italic')} tooltip={txt('italic')}>
                    <Italic className="h-4 w-4" />
                </Button>
                
                <div className="mx-1 h-4 w-px bg-gray-200 dark:bg-gray-700" />
                
                <Button onClick={() => editor.chain().focus().toggleHeading({ level: 1 }).run()} isActive={editor.isActive('heading', { level: 1 })} tooltip={txt('h1')}>
                    <Heading1 className="h-4 w-4" />
                </Button>
                <Button onClick={() => editor.chain().focus().toggleHeading({ level: 2 }).run()} isActive={editor.isActive('heading', { level: 2 })} tooltip={txt('h2')}>
                    <Heading2 className="h-4 w-4" />
                </Button>
                
                <div className="mx-1 h-4 w-px bg-gray-200 dark:bg-gray-700" />
                
                <Button onClick={() => editor.chain().focus().toggleBulletList().run()} isActive={editor.isActive('bulletList')} tooltip={txt('bullets')}>
                    <List className="h-4 w-4" />
                </Button>
                <Button onClick={() => editor.chain().focus().toggleBlockquote().run()} isActive={editor.isActive('blockquote')} tooltip={txt('quote')}>
                    <Quote className="h-4 w-4" />
                </Button>

                <Button onClick={() => editor.chain().focus().setHorizontalRule().run()} tooltip={txt('pageBreak')}>
                    <Layout className="h-4 w-4" />
                </Button>

                <div className="mx-1 h-4 w-px bg-gray-200 dark:bg-gray-700" />

                <button 
                    onClick={() => onAIAction('paraphrase')}
                    className="flex items-center gap-1.5 rounded-full bg-gradient-to-r from-scribehub-blue to-purple-600 px-3 py-1 text-[11px] font-bold text-white shadow-sm transition-all hover:scale-105 active:scale-95"
                >
                    <Sparkles className="h-3 w-3" /> {txt('aiAssistant')}
                </button>
            </div>

            <div className="hidden items-center gap-2 text-[10px] font-bold text-gray-400 sm:flex">
                {isSaving ? (
                    <span className="flex items-center gap-1 text-scribehub-blue">
                        <Loader2 className="h-3 w-3 animate-spin" /> {txt('saving')}
                    </span>
                ) : lastSaved ? (
                    <span className="flex items-center gap-1 text-emerald-500">
                        <CheckCircle className="h-3 w-3" /> {DateTime.fromSQL(lastSaved).setLocale(language).toRelative()}
                    </span>
                ) : null}
            </div>
        </div>
    );
};

import HorizontalRule from '@tiptap/extension-horizontal-rule';

const ResearchEditor = forwardRef<ResearchEditorRef, ResearchEditorProps>(({ manuscript, references }, ref) => {
    const { language, t } = useLanguage();
    const [isSaving, setIsSaving] = useState(false);
    const [lastSaved, setLastSaved] = useState<string | null>(null);
    const [isAILoading, setIsAILoading] = useState(false);
    const saveTimeoutRef = useRef<NodeJS.Timeout | null>(null);

    const txt = (key: string) => t(key, translations) as string;

    const editor = useEditor({
        extensions: [
            StarterKit,
            HorizontalRule,
            Placeholder.configure({
                placeholder: txt('placeholder'),
            }),
            CharacterCount,
            Mention.configure({
                HTMLAttributes: { class: 'mention text-scribehub-blue dark:text-blue-400 font-bold decoration-dotted underline' },
                suggestion: {
                    items: ({ query }) => references.filter(i => 
                        i.title.toLowerCase().includes(query.toLowerCase()) || 
                        i.authors?.join(' ').toLowerCase().includes(query.toLowerCase())
                    ).slice(0, 5),
                },
            }),
        ],
        content: manuscript.content,
        editorProps: {
            attributes: {
                class: 'prose prose-sm sm:prose-base dark:prose-invert focus:outline-none max-w-none p-6 lg:p-10 min-h-[50vh] bg-white dark:bg-[#0a0a0a]',
            },
        },
        onUpdate: ({ editor }) => {
            if (saveTimeoutRef.current) clearTimeout(saveTimeoutRef.current);
            saveTimeoutRef.current = setTimeout(() => handleSave(editor.getHTML()), 3000);
        },
    });

    useImperativeHandle(ref, () => ({
        insertCitation: (reference: Reference) => {
            if (editor) {
                const year = reference.year ? `, ${reference.year}` : '';
                const author = reference.authors?.[0] || 'Unknown';
                editor.chain().focus().insertContent(`<span class="mention" data-type="mention" data-id="${reference.id}">(${author}${year})</span> `).run();
                toast.success(txt('citationAdded'));
            }
        },
        manualSave: async () => {
            if (editor) {
                await handleSave(editor.getHTML());
                toast.success(txt('savedSuccess'));
            }
        }
    }));

    const handleSave = async (content: string) => {
        setIsSaving(true);
        try {
            const response = await axios.patch(`/workspace/${manuscript.id}`, { content });
            if (response.data.success) setLastSaved(response.data.updated_at);
        } catch (error) {
            console.error('Autosave failed:', error);
        } finally {
            setIsSaving(false);
        }
    };

    const handleAIAction = async (type: string) => {
        if (!editor) return;
        const selectedText = editor.state.doc.textBetween(editor.state.selection.from, editor.state.selection.to, ' ');
        
        if (!selectedText || selectedText.length < 10) {
            toast.error(txt('selectText'));
            return;
        }

        setIsAILoading(true);
        toast.info(txt('aiThinking'));

        try {
            const response = await axios.post('/ai/paraphrase', { text: selectedText });
            const result = response.data.result;
            
            editor.chain().focus().insertContent(`<blockquote><strong>${txt('aiDraft')}:</strong> ${result}</blockquote>`).run();
            toast.success(txt('aiSuccess'));
        } catch (error) {
            toast.error(txt('aiFailed'));
        } finally {
            setIsAILoading(false);
        }
    };

    useEffect(() => {
        const handleInsertSelection = (event: any) => {
            const { text, citation } = event.detail;
            if (editor) editor.chain().focus().insertContent(`<blockquote>"${text}"</blockquote><p>(${citation})</p> `).run();
        };
        window.addEventListener('PDF_SELECTION_INSERT', handleInsertSelection);
        return () => window.removeEventListener('PDF_SELECTION_INSERT', handleInsertSelection);
    }, [editor]);

    return (
        <div className="flex h-full flex-col bg-white dark:bg-[#0a0a0a]">
            <MenuBar editor={editor} lastSaved={lastSaved} isSaving={isSaving} onAIAction={handleAIAction} txt={txt} language={language} />
            <div className="flex-1 overflow-y-auto custom-scrollbar bg-gray-50/30 dark:bg-black/20 pb-20 pt-4">
                <style dangerouslySetInnerHTML={{ __html: `
                    .ProseMirror hr {
                        border: none;
                        border-top: 2px dashed #3b82f6;
                        margin: 4rem 0;
                        position: relative;
                        opacity: 0.5;
                        cursor: default;
                    }
                    .ProseMirror hr::after {
                        content: '${language === 'en' ? 'PAGE BREAK' : 'เพิ่มหน้าใหม่'}';
                        position: absolute;
                        left: 50%;
                        top: 50%;
                        transform: translate(-50%, -50%);
                        background: #3b82f6;
                        color: white;
                        font-size: 8px;
                        font-weight: 900;
                        padding: 2px 8px;
                        border-radius: 4px;
                        letter-spacing: 0.1em;
                    }
                `}} />
                <div className="mx-auto max-w-3xl border-x bg-white min-h-full shadow-inner dark:border-gray-800 dark:bg-[#0a0a0a]">
                    <EditorContent editor={editor} />
                    {isAILoading && (
                        <div className="p-10 flex flex-col items-center justify-center text-gray-400 gap-2 animate-pulse">
                            <Wand2 className="h-8 w-8 animate-bounce text-scribehub-blue" />
                            <p className="text-xs font-bold uppercase tracking-widest text-scribehub-blue">{txt('aiCrafting')}</p>
                        </div>
                    )}
                </div>
            </div>
            
            <div className="sticky bottom-0 z-20 border-t bg-white px-4 py-2 text-[10px] text-gray-400 dark:border-gray-800 dark:bg-[#0a0a0a] flex justify-between items-center sm:relative sm:z-0 transition-all">
                <div className="flex items-center gap-4">
                    <span className="font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">ScribeHub v2</span>
                    <span>{txt('words')}: {editor?.storage.characterCount.words()}</span>
                </div>
                <div className="flex items-center gap-1">
                    <Type className="h-3 w-3" /> {txt('autoFormatting')}
                </div>
            </div>
        </div>
    );
});

ResearchEditor.displayName = 'ResearchEditor';

export default ResearchEditor;
