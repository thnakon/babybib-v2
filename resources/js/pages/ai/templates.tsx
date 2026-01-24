import AppLayout from '@/layouts/app-layout';
import { Head, Link, router } from '@inertiajs/react';
import { useState, useMemo } from 'react';
import { Card, CardContent } from '@/components/ui/card';
import { 
    FileCode, Search, BookOpen, 
    ArrowUpRight, Layout, Hash,
    FileText, GraduationCap, Sparkles, HelpCircle,
    Download, Copy, Eye, Clock, Check, X
} from 'lucide-react';
import { cn } from '@/lib/utils';
import { BreadcrumbItem } from '@/types';
import { useLanguage } from '@/contexts/language-context';
import { 
    Dialog, 
    DialogContent, 
    DialogHeader, 
    DialogTitle,
    DialogDescription 
} from '@/components/ui/dialog';
import { toast } from 'sonner';

const translations = {
    en: {
        title: "Research Templates",
        subtitle: "Professional academic templates for various research documents.",
        searchPlaceholder: "Search for templates (e.g., Abstract, Proposal)...",
        useTemplate: "Use Template",
        preview: "Preview",
        categories: {
            all: "All Templates",
            proposal: "Proposal",
            manuscript: "Manuscript",
            abstract: "Abstract",
            ethics: "Ethics & Forms",
            report: "Reports"
        },
        help: "Help",
        engine: "Template Engine",
        mappingActive: "Syncing with Standards",
        graphOnline: "Template Library Active",
        creating: "Creating your manuscript...",
        previewTitle: "Template Preview",
        structure: "Structure & Sections",
        close: "Close"
    },
    th: {
        title: "แบบร่างงานวิจัย",
        subtitle: "แม่แบบเอกสารทางวิชาการมืออาชีพสำหรับงานวิจัยประเภทต่างๆ",
        searchPlaceholder: "ระบุประเภทแม่แบบ (เช่น บทคัดย่อ, ข้อเสนอแนะ)...",
        useTemplate: "ใช้งาน",
        preview: "ดูตัวอย่าง",
        categories: {
            all: "ทั้งหมด",
            proposal: "โครงร่างวิจัย",
            manuscript: "ต้นฉบับบทความ",
            abstract: "บทคัดย่อ",
            ethics: "จริยธรรมและฟอร์ม",
            report: "รายงาน"
        },
        help: "ช่วยเหลือ",
        engine: "ระบบแม่แบบ",
        mappingActive: "กำลังเชื่อมต่อมาตรฐาน",
        graphOnline: "คลังแม่แบบพร้อมใช้งาน",
        creating: "กำลังสร้างฉบับร่างของคุณ...",
        previewTitle: "ตัวอย่างแม่แบบ",
        structure: "โครงสร้างและหัวข้อ",
        close: "ปิด"
    }
};

interface Template {
    id: string;
    title: string;
    description: string;
    category: string;
    icon: any;
    lastUpdated: string;
    isPro?: boolean;
    content: string; // HTML structure for the editor
}

