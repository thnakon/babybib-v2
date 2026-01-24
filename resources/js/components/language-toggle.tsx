import { Button } from '@/components/ui/button';
import { 
    DropdownMenu, 
    DropdownMenuContent, 
    DropdownMenuItem, 
    DropdownMenuTrigger 
} from '@/components/ui/dropdown-menu';
import { useLanguage } from '@/contexts/language-context';
import { Languages, Check } from 'lucide-react';
import { cn } from '@/lib/utils';

export default function LanguageToggle() {
    const { language, setLanguage } = useLanguage();

    const languages = [
        { code: 'en', label: 'English', flag: '🇺🇸' },
        { code: 'th', label: 'ไทย', flag: '🇹🇭' }
    ];

    return (
        <DropdownMenu>
            <DropdownMenuTrigger asChild>
                <Button variant="ghost" size="icon" className="h-9 w-9 rounded-full ring-1 ring-gray-200 dark:ring-gray-800 bg-white dark:bg-gray-900 transition-all">
                    <Languages className="h-[1.2rem] w-[1.2rem] text-gray-500 dark:text-gray-400" />
                    <span className="sr-only">Toggle language</span>
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end" className="w-40 dark:bg-gray-900 dark:border-gray-800">
                {languages.map((lang) => (
                    <DropdownMenuItem
                        key={lang.code}
                        onClick={() => setLanguage(lang.code as 'en' | 'th')}
                        className={cn(
                            "flex items-center justify-between cursor-pointer py-2 px-3",
                            language === lang.code ? "bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400" : "text-gray-600 dark:text-gray-300"
                        )}
                    >
                        <div className="flex items-center gap-3">
                            <span className="text-base">{lang.flag}</span>
                            <span className="text-xs font-bold">{lang.label}</span>
                        </div>
                        {language === lang.code && <Check className="h-3.5 w-3.5" />}
                    </DropdownMenuItem>
                ))}
            </DropdownMenuContent>
        </DropdownMenu>
    );
}
