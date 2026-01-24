import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { Copy, Check, FileText, BookOpen, Loader2, Quote } from 'lucide-react';
import { useState } from 'react';
import { cn } from '@/lib/utils';
import { useLanguage } from '@/contexts/language-context';

// Types
interface Reference {
    id: number;
    title: string;
    authors: string[] | null;
    type: string;
    year: string | null;
}

interface Props {
    references: Reference[];
    styles: Record<string, string>;
}

// Translations
const translations = {
    en: {
        title: "Citation Generator",
        subtitle: "Generate formatted citations in various styles",
        selectReferences: "Select References",
        selectStyle: "Select Citation Style",
        generateBibliography: "Generate Bibliography",
        generating: "Generating...",
        copiedAll: "Copied all!",
        copyAll: "Copy All",
        noReferences: "No references selected",
        noReferencesDesc: "Select one or more references from your library to generate citations.",
        bibliography: "Bibliography",
        inTextCitation: "In-text Citation",
        preview: "Preview",
        selected: "selected",
        styleGroups: {
            international: "International Styles",
            thai: "Thai University Styles"
        }
    },
    th: {
        title: "ตัวสร้างการอ้างอิง",
        subtitle: "สร้างรูปแบบการอ้างอิงในสไตล์ต่างๆ",
        selectReferences: "เลือกรายการอ้างอิง",
        selectStyle: "เลือกรูปแบบการอ้างอิง",
        generateBibliography: "สร้างบรรณานุกรม",
        generating: "กำลังสร้าง...",
        copiedAll: "คัดลอกทั้งหมดแล้ว!",
        copyAll: "คัดลอกทั้งหมด",
        noReferences: "ยังไม่ได้เลือกรายการอ้างอิง",
        noReferencesDesc: "เลือกรายการอ้างอิงหนึ่งรายการขึ้นไปจากคลังของคุณเพื่อสร้างการอ้างอิง",
        bibliography: "บรรณานุกรม",
        inTextCitation: "การอ้างอิงในเนื้อหา",
        preview: "ตัวอย่าง",
        selected: "รายการที่เลือก",
        styleGroups: {
            international: "รูปแบบสากล",
            thai: "รูปแบบมหาวิทยาลัยไทย"
        }
    }
};

const internationalStyles = ['apa7', 'mla9', 'chicago', 'ieee', 'harvard'];
const thaiStyles = ['thai-cu', 'thai-tu', 'thai-mu'];

