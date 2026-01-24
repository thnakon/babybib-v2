import { Link } from '@inertiajs/react';
import AppLogoIcon from './app-logo-icon';
import { cn } from '@/lib/utils';

interface AppLogoProps {
    renderLink?: boolean;
    className?: string;
}

export default function AppLogo({ renderLink = true, className }: AppLogoProps) {
    const content = (
        <>
            <div className="flex aspect-square size-9 items-center justify-center rounded-xl bg-scribehub-blue/5 p-1.5 transition-colors group-hover:bg-scribehub-blue/10 dark:bg-white/10">
                <AppLogoIcon className="size-full object-contain" />
            </div>
            <div className="grid flex-1 text-left text-sm leading-tight">
                <span className="truncate font-bold text-scribehub-blue dark:text-white text-lg tracking-tight">
                    ScribeHub
                </span>
            </div>
        </>
    );

    if (!renderLink) {
        return <div className={cn("flex items-center gap-2", className)}>{content}</div>;
    }

    return (
        <Link href="/" className={cn("flex items-center gap-2", className)}>
            {content}
        </Link>
    );
}
