import { useState, useEffect, useRef } from 'react';
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetClose } from '@/components/ui/sheet';
import { Task, TaskChecklist, ProjectFile, Comment, TaskActivity, User } from '@/types/project';
import { Badge } from '@/components/ui/badge';
import { Separator } from '@/components/ui/separator';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Checkbox } from '@/components/ui/checkbox';
import { cn } from '@/lib/utils';
import { 
    Calendar, Flag, User as UserIcon, CheckSquare, 
    Paperclip, MessageSquare, Activity, 
    Trash2, Plus, X, FileText, Send, Clock, Download
} from 'lucide-react';
import axios from 'axios';
import { formatDistanceToNow, format } from 'date-fns';
import { toast } from 'sonner';

interface Props {
    open: boolean;
    onOpenChange: (open: boolean) => void;
    taskId: number | null;
    onTaskUpdate?: () => void;
}

export function TaskSidePanel({ open, onOpenChange, taskId, onTaskUpdate }: Props) {
    const [task, setTask] = useState<Task | null>(null);
    const [loading, setLoading] = useState(false);
    const [newChecklistTitle, setNewChecklistTitle] = useState('');
    const [newComment, setNewComment] = useState('');
    const [activeTab, setActiveTab] = useState<'activity' | 'comments' | 'files'>('comments');
    const [uploading, setUploading] = useState(false);
    const fileInputRef = useRef<HTMLInputElement>(null);

    useEffect(() => {
        if (open && taskId) {
            fetchTask();
        } else {
            setTask(null);
            setNewChecklistTitle('');
            setNewComment('');
        }
    }, [open, taskId]);

    const fetchTask = async () => {
        if (!taskId) return;
        setLoading(true);
        try {
            const res = await axios.get(`/projects/tasks/${taskId}`);
            setTask(res.data);
        } catch (error) {
            toast.error("Failed to load task details");
            onOpenChange(false);
        } finally {
            setLoading(false);
        }
    };

    const handleAddChecklist = async (e: React.FormEvent) => {
        e.preventDefault();
        if (!newChecklistTitle.trim() || !taskId) return;

        try {
            await axios.post(`/projects/tasks/${taskId}/checklists`, { title: newChecklistTitle });
            setNewChecklistTitle('');
            fetchTask();
        } catch (error) {
            toast.error("Failed to add checklist item");
        }
    };

    const handleToggleChecklist = async (checklist: TaskChecklist) => {
        try {
            // Optimistic update
            setTask(prev => prev ? ({
                ...prev,
                checklists: prev.checklists?.map(c => 
                    c.id === checklist.id ? { ...c, is_completed: !c.is_completed } : c
                )
            }) : null);

            await axios.patch(`/projects/tasks/checklists/${checklist.id}`, { 
                is_completed: !checklist.is_completed 
            });
        } catch (error) {
            fetchTask(); // Revert on failure
        }
    };

    const handleDeleteChecklist = async (id: number) => {
        try {
            await axios.delete(`/projects/tasks/checklists/${id}`);
            setTask(prev => prev ? ({
                ...prev,
                checklists: prev.checklists?.filter(c => c.id !== id)
            }) : null);
        } catch (error) {
            toast.error("Failed to delete checklist item");
        }
    };

    const handleAddComment = async (e: React.FormEvent) => {
        e.preventDefault();
        if (!newComment.trim() || !taskId) return;

        try {
            await axios.post('/projects/comments', {
                commentable_id: taskId,
                commentable_type: 'App\\Models\\Task',
                content: newComment
            });
            setNewComment('');
            fetchTask();
        } catch (error) {
            toast.error("Failed to post comment");
        }
    };

    const handleFileUpload = async (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0];
        if (!file || !taskId) return;

        const formData = new FormData();
        formData.append('file', file);

        setUploading(true);
        try {
            await axios.post(`/projects/tasks/${taskId}/files`, formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            });
            toast.success("File uploaded");
            fetchTask();
        } catch (error) {
            toast.error("Failed to upload file");
        } finally {
            setUploading(false);
            if (fileInputRef.current) fileInputRef.current.value = '';
        }
    };

    if (!taskId) return null;

    return (
        <Sheet open={open} onOpenChange={onOpenChange}>
            <SheetContent className="w-full sm:max-w-xl p-0 flex flex-col bg-white dark:bg-gray-950 overflow-hidden">
                {loading || !task ? (
                    <div className="flex-1 flex items-center justify-center">
                        <Activity className="h-8 w-8 animate-spin text-gray-400" />
                    </div>
                ) : (
                    <>
                        {/* Header */}
                        <div className="p-6 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50">
                            <div className="flex items-start justify-between gap-4">
                                <div className="space-y-1">
                                    <div className="flex items-center gap-2">
                                        <Badge variant="outline" className={cn(
                                            "uppercase text-[10px] font-black tracking-widest border-0",
                                            task.status === 'done' ? "bg-emerald-100 text-emerald-600 dark:bg-emerald-900/20" :
                                            task.status === 'in_progress' ? "bg-blue-100 text-blue-600 dark:bg-blue-900/20" :
                                            task.status === 'review' ? "bg-orange-100 text-orange-600 dark:bg-orange-900/20" :
                                            "bg-gray-100 text-gray-500 dark:bg-gray-800"
                                        )}>
                                            {task.status.replace('_', ' ')}
                                        </Badge>
                                        <span className="text-gray-300 text-xs">#{task.id}</span>
                                    </div>
                                    <h2 className="text-xl font-black text-scribehub-blue dark:text-white leading-tight">
                                        {task.title}
                                    </h2>
                                </div>
                            </div>
                            
                            <div className="grid grid-cols-3 gap-4 mt-6">
                                <div className="space-y-1">
                                    <div className="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                        <UserIcon className="h-3 w-3" /> Assignee
                                    </div>
                                    <div className="flex items-center gap-2">
                                        {task.assignee ? (
                                            <>
                                                <Avatar className="h-6 w-6 border border-white shadow-sm dark:border-gray-800">
                                                    <AvatarFallback className="text-[9px] bg-blue-50 text-blue-600">{task.assignee.name[0]}</AvatarFallback>
                                                </Avatar>
                                                <span className="text-xs font-bold text-gray-700 dark:text-gray-200 truncate max-w-[100px]">{task.assignee.name}</span>
                                            </>
                                        ) : (
                                            <span className="text-xs text-gray-400 italic">Unassigned</span>
                                        )}
                                    </div>
                                </div>

                                <div className="space-y-1">
                                    <div className="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                        <Flag className="h-3 w-3" /> Priority
                                    </div>
                                    <div className={cn(
                                        "text-xs font-black uppercase",
                                        task.priority === 'urgent' ? "text-red-500" :
                                        task.priority === 'high' ? "text-orange-500" :
                                        "text-blue-500"
                                    )}>
                                        {task.priority || 'Normal'}
                                    </div>
                                </div>

                                <div className="space-y-1">
                                    <div className="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                        <Calendar className="h-3 w-3" /> Due Date
                                    </div>
                                    <div className="text-xs font-bold text-gray-700 dark:text-gray-200">
                                        {task.due_date ? format(new Date(task.due_date), 'MMM d, yyyy') : '-'}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="flex-1 overflow-y-auto">
                            <div className="p-6 space-y-8">
                                {/* Description */}
                                {task.description && (
                                    <div className="prose prose-sm dark:prose-invert max-w-none">
                                        <p className="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                                            {task.description}
                                        </p>
                                    </div>
                                )}

                                {/* Checklists */}
                                <div className="space-y-4">
                                    <div className="flex items-center justify-between">
                                        <h3 className="flex items-center gap-2 text-sm font-black uppercase tracking-widest text-gray-400">
                                            <CheckSquare className="h-4 w-4" /> Checklist
                                        </h3>
                                        {task.checklists && task.checklists.length > 0 && (
                                            <span className="text-[10px] font-bold bg-gray-100 dark:bg-gray-800 px-2 py-0.5 rounded-full text-gray-500">
                                                {Math.round((task.checklists.filter(c => c.is_completed).length / task.checklists.length) * 100)}%
                                            </span>
                                        )}
                                    </div>
                                    
                                    <div className="space-y-2">
                                        {task.checklists?.map(item => (
                                            <div key={item.id} className="group flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
                                                <Checkbox 
                                                    checked={item.is_completed} 
                                                    onCheckedChange={() => handleToggleChecklist(item)}
                                                />
                                                <span className={cn(
                                                    "flex-1 text-sm transition-all",
                                                    item.is_completed ? "text-gray-400 line-through decoration-gray-300" : "text-gray-700 dark:text-gray-200 font-medium"
                                                )}>
                                                    {item.title}
                                                </span>
                                                <button 
                                                    onClick={() => handleDeleteChecklist(item.id)}
                                                    className="opacity-0 group-hover:opacity-100 p-1 text-gray-400 hover:text-red-500 transition-all"
                                                >
                                                    <Trash2 className="h-3.5 w-3.5" />
                                                </button>
                                            </div>
                                        ))}

                                        <form onSubmit={handleAddChecklist} className="flex gap-2 items-center mt-2">
                                            <Plus className="h-4 w-4 text-gray-400 shrink-0" />
                                            <Input 
                                                value={newChecklistTitle}
                                                onChange={e => setNewChecklistTitle(e.target.value)}
                                                placeholder="Add an item..." 
                                                className="border-none bg-transparent shadow-none focus-visible:ring-0 p-0 h-auto text-sm placeholder:text-gray-400"
                                            />
                                        </form>
                                    </div>
                                </div>

                                {/* Tabs for Activity, Comments, Files */}
                                <div>
                                    <div className="flex items-center gap-1 border-b border-gray-100 dark:border-gray-800 mb-4">
                                        <button
                                            onClick={() => setActiveTab('comments')}
                                            className={cn(
                                                "px-4 py-2 text-xs font-bold uppercase tracking-wider border-b-2 transition-all",
                                                activeTab === 'comments' 
                                                    ? "border-scribehub-blue text-scribehub-blue dark:border-white dark:text-white" 
                                                    : "border-transparent text-gray-400 hover:text-gray-600"
                                            )}
                                        >
                                            Comments <span className="ml-1 text-[9px] opacity-70">({task.comments?.length || 0})</span>
                                        </button>
                                        <button
                                            onClick={() => setActiveTab('files')}
                                            className={cn(
                                                "px-4 py-2 text-xs font-bold uppercase tracking-wider border-b-2 transition-all",
                                                activeTab === 'files' 
                                                    ? "border-scribehub-blue text-scribehub-blue dark:border-white dark:text-white" 
                                                    : "border-transparent text-gray-400 hover:text-gray-600"
                                            )}
                                        >
                                            Files <span className="ml-1 text-[9px] opacity-70">({task.attachments?.length || 0})</span>
                                        </button>
                                        <button
                                            onClick={() => setActiveTab('activity')}
                                            className={cn(
                                                "px-4 py-2 text-xs font-bold uppercase tracking-wider border-b-2 transition-all",
                                                activeTab === 'activity' 
                                                    ? "border-scribehub-blue text-scribehub-blue dark:border-white dark:text-white" 
                                                    : "border-transparent text-gray-400 hover:text-gray-600"
                                            )}
                                        >
                                            Activity
                                        </button>
                                    </div>

                                    {/* Comments Tab */}
                                    {activeTab === 'comments' && (
                                        <div className="space-y-4 animate-in fade-in slide-in-from-bottom-2 duration-300">
                                            <div className="space-y-4 max-h-[300px] overflow-y-auto pr-2">
                                                {task.comments?.map(comment => (
                                                    <div key={comment.id} className="flex gap-3">
                                                        <Avatar className="h-8 w-8 mt-1">
                                                            <AvatarFallback className="text-[10px] bg-gray-100">{comment.user.name[0]}</AvatarFallback>
                                                        </Avatar>
                                                        <div className="flex-1 space-y-1">
                                                            <div className="flex items-center justify-between">
                                                                <span className="text-xs font-bold text-gray-900 dark:text-white">{comment.user.name}</span>
                                                                <span className="text-[10px] text-gray-400">{formatDistanceToNow(new Date(comment.created_at), { addSuffix: true })}</span>
                                                            </div>
                                                            <div className="text-sm text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-900 rounded-xl rounded-tl-none p-3">
                                                                {comment.content}
                                                            </div>
                                                        </div>
                                                    </div>
                                                ))}
                                                {(!task.comments || task.comments.length === 0) && (
                                                    <div className="text-center py-8 text-gray-400 text-xs italic">
                                                        No comments yet. Start the conversation!
                                                    </div>
                                                )}
                                            </div>
                                            <form onSubmit={handleAddComment} className="relative">
                                                <Textarea 
                                                    value={newComment}
                                                    onChange={e => setNewComment(e.target.value)}
                                                    placeholder="Write a comment..." 
                                                    className="min-h-[80px] pr-12 text-sm bg-gray-50/50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-800 focus:ring- scribehub-blue resize-none rounded-xl"
                                                />
                                                <Button 
                                                    size="icon" 
                                                    type="submit" 
                                                    disabled={!newComment.trim()}
                                                    className="absolute right-2 bottom-2 h-8 w-8 rounded-lg bg-scribehub-blue text-white shadow-sm hover:bg-blue-700 disabled:opacity-50"
                                                >
                                                    <Send className="h-4 w-4" />
                                                </Button>
                                            </form>
                                        </div>
                                    )}

                                    {/* Files Tab */}
                                    {activeTab === 'files' && (
                                        <div className="space-y-4 animate-in fade-in slide-in-from-bottom-2 duration-300">
                                            <div className="grid grid-cols-2 gap-3">
                                                {task.attachments?.map(file => (
                                                    <div key={file.id} className="flex items-center gap-3 p-3 rounded-xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900/50 hover:shadow-md transition-all group">
                                                        <div className="h-10 w-10 shrink-0 flex items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500">
                                                            <FileText className="h-5 w-5" />
                                                        </div>
                                                        <div className="flex-1 min-w-0">
                                                            <div className="text-xs font-bold truncate">{file.name}</div>
                                                            <div className="text-[10px] text-gray-400">{(file.size / 1024).toFixed(1)} KB</div>
                                                        </div>
                                                        <a 
                                                            href={`/storage/${file.path}`} 
                                                            target="_blank" 
                                                            download
                                                            className="h-8 w-8 flex items-center justify-center rounded-lg bg-gray-50 text-gray-400 hover:text-blue-500 hover:bg-blue-50 transition-all opacity-0 group-hover:opacity-100"
                                                        >
                                                            <Download className="h-4 w-4" />
                                                        </a>
                                                    </div>
                                                ))}
                                            </div>
                                            
                                            <div 
                                                onClick={() => fileInputRef.current?.click()}
                                                className="border-2 border-dashed border-gray-200 dark:border-gray-800 rounded-2xl p-6 flex flex-col items-center justify-center gap-2 cursor-pointer hover:border-blue-400 hover:bg-blue-50/10 transition-colors text-center"
                                            >
                                                <input 
                                                    type="file" 
                                                    ref={fileInputRef} 
                                                    className="hidden" 
                                                    onChange={handleFileUpload}
                                                    disabled={uploading}
                                                />
                                                <div className="h-10 w-10 flex items-center justify-center rounded-full bg-blue-50 text-blue-600 dark:bg-blue-900/20">
                                                    <Paperclip className="h-5 w-5" />
                                                </div>
                                                <p className="text-xs font-bold text-gray-600 dark:text-gray-300">Click to upload attachment</p>
                                            </div>
                                        </div>
                                    )}

                                    {/* Activity Tab */}
                                    {activeTab === 'activity' && (
                                        <div className="space-y-4 animate-in fade-in slide-in-from-bottom-2 duration-300">
                                            {task.activities?.map(activity => (
                                                <div key={activity.id} className="flex gap-3 text-xs">
                                                    <div className="mt-1 h-2 w-2 rounded-full bg-gray-300 dark:bg-gray-700" />
                                                    <div className="flex-1 pb-4 border-l border-gray-100 dark:border-gray-800 pl-4 -ml-1.5 space-y-1">
                                                        <div className="flex items-center gap-2">
                                                            <span className="font-bold">{activity.user.name}</span>
                                                            <span className="text-gray-400 text-[10px]">{formatDistanceToNow(new Date(activity.created_at), { addSuffix: true })}</span>
                                                        </div>
                                                        <p className="text-gray-500 dark:text-gray-400">
                                                            {activity.details?.description || activity.activity_type}
                                                        </p>
                                                    </div>
                                                </div>
                                            ))}
                                            {(!task.activities || task.activities.length === 0) && (
                                                <div className="text-center py-8 text-gray-400 text-xs italic">
                                                    No activity recorded yet
                                                </div>
                                            )}
                                        </div>
                                    )}
                                </div>
                            </div>
                        </div>
                    </>
                )}
            </SheetContent>
        </Sheet>
    );
}
