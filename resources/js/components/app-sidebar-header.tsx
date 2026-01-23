import { Breadcrumbs } from '@/components/breadcrumbs';
import { SidebarTrigger } from '@/components/ui/sidebar';
import type { BreadcrumbItem as BreadcrumbItemType } from '@/types';
import { Search, Languages, Sun, Moon, Monitor, Check } from 'lucide-react';
import { useAppearance } from '@/hooks/use-appearance';
import { useLanguage } from '@/contexts/language-context';
import { cn } from '@/lib/utils';

export function AppSidebarHeader({
    breadcrumbs = [],
}: {
    breadcrumbs?: BreadcrumbItemType[];
}) {
    const { appearance, updateAppearance } = useAppearance();
    const { language, setLanguage } = useLanguage();

    return (
        <header className="flex h-16 shrink-0 items-center justify-between gap-2 border-b border-sidebar-border/50 px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4">
            <div className="flex items-center gap-2">
                <SidebarTrigger className="-ml-1" />
                <Breadcrumbs breadcrumbs={breadcrumbs} />
            </div>

            <div className="flex flex-1 items-center justify-end gap-3">
                {/* Toggles */}
                <div className="flex items-center gap-1">
                    {/* Language Switcher */}
                    <div className="group relative">
                        <button className="flex h-9 w-9 items-center justify-center rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                            <Languages className="h-4 w-4 text-gray-500" />
                        </button>
                        <div className="absolute right-0 top-full hidden group-hover:block z-[60] pt-2">
                            <div className="w-32 origin-top-right rounded-xl border border-gray-100 bg-white py-2 shadow-xl ring-1 ring-black ring-opacity-5 dark:border-gray-800 dark:bg-gray-900">
                                <button onClick={() => setLanguage('en')} className={cn("flex w-full items-center justify-between px-4 py-2 text-[10px] font-bold", language === 'en' ? "bg-gray-50 text-scribehub-blue dark:bg-gray-800" : "text-gray-700 dark:text-gray-300")}>ENGLISH {language === 'en' && <Check className="h-3 w-3" />}</button>
                                <button onClick={() => setLanguage('th')} className={cn("flex w-full items-center justify-between px-4 py-2 text-[10px] font-bold", language === 'th' ? "bg-gray-50 text-scribehub-blue dark:bg-gray-800" : "text-gray-700 dark:text-gray-300")}>ภาษาไทย {language === 'th' && <Check className="h-3 w-3" />}</button>
                            </div>
                        </div>
                    </div>

                    {/* Appearance Toggle */}
                    <div className="group relative">
                        <button className="flex h-9 w-9 items-center justify-center rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                            {appearance === 'dark' ? <Moon className="h-4 w-4 text-gray-500" /> : appearance === 'light' ? <Sun className="h-4 w-4 text-gray-500" /> : <Monitor className="h-4 w-4 text-gray-500" />}
                        </button>
                        <div className="absolute right-0 top-full hidden group-hover:block z-[60] pt-2">
                            <div className="w-32 origin-top-right rounded-xl border border-gray-100 bg-white py-2 shadow-xl ring-1 ring-black ring-opacity-5 dark:border-gray-800 dark:bg-gray-900">
                                <button onClick={() => updateAppearance('light')} className={cn("flex w-full items-center justify-between px-4 py-2 text-[10px] font-bold", appearance === 'light' ? "bg-gray-50 text-scribehub-blue dark:bg-gray-800" : "text-gray-700 dark:text-gray-300")}>LIGHT {appearance === 'light' && <Check className="h-3 w-3" />}</button>
                                <button onClick={() => updateAppearance('dark')} className={cn("flex w-full items-center justify-between px-4 py-2 text-[10px] font-bold", appearance === 'dark' ? "bg-gray-50 text-scribehub-blue dark:bg-gray-800" : "text-gray-700 dark:text-gray-300")}>DARK {appearance === 'dark' && <Check className="h-3 w-3" />}</button>
                                <button onClick={() => updateAppearance('system')} className={cn("flex w-full items-center justify-between px-4 py-2 text-[10px] font-bold", appearance === 'system' ? "bg-gray-50 text-scribehub-blue dark:bg-gray-800" : "text-gray-700 dark:text-gray-300")}>SYSTEM {appearance === 'system' && <Check className="h-3 w-3" />}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    );
}

