import { Search, Library, Plus, Quote, PlusCircle } from 'lucide-react';
import { useState } from 'react';
import { Input } from '@/components/ui/input';
import { cn } from '@/lib/utils';
import { Reference } from '@/types';

interface ReferenceSidebarProps {
    references: Reference[];
    onCite: (reference: Reference) => void;
}

export default function ReferenceSidebar({ references, onCite }: ReferenceSidebarProps) {
    const [search, setSearch] = useState('');

    const filtered = references.filter(ref => 
        ref.title.toLowerCase().includes(search.toLowerCase()) ||
        (ref.authors && ref.authors.some((a: string) => a.toLowerCase().includes(search.toLowerCase())))
    );

    return (
        <div className="flex h-full flex-col bg-white dark:bg-[#0a0a0a]">
            <div className="border-b p-4">
                <div className="flex items-center justify-between mb-4">
                    <h3 className="flex items-center gap-2 text-sm font-bold uppercase tracking-wider text-gray-500">
                        <Library className="h-4 w-4" /> My References
                    </h3>
                </div>
                <div className="relative">
                    <Search className="absolute left-2.5 top-2.5 h-4 w-4 text-gray-400" />
                    <Input
                        placeholder="Search references..."
                        className="pl-9 h-9"
                        value={search}
                        onChange={(e) => setSearch(e.target.value)}
                    />
                </div>
            </div>

            <div className="flex-1 overflow-y-auto custom-scrollbar p-2">
                {filtered.length === 0 ? (
                    <div className="flex flex-col items-center justify-center p-8 text-center text-gray-400">
                        <Library className="h-10 w-10 mb-2 opacity-20" />
                        <p className="text-sm">No references found</p>
                    </div>
                ) : (
                    <div className="space-y-1">
                        {filtered.map((ref) => (
                            <div 
                                key={ref.id}
                                className="group rounded-lg border border-transparent p-3 transition-all hover:bg-gray-50 dark:hover:bg-gray-900 hover:border-gray-100 dark:hover:border-gray-800"
                            >
                                <div className="mb-1 text-xs font-bold text-gray-400">
                                    {ref.type.toUpperCase()} • {ref.year || 'n.d.'}
                                </div>
                                <h4 className="mb-2 text-sm font-semibold leading-tight dark:text-gray-200">
                                    {ref.title}
                                </h4>
                                <p className="mb-3 text-[11px] text-gray-500 line-clamp-2">
                                    {ref.authors ? ref.authors.join(', ') : 'Unknown Author'}
                                </p>
                                <button
                                    onClick={() => onCite(ref)}
                                    className="flex w-full items-center justify-center gap-1.5 rounded-md bg-scribehub-blue/5 py-1.5 text-xs font-bold text-scribehub-blue opacity-0 transition-all group-hover:opacity-100 hover:bg-scribehub-blue hover:text-white"
                                >
                                    <PlusCircle className="h-3.5 w-3.5" /> Cite Source
                                </button>
                            </div>
                        ))}
                    </div>
                )}
            </div>
        </div>
    );
}
