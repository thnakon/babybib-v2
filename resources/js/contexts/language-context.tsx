import { createContext, useContext, useState, useEffect, ReactNode } from 'react';

type Language = 'en' | 'th';

interface LanguageContextType {
    language: Language;
    setLanguage: (lang: Language) => void;
    t: (key: string, translations: Record<Language, Record<string, unknown>>) => unknown;
}

const LanguageContext = createContext<LanguageContextType | undefined>(undefined);

const LANGUAGE_STORAGE_KEY = 'scribehub-language';

export function LanguageProvider({ children }: { children: ReactNode }) {
    const [language, setLanguageState] = useState<Language>('en');

    // Load saved language on mount
    useEffect(() => {
        const saved = localStorage.getItem(LANGUAGE_STORAGE_KEY) as Language | null;
        if (saved && (saved === 'en' || saved === 'th')) {
            setLanguageState(saved);
        }
    }, []);

    // Save language when changed
    const setLanguage = (lang: Language) => {
        setLanguageState(lang);
        localStorage.setItem(LANGUAGE_STORAGE_KEY, lang);
    };

    // Helper function to get translation
    const t = (key: string, translations: Record<Language, Record<string, unknown>>): unknown => {
        const langTranslations = translations[language];
        const keys = key.split('.');
        let result: unknown = langTranslations;
        
        for (const k of keys) {
            if (result && typeof result === 'object' && k in result) {
                result = (result as Record<string, unknown>)[k];
            } else {
                return key; // Return key if translation not found
            }
        }
        
        return result;
    };

    return (
        <LanguageContext.Provider value={{ language, setLanguage, t }}>
            {children}
        </LanguageContext.Provider>
    );
}

export function useLanguage() {
    const context = useContext(LanguageContext);
    if (context === undefined) {
        throw new Error('useLanguage must be used within a LanguageProvider');
    }
    return context;
}
