import { dashboard, login } from '@/routes';
import type { SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';
import { MoveRight, Play, Star, Languages, Moon, Sun, Monitor, Check, Zap, BookOpen, Brain, Layout, Library, CreditCard, Users, HelpCircle, ChevronDown, CheckCircle2, ShieldCheck, Smartphone, Cloud, Globe, Sparkles, Files, Layers, Share2, Info, MessagesSquare, Heart, Quote } from 'lucide-react';
import AppLogo from '@/components/app-logo';
import AppLogoIcon from '@/components/app-logo-icon';
import { useState } from 'react';
import { useAppearance } from '@/hooks/use-appearance';
import { cn } from '@/lib/utils';

const translations = {
    en: {
        title: "ScribeHub - Research Operating System",
        heroBadge: "✨ EARLY ADOPTER DISCOUNT - 50% OFF",
        heroTitla1: "Know exactly what",
        heroTitla2: "you're reading for",
        heroSubtitle: "ScribeHub is an all-in-one Research Operating System that redefines citation tools into an 'Intelligent Desk' for writers and researchers.",
        startResearch: "Start Your Research",
        viewDemo: "View Demo",
        noCredit: "No credit card required. Start for free today.",
        features: "Features",
        pricing: "Pricing",
        faq: "FAQ",
        help: "Help",
        signIn: "Sign In",
        testimonials: "What our researchers are saying",
        testimonialSub: "Join thousands of students and researchers worldwide.",
        footerTagline: "Research Operating System - The only platform where researchers do more than just collect data.",
        links: "Links",
        legal: "Legal",
        social: "Social",
        dashboard: "Dashboard",
        terms: "Terms of service",
        privacy: "Privacy policy",
        featuresTagline: "Everything you need to",
        featuresTitle: "master your research",
        featuresSub: "Transform the scattered data into a powerful academic workflow.",
        f1Title: "Advanced Style Library",
        f1Desc: "Comprehensive support for global and local citation standards (APA 7, MLA 9, and Thai Universities).",
        f2Title: "Smart Enhancements",
        f2Desc: "Filling the gaps for a seamless workflow.",
        f3Title: "Split-View Editor",
        f3Desc: "Read and write in one place. No more tab switching.",
        f3Item1: "Side-by-Side Workflow",
        f3Item2: "Drag-and-Drop Citation",
        f3Item3: "Magic Highlight to Note",
        f4Title: "AI Writing Assistant",
        f4Desc: "Powered by Gemini 2.5 Flash for intelligent synthesis.",
        f4Item1: "Contextual Source Suggestions",
        f4Item2: "Academic Paraphrasing",
        f4Item3: "Multi-PDF Research Summarizer",
        f5Title: "Knowledge Visualization",
        f5Desc: "See the connections between your sources.",
        f6Title: "Seamless Collaboration",
        f6Desc: "Work with your team in real-time.",
        f7Title: "Anywhere Access",
        f7Desc: "Your entire research library, synced across all your devices.",
        f8Title: "Privacy First",
        f8Desc: "Your headers and insights are encrypted and private by default.",
        pricingTitle: "Pricing & Plans",
        pricingSub: "Choose the right plan to elevate your research, from classroom reports to doctoral dissertations.",
        comparePlans: "Compare Features",
        starterName: "Starter (Free)",
        starterPrice: "0",
        proName: "Pro Researcher",
        proPrice: "149",
        instName: "Institutional",
        instPrice: "Contact Sales",
        monthly: "THB / month",
        getStarted: "Get Started",
        popular: "Popular",
        faqTitle: "Frequently Asked Questions",
        faqIntro: "Welcome to ScribeHub Help Center. We've gathered frequently asked questions to help you get started with our Research Operating System quickly.",
        faqContact: "If you have more questions not listed here, feel free to contact us at support@scribehub.com",
        faqCategories: [
            {
                title: "General",
                icon: "Info",
                items: [
                    { q: "What is ScribeHub and how is it different from BabyBib?", a: "ScribeHub is an evolution of BabyBib, transforming from a simple bibliography tool into a full Research Operating System with an integrated editor. This allows you to read PDFs while writing and managing citations simultaneously in a single view." },
                    { q: "Why should I switch from Google Docs to ScribeHub?", a: "While Google Docs is user-friendly, ScribeHub is purpose-built for \"Academic Research.\" We feature automatic citation extraction from DOI, a Split-View system for reading PDFs while writing, and AI that helps summarize content and adjust tones for formal academic writing—features that aren't integrated as a system in Google Docs." }
                ]
            },
            {
                title: "Pricing & Plans",
                icon: "CreditCard",
                items: [
                    { q: "Can I use ScribeHub for free?", a: "Absolutely! Our Starter plan is free forever. You can manage unlimited citations and use the basic editor immediately." },
                    { q: "What is the ScribeHub for Students program?", a: "We support education! By signing up with a university email (.ac.th or .edu), you'll receive a 3-month free trial of the Pro Researcher plan to assist with your research or thesis." },
                    { q: "How can I pay for the Pro membership?", a: "We support various convenient payment methods, including PromptPay, Credit/Debit cards, and Thai Mobile Banking." }
                ]
            },
            {
                title: "Features & Usage",
                icon: "Zap",
                items: [
                    { q: "How does the Split-View Editor work?", a: "You can upload a research PDF on the left side of the screen and type your manuscript on the right. You can highlight text in the PDF to save as reference notes and click \"Insert\" to add them to your article instantly without switching windows." },
                    { q: "What citation styles does ScribeHub support?", a: "We support standard formats like APA (7th ed.), MLA, IEEE, and Chicago in the free plan, and over 10,000 styles worldwide in the Pro plan—including specific Thai university formats that we are continuously adding." },
                    { q: "Can I migrate data from Zotero or Mendeley?", a: "Yes, our system supports importing .bib and .ris files, which are the standard formats for most leading reference management software." }
                ]
            },
            {
                title: "Security & Privacy",
                icon: "ShieldCheck",
                items: [
                    { q: "Is my research data safe?", a: "We prioritize your privacy. Your files and research data are stored securely on encrypted cloud servers. We have a strict policy: we DO NOT publish your research or use it to train AI without your permission." },
                    { q: "Who owns the copyright for the writing in ScribeHub?", a: "You own 100% of the copyright for your writing and research. ScribeHub is simply a tool to facilitate formatting and organization." }
                ]
            },
            {
                title: "Support",
                icon: "MessagesSquare",
                items: [
                    { q: "What if the citation style I need isn't in the system?", a: "If you are using the Pro or Institutional plan, you can request a custom style through the Ticket system in the Settings page." },
                    { q: "Can I work offline?", a: "Currently, ScribeHub is web-based to ensure real-time syncing and AI integration. We are exploring a desktop application for future offline support." },
                    { q: "Is there a mobile app?", a: "You can access your library on mobile via any browser. A dedicated mobile app for quick reading and note-taking is currently in development." }
                ]
            }
        ],
        fMain: "Core Features",
        fManagement: "Citation Management",
        fEditor: "Smart Editor",
        fAI: "AI Assistant",
        fStorage: "Storage",
        fProjects: "Projects",
        fStarterMgmt: "Basic (APA, MLA)",
        fProMgmt: "All Styles + Custom",
        fInstMgmt: "All Styles + Univ. Style",
        fStarterEditor: "Standard",
        fProEditor: "Split-View + Highlight",
        fInstEditor: "Split-View + Team Edit",
        fStarterAI: "5 times / month",
        fUnlimited: "Unlimited",
        fInstAI: "Unlimited + Customizable",
        fStarterStorage: "500 MB",
        fProStorage: "20 GB",
        fUnlimitedStorage: "Unlimited",
        fStarterProjects: "Up to 3 projects",
        fProExtra: "Everything in Starter, plus:",
        fInstExtra: "Everything in Pro, plus:",
        earlyDiscount: "🎉 50% discount for first 500 users",
        communityLove: "Community Love",
        convincedTitle: "Still not convinced?",
        convincedSubtitle: "See what users say",
        convincedDesc: "Real feedback from our community. No cherry-picking, just genuine experiences.",
        joinGrowing: "Join our growing community",
        joinCommunity: "Join Our Community",
        ctaTitle: "Ready to take control of your research?",
        ctaSubtitle: "Start writing your masterpiece today.",
        ctaDesc: "Join 1,000+ researchers who write faster and smarter with ScribeHub. Free forever for basic use.",
        ctaPrimary: "Start Writing Free",
        ctaSecondary: "View Demo",
        exploreFeatures: "Explore all features"
    },
    th: {
        title: "ScribeHub - ระบบปฏิบัติการเพื่องานวิจัย",
        heroBadge: "✨ ส่วนลดสำหรับผู้ใช้งานกลุ่มแรก - 50%",
        heroTitla1: "รู้แน่ชัดว่าคุณ",
        heroTitla2: "กำลังอ่านเพื่ออะไร",
        heroSubtitle: "ScribeHub คือระบบปฏิบัติการเพื่องานวิจัย (Research Operating System) แบบครบวงจรที่เปลี่ยนจากเครื่องมือสร้างบรรณานุกรม ให้กลายเป็น 'โต๊ะทำงานอัจฉริยะ' สำหรับนักเขียนและนักวิจัย",
        startResearch: "เริ่มงานวิจัยของคุณ",
        viewDemo: "ดูตัวอย่าง",
        noCredit: "ไม่ต้องใช้บัตรเครดิต เริ่มต้นใช้งานได้ฟรีวันนี้",
        features: "ฟีเจอร์",
        pricing: "ราคา",
        faq: "คำถามที่พบบ่อย",
        help: "ช่วยเหลือ",
        signIn: "ลงชื่อเข้าใช้",
        testimonials: "สิ่งที่นักวิจัยพูดถึงเรา",
        testimonialSub: "ร่วมเป็นส่วนหนึ่งกับนักศึกษาและนักวิจัยนับพันทั่วโลก",
        footerTagline: "ระบบปฏิบัติการเพื่องานวิจัย - แพลตฟอร์มเดียวที่ให้นักวิจัยทำได้มากกว่าแค่เก็บข้อมูล",
        links: "ลิงก์",
        legal: "กฎหมาย",
        social: "โซเชียล",
        dashboard: "แผงควบคุม",
        terms: "ข้อกำหนดการให้บริการ",
        privacy: "นโยบายความเป็นส่วนตัว",
        featuresTagline: "ทุกสิ่งที่คุณต้องการเพื่อ",
        featuresTitle: "จัดการงานวิจัยให้ทรงพลัง",
        featuresSub: "เปลี่ยนข้อมูลที่กระจัดกระจายให้กลายเป็นกระบวนการทำงานวิชาการที่สมบูรณ์แบบ",
        f1Title: "Advanced Style Library",
        f1Desc: "เพิ่มรูปแบบการอ้างอิงให้ครอบคลุม (APA 7, MLA 9, IEEE, Chicago, Harvard และรูปแบบเฉพาะของมหาวิทยาลัยในไทย)",
        f2Title: "ส่วนเสริมอัจฉริยะ",
        f2Desc: "เติมเต็มช่องว่างเพื่อให้ทำงานได้แม่นยำขึ้น",
        f3Title: "Integrated Split-View Editor",
        f3Desc: "อ่านและเขียนในที่เดียว ไม่ต้องสลับแท็บไปมา",
        f3Item1: "Side-by-Side Workflow",
        f3Item2: "ลากแหล่งอ้างอิงมาเขียนได้ทันที",
        f3Item3: "Magic Highlight เปลี่ยนไฮไลท์เป็นโน้ต",
        f4Title: "ผู้ช่วยเขียน AI อัจฉริยะ",
        f4Desc: "ขับเคลื่อนด้วย Gemini 2.5 Flash เพื่อการสรุปและสังเคราะห์",
        f4Item1: "แนะนำแหล่งอ้างอิงที่เกี่ยวข้องขณะพิมพ์",
        f4Item2: "เครื่องมือปรับประโยคภาษาอังกฤษวิชาการ",
        f4Item3: "ระบบสรุปเนื้อหา PDF หลายเล่มพร้อมกัน",
        f5Title: "เห็นภาพรวมความเชื่อมโยง",
        f5Desc: "แสดงความสัมพันธ์ของแหล่งอ้างอิงเป็นแผนผัง",
        f6Title: "ทำงานร่วมกันเป็นทีม",
        f6Desc: "จัดการโปรเจกต์งานวิจัยร่วมกับเพื่อน",
        f7Title: "เข้าถึงได้จากทุกที่",
        f7Desc: "คลังงานวิจัยของคุณจะถูกซิงค์ข้อมูลไปยังทุกอุปกรณ์โดยอัตโนมัติ",
        f8Title: "ความเป็นส่วนตัวสูงสุด",
        f8Desc: "ข้อมูลและข้อคิดเห็นของคุณจะถูกเข้ารหัสและเป็นส่วนตัวเสมอ",
        pricingTitle: "ราคาและแผนการใช้งาน ScribeHub",
        pricingSub: "เลือกแผนที่ใช่เพื่อยกระดับงานวิจัยของคุณ ตั้งแต่การทำรายงานในชั้นเรียนไปจนถึงวิทยานิพนธ์ระดับดุษฎีบัณฑิต",
        comparePlans: "เปรียบเทียบแผนการใช้งาน",
        starterName: "Starter (ฟรี)",
        starterPrice: "0",
        proName: "Pro Researcher",
        proPrice: "149",
        instName: "Institutional",
        instPrice: "ติดต่อฝ่ายขาย",
        monthly: "บาท / เดือน",
        getStarted: "เริ่มเลย",
        popular: "ยอดนิยม",
        faqTitle: "❓ คำถามที่พบบ่อย (Frequently Asked Questions)",
        faqIntro: "ยินดีต้อนรับสู่ศูนย์ช่วยเหลือของ ScribeHub เราได้รวบรวมคำถามที่พบบ่อยเพื่อช่วยให้คุณเริ่มต้นใช้งานระบบปฏิบัติการเพื่องานวิจัยของเราได้อย่างรวดเร็ว",
        faqContact: "หากคุณมีคำถามเพิ่มเติมที่ไม่ได้ระบุไว้ในนี้ สามารถติดต่อเราได้ที่ support@scribehub.com",
        faqCategories: [
            {
                title: "🏗️ ทั่วไป (General)",
                icon: "Info",
                items: [
                    { q: "ScribeHub คืออะไร และแตกต่างจาก BabyBib อย่างไร?", a: "ScribeHub คือการพัฒนาต่อยอดจาก BabyBib โดยเปลี่ยนจากเครื่องมือทำบรรณานุกรมธรรมดา ให้กลายเป็น Research Operating System ที่มี Editor ในตัว ช่วยให้คุณอ่าน PDF ไปพร้อมกับการเขียนและจัดการอ้างอิงได้ในหน้าจอเดียว" },
                    { q: "ทำไมฉันควรเปลี่ยนจาก Google Docs มาใช้ ScribeHub?", a: "แม้ Google Docs จะใช้งานง่าย แต่ ScribeHub ถูกออกแบบมาเพื่อ \"งานวิชาการ\" โดยเฉพาะ เรามีระบบดึงข้อมูลอ้างอิงอัตโนมัติจาก DOI, ระบบ Split-View เพื่ออ่าน PDF ขณะเขียน และ AI ที่ช่วยสรุปเนื้อหาและปรับโทนภาษาให้เป็นทางการ ซึ่ง Google Docs ไม่มีฟีเจอร์เหล่านี้ที่เชื่อมโยงกันอย่างเป็นระบบ" }
                ]
            },
            {
                title: "💰 แผนการใช้งานและราคา (Pricing & Plans)",
                icon: "CreditCard",
                items: [
                    { q: "ฉันสามารถใช้งาน ScribeHub ได้ฟรีหรือไม่?", a: "ได้แน่นอนครับ! แผน Starter ของเราเปิดให้ใช้งานฟรีตลอดไป โดยคุณสามารถจัดการอ้างอิงได้ไม่จำกัดและใช้งาน Editor พื้นฐานได้ทันที" },
                    { q: "โครงการ ScribeHub for Students คืออะไร?", a: "เราสนับสนุนการศึกษาไทย เพียงคุณสมัครสมาชิกด้วยอีเมลของมหาวิทยาลัย (.ac.th หรือ .edu) คุณจะได้รับสิทธิ์ทดลองใช้งานแผน Pro Researcher ฟรีเป็นเวลา 3 เดือนเพื่อช่วยในการทำวิจัยหรือวิทยานิพนธ์" },
                    { q: "ฉันจะจ่ายเงินค่าสมาชิก Pro ได้อย่างไร?", a: "เรารองรับการชำระเงินที่หลากหลายและสะดวกสำหรับคนไทย ทั้ง PromptPay, บัตรเครดิต/เดบิต และการชำระผ่าน Mobile Banking" }
                ]
            },
            {
                title: "🛠️ ฟีเจอร์และการใช้งาน (Features & Usage)",
                icon: "Zap",
                items: [
                    { q: "ระบบ Split-View Editor ทำงานอย่างไร?", a: "คุณสามารถอัปโหลดไฟล์ PDF งานวิจัยไว้ที่ฝั่งซ้ายของหน้าจอ และพิมพ์งานเขียนของคุณที่ฝั่งขวา คุณสามารถไฮไลท์ข้อความใน PDF เพื่อบันทึกเป็นโน้ตอ้างอิง และกดแทรก (Insert) เข้าสู่บทความของคุณได้ทันทีโดยไม่ต้องสลับหน้าต่าง" },
                    { q: "ScribeHub รองรับรูปแบบการอ้างอิง (Citation Styles) อะไรบ้าง?", a: "เรารองรับรูปแบบมาตรฐานอย่าง APA (7th ed.), MLA, IEEE, Chicago ในแผนฟรี และมากกว่า 10,000 รูปแบบทั่วโลกในแผน Pro รวมถึงรูปแบบเฉพาะของมหาวิทยาลัยในไทยที่กำลังทยอยเพิ่มเข้ามา" },
                    { q: "ฉันสามารถย้ายข้อมูลจาก Zotero หรือ Mendeley มาได้ไหม?", a: "ได้ครับ ระบบของเรารองรับการ Import ไฟล์นามสกุล .bib และ .ris ซึ่งเป็นมาตรฐานของโปรแกรมจัดการอ้างอิงชั้นนำส่วนใหญ่" }
                ]
            },
            {
                title: "🔒 ความปลอดภัยและความเป็นส่วนตัว (Security & Privacy)",
                icon: "ShieldCheck",
                items: [
                    { q: "ข้อมูลงานวิจัยของฉันจะปลอดภัยหรือไม่?", a: "เราให้ความสำคัญกับความเป็นส่วนตัวสูงสุด ข้อมูลและไฟล์ PDF ของคุณจะถูกเก็บรักษาอย่างปลอดภัยบนคลาวด์ที่มีการเข้ารหัส และเรา ไม่มีนโยบาย นำเนื้อหางานวิจัยของคุณไปเผยแพร่หรือใช้ฝึกสอน AI โดยไม่ได้รับอนุญาต" },
                    { q: "ใครเป็นเจ้าของลิขสิทธิ์งานเขียนใน ScribeHub?", a: "คุณเป็นเจ้าของลิขสิทธิ์ในงานเขียนและงานวิจัยของคุณ 100% ScribeHub เป็นเพียงเครื่องมือที่ช่วยอำนวยความสะดวกในการจัดรูปแบบและรวบรวมข้อมูลเท่านั้น" }
                ]
            },
            {
                title: "📞 การสนับสนุน (Support)",
                icon: "MessagesSquare",
                items: [
                    { q: "หากรูปแบบการอ้างอิงที่ฉันต้องการไม่มีในระบบ ต้องทำอย่างไร?", a: "หากคุณใช้งานแผน Pro หรือ Institutional คุณสามารถแจ้งขอเพิ่มรูปแบบการอ้างอิงเฉพาะ (Request Custom Style) มายังทีมงานได้ผ่านระบบ Ticket ในหน้า Settings ครับ" },
                    { q: "ระบบรองรับการทำงานแบบ Offline หรือไม่?", a: "ปัจจุบัน ScribeHub เป็นระบบ Web-based เพื่อให้มั่นใจในการซิงค์ข้อมูลแบบ Real-time และการใช้งาน AI เรากำลังพัฒนา Desktop Application เพื่อรองรับการทำงานแบบ Offline ในอนาคต" },
                    { q: "มีแแอปพลิเคชันบนมือถือหรือไม่?", a: "คุณสามารถเข้าถึงคลังข้อมูลผ่านหน้าเว็บเบราว์เซอร์บนมือถือได้ทันที และเรากำลังพัฒนา Official App สำหรับการอ่านและจดบันทึกให้สะดวกยิ่งขึ้น" }
                ]
            }
        ],
        fMain: "ฟีเจอร์หลัก",
        fManagement: "การจัดการอ้างอิง",
        fEditor: "Smart Editor",
        fAI: "AI Assistant",
        fStorage: "พื้นที่เก็บข้อมูล",
        fProjects: "จำนวนโปรเจกต์",
        fStarterMgmt: "พื้นฐาน (APA, MLA)",
        fProMgmt: "ทุกสไตล์ + Custom",
        fInstMgmt: "ทุกสไตล์ + สไตล์สถาบัน",
        fStarterEditor: "มาตรฐาน",
        fProEditor: "Split-View + Highlight",
        fInstEditor: "Split-View + Team Edit",
        fStarterAI: "5 ครั้ง / เดือน",
        fUnlimited: "ไม่จำกัด",
        fInstAI: "ไม่จำกัด + ปรับแต่งได้",
        fStarterStorage: "500 MB",
        fProStorage: "20 GB",
        fUnlimitedStorage: "ไม่จำกัด",
        fStarterProjects: "สูงสุด 3 โปรเจกต์",
        fProExtra: "Everything in Starter และเพิ่มเติม:",
        fInstExtra: "Everything in Pro และเพิ่มเติม:",
        earlyDiscount: "🎉 ส่วนลด 50% สำหรับผู้ใช้งาน 500 ท่านแรก",
        communityLove: "Community Love",
        convincedTitle: "ยังไม่แน่ใจใช่ไหม?",
        convincedSubtitle: "ลองดูคำยืนยันจากผู้ใช้งานจริง",
        convincedDesc: "เสียงตอบรับจากคอมมูนิตี้ของเรา ของจริง ไม่มีปรุงแต่ง เพื่อประสบการณ์ที่ดีที่สุดของคุณ",
        joinGrowing: "ร่วมเป็นส่วนหนึ่งของคอมมูนิตี้ที่กำลังเติบโต",
        joinCommunity: "เข้าร่วมคอมมูนิตี้ของเรา",
        ctaTitle: "พร้อมควบคุมกระบวนการวิจัยของคุณหรือยัง?",
        ctaSubtitle: "เริ่มสร้างสรรค์ผลงานชิ้นเอกของคุณได้ตั้งแต่วันนี้",
        ctaDesc: "เข้าร่วมกับนักวิจัยกว่า 1,000 ท่านที่เขียนงานได้เร็วและเป็นระบบมากขึ้นด้วย ScribeHub ใช้งานพื้นฐานฟรีตลอดไป",
        ctaPrimary: "เริ่มต้นใช้งานฟรี",
        ctaSecondary: "ดูตัวอย่าง",
        exploreFeatures: "ดูฟีเจอร์ทั้งหมด"
    }
};

const citationStyles = [
    { name: "APA 7th Edition", color: "bg-blue-500" },
    { name: "MLA 9th Edition", color: "bg-purple-500" },
    { name: "IEEE Standard", color: "bg-red-500" },
    { name: "Chicago Style", color: "bg-amber-500" },
    { name: "Harvard (Global)", color: "bg-emerald-500" },
    { name: "Vancouver System", color: "bg-sky-500" },
    { name: "ม.มหิดล (MU Style)", color: "bg-indigo-500" },
    { name: "จุฬาลงกรณ์ (CU Style)", color: "bg-pink-500" },
    { name: "ม.เกษตรฯ (KU Style)", color: "bg-green-500" },
    { name: "ม.ธรรมศาสตร์ (TU Style)", color: "bg-orange-500" },
    { name: "ม.ขอนแก่น (KKU Style)", color: "bg-cyan-500" },
    { name: "ม.เชียงใหม่ (CMU Style)", color: "bg-violet-500" },
];

const communityComments = [
    {
        user: { name: "anuchit_p", handle: "@aj_nu", date: "Jan 12, 2024", color: "bg-blue-500" },
        text: "ScribeHub is an absolute lifesaver for my thesis. The Split-View editor alone saved me hours of switching between tabs. It just works. 5⭐"
    },
    {
        user: { name: "sarah.dev", handle: "@sarahj", date: "Dec 3, 2023", color: "bg-purple-500" },
        text: "I love the clean interface. It's so much more intuitive than Zotero for beginners. The AI paraphraser is actually useful for academic writing."
    },
    {
        user: { name: "PhD_Candidate", handle: "@researcher_k", date: "Feb 1, 2024", color: "bg-emerald-500" },
        text: "Finally, a tool that understands Thai university citation styles! No more fixing bibliography manually in Word. Thank you!"
    },
    {
        user: { name: "mike_tech", handle: "@mike_t", date: "Jan 25, 2024", color: "bg-orange-500" },
        text: "The DOI import feature is fast. I was halfway through my paper when I switched to ScribeHub, and migrating my RIS file was seamless."
    },
    {
        user: { name: "Librarian_Ann", handle: "@ann_lib", date: "Nov 21, 2023", color: "bg-rose-500" },
        text: "I recommend this to all my students. The way it handles citations and notes side-by-side encourages better research habits."
    },
    {
        user: { name: "James_Scholar", handle: "@j_scholar", date: "Jan 5, 2024", color: "bg-indigo-500" },
        text: "The AI summary feature is a gem. It helps me scan through 20+ PDFs in minutes to find the core arguments I need for my literature review."
    },
    {
        user: { name: "Patt_W", handle: "@patt_write", date: "Feb 10, 2024", color: "bg-amber-500" },
        text: "Love the real-time collaboration. My co-authors and I can now manage the shared bibliography without sending files back and forth."
    },
    {
        user: { name: "Doctor_R", handle: "@dr_research", date: "Dec 15, 2023", color: "bg-cyan-500" },
        text: "Seamless sync between my iPad and laptop. I can read and highlight on the go, then sit down and write with everything ready."
    }
];

export default function Welcome() {
    const { auth } = usePage<SharedData>().props;
    const [lang, setLang] = useState<'en' | 'th'>('en');
    const { appearance, updateAppearance } = useAppearance();
    const [openFaq, setOpenFaq] = useState<string | null>("0-0");
    
    const t = translations[lang];

    const pricingPlans = [
        {
            name: t.starterName,
            price: t.starterPrice,
            desc: lang === 'en' ? "Ideal for students who need simple and reliable bibliography tools." : "เหมาะสำหรับนักศึกษาที่ต้องการเครื่องมือช่วยทำบรรณานุกรมที่ใช้งานง่ายและแม่นยำ",
            features: lang === 'en' ? ["Manual & Auto Citation: Unlimited", "Basic Style Library: (APA 7th, MLA, IEEE, Chicago)", "Web Editor: Basic writing system", "Reference Import: DOI/ISBN", "Storage: 500 MB PDF files"] : ["Manual & Auto Citation: สร้างการอ้างอิงได้ไม่จำกัด", "Basic Style Library: รองรับรูปแบบมาตรฐาน", "Web Editor: ระบบพิมพ์งานพื้นฐาน", "Reference Import: ดึงข้อมูลจาก DOI/ISBN", "Storage: พื้นที่เก็บไฟล์ PDF 500 MB"],
            icon: BookOpen,
            highlight: false,
            extra: null
        },
        {
            name: t.proName,
            price: t.proPrice,
            desc: lang === 'en' ? "For full-time researchers who want AI power to level up their writing." : "สำหรับนักวิจัยตัวจริงที่ต้องการพลังของ AI มาช่วยยกระดับงานเขียน",
            features: lang === 'en' ? ["Integrated Split-View Editor", "Unlimited AI Power", "Magic Highlight: Auto Note", "Advanced Style Library: 10,000+ styles", "Literature Mapping: Graph Visualization", "Priority Support"] : ["Integrated Split-View Editor อย่างเต็มรูปแบบ", "Unlimited AI Power (Summarizer, Paraphraser)", "Magic Highlight: แปลงไฮไลท์เป็นโน้ตอัตโนมัติ", "Advanced Style Library: 10,000+ สไตล์", "Literature Mapping: แผนผังความเชื่อมโยง", "Priority Support: การช่วยเหลือพิเศษ"],
            icon: Zap,
            highlight: true,
            extra: t.fProExtra
        },
        {
            name: t.instName,
            price: t.instPrice,
            desc: lang === 'en' ? "Designed for universities and labs working in collaborative teams." : "สำหรับมหาวิทยาลัยและห้องแล็บวิจัยที่ทำงานเป็นทีม",
            features: lang === 'en' ? ["Team Knowledge Base", "Real-time Collaboration", "Custom University Style Creator", "Admin Dashboard & Stats", "SSO Integration", "Plagiarism Checker Integration"] : ["Team Knowledge Base: คลังงานวิจัยกลาง", "Real-time Collaboration: แก้ไขเอกสารพร้อมกันทั้งทีม", "Custom Style Creator: สร้างรูปแบบอ้างอิงเฉพาะสถาบัน", "Admin Dashboard: ระบบจัดการสมาชิก", "SSO Integration: ล็อกอินผ่านระบบสถาบัน", "Plagiarism Integration: ระบบตรวจสอบการคัดลอก"],
            icon: Users,
            highlight: false,
            extra: t.fInstExtra
        }
    ];

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
                            <AppLogo />
                            <nav className="hidden items-center gap-8 md:flex">
                                <a href="#features" className="text-sm font-medium hover:text-scribehub-blue transition-colors">{t.features}</a>
                                <a href="#pricing" className="text-sm font-medium hover:text-scribehub-blue transition-colors">{t.pricing}</a>
                                <a href="#faq" className="text-sm font-medium hover:text-scribehub-blue transition-colors">{t.faq}</a>
                                <Link href="/help" className="text-sm font-medium hover:text-scribehub-blue transition-colors">{t.help}</Link>
                            </nav>
                        </div>
                        <div className="flex items-center gap-3">
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
                            <div className="h-6 w-px bg-gray-200 dark:bg-gray-800 mx-1"></div>
                            {auth.user ? (
                                <Link href={dashboard()} className="group inline-flex items-center justify-center rounded-full bg-scribehub-blue px-6 py-2.5 text-sm font-bold text-white transition-all hover:bg-opacity-90 active:scale-95 shadow-md hover:shadow-lg">{t.dashboard} <MoveRight className="ml-2 h-4 w-4 transition-transform group-hover:translate-x-1" /></Link>
                            ) : (
                                <Link href={login()} className="group inline-flex items-center justify-center rounded-full bg-scribehub-blue px-6 py-2.5 text-sm font-bold text-white transition-all hover:bg-opacity-90 active:scale-95 shadow-md hover:shadow-lg">{t.signIn} <MoveRight className="ml-2 h-4 w-4 transition-transform group-hover:translate-x-1" /></Link>
                            )}
                        </div>
                    </div>
                </header>

                <main>
                    {/* Hero Section */}
                    <section className="relative overflow-hidden pt-20 pb-12 text-center">
                        <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                            <div className="mb-8 inline-flex items-center rounded-full bg-scribehub-mint/20 px-4 py-1.5 text-[10px] font-bold tracking-wider text-scribehub-blue ring-1 ring-scribehub-mint/30 animate-in fade-in slide-in-from-bottom-3 duration-1000">{t.heroBadge}</div>
                            <h1 className="mb-6 max-w-4xl mx-auto text-5xl font-extrabold tracking-tight text-scribehub-blue sm:text-6xl lg:text-7xl dark:text-white animate-in fade-in slide-in-from-bottom-4 duration-1000 delay-75">
                                {t.heroTitla1} <br />
                                <span className="relative"><span className="relative z-10 px-2 italic">{t.heroTitla2}</span><span className="absolute bottom-1 left-0 -z-0 h-4 w-full bg-scribehub-mint/40 lg:h-6 transition-all duration-700 hover:h-8"></span></span>
                            </h1>
                            <p className="mb-10 max-w-2xl mx-auto text-lg leading-relaxed text-scribehub-grey/80 dark:text-gray-400 animate-in fade-in slide-in-from-bottom-5 duration-1000 delay-150">{t.heroSubtitle}</p>
                            <div className="flex flex-col items-center gap-4 sm:flex-row justify-center animate-in fade-in slide-in-from-bottom-6 duration-1000 delay-300">
                                <Link href={login()} className="group inline-flex items-center justify-center rounded-full bg-scribehub-blue px-8 py-4 text-base font-bold text-white shadow-lg transition-all hover:scale-105 hover:bg-opacity-95">{t.startResearch} <MoveRight className="ml-2 h-5 w-5 transition-transform group-hover:translate-x-1" /></Link>
                                <button className="inline-flex items-center justify-center rounded-full bg-white px-8 py-4 text-base font-bold text-scribehub-blue shadow-sm ring-1 ring-scribehub-border transition-all hover:bg-gray-50 dark:bg-gray-900 dark:text-white dark:ring-gray-800 hover:shadow-md"><Play className="mr-2 h-5 w-5 fill-current" /> {t.viewDemo}</button>
                            </div>
                            <p className="mt-6 text-[10px] font-bold text-scribehub-grey/60 dark:text-gray-500 animate-in fade-in duration-1000 delay-500">{t.noCredit}</p>
                        </div>
                    </section>

                    {/* Testimonials Section */}
                    <section className="bg-white/50 py-12 dark:bg-black/20">
                        <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                            <div className="flex flex-col items-center text-center">
                                <div className="flex gap-1 mb-4">{[...Array(5)].map((_, i) => <Star key={i} className="h-4 w-4 fill-amber-400 text-amber-400" />)}</div>
                                <h2 className="text-2xl font-bold text-scribehub-blue dark:text-white">{t.testimonials}</h2>
                            </div>
                            <div className="mt-12 grid grid-cols-1 gap-6 md:grid-cols-3">
                                {[{ q: "ช่วยให้การทำ Research Workflow ของฉันง่ายขึ้นมาก ScribeHub คือตัวจบจริงๆ", u: "Doctoral Student", s: "Mahidol University" }, { q: "ชอบฟีเจอร์ Citation Intelligence มาก ช่วยตรวจสอบความถูกต้องแบบ Real-time", u: "Academic Writer", s: "ScribeHub Early User" }, { q: "The Smart Workspace is a game changer. It bridges reading and writing seamlessly.", u: "Researcher", s: "International Institute" }].map((item, idx) => (
                                    <div key={idx} className="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900 transition-all hover:shadow-md hover:-translate-y-1">
                                        <p className="text-sm italic text-scribehub-grey/80 dark:text-gray-300 leading-relaxed group-hover:text-scribehub-blue transition-colors">"{item.q}"</p>
                                        <div className="mt-4 border-t pt-4 text-[10px]"><span className="font-extrabold text-scribehub-blue">{item.u}</span> • <span className="text-gray-400 font-medium">{item.s}</span></div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </section>

                    {/* Features Bento Grid Section */}
                    <section id="features" className="bg-scribehub-paper py-24 dark:bg-[#050505]">
                        <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                            <div className="mb-20 text-center">
                                <h2 className="text-4xl font-extrabold text-scribehub-blue dark:text-white sm:text-5xl">{t.featuresTagline} <br /><span className="relative inline-block mt-2"><span className="relative z-10">{t.featuresTitle}</span><span className="absolute bottom-1 left-0 -z-0 h-3 w-full bg-scribehub-mint/40 transition-all duration-700"></span></span></h2>
                                <p className="mx-auto mt-6 max-w-2xl text-lg text-scribehub-grey/70 dark:text-gray-400 font-medium leading-relaxed">{t.featuresSub}</p>
                            </div>

                            <div className="grid grid-cols-1 gap-6 md:grid-cols-6 md:grid-rows-2">
                                <div className="group md:col-span-4 md:row-span-1 flex flex-col overflow-hidden rounded-3xl border border-scribehub-border bg-white shadow-sm transition-all duration-500 hover:shadow-xl hover:-translate-y-1 dark:border-gray-800 dark:bg-gray-900 animate-in fade-in slide-in-from-left-10 duration-1000">
                                    <div className="p-8 pb-0">
                                        <div className="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-scribehub-blue text-white shadow-sm transition-transform group-hover:scale-110 group-hover:rotate-6"><Layout className="h-5 w-5" /></div>
                                        <h3 className="text-2xl font-bold text-scribehub-blue dark:text-white">{t.f3Title}</h3>
                                        <p className="mt-2 text-sm text-scribehub-grey/70 dark:text-gray-400 font-medium leading-relaxed">{t.f3Desc}</p>
                                    </div>
                                    <div className="mt-6 flex flex-1 items-end overflow-hidden px-8">
                                        <div className="flex h-32 w-full gap-4 rounded-t-2xl border-x border-t border-scribehub-border bg-gray-50/50 p-4 dark:border-gray-800 dark:bg-gray-800/30 transition-transform duration-700 group-hover:bg-gray-100/80">
                                            <div className="w-1/2 rounded-lg border bg-white p-2 dark:bg-gray-800 group-hover:translate-x-1 transition-transform duration-500 shadow-sm"><div className="h-2 w-full rounded bg-gray-100 dark:bg-gray-700 mb-2 animate-pulse"></div><div className="h-2 w-3/4 rounded bg-gray-100 dark:bg-gray-700"></div></div>
                                            <div className="w-1/2 rounded-lg border border-scribehub-blue/20 bg-blue-50/30 p-2 group-hover:-translate-x-1 transition-transform duration-500"><CheckCircle2 className="h-4 w-4 text-scribehub-blue mb-2 animate-bounce" /><div className="h-1 w-full rounded bg-scribehub-blue/10"></div></div>
                                        </div>
                                    </div>
                                </div>
                                <div className="group md:col-span-2 md:row-span-1 flex flex-col overflow-hidden rounded-3xl border border-scribehub-border bg-white shadow-sm transition-all duration-500 hover:shadow-xl hover:-translate-y-1 dark:border-gray-800 dark:bg-gray-900 animate-in fade-in slide-in-from-right-10 duration-1000 delay-100">
                                    <div className="p-8">
                                        <div className="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-scribehub-mint text-scribehub-blue transition-transform group-hover:scale-110 group-hover:rotate-12 shadow-sm"><Sparkles className="h-5 w-5" /></div>
                                        <h3 className="text-xl font-bold text-scribehub-blue dark:text-white">{t.f4Title}</h3>
                                        <p className="mt-2 text-sm text-scribehub-grey/70 dark:text-gray-400 font-medium leading-relaxed">{t.f4Desc}</p>
                                        <ul className="mt-6 space-y-2">
                                            {[t.f4Item1, t.f4Item2, t.f4Item3].map((item, i) => (
                                                <li key={i} className="flex items-center gap-2 text-[10px] font-extrabold dark:text-gray-300 transition-transform group-hover:translate-x-1"><div className="h-1.5 w-1.5 rounded-full bg-scribehub-mint shadow-sm"></div>{item}</li>
                                            ))}
                                        </ul>
                                    </div>
                                </div>
                                <div className="group md:col-span-2 md:row-span-1 flex flex-col overflow-hidden rounded-3xl border border-scribehub-border bg-white shadow-sm transition-all duration-500 hover:shadow-xl hover:-translate-y-1 dark:border-gray-800 dark:bg-gray-900 animate-in fade-in slide-in-from-bottom-10 duration-1000 delay-200">
                                    <div className="p-8">
                                        <div className="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-gray-100 text-scribehub-blue dark:bg-gray-800 transition-transform group-hover:scale-110 group-hover:-rotate-12 shadow-sm"><Zap className="h-5 w-5" /></div>
                                        <h3 className="text-lg font-bold text-scribehub-blue dark:text-white">{t.f2Title}</h3>
                                        <p className="mt-1 text-xs text-scribehub-grey/70 dark:text-gray-400 font-medium">{t.f2Desc}</p>
                                        <div className="mt-6 space-y-2">
                                            <div className="flex items-center gap-3 rounded-xl border border-gray-100 bg-gray-50/50 p-3 text-[10px] font-extrabold transition-all hover:bg-white hover:shadow-sm dark:border-gray-800 dark:bg-white/5"><BookOpen className="h-4 w-4 text-scribehub-blue" /> DOI/ISBN Import</div>
                                            <div className="flex items-center gap-3 rounded-xl border border-green-100 bg-green-50/50 p-3 text-[10px] font-extrabold text-green-700 transition-all hover:bg-white hover:shadow-sm dark:bg-green-900/10 dark:border-green-900/20"><Check className="h-4 w-4" /> Real-time Verified</div>
                                        </div>
                                    </div>
                                </div>
                                <div className="group md:col-span-2 md:row-span-1 flex flex-col overflow-hidden rounded-3xl border border-scribehub-border bg-white shadow-sm transition-all duration-500 hover:shadow-xl hover:-translate-y-1 dark:border-gray-800 dark:bg-gray-900 animate-in fade-in slide-in-from-bottom-10 duration-1000 delay-300">
                                    <div className="p-8">
                                        <div className="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-scribehub-blue/10 text-scribehub-blue transition-transform group-hover:scale-110 shadow-sm"><Smartphone className="h-5 w-5 transition-transform duration-500 group-hover:rotate-12" /></div>
                                        <h3 className="text-lg font-bold text-scribehub-blue dark:text-white">{t.f7Title}</h3>
                                        <p className="mt-1 text-[11px] text-scribehub-grey/70 dark:text-gray-400 font-medium mb-6 leading-relaxed">{t.f7Desc}</p>
                                        <div className="flex items-center gap-4 text-[10px] font-semibold text-scribehub-blue/60">
                                            <div className="flex items-center gap-1 group-hover:text-scribehub-blue transition-colors group-hover:scale-110 duration-300"><Cloud className="h-3 w-3 animate-pulse" /> Sync</div>
                                            <div className="flex items-center gap-1 group-hover:text-scribehub-blue transition-colors group-hover:scale-110 duration-300"><Monitor className="h-3 w-3" /> Web</div>
                                        </div>
                                    </div>
                                </div>
                                <div className="group md:col-span-2 md:row-span-1 flex flex-col overflow-hidden rounded-3xl border border-scribehub-border bg-white shadow-sm transition-all duration-500 hover:shadow-xl hover:-translate-y-1 dark:border-gray-800 dark:bg-gray-900 animate-in fade-in slide-in-from-right-10 duration-1000 delay-400">
                                    <div className="p-8 pb-0">
                                        <div className="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-scribehub-blue/10 text-scribehub-blue transition-transform group-hover:scale-110 group-hover:rotate-12 shadow-sm"><Brain className="h-5 w-5" /></div>
                                        <h3 className="text-lg font-bold text-scribehub-blue dark:text-white">{t.f5Title}</h3>
                                        <p className="mt-1 text-xs text-scribehub-grey/70 dark:text-gray-400 font-medium leading-relaxed">{t.f5Desc}</p>
                                    </div>
                                    <div className="relative h-24 mt-4 overflow-hidden opacity-30 group-hover:opacity-100 transition-all duration-700">
                                        <svg viewBox="0 0 200 100" className="h-full w-full group-hover:scale-125 transition-transform duration-1000"><circle cx="100" cy="50" r="4" fill="#0066FF" className="animate-pulse" /><circle cx="60" cy="30" r="3" fill="#00D2A0" /><circle cx="140" cy="70" r="3" fill="#00D2A0" /><line x1="100" y1="50" x2="60" y2="30" stroke="#DDD" strokeWidth="0.5" className="dark:stroke-gray-700" /><line x1="100" y1="50" x2="140" y2="70" stroke="#DDD" strokeWidth="0.5" className="dark:stroke-gray-700" /></svg>
                                    </div>
                                </div>
                            </div>
                            <div className="mt-6 grid grid-cols-1 gap-6 md:grid-cols-6">
                                <div className="group md:col-span-4 rounded-3xl border border-scribehub-border bg-white/50 overflow-hidden dark:bg-white/5 transition-all hover:bg-white hover:shadow-lg hover:-translate-y-1 p-8 animate-in fade-in slide-in-from-left-10 duration-1000 delay-500 dark:border-gray-800">
                                    <div className="flex items-center gap-3 mb-2"><Library className="h-4 w-4 text-scribehub-blue transition-transform group-hover:scale-125" /><h4 className="text-xl font-bold text-scribehub-blue dark:text-white">{t.f1Title}</h4></div>
                                    <p className="text-sm text-scribehub-grey/70 dark:text-gray-400 font-medium leading-relaxed mb-6">{t.f1Desc}</p>
                                    <div className="relative overflow-hidden py-4">
                                        <div className="flex whitespace-nowrap gap-4 animate-marquee font-bold">
                                            {[...citationStyles, ...citationStyles].map((s, i) => <div key={i} className="inline-flex items-center gap-2 rounded-full border bg-white px-4 py-2 text-[10px] dark:bg-gray-900 shadow-sm border-gray-100 dark:border-gray-800 transition-all hover:border-scribehub-blue/50"><div className={cn("h-2 w-2 rounded-full shadow-sm", s.color)}></div>{s.name}</div>)}
                                        </div>
                                        <div className="pointer-events-none absolute inset-y-0 left-0 w-24 bg-gradient-to-r from-scribehub-paper dark:from-[#050505] to-transparent z-10"></div>
                                        <div className="pointer-events-none absolute inset-y-0 right-0 w-24 bg-gradient-to-l from-scribehub-paper dark:from-[#050505] to-transparent z-10"></div>
                                    </div>
                                </div>
                                <div className="group md:col-span-2 rounded-3xl border border-scribehub-border bg-scribehub-blue p-8 transition-all hover:shadow-2xl hover:shadow-scribehub-blue/30 hover:-translate-y-1 animate-in fade-in slide-in-from-right-10 duration-1000 delay-600 dark:border-gray-800">
                                    <h4 className="text-xl font-bold text-white mb-2 leading-tight">{t.f8Title}</h4>
                                    <p className="text-[11px] text-white/70 font-medium leading-relaxed mb-6">{t.f8Desc}</p>
                                    <ShieldCheck className="h-10 w-10 text-white/20 group-hover:text-white group-hover:scale-125 transition-all duration-500" />
                                </div>
                            </div>

                            <div className="mt-16 flex justify-center animate-in fade-in slide-in-from-bottom-5 duration-1000 delay-700">
                                <Link href="/features" className="group inline-flex items-center gap-2 rounded-full bg-white px-8 py-4 text-sm font-bold text-scribehub-blue shadow-sm ring-1 ring-scribehub-border transition-all hover:bg-gray-50 hover:shadow-md dark:bg-gray-900 dark:text-white dark:ring-gray-800">
                                    {t.exploreFeatures}
                                    <MoveRight className="h-4 w-4 transition-transform group-hover:translate-x-1" />
                                </Link>
                            </div>
                        </div>
                    </section>

                    {/* Pricing Section */}
                    <section id="pricing" className="py-24 bg-white dark:bg-black overflow-hidden border-t dark:border-gray-800 relative">
                        <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                            <div className="flex flex-col lg:flex-row gap-16 items-start">
                                <div className="lg:w-1/3 animate-in fade-in slide-in-from-left-10 duration-1000">
                                    <div className="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-scribehub-blue/5 text-scribehub-blue shadow-sm border border-scribehub-blue/10"><CreditCard className="h-7 w-7" /></div>
                                    <h2 className="text-4xl font-extrabold text-scribehub-blue dark:text-white leading-tight mb-6">{t.pricingTitle}</h2>
                                    <p className="text-lg text-scribehub-grey/70 dark:text-gray-400 mb-8 font-medium leading-relaxed">{t.pricingSub}</p>
                                    <div className="inline-flex items-center gap-2 rounded-full bg-scribehub-mint/20 px-4 py-2 text-[11px] font-semibold text-scribehub-blue ring-1 ring-scribehub-mint/30 mb-12 shadow-sm">{t.earlyDiscount}</div>
                                    <div className="space-y-6">
                                        <div className="flex items-center gap-3 border-b border-gray-100 dark:border-gray-800 pb-4"><Layers className="h-5 w-5 text-scribehub-blue" /><h3 className="text-xl font-bold text-scribehub-blue dark:text-white">{t.comparePlans}</h3></div>
                                        {[{ label: t.fManagement, starter: t.fStarterMgmt, pro: t.fProMgmt, inst: t.fInstMgmt }, { label: t.fEditor, starter: t.fStarterEditor, pro: t.fProEditor, inst: t.fInstEditor }, { label: t.fAI, starter: t.fStarterAI, pro: t.fUnlimited, inst: t.fInstAI }, { label: t.fStorage, starter: t.fStarterStorage, pro: t.fProStorage, inst: t.fUnlimitedStorage }].map((row, i) => (
                                            <div key={i} className="space-y-2 group/row">
                                                <div className="text-[10px] font-semibold tracking-wider text-gray-400 transition-colors group-hover/row:text-scribehub-blue">{row.label}</div>
                                                <div className="grid grid-cols-3 gap-2 text-[9px] font-semibold">
                                                    <div className="bg-gray-50 p-2 rounded border dark:bg-gray-900 dark:border-gray-800 transition-all group-hover/row:border-gray-200">{row.starter}</div>
                                                    <div className="bg-scribehub-blue/5 p-2 rounded text-scribehub-blue border border-scribehub-blue/20 transition-all group-hover/row:shadow-sm">{row.pro}</div>
                                                    <div className="bg-purple-50 p-2 rounded text-purple-700 border border-purple-100 dark:bg-purple-900/20 dark:border-purple-900/40 transition-all group-hover/row:shadow-sm">{row.inst}</div>
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                </div>
                                <div className="lg:w-2/3 grid grid-cols-1 md:grid-cols-3 gap-6">
                                    {pricingPlans.map((plan, i) => (
                                        <div key={i} className={cn("relative flex flex-col rounded-[2.5rem] p-8 transition-all duration-500 hover:-translate-y-2 group animate-in fade-in slide-in-from-bottom-10", plan.highlight ? "bg-white ring-2 ring-scribehub-blue shadow-2xl z-10 dark:bg-gray-900" : "bg-gray-50/50 border border-gray-100 dark:bg-gray-900/30 dark:border-gray-800")} style={{ animationDelay: `${i * 150}ms` }}>
                                            {plan.highlight && <div className="absolute -top-4 left-1/2 -translate-x-1/2 bg-scribehub-blue text-white text-[10px] font-semibold px-5 py-1.5 rounded-full tracking-widest shadow-lg">{t.popular}</div>}
                                            <div className="mb-8">
                                                <div className={cn("mb-6 flex h-14 w-14 items-center justify-center rounded-2xl transition-transform group-hover:scale-110 group-hover:rotate-3 shadow-md", plan.highlight ? "bg-scribehub-blue text-white" : "bg-white text-scribehub-blue dark:bg-gray-800 border dark:border-gray-700")}><plan.icon className="h-7 w-7" /></div>
                                                <h3 className="text-xl font-bold dark:text-white mb-2 leading-tight">{plan.name}</h3>
                                                <div className="flex items-baseline gap-1.5"><span className="text-4xl font-extrabold dark:text-white tracking-tight">{plan.price}</span>{plan.price !== "Contact Sales" && plan.price !== "ติดต่อฝ่ายขาย" && <span className="text-xs text-gray-400 font-semibold tracking-tight">{t.monthly}</span>}</div>
                                            </div>
                                            <div className="space-y-4 mb-10 flex-1">
                                                <p className="text-[11px] text-gray-400 font-medium leading-relaxed min-h-[44px]">{plan.desc}</p>
                                                <div className="h-px bg-gray-100 dark:bg-gray-800"></div>
                                                {plan.extra && <div className="text-[10px] font-bold text-scribehub-blue tracking-tight py-1">{plan.extra}</div>}
                                                <ul className="space-y-2.5">
                                                    {plan.features.map((f, j) => <li key={j} className="flex items-start gap-2.5 text-[10px] font-semibold leading-relaxed dark:text-gray-300 tracking-tight"><Check className="h-3.5 w-3.5 text-scribehub-blue mt-0.5 shrink-0 stroke-[3]" />{f}</li>)}
                                                </ul>
                                            </div>
                                            <button className={cn("w-full rounded-2xl py-4 text-[13px] font-semibold transition-all shadow-lg active:scale-95 tracking-widest", plan.highlight ? "bg-scribehub-blue text-white hover:opacity-90 hover:shadow-scribehub-blue/25" : "bg-white border text-scribehub-blue hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:text-white")}>{t.getStarted}</button>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="faq" className="py-24 bg-scribehub-paper dark:bg-[#050505] border-b dark:border-gray-900">
                        <div className="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                            <div className="flex flex-col items-center text-center mb-16 animate-in fade-in slide-in-from-bottom-5 duration-1000">
                                <div className="mb-6 flex h-14 w-14 items-center justify-center rounded-full bg-scribehub-mint/20 text-scribehub-blue font-bold shadow-sm ring-1 ring-scribehub-mint/30"><HelpCircle className="h-7 w-7 animate-pulse" /></div>
                                <h2 className="text-3xl font-extrabold text-scribehub-blue dark:text-white leading-tight mb-4">{t.faqTitle}</h2>
                                <p className="text-sm text-scribehub-grey/70 dark:text-gray-400 max-w-2xl font-medium leading-relaxed">{t.faqIntro}</p>
                            </div>
                            
                            <div className="space-y-12">
                                {(t.faqCategories as any[]).map((cat, catIdx) => (
                                    <div key={catIdx} className="space-y-6">
                                        <div className="flex items-center gap-3 border-b border-gray-100 dark:border-gray-800 pb-3">
                                            {(() => {
                                                const iconMap: Record<string, any> = { Info, CreditCard, Zap, ShieldCheck, MessagesSquare };
                                                const Icon = iconMap[cat.icon as string] || HelpCircle;
                                                return <Icon className="h-5 w-5 text-scribehub-blue" />;
                                            })()}
                                            <h3 className="text-lg font-bold text-scribehub-blue dark:text-white">{cat.title}</h3>
                                        </div>
                                        <div className="space-y-4">
                                            {cat.items.map((item: { q: string; a: string }, itemIdx: number) => {
                                                const id = `${catIdx}-${itemIdx}`;
                                                return (
                                                    <div key={id} className="group overflow-hidden rounded-3xl border bg-white transition-all duration-300 dark:bg-gray-900 dark:border-gray-800 hover:shadow-md animate-in fade-in slide-in-from-bottom-5">
                                                        <button onClick={() => setOpenFaq(openFaq === id ? null : id)} className="flex w-full items-center justify-between p-6 text-left group-hover:bg-gray-50/50 dark:group-hover:bg-white/5 transition-colors">
                                                            <span className="text-sm font-semibold text-scribehub-blue dark:text-white leading-relaxed">{item.q}</span>
                                                            <ChevronDown className={cn("h-5 w-5 transition-transform duration-500 text-scribehub-blue/50", openFaq === id ? "rotate-180 text-scribehub-blue" : "0")} />
                                                        </button>
                                                        <div className={cn("overflow-hidden transition-all duration-500 ease-in-out", openFaq === id ? "max-h-[500px] opacity-100" : "max-h-0 opacity-0")}>
                                                            <div className="px-6 pb-6 text-sm text-scribehub-grey/70 dark:text-gray-400 border-t border-gray-50 dark:border-gray-800 pt-5 font-medium leading-relaxed bg-gray-50/30 dark:bg-white/5">{item.a}</div>
                                                        </div>
                                                    </div>
                                                );
                                            })}
                                        </div>
                                    </div>
                                ))}
                            </div>

                            <div className="mt-20 rounded-[2.5rem] bg-scribehub-blue p-10 text-center text-white shadow-xl animate-in fade-in zoom-in duration-1000">
                                <h3 className="text-xl font-bold mb-4">{lang === 'en' ? "Still have questions?" : "ยังมีข้อสงสัยเพิ่มเติม?"}</h3>
                                <p className="text-sm text-white/80 font-medium mb-8 leading-relaxed max-w-lg mx-auto">{t.faqContact}</p>
                                <a href="mailto:support@scribehub.com" className="inline-flex items-center justify-center rounded-full bg-white px-8 py-3 text-sm font-bold text-scribehub-blue hover:bg-gray-50 transition-all active:scale-95">{lang === 'en' ? "Contact Support" : "ติดต่อฝ่ายสนับสนุน"}</a>
                            </div>
                        </div>
                    </section>

                    {/* Community Love Section */}
                    <section className="py-24 bg-white dark:bg-black overflow-hidden">
                        <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
                            <div className="flex flex-col items-center mb-16 animate-in fade-in slide-in-from-bottom-5 duration-1000">
                                <div className="mb-6 flex items-center gap-2 rounded-full border border-orange-200 bg-orange-50 px-4 py-1.5 text-[10px] font-bold uppercase tracking-wider text-orange-600 shadow-sm dark:bg-orange-900/20 dark:border-orange-900/40">
                                    <Heart className="h-3 w-3 fill-orange-600" />
                                    {t.communityLove}
                                </div>
                                <h2 className="mb-4 text-4xl font-extrabold text-scribehub-blue dark:text-white sm:text-5xl tracking-tight">
                                    {t.convincedTitle}<br />
                                    <span className="text-gray-400 italic font-medium">{t.convincedSubtitle}</span>
                                </h2>
                                <p className="max-w-xl text-sm font-medium leading-relaxed text-scribehub-grey/60 dark:text-gray-400">
                                    {t.convincedDesc}
                                </p>
                            </div>

                            <div className="columns-1 gap-6 md:columns-2 lg:columns-3 xl:columns-4 [column-fill:_balance] mb-16">
                                {communityComments.map((comment, i) => (
                                    <div key={i} className="break-inside-avoid mb-6 animate-in fade-in slide-in-from-bottom-10 duration-1000 group" style={{ animationDelay: `${i * 100}ms` }}>
                                        <div className="overflow-hidden rounded-[2rem] border border-gray-100 bg-white transition-all duration-500 hover:shadow-xl hover:-translate-y-1 dark:border-gray-800 dark:bg-gray-900">
                                            <div className="bg-[#1a1c1e] p-5 flex items-center gap-3">
                                                <div className={cn("h-9 w-9 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-inner ring-1 ring-white/10", comment.user.color)}>
                                                    {comment.user.name[0]}
                                                </div>
                                                <div className="flex-1 text-left min-w-0">
                                                    <div className="flex items-center gap-1.5">
                                                        <span className="text-[11px] font-bold text-white truncate">{comment.user.name}</span>
                                                        <div className="h-3 w-3 rounded-full bg-blue-500 flex items-center justify-center text-[6px] text-white"><Check className="h-2 w-2 stroke-[4]" /></div>
                                                    </div>
                                                    <div className="text-[9px] text-gray-500 font-semibold">{comment.user.handle} • {comment.user.date}</div>
                                                </div>
                                                <Quote className="h-5 w-5 text-white/5 opacity-0 group-hover:opacity-100 transition-opacity" />
                                            </div>
                                            <div className="p-7 text-left relative">
                                                <p className="text-[13px] font-medium leading-relaxed text-scribehub-grey/80 dark:text-gray-300 relative z-10">
                                                    "{comment.text}"
                                                </p>
                                                <Quote className="absolute top-4 right-6 h-12 w-12 text-gray-50 dark:text-white/5 -z-0 pointer-events-none" />
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>

                            <div className="flex flex-col items-center gap-6 animate-in fade-in zoom-in duration-1000">
                                <p className="text-xs font-bold text-gray-400 uppercase tracking-widest">{t.joinGrowing}</p>
                                <button className="group relative flex items-center gap-3 overflow-hidden rounded-2xl bg-scribehub-blue px-8 py-4 text-sm font-bold text-white transition-all hover:bg-blue-700 active:scale-95 shadow-lg shadow-blue-900/10">
                                    <div className="absolute inset-x-0 -bottom-full hidden h-full bg-white/10 transition-all group-hover:bottom-0 md:block" />
                                    <Users className="h-5 w-5" />
                                    {t.joinCommunity}
                                    <MoveRight className="h-4 w-4 transition-transform group-hover:translate-x-1" />
                                </button>
                            </div>
                        </div>
                    </section>

                    {/* Final CTA Section */}
                    <section className="py-24 bg-white dark:bg-black">
                        <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                            <div className="relative overflow-hidden rounded-[3rem] bg-gray-50/50 p-12 md:p-20 dark:bg-gray-900/30 border border-gray-100 dark:border-gray-800">
                                <div className="relative z-10 max-w-2xl">
                                    <h2 className="text-4xl font-extrabold text-scribehub-blue dark:text-white sm:text-5xl leading-[1.1] mb-8 tracking-tight">
                                        {t.ctaTitle}
                                    </h2>
                                    <p className="text-lg font-bold text-orange-600 dark:text-orange-400 mb-4">
                                        {t.ctaSubtitle}
                                    </p>
                                    <p className="text-sm font-medium text-scribehub-grey/60 dark:text-gray-400 mb-12 max-w-md leading-relaxed">
                                        {t.ctaDesc}
                                    </p>
                                    <div className="flex flex-col sm:flex-row items-center gap-4">
                                        <button className="w-full sm:w-auto flex items-center justify-center gap-2 rounded-2xl bg-scribehub-blue px-8 py-4 text-sm font-bold text-white shadow-lg shadow-blue-900/20 hover:bg-blue-700 transition-all active:scale-95">
                                            {t.ctaPrimary} <MoveRight className="h-4 w-4" />
                                        </button>
                                        <button className="w-full sm:w-auto flex items-center justify-center gap-2 rounded-2xl bg-white px-8 py-4 text-sm font-bold text-scribehub-blue ring-1 ring-gray-200 hover:bg-gray-50 transition-all active:scale-95 dark:bg-gray-800 dark:ring-gray-700 dark:text-white">
                                            {t.ctaSecondary} <Sparkles className="h-4 w-4 text-orange-400" />
                                        </button>
                                    </div>
                                </div>
                                
                                {/* Background Decorative Elements (No Mascot) */}
                                <div className="absolute top-1/2 right-[-5%] -translate-y-1/2 opacity-[0.03] dark:opacity-[0.05] pointer-events-none hidden lg:block">
                                    <AppLogoIcon className="w-[600px] h-auto grayscale" />
                                </div>
                                <div className="absolute top-10 right-20 animate-pulse hidden lg:block">
                                    <div className="h-24 w-24 rounded-full bg-scribehub-blue/5 blur-3xl"></div>
                                </div>
                            </div>
                        </div>
                    </section>
                </main>

                <footer className="border-t border-scribehub-border bg-white pt-16 pb-8 dark:border-gray-800 dark:bg-black">
                    <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div className="grid grid-cols-1 md:grid-cols-4 gap-12 text-center md:text-left">
                            <div className="md:col-span-1"><AppLogo /><p className="mt-6 text-[10px] text-gray-500 font-semibold leading-relaxed tracking-tight">{t.footerTagline}</p></div>
                            <div><h4 className="text-[10px] font-bold tracking-widest text-scribehub-blue mb-6">Links</h4><ul className="space-y-3 text-[11px] text-gray-400 font-semibold tracking-tight"><li><a href="#features" className="hover:text-scribehub-blue transition-colors">Features</a></li><li><a href="#pricing" className="hover:text-scribehub-blue transition-colors">Pricing</a></li><li><a href="#faq" className="hover:text-scribehub-blue transition-colors">FAQ</a></li><li><Link href="/help" className="hover:text-scribehub-blue transition-colors">Help Center</Link></li></ul></div>
                            <div><h4 className="text-[10px] font-bold tracking-widest text-scribehub-blue mb-6">Legal</h4><ul className="space-y-3 text-[11px] text-gray-400 font-semibold tracking-tight"><li><a href="#" className="hover:text-scribehub-blue transition-colors">Terms</a></li><li><a href="#" className="hover:text-scribehub-blue transition-colors">Privacy</a></li></ul></div>
                            <div><h4 className="text-[10px] font-bold tracking-widest text-scribehub-blue mb-6">Social</h4><div className="flex gap-4 justify-center md:justify-start"><Globe className="h-5 w-5 text-gray-300 hover:text-scribehub-blue transition-all cursor-pointer hover:scale-110" /><ShieldCheck className="h-5 w-5 text-gray-300 hover:text-scribehub-blue transition-all cursor-pointer hover:scale-110" /></div></div>
                        </div>
                        <div className="mt-16 pt-8 border-t border-gray-50 dark:border-gray-900 text-[10px] text-gray-400 font-semibold text-center tracking-widest">Copyright © {new Date().getFullYear()} ScribeHub - All rights reserved</div>
                    </div>
                </footer>
            </div>
        </>
    );
}
