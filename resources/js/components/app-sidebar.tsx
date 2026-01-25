import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import workspace from '@/routes/workspace';
import citations from '@/routes/citations';
import references from '@/routes/references';
import type { NavItem } from '@/types';
import { Link } from '@inertiajs/react';
import { 
    BookOpen, Folder, LayoutGrid, Library, Brain, 
    Sparkles, Share2, MessageSquare, Search, FileCode,
    Archive, Star, PlusCircle, Monitor, Wand2,
    Users, Clock
} from 'lucide-react';
import AppLogo from './app-logo';
import { useLanguage } from '@/contexts/language-context';
const translations = {
    en: {
        overview: "Overview",
        workDesk: "Dashboard",
        aiAgent: "AI Agent",
        projects: "Projects",
        myProjects: "My Projects",
        intelligence: "Knowledge Intelligence",
        literatureMap: "Literature Map",
        templates: "Research Templates",
        paraphraser: "Academic Paraphraser",
        resources: "Library Resources",
        references: "My References",
        citations: "Citations",
        searchDiscovery: "Search & Discovery",
        workingSpace: "Working Space"
    },
    th: {
        overview: "ภาพรวม",
        workDesk: "แดชบอร์ด",
        aiAgent: "ผู้ช่วย AI",
        projects: "โปรเจกต์",
        myProjects: "โปรเจกต์ของฉัน",
        intelligence: "ระบบวิเคราะห์ข้อมูล",
        literatureMap: "แผนผังงานวิจัย",
        templates: "แบบร่างงานวิจัย",
        paraphraser: "ขัดเกลาสำนวนภาษา",
        resources: "คลังข้อมูล",
        references: "รายการอ้างอิง",
        citations: "การอ้างอิงในเนื้อหา",
        searchDiscovery: "ค้นหาแหล่งข้อมูล",
        workingSpace: "พื้นที่ทำงาน"
    }
};

export function AppSidebar() {
    const { t, language } = useLanguage();
    const txt = (key: string) => t(key, translations) as string;

    const mainNavItems: NavItem[] = [
        {
            title: txt('workDesk'),
            href: dashboard(),
            icon: LayoutGrid,
        },
        {
            title: txt('aiAgent'),
            href: '/ai/chat',
            icon: Sparkles,
        },
    ];

    const projectNavItems: NavItem[] = [
        {
            title: txt('myProjects'),
            href: '/projects',
            icon: Folder,
        },
        {
            title: language === 'en' ? 'Team Spaces' : 'พื้นที่ทำงานร่วม',
            href: '/team-spaces',
            icon: Users,
        }
    ];

    const aiNavItems: NavItem[] = [
        {
            title: txt('workingSpace'),
            href: workspace.index.url(),
            icon: Monitor,
        },
        {
            title: txt('paraphraser'),
            href: '/paraphraser',
            icon: Wand2,
        },
        {
            title: txt('templates'),
            href: '/research-templates',
            icon: FileCode,
        },
    ];

    const libraryNavItems: NavItem[] = [
        {
            title: txt('searchDiscovery'),
            href: '/import',
            icon: Search,
        },
        {
            title: txt('references'),
            href: references.index.url(),
            icon: Library,
        },
        {
            title: txt('citations'),
            href: citations.index.url(),
            icon: FileCode,
        },
        {
            title: txt('literatureMap'),
            href: '/literature-map',
            icon: Share2,
        },
    ];

    const footerNavItems: NavItem[] = [
        {
            title: 'Repository',
            href: 'https://github.com/thnakon/babybib-v2',
            icon: Folder,
        },
        {
            title: 'Feedback',
            href: '/feedback',
            icon: MessageSquare,
        },
    ];

    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href={dashboard()} prefetch>
                                <AppLogo renderLink={false} />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={mainNavItems} label={txt('overview')} />
                <NavMain items={projectNavItems} label={txt('projects')} />
                <NavMain items={aiNavItems} label={txt('intelligence')} />
                <NavMain items={libraryNavItems} label={txt('resources')} />
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
