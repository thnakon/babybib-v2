import WorkspaceLayout from '@/layouts/workspace-layout';
import { Head } from '@inertiajs/react';
import { Group, Panel, Separator } from 'react-resizable-panels';
import PdfViewer from '@/components/workspace/pdf-viewer';
import ResearchEditor, { ResearchEditorRef } from '@/components/workspace/research-editor';
import { GripVertical } from 'lucide-react';
import { useState, useRef, useEffect } from 'react';
import { Reference } from '@/types';
import { cn } from '@/lib/utils';

interface Manuscript {
    id: number;
    title: string;
    content: string;
}

interface WorkspaceProps {
    manuscript: Manuscript;
    manuscripts: Manuscript[];
    references: Reference[];
}

export default function WorkspaceIndex({ manuscript, manuscripts, references }: WorkspaceProps) {
    const [activeTab, setActiveTab] = useState<'editor' | 'pdf'>('editor');
    const [isDesktop, setIsDesktop] = useState(true);
    const editorRef = useRef<ResearchEditorRef>(null);

    // Dynamic layout checking
    useEffect(() => {
        const checkDevice = () => setIsDesktop(window.innerWidth >= 1024);
        checkDevice();
        window.addEventListener('resize', checkDevice);
        return () => window.removeEventListener('resize', checkDevice);
    }, []);

    const handleCiteFromHeader = (ref: Reference) => {
        editorRef.current?.insertCitation(ref);
        if (!isDesktop) setActiveTab('editor'); // Auto-switch on mobile when citing
    };

    const handleManualSave = () => {
        editorRef.current?.manualSave();
    };

    return (
        <WorkspaceLayout 
            manuscriptId={manuscript.id} 
            manuscripts={manuscripts}
            references={references}
            activeTab={activeTab}
            setActiveTab={setActiveTab}
            onCite={handleCiteFromHeader}
            onSave={handleManualSave}
        >
            <Head title={`Workspace - ${manuscript.title}`} />
            
            <div className="flex-1 w-full bg-white dark:bg-[#050505] overflow-hidden">
                {/* 1. Desktop & Large Tablets (Split View: PDF & Editor) */}
                {isDesktop ? (
                    <Group orientation="horizontal" className="h-full">
                        {/* Left: PDF Viewer */}
                        <Panel defaultSize={50} minSize={30} id="workspace-pdf">
                            <div className="h-full border-r dark:border-gray-800 bg-gray-100 dark:bg-black/40">
                                <PdfViewer />
                            </div>
                        </Panel>

                        <Separator className="w-2 shrink-0 flex items-center justify-center bg-white shadow-sm transition-colors hover:bg-blue-600/20 dark:bg-[#050505] dark:hover:bg-blue-900/20 cursor-col-resize">
                            <div className="h-8 w-1.5 rounded-full bg-gray-200 dark:bg-gray-700" />
                        </Separator>

                        {/* Right: Editor */}
                        <Panel defaultSize={50} minSize={30} id="workspace-editor">
                            <div className="h-full bg-white dark:bg-[#0a0a0a]">
                                <ResearchEditor ref={editorRef} manuscript={manuscript} references={references} />
                            </div>
                        </Panel>
                    </Group>
                ) : (
                    /* 2. Mobile & Standard Tablets (Tab View) */
                    <div className="h-full flex flex-col w-full overflow-hidden bg-white dark:bg-[#050505]">
                        <div className={cn("h-full w-full bg-gray-50 dark:bg-black/20", activeTab === 'pdf' ? "flex flex-col" : "hidden")}>
                            <PdfViewer />
                        </div>

                        <div className={cn("h-full w-full", activeTab === 'editor' ? "flex flex-col" : "hidden")}>
                            <ResearchEditor ref={editorRef} manuscript={manuscript} references={references} />
                        </div>
                    </div>
                )}
            </div>
        </WorkspaceLayout>
    );
}