export default function ResearchTemplates() {
    const { language } = useLanguage();
    const [searchQuery, setSearchQuery] = useState('');
    const [activeCategory, setActiveCategory] = useState('all');
    const [previewTemplate, setPreviewTemplate] = useState<Template | null>(null);
    const [isCreating, setIsCreating] = useState(false);

    const txt = (key: string) => {
        const path = key.split('.');
        let current: any = translations[language as keyof typeof translations];
        for (const p of path) {
            if (current && current[p]) current = current[p];
            else return key;
        }
        return current;
    };

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: txt('title'),
            href: '/research-templates',
        },
    ];

    const templateData: Template[] = [
        {
            id: 'abstract-standard',
            title: language === 'en' ? 'Standard Academic Abstract' : 'บทคัดย่อมาตรฐานวิชาการ',
            description: language === 'en' ? 'A structured abstract template including Background, Methods, Results, and Conclusion.' : 'โครงสร้างบทคัดย่อประกอบด้วย ความเป็นมา, วิธีการ, ผลลัพธ์ และบทสรุป',
            category: 'abstract',
            icon: FileText,
            lastUpdated: '2 days ago',
            content: `
                <h1>Abstract</h1>
                <p><strong>Background:</strong> [Describe the context and importance of the study]</p>
                <p><strong>Objectives:</strong> [What were the main goals or hypotheses?]</p>
                <p><strong>Methods:</strong> [Design, participants, setting, and interventions]</p>
                <p><strong>Results:</strong> [Key findings and statistical significance]</p>
                <p><strong>Conclusion:</strong> [Synthesis of findings and practical implications]</p>
                <p><strong>Keywords:</strong> word1, word2, word3</p>
            `
        },
        {
            id: 'proposal-th-uni',
            title: language === 'en' ? 'Thai University Research Proposal' : 'ข้อเสนอโครงร่างวิจัย (มาตรฐาน มหาวิทยาลัยไทย)',
            description: language === 'en' ? 'Verified structure for Thai university Master/PhD proposals.' : 'โครงสร้างที่ผ่านการตรวจสอบสำหรับข้อเสนอวิทยานิพนธ์ ระดับ ป.โท/ป.เอก',
            category: 'proposal',
            icon: GraduationCap,
            lastUpdated: '1 week ago',
            content: `
                <h1>โครงร่างดุษฎีนิพนธ์/วิทยานิพนธ์</h1>
                <h2>บทที่ 1 บทนำ</h2>
                <p>1.1 ความสำคัญและที่มาของปัญหา</p>
                <p>1.2 วัตถุประสงค์ของการวิจัย</p>
                <p>1.3 สมมติฐานของการวิจัย (ถ้ามี)</p>
                <p>1.4 ขอบเขตของการวิจัย</p>
                <p>1.5 นิยามศัพท์เฉพาะ</p>
                <p>1.6 ประโยชน์ที่คาดว่าจะได้รับ</p>
                <h2>บทที่ 2 การตรวจเอกสารและงานวิจัยที่เกี่ยวข้อง</h2>
                <p>[ระบุแนวคิด ทฤษฎี และงานวิจัยที่เกี่ยวข้อง...]</p>
                <h2>บทที่ 3 วิธีดำเนินการวิจัย</h2>
                <p>3.1 ประชากรและกลุ่มตัวอย่าง</p>
                <p>3.2 เครื่องมือที่ใช้ในการวิจัย</p>
                <p>3.3 การเก็บรวบรวมข้อมูล</p>
                <p>3.4 การวิเคราะห์ข้อมูล</p>
            `
        },
        {
            id: 'manuscript-ieee',
            title: language === 'en' ? 'IEEE Conference Manuscript' : 'ต้นฉบับบทความประชุมวิชาการ IEEE',
            description: language === 'en' ? 'Double-column format structure for engineering and computer science papers.' : 'รูปแบบสำหรับการประชุมวิชาการด้านวิศวกรรมและวิทยาการคอมพิวเตอร์',
            category: 'manuscript',
            icon: FileCode,
            lastUpdated: '3 days ago',
            isPro: true,
            content: `
                <h1>Manuscript Title Here</h1>
                <p><em>Author Name, Department, Organization, Email</em></p>
                <hr/>
                <h3>Abstract</h3>
                <p>This template provides authors with most of the formatting specifications needed for preparing electronic versions of their papers...</p>
                <h3>I. Introduction</h3>
                <p>Placeholder for introduction text...</p>
                <h3>II. Related Work</h3>
                <p>Current state of the art...</p>
                <h3>III. Proposed Methodology</h3>
                <p>Detailed technical description...</p>
                <h3>IV. Results and Discussion</h3>
                <p>Analysis of performance...</p>
                <h3>V. Conclusion</h3>
                <p>Final remarks and future directions...</p>
                <h3>References</h3>
                <p>[Use @ symbol to insert references here]</p>
            `
        },
        {
            id: 'ethics-consent',
            title: language === 'en' ? 'Informed Consent Form' : 'แบบฟอร์มยินยอมการเข้าร่วมวิจัย',
            description: language === 'en' ? 'Standard ethics committee approved consent form for human subjects.' : 'แบบฟอร์มความยินยอมตามมาตรฐานคณะกรรมการจริยธรรมในมนุษย์',
            category: 'ethics',
            icon: Check,
            lastUpdated: '1 month ago',
            content: `
                <h1>เอกสารแสดงความยินยอมเข้าร่วมโครงการวิจัย (Informed Consent Form)</h1>
                <p><strong>ชื่อโครงการ:</strong> [ชื่อโครงการวิจัย]</p>
                <p>วันทีให้ความยินยอม: ............................................................</p>
                <p>ข้าพเจ้า .............................................................. อายุ ................ ปี อยู่บ้านเลขที่ ........................................</p>
                <p>ได้รับทราบรายละเอียดเกี่ยวกับที่มาและวัตถุประสงค์ของการทำวิจัย ขั้นตอนต่างๆ ที่ข้าพเจ้าจะต้องปฏิบัติ ประโยชน์ที่คาดว่าจะได้รับ และความเสี่ยงที่อาจเกิดขึ้นแล้ว...</p>
                <p>ข้าพเจ้าสมัครใจเข้าร่วมในโครงการวิจัยนี้ด้วยความเต็มใจ และสามารถถอนตัวออกจากการศึกษาได้ทุกเมื่อโดยไม่มีผลกระทบใดๆ...</p>
                <br/><br/>
                <p>ลงชื่อ .............................................................. ผู้ให้ความยินยอม</p>
                <p>ลงชื่อ .............................................................. พยาน</p>
            `
        },
        {
            id: 'manuscript-apa',
            title: language === 'en' ? 'APA 7th Edition Paper' : 'บทความวิจัยตามรูปแบบ APA ฉบับที่ 7',
            description: language === 'en' ? 'Social sciences standard formatting for professional manuscripts.' : 'มาตรฐานการจัดรูปแบบสำหรับงานวิจัยด้านสังคมศาสตร์',
            category: 'manuscript',
            icon: BookOpen,
            lastUpdated: '5 days ago',
            content: `
                <h1>Paper Title</h1>
                <p>Author Name</p>
                <p>Affiliation</p>
                <p>Author Note</p>
                <hr/>
                <h2>Abstract</h2>
                <p>Start your abstract here. A typical abstract is between 150-250 words.</p>
                <h2>Introduction</h2>
                <p>The introduction presents the specific problem under study and describes the research strategy...</p>
                <h2>Method</h2>
                <p>The Method section describes in detail how the study was conducted...</p>
                <h2>Results</h2>
                <p>The Results section summarizes the collected data and the analysis performed...</p>
                <h2>Discussion</h2>
                <p>The Discussion section evaluates and interprets the implications of the results...</p>
            `
        },
        {
            id: 'report-progress',
            title: language === 'en' ? 'Monthly Research Progress Report' : 'รายงานความก้าวหน้างานวิจัยรายเดือน',
            description: language === 'en' ? 'Track milestones, current status, and upcoming tasks for your research project.' : 'ติดตามความคืบหน้า สถานะปัจจุบัน และงานที่ต้องทำถัดไปของโครงการวิจัย',
            category: 'report',
            icon: Sparkles,
            lastUpdated: '1 day ago',
            content: `
                <h1>Research Progress Report</h1>
                <p><strong>Reporting Period:</strong> [Month/Year]</p>
                <p><strong>Researcher:</strong> [Name]</p>
                <hr/>
                <h3>1. Key Achievements</h3>
                <p>[List major accomplishments during this period]</p>
                <h3>2. Work in Progress</h3>
                <p>[Current tasks and percentage of completion]</p>
                <h3>3. Issues & Roadblocks</h3>
                <p>[Describe any challenges faced]</p>
                <h3>4. Next Steps</h3>
                <p>[Planned activities for the next period]</p>
            `
        },
        {
            id: 'report-lab',
            title: language === 'en' ? 'Scientific Lab Report' : 'รายงานผลการทดลองทางวิทยาศาสตร์',
            description: language === 'en' ? 'A detailed structure for documenting experiments, data analysis, and observations.' : 'โครงสร้างที่ละเอียดสำหรับการบันทึกการทดลอง การวิเคราะห์ข้อมูล และข้อสังเกต',
            category: 'report',
            icon: FileCode,
            lastUpdated: '4 days ago',
            content: `
                <h1>Lab Report</h1>
                <p><strong>Experiment Name:</strong> [Title]</p>
                <p><strong>Date:</strong> [Date]</p>
                <hr/>
                <h3>Introduction</h3>
                <p>[Purpose and hypothesis of the experiment]</p>
                <h3>Materials & Equipment</h3>
                <p>[List all items used]</p>
                <h3>Procedure</h3>
                <p>[Step-by-step description of the experiment]</p>
                <h3>Data & Observations</h3>
                <p>[Recorded data, tables, and graphs]</p>
                <h3>Analysis & Conclusion</h3>
                <p>[Interpretation of data and final result]</p>
            `
        }
    ];

    const filteredTemplates = useMemo(() => {
        return templateData.filter(template => {
            const matchesSearch = template.title.toLowerCase().includes(searchQuery.toLowerCase()) || 
                                 template.description.toLowerCase().includes(searchQuery.toLowerCase());
            const matchesCategory = activeCategory === 'all' || template.category === activeCategory;
            return matchesSearch && matchesCategory;
        });
    }, [searchQuery, activeCategory, language]);

    const handleUseTemplate = (template: Template) => {
        if (template.isPro) {
            toast.error(language === 'en' ? 'Premium Required' : 'ต้องใช้งานเวอร์ชันพรีเมียม');
            return;
        }

        setIsCreating(true);
        toast.promise(
            new Promise((resolve, reject) => {
                router.post('/workspace', {
                    title: template.title,
                    content: template.content
                }, {
                    onSuccess: () => resolve(true),
                    onError: () => {
                        setIsCreating(false);
                        reject(false);
                    }
                });
            }),
            {
                loading: txt('creating'),
                success: language === 'en' ? 'Workspace ready!' : 'หน้าทำงานพร้อมแล้ว!',
                error: language === 'en' ? 'Failed to create.' : 'ผิดพลาดในการสร้าง'
            }
        );
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={txt('title')} />
            
            <div className="flex h-full flex-1 flex-col gap-6 bg-scribehub-paper/50 p-6 dark:bg-[#050505]/50 animate-in fade-in duration-700 overflow-hidden relative">
                {isCreating && (
                    <div className="absolute inset-0 z-50 bg-white/20 dark:bg-black/20 backdrop-blur-sm flex items-center justify-center">
                        <div className="flex flex-col items-center gap-4 p-8 bg-white dark:bg-gray-900 rounded-[2rem] shadow-2xl ring-1 ring-gray-100 dark:ring-gray-800">
                            <div className="h-12 w-12 rounded-full border-4 border-scribehub-blue/20 border-t-scribehub-blue animate-spin" />
                            <p className="text-sm font-black uppercase tracking-widest text-scribehub-blue dark:text-blue-400">{txt('creating')}</p>
                        </div>
                    </div>
                )}

                {/* Header Section */}
                <div className="flex flex-col gap-8 lg:flex-row lg:items-start lg:justify-between">
                    <div className="shrink-0 pt-2">
                        <h1 className="text-3xl font-extrabold text-scribehub-blue dark:text-white leading-none">{txt('title')}</h1>
                        <p className="mt-2 text-sm font-medium text-gray-400 dark:text-gray-500">{txt('subtitle')}</p>
                    </div>

                    <div className="flex-1 max-w-4xl w-full">
                        <div className="flex items-center justify-between mb-2 px-1">
                            <div className="flex items-center gap-6 overflow-x-auto no-scrollbar pb-1">
                                {Object.entries(translations[language as keyof typeof translations].categories).map(([key, label]) => (
                                    <button 
                                        key={key}
                                        onClick={() => setActiveCategory(key)}
                                        className={cn(
                                            "text-[10px] font-black uppercase tracking-[0.1em] transition-all pb-1 border-b-2 whitespace-nowrap", 
                                            activeCategory === key ? "text-scribehub-blue border-scribehub-blue dark:text-white dark:border-white" : "text-gray-300 border-transparent hover:text-gray-500"
                                        )}
                                    >
                                        {label}
                                    </button>
                                ))}
                            </div>

                            <Link href="/help" className="flex items-center gap-1.5 text-[10px] font-black uppercase tracking-wider text-gray-400 hover:text-scribehub-blue transition-colors ml-4 shrink-0">
                                <HelpCircle className="h-3 w-3" />
                                {txt('help')}
                            </Link>
                        </div>

                        <div className="relative flex items-center gap-3 rounded-xl border border-gray-100 bg-white p-1.5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:border-gray-800 dark:bg-gray-900/80 dark:shadow-none z-10 transition-all focus-within:ring-4 focus-within:ring-scribehub-blue/5">
                            <div className="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-900/20">
                                <Search className="h-4 w-4 text-scribehub-blue dark:text-blue-400" />
                            </div>
                            <input 
                                type="text"
                                placeholder={txt('searchPlaceholder')}
                                value={searchQuery}
                                onChange={(e) => setSearchQuery(e.target.value)}
                                className="flex-1 bg-transparent border-none py-1.5 text-xs font-semibold text-gray-700 placeholder:text-gray-300 focus:ring-0 dark:text-gray-200 dark:placeholder:text-gray-600"
                            />
                        </div>
                    </div>
                </div>

                {/* Content Area */}
                <div className="flex-1 overflow-auto custom-scrollbar rounded-2xl">
                    <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 pb-20 max-w-[1400px]">
                        {filteredTemplates.map((template, idx) => (
                            <Card key={template.id} className="group hover:border-scribehub-blue/30 hover:shadow-xl hover:shadow-scribehub-blue/5 transition-all duration-300 border-none shadow-sm bg-white dark:bg-[#0a0a0a] ring-1 ring-gray-100 dark:ring-gray-800 overflow-hidden rounded-xl animate-in fade-in slide-in-from-bottom-4 duration-500" style={{ animationDelay: `${idx * 50}ms` }}>
                                <CardContent className="p-6">
                                    <div className="flex justify-between items-start mb-4">
                                        <div className="flex items-center gap-3">
                                            <div className="h-10 w-10 rounded-xl bg-gray-50 dark:bg-gray-800 flex items-center justify-center text-scribehub-blue dark:text-blue-400 group-hover:bg-scribehub-blue group-hover:text-white transition-all duration-300">
                                                <template.icon className="h-5 w-5" />
                                            </div>
                                            <div>
                                                <div className="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em]">
                                                    {txt(`categories.${template.category}`)}
                                                </div>
                                                <div className="flex items-center gap-2 mt-0.5">
                                                    <Clock className="h-3 w-3 text-gray-300" />
                                                    <span className="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">{template.lastUpdated}</span>
                                                </div>
                                            </div>
                                        </div>
                                        {template.isPro && (
                                            <div className="px-2 py-1 rounded-md bg-amber-50 text-amber-600 dark:bg-amber-900/20 dark:text-amber-400 text-[8px] font-black uppercase tracking-widest border border-amber-100 dark:border-amber-900/50">
                                                PRO
                                            </div>
                                        )}
                                    </div>

                                    <h3 className="font-extrabold text-[16px] leading-tight text-gray-900 dark:text-white mb-3 group-hover:text-scribehub-blue transition-colors min-h-[2.5rem] line-clamp-2">
                                        {template.title}
                                    </h3>
                                    <p className="text-xs text-gray-500 dark:text-gray-400 leading-relaxed mb-6 line-clamp-2 font-medium h-8">
                                        {template.description}
                                    </p>

                                    <div className="flex items-center gap-2 pt-4 border-t border-gray-50 dark:border-gray-800">
                                        <button 
                                            onClick={() => handleUseTemplate(template)}
                                            className="flex-1 flex items-center justify-center gap-2 h-9 rounded-lg bg-scribehub-blue text-white text-[10px] font-black uppercase tracking-wider hover:bg-blue-600 transition-all active:scale-95 shadow-lg shadow-blue-500/10"
                                        >
                                            <FileText className="h-3.5 w-3.5" />
                                            {txt('useTemplate')}
                                        </button>
                                        <button 
                                            onClick={() => setPreviewTemplate(template)}
                                            className="flex-1 flex items-center justify-center gap-2 h-9 rounded-lg bg-gray-50 text-gray-600 text-[10px] font-black uppercase tracking-wider hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 transition-all active:scale-95 border dark:border-gray-700"
                                        >
                                            <Eye className="h-3.5 w-3.5" />
                                            {txt('preview')}
                                        </button>
                                    </div>
                                </CardContent>
                            </Card>
                        ))}
                    </div>
                </div>

                {/* Footer Status Bar */}
                <div className="h-8 shrink-0 bg-white/80 border-t dark:bg-[#0a0a0a]/80 dark:border-gray-800 flex items-center justify-between px-6 text-[8px] font-black text-gray-400 uppercase tracking-widest transition-all rounded-b-2xl">
                    <div className="flex items-center gap-4">
                        <span className="flex items-center gap-1.5 text-scribehub-blue dark:text-blue-400">
                            <div className="h-1 w-1 rounded-full bg-scribehub-blue animate-pulse" /> {txt('graphOnline')}
                        </span>
                        <span className="flex items-center gap-1.5">
                            <Layout className="h-2.5 w-2.5" /> {txt('categories.all')}: {templateData.length}
                        </span>
                        <span className="flex items-center gap-1.5">
                            <Hash className="h-2.5 w-2.5" /> {txt('engine')} v2.1
                        </span>
                    </div>
                    <div className="flex items-center gap-2">
                         SCRIBEHUB TEMPLATE CLOUD <div className="h-1 w-1 rounded-full bg-green-500" /> {txt('mappingActive')}
                    </div>
                </div>
            </div>

            {/* Preview Dialog */}
            <Dialog open={!!previewTemplate} onOpenChange={(open) => !open && setPreviewTemplate(null)}>
                <DialogContent className="max-w-2xl max-h-[85vh] flex flex-col p-0 overflow-hidden bg-scribehub-paper dark:bg-gray-950 rounded-3xl border-none shadow-2xl">
                    <DialogHeader className="p-8 pb-4 bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                        <div className="flex items-center justify-between mb-2">
                             <div className="px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-900/30 text-scribehub-blue dark:text-blue-400 text-[9px] font-black uppercase tracking-widest">
                                {previewTemplate && txt(`categories.${previewTemplate.category}`)}
                            </div>
                            <button onClick={() => setPreviewTemplate(null)} className="h-8 w-8 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 flex items-center justify-center transition-colors">
                                <X className="h-4 w-4" />
                            </button>
                        </div>
                        <DialogTitle className="text-2xl font-black text-scribehub-blue dark:text-white mb-1">
                            {previewTemplate?.title}
                        </DialogTitle>
                        <DialogDescription className="text-xs font-medium text-gray-400 dark:text-gray-500">
                            {previewTemplate?.description}
                        </DialogDescription>
                    </DialogHeader>

                    <div className="flex-1 overflow-auto p-8 custom-scrollbar pt-6">
                        <div className="flex items-center gap-2 mb-6 text-[10px] font-black uppercase tracking-wider text-gray-300">
                            <Layout className="h-3.5 w-3.5" />
                            {txt('structure')}
                        </div>
                        <div 
                            className="prose prose-sm dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 font-medium leading-relaxed bg-white/50 dark:bg-gray-900/50 p-8 rounded-2xl border border-dashed border-gray-200 dark:border-gray-800"
                            dangerouslySetInnerHTML={{ __html: previewTemplate?.content || '' }}
                        />
                    </div>

                    <div className="p-6 bg-white dark:bg-gray-900 border-t dark:border-gray-800 flex items-center gap-3">
                        <button 
                            onClick={() => {
                                if (previewTemplate) handleUseTemplate(previewTemplate);
                                setPreviewTemplate(null);
                            }}
                            className="flex-1 h-12 rounded-xl bg-scribehub-blue text-white text-xs font-black uppercase tracking-[0.1em] hover:bg-blue-600 transition-all active:scale-95 shadow-xl shadow-blue-500/20"
                        >
                            {txt('useTemplate')}
                        </button>
                        <button 
                            onClick={() => setPreviewTemplate(null)}
                            className="px-6 h-12 rounded-xl bg-gray-50 text-gray-600 text-xs font-black uppercase tracking-[0.1em] hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 transition-all active:scale-95"
                        >
                            {txt('close')}
                        </button>
                    </div>
                </DialogContent>
            </Dialog>
            
            <style dangerouslySetInnerHTML={{ __html: `
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
                .no-scrollbar::-webkit-scrollbar {
                    display: none;
                }
                .no-scrollbar {
                    -ms-overflow-style: none;
                    scrollbar-width: none;
                }
            `}} />
        </AppLayout>
    );
}
