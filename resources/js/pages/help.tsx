import { login } from '@/routes';
import type { SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';
import { 
    MoveRight, Search, Zap, Layout, Sparkles, Users, 
    CreditCard, Wrench, LifeBuoy, ChevronRight, Check, 
    Mail, MessageCircle, Facebook, Languages, Moon, Sun, 
    Monitor, BookOpen, Clock, ShieldCheck, ChevronDown, 
    FileText, HelpCircle, ArrowLeft
} from 'lucide-react';
import AppLogo from '@/components/app-logo';
import { useState } from 'react';
import { useAppearance } from '@/hooks/use-appearance';
import { cn } from '@/lib/utils';

const translations = {
    en: {
        title: "ScribeHub - Help Center",
        searchPlaceholder: "Search for help...",
        howCanWeHelp: "How Can We Help?",
        popularTopics: "Popular Topics",
        contactUs: "Contact Us",
        getInTouch: "Get In Touch",
        backToHome: "Back to Home",
        categories: [
            { id: 'getting-started', title: "Getting Started", icon: Zap, desc: "Learn the basics and set up your account" },
            { id: 'editor', title: "Editor & Split-View", icon: Layout, desc: "Master the all-in-one research writing workspace" },
            { id: 'ai', title: "AI Assistant", icon: Sparkles, desc: "Leverage AI to summarize and refine your core writing" },
            { id: 'collab', title: "Collaboration", icon: Users, desc: "Work with your team in real-time on shared projects" },
            { id: 'references', title: "Reference Management", icon: BookOpen, desc: "Manual entry, folders, projects, and exporting" },
            { id: 'trouble', title: "Troubleshooting", icon: Wrench, desc: "Common solutions for common technical issues" }
        ],
        sections: [
            {
                id: 'getting-started',
                title: "1. Getting Started",
                icon: Zap,
                articles: [
                    {
                        title: "Creating your first project",
                        content: [
                            "Go to your Dashboard and click '+ New Project'.",
                            "Name your project (e.g., Thesis Chapter 1).",
                            "Select the primary citation style (e.g., APA 7th Edition)."
                        ]
                    },
                    {
                        title: "Importing References",
                        content: [
                            "DOI/ISBN Lookup: Simply enter the DOI of an article or ISBN of a book, and the system will automatically pull the data.",
                            "Manual Entry: For sources without standard numbers, you can manually fill in the details in the form.",
                            "RIS/BibTeX Import: If you have data from Zotero or Mendeley, you can upload the file to migrate immediately.",
                            "Citation Standards: Support for APA 7th, MLA 9th, and 10,000+ global styles including Thai University formats."
                        ]
                    },
                    {
                        "title": "Mobile & Pad Access",
                        "content": [
                            "Access ScribeHub via any modern browser on your iPad or Tablet.",
                            "The Split-View editor is optimized for touch gestures, allowing for smooth reading and highlighting on the go."
                        ]
                    }
                ]
            },
            {
                id: 'editor',
                title: "2. Editor and Split-View",
                icon: Layout,
                articles: [
                    {
                        title: "Using Split-View",
                        content: [
                            "Open your project and click the 'Open PDF' icon in the toolbar.",
                            "Upload the research file you want to read.",
                            "The screen will split into two sides: Left (Read PDF) and Right (Type your work)."
                        ]
                    },
                    {
                        title: "Magic Highlight",
                        content: [
                            "While reading the PDF on the left, highlight important text.",
                            "Select 'Save as Note'. The text will appear in the Sidebar with citation info.",
                            "Drag that note into the Right Editor. The system will auto-insert the In-text Citation."
                        ]
                    }
                ]
            },
            {
                id: 'ai',
                title: "3. AI Assistant",
                icon: Sparkles,
                articles: [
                    {
                        title: "AI Summarizer",
                        content: [
                            "Click the 'Summarize' button on the PDF toolbar. AI will summarize key points like hypotheses, Research Methods, and Results in a short summary."
                        ]
                    },
                    {
                        title: "Academic Paraphraser",
                        content: [
                            "Highlight a sentence you wrote in the Editor.",
                            "Select 'Academic Tone' to help AI adjust the language to be more professional and academic."
                        ]
                    }
                ]
            },
            {
                id: 'collab',
                title: "4. Collaboration",
                icon: Users,
                articles: [
                    {
                        title: "Sharing Libraries",
                        content: [
                            "In Project Settings, select 'Invite Collaborators'.",
                            "Enter the email of team members so they can access the shared reference library and notes."
                        ]
                    },
                    {
                        title: "Real-time Collaboration",
                        content: [
                            "Team members can open the same Editor and type together. The system shows each person's cursor in real-time."
                        ]
                    }
                ]
            },
            {
                id: 'billing',
                title: "5. Billing and Account",
                icon: CreditCard,
                articles: [
                    {
                        title: "Upgrading to Pro",
                        content: [
                            "Go to the Billing page and select 'Pro Researcher'.",
                            "Pay via PromptPay or Credit Card to unlock unlimited storage and AI features."
                        ]
                    },
                    {
                        title: "Student Discount",
                        content: [
                            "If you sign up with an institutional email (.ac.th), you will automatically receive 3 months of free Pro usage."
                        ]
                    }
                ]
            },
            {
                id: 'references',
                title: "6. Reference Management",
                icon: BookOpen,
                articles: [
                    {
                        title: "Manual Entry Guide",
                        content: [
                            "Click the '+ New' button and select 'New Source'.",
                            "Choose the resource type (Book, Website, Journal, etc.).",
                            "Fill in the metadata: Title, Authors, Year, and Publisher.",
                            "Toggle 'Online Source' to add URL and Access Date for digital references."
                        ]
                    },
                    {
                        title: "Organizing with Projects & Folders",
                        content: [
                            "Projects: High-level containers for different research papers. Create via the '+ New' menu.",
                            "Folders: Sub-categorization within a project. Right-click a project or use '+ New Folder' to group references by theme or chapter.",
                            "Drag and Drop: You can reorder projects and folders by dragging the handle icon on the left."
                        ]
                    },
                    {
                        title: "Exporting Your Bibliography",
                        content: [
                            "Full List Export: Click the 'Export' icon in your Workspace toolbar.",
                            "Word (.docx): Download a perfectly formatted Word document including all citations.",
                            "PDF (.pdf): Export a clean PDF bibliography ready for printing or submission.",
                            "BibTeX/RIS: Export for use with other managers like Zotero, Mendeley, or LaTeX editors."
                        ]
                    }
                ]
            },
            {
                id: 'trouble',
                title: "7. Troubleshooting",
                icon: Wrench,
                articles: [
                    {
                        title: "PDF not displaying",
                        content: [
                            "Check your internet connection or try refreshing the page."
                        ]
                    },
                    {
                        title: "Citations not in correct format",
                        content: [
                            "Check the Metadata of the source to ensure all fields are correctly filled."
                        ]
                    },
                    {
                        title: "Can't find a button",
                        content: [
                            "Use the Search function in the Help Center to find the tool you need."
                        ]
                    }
                ]
            }
        ],
        contactInfo: {
            title: "Contact us",
            email: "support@scribehub.com",
            chat: "Live Chat: Mon-Fri 09:00 - 18:00 (Click the chat icon at the bottom right)",
            community: "Community: Join our Facebook group for tips and tricks."
        }
    },
    th: {
        title: "ScribeHub - ศูนย์ช่วยเหลือ",
        searchPlaceholder: "ค้นหาการช่วยเหลือ...",
        howCanWeHelp: "เราช่วยคุณได้อย่างไร?",
        popularTopics: "หัวข้อยอดนิยม",
        contactUs: "ติดต่อเรา",
        getInTouch: "ติดต่อเรา",
        backToHome: "กลับสู่หน้าหลัก",
        categories: [
            { id: 'getting-started', title: "เริ่มต้นใช้งาน", icon: Zap, desc: "เรียนรู้พื้นฐานและตั้งค่าบัญชีของคุณ" },
            { id: 'editor', title: "Editor & Split-View", icon: Layout, desc: "เชี่ยวชาญเครื่องมือการเขียนงานวิจัยครบวงจร" },
            { id: 'ai', title: "AI Assistant", icon: Sparkles, desc: "ใช้ AI ช่วยสรุปและปรับแต่งงานเขียนของคุณ" },
            { id: 'collab', title: "การทำงานร่วมกัน", icon: Users, desc: "ทำงานกับทีมแบบเรียลไทม์บนโปรเจกต์เดียวกัน" },
            { id: 'references', title: "การจัดการบรรณานุกรม", icon: BookOpen, desc: "การกรอกข้อมูลเอง, โฟลเดอร์, โปรเจกต์ และการส่งออก" },
            { id: 'trouble', title: "การแก้ปัญหา", icon: Wrench, desc: "วิธีแก้ปัญหาทางเทคนิคเบื้องต้นที่พบบ่อย" }
        ],
        sections: [
            {
                id: 'getting-started',
                title: "1. เริ่มต้นใช้งาน (Getting Started)",
                icon: Zap,
                articles: [
                    {
                        title: "การสร้างโปรเจกต์แรก",
                        content: [
                            "ไปที่หน้า Dashboard แล้วคลิกปุ่ม '+ New Project'",
                            "ตั้งชื่อโปรเจกต์ของคุณ (เช่น วิทยานิพนธ์บทที่ 1)",
                            "เลือกรูปแบบการอ้างอิงหลักที่ต้องการใช้ (เช่น APA 7th Edition)"
                        ]
                    },
                    {
                        title: "การนำเข้าแหล่งอ้างอิง (Importing References)",
                        content: [
                            "DOI/ISBN Lookup: เพียงกรอกเลข DOI ของบทความหรือ ISBN ของหนังสือ ระบบจะดึงข้อมูลมาให้โดยอัตโนมัติ",
                            "Manual Entry: หากเป็นแหล่งข้อมูลที่ไม่มีเลขมาตรฐาน คุณสามารถกรอกรายละเอียดเองได้ในแบบฟอร์ม",
                            "RIS/BibTeX Import: หากคุณมีข้อมูลจาก Zotero หรือ Mendeley สามารถอัปโหลดไฟล์เพื่อย้ายข้อมูลมาได้ทันที",
                            "Citation Standards: รองรับรูปแบบการอ้างอิงมาตรฐานสากล (APA, MLA) และรูปแบบเฉพาะของมหาวิทยาลัยในไทยกว่า 10,000 รูปแบบ"
                        ]
                    },
                    {
                        "title": "การใช้งานบนมือถือและแท็บเล็ต",
                        "content": [
                            "เข้าถึง ScribeHub ได้ฟรีผ่านเบราว์เซอร์บน iPad หรือแท็บเล็ตเครื่องโปรดของคุณ",
                            "ระบบ Split-View ถูกออกแบบมาให้รองรับการสัมผัส (Touch) ช่วยให้การอ่านและไฮไลท์ทำได้ลื่นไหลแม้ไม่ได้ใช้คอมพิวเตอร์"
                        ]
                    }
                ]
            },
            {
                id: 'editor',
                title: "2. การใช้งาน Editor และระบบ Split-View",
                icon: Layout,
                articles: [
                    {
                        title: "การใช้งาน Split-View",
                        content: [
                            "เปิดโปรเจกต์ของคุณ แล้วคลิกไอคอน 'Open PDF' ในแถบเครื่องมือ",
                            "อัปโหลดไฟล์งานวิจัยที่คุณต้องการอ่าน",
                            "หน้าจอจะแบ่งเป็น 2 ฝั่ง: ฝั่งซ้าย (อ่าน PDF) และ ฝั่งขวา (พิมพ์งาน)"
                        ]
                    },
                    {
                        title: "การใช้งาน Magic Highlight",
                        content: [
                            "ขณะอ่าน PDF ในฝั่งซ้าย ให้ทำการไฮไลท์ข้อความที่สำคัญ",
                            "เลือกเมนู 'Save as Note' ข้อความจะไปปรากฏในแถบ Sidebar พร้อมข้อมูลอ้างอิง",
                            "คุณสามารถลากโน้ตนั้นมาวางใน Editor ฝั่งขวาได้ทันที ระบบจะใส่ In-text Citation ให้โดยอัตโนมัติ"
                        ]
                    }
                ]
            },
            {
                id: 'ai',
                title: "3. เครื่องมือช่วยเขียนอัจฉริยะ (AI Assistant)",
                icon: Sparkles,
                articles: [
                    {
                        title: "AI Summarizer (สรุปงานวิจัย)",
                        content: [
                            "คลิกที่ปุ่ม 'Summarize' บนแถบ PDF เพื่อให้ AI สรุปประเด็นสำคัญ เช่น สมมติฐาน, วิธีการวิจัย และผลการทดลอง ในรูปแบบข้อความสั้นๆ"
                        ]
                    },
                    {
                        title: "Academic Paraphraser (ปรับโทนภาษา)",
                        content: [
                            "ไฮไลท์ประโยคที่คุณเขียนใน Editor",
                            "เลือกเมนู 'Academic Tone' เพื่อให้ AI ช่วยปรับระดับภาษาให้มีความเป็นวิชาการมากขึ้น"
                        ]
                    }
                ]
            },
            {
                id: 'collab',
                title: "4. การทำงานร่วมกัน (Collaboration)",
                icon: Users,
                articles: [
                    {
                        title: "การแชร์ Library",
                        content: [
                            "ในหน้า Project Settings เลือก 'Invite Collaborators'",
                            "กรอกอีเมลของเพื่อนร่วมทีม เพื่อให้พวกเขาสามารถเข้าถึงคลังแหล่งอ้างอิงและโน้ตร่วมกันได้"
                        ]
                    },
                    {
                        title: "การเขียนร่วมกันแบบ Real-time",
                        content: [
                            "สมาชิกในทีมสามารถเปิดหน้า Editor เดียวกันและช่วยกันพิมพ์งานได้พร้อมกัน โดยระบบจะแสดงเคอร์เซอร์ของแต่ละคน"
                        ]
                    }
                ]
            },
            {
                id: 'billing',
                title: "5. การจัดการบัญชีและแผนการใช้งาน",
                icon: CreditCard,
                articles: [
                    {
                        title: "การอัปเกรดเป็นแผน Pro",
                        content: [
                            "ไปที่หน้า Billing เพื่อเลือกแผน Pro Researcher",
                            "ชำระเงินผ่าน PromptPay หรือบัตรเครดิตเพื่อปลดล็อกพื้นที่เก็บข้อมูลและฟีเจอร์ AI ไม่จำกัด"
                        ]
                    },
                    {
                        title: "สิทธิ์สำหรับนักศึกษา (Student Discount)",
                        content: [
                            "หากสมัครด้วยอีเมลสถาบัน (.ac.th) คุณจะได้รับสิทธิ์ใช้งาน Pro ฟรี 3 เดือนแรกอัตโนมัติ"
                        ]
                    }
                ]
            },
            {
                id: 'references',
                title: "6. การจัดการบรรณานุกรม (Reference Management)",
                icon: BookOpen,
                articles: [
                    {
                        title: "การกรอกข้อมูลด้วยตนเอง (Manual Entry)",
                        content: [
                            "คลิกปุ่ม '+ New' แล้วเลือก 'New Source'",
                            "เลือกประเภททรัพยากรที่ต้องการ (หนังสือ, เว็บไซต์, วารสาร ฯลฯ)",
                            "กรอกข้อมูลพื้นฐาน เช่น ชื่อเรื่อง, ชื่อผู้แต่ง, ปีที่พิมพ์ และสำนักพิมพ์",
                            "เลือก 'เป็นแหล่งข้อมูลออนไลน์' เพื่อเพิ่ม URL และวันที่เข้าถึงสำหรับข้อมูลดิจิทัล"
                        ]
                    },
                    {
                        title: "การจัดการด้วยโปรเจกต์และโฟลเดอร์",
                        content: [
                            "โปรเจกต์: ตู้คอนเทนเนอร์หลักสำหรับงานวิจัยแต่ละเรื่อง สร้างได้ผ่านเมนู '+ New Project'",
                            "โฟลเดอร์: การแบ่งหัวข้อย่อยภายในโปรเจกต์ ใช้เพื่อแยกบรรณานุกรมตามบทหรือตามเนื้อหา (สร้างได้จากปุ่ม '+ New Folder' หรือเมนูที่โปรเจกต์)",
                            "การจัดเรียง: คุณสามารถลากวาง (Drag & Drop) เพื่อจัดลำดับโปรเจกต์ตามความสำคัญได้"
                        ]
                    },
                    {
                        title: "รูปแบบการส่งออก (Exporting Options)",
                        content: [
                            "ส่งออกทั้งรายการ: คลิกไอคอน 'Export' ในแถบเครื่องมือของพื้นที่ทำงาน",
                            "Microsoft Word (.docx): ดาวน์โหลดไฟล์ Word ที่จัดรูปแบบการอ้างอิงให้เสร็จสรรพ พร้อมใช้งานทันที",
                            "PDF Document (.pdf): ส่งออกไฟล์ PDF สำหรับพิมพ์หรือแนบท้ายเล่มวิจัย",
                            "BibTeX/RIS: สำหรับนำไปใช้ต่อในโปรแกรมจัดการบรรณานุกรมอื่นๆ เช่น Zotero, Mendeley หรือ LaTeX"
                        ]
                    }
                ]
            },
            {
                id: 'trouble',
                title: "7. การแก้ปัญหาเบื้องต้น (Troubleshooting)",
                icon: Wrench,
                articles: [
                    {
                        title: "PDF ไม่แสดงผล",
                        content: [
                            "ตรวจสอบการเชื่อมต่ออินเทอร์เน็ต หรือลองรีเฟรชหน้าจอ (Refresh)"
                        ]
                    },
                    {
                        title: "Citation ไม่ตรงรูปแบบ",
                        content: [
                            "ตรวจสอบข้อมูล Metadata ในส่วนของแหล่งอ้างอิงว่ากรอกข้อมูลครบถ้วนหรือไม่"
                        ]
                    },
                    {
                        title: "หาปุ่มไม่เจอ",
                        content: [
                            "ใช้ฟังก์ชัน Search ในหน้า Help เพื่อค้นหาเครื่องมือที่ต้องการ"
                        ]
                    }
                ]
            }
        ],
        contactInfo: {
            title: "ติดต่อเรา (Contact Support)",
            email: "อีเมล: support@scribehub.com",
            chat: "Live Chat: คลิกไอคอนข้อความที่มุมขวาล่างของหน้าจอ (ให้บริการ จันทร์-ศุกร์ 09:00 - 18:00 น.)",
            community: "Community: เข้าร่วมกลุ่ม Facebook ของเราเพื่อแลกเปลี่ยนเทคนิคกับผู้ใช้อื่นๆ"
        }
    }
};

export default function Help() {
    const { auth } = usePage<SharedData>().props;
    const [lang, setLang] = useState<'en' | 'th'>('en');
    const { appearance, updateAppearance } = useAppearance();
    const [searchQuery, setSearchQuery] = useState('');
    const [activeSection, setActiveSection] = useState('all');
    
    const t = translations[lang];

    return (
        <>
            <Head title={t.title}>
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700|overpass:400,600,700,800&display=swap" rel="stylesheet" />
            </Head>

            <div className="min-h-screen bg-scribehub-paper font-overpass text-scribehub-grey antialiased dark:bg-[#0a0a0a] dark:text-gray-300">
                <header className="sticky top-0 z-50 w-full border-b border-scribehub-border bg-white/80 backdrop-blur-md dark:border-gray-800 dark:bg-black/80">
                    <div className="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
                        <div className="flex items-center gap-10">
                            <Link href="/">
                                <AppLogo />
                            </Link>
                            <nav className="hidden items-center gap-8 md:flex">
                                <Link href="/" className="text-sm font-medium hover:text-scribehub-blue transition-colors flex items-center gap-1.5"><ArrowLeft className="h-4 w-4" /> {t.backToHome}</Link>
                            </nav>
                        </div>
                        <div className="flex items-center gap-3">
                            <button 
                                onClick={() => setActiveSection('all')}
                                className="hidden md:flex items-center gap-2 px-4 py-2 text-sm font-bold text-scribehub-blue hover:bg-scribehub-blue/5 rounded-xl transition-colors"
                            >
                                <HelpCircle className="h-4 w-4" />
                                {lang === 'en' ? 'Help Home' : 'หน้าแรกความช่วยเหลือ'}
                            </button>
                            <div className="group relative">
                                <button className="flex h-9 w-9 items-center justify-center rounded-full text-scribehub-grey hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800 transition-colors"><Languages className="h-5 w-5" /></button>
                                <div className="absolute right-0 top-full hidden w-32 origin-top-right rounded-xl border border-gray-100 bg-white py-2 shadow-xl ring-1 ring-black ring-opacity-5 group-hover:block dark:border-gray-800 dark:bg-gray-900 z-[60]">
                                    <button onClick={() => setLang('en')} className={cn("flex w-full items-center justify-between px-4 py-2 text-xs font-bold", lang === 'en' ? "bg-gray-50 text-scribehub-blue dark:bg-gray-800" : "text-gray-700 dark:text-gray-300")}>English {lang === 'en' && <Check className="h-3 w-3" />}</button>
                                    <button onClick={() => setLang('th')} className={cn("flex w-full items-center justify-between px-4 py-2 text-xs font-bold", lang === 'th' ? "bg-gray-50 text-scribehub-blue dark:bg-gray-800" : "text-gray-700 dark:text-gray-300")}>ภาษาไทย {lang === 'th' && <Check className="h-3 w-3" />}</button>
                                </div>
                            </div>
                            <div className="group relative">
                                <button className="flex h-9 w-9 items-center justify-center rounded-full text-scribehub-grey hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800 transition-colors">{appearance === 'dark' ? <Moon className="h-5 w-5 transition-all group-hover:rotate-12" /> : appearance === 'light' ? <Sun className="h-5 w-5 transition-all group-hover:rotate-90" /> : <Monitor className="h-5 w-5" />}</button>
                                <div className="absolute right-0 top-full hidden w-32 origin-top-right rounded-xl border border-gray-100 bg-white py-2 shadow-xl ring-1 ring-black ring-opacity-5 group-hover:block dark:border-gray-800 dark:bg-gray-900 z-[60]">
                                    <button onClick={() => updateAppearance('light')} className={cn("flex w-full items-center justify-between px-4 py-2 text-xs font-bold", appearance === 'light' ? "bg-gray-50 text-scribehub-blue dark:bg-gray-800" : "text-gray-700 dark:text-gray-300")}>Light {appearance === 'light' && <Check className="h-3 w-3" />}</button>
                                    <button onClick={() => updateAppearance('dark')} className={cn("flex w-full items-center justify-between px-4 py-2 text-xs font-bold", appearance === 'dark' ? "bg-gray-50 text-scribehub-blue dark:bg-gray-800" : "text-gray-700 dark:text-gray-300")}>Dark {appearance === 'dark' && <Check className="h-3 w-3" />}</button>
                                    <button onClick={() => updateAppearance('system')} className={cn("flex w-full items-center justify-between px-4 py-2 text-xs font-bold", appearance === 'system' ? "bg-gray-50 text-scribehub-blue dark:bg-gray-800" : "text-gray-700 dark:text-gray-300")}>System {appearance === 'system' && <Check className="h-3 w-3" />}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <main className="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                    {/* Hero Help Search */}
                    <div className="mb-16 text-center animate-in fade-in slide-in-from-bottom-5 duration-1000">
                        <h1 className="text-4xl font-extrabold text-scribehub-blue dark:text-white sm:text-5xl mb-8">{t.howCanWeHelp}</h1>
                        <div className="relative mx-auto max-w-2xl">
                            <Search className="absolute left-6 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" />
                            <input 
                                type="text" 
                                placeholder={t.searchPlaceholder}
                                className="w-full rounded-2xl border-none bg-white p-6 pl-16 text-lg shadow-xl ring-1 ring-gray-100 focus:ring-2 focus:ring-scribehub-blue dark:bg-gray-900 dark:ring-gray-800"
                                value={searchQuery}
                                onChange={(e) => setSearchQuery(e.target.value)}
                            />
                        </div>
                    </div>

                    <div className="flex flex-col gap-12 lg:flex-row">
                        {/* Sidebar Topics */}
                        <aside className="lg:w-1/4">
                            <div className="sticky top-28 space-y-1">
                                <h3 className="mb-6 px-4 text-[10px] font-bold uppercase tracking-widest text-gray-400">{t.popularTopics}</h3>
                                <button 
                                    onClick={() => setActiveSection('all')}
                                    className={cn(
                                        "flex w-full items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all hover:bg-white dark:hover:bg-gray-900 active:scale-95",
                                        activeSection === 'all' ? "bg-white text-scribehub-blue shadow-sm ring-1 ring-gray-100 dark:bg-gray-900 dark:ring-gray-800" : "text-gray-500"
                                    )}
                                >
                                    <BookOpen className={cn("h-4 w-4", activeSection === 'all' ? "text-scribehub-blue" : "text-gray-400")} />
                                    {lang === 'en' ? 'All Guides' : 'คู่มือทั้งหมด'}
                                </button>
                                {t.categories.map((cat: any) => (
                                    <button 
                                        key={cat.id}
                                        onClick={() => {
                                            setActiveSection(cat.id);
                                            window.scrollTo({ top: 0, behavior: 'smooth' });
                                        }}
                                        className={cn(
                                            "flex w-full items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition-all hover:bg-white dark:hover:bg-gray-900 active:scale-95",
                                            activeSection === cat.id ? "bg-white text-scribehub-blue shadow-sm ring-1 ring-gray-100 dark:bg-gray-900 dark:ring-gray-800" : "text-gray-500"
                                        )}
                                    >
                                        <cat.icon className={cn("h-4 w-4", activeSection === cat.id ? "text-scribehub-blue" : "text-gray-400")} />
                                        {cat.title}
                                    </button>
                                ))}
                                
                                <div className="mt-12 pt-8 border-t border-gray-100 dark:border-gray-800">
                                    <h3 className="mb-6 px-4 text-[10px] font-bold uppercase tracking-widest text-gray-400">{t.contactUs}</h3>
                                    <div className="space-y-4 px-4">
                                        <div className="flex items-center gap-3 text-xs text-gray-500 font-medium"><Mail className="h-4 w-4 text-scribehub-blue" /> support@scribehub.com</div>
                                        <div className="flex items-center gap-3 text-xs text-gray-500 font-medium"><Facebook className="h-4 w-4 text-scribehub-blue" /> ScribeHub Community</div>
                                    </div>
                                </div>
                            </div>
                        </aside>

                        {/* Help Content */}
                        <div className="flex-1 space-y-20">
                            {/* Category Cards Grid */}
                            {activeSection === 'all' && (
                                <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    {t.categories.map((cat: any, i: number) => (
                                        <button key={i} onClick={() => setActiveSection(cat.id)} className="group flex flex-col items-start rounded-[2.5rem] border border-gray-100 bg-white p-8 text-left transition-all hover:shadow-xl hover:-translate-y-1 dark:border-gray-800 dark:bg-gray-900 animate-in fade-in slide-in-from-bottom-5" style={{ animationDelay: `${i * 100}ms` }}>
                                            <div className="mb-6 rounded-2xl bg-scribehub-blue/5 p-4 text-scribehub-blue transition-transform group-hover:scale-110 group-hover:rotate-3 shadow-sm border border-scribehub-blue/10"><cat.icon className="h-8 w-8" /></div>
                                            <h3 className="text-xl font-bold dark:text-white mb-2">{cat.title}</h3>
                                            <p className="text-sm text-gray-400 leading-relaxed font-medium mb-8">{cat.desc}</p>
                                            <div className="mt-auto flex items-center gap-2 text-xs font-bold text-scribehub-blue group-hover:gap-4 transition-all">View articles <MoveRight className="h-4 w-4" /></div>
                                        </button>
                                    ))}
                                </div>
                            )}

                            {/* Detailed Sections */}
                            {t.sections
                                .filter(s => {
                                    if (searchQuery) {
                                        const query = searchQuery.toLowerCase();
                                        return s.title.toLowerCase().includes(query) || 
                                               s.articles.some(a => a.title.toLowerCase().includes(query) || a.content.some(p => p.toLowerCase().includes(query)));
                                    }
                                    return activeSection === 'all' || s.id === activeSection;
                                })
                                .map((section: any, idx: number) => (
                                <section key={section.id} id={section.id} className="animate-in fade-in slide-in-from-right-5 duration-700">
                                    <div className="mb-10 flex items-center gap-4">
                                        <div className="flex h-12 w-12 items-center justify-center rounded-2xl bg-scribehub-blue text-white shadow-lg"><section.icon className="h-6 w-6" /></div>
                                        <h2 className="text-3xl font-extrabold text-scribehub-blue dark:text-white">{section.title}</h2>
                                    </div>
                                    <div className="space-y-10">
                                        {section.articles.map((article: any, i: number) => (
                                            <div key={i} className="rounded-[2rem] border border-gray-100 bg-white p-8 shadow-sm dark:border-gray-800 dark:bg-gray-900 transition-all hover:shadow-md">
                                                <h3 className="text-xl font-bold dark:text-white mb-6 border-l-4 border-scribehub-mint pl-4">{article.title}</h3>
                                                <ul className="space-y-4">
                                                    {article.content.map((point: string, pIdx: number) => (
                                                        <li key={pIdx} className="flex items-start gap-4 text-sm font-medium leading-relaxed text-scribehub-grey/80 dark:text-gray-400">
                                                            <div className="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-scribehub-blue/30" />
                                                            {point}
                                                        </li>
                                                    ))}
                                                </ul>
                                            </div>
                                        ))}
                                    </div>
                                </section>
                            ))}

                            {/* Contact Support Footer Card */}
                            <div className="rounded-[3rem] bg-gradient-to-br from-scribehub-blue to-blue-900 p-12 text-white shadow-2xl relative overflow-hidden group">
                                <div className="absolute top-0 right-0 p-12 opacity-10 transition-transform group-hover:scale-125 duration-1000"><HelpCircle className="h-48 w-48" /></div>
                                <div className="relative z-10 max-w-2xl">
                                    <h2 className="text-3xl font-extrabold mb-4">{t.contactInfo.title}</h2>
                                    <p className="text-blue-100 mb-8 font-medium leading-relaxed">If this guide doesn't answer your questions, our support team is happy to help you personally.</p>
                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                                        <div className="space-y-4">
                                            <div className="flex items-center gap-4 group/item">
                                                <div className="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 group-hover/item:bg-white/20 transition-colors"><Mail className="h-5 w-5" /></div>
                                                <div className="text-sm font-bold">{t.contactInfo.email}</div>
                                            </div>
                                            <div className="flex items-center gap-4 group/item">
                                                <div className="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 group-hover/item:bg-white/20 transition-colors"><MessageCircle className="h-5 w-5" /></div>
                                                <div className="text-sm font-bold leading-tight">{t.contactInfo.chat}</div>
                                            </div>
                                        </div>
                                        <div className="space-y-4">
                                            <div className="flex items-center gap-4 group/item">
                                                <div className="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 group-hover/item:bg-white/20 transition-colors"><Facebook className="h-5 w-5" /></div>
                                                <div className="text-sm font-bold">{t.contactInfo.community}</div>
                                            </div>
                                            <button className="w-full rounded-2xl bg-white py-4 text-sm font-bold text-scribehub-blue shadow-lg hover:bg-gray-50 transition-all active:scale-95">{t.getInTouch}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>

                <footer className="mt-32 border-t border-gray-100 bg-white py-12 dark:border-gray-800 dark:bg-black">
                    <div className="mx-auto max-w-7xl px-4 text-center sm:px-6 lg:px-8">
                        <AppLogo />
                        <p className="mt-8 text-[10px] font-bold tracking-widest text-gray-400 uppercase">ScribeHub Research Operating System - All rights reserved</p>
                    </div>
                </footer>
            </div>
        </>
    );
}
