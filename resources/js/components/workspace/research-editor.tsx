import { useEditor, EditorContent } from '@tiptap/react';
import StarterKit from '@tiptap/starter-kit';
import { Bold, Italic, List, ListOrdered, Heading1, Heading2, Quote, Undo, Redo } from 'lucide-react';
import { cn } from '@/lib/utils';
import { useEffect } from 'react';

const MenuBar = ({ editor }: { editor: any }) => {
    if (!editor) {
        return null;
    }

    const Button = ({ onClick, isActive, disabled, children }: any) => (
        <button
            onClick={onClick}
            disabled={disabled}
            className={cn(
                "rounded p-1.5 transition-colors hover:bg-gray-100 disabled:opacity-50 dark:hover:bg-gray-700",
                isActive ? "bg-gray-200 text-scribehub-blue dark:bg-gray-700 dark:text-white" : "text-gray-600 dark:text-gray-400"
            )}
        >
            {children}
        </button>
    );

    return (
        <div className="flex flex-wrap items-center gap-1 border-b bg-white px-4 py-2 dark:border-gray-800 dark:bg-[#0a0a0a]">
            <Button
                onClick={() => editor.chain().focus().toggleBold().run()}
                disabled={!editor.can().chain().focus().toggleBold().run()}
                isActive={editor.isActive('bold')}
            >
                <Bold className="h-4 w-4" />
            </Button>
            <Button
                onClick={() => editor.chain().focus().toggleItalic().run()}
                disabled={!editor.can().chain().focus().toggleItalic().run()}
                isActive={editor.isActive('italic')}
            >
                <Italic className="h-4 w-4" />
            </Button>
            
            <div className="mx-1 h-4 w-px bg-gray-200 dark:bg-gray-700" />
            
            <Button
                onClick={() => editor.chain().focus().toggleHeading({ level: 1 }).run()}
                isActive={editor.isActive('heading', { level: 1 })}
            >
                <Heading1 className="h-4 w-4" />
            </Button>
            <Button
                onClick={() => editor.chain().focus().toggleHeading({ level: 2 }).run()}
                isActive={editor.isActive('heading', { level: 2 })}
            >
                <Heading2 className="h-4 w-4" />
            </Button>
            
            <div className="mx-1 h-4 w-px bg-gray-200 dark:bg-gray-700" />
            
            <Button
                onClick={() => editor.chain().focus().toggleBulletList().run()}
                isActive={editor.isActive('bulletList')}
            >
                <List className="h-4 w-4" />
            </Button>
            <Button
                onClick={() => editor.chain().focus().toggleOrderedList().run()}
                isActive={editor.isActive('orderedList')}
            >
                <ListOrdered className="h-4 w-4" />
            </Button>
             <Button
                onClick={() => editor.chain().focus().toggleBlockquote().run()}
                isActive={editor.isActive('blockquote')}
            >
                <Quote className="h-4 w-4" />
            </Button>

            <div className="mx-1 h-4 w-px bg-gray-200 dark:bg-gray-700" />

            <Button
                onClick={() => editor.chain().focus().undo().run()}
                disabled={!editor.can().chain().focus().undo().run()}
            >
                <Undo className="h-4 w-4" />
            </Button>
            <Button
                onClick={() => editor.chain().focus().redo().run()}
                disabled={!editor.can().chain().focus().redo().run()}
            >
                <Redo className="h-4 w-4" />
            </Button>
        </div>
    );
};

export default function ResearchEditor() {
    const editor = useEditor({
        extensions: [
            StarterKit.configure({
                heading: {
                    levels: [1, 2, 3],
                },
            }),
        ],
        content: `
            <h1>Untitled Manuscript</h1>
            <p>Start writing your research paper here...</p>
        `,
        editorProps: {
            attributes: {
                class: 'prose prose-sm sm:prose-base dark:prose-invert focus:outline-none max-w-none p-8 min-h-[calc(100vh-8rem)]',
            },
        },
    });

    // Cleanup
    useEffect(() => {
        return () => {
            editor?.destroy();
        };
    }, [editor]);

    return (
        <div className="flex h-full flex-col bg-white dark:bg-[#0a0a0a]">
            <MenuBar editor={editor} />
            <div className="flex-1 overflow-y-auto custom-scrollbar">
               <EditorContent editor={editor} />
            </div>
            
            {/* Status Integration Bar */}
            <div className="border-t bg-gray-50 px-4 py-1 text-[10px] text-gray-500 dark:border-gray-800 dark:bg-[#050505]">
                Draft saved locally • Word count: {editor?.storage.characterCount?.words() || 0}
            </div>
        </div>
    );
}
