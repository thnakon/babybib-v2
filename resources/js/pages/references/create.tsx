import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/react';
import { ArrowLeft, Plus, Trash2, Save } from 'lucide-react';
import { useState } from 'react';
import { cn } from '@/lib/utils';
import { useLanguage } from '@/contexts/language-context';

// Translations
const translations = {
    en: {
        title: "Add Reference",
        subtitle: "Add a new bibliographic reference to your library",
        back: "Back to References",
        save: "Save Reference",
        cancel: "Cancel",
        // Form labels
        basicInfo: "Basic Information",
        titleLabel: "Title",
        titlePlaceholder: "Enter the title of the work",
        authorsLabel: "Authors",
        authorsPlaceholder: "Enter author name",
        addAuthor: "Add Author",
        typeLabel: "Reference Type",
        yearLabel: "Year",
        yearPlaceholder: "e.g., 2024",
        // Identifiers
        identifiers: "Identifiers",
        doiLabel: "DOI",
        doiPlaceholder: "e.g., 10.1000/xyz123",
        isbnLabel: "ISBN",
        isbnPlaceholder: "e.g., 978-3-16-148410-0",
        urlLabel: "URL",
        urlPlaceholder: "https://example.com/article",
        // Publication
        publication: "Publication Details",
        publisherLabel: "Publisher",
        publisherPlaceholder: "e.g., Academic Press",
        journalLabel: "Journal Name",
        journalPlaceholder: "e.g., Nature",
        volumeLabel: "Volume",
        issueLabel: "Issue",
        pagesLabel: "Pages",
        pagesPlaceholder: "e.g., 1-15",
        editionLabel: "Edition",
        // Additional
        additional: "Additional Information",
        abstractLabel: "Abstract",
        abstractPlaceholder: "Enter the abstract or summary...",
        notesLabel: "Notes",
        notesPlaceholder: "Your personal notes about this reference...",
        // Types
        types: {
            book: "Book",
            journal: "Journal Article",
            website: "Website",
            conference: "Conference Paper",
            thesis: "Thesis/Dissertation",
            report: "Report",
            other: "Other"
        }
    },
    th: {
        title: "เพิ่มรายการอ้างอิง",
        subtitle: "เพิ่มข้อมูลบรรณานุกรมใหม่เข้าสู่คลังของคุณ",
        back: "กลับไปยังรายการอ้างอิง",
        save: "บันทึกรายการอ้างอิง",
        cancel: "ยกเลิก",
        // Form labels
        basicInfo: "ข้อมูลพื้นฐาน",
        titleLabel: "ชื่อเรื่อง",
        titlePlaceholder: "กรอกชื่อเรื่องของผลงาน",
        authorsLabel: "ผู้แต่ง",
        authorsPlaceholder: "กรอกชื่อผู้แต่ง",
        addAuthor: "เพิ่มผู้แต่ง",
        typeLabel: "ประเภทรายการอ้างอิง",
        yearLabel: "ปี",
        yearPlaceholder: "เช่น 2567",
        // Identifiers
        identifiers: "ตัวระบุ",
        doiLabel: "DOI",
        doiPlaceholder: "เช่น 10.1000/xyz123",
        isbnLabel: "ISBN",
        isbnPlaceholder: "เช่น 978-3-16-148410-0",
        urlLabel: "URL",
        urlPlaceholder: "https://example.com/article",
        // Publication
        publication: "รายละเอียดการตีพิมพ์",
        publisherLabel: "สำนักพิมพ์",
        publisherPlaceholder: "เช่น สำนักพิมพ์จุฬาฯ",
        journalLabel: "ชื่อวารสาร",
        journalPlaceholder: "เช่น วารสารวิจัย",
        volumeLabel: "เล่มที่",
        issueLabel: "ฉบับที่",
        pagesLabel: "หน้า",
        pagesPlaceholder: "เช่น 1-15",
        editionLabel: "ครั้งที่พิมพ์",
        // Additional
        additional: "ข้อมูลเพิ่มเติม",
        abstractLabel: "บทคัดย่อ",
        abstractPlaceholder: "กรอกบทคัดย่อหรือสรุป...",
        notesLabel: "บันทึกส่วนตัว",
        notesPlaceholder: "บันทึกส่วนตัวเกี่ยวกับรายการอ้างอิงนี้...",
        // Types
        types: {
            book: "หนังสือ",
            journal: "บทความวารสาร",
            website: "เว็บไซต์",
            conference: "บทความการประชุมวิชาการ",
            thesis: "วิทยานิพนธ์/ดุษฎีนิพนธ์",
            report: "รายงาน",
            other: "อื่นๆ"
        }
    }
};

const referenceTypes = ['book', 'journal', 'website', 'conference', 'thesis', 'report', 'other'];

