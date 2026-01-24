import WorkspaceLayout from '@/layouts/workspace-layout';
import { Head } from '@inertiajs/react';
// @ts-ignore
import { Panel, PanelGroup, PanelResizeHandle } from 'react-resizable-panels';
import PdfViewer from '@/components/workspace/pdf-viewer';
import ResearchEditor from '@/components/workspace/research-editor';
import { GripVertical } from 'lucide-react';

export default function WorkspaceIndex() {
    return (
        <WorkspaceLayout>
            <Head title="Workspace - ScribeHub" />
            
            <PanelGroup direction="horizontal" className="h-full">
                {/* Left Panel: PDF Viewer */}
                <Panel defaultSize={50} minSize={30}>
                    <div className="h-full border-r dark:border-gray-800">
                        <PdfViewer />
                    </div>
                </Panel>

                {/* Resizer Handle */}
                <PanelResizeHandle className="relative flex w-2 items-center justify-center bg-gray-50 transition-colors hover:bg-scribehub-blue/20 dark:bg-[#050505] dark:hover:bg-blue-900/20">
                    <div className="z-10 flex h-8 w-4 items-center justify-center rounded-sm border bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <GripVertical className="h-3 w-3 text-gray-400" />
                    </div>
                </PanelResizeHandle>

                {/* Right Panel: Research Editor */}
                <Panel defaultSize={50} minSize={30}>
                    <div className="h-full">
                        <ResearchEditor />
                    </div>
                </Panel>
            </PanelGroup>
        </WorkspaceLayout>
    );
}
