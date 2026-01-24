import AppLogo from '@/components/app-logo';
import { NavUser } from '@/components/nav-user';
import { dashboard } from '@/routes';
import { Link } from '@inertiajs/react';
import { ArrowLeft, Moon, Sun, Monitor, Check } from 'lucide-react';
import { useAppearance } from '@/hooks/use-appearance';
import { cn } from '@/lib/utils';

interface WorkspaceLayoutProps {
    children: React.ReactNode;
}

export default function WorkspaceLayout({ children }: WorkspaceLayoutProps) {
    const { appearance, updateAppearance } = useAppearance();

    return (
        <div className="flex h-screen flex-col bg-gray-50 dark:bg-[#050505]">
            {/* Minimal Header */}
            <header className="flex h-12 shrink-0 items-center justify-between border-b bg-white px-4 shadow-sm dark:border-gray-800 dark:bg-[#0a0a0a]">
                <div className="flex items-center gap-4">
                    <Link 
                        href={dashboard()} 
                        className="flex h-8 w-8 items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white"
                        title="Back to Dashboard"
                    >
                        <ArrowLeft className="h-4 w-4" />
                    </Link>
                    <div className="h-6 w-px bg-gray-200 dark:bg-gray-800"></div>
                    <AppLogo className="h-6 w-auto" />
                    <span className="rounded-full bg-scribehub-blue/10 px-2.5 py-0.5 text-[10px] font-bold text-scribehub-blue dark:bg-scribehub-blue/20 dark:text-blue-300">
                        WORKSPACE
                    </span>
                </div>

                <div className="flex items-center gap-2">
                    {/* Appearance Toggle (Compact) */}
                    <div className="group relative">
                         <button className="flex h-8 w-8 items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800">
                            {appearance === 'dark' ? <Moon className="h-4 w-4" /> : appearance === 'light' ? <Sun className="h-4 w-4" /> : <Monitor className="h-4 w-4" />}
                        </button>
                        <div className="absolute right-0 top-full z-50 hidden w-32 origin-top-right rounded-lg border border-gray-100 bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 group-hover:block dark:border-gray-800 dark:bg-gray-900">
                            <button onClick={() => updateAppearance('light')} className={cn("flex w-full items-center justify-between px-3 py-1.5 text-xs font-medium", appearance === 'light' ? "bg-gray-50 text-scribehub-blue dark:bg-gray-800" : "text-gray-700 dark:text-gray-300")}>Light {appearance === 'light' && <Check className="h-3 w-3" />}</button>
                            <button onClick={() => updateAppearance('dark')} className={cn("flex w-full items-center justify-between px-3 py-1.5 text-xs font-medium", appearance === 'dark' ? "bg-gray-50 text-scribehub-blue dark:bg-gray-800" : "text-gray-700 dark:text-gray-300")}>Dark {appearance === 'dark' && <Check className="h-3 w-3" />}</button>
                            <button onClick={() => updateAppearance('system')} className={cn("flex w-full items-center justify-between px-3 py-1.5 text-xs font-medium", appearance === 'system' ? "bg-gray-50 text-scribehub-blue dark:bg-gray-800" : "text-gray-700 dark:text-gray-300")}>System {appearance === 'system' && <Check className="h-3 w-3" />}</button>
                        </div>
                    </div>
                    
                    <div className="h-6 w-px bg-gray-200 dark:bg-gray-800 mx-1"></div>
                    <NavUser />
                </div>
            </header>

            {/* Main Content (Full Height) */}
            <main className="flex-1 overflow-hidden">
                {children}
            </main>
        </div>
    );
}
