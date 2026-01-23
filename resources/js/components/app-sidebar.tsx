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
import type { NavItem } from '@/types';
import { Link } from '@inertiajs/react';
import { BookOpen, Folder, LayoutGrid, Library, Brain, Upload, FileText } from 'lucide-react';
import AppLogo from './app-logo';

const mainNavItems: NavItem[] = [
    {
        title: 'Work Desk',
        href: dashboard(),
        icon: LayoutGrid,
    },
];

// Research Tools section
const researchNavItems: NavItem[] = [
    {
        title: 'My References',
        href: '/references',
        icon: Library,
    },
    {
        title: 'Quick Import',
        href: '/import',
        icon: Upload,
    },
    {
        title: 'Citations',
        href: '/citations',
        icon: FileText,
    },
    {
        title: 'My Projects',
        href: '/projects',
        icon: Folder,
    },
];

// AI & Tools section
const toolsNavItems: NavItem[] = [
    {
        title: 'AI Workspace',
        href: '#',
        icon: Brain,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Repository',
        href: 'https://github.com/laravel/react-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#react',
        icon: BookOpen,
    },
];

export function AppSidebar() {
    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href={dashboard()} prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={mainNavItems} label="Overview" />
                <NavMain items={researchNavItems} label="Research Tools" />
                <NavMain items={toolsNavItems} label="AI & Tools" />
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
