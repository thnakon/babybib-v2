import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { Head, Link, router, useForm, usePage } from '@inertiajs/react';
import { 
    ArrowLeft, Edit, Trash2, FolderOpen, FileText, Plus, X, 
    BookOpen, LayoutGrid, List as ListIcon, Users, FileStack, 
    Activity, CheckCircle2, Clock, AlertCircle, MoreHorizontal,
    GripVertical, UserPlus, Upload, Download, MessageSquare,
    Send, Calendar, Search, TrendingUp, MoreVertical, Star,
    CheckCircle, Timer, Paperclip, Image as ImageIcon, Smile,
    MoreVertical as MoreVerticalIcon, Link as LinkIcon, Copy, RefreshCcw
} from 'lucide-react';
import { useState, useMemo, useEffect, useRef } from 'react';
import { formatDistanceToNow } from 'date-fns';
import axios from 'axios';
import { cn } from '@/lib/utils';
import { useLanguage } from '@/contexts/language-context';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogFooter } from '@/components/ui/dialog';
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
import { toast } from 'sonner';
import { 
    Select, 
    SelectContent, 
    SelectItem, 
    SelectTrigger, 
    SelectValue 
} from '@/components/ui/select';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';

import { TaskSidePanel } from '@/components/projects/task-side-panel';
import { Project, Task, User, Reference, ProjectFile, Comment, Member } from '@/types/project';

interface Props {
    project: Project;
    availableReferences: Reference[];
}

// Translations
const translations = {
    en: {
        back: "Back to Projects",
        tabs: {
            board: "Board",
            list: "List",
            references: "References",
            files: "Files",
            team: "Team",
            activity: "Activity"
        },
        task: {
            new: "Add Task",
            todo: "To Do",
            in_progress: "In Progress",
            review: "Review",
            done: "Done",
            noTasks: "No tasks here",
            priority: "Priority",
            assignedTo: "Assigned to",
            dueDate: "Due date"
        },
        references: {
            title: "Project References",
            add: "Add Reference",
            remove: "Remove from Project"
        },
        files: {
            title: "Project Files",
            upload: "Upload File",
            noFiles: "No files uploaded yet"
        },
        team: {
            title: "Project Team",
            invite: "Invite Member",
            role: "Role"
        },
        activity: {
            title: "Project Activity",
            writeComment: "Write a comment...",
            send: "Send"
        }
    },
    th: {
        back: "กลับไปยังโปรเจกต์",
        tabs: {
            board: "บอร์ด",
            list: "รายการ",
            references: "แหล่งอ้างอิง",
            files: "ไฟล์",
            team: "ทีมงาน",
            activity: "กิจกรรม"
        },
        task: {
            new: "เพิ่มงาน",
            todo: "ที่ต้องทำ",
            in_progress: "กำลังทำ",
            review: "รอตรวจ",
            done: "เสร็จสิ้น",
            noTasks: "ยังไม่มีงาน",
            priority: "ความสำคัญ",
            assignedTo: "ผู้รับผิดชอบ",
            dueDate: "วันครบกำหนด"
        },
        references: {
            title: "แหล่งอ้างอิงรายโครงการ",
            add: "เพิ่มแหล่งอ้างอิง",
            remove: "ลบออกจากโครงการ"
        },
        files: {
            title: "ไฟล์โครงการ",
            upload: "อัปโหลดไฟล์",
            noFiles: "ยังไม่ได้อัปโหลดไฟล์"
        },
        team: {
            title: "ทีมงานโครงการ",
            invite: "เชิญสมาชิก",
            role: "บทบาท"
        },
        activity: {
            title: "กิจกรรมภายในโครงการ",
            writeComment: "เขียนความคิดเห็น...",
            send: "ส่ง"
        }
    }
};

const projectColors: Record<string, { bg: string, light: string, text: string, border: string }> = {
    blue: { bg: 'bg-blue-500', light: 'bg-blue-50 dark:bg-blue-900/20', text: 'text-blue-600 dark:text-blue-400', border: 'border-blue-200 dark:border-blue-800' },
    green: { bg: 'bg-emerald-500', light: 'bg-emerald-50 dark:bg-emerald-900/20', text: 'text-emerald-600 dark:text-emerald-400', border: 'border-emerald-200 dark:border-emerald-800' },
    purple: { bg: 'bg-purple-500', light: 'bg-purple-50 dark:bg-purple-900/20', text: 'text-purple-600 dark:text-purple-400', border: 'border-purple-200 dark:border-purple-800' },
    orange: { bg: 'bg-orange-500', light: 'bg-orange-50 dark:bg-orange-900/20', text: 'text-orange-600 dark:text-orange-400', border: 'border-orange-200 dark:border-orange-800' },
    pink: { bg: 'bg-pink-500', light: 'bg-pink-50 dark:bg-pink-900/20', text: 'text-pink-600 dark:text-pink-400', border: 'border-pink-200 dark:border-pink-800' },
    cyan: { bg: 'bg-cyan-500', light: 'bg-cyan-50 dark:bg-cyan-900/20', text: 'text-cyan-600 dark:text-cyan-400', border: 'border-cyan-200 dark:border-cyan-800' },
    red: { bg: 'bg-red-500', light: 'bg-red-50 dark:bg-red-900/20', text: 'text-red-600 dark:text-red-400', border: 'border-red-200 dark:border-red-800' },
    amber: { bg: 'bg-amber-500', light: 'bg-amber-50 dark:bg-amber-900/20', text: 'text-amber-600 dark:text-amber-400', border: 'border-amber-200 dark:border-amber-800' },
};

