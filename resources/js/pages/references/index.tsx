import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { Head, Link, router, usePage } from '@inertiajs/react';
import { 
    Plus, BookOpen, FileText, Globe, Calendar, MoreVertical, Edit, Trash2, Eye, 
    Filter, Download, Link as LinkIcon, HelpCircle, FolderPlus, Upload, FilePlus,
    Settings, Palette, MoreHorizontal, Folder, Loader2, Sparkles, GraduationCap, 
    Microscope, Library, FlaskConical, Beaker, PenTool, BookMarked, Briefcase, 
    Heart, Star, Cloud, ChevronDown, List, AlignLeft, Quote, Copy, Search, Check, Code, UserPlus, Globe2, GripVertical
} from 'lucide-react';
import { Checkbox } from '@/components/ui/checkbox';
import { 
    Select, 
    SelectContent, 
    SelectItem, 
    SelectTrigger, 
    SelectValue 
} from '@/components/ui/select';
import { useState, FormEvent, useEffect, useRef } from 'react';
import { cn } from '@/lib/utils';
import { useLanguage } from '@/contexts/language-context';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogFooter } from '@/components/ui/dialog';
import { useForm } from '@inertiajs/react';
import { toast } from 'sonner';
import { Input } from '@/components/ui/input';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import {
    DndContext, 
    closestCenter,
    KeyboardSensor,
    PointerSensor,
    useSensor,
    useSensors,
    DragEndEvent,
} from '@dnd-kit/core';
import {
    arrayMove,
    SortableContext,
    sortableKeyboardCoordinates,
    verticalListSortingStrategy,
    useSortable,
} from '@dnd-kit/sortable';
import { CSS } from '@dnd-kit/utilities';
import { restrictToVerticalAxis, restrictToWindowEdges } from '@dnd-kit/modifiers';
import { Label } from '@/components/ui/label';

// Types
interface Reference {
    id: number;
    title: string;
    authors: string[] | null;
    type: string;
    year: string | null;
    year_suffix: string | null;
    doi: string | null;
    isbn: string | null;
    url: string | null;
    publisher: string | null;
    journal_name: string | null;
    created_at: string;
    citation: string;
    sort_order: number;
}

interface FolderItem {
    id: number;
    name: string;
    color: string | null;
    references_count?: number;
}

interface Project {
    id: number;
    name: string;
    description: string | null;
    color: string;
    icon: string | null;
    references_count: number;
    folders: FolderItem[];
}

interface Props {
    references: Reference[];
    projects: Project[];
    selectedProjectId: number | null;
}

// Translations
const translations = {
    en: {
        title: "My References",
        subtitle: "Manage your bibliographic references",
        addNew: "Add Reference",
        search: "Search references...",
        noReferences: "No references yet",
        noReferencesDesc: "Start building your library by adding your first reference.",
        addFirst: "Add Your First Reference",
        type: "Type",
        year: "Year",
        actions: "Actions",
        edit: "Edit",
        confirmDelete: "Are you sure you want to delete this reference?",
        total: "Total",
        references: "References",
        export: "Export",
        copy: "Copy",
        exportBibtex: "Export BibTeX",
        exportRis: "Export RIS",
        types: {
            book: "Book",
            journal: "Journal",
            website: "Website",
            conference: "Conference",
            thesis: "Thesis",
            report: "Report",
            other: "Other"
        },
        bibliography: "Bibliography",
        inText: "In-text Citation",
        viewCitation: "View Citation",
        copyCitation: "Copy Citation",
        quickCite: "Quick Cite",
        websites: "Websites",
        books: "Books",
        articles: "Articles",
        more: "More",
        cite: "Cite",
        pastePlaceholder: "Paste URL, DOI, or ISBN to cite immediately...",
        placeholderWebsites: "Paste URL of the website you want to cite",
        placeholderBooks: "Search for title, author name, ISBN",
        placeholderArticles: "Search for title, author name, or identifier (DOI, PMID, ...)",
        help: "Help",
        new: "New",
        newSource: "New Source",
        newProject: "New Project",
        newFolder: "New Folder",
        uploadFile: "Upload File",
        rename: "Rename",
        colorAndEmoji: "Color and Emoji",
        addFolder: "Add Folder",
        delete: "Delete",
        projectsTitle: "Projects",
        allReferences: "All References",
        projectName: "Project Name",
        projectDescription: "Description (Optional)",
        createProject: "Create Project",
        cancel: "Cancel",
        projectCreated: "Project created successfully",
        addField: "Add Field",
        primaryLanguage: "Primary Language",
        resourceType: "Resource Type",
        isOnline: "This is an online source",
        whatToCite: "What do you want to cite?",
        entryTitle: "Entry Title",
        whoCreated: "Who created it?",
        addContributor: "Add Contributor",
        when: "When?",
        yearPublished: "Year Published",
        role: "Role",
        firstName: "First Name",
        middleName: "Middle",
        lastName: "Last Name",
        preview: "Live Preview",
        addToProject: "Add to Project",
        searchFields: "Search fields...",
        workspace: "Workspace",
        noResults: "No results found for your search",
        toasts: {
            importSuccess: "Imported {count} references successfully",
            importNoValid: "No valid references found in file",
            importFailed: "Failed to import file. Please check the format.",
            orderSaved: "Order saved",
            projectUpdated: "Project updated successfully",
            projectDeleted: "Project deleted successfully",
            folderCreated: "Folder created successfully",
            copiedToClipboard: "References copied to clipboard",
            exporting: "Exporting as {format}...",
            folderUpdated: "Folder updated successfully",
            folderDeleted: "Folder deleted successfully",
            addedToProject: "Successfully added to project",
            sourceAdded: "Source added successfully",
            sourceFailed: "Failed to add source",
            citationCopied: "Citation copied",
            referenceDeleted: "Reference deleted"
        }
    },
    th: {
        title: "รายการอ้างอิงของฉัน",
        subtitle: "จัดการข้อมูลบรรณานุกรมของคุณ",
        addNew: "เพิ่มรายการอ้างอิง",
        search: "ค้นหารายการอ้างอิง...",
        noReferences: "ยังไม่มีรายการอ้างอิง",
        noReferencesDesc: "เริ่มต้นสร้างคลังข้อมูลของคุณโดยเพิ่มรายการอ้างอิงแรก",
        addFirst: "เพิ่มรายการอ้างอิงแรกของคุณ",
        type: "ประเภท",
        year: "ปี",
        actions: "การดำเนินการ",
        edit: "แก้ไข",
        confirmDelete: "คุณแน่ใจหรือไม่ว่าต้องการลบรายการอ้างอิงนี้?",
        total: "ทั้งหมด",
        references: "บรรณานุกรม",
        export: "ส่งออก",
        copy: "คัดลอก",
        exportBibtex: "ส่งออก BibTeX",
        exportRis: "ส่งออก RIS",
        types: {
            book: "หนังสือ",
            journal: "วารสาร",
            website: "เว็บไซต์",
            conference: "การประชุมวิชาการ",
            thesis: "วิทยานิพนธ์",
            report: "รายงาน",
            other: "อื่นๆ"
        },
        bibliography: "บรรณานุกรม",
        inText: "การอ้างอิงในเนื้อหา",
        viewCitation: "ดูการอ้างอิง",
        copyCitation: "คัดลอกการอ้างอิง",
        quickCite: "อ้างอิงด่วน",
        websites: "เว็บไซต์",
        books: "หนังสือ",
        articles: "บทความ",
        more: "เพิ่มเติม",
        cite: "อ้างอิง",
        pastePlaceholder: "วาง URL, DOI หรือ ISBN เพื่อแปลกการอ้างอิงทันที...",
        placeholderWebsites: "วาง URL ของเว็บไซต์ที่คุณต้องการอ้างอิง",
        placeholderBooks: "ค้นหาด้วย ชื่อเรื่อง, ชื่อผู้แต่ง หรือ ISBN",
        placeholderArticles: "ค้นหาด้วย ชื่อเรื่อง, ชื่อผู้แต่ง หรือ รหัสอ้างอิง (DOI, PMID, ...)",
        help: "ช่วยเหลือ",
        new: "เพิ่มใหม่",
        newSource: "แหล่งข้อมูลใหม่",
        newProject: "โปรเจกต์ใหม่",
        newFolder: "โฟลเดอร์ใหม่",
        uploadFile: "อัปโหลดไฟล์",
        rename: "เปลี่ยนชื่อ",
        colorAndEmoji: "สีและอิโมจิ",
        addFolder: "เพิ่มโฟลเดอร์",
        delete: "ลบ",
        projectsTitle: "โปรเจกต์",
        allReferences: "รายการอ้างอิงทั้งหมด",
        projectName: "ชื่อโปรเจกต์",
        projectDescription: "คำอธิบาย (ไม่บังคับ)",
        createProject: "สร้างโปรเจกต์",
        cancel: "ยกเลิก",
        projectCreated: "สร้างโปรเจกต์สำเร็จแล้ว",
        addField: "เพิ่มฟิลด์",
        primaryLanguage: "ภาษาหลักของบรรณานุกรม",
        resourceType: "ประเภททรัพยากร",
        isOnline: "เป็นแหล่งข้อมูลออนไลน์",
        whatToCite: "คุณต้องการอ้างอิงข้อมูลอะไร?",
        entryTitle: "ชื่อเรื่อง/ชื่อรายการ",
        whoCreated: "ใครเป็นผู้แต่ง/สร้าง?",
        addContributor: "เพิ่มผู้แต่ง/ผู้ร่วมงาน",
        when: "ระบุช่วงเวลา?",
        yearPublished: "ปีที่พิมพ์ (พ.ศ./ค.ศ.)",
        role: "บทบาท",
        firstName: "ชื่อต้น",
        middleName: "ชื่อกลาง",
        lastName: "นามสกุล",
        preview: "ตัวอย่างบรรณานุกรม",
        addToProject: "บันทึกลงในโปรเจกต์",
        searchFields: "ค้นหาฟิลด์...",
        workspace: "พื้นที่ทำงาน",
        noResults: "ไม่พบข้อมูลที่ค้นหา",
        toasts: {
            importSuccess: "นำเข้า {count} รายการอ้างอิงสำเร็จแล้ว",
            importNoValid: "ไม่พบรายการอ้างอิงที่ถูกต้องในไฟล์",
            importFailed: "นำเข้าไฟล์ล้มเหลว กรุณาตรวจสอบรูปแบบไฟล์",
            orderSaved: "บันทึกการจัดเรียงแล้ว",
            projectUpdated: "อัปเดตโปรเจกต์สำเร็จแล้ว",
            projectDeleted: "ลบโปรเจกต์สำเร็จแล้ว",
            folderCreated: "สร้างโฟลเดอร์สำเร็จแล้ว",
            copiedToClipboard: "คัดลอกรายการอ้างอิงไปยังคลิปบอร์ดแล้ว",
            exporting: "กำลังส่งออกเป็น {format}...",
            folderUpdated: "อัปเดตโฟลเดอร์สำเร็จแล้ว",
            folderDeleted: "ลบโฟลเดอร์สำเร็จแล้ว",
            addedToProject: "เพิ่มลงในโปรเจกต์สำเร็จแล้ว",
            sourceAdded: "เพิ่มแหล่งข้อมูลสำเร็จแล้ว",
            sourceFailed: "เพิ่มแหล่งข้อมูลล้มเหลว",
            citationCopied: "คัดลอกบรรณานุกรมแล้ว",
            referenceDeleted: "ลบรายการอ้างอิงแล้ว"
        }
    }
};

const citationStyles = [
    { id: 'apa7', name: 'APA', version: '7th edition' },
    { id: 'mla9', name: 'MLA', version: '9th edition' },
    { id: 'chicago17', name: 'Chicago', version: '17th (Author-Date)' },
    { id: 'harvard', name: 'Harvard', version: 'Latest' },
    { id: 'vancouver', name: 'Vancouver', version: 'ICMJE' },
    { id: 'ieee', name: 'IEEE', version: 'Standard' },
    { id: 'nature', name: 'Nature', version: 'Journal' },
    { id: 'science', name: 'Science', version: 'Journal' },
    { id: 'oxford', name: 'Oxford', version: 'Standard' },
    { id: 'turabian', name: 'Turabian', version: '9th edition' },
];
const projectColors = [
    '#3B82F6', // Blue
    '#10B981', // Emerald
    '#F59E0B', // Amber
    '#EF4444', // Red
    '#8B5CF6', // Violet
    '#EC4899', // Pink
    '#64748B', // Slate
];

const availableIcons = [
    { name: 'Folder', icon: Folder },
    { name: 'BookOpen', icon: BookOpen },
    { name: 'GraduationCap', icon: GraduationCap },
    { name: 'Microscope', icon: Microscope },
    { name: 'Library', icon: Library },
    { name: 'FlaskConical', icon: FlaskConical },
    { name: 'Beaker', icon: Beaker },
    { name: 'PenTool', icon: PenTool },
    { name: 'BookMarked', icon: BookMarked },
    { name: 'Briefcase', icon: Briefcase },
    { name: 'Heart', icon: Heart },
    { name: 'Star', icon: Star },
    { name: 'Cloud', icon: Cloud },
    { name: 'Sparkles', icon: Sparkles },
];

const sourceTypes = [
    { id: 'book', name: 'Book', thName: 'หนังสือ', icon: BookOpen },
    { id: 'journal', name: 'Journal Article', thName: 'บทความวารสาร', icon: FileText },
    { id: 'website', name: 'Website', thName: 'เว็บไซต์', icon: Globe },
    { id: 'conference', name: 'Conference Paper', thName: 'การประชุมวิชาการ', icon: Calendar },
    { id: 'thesis', name: 'Thesis', thName: 'วิทยานิพนธ์', icon: GraduationCap },
    { id: 'report', name: 'Report', thName: 'รายงาน', icon: Briefcase },
    { id: 'other', name: 'Other', thName: 'อื่นๆ', icon: MoreHorizontal },
];

const availableFields = [
    { id: 'publisher', name: 'Publisher', thName: 'สำนักพิมพ์' },
    { id: 'publisherPlace', name: 'Publisher Place', thName: 'สถานที่พิมพ์' },
    { id: 'isbn', name: 'ISBN', thName: 'ISBN' },
    { id: 'issn', name: 'ISSN', thName: 'ISSN' },
    { id: 'doi', name: 'DOI', thName: 'DOI' },
    { id: 'edition', name: 'Edition', thName: 'ฉบับที่/พิมพ์ครั้งที่' },
    { id: 'volume', name: 'Volume', thName: 'เล่มที่' },
    { id: 'pageCount', name: 'Page Count', thName: 'จำนวนหน้า' },
    { id: 'url', name: 'URL', thName: 'URL' },
    { id: 'dateAccessed', name: 'Date Accessed', thName: 'วันที่เข้าถึง' },
    { id: 'conferenceDate', name: 'Conference Date', thName: 'วันที่ประชุม' },
    { id: 'originalTitle', name: 'Original Title', thName: 'ชื่อเรื่องเดิม' },
    { id: 'library', name: 'Library', thName: 'ห้องสมุด' },
    { id: 'database', name: 'Database', thName: 'ฐานข้อมูล' },
    { id: 'language', name: 'Language', thName: 'ภาษา' },
    { id: 'originalDate', name: 'Original Publication Date', thName: 'วันที่พิมพ์ครั้งแรก' },
];