export default function ReferencesCreate() {
    const { language } = useLanguage();
    const t = translations[language];

    const { data, setData, post, processing, errors } = useForm({
        title: '',
        authors: [''],
        type: 'journal',
        year: '',
        doi: '',
        isbn: '',
        url: '',
        publisher: '',
        journal_name: '',
        volume: '',
        issue: '',
        pages: '',
        edition: '',
        abstract: '',
        notes: '',
        tags: [],
    });

    const breadcrumbs: BreadcrumbItem[] = [
        { title: language === 'en' ? 'References' : 'รายการอ้างอิง', href: '/references' },
        { title: t.title, href: '/references/create' },
    ];

    const addAuthor = () => {
        setData('authors', [...data.authors, '']);
    };

    const removeAuthor = (index: number) => {
        const newAuthors = data.authors.filter((_, i) => i !== index);
        setData('authors', newAuthors.length > 0 ? newAuthors : ['']);
    };

    const updateAuthor = (index: number, value: string) => {
        const newAuthors = [...data.authors];
        newAuthors[index] = value;
        setData('authors', newAuthors);
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        // Filter out empty authors
        const filteredAuthors = data.authors.filter(a => a.trim() !== '');
        setData('authors', filteredAuthors.length > 0 ? filteredAuthors : []);
        post('/references');
    };

    const inputClass = "h-11 w-full rounded-xl border border-gray-100 bg-white px-4 text-sm font-medium focus:border-scribehub-blue/50 focus:outline-none focus:ring-4 focus:ring-scribehub-blue/5 dark:border-gray-800 dark:bg-gray-900/50 dark:text-white";
    const labelClass = "block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2";

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t.title} />

            <div className="flex h-full flex-1 flex-col gap-6 bg-scribehub-paper/50 p-6 dark:bg-[#050505]/50 animate-in fade-in duration-700">
                {/* Header */}
                <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <Link
                            href="/references"
                            className="mb-2 inline-flex items-center gap-2 text-sm font-bold text-gray-500 transition-colors hover:text-scribehub-blue dark:text-gray-400"
                        >
                            <ArrowLeft className="h-4 w-4" />
                            {t.back}
                        </Link>
                        <h1 className="text-3xl font-extrabold text-scribehub-blue dark:text-white">{t.title}</h1>
                        <p className="mt-1 text-sm font-medium text-gray-500 dark:text-gray-400">{t.subtitle}</p>
                    </div>
                </div>

                {/* Form */}
                <form onSubmit={handleSubmit} className="space-y-8">
                    {/* Basic Information */}
                    <div className="rounded-3xl border border-white bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900/50">
                        <h2 className="mb-6 text-lg font-extrabold text-scribehub-blue dark:text-white">{t.basicInfo}</h2>
                        
                        <div className="space-y-5">
                            {/* Title */}
                            <div>
                                <label className={labelClass}>{t.titleLabel} *</label>
                                <input
                                    type="text"
                                    value={data.title}
                                    onChange={e => setData('title', e.target.value)}
                                    placeholder={t.titlePlaceholder}
                                    className={cn(inputClass, errors.title && 'border-red-500')}
                                    required
                                />
                                {errors.title && <p className="mt-1 text-xs text-red-500">{errors.title}</p>}
                            </div>

                            {/* Authors */}
                            <div>
                                <label className={labelClass}>{t.authorsLabel}</label>
                                <div className="space-y-2">
                                    {data.authors.map((author, index) => (
                                        <div key={index} className="flex gap-2">
                                            <input
                                                type="text"
                                                value={author}
                                                onChange={e => updateAuthor(index, e.target.value)}
                                                placeholder={t.authorsPlaceholder}
                                                className={inputClass}
                                            />
                                            {data.authors.length > 1 && (
                                                <button
                                                    type="button"
                                                    onClick={() => removeAuthor(index)}
                                                    className="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-red-100 text-red-500 transition-colors hover:bg-red-50 dark:border-red-900/30 dark:hover:bg-red-900/20"
                                                >
                                                    <Trash2 className="h-4 w-4" />
                                                </button>
                                            )}
                                        </div>
                                    ))}
                                </div>
                                <button
                                    type="button"
                                    onClick={addAuthor}
                                    className="mt-3 inline-flex items-center gap-2 text-sm font-bold text-scribehub-blue transition-colors hover:opacity-80"
                                >
                                    <Plus className="h-4 w-4" />
                                    {t.addAuthor}
                                </button>
                            </div>

                            {/* Type and Year */}
                            <div className="grid gap-5 md:grid-cols-2">
                                <div>
                                    <label className={labelClass}>{t.typeLabel} *</label>
                                    <select
                                        value={data.type}
                                        onChange={e => setData('type', e.target.value)}
                                        className={inputClass}
                                    >
                                        {referenceTypes.map(type => (
                                            <option key={type} value={type}>
                                                {t.types[type as keyof typeof t.types]}
                                            </option>
                                        ))}
                                    </select>
                                </div>
                                <div>
                                    <label className={labelClass}>{t.yearLabel}</label>
                                    <input
                                        type="text"
                                        value={data.year}
                                        onChange={e => setData('year', e.target.value)}
                                        placeholder={t.yearPlaceholder}
                                        className={inputClass}
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Identifiers */}
                    <div className="rounded-3xl border border-white bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900/50">
                        <h2 className="mb-6 text-lg font-extrabold text-scribehub-blue dark:text-white">{t.identifiers}</h2>
                        
                        <div className="grid gap-5 md:grid-cols-3">
                            <div>
                                <label className={labelClass}>{t.doiLabel}</label>
                                <input
                                    type="text"
                                    value={data.doi}
                                    onChange={e => setData('doi', e.target.value)}
                                    placeholder={t.doiPlaceholder}
                                    className={inputClass}
                                />
                            </div>
                            <div>
                                <label className={labelClass}>{t.isbnLabel}</label>
                                <input
                                    type="text"
                                    value={data.isbn}
                                    onChange={e => setData('isbn', e.target.value)}
                                    placeholder={t.isbnPlaceholder}
                                    className={inputClass}
                                />
                            </div>
                            <div>
                                <label className={labelClass}>{t.urlLabel}</label>
                                <input
                                    type="url"
                                    value={data.url}
                                    onChange={e => setData('url', e.target.value)}
                                    placeholder={t.urlPlaceholder}
                                    className={inputClass}
                                />
                            </div>
                        </div>
                    </div>

                    {/* Publication Details */}
                    <div className="rounded-3xl border border-white bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900/50">
                        <h2 className="mb-6 text-lg font-extrabold text-scribehub-blue dark:text-white">{t.publication}</h2>
                        
                        <div className="grid gap-5 md:grid-cols-2">
                            <div>
                                <label className={labelClass}>{t.publisherLabel}</label>
                                <input
                                    type="text"
                                    value={data.publisher}
                                    onChange={e => setData('publisher', e.target.value)}
                                    placeholder={t.publisherPlaceholder}
                                    className={inputClass}
                                />
                            </div>
                            <div>
                                <label className={labelClass}>{t.journalLabel}</label>
                                <input
                                    type="text"
                                    value={data.journal_name}
                                    onChange={e => setData('journal_name', e.target.value)}
                                    placeholder={t.journalPlaceholder}
                                    className={inputClass}
                                />
                            </div>
                        </div>
                        <div className="mt-5 grid gap-5 md:grid-cols-4">
                            <div>
                                <label className={labelClass}>{t.volumeLabel}</label>
                                <input
                                    type="text"
                                    value={data.volume}
                                    onChange={e => setData('volume', e.target.value)}
                                    className={inputClass}
                                />
                            </div>
                            <div>
                                <label className={labelClass}>{t.issueLabel}</label>
                                <input
                                    type="text"
                                    value={data.issue}
                                    onChange={e => setData('issue', e.target.value)}
                                    className={inputClass}
                                />
                            </div>
                            <div>
                                <label className={labelClass}>{t.pagesLabel}</label>
                                <input
                                    type="text"
                                    value={data.pages}
                                    onChange={e => setData('pages', e.target.value)}
                                    placeholder={t.pagesPlaceholder}
                                    className={inputClass}
                                />
                            </div>
                            <div>
                                <label className={labelClass}>{t.editionLabel}</label>
                                <input
                                    type="text"
                                    value={data.edition}
                                    onChange={e => setData('edition', e.target.value)}
                                    className={inputClass}
                                />
                            </div>
                        </div>
                    </div>

                    {/* Additional Information */}
                    <div className="rounded-3xl border border-white bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900/50">
                        <h2 className="mb-6 text-lg font-extrabold text-scribehub-blue dark:text-white">{t.additional}</h2>
                        
                        <div className="space-y-5">
                            <div>
                                <label className={labelClass}>{t.abstractLabel}</label>
                                <textarea
                                    value={data.abstract}
                                    onChange={e => setData('abstract', e.target.value)}
                                    placeholder={t.abstractPlaceholder}
                                    rows={4}
                                    className={cn(inputClass, 'h-auto py-3')}
                                />
                            </div>
                            <div>
                                <label className={labelClass}>{t.notesLabel}</label>
                                <textarea
                                    value={data.notes}
                                    onChange={e => setData('notes', e.target.value)}
                                    placeholder={t.notesPlaceholder}
                                    rows={3}
                                    className={cn(inputClass, 'h-auto py-3')}
                                />
                            </div>
                        </div>
                    </div>

                    {/* Submit */}
                    <div className="flex items-center justify-end gap-4">
                        <Link
                            href="/references"
                            className="rounded-xl border border-gray-200 px-6 py-3 text-sm font-bold text-gray-600 transition-colors hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800"
                        >
                            {t.cancel}
                        </Link>
                        <button
                            type="submit"
                            disabled={processing}
                            className="inline-flex items-center gap-2 rounded-xl bg-scribehub-blue px-8 py-3 text-sm font-bold text-white shadow-lg shadow-blue-900/20 transition-all hover:opacity-90 active:scale-95 disabled:opacity-50"
                        >
                            <Save className="h-4 w-4" />
                            {t.save}
                        </button>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
}