export default function ProjectsShow({ project, availableReferences }: Props) {
    const { language } = useLanguage();
    const t = translations[language];
    
    // Persist tab state in URL
    const [activeTab, setActiveTab] = useState(() => {
        if (typeof window !== 'undefined') {
            const params = new URLSearchParams(window.location.search);
            return params.get('tab') || 'board';
        }
        return 'board';
    });

    useEffect(() => {
        const url = new URL(window.location.href);
        url.searchParams.set('tab', activeTab);
        window.history.replaceState({}, '', url.toString());
    }, [activeTab]);

    const [seenTabs, setSeenTabs] = useState<string[]>([activeTab]);
    const [showAddMember, setShowAddMember] = useState(false);
    const [isDeleteModalOpen, setIsDeleteModalOpen] = useState(false);
    const [isCreateTaskOpen, setIsCreateTaskOpen] = useState(false);
    const [isInviteModalOpen, setIsInviteModalOpen] = useState(false);
    const [isDeleteTaskModalOpen, setIsDeleteTaskModalOpen] = useState(false);
    const [taskToDelete, setTaskToDelete] = useState<Task | null>(null);
    const [selectedTaskId, setSelectedTaskId] = useState<number | null>(null);
    const [isSidePanelOpen, setIsSidePanelOpen] = useState(false);
    const [taskSearch, setTaskSearch] = useState('');
    const [filterAssignee, setFilterAssignee] = useState<string>('all');
    const [filterPriority, setFilterPriority] = useState<string>('all');
    const [searchQuery, setSearchQuery] = useState('');
    const [searchResults, setSearchResults] = useState<User[]>([]);
    const [newTaskStatus, setNewTaskStatus] = useState('todo');
    const [editingTask, setEditingTask] = useState<Task | null>(null);
    const fileInputRef = useRef<HTMLInputElement>(null);
    const theme = projectColors[project.color || 'blue'] || projectColors.blue;

    const { data, setData, post, processing, reset, errors } = useForm({
        id: null as number | null,
        title: '',
        description: '',
        status: 'todo',
        priority: 'medium',
        due_date: '',
        assigned_to: '' as string | number,
    });

    useEffect(() => {
        const delayDebounceFn = setTimeout(() => {
            if (searchQuery.length > 1) {
                axios.get(`/users/search?q=${searchQuery}`).then(res => {
                    setSearchResults(res.data);
                });
            } else {
                setSearchResults([]);
            }
        }, 300);

        return () => clearTimeout(delayDebounceFn);
    }, [searchQuery]);

    const handleInvite = (userId: number) => {
        router.post(`/projects/${project.id}/members`, { 
            user_id: userId,
            role: 'contributor'
        }, {
            onSuccess: () => {
                toast.success(language === 'en' ? 'Invitation sent' : 'ส่งคำเชิญแล้ว');
                setSearchQuery('');
            }
        });
    };

    const breadcrumbs: BreadcrumbItem[] = [
        { title: language === 'en' ? 'Projects' : 'โปรเจกต์', href: '/projects' },
        { title: project.name, href: `/projects/${project.id}` },
    ];

    const openCreateTask = (status: string = 'todo') => {
        setEditingTask(null);
        reset();
        setData('status', status);
        setIsCreateTaskOpen(true);
    };

    const openTaskDetail = (task: Task) => {
        setSelectedTaskId(task.id);
        setIsSidePanelOpen(true);
    };

    const openEditTask = (task: Task) => {
        setEditingTask(task);
        setData({
            id: task.id,
            title: task.title,
            description: task.description || '',
            status: task.status,
            priority: task.priority,
            due_date: task.due_date || '',
            assigned_to: task.assigned_to?.toString() || '',
        });
        setIsCreateTaskOpen(true);
    };

    const handleMarkComplete = (task: Task) => {
        const newStatus = task.status === 'done' ? 'todo' : 'done';
        router.patch(`/projects/tasks/${task.id}`, { status: newStatus }, {
            preserveScroll: true,
            onSuccess: () => toast.success(language === 'en' ? 'Task updated' : 'อัปเดตงานเรียบร้อย')
        });
    };

    const handleDeleteTask = (task: Task) => {
        setTaskToDelete(task);
        setIsDeleteTaskModalOpen(true);
    };

    const confirmDeleteTask = () => {
        if (taskToDelete) {
            router.delete(`/projects/tasks/${taskToDelete.id}`, {
                preserveScroll: true,
                onSuccess: () => {
                    setIsDeleteTaskModalOpen(false);
                    setTaskToDelete(null);
                    toast.success(language === 'en' ? 'Task deleted' : 'ลบงานเรียบร้อย');
                }
            });
        }
    };

    const handleTaskSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        if (editingTask) {
            router.patch(`/projects/tasks/${editingTask.id}`, data, {
                onSuccess: () => {
                    setIsCreateTaskOpen(false);
                    toast.success(language === 'en' ? 'Task updated' : 'อัปเดตงานเรียบร้อย');
                }
            });
        } else {
            post(`/projects/${project.id}/tasks`, {
                onSuccess: () => {
                    setIsCreateTaskOpen(false);
                    reset();
                    toast.success(language === 'en' ? 'Task created' : 'สร้างงานเรียบร้อย');
                }
            });
        }
    };

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

    const onDragEnd = async (event: DragEndEvent) => {
        const { active, over } = event;
        if (!over) return;

        const activeId = active.id as number;
        const overId = over.id as any;

        const activeTask = project.tasks.find(t => t.id === activeId);
        if (!activeTask) return;

        const columns = ['todo', 'in_progress', 'review', 'done'];
        if (columns.includes(overId)) {
            if (activeTask.status !== overId) {
                router.patch(`/projects/tasks/${activeId}`, { status: overId }, { preserveScroll: true });
            }
            return;
        }

        const overTask = project.tasks.find(t => t.id === overId);
        if (!overTask) return;

        if (activeTask.id !== overTask.id) {
            const oldIndex = project.tasks.indexOf(activeTask);
            const newIndex = project.tasks.indexOf(overTask);
            
            const newTasks = arrayMove(project.tasks, oldIndex, newIndex);
            
            if (activeTask.status !== overTask.status) {
                router.patch(`/projects/tasks/${activeId}`, { 
                    status: overTask.status,
                }, { preserveScroll: true });
            } else {
                router.post(`/projects/${project.id}/tasks/reorder`, {
                    tasks: newTasks.map((t, i) => ({
                        id: t.id,
                        position: i,
                        status: t.status
                    }))
                }, { preserveScroll: true });
            }
        }
    };

    const handleFileUpload = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('file', file);

        router.post(`/projects/${project.id}/files`, formData as any, {
            onSuccess: () => {
                toast.success(language === 'en' ? 'File uploaded successfully' : 'อัปโหลดไฟล์สำเร็จแล้ว');
                if (fileInputRef.current) fileInputRef.current.value = '';
            },
        });
    };

    const handleDeleteFile = (fileId: number) => {
        if (confirm(language === 'en' ? 'Delete this file?' : 'ต้องการลบไฟล์นี้หรือไม่?')) {
            router.delete(`/projects/files/${fileId}`, {
                onSuccess: () => toast.success(language === 'en' ? 'File deleted' : 'ลบไฟล์สำเร็จแล้ว'),
                preserveScroll: true
            });
        }
    };

    const handleDeleteProject = () => {
        setIsDeleteModalOpen(true);
    };

    const confirmDelete = () => {
        router.delete(`/projects/${project.id}`, {
            onSuccess: () => {
                setIsDeleteModalOpen(false);
                toast.success(language === 'en' ? 'Project deleted successfully' : 'ลบโปรเจกต์สำเร็จแล้ว');
            }
        });
    };

    const filteredTasks = useMemo(() => {
        return project.tasks.filter(task => {
            const matchesSearch = task.title.toLowerCase().includes(taskSearch.toLowerCase());
            const matchesAssignee = filterAssignee === 'all' ? true : 
                filterAssignee === 'unassigned' ? !task.assigned_to :
                task.assigned_to?.toString() === filterAssignee;
            const matchesPriority = filterPriority === 'all' ? true : task.priority === filterPriority;
            return matchesSearch && matchesAssignee && matchesPriority;
        });
    }, [project.tasks, taskSearch, filterAssignee, filterPriority]);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={project.name} />

            <div className="flex h-full flex-1 flex-col bg-gray-50/30 dark:bg-[#050505]/50">
                {/* Fixed Top Header */}
                <div className="border-b border-gray-100 bg-white/50 p-4 backdrop-blur-md dark:border-gray-800 dark:bg-gray-900/50">
                    <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div className="flex items-center gap-4">
                            <div className={cn("flex h-12 w-12 items-center justify-center rounded-2xl shadow-sm", theme.light)}>
                                <FolderOpen className={cn("h-6 w-6", theme.text)} />
                            </div>
                            <div>
                                <h1 className="text-2xl font-black text-scribehub-blue dark:text-white">{project.name}</h1>
                                <div className="mt-1 flex items-center gap-3">
                                    <div className="flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1 text-[10px] font-black uppercase tracking-wider text-blue-600 dark:bg-blue-900/20">
                                        {project.status.replace('_', ' ')}
                                    </div>
                                    <div className="h-1 w-24 rounded-full bg-gray-100 dark:bg-gray-800 overflow-hidden">
                                        <div className={cn("h-full", theme.bg)} style={{ width: `${project.progress}%` }} />
                                    </div>
                                    <span className="text-[10px] font-black text-gray-400 uppercase tracking-widest">{project.progress}% DONE</span>
                                </div>
                            </div>
                        </div>

                        <div className="flex items-center gap-3">
                            <div className="flex items-center -space-x-2 mr-4">
                                {project.members.map((m, i) => (
                                    <div key={m.id} className="flex h-7 w-7 items-center justify-center rounded-full border-2 border-white bg-gray-100 text-[9px] font-bold dark:border-gray-900 dark:bg-gray-800">
                                        {m.user.name[0]}
                                    </div>
                                ))}
                                <button 
                                    onClick={() => setIsInviteModalOpen(true)}
                                    className="flex h-7 w-7 items-center justify-center rounded-full border-2 border-white bg-gray-100 text-gray-400 hover:bg-white dark:border-gray-900 dark:bg-gray-800"
                                >
                                    <Plus className="h-3.5 w-3.5" />
                                </button>
                            </div>
                            <Link 
                                href={`/projects/${project.id}/edit`}
                                className="flex h-9 items-center gap-2 rounded-xl border border-gray-100 bg-white px-3 text-[10px] font-black shadow-sm transition-all hover:bg-gray-50 active:scale-95 dark:border-gray-800 dark:bg-gray-900"
                            >
                                <Edit className="h-3 w-3" /> EDIT
                            </Link>
                            <button onClick={handleDeleteProject} className="flex h-9 w-9 items-center justify-center rounded-xl bg-red-50 text-red-600 transition-all hover:bg-red-100 active:scale-95 dark:bg-red-900/20">
                                <Trash2 className="h-3.5 w-3.5" />
                            </button>
                        </div>
                    </div>

                    <div className="mt-6 flex flex-wrap items-center gap-1.5">
                        {[
                            { id: 'board', label: t.tabs.board, icon: LayoutGrid, count: project.tasks?.length || 0 },
                            { id: 'list', label: t.tabs.list, icon: ListIcon, count: project.tasks?.length || 0 },
                            { id: 'references', label: t.tabs.references, icon: BookOpen, count: project.references?.length || 0 },
                            { id: 'files', label: t.tabs.files, icon: FileStack, count: project.files?.length || 0 },
                            { id: 'team', label: t.tabs.team, icon: Users, count: project.members?.length || 0 },
                            { id: 'activity', label: t.tabs.activity, icon: Activity, count: project.comments?.length || 0 },
                        ].map(tab => {
                            const showBadge = tab.count > 0 && !seenTabs.includes(tab.id);
                            
                            return (
                                <button
                                    key={tab.id}
                                    onClick={() => {
                                        setActiveTab(tab.id);
                                        if (!seenTabs.includes(tab.id)) {
                                            setSeenTabs(prev => [...prev, tab.id]);
                                        }
                                    }}
                                    className={cn(
                                        "group/tab relative flex items-center gap-1.5 rounded-xl px-4 py-2 text-[10px] font-black uppercase tracking-widest transition-all active:scale-95",
                                        activeTab === tab.id 
                                            ? "bg-scribehub-blue text-white shadow-md shadow-blue-900/10" 
                                            : "text-gray-400 hover:bg-white hover:text-gray-600 dark:hover:bg-gray-800"
                                    )}
                                >
                                    <tab.icon className="h-3.5 w-3.5" />
                                    {tab.label}
                                    {showBadge && (
                                        <span className="absolute -right-1 -top-1 flex h-4 min-w-[16px] items-center justify-center rounded-full bg-red-500 px-1 text-[8px] font-black text-white shadow-lg ring-2 ring-white dark:bg-red-600 dark:ring-gray-900">
                                            {tab.count}
                                        </span>
                                    )}
                                </button>
                            );
                        })}
                    </div>
                </div>

                {/* Tab Content Area */}
                <div className="flex-1 overflow-auto p-5 animate-in fade-in slide-in-from-bottom-2 duration-700">
                    {(activeTab === 'board' || activeTab === 'list') && (
                        <div className="mb-6 flex flex-wrap items-center gap-4">
                            <div className="relative flex-1 min-w-[200px]">
                                <Search className="absolute left-3 top-2.5 h-4 w-4 text-gray-400" />
                                <Input
                                    placeholder="Filter tasks..."
                                    value={taskSearch}
                                    onChange={(e) => setTaskSearch(e.target.value)}
                                    className="pl-9 h-9 text-sm bg-white dark:bg-gray-900 border-gray-100 dark:border-gray-800 rounded-xl"
                                />
                            </div>
                            <Select value={filterAssignee} onValueChange={setFilterAssignee}>
                                <SelectTrigger className="w-[140px] h-9 text-xs font-bold rounded-xl bg-white dark:bg-gray-900 border-gray-100 dark:border-gray-800">
                                    <SelectValue placeholder="Assignee" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="all">All Members</SelectItem>
                                    <SelectItem value="unassigned">Unassigned</SelectItem>
                                    {project.members.map(m => (
                                        <SelectItem key={m.id} value={m.user.id.toString()}>{m.user.name}</SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                            <Select value={filterPriority} onValueChange={setFilterPriority}>
                                <SelectTrigger className="w-[120px] h-9 text-xs font-bold rounded-xl bg-white dark:bg-gray-900 border-gray-100 dark:border-gray-800">
                                    <SelectValue placeholder="Priority" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="all">All Priorities</SelectItem>
                                    <SelectItem value="urgent">Urgent</SelectItem>
                                    <SelectItem value="high">High</SelectItem>
                                    <SelectItem value="medium">Medium</SelectItem>
                                    <SelectItem value="low">Low</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                    )}
                    {activeTab === 'board' && (
                        <DndContext 
                            sensors={sensors}
                            collisionDetection={closestCenter}
                            onDragEnd={onDragEnd}
                        >
                            <BoardView 
                                tasks={filteredTasks} 
                                t={t} 
                                onAddTask={openCreateTask} 
                                onEditTask={openEditTask}
                                onDeleteTask={handleDeleteTask}
                                onMarkComplete={handleMarkComplete}
                                onTaskClick={openTaskDetail}
                            />
                        </DndContext>
                    )}
                    {activeTab === 'list' && (
                        <ListView 
                            tasks={filteredTasks} 
                            t={t} 
                            onAddTask={() => openCreateTask('todo')} 
                            onEditTask={openEditTask}
                            onDeleteTask={handleDeleteTask}
                            onMarkComplete={handleMarkComplete}
                            onTaskClick={openTaskDetail}
                        />
                    )}
                    {activeTab === 'references' && <ReferencesView project={project} available={availableReferences} t={t} />}
                    {activeTab === 'files' && (
                        <FilesView 
                            files={project.files} 
                            t={t} 
                            onUploadClick={() => fileInputRef.current?.click()} 
                            onDeleteFile={handleDeleteFile}
                        />
                    )}
                    {activeTab === 'team' && <TeamView project={project} members={project.members} t={t} onInviteClick={() => setIsInviteModalOpen(true)} />}
                    {activeTab === 'activity' && <ActivityView comments={project.comments} t={t} project={project} language={language} />}
                </div>

                <input 
                    type="file" 
                    ref={fileInputRef} 
                    className="hidden" 
                    onChange={handleFileUpload} 
                />
            </div>

            {/* Create Task Modal */}
            <Dialog open={isCreateTaskOpen} onOpenChange={setIsCreateTaskOpen}>
                <DialogContent className="max-w-md rounded-[2rem] border-none bg-white p-8 shadow-2xl dark:bg-gray-900 overflow-hidden">
                    <DialogHeader>
                        <DialogTitle className="flex items-center gap-3 text-2xl font-black text-scribehub-blue dark:text-white">
                            <div className="flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 dark:bg-blue-900/20">
                                {editingTask ? <Edit className="h-5 w-5" /> : <Plus className="h-5 w-5" />}
                            </div>
                            {editingTask 
                                ? (language === 'en' ? 'Edit Task' : 'แก้ไขงาน') 
                                : (language === 'en' ? 'Create New Task' : 'สร้างงานใหม่')}
                        </DialogTitle>
                    </DialogHeader>
                    
                    <form onSubmit={handleTaskSubmit} className="mt-8 space-y-6">
                        <div className="space-y-2">
                            <Label className="text-[10px] font-black uppercase tracking-widest text-gray-400">{language === 'en' ? 'Task Title' : 'หัวข้องาน'}</Label>
                            <Input 
                                value={data.title}
                                onChange={e => setData('title', e.target.value)}
                                placeholder={language === 'en' ? 'What needs to be done?' : 'ต้องทำอะไรบ้าง?'}
                                className="rounded-xl border-gray-100 bg-gray-50/50 py-6 text-sm font-bold focus:ring-scribehub-blue dark:border-gray-800 dark:bg-gray-800/50"
                                required
                            />
                            {errors.title && <p className="text-[10px] font-bold text-red-500">{errors.title}</p>}
                        </div>

                        <div className="space-y-2">
                            <Label className="text-[10px] font-black uppercase tracking-widest text-gray-400">{language === 'en' ? 'Description' : 'รายละเอียด'}</Label>
                            <textarea 
                                value={data.description}
                                onChange={e => setData('description', e.target.value)}
                                placeholder={language === 'en' ? 'Briefly describe the task...' : 'อธิบายรายละเอียดงานสักเล็กน้อย...'}
                                className="w-full min-h-[100px] rounded-xl border border-gray-100 bg-gray-50/50 p-4 text-sm font-bold focus:ring-scribehub-blue dark:border-gray-800 dark:bg-gray-800/50"
                            />
                        </div>

                        <div className="grid grid-cols-2 gap-4">
                            <div className="space-y-2">
                                <Label className="text-[10px] font-black uppercase tracking-widest text-gray-400">{language === 'en' ? 'Priority' : 'ความสำคัญ'}</Label>
                                <Select value={data.priority} onValueChange={v => setData('priority', v)}>
                                    <SelectTrigger className="rounded-xl border-gray-100 bg-gray-50/50 font-bold dark:border-gray-800 dark:bg-gray-800/50">
                                        <SelectValue />
                                    </SelectTrigger>
                                    <SelectContent className="rounded-xl dark:bg-gray-900">
                                        <SelectItem value="low">
                                            <div className="flex items-center gap-2">
                                                <div className="h-2 w-2 rounded-full bg-emerald-500" />
                                                <span className="font-bold text-emerald-600">Low</span>
                                            </div>
                                        </SelectItem>
                                        <SelectItem value="medium">
                                            <div className="flex items-center gap-2">
                                                <div className="h-2 w-2 rounded-full bg-blue-500" />
                                                <span className="font-bold text-blue-600">Medium</span>
                                            </div>
                                        </SelectItem>
                                        <SelectItem value="high">
                                            <div className="flex items-center gap-2">
                                                <div className="h-2 w-2 rounded-full bg-orange-500" />
                                                <span className="font-bold text-orange-600">High</span>
                                            </div>
                                        </SelectItem>
                                        <SelectItem value="urgent">
                                            <div className="flex items-center gap-2">
                                                <div className="h-2 w-2 rounded-full bg-red-500" />
                                                <span className="font-bold text-red-600">Urgent</span>
                                            </div>
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                            <div className="space-y-2">
                                <Label className="text-[10px] font-black uppercase tracking-widest text-gray-400">{language === 'en' ? 'Due Date' : 'วันครบกำหนด'}</Label>
                                <Input 
                                    type="date"
                                    value={data.due_date}
                                    onChange={e => setData('due_date', e.target.value)}
                                    className="rounded-xl border-gray-100 bg-gray-50/50 font-bold dark:border-gray-800 dark:bg-gray-800/50"
                                />
                            </div>
                        </div>

                        <div className="mt-8 flex gap-3">
                            <button
                                type="button"
                                onClick={() => setIsCreateTaskOpen(false)}
                                className="flex-1 rounded-xl bg-gray-50 py-3 text-xs font-black uppercase tracking-widest text-gray-400 transition-all hover:bg-gray-100 active:scale-95 dark:bg-gray-800 dark:hover:bg-gray-700"
                            >
                                {language === 'en' ? 'Cancel' : 'ยกเลิก'}
                            </button>
                            <button
                                type="submit"
                                disabled={processing}
                                className="flex-[2] rounded-xl bg-scribehub-blue py-3 text-xs font-black uppercase tracking-widest text-white shadow-lg shadow-blue-900/20 transition-all hover:opacity-90 active:scale-95 disabled:opacity-50"
                            >
                                {processing ? '...' : (editingTask 
                                    ? (language === 'en' ? 'Update Task' : 'อัปเดตงาน')
                                    : (language === 'en' ? 'Create Task' : 'สร้างงาน'))}
                            </button>
                        </div>
                    </form>
                </DialogContent>
            </Dialog>

            {/* Delete Confirmation Modal */}
            <Dialog open={isDeleteModalOpen} onOpenChange={setIsDeleteModalOpen}>
                <DialogContent className="max-w-sm rounded-[2rem] border-none bg-white p-8 shadow-2xl dark:bg-gray-900">
                    <div className="flex flex-col items-center text-center">
                        <div className="mb-6 flex h-20 w-20 items-center justify-center rounded-3xl bg-red-50 text-red-500 dark:bg-red-900/20">
                            <Trash2 className="h-10 w-10" />
                        </div>
                        <DialogTitle className="text-xl font-black text-scribehub-blue dark:text-white">
                            {language === 'en' ? 'Delete Project?' : 'ลบโปรเจกต์หรือไม่?'}
                        </DialogTitle>
                        <p className="mt-3 text-sm font-medium text-gray-500 dark:text-gray-400">
                            {language === 'en' 
                                ? `Are you sure you want to delete "${project.name}"? This action cannot be undone.` 
                                : `คุณแน่ใจหรือไม่ที่จะลบ "${project.name}"? การดำเนินการนี้ไม่สามารถย้อนกลับได้`}
                        </p>
                        
                        <div className="mt-8 flex w-full gap-3">
                            <button
                                onClick={() => setIsDeleteModalOpen(false)}
                                className="flex-1 rounded-xl bg-gray-50 py-3 text-xs font-black uppercase tracking-widest text-gray-400 transition-all hover:bg-gray-100 active:scale-95 dark:bg-gray-800 dark:hover:bg-gray-700"
                            >
                                {language === 'en' ? 'Cancel' : 'ยกเลิก'}
                            </button>
                            <button
                                onClick={confirmDelete}
                                className="flex-1 rounded-xl bg-red-500 py-3 text-xs font-black uppercase tracking-widest text-white shadow-lg shadow-red-900/20 transition-all hover:opacity-90 active:scale-95"
                            >
                                {language === 'en' ? 'Delete' : 'ลบ'}
                            </button>
                        </div>
                    </div>
                </DialogContent>
            </Dialog>

            {/* Delete Task Confirmation Modal */}
            <Dialog open={isDeleteTaskModalOpen} onOpenChange={setIsDeleteTaskModalOpen}>
                <DialogContent className="max-w-sm rounded-[2rem] border-none bg-white p-8 shadow-2xl dark:bg-gray-900 overflow-hidden">
                    <div className="flex flex-col items-center text-center">
                        <div className="flex h-16 w-16 items-center justify-center rounded-2xl bg-red-50 text-red-600 dark:bg-red-900/20">
                            <AlertCircle className="h-8 w-8" />
                        </div>
                        <h2 className="mt-6 text-2xl font-black text-scribehub-blue dark:text-white">
                            {language === 'en' ? 'Delete Task?' : 'ลบงานนี้?'}
                        </h2>
                        <p className="mt-3 text-sm font-medium text-gray-500 dark:text-gray-400">
                            {language === 'en' 
                                ? `Are you sure you want to delete "${taskToDelete?.title}"? This action cannot be undone.` 
                                : `คุณแน่ใจหรือไม่ที่จะลบ "${taskToDelete?.title}"? การดำเนินการนี้ไม่สามารถย้อนกลับได้`}
                        </p>
                        
                        <div className="mt-8 flex w-full gap-3">
                            <button
                                onClick={() => setIsDeleteTaskModalOpen(false)}
                                className="flex-1 rounded-xl bg-gray-50 py-3 text-xs font-black uppercase tracking-widest text-gray-400 transition-all hover:bg-gray-100 active:scale-95 dark:bg-gray-800 dark:hover:bg-gray-700"
                            >
                                {language === 'en' ? 'Cancel' : 'ยกเลิก'}
                            </button>
                            <button
                                onClick={confirmDeleteTask}
                                className="flex-1 rounded-xl bg-red-500 py-3 text-xs font-black uppercase tracking-widest text-white shadow-lg shadow-red-900/20 transition-all hover:opacity-90 active:scale-95"
                            >
                                {language === 'en' ? 'Delete' : 'ลบ'}
                            </button>
                        </div>
                    </div>
                </DialogContent>
            </Dialog>

            {/* Invite Modal */}
            <Dialog open={isInviteModalOpen} onOpenChange={setIsInviteModalOpen}>
                <DialogContent className="max-w-md rounded-[2rem] border-none bg-white p-8 shadow-2xl dark:bg-gray-900 overflow-hidden">
                    <DialogHeader>
                        <DialogTitle className="text-2xl font-black text-scribehub-blue dark:text-white">
                            {language === 'en' ? 'Invite Team Member' : 'เชิญสมาชิกทีม'}
                        </DialogTitle>
                    </DialogHeader>
                    
                    <div className="mt-6 space-y-4">
                        <div className="relative">
                            <Search className="absolute left-4 top-3.5 h-4 w-4 text-gray-400" />
                            <Input 
                                value={searchQuery}
                                onChange={e => setSearchQuery(e.target.value)}
                                placeholder={language === 'en' ? 'Search by name or email...' : 'ค้นหาด้วยชื่อหรืออีเมล...'}
                                className="rounded-xl border-gray-100 bg-gray-50/50 py-6 pl-12 text-sm font-bold focus:ring-scribehub-blue dark:border-gray-800 dark:bg-gray-800/50"
                            />
                        </div>

                        <div className="mt-6 max-h-[300px] overflow-y-auto space-y-2 pr-2">
                            {searchResults.map(user => (
                                <div key={user.id} className="flex items-center justify-between p-4 rounded-2xl bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-800 transition-all hover:bg-white dark:hover:bg-gray-800">
                                    <div className="flex items-center gap-3">
                                        <div className="h-10 w-10 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 font-black dark:bg-blue-900/20">
                                            {user.name[0]}
                                        </div>
                                        <div>
                                            <p className="text-sm font-bold text-scribehub-blue dark:text-white">{user.name}</p>
                                            <p className="text-[10px] text-gray-400">{user.email}</p>
                                        </div>
                                    </div>
                                    <button 
                                        onClick={() => handleInvite(user.id)}
                                        className="rounded-lg bg-scribehub-blue px-4 py-2 text-[10px] font-black text-white active:scale-95"
                                    >
                                        INVITE
                                    </button>
                                </div>
                            ))}
                            {searchQuery.length > 1 && searchResults.length === 0 && (
                                <p className="text-center py-8 text-xs text-gray-400 font-bold italic">No users found</p>
                            )}
                        </div>

                        <div className="mt-4 flex justify-end">
                            <button
                                onClick={() => setIsInviteModalOpen(false)}
                                className="rounded-xl bg-gray-50 px-8 py-3 text-xs font-black uppercase tracking-widest text-gray-400 transition-all hover:bg-gray-100 active:scale-95 dark:bg-gray-800 dark:hover:bg-gray-700"
                            >
                                {language === 'en' ? 'Close' : 'ปิด'}
                            </button>
                        </div>
                    </div>
                </DialogContent>
            </Dialog>
            {/* Task Side Panel */}
            <TaskSidePanel 
                open={isSidePanelOpen} 
                onOpenChange={setIsSidePanelOpen} 
                taskId={selectedTaskId}
                onTaskUpdate={() => router.reload({ only: ['project'] })}
            />
        </AppLayout>
    );
}

// Sub-components for each tab
function BoardView({ 
    tasks, t, onAddTask, onEditTask, onDeleteTask, onMarkComplete, onTaskClick
}: { 
    tasks: Task[], t: any, onAddTask: (status: string) => void,
    onEditTask: (task: Task) => void,
    onDeleteTask: (task: Task) => void,
    onMarkComplete: (task: Task) => void,
    onTaskClick: (task: Task) => void
}) {
    const columns = ['todo', 'in_progress', 'review', 'done'] as const;
    
    return (
        <div className="flex h-full gap-4 overflow-x-auto pb-4 scrollbar-hide">
            {columns.map(col => {
                const colTasks = tasks.filter(tk => tk.status === col);
                return (
                    <div key={col} className="flex min-w-[300px] max-w-[300px] flex-col gap-3">
                        <div className="flex items-center justify-between px-2">
                            <div className="flex items-center gap-2">
                                <h3 className="text-xs font-black uppercase tracking-widest text-scribehub-blue dark:text-white">
                                    {t.task[col]}
                                </h3>
                                <div className="flex h-5 w-5 items-center justify-center rounded-full bg-blue-50 text-[10px] font-black text-blue-600 dark:bg-blue-900/20">
                                    {colTasks.length}
                                </div>
                            </div>
                            <button 
                                onClick={() => onAddTask(col)}
                                title="Add Task"
                                className="h-8 w-8 rounded-xl bg-white text-gray-400 hover:text-scribehub-blue dark:bg-gray-900 flex items-center justify-center transition-colors"
                            >
                                <Plus className="h-4 w-4" />
                            </button>
                        </div>
                        
                        <SortableContext 
                            id={col}
                            items={colTasks.map(t => t.id)}
                            strategy={verticalListSortingStrategy}
                        >
                            <div className="flex flex-1 flex-col gap-3 rounded-[1.5rem] bg-gray-100/50 p-3 dark:bg-gray-900/40 min-h-[500px]">
                                {colTasks.map(task => (
                                    <SortableTaskCard 
                                        key={task.id} 
                                        task={task} 
                                        onEdit={() => onEditTask(task)}
                                        onDelete={() => onDeleteTask(task)}
                                        onToggleComplete={() => onMarkComplete(task)}
                                        onClick={() => onTaskClick(task)}
                                    />
                                ))}
                                <button 
                                    onClick={() => onAddTask(col)}
                                    className="group flex items-center justify-center gap-2 rounded-2xl border-2 border-dashed border-gray-200 py-4 text-[10px] font-black uppercase tracking-widest text-gray-400 transition-all hover:border-scribehub-blue hover:bg-white hover:text-scribehub-blue dark:border-gray-800 dark:hover:bg-gray-900"
                                >
                                    <Plus className="h-3 w-3 transition-transform group-hover:rotate-90" /> {t.task.new}
                                </button>
                            </div>
                        </SortableContext>
                    </div>
                );
            })}
        </div>
    );
}

function SortableTaskCard({ task, onEdit, onDelete, onToggleComplete, onClick }: { task: Task, onEdit: () => void, onDelete: () => void, onToggleComplete: () => void, onClick: () => void }) {
    const {
        attributes,
        listeners,
        setNodeRef,
        transform,
        transition,
        isDragging,
    } = useSortable({ id: task.id });

    const style = {
        transform: CSS.Translate.toString(transform),
        transition,
        zIndex: isDragging ? 50 : 'auto',
        opacity: isDragging ? 0.5 : 1,
    };

    return (
        <div 
            ref={setNodeRef} 
            style={style}
            className="group relative rounded-[1.25rem] border border-white bg-white p-4 shadow-sm transition-all hover:shadow-xl hover:-translate-y-0.5 dark:border-gray-800 dark:bg-gray-900 select-none cursor-grab active:cursor-grabbing"
            onClick={onClick}
            {...attributes}
            {...listeners}
        >
            <div className="flex items-start justify-between">
                <div className={cn(
                    "rounded-full px-2 py-0.5 text-[8px] font-black uppercase tracking-wider",
                    task.priority === 'urgent' ? "bg-red-50 text-red-600" : 
                    task.priority === 'high' ? "bg-orange-50 text-orange-600" :
                    "bg-blue-50 text-blue-600"
                )}>
                    {task.priority}
                </div>
                
                <div className="flex items-center gap-1">
                    {task.status === 'done' && (
                        <CheckCircle className="h-3.5 w-3.5 text-emerald-500" />
                    )}
                </div>
            </div>

            <h4 className={cn(
                "mt-3 text-[13px] font-black text-scribehub-blue dark:text-white leading-tight",
                task.status === 'done' && "line-through opacity-50"
            )}>
                {task.title}
            </h4>
            
            {task.description && (
                <p className="mt-2 text-[10px] font-medium text-gray-400 line-clamp-2 leading-relaxed">
                    {task.description}
                </p>
            )}
            
            <div className="mt-4 flex items-center justify-between border-t border-gray-50 pt-3 dark:border-gray-800">
                <div className="flex items-center gap-2 text-[9px] font-black text-gray-400 uppercase tracking-wider">
                    <Calendar className="h-2.5 w-2.5" />
                    {task.due_date ? formatDistanceToNow(new Date(task.due_date), { addSuffix: true }) : 'No date'}
                </div>
                <div className="h-6 w-6 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center text-[10px] font-black text-gray-500 uppercase dark:bg-gray-800 dark:border-gray-700">
                    {task.assignee?.name[0] || '?'}
                </div>
            </div>

            {/* Hover Actions Overlay */}
            <div className="absolute inset-0 z-10 flex items-center justify-center gap-2 rounded-[1.25rem] bg-indigo-900/10 backdrop-blur-[1px] opacity-0 group-hover:opacity-100 transition-all duration-300">
                <div className="flex items-center gap-1.5 bg-white dark:bg-gray-900 p-2 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-800 scale-90 group-hover:scale-100 transition-transform">
                    <button 
                        onPointerDown={e => { e.stopPropagation(); onToggleComplete(); }}
                        title={task.status === 'done' ? "Undo" : "Complete"}
                        className={cn(
                            "h-8 w-8 flex items-center justify-center rounded-xl transition-all",
                            task.status === 'done' ? "bg-emerald-50 text-emerald-500" : "bg-gray-50 text-gray-400 hover:text-emerald-500"
                        )}
                    >
                        <CheckCircle2 className="h-4 w-4" />
                    </button>
                    <button 
                        onPointerDown={e => { e.stopPropagation(); onEdit(); }}
                        title="Edit"
                        className="h-8 w-8 flex items-center justify-center rounded-xl bg-gray-50 text-gray-400 hover:text-blue-500 transition-all"
                    >
                        <Edit className="h-4 w-4" />
                    </button>
                    <button 
                        onPointerDown={e => { e.stopPropagation(); onDelete(); }}
                        title="Delete"
                        className="h-8 w-8 flex items-center justify-center rounded-xl bg-gray-50 text-gray-400 hover:text-red-500 transition-all"
                    >
                        <Trash2 className="h-4 w-4" />
                    </button>
                </div>
            </div>
        </div>
    );
}

function ListView({ 
    tasks, t, onAddTask, onEditTask, onDeleteTask, onMarkComplete, onTaskClick
}: { 
    tasks: Task[], t: any, onAddTask: () => void,
    onEditTask: (task: Task) => void,
    onDeleteTask: (task: Task) => void,
    onMarkComplete: (task: Task) => void,
    onTaskClick: (task: Task) => void
}) {
    return (
        <div className="rounded-[2.5rem] bg-white p-8 shadow-sm dark:bg-gray-900/50 border border-white dark:border-gray-800">
            <div className="flex items-center justify-between mb-8">
                <div>
                    <h2 className="text-xl font-black text-scribehub-blue dark:text-white uppercase tracking-tight">{t.tabs.list}</h2>
                    <p className="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Manage all project tasks</p>
                </div>
                <button 
                    onClick={onAddTask}
                    className="flex items-center gap-2 rounded-xl bg-scribehub-blue px-6 py-2.5 text-[10px] font-black text-white shadow-xl shadow-blue-500/20 active:scale-95 transition-all"
                >
                    <Plus className="h-3.5 w-3.5" /> NEW TASK
                </button>
            </div>
            <div className="space-y-3">
                {tasks.map(task => (
                    <div key={task.id} className="group flex items-center gap-4 rounded-[2rem] border border-gray-50 bg-gray-50/30 p-4 transition-all hover:bg-white hover:shadow-xl hover:border-white dark:border-gray-800 dark:bg-gray-900">
                        <button 
                            onClick={() => onMarkComplete(task)}
                            className={cn(
                                "flex h-12 w-12 shrink-0 items-center justify-center rounded-[1.25rem] shadow-sm transition-all active:scale-90",
                                task.status === 'done' ? "bg-emerald-500 text-white" : "bg-white text-gray-200 hover:text-emerald-500 dark:bg-gray-800"
                            )}
                        >
                            <CheckCircle2 className="h-6 w-6" />
                        </button>
                        <div className="flex-1 min-w-0" onClick={() => onTaskClick(task)}>
                            <h4 className={cn(
                                "font-black text-[15px] text-scribehub-blue dark:text-white truncate cursor-pointer",
                                task.status === 'done' && "line-through opacity-50"
                            )}>
                                {task.title}
                            </h4>
                            <div className="flex items-center gap-3 mt-1">
                                <span className={cn(
                                    "px-2 py-0.5 rounded-full text-[8px] font-black uppercase tracking-wider",
                                    task.status === 'todo' ? "bg-gray-100 text-gray-400" :
                                    task.status === 'in_progress' ? "bg-blue-100 text-blue-600" :
                                    task.status === 'review' ? "bg-orange-100 text-orange-600" :
                                    "bg-emerald-100 text-emerald-600"
                                )}>
                                    {task.status}
                                </span>
                                <span className="text-[9px] font-bold text-gray-300">|</span>
                                <span className="text-[10px] font-medium text-gray-400 italic">
                                    {task.description || 'No description'}
                                </span>
                            </div>
                        </div>
                        <div className="flex items-center gap-8 px-4">
                            <div className="text-right">
                                <p className="text-[9px] font-black uppercase tracking-widest text-gray-300">Priority</p>
                                <p className={cn(
                                    "text-[10px] font-black uppercase",
                                    task.priority === 'urgent' ? "text-red-500" :
                                    task.priority === 'high' ? "text-orange-500" :
                                    "text-blue-400"
                                )}>{task.priority}</p>
                            </div>
                            <div className="h-8 w-px bg-gray-100 dark:bg-gray-800" />
                            <div className="text-right min-w-[80px]">
                                <p className="text-[9px] font-black uppercase tracking-widest text-gray-300">Due Date</p>
                                <p className="text-[10px] font-black text-gray-700 dark:text-gray-300">{task.due_date || '-'}</p>
                            </div>
                            
                            <div className="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button 
                                    onClick={() => onEditTask(task)}
                                    className="h-10 w-10 flex items-center justify-center rounded-xl bg-white text-gray-400 hover:text-blue-500 shadow-sm dark:bg-gray-800"
                                >
                                    <Edit className="h-4 w-4" />
                                </button>
                                <button 
                                    onClick={() => onDeleteTask(task)}
                                    className="h-10 w-10 flex items-center justify-center rounded-xl bg-white text-gray-400 hover:text-red-500 shadow-sm dark:bg-gray-800"
                                >
                                    <Trash2 className="h-4 w-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
}

function ReferencesView({ project, available, t }: { project: Project, available: Reference[], t: any }) {
    const handleAdd = (id: number) => {
        router.post(`/projects/${project.id}/add-reference`, { reference_id: id }, { preserveScroll: true });
    };

    const handleRemove = (id: number) => {
        router.post(`/projects/${project.id}/remove-reference`, { reference_id: id }, { preserveScroll: true });
    };

    return (
        <div className="grid gap-6 lg:grid-cols-3">
            <div className="lg:col-span-2 space-y-4">
                <div className="rounded-2xl bg-white p-6 dark:bg-gray-900 border border-white dark:border-gray-800">
                    <h2 className="mb-4 text-lg font-black text-scribehub-blue dark:text-white">{t.references.title}</h2>
                    <div className="space-y-2">
                        {project.references.map(ref => (
                            <div key={ref.id} className="group relative flex items-center justify-between rounded-xl bg-gray-100/50 p-4 dark:bg-gray-800/50">
                                <div>
                                    <h4 className="text-sm font-bold text-scribehub-blue dark:text-white hover:underline cursor-pointer">{ref.title}</h4>
                                    <p className="text-[10px] text-gray-400">{ref.authors?.join(', ')} ({ref.year})</p>
                                </div>
                                <button onClick={() => handleRemove(ref.id)} className="h-8 w-8 flex items-center justify-center opacity-0 group-hover:opacity-100 bg-red-50 text-red-500 rounded-lg transition-all hover:bg-red-100">
                                    <Trash2 className="h-3.5 w-3.5" />
                                </button>
                            </div>
                        ))}
                    </div>
                </div>
            </div>
            <div className="space-y-4">
                <div className="rounded-2xl bg-scribehub-blue/5 p-6 dark:bg-gray-900/50 border border-scribehub-blue/10">
                    <h2 className="mb-4 text-base font-black text-scribehub-blue dark:text-white">Available Sources</h2>
                    <div className="space-y-3">
                        {available.map(ref => (
                            <div key={ref.id} className="flex items-center justify-between gap-3 rounded-lg bg-white p-3 dark:bg-gray-900 shadow-sm">
                                <div className="min-w-0">
                                    <h4 className="text-[10px] font-bold text-gray-700 dark:text-gray-300 truncate">{ref.title}</h4>
                                    <p className="text-[9px] text-gray-400">{ref.year}</p>
                                </div>
                                <button onClick={() => handleAdd(ref.id)} className="shrink-0 flex items-center gap-1 bg-scribehub-blue text-white rounded-lg px-2 py-1 text-[9px] font-bold">
                                    ADD
                                </button>
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </div>
    );
}

function FilesView({ files, t, onUploadClick, onDeleteFile }: { files: ProjectFile[], t: any, onUploadClick: () => void, onDeleteFile: (id: number) => void }) {
    return (
        <div className="rounded-[1.5rem] bg-white p-6 shadow-sm dark:bg-gray-900/50">
            <div className="flex items-center justify-between mb-8">
                <h2 className="text-lg font-black text-scribehub-blue dark:text-white">{t.files.title}</h2>
                <button 
                    onClick={onUploadClick}
                    className="flex items-center gap-2 rounded-xl bg-emerald-500 px-4 py-2 text-[10px] font-black text-white shadow-lg active:scale-95"
                >
                    <Upload className="h-3.5 w-3.5" /> UPLOAD FILE
                </button>
            </div>
            {files.length === 0 ? (
                <div className="flex flex-col items-center justify-center py-20 text-gray-400">
                    <FileStack className="h-20 w-20 mb-6 opacity-10" />
                    <p className="font-bold">{t.files.noFiles}</p>
                </div>
            ) : (
                <div className="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    {files.map(file => (
                        <div key={file.id} className="group relative flex flex-col items-center rounded-2xl bg-gray-50/50 p-4 transition-all hover:bg-white hover:shadow-xl dark:bg-gray-900/50">
                            <div className="w-full flex justify-end mb-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button 
                                    onClick={() => onDeleteFile(file.id)}
                                    className="text-red-500 hover:scale-110 transition-transform"
                                >
                                    <X className="h-3 w-3" />
                                </button>
                            </div>
                            <div className="flex h-12 w-12 items-center justify-center rounded-xl bg-white shadow-sm dark:bg-gray-800 text-blue-500 font-extrabold text-[10px] uppercase">
                                {file.extension || 'FILE'}
                            </div>
                            <h4 className="mt-3 w-full truncate text-center text-[10px] font-bold text-scribehub-blue dark:text-white">{file.name}</h4>
                            <p className="mt-0.5 text-[9px] text-gray-400">{(file.size / 1024 / 1024).toFixed(2)} MB</p>
                            <button className="mt-4 flex h-8 w-8 items-center justify-center rounded-full bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all">
                                <Download className="h-3.5 w-3.5" />
                            </button>
                        </div>
                    ))}
                </div>
            )}
        </div>
    );
}

function TeamView({ project, members, t, onInviteClick }: { project: Project, members: Member[], t: any, onInviteClick: () => void }) {
    const { auth } = usePage<any>().props;
    const [copied, setCopied] = useState(false);
    const [isUpdatingInvite, setIsUpdatingInvite] = useState(false);

    const currentUserRole = useMemo(() => {
        return members.find(m => m.user.id === auth.user.id)?.role || 'viewer';
    }, [members, auth.user.id]);

    const canManageInvite = currentUserRole === 'owner' || currentUserRole === 'manager';

    const inviteUrl = project.invite_token 
        ? `${window.location.origin}/projects/join/${project.invite_token}`
        : '';

    const handleCopy = () => {
        if (!inviteUrl) return;
        navigator.clipboard.writeText(inviteUrl);
        setCopied(true);
        setTimeout(() => setCopied(false), 2000);
    };

    const handleUpdateInviteSettings = (role?: string, regenerate?: boolean) => {
        setIsUpdatingInvite(true);
        router.post(`/projects/${project.id}/invite-settings`, {
            invite_role: role || project.invite_role,
            regenerate: regenerate || false
        }, {
            preserveScroll: true,
            onFinish: () => setIsUpdatingInvite(false)
        });
    };

    return (
        <div className="space-y-6">
            <div className="rounded-[1.5rem] bg-white p-6 shadow-sm dark:bg-gray-900/50 border border-white dark:border-gray-800">
                <div className="flex items-center justify-between mb-8">
                    <h2 className="text-lg font-black text-scribehub-blue dark:text-white">{t.team.title}</h2>
                    <button 
                        onClick={onInviteClick}
                        className="flex items-center gap-2 rounded-xl bg-scribehub-blue px-4 py-2 text-[10px] font-black text-white active:scale-95 shadow-lg"
                    >
                        <UserPlus className="h-3.5 w-3.5" /> {t.team.invite}
                    </button>
                </div>
                <div className="space-y-3">
                    {members.map(member => (
                        <div key={member.id} className="flex items-center gap-3 rounded-2xl bg-gray-50/50 p-4 dark:bg-gray-900 border border-gray-50 dark:border-gray-800">
                            <div className="relative">
                                <div className="h-10 w-10 rounded-xl bg-scribehub-blue flex items-center justify-center text-white text-xs font-black overflow-hidden border border-white/20">
                                    {member.user.avatar ? (
                                        <img src={member.user.avatar} alt={member.user.name} className="h-full w-full object-cover" />
                                    ) : member.user.avatar_path ? (
                                        <img src={member.user.avatar_path} alt={member.user.name} className="h-full w-full object-cover" />
                                    ) : (
                                        <img src={`https://ui-avatars.com/api/?name=${encodeURIComponent(member.user.name)}&color=7F9CF5&background=EBF4FF`} alt={member.user.name} className="h-full w-full object-cover" />
                                    )}
                                </div>
                                {member.status === 'pending' && (
                                    <div className="absolute -top-1 -right-1 h-3 w-3 rounded-full bg-amber-500 border-2 border-white dark:border-gray-900" title="Pending" />
                                )}
                            </div>
                            <div className="flex-1">
                                <div className="flex items-center gap-2">
                                    <h4 className="font-bold text-scribehub-blue dark:text-white">{member.user.name}</h4>
                                    {member.status === 'pending' && (
                                        <span className="text-[10px] font-bold text-amber-500 bg-amber-50 px-1.5 py-0.5 rounded uppercase">Pending</span>
                                    )}
                                </div>
                                <p className="text-xs text-gray-400">{member.user.email}</p>
                            </div>
                            <div className="flex items-center gap-6">
                                <div className="text-right">
                                    <p className="text-[10px] font-black uppercase text-gray-400">Role</p>
                                    <p className="text-xs font-bold text-scribehub-blue dark:text-blue-400 capitalize">{member.role}</p>
                                </div>
                                {canManageInvite && member.role !== 'owner' && (
                                    <button 
                                        onClick={() => {
                                            if (confirm('Are you sure you want to remove this member?')) {
                                                router.post(`/projects/${project.id}/members/${member.id}/remove`, {}, { preserveScroll: true });
                                            }
                                        }}
                                        className="h-10 w-10 flex items-center justify-center rounded-xl hover:bg-red-50 hover:text-red-500 text-gray-400"
                                    >
                                        <Trash2 className="h-4 w-4" />
                                    </button>
                                )}
                            </div>
                        </div>
                    ))}
                </div>
            </div>

            {canManageInvite && (
                <div className="rounded-[1.5rem] bg-indigo-50/50 p-6 dark:bg-indigo-950/20 border border-indigo-100 dark:border-indigo-900/50">
                    <div className="flex items-center gap-3 mb-6">
                        <div className="h-10 w-10 rounded-xl bg-indigo-500 flex items-center justify-center text-white shadow-lg">
                            <LinkIcon className="h-5 w-5" />
                        </div>
                        <div>
                            <h3 className="text-base font-black text-indigo-900 dark:text-indigo-100 italic">Invite via Link</h3>
                            <p className="text-xs text-indigo-600/70 dark:text-indigo-400/70">Share this link to instantly invite teammates</p>
                        </div>
                    </div>

                    <div className="flex flex-col md:flex-row gap-4">
                        <div className="flex-1">
                            <div className="relative">
                                <input 
                                    type="text" 
                                    readOnly 
                                    value={inviteUrl}
                                    className="w-full rounded-xl border-none bg-white p-4 pr-24 text-sm font-medium text-gray-600 shadow-sm dark:bg-gray-900 dark:text-gray-300 focus:ring-2 focus:ring-indigo-500"
                                />
                                <button 
                                    onClick={handleCopy}
                                    className={cn(
                                        "absolute right-2 top-2 bottom-2 flex items-center gap-2 rounded-lg px-4 text-xs font-black transition-all",
                                        copied ? "bg-emerald-500 text-white" : "bg-indigo-500 text-white hover:bg-indigo-600"
                                    )}
                                >
                                    {copied ? <CheckCircle2 className="h-3.5 w-3.5" /> : <Copy className="h-3.5 w-3.5" />}
                                    {copied ? 'COPIED' : 'COPY'}
                                </button>
                            </div>
                        </div>

                        <div className="flex items-center gap-4">
                            <div className="flex flex-col">
                                <label className="text-[10px] font-black text-indigo-900/50 dark:text-indigo-100/50 uppercase mb-1 ml-1">Default Role</label>
                                <select 
                                    value={project.invite_role}
                                    onChange={(e) => handleUpdateInviteSettings(e.target.value)}
                                    className="rounded-xl border-none bg-white py-3 pl-4 pr-10 text-xs font-bold text-gray-700 shadow-sm dark:bg-gray-900 dark:text-gray-300 focus:ring-2 focus:ring-indigo-500"
                                >
                                    <option value="manager">Manager</option>
                                    <option value="contributor">Contributor</option>
                                    <option value="viewer">Viewer</option>
                                </select>
                            </div>

                            <button 
                                onClick={() => handleUpdateInviteSettings(undefined, true)}
                                disabled={isUpdatingInvite}
                                className="mt-5 h-10 w-10 flex items-center justify-center rounded-xl bg-white text-gray-400 hover:text-indigo-500 shadow-sm dark:bg-gray-900 transition-all active:scale-95"
                                title="Regenerate Link"
                            >
                                <RefreshCcw className={cn("h-4 w-4", isUpdatingInvite && "animate-spin")} />
                            </button>
                        </div>
                    </div>

                    {!project.invite_token && (
                        <div className="mt-4 flex items-center gap-2 rounded-lg bg-amber-50 p-3 text-xs font-medium text-amber-700 dark:bg-amber-900/20 dark:text-amber-400 border border-amber-100 dark:border-amber-900/50">
                            <AlertCircle className="h-4 w-4" />
                            No invitation link generated yet. Click the refresh button to create one.
                        </div>
                    )}
                </div>
            )}
        </div>
    );
}

function ActivityView({ comments, project, t, language }: { comments: Comment[], project: Project, t: any, language: string }) {
    const { auth } = usePage<any>().props;
    const { data, setData, post, processing, reset } = useForm({
        commentable_id: project.id,
        commentable_type: 'App\\Models\\Project',
        content: '',
        attachments: [] as File[]
    });

    const [attachmentPreviews, setAttachmentPreviews] = useState<{ url: string, type: string, name: string }[]>([]);
    const [editingCommentId, setEditingCommentId] = useState<number | null>(null);
    const [editContent, setEditContent] = useState('');
    const chatEndRef = useRef<HTMLDivElement>(null);
    const fileRef = useRef<HTMLInputElement>(null);

    const scrollToBottom = () => {
        chatEndRef.current?.scrollIntoView({ behavior: 'smooth' });
    };

    useEffect(() => {
        scrollToBottom();
    }, [comments]);

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/projects/comments', {
            onSuccess: () => {
                reset('content', 'attachments');
                setAttachmentPreviews([]);
            },
            preserveScroll: true
        });
    };

    const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const files = Array.from(e.target.files || []);
        if (files.length > 0) {
            setData('attachments', [...data.attachments, ...files]);
            
            files.forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        setAttachmentPreviews(prev => [...prev, { 
                            url: e.target?.result as string, 
                            type: 'image', 
                            name: file.name 
                        }]);
                    };
                    reader.readAsDataURL(file);
                } else {
                    setAttachmentPreviews(prev => [...prev, { 
                        url: '', 
                        type: 'file', 
                        name: file.name 
                    }]);
                }
            });
        }
    };

    const removeAttachment = (index: number) => {
        const newFiles = [...data.attachments];
        newFiles.splice(index, 1);
        setData('attachments', newFiles);

        const newPreviews = [...attachmentPreviews];
        newPreviews.splice(index, 1);
        setAttachmentPreviews(newPreviews);
    };

    const addEmoji = (emoji: string) => {
        setData('content', data.content + emoji);
    };

    const formatTime = (dateStr: string) => {
        try {
            const date = new Date(dateStr);
            return new Intl.DateTimeFormat(language === 'en' ? 'en-US' : 'th-TH', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            }).format(date);
        } catch (e) {
            return dateStr;
        }
    };

    const handleDelete = (id: number) => {
        if (confirm(language === 'en' ? 'Delete this message?' : 'ต้องการลบข้อความนี้หรือไม่?')) {
            router.delete(`/projects/comments/${id}`, { preserveScroll: true });
        }
    };

    const handleUpdate = (id: number) => {
        router.patch(`/projects/comments/${id}`, { content: editContent }, {
            onSuccess: () => setEditingCommentId(null),
            preserveScroll: true
        });
    };

    return (
        <div className="flex flex-col h-[600px] rounded-[1.5rem] bg-white overflow-hidden shadow-sm dark:bg-gray-900/50">
            <div className="p-6 pb-2">
                <h2 className="text-lg font-black text-scribehub-blue dark:text-white">{t.activity.title}</h2>
            </div>
            
            <div className="flex-1 overflow-y-auto px-6 py-4 space-y-6 scrollbar-hide">
                {comments.map((comment, i) => {
                    const isOwn = comment.user.id === auth.user.id;
                    return (
                        <div key={comment.id} className={cn("flex gap-3 max-w-[85%]", isOwn ? "ml-auto flex-row-reverse" : "")}>
                            <div className="mt-1 shrink-0 h-8 w-8 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center overflow-hidden border border-black/5 dark:border-white/5">
                                {comment.user.avatar ? (
                                    <img src={comment.user.avatar} alt={comment.user.name} className="h-full w-full object-cover" />
                                ) : comment.user.avatar_path ? (
                                    <img src={comment.user.avatar_path} alt={comment.user.name} className="h-full w-full object-cover" />
                                ) : (
                                    <img src={`https://ui-avatars.com/api/?name=${encodeURIComponent(comment.user.name)}&color=7F9CF5&background=EBF4FF`} alt={comment.user.name} className="h-full w-full object-cover" />
                                )}
                            </div>
                            <div className={cn("flex flex-col gap-1.5", isOwn ? "items-end" : "items-start")}>
                                <div className="flex items-baseline gap-2">
                                    <span className="text-[10px] font-black text-gray-400">{comment.user.name}</span>
                                    <span className="text-[8px] font-bold text-gray-300 uppercase tracking-tighter">{formatTime(comment.created_at)}</span>
                                </div>
                                <div className="group relative flex items-center">
                                    {isOwn && !editingCommentId && (
                                        <div className="absolute -left-20 top-1/2 -translate-y-1/2 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-all duration-200 z-10 px-2">
                                            <button 
                                                onClick={() => {
                                                    setEditingCommentId(comment.id);
                                                    setEditContent(comment.content);
                                                }}
                                                className="h-7 w-7 flex items-center justify-center rounded-lg bg-white shadow-sm text-gray-400 hover:text-blue-500"
                                            >
                                                <Edit className="h-3 w-3" />
                                            </button>
                                            <button 
                                                onClick={() => handleDelete(comment.id)}
                                                className="h-7 w-7 flex items-center justify-center rounded-xl bg-white shadow-sm text-gray-400 hover:text-red-500"
                                            >
                                                <Trash2 className="h-3 w-3" />
                                            </button>
                                        </div>
                                    )}
                                    <div className={cn(
                                        "rounded-2xl p-4 text-sm shadow-sm transition-all hover:shadow-md",
                                        isOwn 
                                            ? "bg-scribehub-blue text-white rounded-tr-none" 
                                            : "bg-gray-50 text-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-tl-none"
                                    )}>
                                        {comment.attachments && comment.attachments.length > 0 && (
                                            <div className="mb-3 space-y-2">
                                                {comment.attachments.map((att, idx) => (
                                                    <div key={idx} className="overflow-hidden rounded-xl bg-black/5 dark:bg-white/5">
                                                        {att.extension.match(/(jpg|jpeg|png|gif)$/i) ? (
                                                            <div className="flex flex-col">
                                                                <img 
                                                                    src={`/storage/${att.path}`} 
                                                                    alt={att.name} 
                                                                    className="max-h-[200px] w-full object-contain cursor-pointer transition-transform hover:scale-105"
                                                                    onClick={() => window.open(`/storage/${att.path}`, '_blank')}
                                                                />
                                                                <div className="p-2 text-[8px] font-bold text-gray-400 bg-white/50 dark:bg-black/20 truncate">
                                                                    {att.name}
                                                                </div>
                                                            </div>
                                                        ) : (
                                                            <a 
                                                                href={`/storage/${att.path}`} 
                                                                target="_blank" 
                                                                className="flex items-center gap-3 p-3 text-xs font-bold hover:underline"
                                                            >
                                                                <Paperclip className="h-4 w-4 shrink-0" />
                                                                <span className="truncate">{att.name}</span>
                                                            </a>
                                                        )}
                                                    </div>
                                                ))}
                                            </div>
                                        )}
                                        {editingCommentId === comment.id ? (
                                            <div className="flex flex-col gap-2 min-w-[200px]">
                                                <textarea 
                                                    value={editContent}
                                                    onChange={e => setEditContent(e.target.value)}
                                                    className="w-full bg-white/10 border-none rounded-lg p-2 text-white placeholder-white/50 text-xs focus:ring-1 focus:ring-white/30"
                                                    autoFocus
                                                />
                                                <div className="flex justify-end gap-2">
                                                    <button onClick={() => setEditingCommentId(null)} className="text-[10px] font-bold hover:underline">Cancel</button>
                                                    <button onClick={() => handleUpdate(comment.id)} className="text-[10px] font-black underline decoration-2">Save</button>
                                                </div>
                                            </div>
                                        ) : (
                                            comment.content
                                        )}
                                    </div>
                                </div>
                            </div>
                        </div>
                    );
                })}
                <div ref={chatEndRef} />
            </div>

            <div className="p-6 bg-gray-50/50 dark:bg-gray-800/30 border-t border-gray-100 dark:border-gray-800">
                {attachmentPreviews.length > 0 && (
                    <div className="mb-4 flex flex-wrap gap-2 animate-in slide-in-from-bottom-2 duration-300">
                        {attachmentPreviews.map((preview, idx) => (
                            <div key={idx} className="relative group rounded-xl bg-white p-1 shadow-md dark:bg-gray-900 overflow-hidden ring-1 ring-gray-100 dark:ring-gray-800">
                                {preview.type === 'file' ? (
                                    <div className="flex h-14 w-14 items-center justify-center rounded-lg bg-blue-50 text-blue-600 dark:bg-blue-900/20">
                                        <Paperclip className="h-6 w-6" />
                                    </div>
                                ) : (
                                    <img src={preview.url} className="h-14 w-14 rounded-lg object-cover" />
                                )}
                                <div className="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <button 
                                        type="button"
                                        onClick={() => removeAttachment(idx)}
                                        className="text-white hover:scale-110 transition-transform"
                                    >
                                        <X className="h-4 w-4" />
                                    </button>
                                </div>
                                <div className="absolute bottom-0 left-0 right-0 p-0.5 bg-black/40 text-white text-[6px] truncate text-center">
                                    {preview.name}
                                </div>
                            </div>
                        ))}
                    </div>
                )}
                
                <form onSubmit={handleSubmit} className="flex gap-3">
                    <div className="relative flex-1 group">
                        <input
                            type="text"
                            placeholder={t.activity.writeComment}
                            value={data.content}
                            onChange={e => setData('content', e.target.value)}
                            className="w-full rounded-2xl border-none bg-white p-4 pr-32 text-sm shadow-inner transition-all focus:ring-2 focus:ring-scribehub-blue/20 dark:bg-gray-900"
                        />
                        <div className="absolute right-2 top-2 flex items-center gap-1 opacity-60 group-focus-within:opacity-100 transition-opacity">
                            <button 
                                type="button" 
                                onClick={() => fileRef.current?.click()}
                                className="flex h-10 w-10 items-center justify-center rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-400 transition-colors"
                            >
                                <Paperclip className="h-4 w-4" />
                            </button>
                            <div className="flex items-center gap-1 bg-gray-100/50 dark:bg-gray-800/50 rounded-xl px-2 py-1">
                                {['👍', '❤️', '✅'].map(emoji => (
                                    <button 
                                        key={emoji}
                                        type="button" 
                                        onClick={() => addEmoji(emoji)}
                                        className="h-8 w-8 text-sm transition-transform hover:scale-125"
                                    >
                                        {emoji}
                                    </button>
                                ))}
                            </div>
                        </div>
                    </div>
                    <button 
                        disabled={processing || (!data.content && data.attachments.length === 0)}
                        className="h-14 w-14 shrink-0 flex items-center justify-center rounded-2xl bg-scribehub-blue text-white shadow-xl shadow-blue-900/20 transition-all hover:opacity-90 active:scale-95 disabled:opacity-50"
                    >
                        <Send className="h-5 w-5" />
                    </button>
                </form>
                <input 
                    type="file" 
                    ref={fileRef} 
                    className="hidden" 
                    multiple
                    onChange={handleFileChange} 
                />
            </div>
        </div>
    );
}