const authorRoles = [
    { id: 'author', name: 'Author', thName: 'ผู้แต่ง' },
    { id: 'editor', name: 'Editor', thName: 'บรรณาธิการ' },
    { id: 'translator', name: 'Translator', thName: 'ผู้แปล' },
    { id: 'contributor', name: 'Contributor', thName: 'ผู้ร่วมงาน' },
];

const IconRenderer = ({ iconName, className, style }: { iconName: string | null, className?: string, style?: any }) => {
    const iconObj = availableIcons.find(i => i.name === iconName) || availableIcons[0];
    const IconComponent = iconObj.icon;
    return <IconComponent className={className} style={style} />;
};

// Sortable Project Item Component
function SortableProjectItem({ 
    project, 
    selectedProjectId, 
    expandedProjects, 
    toggleProjectExpand, 
    setProjectMenuOpen, 
    projectMenuOpen,
    folderMenuOpen,
    setFolderMenuOpen,
    setEditingFolder,
    editFolderForm,
    setIsEditFolderOpen,
    setIsDeleteFolderConfirmOpen,
    t,
    language,
    setEditingProject,
    editForm,
    setIsEditProjectOpen,
    setTargetProject,
    folderForm,
    setIsAddFolderOpen,
    setIsDeleteProjectOpen,
    onUploadClick
}: any) {
    const {
        attributes,
        listeners,
        setNodeRef,
        transform,
        transition,
        isDragging
    } = useSortable({ id: project.id });

    const style = {
        transform: CSS.Transform.toString(transform),
        transition,
        zIndex: isDragging ? 50 : undefined,
    };

    return (
        <div 
            ref={setNodeRef} 
            style={style} 
            className={cn(
                "group relative animate-in fade-in duration-300",
                isDragging && "opacity-50 grayscale scale-[0.98] ring-2 ring-scribehub-blue/20 rounded-xl"
            )}
        >
            <div className="flex items-center">
                {/* Drag Handle - Visible on Hover */}
                <div 
                    {...attributes} 
                    {...listeners}
                    className="absolute -left-2 top-1/2 -translate-y-1/2 p-1 opacity-0 group-hover:opacity-100 cursor-grab active:cursor-grabbing text-gray-300 hover:text-gray-500 transition-all z-20"
                >
                    <GripVertical className="h-4 w-4" />
                </div>

                <Link
                    href={`/references?project_id=${project.id}`}
                    className={cn(
                        "flex flex-1 items-center justify-between rounded-xl px-4 py-3 text-xs font-bold transition-all ml-1",
                        selectedProjectId === project.id ? "bg-white text-scribehub-blue shadow-sm dark:bg-gray-800 dark:text-white" : "text-gray-500 hover:bg-gray-50/50 dark:text-gray-400 dark:hover:bg-gray-800/50"
                    )}
                >
                    <div className="flex items-center gap-3 min-w-0">
                        {project.folders && project.folders.length > 0 && (
                            <button 
                                onClick={(e) => {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    toggleProjectExpand(project.id);
                                }}
                                className={cn(
                                    "transition-transform duration-200",
                                    expandedProjects.includes(project.id) ? "rotate-180" : ""
                                )}
                            >
                                <ChevronDown className="h-3 w-3 text-gray-400" />
                            </button>
                        )}
                        <div className="h-4 w-4 shrink-0 flex items-center justify-center">
                            <IconRenderer iconName={project.icon} className="h-4 w-4" style={{ color: project.color }} />
                        </div>
                        <span className="truncate">{project.name}</span>
                    </div>
                    <div className="flex items-center gap-2">
                        <span className="text-[10px] text-gray-400 font-medium">{project.references_count}</span>
                        <button 
                            onClick={(e) => {
                                e.preventDefault();
                                e.stopPropagation();
                                setProjectMenuOpen(projectMenuOpen === project.id ? null : project.id);
                            }}
                            className="opacity-0 group-hover:opacity-100 p-1 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-md transition-all relative z-10"
                        >
                            <MoreHorizontal className="h-3 w-3" />
                        </button>
                    </div>
                </Link>
            </div>

            {expandedProjects.includes(project.id) && project.folders && (
                <div className="ml-9 border-l-2 border-gray-100 dark:border-gray-800 space-y-1 mb-2 animate-in slide-in-from-top-2 duration-200">
                    {project.folders.map((folder: any) => (
                        <div key={folder.id} className="group/folder relative">
                            <Link
                                href={`/references?project_id=${project.id}&folder_id=${folder.id}`}
                                className={cn(
                                    "flex flex-1 items-center justify-between px-4 py-1.5 text-[11px] font-bold transition-all rounded-lg mx-1",
                                    (window.location.search.includes(`folder_id=${folder.id}`)) ? "bg-scribehub-blue/5 text-scribehub-blue" : "text-gray-400 hover:text-scribehub-blue"
                                )}
                            >
                                <div className="flex items-center gap-2 truncate">
                                    <Folder className={cn("h-3 w-3", (window.location.search.includes(`folder_id=${folder.id}`)) ? "text-scribehub-blue" : "text-gray-400")} />
                                    <span className="truncate">{folder.name}</span>
                                    {folder.references_count !== undefined && (
                                        <span className="text-[9px] text-gray-300 font-medium ml-1">({folder.references_count})</span>
                                    )}
                                </div>
                                <button 
                                    onClick={(e) => {
                                        e.preventDefault();
                                        e.stopPropagation();
                                        setFolderMenuOpen(folderMenuOpen === folder.id ? null : folder.id);
                                    }}
                                    className="opacity-0 group-hover/folder:opacity-100 p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-all relative z-10"
                                >
                                    <MoreHorizontal className="h-3 w-3" />
                                </button>
                            </Link>

                            {folderMenuOpen === folder.id && (
                                <>
                                    <div className="fixed inset-0 z-20" onClick={() => setFolderMenuOpen(null)}></div>
                                    <div className="absolute left-full top-0 z-30 ml-2 w-40 rounded-xl border border-gray-100 bg-white py-1.5 shadow-xl dark:border-gray-800 dark:bg-gray-900 animate-in fade-in slide-in-from-left-2 duration-200">
                                        <button 
                                            onClick={() => {
                                                setFolderMenuOpen(null);
                                                setEditingFolder(folder);
                                                editFolderForm.setData('name', folder.name);
                                                setIsEditFolderOpen(true);
                                            }}
                                            className="flex w-full items-center gap-2 px-3 py-1.5 text-[10px] font-bold text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-800"
                                        >
                                            <Edit className="h-3.5 w-3.5 text-blue-500" /> {t.rename}
                                        </button>
                                        <button 
                                            onClick={() => {
                                                setFolderMenuOpen(null);
                                                setEditingFolder(folder);
                                                setIsDeleteFolderConfirmOpen(true);
                                            }}
                                            className="flex w-full items-center gap-2 px-3 py-1.5 text-[10px] font-bold text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20"
                                        >
                                            <Trash2 className="h-3.5 w-3.5 text-red-500" /> {t.delete}
                                        </button>
                                    </div>
                                </>
                            )}
                        </div>
                    ))}
                </div>
            )}

            {projectMenuOpen === project.id && (
                <>
                    <div className="fixed inset-0 z-30" onClick={() => setProjectMenuOpen(null)}></div>
                    <div className="absolute left-4 top-10 z-40 w-48 rounded-xl border border-gray-100 bg-white py-2 shadow-2xl dark:border-gray-800 dark:bg-gray-900 animate-in fade-in zoom-in-95 duration-100">
                        <button 
                            onClick={() => {
                                setEditingProject(project);
                                editForm.setData({
                                    name: project.name,
                                    description: project.description || '',
                                    color: project.color,
                                    icon: project.icon || 'Folder',
                                });
                                setIsEditProjectOpen(true);
                                setProjectMenuOpen(null);
                            }}
                            className="flex w-full items-center gap-3 px-4 py-2 text-[11px] font-bold text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-800"
                        >
                            <Edit className="h-4 w-4 text-blue-500" /> {t.rename}
                        </button>
                        <button 
                            onClick={() => {
                                setEditingProject(project);
                                editForm.setData({
                                    name: project.name,
                                    description: project.description || '',
                                    color: project.color,
                                    icon: project.icon || 'Folder',
                                });
                                setIsEditProjectOpen(true);
                                setProjectMenuOpen(null);
                            }}
                            className="flex w-full items-center gap-3 px-4 py-2 text-[11px] font-bold text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-800"
                        >
                            <Palette className="h-4 w-4 text-purple-500" /> {t.colorAndEmoji}
                        </button>
                        <button 
                            onClick={() => {
                                setTargetProject(project);
                                folderForm.setData('project_id', project.id as any);
                                setIsAddFolderOpen(true);
                                setProjectMenuOpen(null);
                            }}
                            className="flex w-full items-center gap-3 px-4 py-2 text-[11px] font-bold text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-800"
                        >
                            <FolderPlus className="h-4 w-4 text-blue-400" /> {t.addFolder}
                        </button>
                        <button 
                            onClick={() => {
                                onUploadClick(project.id);
                                setProjectMenuOpen(null);
                            }}
                            className="flex w-full items-center gap-3 px-4 py-2 text-[11px] font-bold text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-800"
                        >
                            <Upload className="h-4 w-4 text-orange-500" /> {t.uploadFile}
                        </button>
                        <div className="my-1 border-t border-gray-100 dark:border-gray-800"></div>
                        <button 
                            onClick={() => {
                                setEditingProject(project);
                                setIsDeleteProjectOpen(true);
                                setProjectMenuOpen(null);
                            }}
                            className="flex w-full items-center gap-3 px-4 py-2 text-[11px] font-bold text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20"
                        >
                            <Trash2 className="h-4 w-4 text-red-500" /> {t.delete}
                        </button>
                    </div>
                </>
            )}
        </div>
    );
}

// Sortable Reference Item Component
function SortableReferenceItem({ 
    reference, 
    handleDeleteClick, 
    handleEditClick, 
    onCopy, 
    handleViewCitation,
    viewMode = 'citation',
    isNew = false,
    t 
}: any) {
    const {
        attributes,
        listeners,
        setNodeRef,
        transform,
        transition,
        isDragging
    } = useSortable({ id: reference.id });

    const style = {
        transform: CSS.Transform.toString(transform),
        transition,
        zIndex: isDragging ? 50 : undefined,
    };

    const [copied, setCopied] = useState(false);

    // Helper to get structured data for non-citation views
    const getMetadata = () => {
        const title = reference.title;
        const authors = reference.authors ? (Array.isArray(reference.authors) ? reference.authors.join(', ') : reference.authors) : 'Unknown Author';
        let year = reference.year || 'n.d.';
        if (reference.year && reference.year_suffix) {
            year += reference.year_suffix;
        }
        const source = reference.journal_name || reference.publisher || reference.url || '';
        return { title, authors, year, source };
    };

    const metadata = getMetadata();

    return (
        <div 
            ref={setNodeRef} 
            style={style} 
            className={cn(
                "group relative transition-all rounded-xl",
                viewMode === 'compact' ? "py-2 px-2 -mx-2" : "pl-8 pr-4 py-3 -mx-4",
                isDragging ? "opacity-50 grayscale bg-gray-50 dark:bg-gray-800" : "hover:bg-gray-100/50 dark:hover:bg-gray-800/50",
                isNew && "bg-scribehub-blue/5 border-2 border-scribehub-blue/20 ring-4 ring-scribehub-blue/5 animate-in fade-in zoom-in-95 duration-700"
            )}
        >
            <div className={cn("flex gap-4", viewMode === 'compact' ? "items-center" : "items-start")}>
                {/* Drag Handle - Absolute Left */}
                <div 
                    {...attributes} 
                    {...listeners}
                    className={cn(
                        "p-1 opacity-0 group-hover:opacity-100 cursor-grab active:cursor-grabbing text-gray-300 hover:text-gray-500 transition-all shrink-0",
                        viewMode === 'compact' ? "relative" : "absolute left-2 top-3"
                    )}
                >
                    <GripVertical className="h-4 w-4" />
                </div>

                {/* Content based on View Mode */}
                <div className="flex-1 min-w-0 flex flex-col gap-2">
                    {viewMode === 'citation' && (
                        <div 
                            className="text-[15px] leading-relaxed text-gray-900 dark:text-gray-100 font-serif"
                            style={{ paddingLeft: '2em', textIndent: '-2em' }}
                            dangerouslySetInnerHTML={{ __html: reference.citation || reference.title }}
                        />
                    )}

                    {viewMode === 'standard' && (
                         <div className="flex flex-col gap-1">
                            <h4 className="font-bold text-gray-900 dark:text-gray-100 text-[15px] leading-snug">{metadata.title}</h4>
                            <div className="text-sm text-gray-600 dark:text-gray-400 flex flex-wrap gap-x-2 items-center">
                                <span>{metadata.authors}</span>
                                <span className="h-1 w-1 rounded-full bg-gray-300 dark:bg-gray-600"/>
                                <span>{metadata.year}</span>
                                {metadata.source && (
                                    <>
                                        <span className="h-1 w-1 rounded-full bg-gray-300 dark:bg-gray-600"/>
                                        <span className="italic">{metadata.source}</span>
                                    </>
                                )}
                            </div>
                         </div>
                    )}

                    {viewMode === 'compact' && (
                        <div className="flex items-center gap-4 text-sm">
                            <span className="font-semibold text-gray-900 dark:text-gray-100 truncate min-w-[200px]">{metadata.title}</span>
                            <span className="text-gray-500 truncate">{metadata.authors}</span>
                            <span className="text-gray-400 shrink-0 ml-auto">{metadata.year}</span>
                        </div>
                    )}
                    
                    {/* Actions - Below Content (Only for Standard and Citation views, inline for Compact maybe? or keep consistent) */}
                    {viewMode !== 'compact' && (
                    <div className={cn("flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-all", viewMode === 'citation' ? "pl-[2em]" : "")}>
                        <button 
                            onClick={() => handleViewCitation(reference)}
                            className="flex items-center gap-1.5 px-2 py-1 text-[11px] font-bold uppercase tracking-wider text-gray-400 hover:text-scribehub-blue hover:bg-scribehub-blue/5 rounded-md transition-all"
                            title={t.viewCitation}
                        >
                            <Quote className="h-3 w-3" />
                            {t.view}
                        </button>
                        <div className="h-3 w-px bg-gray-200 dark:bg-gray-700 mx-1"></div>
                        <button 
                            onClick={() => {
                                onCopy(reference);
                                setCopied(true);
                                setTimeout(() => setCopied(false), 2000);
                            }}
                            className={cn(
                                "flex items-center gap-1.5 px-2 py-1 text-[11px] font-bold uppercase tracking-wider rounded-md transition-all",
                                copied ? "text-emerald-600 bg-emerald-50" : "text-gray-400 hover:text-emerald-600 hover:bg-emerald-50"
                            )}
                            title={t.copyCitation}
                        >
                            {copied ? <Check className="h-3 w-3" /> : <Copy className="h-3 w-3" />}
                            {copied ? 'Copied' : t.copy}
                        </button>
                        <div className="h-3 w-px bg-gray-200 dark:bg-gray-700 mx-1"></div>
                        <button 
                            onClick={() => handleEditClick(reference)}
                            className="flex items-center gap-1.5 px-2 py-1 text-[11px] font-bold uppercase tracking-wider text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-all"
                            title={t.edit}
                        >
                            <Edit className="h-3 w-3" />
                            {t.edit}
                        </button>
                        <div className="h-3 w-px bg-gray-200 dark:bg-gray-700 mx-1"></div>
                        <button 
                            onClick={() => handleDeleteClick(reference)}
                            className="flex items-center gap-1.5 px-2 py-1 text-[11px] font-bold uppercase tracking-wider text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-md transition-all"
                            title={t.delete}
                        >
                            <Trash2 className="h-3 w-3" />
                            {t.delete}
                        </button>
                    </div>
                   )}
                </div>
                
                 {/* Compact View Actions (Inline) */}
                 {viewMode === 'compact' && (
                    <div className="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-all shrink-0">
                        <button onClick={() => handleCopyClick(reference)} className="p-1.5 text-gray-400 hover:text-emerald-600 rounded-lg"><Copy className="h-3.5 w-3.5" /></button>
                         <button onClick={() => handleEditClick(reference)} className="p-1.5 text-gray-400 hover:text-blue-600 rounded-lg"><Edit className="h-3.5 w-3.5" /></button>
                         <button onClick={() => handleDeleteClick(reference)} className="p-1.5 text-gray-400 hover:text-red-600 rounded-lg"><Trash2 className="h-3.5 w-3.5" /></button>
                    </div>
                 )}
            </div>
        </div>
    );
}