export default function CitationsIndex({ references, styles }: Props) {
    const { language } = useLanguage();
    const t = translations[language];

    const [selectedRefs, setSelectedRefs] = useState<number[]>([]);
    const [selectedStyle, setSelectedStyle] = useState('apa7');
    const [citations, setCitations] = useState<{ id: number; title: string; citation: string; in_text_citation: string }[]>([]);
    const [loading, setLoading] = useState(false);
    const [activeTab, setActiveTab] = useState<'bibliography' | 'inText'>('bibliography');
    const [copiedId, setCopiedId] = useState<number | null>(null);
    const [copiedAll, setCopiedAll] = useState(false);

    const breadcrumbs: BreadcrumbItem[] = [
        { title: t.title, href: '/citations' },
    ];

    const toggleRef = (id: number) => {
        setSelectedRefs(prev =>
            prev.includes(id) ? prev.filter(r => r !== id) : [...prev, id]
        );
    };

    const generateBibliography = async () => {
        if (selectedRefs.length === 0) return;

        setLoading(true);
        try {
            const response = await fetch('/citations/bibliography', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                },
                body: JSON.stringify({
                    reference_ids: selectedRefs,
                    style: selectedStyle,
                }),
            });

            const data = await response.json();
            if (data.success) {
                setCitations(data.citations);
            }
        } catch (error) {
            console.error('Error generating bibliography:', error);
        } finally {
            setLoading(false);
        }
    };

    const copyCitation = (id: number, citation: string) => {
        navigator.clipboard.writeText(citation.replace(/<[^>]*>/g, ''));
        setCopiedId(id);
        setTimeout(() => setCopiedId(null), 2000);
    };

    const copyAllCitations = () => {
        const key = activeTab === 'bibliography' ? 'citation' : 'in_text_citation';
        const allText = citations.map(c => (c as any)[key].replace(/<[^>]*>/g, '')).join('\n\n');
        navigator.clipboard.writeText(allText);
        setCopiedAll(true);
        setTimeout(() => setCopiedAll(false), 2000);
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t.title} />

            <div className="flex h-full flex-1 flex-col gap-6 bg-scribehub-paper/50 p-6 dark:bg-[#050505]/50 animate-in fade-in duration-700">
                {/* Header */}
                <div>
                    <h1 className="text-3xl font-extrabold text-scribehub-blue dark:text-white">{t.title}</h1>
                    <p className="mt-1 text-sm font-medium text-gray-500 dark:text-gray-400">{t.subtitle}</p>
                </div>

                <div className="grid gap-6 lg:grid-cols-2">
                    {/* Left: Selection Panel */}
                    <div className="space-y-6">
                        {/* Reference Selection */}
                        <div className="rounded-3xl border border-white bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900/50">
                            <div className="mb-4 flex items-center justify-between">
                                <h2 className="text-lg font-extrabold text-scribehub-blue dark:text-white">
                                    {t.selectReferences}
                                </h2>
                                <span className="text-xs font-bold text-gray-400">
                                    {selectedRefs.length} {t.selected}
                                </span>
                            </div>

                            <div className="max-h-64 space-y-2 overflow-y-auto">
                                {references.map(ref => (
                                    <div
                                        key={ref.id}
                                        onClick={() => toggleRef(ref.id)}
                                        className={cn(
                                            "flex cursor-pointer items-start gap-3 rounded-xl border p-3 transition-colors",
                                            selectedRefs.includes(ref.id)
                                                ? "border-scribehub-blue/30 bg-scribehub-blue/5"
                                                : "border-gray-100 hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-800/50"
                                        )}
                                    >
                                        <div className={cn(
                                            "mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded border-2 transition-colors",
                                            selectedRefs.includes(ref.id)
                                                ? "border-scribehub-blue bg-scribehub-blue text-white"
                                                : "border-gray-300 dark:border-gray-600"
                                        )}>
                                            {selectedRefs.includes(ref.id) && <Check className="h-3 w-3" />}
                                        </div>
                                        <div className="min-w-0 flex-1">
                                            <p className="truncate text-sm font-bold text-gray-800 dark:text-white">{ref.title}</p>
                                            <p className="text-xs text-gray-500">
                                                {ref.authors?.join(', ') || 'Unknown'} {ref.year && `(${ref.year})`}
                                            </p>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>

                        {/* Style Selection */}
                        <div className="rounded-3xl border border-white bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900/50">
                            <h2 className="mb-4 text-lg font-extrabold text-scribehub-blue dark:text-white">
                                {t.selectStyle}
                            </h2>

                            {/* International Styles */}
                            <div className="mb-4">
                                <p className="mb-2 text-xs font-bold uppercase tracking-wider text-gray-400">
                                    {t.styleGroups.international}
                                </p>
                                <div className="flex flex-wrap gap-2">
                                    {internationalStyles.map(style => (
                                        <button
                                            key={style}
                                            onClick={() => setSelectedStyle(style)}
                                            className={cn(
                                                "rounded-lg px-3 py-2 text-xs font-bold transition-colors",
                                                selectedStyle === style
                                                    ? "bg-scribehub-blue text-white"
                                                    : "bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-400"
                                            )}
                                        >
                                            {styles[style]}
                                        </button>
                                    ))}
                                </div>
                            </div>

                            {/* Thai Styles */}
                            <div>
                                <p className="mb-2 text-xs font-bold uppercase tracking-wider text-gray-400">
                                    {t.styleGroups.thai}
                                </p>
                                <div className="flex flex-wrap gap-2">
                                    {thaiStyles.map(style => (
                                        <button
                                            key={style}
                                            onClick={() => setSelectedStyle(style)}
                                            className={cn(
                                                "rounded-lg px-3 py-2 text-xs font-bold transition-colors",
                                                selectedStyle === style
                                                    ? "bg-amber-500 text-white"
                                                    : "bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-400"
                                            )}
                                        >
                                            {styles[style]}
                                        </button>
                                    ))}
                                </div>
                            </div>

                            <button
                                onClick={generateBibliography}
                                disabled={loading || selectedRefs.length === 0}
                                className="mt-6 w-full rounded-xl bg-scribehub-blue py-3 text-sm font-bold text-white transition-all hover:opacity-90 disabled:opacity-50"
                            >
                                {loading ? (
                                    <span className="flex items-center justify-center gap-2">
                                        <Loader2 className="h-4 w-4 animate-spin" /> {t.generating}
                                    </span>
                                ) : t.generateBibliography}
                            </button>
                        </div>
                    </div>

                    {/* Right: Output Panel */}
                    <div className="rounded-3xl border border-white bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900/50">
                        <div className="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div className="flex rounded-xl bg-gray-100 p-1 dark:bg-gray-800">
                                <button
                                    onClick={() => setActiveTab('bibliography')}
                                    className={cn(
                                        "flex items-center gap-2 rounded-lg px-4 py-2 text-xs font-bold transition-all",
                                        activeTab === 'bibliography'
                                            ? "bg-white text-scribehub-blue shadow-sm dark:bg-gray-700 dark:text-white"
                                            : "text-gray-500 hover:text-gray-700 dark:text-gray-400"
                                    )}
                                >
                                    <BookOpen className="h-4 w-4" />
                                    {t.bibliography}
                                </button>
                                <button
                                    onClick={() => setActiveTab('inText')}
                                    className={cn(
                                        "flex items-center gap-2 rounded-lg px-4 py-2 text-xs font-bold transition-all",
                                        activeTab === 'inText'
                                            ? "bg-white text-scribehub-blue shadow-sm dark:bg-gray-700 dark:text-white"
                                            : "text-gray-500 hover:text-gray-700 dark:text-gray-400"
                                    )}
                                >
                                    <Quote className="h-4 w-4" />
                                    {t.inTextCitation}
                                </button>
                            </div>
                            
                            {citations.length > 0 && (
                                <button
                                    onClick={copyAllCitations}
                                    className={cn(
                                        "flex items-center justify-center gap-2 rounded-lg px-4 py-2 text-xs font-bold transition-colors",
                                        copiedAll
                                            ? "bg-green-100 text-green-600"
                                            : "bg-scribehub-blue text-white hover:opacity-90"
                                    )}
                                >
                                    {copiedAll ? <Check className="h-3 w-3" /> : <Copy className="h-3 w-3" />}
                                    {copiedAll ? t.copiedAll : t.copyAll}
                                </button>
                            )}
                        </div>

                        {citations.length === 0 ? (
                            <div className="flex flex-col items-center justify-center py-12 text-center">
                                <div className="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-50 dark:bg-gray-800">
                                    <FileText className="h-8 w-8 text-gray-300 dark:text-gray-600" />
                                </div>
                                <h3 className="font-bold text-gray-600 dark:text-gray-400">{t.noReferences}</h3>
                                <p className="mt-1 max-w-xs text-sm text-gray-400">{t.noReferencesDesc}</p>
                            </div>
                        ) : (
                            <div className="space-y-4">
                                {citations.map((item, index) => (
                                    <div
                                        key={item.id}
                                        className="group relative rounded-xl border border-gray-100 bg-gray-50/50 p-4 dark:border-gray-800 dark:bg-gray-800/30"
                                    >
                                        <div
                                            className="text-sm leading-relaxed text-gray-700 dark:text-gray-300"
                                            dangerouslySetInnerHTML={{ __html: activeTab === 'bibliography' ? item.citation : item.in_text_citation }}
                                        />
                                        <button
                                            onClick={() => copyCitation(item.id, activeTab === 'bibliography' ? item.citation : item.in_text_citation)}
                                            className={cn(
                                                "absolute right-2 top-2 rounded-lg p-2 transition-colors",
                                                copiedId === item.id
                                                    ? "bg-green-100 text-green-600"
                                                    : "bg-white text-gray-400 opacity-0 hover:text-gray-600 group-hover:opacity-100 dark:bg-gray-800"
                                            )}
                                        >
                                            {copiedId === item.id ? <Check className="h-4 w-4" /> : <Copy className="h-4 w-4" />}
                                        </button>
                                    </div>
                                ))}
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
