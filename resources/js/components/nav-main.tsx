import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { useCurrentUrl } from '@/hooks/use-current-url';
import type { NavItem } from '@/types';
import { Link } from '@inertiajs/react';
import { cn } from '@/lib/utils';
import { Lock } from 'lucide-react';

export function NavMain({ items = [], label = 'Platform' }: { items: NavItem[]; label?: string }) {
    const { isCurrentUrl } = useCurrentUrl();

    return (
        <SidebarGroup className="px-2 py-0">
            <SidebarGroupLabel>{label}</SidebarGroupLabel>
            <SidebarMenu>
                {items.map((item) => {
                    const active = isCurrentUrl(item.href);
                    const locked = item.locked;
                    
                    return (
                        <SidebarMenuItem key={item.title}>
                            <SidebarMenuButton
                                asChild={!locked}
                                isActive={active}
                                disabled={locked}
                                tooltip={{ children: locked ? `${item.title} (Coming Soon)` : item.title }}
                                className={cn(
                                    "transition-all duration-200",
                                    active && "bg-sidebar-accent/80 text-sidebar-accent-foreground font-bold shadow-sm ring-1 ring-black/5 dark:ring-white/10",
                                    locked && "opacity-50 cursor-not-allowed hover:bg-transparent"
                                )}
                            >
                                {locked ? (
                                    <div className="flex w-full items-center gap-2 px-2 py-1.5 grayscale">
                                        {item.icon && <item.icon className="h-4 w-4" />}
                                        <span className="flex-1 truncate">{item.title}</span>
                                        <Lock className="h-3 w-3 opacity-70" />
                                    </div>
                                ) : (
                                    <Link href={item.href} prefetch className="flex flex-1 items-center gap-2">
                                        {item.icon && <item.icon />}
                                        <span className="flex-1">{item.title}</span>
                                        {item.badge && (
                                            <span className="flex h-5 min-w-5 items-center justify-center rounded-full bg-red-500 px-1.5 text-[10px] font-bold text-white shadow-sm ring-1 ring-white dark:ring-gray-900">
                                                {item.badge}
                                            </span>
                                        )}
                                    </Link>
                                )}
                            </SidebarMenuButton>
                        </SidebarMenuItem>
                    );
                })}
            </SidebarMenu>
        </SidebarGroup>
    );
}
