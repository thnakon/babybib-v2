import { useState } from 'react';
import { Document, Page, pdfjs } from 'react-pdf';
import { Upload, ZoomIn, ZoomOut, ChevronLeft, ChevronRight, File as FileIcon } from 'lucide-react';
import { cn } from '@/lib/utils';
import 'react-pdf/dist/Page/AnnotationLayer.css';
import 'react-pdf/dist/Page/TextLayer.css';

// Set up worker
pdfjs.GlobalWorkerOptions.workerSrc = new URL(
  'pdfjs-dist/build/pdf.worker.min.mjs',
  import.meta.url,
).toString();

export default function PdfViewer() {
    const [file, setFile] = useState<File | null>(null);
    const [numPages, setNumPages] = useState<number>(0);
    const [pageNumber, setPageNumber] = useState<number>(1);
    const [scale, setScale] = useState<number>(1.0);

    function onDocumentLoadSuccess({ numPages }: { numPages: number }) {
        setNumPages(numPages);
        setPageNumber(1);
    }

    const handleFileUpload = (event: React.ChangeEvent<HTMLInputElement>) => {
        const files = event.target.files;
        if (files && files[0]) {
            setFile(files[0]);
        }
    };

    return (
        <div className="flex h-full flex-col bg-gray-100 dark:bg-gray-900">
            {/* Toolbar */}
            <div className="flex h-12 items-center justify-between border-b bg-white px-4 shadow-sm dark:border-gray-800 dark:bg-[#0a0a0a]">
                <div className="flex items-center gap-2">
                    <div className="flex items-center gap-2 text-sm font-bold text-gray-600 dark:text-gray-300">
                        <FileIcon className="h-4 w-4" />
                        <span className="max-w-[150px] truncate">{file ? file.name : 'No PDF loaded'}</span>
                    </div>
                </div>
                
                <div className="flex items-center rounded-lg bg-gray-100 p-1 dark:bg-gray-800">
                    <button 
                        onClick={() => setPageNumber(prev => Math.max(prev - 1, 1))}
                        disabled={pageNumber <= 1}
                        className="rounded p-1 hover:bg-white disabled:opacity-50 dark:hover:bg-gray-700"
                    >
                        <ChevronLeft className="h-4 w-4" />
                    </button>
                    <span className="mx-2 text-xs font-medium min-w-[3rem] text-center">
                        {pageNumber} / {numPages || '-'}
                    </span>
                    <button 
                        onClick={() => setPageNumber(prev => Math.min(prev + 1, numPages))}
                        disabled={pageNumber >= numPages}
                        className="rounded p-1 hover:bg-white disabled:opacity-50 dark:hover:bg-gray-700"
                    >
                        <ChevronRight className="h-4 w-4" />
                    </button>
                </div>

                <div className="flex items-center gap-1">
                    <button onClick={() => setScale(s => Math.max(s - 0.1, 0.5))} className="rounded p-1.5 hover:bg-gray-100 dark:hover:bg-gray-800"><ZoomOut className="h-4 w-4" /></button>
                    <span className="text-xs font-medium w-12 text-center">{Math.round(scale * 100)}%</span>
                    <button onClick={() => setScale(s => Math.min(s + 0.1, 2.0))} className="rounded p-1.5 hover:bg-gray-100 dark:hover:bg-gray-800"><ZoomIn className="h-4 w-4" /></button>
                </div>
            </div>

            {/* Viewer Content */}
            <div className="flex-1 overflow-auto p-4 flex justify-center">
                {file ? (
                    <Document
                        file={file}
                        onLoadSuccess={onDocumentLoadSuccess}
                        className="shadow-xl"
                        loading={
                            <div className="flex flex-col items-center justify-center p-10 text-gray-400">
                                <div className="h-8 w-8 animate-spin rounded-full border-2 border-dashed border-scribehub-blue"></div>
                                <span className="mt-2 text-xs">Loading PDF...</span>
                            </div>
                        }
                    >
                        <Page 
                            pageNumber={pageNumber} 
                            scale={scale} 
                            renderTextLayer={true}
                            renderAnnotationLayer={true}
                            className="bg-white shadow-lg"
                        />
                    </Document>
                ) : (
                    <div className="flex flex-col items-center justify-center h-full text-gray-400">
                        <div className="mb-4 rounded-full bg-gray-200 p-6 dark:bg-gray-800">
                            <Upload className="h-8 w-8" />
                        </div>
                        <h3 className="text-lg font-bold text-gray-600 dark:text-gray-300">Upload a Research Paper</h3>
                        <p className="text-sm">Select a PDF file to start reading</p>
                        <label className="mt-6 cursor-pointer rounded-xl bg-scribehub-blue px-6 py-2.5 text-sm font-bold text-white transition-transform hover:scale-105 active:scale-95">
                            Browse Files
                            <input type="file" accept=".pdf" onChange={handleFileUpload} className="hidden" />
                        </label>
                    </div>
                )}
            </div>
        </div>
    );
}