export default function ReferencesIndex({ references, projects, selectedProjectId }: Props) {
    const { url } = usePage();
    const searchParams = new URL(url, window.location.origin).searchParams;
    const selectedFolderId = searchParams.get('folder_id') ? Number(searchParams.get('folder_id')) : null;
    
    // View State
    const [viewMode, setViewMode] = useState<'standard' | 'compact' | 'citation'>('citation');
    const [lookupResults, setLookupResults] = useState<any>(null);
    const [showLookupDropdown, setShowLookupDropdown] = useState(false);
    const [citeInput, setCiteInput] = useState('');
    const [citeLoading, setCiteLoading] = useState(false);
    const [activeCiteTab, setActiveCiteTab] = useState('websites');
    const [selectedStyle, setSelectedStyle] = useState(citationStyles[0]);
    const [isStyleMenuOpen, setIsStyleMenuOpen] = useState(false);
    const [styleSearch, setStyleSearch] = useState('');
    const [isExportMenuOpen, setIsExportMenuOpen] = useState(false);
    const [viewingCitation, setViewingCitation] = useState<Reference | null>(null);
    const [copySuccess, setCopySuccess] = useState(false);

    // Find selected folder name
    const currentFolder = projects
        .flatMap(p => p.folders || [])
        .find(f => f.id === selectedFolderId);

    const { language } = useLanguage();
    const [localProjects, setLocalProjects] = useState<Project[]>(projects);
    
    // Sync local projects when prop changes
    useEffect(() => {
        setLocalProjects(projects);
    }, [projects]);

    // DND Sensors
    const sensors = useSensors(
        useSensor(PointerSensor, {
            activationConstraint: {
                distance: 8,
            },
        }),
        useSensor(KeyboardSensor, {
            coordinateGetter: sortableKeyboardCoordinates,
        })
    );

    // File Upload Handler
    const fileInputRef = useRef<HTMLInputElement>(null);
    const [uploadTargetProjectId, setUploadTargetProjectId] = useState<number | null>(null);

    const handleUploadClick = (projectId: number | null = null) => {
        setUploadTargetProjectId(projectId);
        fileInputRef.current?.click();
    };

    const handleFileChange = async (event: React.ChangeEvent<HTMLInputElement>) => {
        const file = event.target.files?.[0];
        if (!file) return;

        // Create form data
        const reader = new FileReader();
        reader.onload = async (e) => {
            const content = e.target?.result as string;
            
            try {
                // Get CSRF token
                const csrfToken = (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content;

                // First parse the content
                const parseResponse = await fetch('/import/parse-bibtex', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || ''
                    },
                    body: JSON.stringify({ content })
                });
                
                const parseData = await parseResponse.json();
                
                if (parseData.success && parseData.entries.length > 0) {
                    // Then import the entries
                    const importResponse = await fetch('/import/bibtex', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken || ''
                        },
                        body: JSON.stringify({ 
                            entries: parseData.entries,
                            project_id: uploadTargetProjectId 
                        })
                    });
                    
                    const importData = await importResponse.json();
                    
                    if (importData.success) {
                        toast.success(t.toasts.importSuccess.replace('{count}', importData.count.toString()));
                        router.reload();
                    }
                } else {
                    toast.error(t.toasts.importNoValid);
                }
            } catch (error) {
                console.error(error);
                toast.error(t.toasts.importFailed);
            }
            
            // Reset input
            if (fileInputRef.current) fileInputRef.current.value = '';
        };
        reader.readAsText(file);
    };

    // Keyboard handling for Lookup
    useEffect(() => {
        const handleKeyDown = (e: KeyboardEvent) => {
            if (showLookupDropdown && lookupResults) {
                if (e.key === 'Escape') {
                    setShowLookupDropdown(false);
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    if (Array.isArray(lookupResults) && lookupResults.length > 0) {
                        handleAddLookup(lookupResults[0], 0);
                    } else if (lookupResults && !Array.isArray(lookupResults)) {
                        handleAddLookup(lookupResults, 0);
                    }
                }
            }
        };

        window.addEventListener('keydown', handleKeyDown);
        return () => window.removeEventListener('keydown', handleKeyDown);
    }, [showLookupDropdown, lookupResults]);

    // Auto-search logic
    useEffect(() => {
        if (!citeInput.trim() || citeLoading) {
            if (!citeInput.trim()) {
                setShowLookupDropdown(false);
                setLookupResults(null);
            }
            return;
        }

        // Auto-search if it's a URL, DOI, ISBN, or a title (min length 10 or 5 for Thai/Short English)
        const isPotentialSource = citeInput.includes('.') || citeInput.includes('/') || citeInput.length >= 5;
        if (!isPotentialSource) return;

        const timer = setTimeout(() => {
            performLookup(citeInput);
        }, 1000); // 1 second debounce

        return () => clearTimeout(timer);
    }, [citeInput]);

    const performLookup = async (input: string) => {
        setCiteLoading(true);
        setShowLookupDropdown(false);
        setLookupResults(null);

        try {
            const csrfToken = (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content;
            const response = await fetch('/import/lookup', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || ''
                },
                body: JSON.stringify({ identifier: input })
            });
            
            const data = await response.json();
            
            if (data.success) {
                setLookupResults(data.data);
                setShowLookupDropdown(true);
            }
        } catch (error: any) {
            console.error(error);
        } finally {
            setCiteLoading(false);
        }
    };

    const handleDragEnd = (event: DragEndEvent) => {
        const { active, over } = event;

        if (over && active.id !== over.id) {
            const oldIndex = localProjects.findIndex((i) => i.id === active.id);
            const newIndex = localProjects.findIndex((i) => i.id === over.id);

            const newItems = arrayMove(localProjects, oldIndex, newIndex);
            setLocalProjects(newItems);
            
            // Sync with server
            router.post('/projects/reorder', {
                ids: newItems.map(i => i.id)
            }, {
                preserveScroll: true,
                onSuccess: () => toast.success(t.toasts.orderSaved),
                onError: () => setLocalProjects(projects) // Revert if failed
            });
        }
    };
    const [addDropdownOpen, setAddDropdownOpen] = useState(false);
    const [projectMenuOpen, setProjectMenuOpen] = useState<number | null>(null);
    const [folderMenuOpen, setFolderMenuOpen] = useState<number | null>(null);
    const [isCreateProjectOpen, setIsCreateProjectOpen] = useState(false);
    const [isEditProjectOpen, setIsEditProjectOpen] = useState(false);
    const [isDeleteProjectOpen, setIsDeleteProjectOpen] = useState(false);
    const [isAddFolderOpen, setIsAddFolderOpen] = useState(false);
    const [isEditFolderOpen, setIsEditFolderOpen] = useState(false);
    const [isDeleteFolderConfirmOpen, setIsDeleteFolderConfirmOpen] = useState(false);
    const [isNewSourceOpen, setIsNewSourceOpen] = useState(false);
    const [editingProject, setEditingProject] = useState<Project | null>(null);
    const [editingFolder, setEditingFolder] = useState<FolderItem | null>(null);
    const [targetProject, setTargetProject] = useState<Project | null>(null);
    const [localReferences, setLocalReferences] = useState<Reference[]>(references || []);
    const [isDeleteReferenceOpen, setIsDeleteReferenceOpen] = useState(false);
    const [editingReference, setEditingReference] = useState<Reference | null>(null);
    const [newReferenceId, setNewReferenceId] = useState<number | null>(null);
    const [isAddingLookupId, setIsAddingLookupId] = useState<number | null>(null);

    // Sync local references when prop changes
    useEffect(() => {
        setLocalReferences(references || []);
    }, [references]);

    const handleReferenceDragEnd = (event: DragEndEvent) => {
        const { active, over } = event;

        if (over && active.id !== over.id) {
            const oldIndex = localReferences.findIndex((i) => i.id === active.id);
            const newIndex = localReferences.findIndex((i) => i.id === over.id);

            const newItems = arrayMove(localReferences, oldIndex, newIndex);
            setLocalReferences(newItems);
            
            // Sync with server
            router.post('/references/reorder', {
                ids: newItems.map(i => i.id)
            }, {
                preserveScroll: true,
                onSuccess: () => toast.success(t.toasts.orderSaved),
                onError: () => setLocalReferences(references)
            });
        }
    };

    // New Source Form State
    const [sourceData, setSourceData] = useState({
        type: 'book',
        title: '',
        year: '',
        year_suffix: '',
        isOnline: false,
        lang: 'en', // Bibliography language
        authors: [{ firstName: '', middleName: '', lastName: '', role: 'author' }],
        extraFields: {} as Record<string, string>,
    });
    const [activeFields, setActiveFields] = useState<string[]>(['publisher', 'isbn']);
    const [isAddFieldOpen, setIsAddFieldOpen] = useState(false);

    const [expandedProjects, setExpandedProjects] = useState<number[]>([]);
    const t = translations[language];

    // Persist expanded projects
    useEffect(() => {
        const saved = localStorage.getItem('expandedProjects');
        if (saved) {
            try {
                const parsed = JSON.parse(saved);
                // Ensure selectedProjectId is included if it exists
                if (selectedProjectId && !parsed.includes(Number(selectedProjectId))) {
                    setExpandedProjects([...parsed, Number(selectedProjectId)]);
                } else {
                    setExpandedProjects(parsed);
                }
            } catch (e) {
                if (selectedProjectId) setExpandedProjects([Number(selectedProjectId)]);
            }
        } else if (selectedProjectId) {
            setExpandedProjects([Number(selectedProjectId)]);
        }
    }, [selectedProjectId]);

    useEffect(() => {
        localStorage.setItem('expandedProjects', JSON.stringify(expandedProjects));
    }, [expandedProjects]);

    // Persist selected style
    useEffect(() => {
        const saved = localStorage.getItem('selectedCitationStyle');
        if (saved) {
            const found = citationStyles.find(s => s.id === saved);
            if (found) setSelectedStyle(found);
        }
    }, []);

    useEffect(() => {
        localStorage.setItem('selectedCitationStyle', selectedStyle.id);
    }, [selectedStyle]);

    const breadcrumbs: BreadcrumbItem[] = [
        { title: t.title, href: '/references' },
    ];

    const { data, setData, post, processing, reset, errors } = useForm({
        name: '',
        description: '',
        color: projectColors[0],
        icon: 'Folder',
    });

    const editForm = useForm({
        name: '',
        description: '',
        color: '',
        icon: '',
    });

    const folderForm = useForm({
        name: '',
        project_id: '',
    });

    const editFolderForm = useForm({
        name: '',
    });

    const handleCreateProject = (e: FormEvent) => {
        e.preventDefault();
        post('/projects', {
            onSuccess: () => {
                setIsCreateProjectOpen(false);
                reset();
                toast.success(t.projectCreated);
            },
        });
    };

    const handleEditProject = (e: FormEvent) => {
        e.preventDefault();
        if (!editingProject) return;
        
        editForm.put(`/projects/${editingProject.id}`, {
            onSuccess: () => {
                setIsEditProjectOpen(false);
                setEditingProject(null);
                toast.success(t.toasts.projectUpdated);
            },
        });
    };

    const handleDeleteProject = (e: FormEvent) => {
        e.preventDefault();
        if (!editingProject) return;

        router.delete(`/projects/${editingProject.id}`, {
            onSuccess: () => {
                setIsDeleteProjectOpen(false);
                setEditingProject(null);
                toast.success(t.toasts.projectDeleted);
            },
        });
    };

    const handleAddFolder = (e: FormEvent) => {
        e.preventDefault();
        
        // We ensure project_id is set before posting
        folderForm.post('/folders', {
            onSuccess: () => {
                setIsAddFolderOpen(false);
                folderForm.reset();
                setTargetProject(null);
                toast.success(t.toasts.folderCreated);
            },
        });
    };

    const toggleProjectExpand = (projectId: number) => {
        setExpandedProjects(prev => 
            prev.includes(projectId) 
                ? prev.filter(id => id !== projectId) 
                : [...prev, projectId]
        );
    };

    const handleCopyAll = () => {
        // Mock copy functionality
        setCopySuccess(true);
        toast.success(t.toasts.copiedToClipboard);
        setTimeout(() => setCopySuccess(false), 2000);
    };

    const handleExport = (format: string) => {
        setIsExportMenuOpen(false);
        
        const params = new URLSearchParams();
        if (selectedProjectId) params.set('project_id', selectedProjectId.toString());
        if (selectedFolderId) params.set('folder_id', selectedFolderId.toString());
        params.set('style', selectedStyle.id);
        params.set('lang', language);
        
        if (format === 'pdf') {
            window.location.href = `/export/pdf?${params.toString()}`;
            toast.success(t.toasts.exporting.replace('{format}', 'PDF'));
        } else if (format === 'word') {
            window.location.href = `/export/word?${params.toString()}`;
            toast.success(t.toasts.exporting.replace('{format}', 'Word'));
        } else if (format === 'bibtex') {
            window.location.href = `/export/bibtex?${params.toString()}`;
            toast.success(t.toasts.exporting.replace('{format}', 'BibTeX'));
        } else if (format === 'ris') {
            window.location.href = `/export/ris?${params.toString()}`;
            toast.success(t.toasts.exporting.replace('{format}', 'RIS'));
        }
    };


    const handleEditFolder = (e: FormEvent) => {
        e.preventDefault();
        if (!editingFolder) return;
        
        editFolderForm.patch(`/folders/${editingFolder.id}`, {
            onSuccess: () => {
                setIsEditFolderOpen(false);
                editFolderForm.reset();
                setEditingFolder(null);
                toast.success(t.toasts.folderUpdated);
            },
        });
    };

    const handleDeleteFolder = (e: FormEvent) => {
        e.preventDefault();
        if (!editingFolder) return;
        
        router.delete(`/folders/${editingFolder.id}`, {
            onSuccess: () => {
                setIsDeleteFolderConfirmOpen(false);
                setEditingFolder(null);
                toast.success(t.toasts.folderDeleted);
            },
        });
    };

    const handleAddAuthor = () => {
        setSourceData(prev => ({
            ...prev,
            authors: [...prev.authors, { firstName: '', middleName: '', lastName: '', role: 'author' }]
        }));
    };

    const handleAuthorChange = (index: number, field: string, value: string) => {
        const newAuthors = [...sourceData.authors];
        newAuthors[index] = { ...newAuthors[index], [field]: value };
        setSourceData(prev => ({ ...prev, authors: newAuthors }));
    };

    const handleRemoveAuthor = (index: number) => {
        if (sourceData.authors.length === 1) return;
        setSourceData(prev => ({
            ...prev,
            authors: prev.authors.filter((_, i) => i !== index)
        }));
    };

    const toggleField = (fieldId: string) => {
        setActiveFields(prev => 
            prev.includes(fieldId) ? prev.filter(f => f !== fieldId) : [...prev, fieldId]
        );
    };

    const handleNewSourceSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        toast.success(t.toasts.addedToProject);
        setIsNewSourceOpen(false);
        // Add logic to save reference
    };

    const handleQuickCite = async (e: React.FormEvent) => {
        e.preventDefault();
        if (!citeInput.trim()) return;
        performLookup(citeInput);
    };

    const handleAddLookup = (data: any, index: number) => {
        const params = new URLSearchParams(window.location.search);
        const projectId = params.get('project_id');
        const folderId = params.get('folder_id');

        setIsAddingLookupId(index);

        router.post('/import/from-lookup', { 
            ...data,
            project_id: projectId,
            folder_id: folderId
        }, {
            onSuccess: (page: any) => {
                setShowLookupDropdown(false);
                setLookupResults(null);
                setCiteInput('');
                setIsAddingLookupId(null);
                toast.success(t.toasts.sourceAdded);

                // Highlight the new reference
                const newRef = page.props.references?.[0];
                if (newRef) {
                    setNewReferenceId(newRef.id);
                    setTimeout(() => setNewReferenceId(null), 3000);
                }
            },
            onError: (errors: any) => {
                setIsAddingLookupId(null);
                const message = Object.values(errors)[0] as string;
                toast.error(message || t.toasts.sourceFailed);
            },
            preserveScroll: true
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t.title} />

            <div className="flex h-full flex-1 flex-col gap-6 bg-scribehub-paper/50 p-6 dark:bg-[#050505]/50 animate-in fade-in duration-700">
                {/* Header with Quick Cite */}
                <div className="flex flex-col gap-8 lg:flex-row lg:items-start lg:justify-between">
                    <div className="shrink-0 pt-2">
                        <h1 className="text-3xl font-extrabold text-scribehub-blue dark:text-white leading-none">{t.title}</h1>
                        <p className="mt-2 text-sm font-medium text-gray-400 dark:text-gray-500">{t.subtitle}</p>
                    </div>

                    <div className="flex-1 max-w-4xl w-full">
                        {/* Tabs Row */}
                        <div className="flex items-center justify-between mb-2 px-1">
                            <div className="flex items-center gap-6">
                                <button 
                                    onClick={() => setActiveCiteTab('websites')}
                                    className={cn(
                                        "text-[10px] font-black uppercase tracking-[0.1em] transition-all pb-1 border-b-2", 
                                        activeCiteTab === 'websites' ? "text-scribehub-blue border-scribehub-blue dark:text-white dark:border-white" : "text-gray-300 border-transparent hover:text-gray-500"
                                    )}
                                >
                                    {t.websites}
                                </button>
                                <button 
                                    onClick={() => setActiveCiteTab('books')}
                                    className={cn(
                                        "text-[10px] font-black uppercase tracking-[0.1em] transition-all pb-1 border-b-2", 
                                        activeCiteTab === 'books' ? "text-scribehub-blue border-scribehub-blue dark:text-white dark:border-white" : "text-gray-300 border-transparent hover:text-gray-500"
                                    )}
                                >
                                    {t.books}
                                </button>
                                <button 
                                    onClick={() => setActiveCiteTab('articles')}
                                    className={cn(
                                        "text-[10px] font-black uppercase tracking-[0.1em] transition-all pb-1 border-b-2", 
                                        activeCiteTab === 'articles' ? "text-scribehub-blue border-scribehub-blue dark:text-white dark:border-white" : "text-gray-300 border-transparent hover:text-gray-500"
                                    )}
                                >
                                    {t.articles}
                                </button>
                                <button 
                                    onClick={() => setIsNewSourceOpen(true)}
                                    className="text-[10px] font-black uppercase tracking-[0.1em] text-gray-300 hover:text-gray-500 transition-colors pb-1 border-b-2 border-transparent"
                                >
                                    + {t.more}
                                </button>
                            </div>

                            <Link href="/help" className="flex items-center gap-1.5 text-[10px] font-black uppercase tracking-wider text-gray-400 hover:text-scribehub-blue transition-colors">
                                <HelpCircle className="h-3 w-3" />
                                {t.help}
                            </Link>
                        </div>

                        {/* Input Bar */}
                        <div className={cn("relative group", (showLookupDropdown || citeLoading) ? "z-[45]" : "z-0")}>
                            {/* Lookup Backdrop (Focus Mode) */}
                            {(showLookupDropdown || citeLoading) && (
                                <div 
                                    className="fixed inset-0 z-[40] bg-zinc-900/60 backdrop-blur-md animate-in fade-in duration-500"
                                    onClick={() => !citeLoading && setShowLookupDropdown(false)}
                                ></div>
                            )}

                            <form onSubmit={handleQuickCite}>
                                {/* Outer Glow */}
                                <div className="absolute inset-x-4 -inset-y-2 bg-scribehub-blue/5 rounded-[2rem] blur-2xl dark:bg-scribehub-blue/10 opacity-0 group-focus-within:opacity-100 transition-opacity"></div>
                                
                                <div className={cn(
                                    "relative flex items-center gap-2.5 rounded-xl border border-gray-100 bg-white p-1.5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all hover:shadow-[0_8px_30_rgb(0,0,0,0.08)] focus-within:border-scribehub-blue/50 focus-within:ring-4 focus-within:ring-scribehub-blue/5 dark:border-gray-800 dark:bg-gray-900/80 dark:shadow-none",
                                    (showLookupDropdown || citeLoading) ? "z-[45]" : "z-10"
                                )}>
                                    <div className="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-gray-50 dark:bg-gray-800">
                                        <LinkIcon className="h-4 w-4 text-gray-400" />
                                    </div>
                                    <input 
                                        type="text" 
                                        value={citeInput}
                                        onChange={(e) => setCiteInput(e.target.value)}
                                        placeholder={
                                            activeCiteTab === 'websites' ? t.placeholderWebsites :
                                            activeCiteTab === 'books' ? t.placeholderBooks :
                                            activeCiteTab === 'articles' ? t.placeholderArticles :
                                            t.pastePlaceholder
                                        }
                                        className="flex-1 bg-transparent border-none py-1.5 text-xs font-semibold text-gray-700 placeholder:text-gray-300 focus:ring-0 dark:text-gray-200 dark:placeholder:text-gray-600"
                                    />
                                    <button 
                                        type="submit"
                                        disabled={citeLoading}
                                        className="inline-flex h-8 items-center gap-2 rounded-lg bg-scribehub-blue px-4 text-[10px] font-black text-white shadow-lg shadow-blue-500/20 transition-all hover:scale-[1.02] hover:shadow-blue-500/30 active:scale-[0.98] disabled:opacity-50 disabled:scale-100 relative z-20"
                                    >
                                        {citeLoading ? (
                                            <div className="h-4 w-4 animate-spin rounded-full border-2 border-white/20 border-t-white" />
                                        ) : (
                                            <>
                                                <Plus className="h-3.5 w-3.5" />
                                                {t.cite}
                                            </>
                                        )}
                                    </button>
                                </div>
                            </form>

                            {/* Lookup Dropdown */}
                            {showLookupDropdown && lookupResults && Array.isArray(lookupResults) && lookupResults.length > 0 && (
                                <>
                                    <div className="absolute top-full left-0 right-0 mt-3 z-[45] bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-[0_20px_50px_rgba(0,0,0,0.15)] dark:shadow-[0_20px_50px_rgba(0,0,0,0.3)] overflow-hidden animate-in fade-in slide-in-from-top-2 duration-300">
                                        <div className="max-h-[400px] overflow-y-auto divide-y divide-gray-50 dark:divide-gray-800">
                                            {Array.isArray(lookupResults) && lookupResults.length > 0 ? (
                                                lookupResults.map((result, idx) => (
                                                    <div key={idx} className="p-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors group/item relative z-10">
                                                        <div className="flex-1 min-w-0 pr-4">
                                                            <h3 className="text-[14px] font-black text-scribehub-blue dark:text-white truncate">
                                                                {result.title}
                                                            </h3>
                                                            <div className="flex flex-wrap items-center gap-2 mt-1">
                                                                {result.authors && result.authors.length > 0 && (
                                                                    <span className="text-[11px] font-bold text-gray-500 dark:text-gray-400">
                                                                        {result.authors.join(', ')}
                                                                    </span>
                                                                )}
                                                                <span className="text-gray-300 dark:text-gray-700 text-[10px]">•</span>
                                                                <span className="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 italic">
                                                                    {result.publisher || result.journal_name || 'Web Resource'}
                                                                </span>
                                                                <span className="text-gray-300 dark:text-gray-700 text-[10px]">•</span>
                                                                <span className="text-[11px] font-medium text-gray-400 capitalize">
                                                                    {result.type}
                                                                </span>
                                                                {result.year && (
                                                                    <>
                                                                        <span className="text-gray-300 dark:text-gray-700 text-[10px]">•</span>
                                                                        <span className="text-[11px] font-medium text-gray-400">{result.year}</span>
                                                                    </>
                                                                )}
                                                            </div>
                                                        </div>
                                                        <button 
                                                            onClick={() => handleAddLookup(result, idx)}
                                                            disabled={isAddingLookupId === idx}
                                                            className="inline-flex h-8 items-center gap-2 rounded-lg bg-gray-100 px-4 text-[10px] font-black text-gray-900 transition-all hover:bg-scribehub-blue hover:text-white hover:scale-[1.05] dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white dark:hover:text-black shrink-0 disabled:opacity-50 disabled:scale-100"
                                                        >
                                                            {isAddingLookupId === idx ? (
                                                                <>
                                                                    <Loader2 className="h-3.5 w-3.5 animate-spin" />
                                                                    {language === 'th' ? 'กำลังเพิ่ม...' : 'Adding...'}
                                                                </>
                                                            ) : (
                                                                <>
                                                                    <Plus className="h-3.5 w-3.5" />
                                                                    Add
                                                                </>
                                                            )}
                                                        </button>
                                                    </div>
                                                ))
                                            ) : (
                                                <div className="p-8 text-center">
                                                    <div className="mx-auto mb-3 flex h-10 w-10 items-center justify-center rounded-full bg-gray-50 dark:bg-gray-800">
                                                        <Search className="h-5 w-5 text-gray-300" />
                                                    </div>
                                                    <p className="text-xs font-bold text-gray-400">{t.noResults}</p>
                                                </div>
                                            )}
                                        </div>

                                    {/* Keyboard Navigation Hints */}
                                    <div className="bg-gray-50/80 px-4 py-2 flex items-center justify-end gap-6 dark:bg-gray-800/30 border-t border-gray-100 dark:border-gray-800">
                                        <div className="flex items-center gap-2 text-[10px] font-bold text-gray-400">
                                            <span className="flex items-center gap-1">
                                                <svg className="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={3} d="M19 14l-7 7m0 0l-7-7m7 7V3" /></svg>
                                                Navigate
                                            </span>
                                        </div>
                                        <div className="flex items-center gap-2 text-[10px] font-bold text-gray-300">
                                            <div className="flex items-center gap-1.5">
                                                <kbd className="px-1.5 py-0.5 rounded-md bg-white border border-gray-200 dark:bg-gray-800 dark:border-gray-700 text-[9px] text-gray-500">Enter</kbd>
                                                Add source to project
                                            </div>
                                        </div>
                                        <div className="flex items-center gap-2 text-[10px] font-bold text-gray-300">
                                            <div className="flex items-center gap-1.5">
                                                <kbd className="px-1.5 py-0.5 rounded-md bg-white border border-gray-200 dark:bg-gray-800 dark:border-gray-700 text-[9px] text-gray-500">Esc</kbd>
                                                Clear search
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </>
                            )}
                        </div>
                    </div>
                </div>

                <div className="flex flex-col lg:flex-row flex-1 gap-6 min-h-0 overflow-hidden lg:overflow-visible">
                    {/* Workspace Sidebar */}
                    <div className="w-full lg:w-64 flex flex-col gap-4 lg:shrink-0">
                        {/* Add New Button */}
                        <div className="relative flex justify-center">
                            <button 
                                onClick={() => setAddDropdownOpen(!addDropdownOpen)}
                                className="inline-flex w-fit items-center gap-3 rounded-xl bg-scribehub-blue py-2 px-5 text-xs font-black text-white shadow-lg shadow-blue-500/20 transition-all hover:opacity-90 active:scale-95"
                            >
                                <div className="flex items-center gap-2">
                                    <Plus className="h-4 w-4" />
                                    {t.new}
                                </div>
                                <div className="h-4 w-6 rounded-md bg-white/20 flex items-center justify-center text-[9px]">
                                    ⌘N
                                </div>
                            </button>

                            {addDropdownOpen && (
                                <>
                                    <div className="fixed inset-0 z-20" onClick={() => setAddDropdownOpen(false)}></div>
                                    <div className="absolute left-0 top-full z-30 mt-2 w-56 rounded-2xl border border-gray-100 bg-white py-2 shadow-2xl dark:border-gray-800 dark:bg-gray-900 animate-in fade-in zoom-in-95 duration-200">
                                        <button 
                                            onClick={() => {
                                                setAddDropdownOpen(false);
                                                setIsNewSourceOpen(true);
                                            }}
                                            className="flex w-full items-center gap-3 px-4 py-2.5 text-xs font-bold text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-800"
                                        >
                                            <FilePlus className="h-4 w-4 text-blue-500" /> {t.newSource}
                                        </button>
                                        <button 
                                            onClick={() => {
                                                setAddDropdownOpen(false);
                                                setIsCreateProjectOpen(true);
                                            }}
                                            className="flex w-full items-center gap-3 px-4 py-2.5 text-xs font-bold text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-800"
                                        >
                                            <Settings className="h-4 w-4 text-emerald-500" /> {t.newProject}
                                        </button>
                                        <button 
                                            onClick={() => {
                                                setAddDropdownOpen(false);
                                                setTargetProject(null);
                                                folderForm.reset();
                                                setIsAddFolderOpen(true);
                                            }}
                                            className="flex w-full items-center gap-3 px-4 py-2.5 text-xs font-bold text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-800"
                                        >
                                            <FolderPlus className="h-4 w-4 text-amber-500" /> {t.newFolder}
                                        </button>
                                        <div className="my-1 border-t border-gray-100 dark:border-gray-800"></div>
                                        <button 
                                            onClick={() => {
                                                setAddDropdownOpen(false);
                                                handleUploadClick(null);
                                            }}
                                            className="flex w-full items-center gap-3 px-4 py-2.5 text-xs font-bold text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-800"
                                        >
                                            <Upload className="h-4 w-4 text-purple-500" /> {t.uploadFile}
                                        </button>
                                    </div>
                                </>
                            )}
                        </div>

                        {/* Project List */}
                        <div className="flex-1 flex flex-col gap-1 lg:pr-2 custom-scrollbar overflow-x-auto lg:overflow-x-visible pb-2 lg:pb-0">
                            <div className="mb-2 px-4 text-[10px] font-black uppercase tracking-widest text-gray-400">
                                {t.projectsTitle}
                            </div>

                            <DndContext 
                                sensors={sensors}
                                collisionDetection={closestCenter}
                                onDragEnd={handleDragEnd}
                                modifiers={[restrictToVerticalAxis]}
                            >
                                <div className="flex lg:flex-col gap-1 min-w-0">
                                    <SortableContext 
                                        items={localProjects.map(p => p.id)}
                                        strategy={verticalListSortingStrategy}
                                    >
                                        {localProjects.map((project) => (
                                            <SortableProjectItem 
                                                key={project.id}
                                                project={project}
                                                selectedProjectId={selectedProjectId}
                                                expandedProjects={expandedProjects}
                                                toggleProjectExpand={toggleProjectExpand}
                                                setProjectMenuOpen={setProjectMenuOpen}
                                                projectMenuOpen={projectMenuOpen}
                                                folderMenuOpen={folderMenuOpen}
                                                setFolderMenuOpen={setFolderMenuOpen}
                                                setEditingFolder={setEditingFolder}
                                                editFolderForm={editFolderForm}
                                                setIsEditFolderOpen={setIsEditFolderOpen}
                                                setIsDeleteFolderConfirmOpen={setIsDeleteFolderConfirmOpen}
                                                t={t}
                                                language={language}
                                                setEditingProject={setEditingProject}
                                                editForm={editForm}
                                                setIsEditProjectOpen={setIsEditProjectOpen}
                                                setTargetProject={setTargetProject}
                                                folderForm={folderForm}
                                                setIsAddFolderOpen={setIsAddFolderOpen}
                                                setIsDeleteProjectOpen={setIsDeleteProjectOpen}
                                                onUploadClick={handleUploadClick}
                                            />
                                        ))}
                                    </SortableContext>
                                </div>
                            </DndContext>

                            <button 
                                onClick={() => setIsCreateProjectOpen(true)}
                                className="flex items-center gap-3 rounded-xl px-4 py-3 text-xs font-bold text-gray-400 hover:text-scribehub-blue hover:bg-gray-50 dark:hover:bg-gray-800 transition-all border border-dashed border-gray-200 mt-2 dark:border-gray-800 whitespace-nowrap"
                            >
                                <Plus className="h-4 w-4" />
                                {t.newProject}
                            </button>
                        </div>
                    </div>

                    {/* Main Workspace Area (Right) */}
                    <div className="flex-1 min-h-[400px] lg:min-h-0 flex flex-col gap-2">
                        {/* Workspace Toolbar */}
                        <div className="flex items-center justify-between px-2 py-1">
                            <div className="flex items-center gap-4">
                                <div className="flex items-center gap-2">
                                    {currentFolder && (
                                        <div className="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-scribehub-blue/5 border border-scribehub-blue/10 animate-in fade-in slide-in-from-left-2 duration-300">
                                            <Folder className="h-3.5 w-3.5 text-scribehub-blue" />
                                            <span className="text-[10px] font-black uppercase tracking-widest text-scribehub-blue">{currentFolder.name}</span>
                                        </div>
                                    )}
                                    <div className="relative">
                                        <button 
                                            onClick={() => setIsStyleMenuOpen(!isStyleMenuOpen)}
                                        className="flex items-center gap-2 rounded-xl bg-gray-50/50 dark:bg-gray-800/50 px-4 py-2 text-[11px] font-black uppercase tracking-wider text-gray-500 hover:text-scribehub-blue transition-all border border-gray-100 dark:border-gray-700 whitespace-nowrap"
                                    >
                                        <span className="text-gray-400">Style:</span> {selectedStyle.name} - {selectedStyle.version}
                                        <ChevronDown className={cn("h-3 w-3 transition-transform", isStyleMenuOpen && "rotate-180")} />
                                    </button>

                                    {isStyleMenuOpen && (
                                        <>
                                            <div className="fixed inset-0 z-40" onClick={() => setIsStyleMenuOpen(false)}></div>
                                            <div className="absolute left-0 top-full z-50 mt-2 w-72 rounded-2xl border border-gray-100 bg-white p-2 shadow-2xl dark:border-gray-800 dark:bg-gray-900 animate-in fade-in zoom-in-95 duration-200">
                                                <div className="relative mb-2 mt-1 px-2">
                                                    <Search className="absolute left-4 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-gray-400" />
                                                    <input 
                                                        type="text"
                                                        value={styleSearch}
                                                        onChange={(e) => setStyleSearch(e.target.value)}
                                                        placeholder="Search styles..."
                                                        className="w-full rounded-xl border-none bg-gray-50 py-2 pl-9 pr-4 text-[11px] font-bold text-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-scribehub-blue/10 dark:bg-gray-800 dark:text-gray-200"
                                                    />
                                                </div>
                                                <div className="max-h-64 overflow-y-auto custom-scrollbar">
                                                    {citationStyles
                                                        .filter(s => s.name.toLowerCase().includes(styleSearch.toLowerCase()) || s.version.toLowerCase().includes(styleSearch.toLowerCase()))
                                                        .map(style => (
                                                            <button 
                                                                key={style.id}
                                                                onClick={() => {
                                                                    setSelectedStyle(style);
                                                                    setIsStyleMenuOpen(false);
                                                                    setStyleSearch('');
                                                                    
                                                                    // Reload with new style
                                                                    const params = new URLSearchParams(window.location.search);
                                                                    params.set('style', style.id);
                                                                    router.get(`/references?${params.toString()}`, {}, {
                                                                        preserveState: true,
                                                                        preserveScroll: true
                                                                    });
                                                                }}
                                                                className={cn(
                                                                    "flex w-full items-center justify-between rounded-xl px-3 py-2.5 text-left text-[11px] font-bold transition-all",
                                                                    selectedStyle.id === style.id ? "bg-scribehub-blue/5 text-scribehub-blue" : "text-gray-500 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800"
                                                                )}
                                                            >
                                                                <div className="flex flex-col">
                                                                    <span>{style.name}</span>
                                                                    <span className="text-[9px] font-medium text-gray-400">{style.version}</span>
                                                                </div>
                                                                {selectedStyle.id === style.id && <Check className="h-3.5 w-3.5" />}
                                                            </button>
                                                        ))
                                                    }
                                                </div>
                                            </div>
                                        </>
                                    )}
                                </div>
                            </div>

                                <div className="h-4 w-px bg-gray-200 dark:bg-gray-700 hidden sm:block"></div>

                                {/* View Toggle */}
                                <div className="hidden sm:flex items-center gap-1.5 bg-gray-50/50 dark:bg-gray-800/50 p-1 rounded-xl border border-gray-100 dark:border-gray-700">
                                    <span className="px-2 text-[10px] font-black uppercase tracking-widest text-gray-400">View</span>
                                    <Tooltip>
                                        <TooltipTrigger asChild>
                                            <button 
                                                onClick={() => setViewMode('standard')}
                                                className={cn(
                                                    "p-1.5 rounded-lg transition-all",
                                                    viewMode === 'standard' 
                                                    ? "bg-white dark:bg-gray-700 text-scribehub-blue dark:text-white shadow-sm" 
                                                    : "text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-200/50 dark:hover:bg-gray-800"
                                                )}
                                            >
                                                <List className="h-3.5 w-3.5" />
                                            </button>
                                        </TooltipTrigger>
                                        <TooltipContent>
                                            <p>Standard List</p>
                                        </TooltipContent>
                                    </Tooltip>
                                    <Tooltip>
                                        <TooltipTrigger asChild>
                                            <button 
                                                onClick={() => setViewMode('compact')}
                                                className={cn(
                                                    "p-1.5 rounded-lg transition-all",
                                                    viewMode === 'compact' 
                                                    ? "bg-white dark:bg-gray-700 text-scribehub-blue dark:text-white shadow-sm" 
                                                    : "text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-200/50 dark:hover:bg-gray-800"
                                                )}
                                            >
                                                <AlignLeft className="h-3.5 w-3.5" />
                                            </button>
                                        </TooltipTrigger>
                                        <TooltipContent>
                                            <p>Compact List</p>
                                        </TooltipContent>
                                    </Tooltip>
                                    <Tooltip>
                                        <TooltipTrigger asChild>
                                            <button 
                                                onClick={() => setViewMode('citation')}
                                                className={cn(
                                                    "p-1.5 rounded-lg transition-all",
                                                    viewMode === 'citation' 
                                                    ? "bg-white dark:bg-gray-700 text-scribehub-blue dark:text-white shadow-sm" 
                                                    : "text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-200/50 dark:hover:bg-gray-800"
                                                )}
                                            >
                                                <Quote className="h-3.5 w-3.5" />
                                            </button>
                                        </TooltipTrigger>
                                        <TooltipContent>
                                            <p>Citation View</p>
                                        </TooltipContent>
                                    </Tooltip>
                                </div>
                            </div>

                            <div className="flex items-center gap-2">
                                <button 
                                    onClick={handleCopyAll}
                                    className={cn(
                                        "flex items-center gap-2 rounded-xl px-4 py-2 text-[11px] font-black uppercase tracking-wider transition-all border",
                                        copySuccess 
                                            ? "bg-emerald-50 text-emerald-600 border-emerald-200 dark:bg-emerald-900/20 dark:border-emerald-800" 
                                            : "bg-gray-50/50 dark:bg-gray-800/50 text-gray-500 hover:text-scribehub-blue border-gray-100 dark:border-gray-700"
                                    )}
                                >
                                    {copySuccess ? <Check className="h-3.5 w-3.5" /> : <Copy className="h-3.5 w-3.5" />}
                                    <span>{copySuccess ? 'Copied' : 'Copy'}</span>
                                </button>
                                
                                <div className="relative">
                                    <button 
                                        onClick={() => setIsExportMenuOpen(!isExportMenuOpen)}
                                        className={cn(
                                            "p-2 rounded-xl transition-all border",
                                            isExportMenuOpen
                                                ? "bg-scribehub-blue/5 text-scribehub-blue border-scribehub-blue/20"
                                                : "bg-gray-50/50 dark:bg-gray-800/50 text-gray-400 hover:text-gray-600 border-gray-100 dark:border-gray-700"
                                        )}
                                    >
                                        <MoreHorizontal className="h-4 w-4" />
                                    </button>

                                    {isExportMenuOpen && (
                                        <>
                                            <div className="fixed inset-0 z-40" onClick={() => setIsExportMenuOpen(false)}></div>
                                            <div className="absolute right-0 top-full z-50 mt-2 w-48 rounded-2xl border border-gray-100 bg-white p-2 shadow-2xl dark:border-gray-800 dark:bg-gray-900 animate-in fade-in zoom-in-95 duration-200">
                                                <div className="mb-2 px-3 py-1 text-[9px] font-black uppercase tracking-widest text-gray-400">Export As</div>
                                                <button 
                                                    onClick={() => handleExport('word')}
                                                    className="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-[11px] font-bold text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800 transition-all"
                                                >
                                                    <FileText className="h-3.5 w-3.5 text-blue-500" />
                                                    Microsoft Word (.docx)
                                                </button>
                                                <button 
                                                    onClick={() => handleExport('pdf')}
                                                    className="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-[11px] font-bold text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800 transition-all"
                                                >
                                                    <FileText className="h-3.5 w-3.5 text-red-500" />
                                                    PDF Document (.pdf)
                                                </button>
                                                <button 
                                                    onClick={() => handleExport('bibtex')}
                                                    className="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-[11px] font-bold text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800 transition-all"
                                                >
                                                    <Code className="h-3.5 w-3.5 text-purple-500" />
                                                    BibTeX (.bib)
                                                </button>
                                                <button 
                                                    onClick={() => handleExport('ris')}
                                                    className="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-[11px] font-bold text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800 transition-all"
                                                >
                                                    <Download className="h-3.5 w-3.5 text-emerald-500" />
                                                    RIS (EndNote, Zotero)
                                                </button>
                                            </div>
                                        </>
                                    )}
                                </div>
                            </div>
                        </div>

                        {/* Workspace Content - Full Paper Style */}
                        <div className="flex-1 bg-white dark:bg-[#1a1a1a] border border-gray-200 dark:border-gray-800 flex flex-col overflow-hidden relative shadow-sm">
                            <div className="absolute inset-0 overflow-y-auto custom-scrollbar px-8 py-12 md:px-16 md:py-16">
                                {localReferences && localReferences.length > 0 ? (
                                    <>
                                        {/* Paper Header */}
                                        <div className="text-center mb-8">
                                            <h2 className="text-xl font-bold text-black dark:text-white capitalize">{t.references}</h2>
                                        </div>

                                        <DndContext 
                                            sensors={sensors}
                                            collisionDetection={closestCenter}
                                            onDragEnd={handleReferenceDragEnd}
                                            modifiers={[restrictToVerticalAxis]}
                                        >
                                            <SortableContext 
                                                items={localReferences.map(r => r.id)}
                                                strategy={verticalListSortingStrategy}
                                            >
                                                <div className="flex flex-col gap-1">
                                                {localReferences.map((reference) => (
                                                    <SortableReferenceItem 
                                                        key={reference.id}
                                                        reference={reference}
                                                        t={t}
                                                        viewMode={viewMode}
                                                        handleDeleteClick={(ref: any) => {
                                                            setEditingReference(ref);
                                                            setIsDeleteReferenceOpen(true);
                                                        }}
                                                        handleEditClick={(ref: any) => {
                                                            setEditingReference(ref);
                                                            // Calculate authors
                                                            const authors = ref.authors ? ref.authors.map((a: string) => {
                                                                // Simple parser, in reality should be better
                                                                const parts = a.split(' ');
                                                                return {
                                                                    firstName: parts[0] || '',
                                                                    middleName: '',
                                                                    lastName: parts.slice(1).join(' ') || '',
                                                                    role: 'author'
                                                                };
                                                            }) : [{ firstName: '', middleName: '', lastName: '', role: 'author' }];

                                                            setSourceData({
                                                                type: ref.type || 'book',
                                                                title: ref.title || '',
                                                                year: ref.year || '',
                                                                year_suffix: ref.year_suffix || '',
                                                                isOnline: !!ref.url,
                                                                lang: 'en',
                                                                authors: authors,
                                                                extraFields: {}
                                                            });
                                                            setIsNewSourceOpen(true);
                                                        }}
                                                        handleCopyClick={(ref: any) => {
                                                            navigator.clipboard.writeText(ref.citation.replace(/<[^>]*>/g, ''));
                                                            toast.success(t.toasts.citationCopied);
                                                        }}
                                                        handleViewCitation={(ref: any) => {
                                                            setViewingCitation(ref);
                                                        }}
                                                        isNew={reference.id === newReferenceId}
                                                    />
                                                ))}
                                                </div>
                                            </SortableContext>
                                        </DndContext>
                                    </>
                                ) : (
                                    <div className="h-full flex flex-col items-center justify-center gap-4 text-center p-8">
                                        <div className="h-16 w-16 rounded-3xl bg-scribehub-blue/5 flex items-center justify-center animate-bounce duration-[2000ms]">
                                            <FileText className="h-8 w-8 text-scribehub-blue/40" />
                                        </div>
                                        <div className="space-y-1">
                                            <h3 className="text-sm font-black text-scribehub-blue dark:text-white uppercase tracking-widest">{t.workspace || 'Workspace'}</h3>
                                            <p className="text-xs font-medium text-gray-400">
                                                {selectedProjectId 
                                                    ? "No references in this project yet. Start by adding one!" 
                                                    : "Select a project to start research"}
                                            </p>
                                        </div>
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>
                </div>

                {/* Delete Reference Confirmation */}
                {/* Delete Reference Confirmation */}
                <Dialog open={isDeleteReferenceOpen} onOpenChange={setIsDeleteReferenceOpen}>
                    <DialogContent className="sm:max-w-[400px] rounded-3xl border-none p-0 overflow-hidden bg-white dark:bg-gray-900 shadow-2xl">
                        <div className="p-8 pb-4 text-center sm:text-center">
                            <div className="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/20">
                                <Trash2 className="h-6 w-6 text-red-600" />
                            </div>
                            <h2 className="text-xl font-black text-gray-900 dark:text-white">{t.delete}?</h2>
                        </div>
                        
                        <div className="px-8 py-4 text-center">
                            <p className="text-sm font-medium text-gray-500 dark:text-gray-400">
                                {t.confirmDelete} <br/>
                                <span className="font-bold text-gray-900 dark:text-white">"{editingReference?.title}"</span>
                            </p>
                        </div>

                        <DialogFooter className="bg-gray-50/50 p-8 dark:bg-gray-800/20 sm:justify-center flex-row items-center gap-4">
                            <button 
                                onClick={() => setIsDeleteReferenceOpen(false)}
                                className="flex-1 rounded-xl bg-white px-4 py-3 text-xs font-black uppercase tracking-widest text-gray-400 border border-gray-100 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 transition-all"
                            >
                                {t.cancel}
                            </button>
                            <button 
                                onClick={() => {
                                    if (editingReference) {
                                        router.delete(`/references/${editingReference.id}`, {
                                            onSuccess: () => {
                                                setIsDeleteReferenceOpen(false);
                                                setEditingReference(null);
                                                toast.success(t.toasts.referenceDeleted);
                                            }
                                        });
                                    }
                                }}
                                className="flex-1 rounded-xl bg-red-600 px-4 py-3 text-xs font-black uppercase tracking-widest text-white shadow-lg shadow-red-500/20 hover:bg-red-700 active:scale-95 transition-all"
                            >
                                {t.delete}
                            </button>
                        </DialogFooter>
                    </DialogContent>
                </Dialog>

                {/* View Citation Modal */}
                <Dialog open={!!viewingCitation} onOpenChange={() => setViewingCitation(null)}>
                    <DialogContent className="sm:max-w-[500px] rounded-3xl border-none p-0 overflow-hidden bg-white dark:bg-gray-900 shadow-2xl">
                        <DialogHeader className="p-8 pb-4">
                            <DialogTitle className="text-xl font-black text-scribehub-blue dark:text-white uppercase tracking-widest leading-none">{t.viewCitation || 'Citation'}</DialogTitle>
                        </DialogHeader>
                        <div className="px-8 py-2 space-y-6">
                            <div className="space-y-2">
                                <Label className="text-[10px] font-black uppercase tracking-widest text-gray-400">{t.bibliography}</Label>
                                <div className="rounded-2xl bg-gray-50 dark:bg-gray-800/50 p-6 border border-gray-100 dark:border-gray-700 relative group">
                                    <div 
                                        className="text-sm leading-relaxed text-gray-700 dark:text-gray-300 pr-8"
                                        dangerouslySetInnerHTML={{ __html: viewingCitation?.citation || viewingCitation?.title || '' }}
                                    />
                                    <button 
                                        onClick={() => {
                                            if (viewingCitation) {
                                                navigator.clipboard.writeText(viewingCitation.citation.replace(/<[^>]*>/g, ''));
                                                toast.success(t.toasts.citationCopied);
                                            }
                                        }}
                                        className="absolute top-4 right-4 p-2 rounded-lg bg-white dark:bg-gray-800 shadow-sm opacity-0 group-hover:opacity-100 transition-opacity hover:text-scribehub-blue"
                                    >
                                        <Copy className="h-3.5 w-3.5" />
                                    </button>
                                </div>
                            </div>

                            <div className="space-y-2">
                                <Label className="text-[10px] font-black uppercase tracking-widest text-gray-400">{t.inText}</Label>
                                <div className="rounded-2xl bg-gray-50 dark:bg-gray-800/50 p-4 border border-gray-100 dark:border-gray-700 relative group">
                                    <div className="text-sm font-bold text-gray-700 dark:text-gray-300">
                                        {(viewingCitation as any)?.citation_in_text}
                                    </div>
                                    <button 
                                        onClick={() => {
                                            if (viewingCitation) {
                                                navigator.clipboard.writeText((viewingCitation as any).citation_in_text);
                                                toast.success(t.toasts.citationCopied);
                                            }
                                        }}
                                        className="absolute top-3 right-4 p-2 rounded-lg bg-white dark:bg-gray-800 shadow-sm opacity-0 group-hover:opacity-100 transition-opacity hover:text-scribehub-blue"
                                    >
                                        <Copy className="h-3.5 w-3.5" />
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div className="flex items-center justify-end gap-3 bg-gray-50/50 dark:bg-gray-800/50 px-8 py-4">
                            <button 
                                onClick={() => setViewingCitation(null)}
                                className="px-5 py-2.5 text-xs font-black uppercase tracking-widest text-gray-500 hover:text-gray-700 transition-colors"
                            >
                                {t.cancel}
                            </button>
                            <button 
                                onClick={() => {
                                    if (viewingCitation) {
                                        setViewingCitation(null);
                                        setEditingReference(viewingCitation);
                                        // Calculate authors
                                        const authors = viewingCitation.authors ? viewingCitation.authors.map((a: string) => {
                                            const parts = a.split(' ');
                                            return {
                                                firstName: parts[0] || '',
                                                middleName: '',
                                                lastName: parts.slice(1).join(' ') || '',
                                                role: 'author'
                                            };
                                        }) : [{ firstName: '', middleName: '', lastName: '', role: 'author' }];

                                        setSourceData({
                                            type: viewingCitation.type || 'book',
                                            title: viewingCitation.title || '',
                                            year: viewingCitation.year || '',
                                            year_suffix: (viewingCitation as any).year_suffix || '',
                                            isOnline: !!viewingCitation.url,
                                            lang: 'en',
                                            authors: authors,
                                            extraFields: {}
                                        });
                                        setIsNewSourceOpen(true);
                                    }
                                }}
                                className="rounded-xl bg-white border border-gray-200 px-6 py-2.5 text-xs font-black uppercase tracking-widest text-gray-600 hover:bg-gray-50 transition-all active:scale-95 flex items-center gap-2"
                            >
                                <Edit className="h-3.5 w-3.5" />
                                {t.edit}
                            </button>
                            <button 
                                onClick={() => {
                                    if (viewingCitation) {
                                        navigator.clipboard.writeText(viewingCitation.citation.replace(/<[^>]*>/g, ''));
                                        setCopySuccess(true);
                                        toast.success(t.toasts.citationCopied);
                                        setTimeout(() => setCopySuccess(false), 2000);
                                    }
                                }}
                                className={cn(
                                    "rounded-xl px-6 py-2.5 text-xs font-black uppercase tracking-widest text-white transition-all shadow-lg active:scale-95 flex items-center gap-2",
                                    copySuccess ? "bg-emerald-500 shadow-emerald-500/20" : "bg-scribehub-blue hover:bg-scribehub-blue/90 shadow-scribehub-blue/20"
                                )}
                            >
                                {copySuccess ? <Check className="h-3.5 w-3.5" /> : <Copy className="h-3.5 w-3.5" />}
                                {copySuccess ? 'Copied' : t.copyCitation}
                            </button>
                        </div>
                    </DialogContent>
                </Dialog>

                <Dialog open={isCreateProjectOpen} onOpenChange={setIsCreateProjectOpen}>
                    <DialogContent className="sm:max-w-[425px] rounded-3xl border-none p-0 overflow-hidden bg-white dark:bg-gray-900 shadow-2xl">
                        <form onSubmit={handleCreateProject}>
                            <DialogHeader className="p-8 pb-4">
                                <DialogTitle className="text-2xl font-black text-scribehub-blue dark:text-white">{t.newProject}</DialogTitle>
                            </DialogHeader>
                            
                            <div className="px-8 space-y-6 py-4">
                                <div className="space-y-2">
                                    <Label htmlFor="name" className="text-xs font-black uppercase tracking-widest text-gray-400">{t.projectName}</Label>
                                    <Input 
                                        id="name" 
                                        value={data.name} 
                                        onChange={e => setData('name', e.target.value)}
                                        className="h-12 rounded-xl border-gray-100 bg-gray-50/50 px-4 font-bold focus:border-scribehub-blue/50 focus:ring-scribehub-blue/10 dark:border-gray-800 dark:bg-gray-800/50 dark:text-white"
                                        placeholder="e.g. My Thesis"
                                        required
                                    />
                                    {errors.name && <p className="text-[10px] font-bold text-red-500">{errors.name}</p>}
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="description" className="text-xs font-black uppercase tracking-widest text-gray-400">{t.projectDescription}</Label>
                                    <Input 
                                        id="description" 
                                        value={data.description} 
                                        onChange={e => setData('description', e.target.value)}
                                        className="h-12 rounded-xl border-gray-100 bg-gray-50/50 px-4 font-bold focus:border-scribehub-blue/50 focus:ring-scribehub-blue/10 dark:border-gray-800 dark:bg-gray-800/50 dark:text-white"
                                        placeholder="..."
                                    />
                                </div>

                                <div className="space-y-3">
                                    <Label className="text-xs font-black uppercase tracking-widest text-gray-400">Project Color</Label>
                                    <div className="flex flex-wrap gap-3">
                                        {projectColors.map(color => (
                                            <button
                                                key={color}
                                                type="button"
                                                onClick={() => setData('color', color)}
                                                className={cn(
                                                    "h-8 w-8 rounded-full transition-all hover:scale-110 active:scale-95",
                                                    data.color === color ? "ring-4 ring-scribehub-blue/20 ring-offset-2 dark:ring-offset-gray-900" : ""
                                                )}
                                                style={{ backgroundColor: color }}
                                            />
                                        ))}
                                    </div>
                                </div>

                                <div className="space-y-3">
                                    <Label className="text-xs font-black uppercase tracking-widest text-gray-400">Project Icon</Label>
                                    <div className="grid grid-cols-7 gap-3">
                                        {availableIcons.map(item => (
                                            <button
                                                key={item.name}
                                                type="button"
                                                onClick={() => setData('icon', item.name)}
                                                className={cn(
                                                    "h-9 w-9 flex items-center justify-center rounded-xl transition-all hover:bg-gray-100 dark:hover:bg-gray-800 active:scale-95",
                                                    data.icon === item.name ? "bg-scribehub-blue/10 text-scribehub-blue ring-2 ring-scribehub-blue/20" : "text-gray-400"
                                                )}
                                            >
                                                <item.icon className="h-5 w-5" />
                                            </button>
                                        ))}
                                    </div>
                                </div>
                            </div>

                            <DialogFooter className="bg-gray-50/50 p-8 dark:bg-gray-800/20 sm:justify-between flex-row items-center gap-4">
                                <button 
                                    type="button"
                                    onClick={() => setIsCreateProjectOpen(false)}
                                    className="text-xs font-black uppercase tracking-widest text-gray-400 hover:text-gray-600 transition-colors"
                                >
                                    {t.cancel}
                                </button>
                                <button 
                                    type="submit"
                                    disabled={processing}
                                    className="inline-flex h-12 items-center gap-2 rounded-xl bg-scribehub-blue px-8 text-xs font-black text-white shadow-xl shadow-blue-500/20 transition-all hover:opacity-90 active:scale-95 disabled:opacity-50"
                                >
                                    {processing ? <Loader2 className="h-4 w-4 animate-spin" /> : t.createProject}
                                </button>
                            </DialogFooter>
                        </form>
                    </DialogContent>
                </Dialog>
                {/* Edit Project Modal */}
                <Dialog open={isEditProjectOpen} onOpenChange={setIsEditProjectOpen}>
                    <DialogContent className="sm:max-w-[425px] rounded-3xl border-none p-0 overflow-hidden bg-white dark:bg-gray-900 shadow-2xl">
                        <form onSubmit={handleEditProject}>
                            <DialogHeader className="p-8 pb-4">
                                <DialogTitle className="text-2xl font-black text-scribehub-blue dark:text-white">Project Settings</DialogTitle>
                            </DialogHeader>
                            
                            <div className="px-8 space-y-6 py-4">
                                <div className="space-y-2">
                                    <Label htmlFor="edit-name" className="text-xs font-black uppercase tracking-widest text-gray-400">{t.projectName}</Label>
                                    <Input 
                                        id="edit-name" 
                                        value={editForm.data.name} 
                                        onChange={e => editForm.setData('name', e.target.value)}
                                        className="h-12 rounded-xl border-gray-100 bg-gray-50/50 px-4 font-bold focus:border-scribehub-blue/50 focus:ring-scribehub-blue/10 dark:border-gray-800 dark:bg-gray-800/50 dark:text-white"
                                        placeholder="e.g. My Thesis"
                                        required
                                    />
                                    {editForm.errors.name && <p className="text-[10px] font-bold text-red-500">{editForm.errors.name}</p>}
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="edit-description" className="text-xs font-black uppercase tracking-widest text-gray-400">{t.projectDescription}</Label>
                                    <Input 
                                        id="edit-description" 
                                        value={editForm.data.description} 
                                        onChange={e => editForm.setData('description', e.target.value)}
                                        className="h-12 rounded-xl border-gray-100 bg-gray-50/50 px-4 font-bold focus:border-scribehub-blue/50 focus:ring-scribehub-blue/10 dark:border-gray-800 dark:bg-gray-800/50 dark:text-white"
                                        placeholder="..."
                                    />
                                </div>

                                <div className="space-y-3">
                                    <Label className="text-xs font-black uppercase tracking-widest text-gray-400">Project Color</Label>
                                    <div className="flex flex-wrap gap-3">
                                        {projectColors.map(color => (
                                            <button
                                                key={color}
                                                type="button"
                                                onClick={() => editForm.setData('color', color)}
                                                className={cn(
                                                    "h-8 w-8 rounded-full transition-all hover:scale-110 active:scale-95",
                                                    editForm.data.color === color ? "ring-4 ring-scribehub-blue/20 ring-offset-2 dark:ring-offset-gray-900" : ""
                                                )}
                                                style={{ backgroundColor: color }}
                                            />
                                        ))}
                                    </div>
                                </div>

                                <div className="space-y-3">
                                    <Label className="text-xs font-black uppercase tracking-widest text-gray-400">Project Icon</Label>
                                    <div className="grid grid-cols-7 gap-3">
                                        {availableIcons.map(item => (
                                            <button
                                                key={item.name}
                                                type="button"
                                                onClick={() => editForm.setData('icon', item.name)}
                                                className={cn(
                                                    "h-9 w-9 flex items-center justify-center rounded-xl transition-all hover:bg-gray-100 dark:hover:bg-gray-800 active:scale-95",
                                                    editForm.data.icon === item.name ? "bg-scribehub-blue/10 text-scribehub-blue ring-2 ring-scribehub-blue/20" : "text-gray-400"
                                                )}
                                            >
                                                <item.icon className="h-5 w-5" />
                                            </button>
                                        ))}
                                    </div>
                                </div>
                            </div>

                            <DialogFooter className="bg-gray-50/50 p-8 dark:bg-gray-800/20 sm:justify-between flex-row items-center gap-4">
                                <button 
                                    type="button"
                                    onClick={() => setIsEditProjectOpen(false)}
                                    className="text-xs font-black uppercase tracking-widest text-gray-400 hover:text-gray-600 transition-colors"
                                >
                                    {t.cancel}
                                </button>
                                <button 
                                    type="submit"
                                    disabled={editForm.processing}
                                    className="inline-flex h-12 items-center gap-2 rounded-xl bg-scribehub-blue px-8 text-xs font-black text-white shadow-xl shadow-blue-500/20 transition-all hover:opacity-90 active:scale-95 disabled:opacity-50"
                                >
                                    {editForm.processing ? <Loader2 className="h-4 w-4 animate-spin" /> : 'Save Changes'}
                                </button>
                            </DialogFooter>
                        </form>
                    </DialogContent>
                </Dialog>

                {/* Delete Project Modal */}
                <Dialog open={isDeleteProjectOpen} onOpenChange={setIsDeleteProjectOpen}>
                    <DialogContent className="sm:max-w-[400px] rounded-3xl border-none p-0 overflow-hidden bg-white dark:bg-gray-900 shadow-2xl">
                        <form onSubmit={handleDeleteProject}>
                            <DialogHeader className="p-8 pb-4 text-center sm:text-center">
                                <div className="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/20">
                                    <Trash2 className="h-6 w-6 text-red-600" />
                                </div>
                                <DialogTitle className="text-xl font-black text-gray-900 dark:text-white">Delete Project?</DialogTitle>
                            </DialogHeader>
                            
                            <div className="px-8 py-4 text-center">
                                <p className="text-sm font-medium text-gray-500 dark:text-gray-400">
                                    Are you sure you want to delete <span className="font-bold text-gray-900 dark:text-white">"{editingProject?.name}"</span>? 
                                    This action cannot be undone and all references within this project will be decoupled.
                                </p>
                            </div>

                            <DialogFooter className="bg-gray-50/50 p-8 dark:bg-gray-800/20 sm:justify-center flex-row items-center gap-4">
                                <button 
                                    type="button"
                                    onClick={() => setIsDeleteProjectOpen(false)}
                                    className="flex-1 rounded-xl bg-white px-4 py-3 text-xs font-black uppercase tracking-widest text-gray-400 border border-gray-100 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 transition-all"
                                >
                                    {t.cancel}
                                </button>
                                <button 
                                    type="submit"
                                    className="flex-1 rounded-xl bg-red-600 px-4 py-3 text-xs font-black uppercase tracking-widest text-white shadow-lg shadow-red-500/20 hover:bg-red-700 active:scale-95 transition-all"
                                >
                                    {t.delete}
                                </button>
                            </DialogFooter>
                        </form>
                    </DialogContent>
                </Dialog>

                {/* Add Folder Modal */}
                <Dialog open={isAddFolderOpen} onOpenChange={setIsAddFolderOpen}>
                    <DialogContent className="sm:max-w-[400px] rounded-3xl border-none p-0 overflow-hidden bg-white dark:bg-gray-900 shadow-2xl">
                        <form onSubmit={handleAddFolder}>
                            <DialogHeader className="p-8 pb-4">
                                <DialogTitle className="text-2xl font-black text-scribehub-blue dark:text-white">{t.newFolder}</DialogTitle>
                            </DialogHeader>
                            
                             <div className="px-8 space-y-4 py-4">
                                <div className="space-y-2">
                                    <Label className="text-xs font-black uppercase tracking-widest text-gray-400">Select Project</Label>
                                    <Select 
                                        value={folderForm.data.project_id.toString()} 
                                        onValueChange={(value) => folderForm.setData('project_id', value)}
                                    >
                                        <SelectTrigger className="h-12 rounded-xl border-gray-100 bg-gray-50/50 px-4 font-bold focus:border-scribehub-blue/50 focus:ring-scribehub-blue/10 dark:border-gray-800 dark:bg-gray-800/50 dark:text-white">
                                            <SelectValue placeholder="Select a project" />
                                        </SelectTrigger>
                                        <SelectContent className="rounded-2xl border-gray-100 dark:border-gray-800">
                                            {projects.map((project) => (
                                                <SelectItem 
                                                    key={project.id} 
                                                    value={project.id.toString()}
                                                    className="font-bold text-xs"
                                                >
                                                    <div className="flex items-center gap-2">
                                                        <div className="h-2 w-2 rounded-full" style={{ backgroundColor: project.color }}></div>
                                                        {project.name}
                                                    </div>
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>
                                    {folderForm.errors.project_id && <p className="text-[10px] font-bold text-red-500">{folderForm.errors.project_id}</p>}
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="folder-name" className="text-xs font-black uppercase tracking-widest text-gray-400">Folder Name</Label>
                                    <Input 
                                        id="folder-name" 
                                        value={folderForm.data.name} 
                                        onChange={e => folderForm.setData('name', e.target.value)}
                                        className="h-12 rounded-xl border-gray-100 bg-gray-50/50 px-4 font-bold focus:border-scribehub-blue/50 focus:ring-scribehub-blue/10 dark:border-gray-800 dark:bg-gray-800/50 dark:text-white"
                                        placeholder="e.g. Research Papers"
                                        required
                                    />
                                    {folderForm.errors.name && <p className="text-[10px] font-bold text-red-500">{folderForm.errors.name}</p>}
                                </div>
                            </div>

                            <DialogFooter className="bg-gray-50/50 p-8 dark:bg-gray-800/20 sm:justify-between flex-row items-center gap-4">
                                <button 
                                    type="button"
                                    onClick={() => setIsAddFolderOpen(false)}
                                    className="text-xs font-black uppercase tracking-widest text-gray-400 hover:text-gray-600 transition-colors"
                                >
                                    {t.cancel}
                                </button>
                                <button 
                                    type="submit"
                                    className="inline-flex h-12 items-center gap-2 rounded-xl bg-scribehub-blue px-8 text-xs font-black text-white shadow-xl shadow-blue-500/20 transition-all hover:opacity-90 active:scale-95"
                                >
                                    Create Folder
                                </button>
                            </DialogFooter>
                        </form>
                    </DialogContent>
                </Dialog>
                {/* Edit Folder Modal */}
                <Dialog open={isEditFolderOpen} onOpenChange={setIsEditFolderOpen}>
                    <DialogContent className="sm:max-w-[400px] rounded-3xl border-none p-0 overflow-hidden bg-white dark:bg-gray-900 shadow-2xl">
                        <form onSubmit={handleEditFolder}>
                            <DialogHeader className="p-8 pb-4">
                                <DialogTitle className="text-2xl font-black text-scribehub-blue dark:text-white">{t.rename} {t.newFolder}</DialogTitle>
                            </DialogHeader>
                            
                            <div className="px-8 space-y-4 py-4">
                                <div className="space-y-2">
                                    <Label htmlFor="edit-folder-name" className="text-xs font-black uppercase tracking-widest text-gray-400">Folder Name</Label>
                                    <Input 
                                        id="edit-folder-name" 
                                        value={editFolderForm.data.name} 
                                        onChange={e => editFolderForm.setData('name', e.target.value)}
                                        className="h-12 rounded-xl border-gray-100 bg-gray-50/50 px-4 font-bold focus:border-scribehub-blue/50 focus:ring-scribehub-blue/10 dark:border-gray-800 dark:bg-gray-800/50 dark:text-white"
                                        placeholder="e.g. Research Papers"
                                        required
                                    />
                                    {editFolderForm.errors.name && <p className="text-[10px] font-bold text-red-500">{editFolderForm.errors.name}</p>}
                                </div>
                            </div>

                            <DialogFooter className="bg-gray-50/50 p-8 dark:bg-gray-800/20 sm:justify-between flex-row items-center gap-4">
                                <button 
                                    type="button"
                                    onClick={() => setIsEditFolderOpen(false)}
                                    className="text-xs font-black uppercase tracking-widest text-gray-400 hover:text-gray-600 transition-colors"
                                >
                                    {t.cancel}
                                </button>
                                <button 
                                    type="submit"
                                    disabled={editFolderForm.processing}
                                    className="inline-flex h-12 items-center gap-2 rounded-xl bg-scribehub-blue px-8 text-xs font-black text-white shadow-xl shadow-blue-500/20 transition-all hover:opacity-90 active:scale-95 disabled:opacity-50"
                                >
                                    {editFolderForm.processing ? <Loader2 className="h-4 w-4 animate-spin" /> : 'Update Folder'}
                                </button>
                            </DialogFooter>
                        </form>
                    </DialogContent>
                </Dialog>

                {/* Delete Folder Confirmation Modal */}
                <Dialog open={isDeleteFolderConfirmOpen} onOpenChange={setIsDeleteFolderConfirmOpen}>
                    <DialogContent className="sm:max-w-[400px] rounded-3xl border-none p-0 overflow-hidden bg-white dark:bg-gray-900 shadow-2xl">
                        <form onSubmit={handleDeleteFolder}>
                            <DialogHeader className="p-8 pb-4 text-center sm:text-center">
                                <div className="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/20">
                                    <Trash2 className="h-6 w-6 text-red-600" />
                                </div>
                                <DialogTitle className="text-xl font-black text-gray-900 dark:text-white">Delete Folder?</DialogTitle>
                            </DialogHeader>
                            
                            <div className="px-8 py-4 text-center">
                                <p className="text-sm font-medium text-gray-500 dark:text-gray-400">
                                    Are you sure you want to delete folder <span className="font-bold text-gray-900 dark:text-white">"{editingFolder?.name}"</span>? 
                                    All references in this folder will remain in the project but will no longer be categorized.
                                </p>
                            </div>

                            <DialogFooter className="bg-gray-50/50 p-8 dark:bg-gray-800/20 sm:justify-center flex-row items-center gap-4">
                                <button 
                                    type="button"
                                    onClick={() => setIsDeleteFolderConfirmOpen(false)}
                                    className="flex-1 rounded-xl bg-white px-4 py-3 text-xs font-black uppercase tracking-widest text-gray-400 border border-gray-100 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 transition-all"
                                >
                                    {t.cancel}
                                </button>
                                <button 
                                    type="submit"
                                    className="flex-1 rounded-xl bg-red-600 px-4 py-3 text-xs font-black uppercase tracking-widest text-white shadow-lg shadow-red-500/20 hover:bg-red-700 active:scale-95 transition-all"
                                >
                                    {t.delete}
                                </button>
                            </DialogFooter>
                        </form>
                    </DialogContent>
                </Dialog>
                {/* New Source Modal */}
                <Dialog open={isNewSourceOpen} onOpenChange={setIsNewSourceOpen}>
                    <DialogContent className="max-w-[95vw] w-[95vw] h-[95vh] overflow-hidden rounded-[2.5rem] border-none p-0 bg-white dark:bg-gray-900 shadow-2xl flex flex-col transition-all duration-500">
                        <form onSubmit={handleNewSourceSubmit} className="flex flex-col h-full overflow-hidden">
                            <DialogHeader className="p-8 pb-4 flex flex-row items-center justify-between shrink-0">
                                <DialogTitle className="text-2xl font-black text-scribehub-blue dark:text-white">{t.newSource}</DialogTitle>
                                <div className="relative">
                                    <button 
                                        type="button"
                                        onClick={() => setIsAddFieldOpen(!isAddFieldOpen)}
                                        className="flex items-center gap-2 rounded-xl bg-gray-50 dark:bg-gray-800 px-4 py-2 text-[10px] font-black uppercase tracking-widest text-gray-500 hover:text-scribehub-blue transition-all border border-gray-100 dark:border-gray-700"
                                    >
                                        <Plus className="h-3.5 w-3.5" />
                                        {t.addField}
                                    </button>
                                    
                                    {isAddFieldOpen && (
                                        <>
                                            <div className="fixed inset-0 z-40" onClick={() => setIsAddFieldOpen(false)}></div>
                                            <div className="absolute right-0 top-full z-50 mt-2 w-64 rounded-2xl border border-gray-100 bg-white p-2 shadow-2xl dark:border-gray-800 dark:bg-gray-900 animate-in fade-in zoom-in-95 duration-200">
                                                <div className="p-2">
                                                    <Input placeholder={t.searchFields} className="h-9 mb-2 text-xs" />
                                                    <div className="max-h-64 overflow-y-auto space-y-1 custom-scrollbar">
                                                        {availableFields.map(field => (
                                                            <label key={field.id} className="flex items-center gap-3 p-2 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 cursor-pointer">
                                                                <Checkbox 
                                                                    checked={activeFields.includes(field.id)}
                                                                    onCheckedChange={() => toggleField(field.id)}
                                                                />
                                                                <span className="text-xs font-bold text-gray-600 dark:text-gray-400">{language === 'en' ? field.name : field.thName}</span>
                                                            </label>
                                                        ))}
                                                    </div>
                                                </div>
                                            </div>
                                        </>
                                    )}
                                </div>
                            </DialogHeader>
                            
                            <div className="flex-1 overflow-y-auto px-8 py-4 space-y-8 custom-scrollbar min-h-0">
                                {/* Type & Logic Selection */}
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 rounded-[2rem] bg-gray-50/50 dark:bg-gray-800/30 border border-gray-100 dark:border-gray-800">
                                    <div className="space-y-3">
                                        <Label className="text-[10px] font-black uppercase tracking-widest text-gray-400">{t.primaryLanguage}</Label>
                                        <div className="flex bg-white dark:bg-gray-900 p-1 rounded-xl border border-gray-100 dark:border-gray-800">
                                            <button 
                                                type="button"
                                                onClick={() => setSourceData(prev => ({ ...prev, lang: 'en' }))}
                                                className={cn("flex-1 py-2 text-[10px] font-black uppercase tracking-widest rounded-lg transition-all", sourceData.lang === 'en' ? "bg-scribehub-blue text-white shadow-lg" : "text-gray-400 hover:text-gray-600")}
                                            >
                                                English (EN)
                                            </button>
                                            <button 
                                                type="button"
                                                onClick={() => setSourceData(prev => ({ ...prev, lang: 'th' }))}
                                                className={cn("flex-1 py-2 text-[10px] font-black uppercase tracking-widest rounded-lg transition-all", sourceData.lang === 'th' ? "bg-scribehub-blue text-white shadow-lg" : "text-gray-400 hover:text-gray-600")}
                                            >
                                                ภาษาไทย (TH)
                                            </button>
                                        </div>
                                    </div>

                                    <div className="space-y-3">
                                        <Label className="text-[10px] font-black uppercase tracking-widest text-gray-400">{t.resourceType}</Label>
                                        <Select value={sourceData.type} onValueChange={(v) => setSourceData(prev => ({ ...prev, type: v }))}>
                                            <SelectTrigger className="h-12 rounded-xl bg-white dark:bg-gray-900 border-gray-100 dark:border-gray-800 px-4 font-bold">
                                                <SelectValue />
                                            </SelectTrigger>
                                            <SelectContent className="rounded-2xl border-gray-100 dark:border-gray-800">
                                                {sourceTypes.map(type => (
                                                    <SelectItem key={type.id} value={type.id} className="font-bold text-xs">
                                                        <div className="flex items-center gap-2">
                                                            <type.icon className="h-3.5 w-3.5 text-scribehub-blue" />
                                                            {language === 'en' ? type.name : type.thName}
                                                        </div>
                                                    </SelectItem>
                                                ))}
                                            </SelectContent>
                                        </Select>
                                    </div>

                                    <div className="flex flex-col gap-4">
                                        <div className="flex items-center gap-4">
                                            <Checkbox 
                                                id="isOnline"
                                                checked={sourceData.isOnline}
                                                onCheckedChange={(v) => {
                                                    const isOnline = v as boolean;
                                                    setSourceData(prev => ({ ...prev, isOnline }));
                                                    // Auto-add URL field if not already there
                                                    if (isOnline && !activeFields.includes('url')) {
                                                        setActiveFields(prev => [...prev, 'url']);
                                                    }
                                                }}
                                            />
                                            <Label htmlFor="isOnline" className="text-xs font-bold text-gray-600 dark:text-gray-400 cursor-pointer">{t.isOnline}</Label>
                                        </div>

                                        {sourceData.isOnline && (
                                            <div className="animate-in slide-in-from-top-2 duration-300">
                                                <Label className="text-[10px] font-black uppercase tracking-widest text-scribehub-blue">Source URL</Label>
                                                <Input 
                                                    value={sourceData.extraFields.url || ''}
                                                    onChange={e => setSourceData(prev => ({ 
                                                        ...prev, 
                                                        extraFields: { ...prev.extraFields, url: e.target.value }
                                                    }))}
                                                    className="h-11 mt-1 rounded-xl bg-white dark:bg-gray-900 border-scribehub-blue/30 px-4 font-bold text-scribehub-blue"
                                                    placeholder="https://example.com/source-link"
                                                />
                                            </div>
                                        )}
                                    </div>
                                </div>

                                {/* Main Fields */}
                                <div className="space-y-6">
                                    <div className="space-y-2">
                                        <div className="flex items-center gap-2 mb-1">
                                            <div className="h-1.5 w-1.5 rounded-full bg-scribehub-blue"></div>
                                            <Label className="text-[10px] font-black uppercase tracking-widest text-scribehub-blue">{t.whatToCite}</Label>
                                        </div>
                                        <Label className="text-[10px] font-black uppercase tracking-widest text-gray-400">{t.entryTitle}</Label>
                                        <Input 
                                            value={sourceData.title}
                                            onChange={e => setSourceData(prev => ({ ...prev, title: e.target.value }))}
                                            className="h-14 rounded-2xl bg-gray-50/50 dark:bg-gray-800/50 border-gray-100 dark:border-gray-800 px-6 font-bold text-lg"
                                            placeholder="..."
                                            required
                                        />
                                    </div>

                                    {/* Authors Section */}
                                    <div className="space-y-4">
                                        <div className="flex items-center justify-between">
                                            <div className="flex items-center gap-2">
                                                <div className="h-1.5 w-1.5 rounded-full bg-scribehub-blue"></div>
                                                <Label className="text-[10px] font-black uppercase tracking-widest text-scribehub-blue">{t.whoCreated}</Label>
                                            </div>
                                            <button 
                                                type="button" 
                                                onClick={handleAddAuthor}
                                                className="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-scribehub-blue hover:opacity-70"
                                            >
                                                <UserPlus className="h-3.5 w-3.5" />
                                                {t.addContributor}
                                            </button>
                                        </div>

                                        <div className="space-y-4">
                                            {sourceData.authors.map((author, index) => (
                                                <div key={index} className="grid grid-cols-12 gap-3 items-end animate-in fade-in slide-in-from-right-2 duration-300 bg-gray-50/50 dark:bg-gray-800/20 p-4 rounded-2xl border border-gray-100 dark:border-gray-800">
                                                    <div className="col-span-12 md:col-span-3 space-y-2">
                                                        <Label className="text-[10px] font-black uppercase tracking-widest text-gray-400">{t.role}</Label>
                                                        <Select 
                                                            value={author.role} 
                                                            onValueChange={(v) => handleAuthorChange(index, 'role', v)}
                                                        >
                                                            <SelectTrigger className="h-11 rounded-xl bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-800 font-bold text-xs">
                                                                <SelectValue />
                                                            </SelectTrigger>
                                                            <SelectContent>
                                                                {authorRoles.map(role => (
                                                                    <SelectItem key={role.id} value={role.id} className="font-bold text-xs">{language === 'en' ? role.name : role.thName}</SelectItem>
                                                                ))}
                                                            </SelectContent>
                                                        </Select>
                                                    </div>
                                                    <div className="col-span-4 md:col-span-3 space-y-2">
                                                        <Label className="text-[10px] font-black uppercase tracking-widest text-gray-400">{t.firstName}</Label>
                                                        <Input 
                                                            value={author.firstName}
                                                            onChange={e => handleAuthorChange(index, 'firstName', e.target.value)}
                                                            className="h-11 rounded-xl bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-800 px-4 font-bold"
                                                            placeholder="..."
                                                        />
                                                    </div>
                                                    <div className="col-span-4 md:col-span-2 space-y-2">
                                                        <Label className="text-[10px] font-black uppercase tracking-widest text-gray-400">{t.middleName}</Label>
                                                        <Input 
                                                            value={author.middleName}
                                                            onChange={e => handleAuthorChange(index, 'middleName', e.target.value)}
                                                            className="h-11 rounded-xl bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-800 px-4 font-bold"
                                                            placeholder="..."
                                                        />
                                                    </div>
                                                    <div className="col-span-4 md:col-span-3 space-y-2">
                                                        <Label className="text-[10px] font-black uppercase tracking-widest text-gray-400">{t.lastName}</Label>
                                                        <Input 
                                                            value={author.lastName}
                                                            onChange={e => handleAuthorChange(index, 'lastName', e.target.value)}
                                                            className="h-11 rounded-xl bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-800 px-4 font-bold"
                                                            placeholder="..."
                                                        />
                                                    </div>
                                                    {sourceData.authors.length > 1 && (
                                                        <div className="col-span-12 md:col-span-1 flex justify-center pb-2">
                                                            <button 
                                                                type="button"
                                                                onClick={() => handleRemoveAuthor(index)}
                                                                className="p-2 text-red-400 hover:text-red-500 transition-colors bg-red-50 dark:bg-red-900/10 rounded-lg"
                                                            >
                                                                <Trash2 className="h-4 w-4" />
                                                            </button>
                                                        </div>
                                                    )}
                                                </div>
                                            ))}
                                        </div>
                                    </div>

                                    {/* Optional Dynamic Fields */}
                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                                        <div className="space-y-4">
                                            <div className="space-y-2">
                                                <div className="flex items-center gap-2 mb-1">
                                                    <div className="h-1.5 w-1.5 rounded-full bg-scribehub-blue"></div>
                                                    <Label className="text-[10px] font-black uppercase tracking-widest text-scribehub-blue">{t.when}</Label>
                                                </div>
                                                <Label className="text-[10px] font-black uppercase tracking-widest text-gray-400">{t.yearPublished}</Label>
                                                <Input 
                                                    value={sourceData.year}
                                                    onChange={e => setSourceData(prev => ({ ...prev, year: e.target.value }))}
                                                    className="h-12 rounded-xl bg-gray-50 dark:bg-gray-800 border-none px-4 font-bold"
                                                    placeholder="..."
                                                />
                                            </div>

                                            <div className="space-y-2">
                                                <Label className="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                                    {sourceData.lang === 'th' ? "อักษรลำดับหลังปี (ก, ข, ...)" : "Year Suffix (a, b, ...)"}
                                                </Label>
                                                <Input 
                                                    value={sourceData.year_suffix}
                                                    onChange={e => setSourceData(prev => ({ ...prev, year_suffix: e.target.value }))}
                                                    className="h-12 rounded-xl bg-gray-50 dark:bg-gray-800 border-none px-4 font-bold"
                                                    placeholder={sourceData.lang === 'th' ? "ก" : "a"}
                                                />
                                            </div>
                                        </div>

                                        {activeFields.map(fieldId => {
                                            const field = availableFields.find(f => f.id === fieldId);
                                            if (!field) return null;
                                            return (
                                                <div key={field.id} className="space-y-2 animate-in zoom-in-95 duration-200">
                                                    <Label className="text-[10px] font-black uppercase tracking-widest text-gray-400">{language === 'en' ? field.name : field.thName}</Label>
                                                    <Input 
                                                        value={sourceData.extraFields[field.id] || ''}
                                                        onChange={e => setSourceData(prev => ({ 
                                                            ...prev, 
                                                            extraFields: { ...prev.extraFields, [field.id]: e.target.value }
                                                        }))}
                                                        className="h-12 rounded-xl bg-gray-50 dark:bg-gray-800 border-none px-4 font-bold"
                                                        placeholder="..."
                                                    />
                                                </div>
                                            );
                                        })}
                                    </div>
                                </div>
                            </div>

                            <DialogFooter className="bg-gray-50/50 dark:bg-gray-800/20 p-8 flex flex-col md:flex-row items-center justify-between gap-6 border-t border-gray-100 dark:border-gray-800 shrink-0">
                                <div className="flex-1 w-full p-4 rounded-2xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800">
                                    <div className="flex items-center gap-2 mb-2">
                                        <Sparkles className="h-3 w-3 text-scribehub-blue" />
                                        <span className="text-[8px] font-black uppercase tracking-[0.2em] text-scribehub-blue">{t.preview}</span>
                                    </div>
                                    <p className="text-[11px] font-medium text-gray-500 italic">
                                        {sourceData.authors[0].lastName || '...'}, {sourceData.authors[0].firstName || '...'} {sourceData.authors[0].middleName || ''} ({sourceData.year || '....'}). {sourceData.title || '....'}. {sourceData.extraFields.publisher || '....'}...
                                    </p>
                                </div>
                                <div className="flex items-center gap-4 w-full md:w-auto">
                                    <button 
                                        type="button" 
                                        onClick={() => setIsNewSourceOpen(false)}
                                        className="flex-1 md:flex-none text-xs font-black uppercase tracking-widest text-gray-400 hover:text-gray-600 transition-colors"
                                    >
                                        {t.cancel}
                                    </button>
                                    <button 
                                        type="submit"
                                        className="flex-1 md:flex-none inline-flex h-14 items-center justify-center gap-3 rounded-2xl bg-scribehub-blue px-10 text-xs font-black text-white shadow-xl shadow-blue-500/20 transition-all hover:scale-[1.02] active:scale-[0.98]"
                                    >
                                        <Plus className="h-4 w-4" />
                                        {t.addToProject}
                                    </button>
                                </div>
                            </DialogFooter>
                        </form>
                    </DialogContent>
                </Dialog>
            </div>
            {/* Hidden File Input */}
            <input 
                type="file" 
                ref={fileInputRef}
                onChange={handleFileChange}
                accept=".bib,.txt"
                className="hidden"
            />
        </AppLayout>
    );
}
