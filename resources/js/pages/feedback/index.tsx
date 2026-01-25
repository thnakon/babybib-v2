import AppLayout from '@/layouts/app-layout';
import { Head, useForm, router } from '@inertiajs/react';
import { useState, useMemo } from 'react';
import { 
    MessageSquare, Search, Filter, Plus, 
    Bug, Lightbulb, TrendingUp, CheckCircle, 
    AlertCircle, Clock, X, ThumbsUp
} from 'lucide-react';
import { cn } from '@/lib/utils';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogFooter,
} from '@/components/ui/dialog';
import { Textarea } from '@/components/ui/textarea';
import { formatDistanceToNow } from 'date-fns';
import { Badge } from '@/components/ui/badge';

interface User {
    id: number;
    name: string;
    avatar?: string;
}

interface Feedback {
    id: number;
    title: string;
    description: string;
    type: 'bug' | 'feature' | 'improvement';
    status: 'pending' | 'in_progress' | 'completed' | 'closed';
    created_at: string;
    user: User;
}

interface Props {
    feedbacks: Feedback[];
}

export default function FeedbackIndex({ feedbacks }: Props) {
    const [searchQuery, setSearchQuery] = useState('');
    const [filterType, setFilterType] = useState<string>('all');
    const [filterStatus, setFilterStatus] = useState<string>('all');
    const [isCreateModalOpen, setIsCreateModalOpen] = useState(false);

    const { data, setData, post, processing, reset, errors } = useForm({
        title: '',
        description: '',
        type: 'improvement',
    });

    const filteredFeedbacks = useMemo(() => {
        return feedbacks.filter(item => {
            const matchesSearch = item.title.toLowerCase().includes(searchQuery.toLowerCase()) || 
                                  item.description.toLowerCase().includes(searchQuery.toLowerCase());
            const matchesType = filterType === 'all' ? true : item.type === filterType;
            const matchesStatus = filterStatus === 'all' ? true : item.status === filterStatus;
            return matchesSearch && matchesType && matchesStatus;
        });
    }, [feedbacks, searchQuery, filterType, filterStatus]);

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/feedback', {
            onSuccess: () => {
                setIsCreateModalOpen(false);
                reset();
            }
        });
    };

    const getTypeIcon = (type: string) => {
        switch (type) {
            case 'bug': return <Bug className="h-4 w-4" />;
            case 'feature': return <Lightbulb className="h-4 w-4" />;
            case 'improvement': return <TrendingUp className="h-4 w-4" />;
            default: return <MessageSquare className="h-4 w-4" />;
        }
    };

    const getTypeColor = (type: string) => {
        switch (type) {
            case 'bug': return 'text-red-500 bg-red-50 dark:bg-red-900/20';
            case 'feature': return 'text-blue-500 bg-blue-50 dark:bg-blue-900/20';
            case 'improvement': return 'text-purple-500 bg-purple-50 dark:bg-purple-900/20';
            default: return 'text-gray-500 bg-gray-50 dark:bg-gray-800';
        }
    };

    const getStatusColor = (status: string) => {
        switch (status) {
            case 'completed': return 'text-emerald-500 bg-emerald-50 dark:bg-emerald-900/20';
            case 'in_progress': return 'text-orange-500 bg-orange-50 dark:bg-orange-900/20';
            case 'closed': return 'text-gray-500 bg-gray-50 dark:bg-gray-800';
            default: return 'text-blue-500 bg-blue-50 dark:bg-blue-900/20';
        }
    };

    return (
        <AppLayout breadcrumbs={[
            { title: 'Feedback', href: '/feedback' },
        ]}>
            <Head title="Feedback & Suggestions" />

            <div className="flex h-full flex-col p-6 max-w-5xl mx-auto w-full">
                {/* Header */}
                <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                    <div>
                        <h1 className="text-2xl font-black text-scribehub-blue dark:text-white flex items-center gap-3">
                            <MessageSquare className="h-6 w-6" />
                            Feedback & Suggestions
                        </h1>
                        <p className="text-sm text-gray-500 mt-1">
                            Help us improve by sharing your feedback, reporting bugs, or requesting features.
                        </p>
                    </div>
                    <Button 
                        onClick={() => setIsCreateModalOpen(true)}
                        className="bg-scribehub-blue hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/20 active:scale-95 transition-all w-full md:w-auto"
                    >
                        <Plus className="h-4 w-4 mr-2" />
                        Submit Feedback
                    </Button>
                </div>

                {/* Filters */}
                <div className="flex flex-wrap items-center gap-3 mb-6 p-4 bg-white dark:bg-gray-900/50 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm">
                    <div className="relative flex-1 min-w-[200px]">
                        <Search className="absolute left-3 top-2.5 h-4 w-4 text-gray-400" />
                        <Input 
                            placeholder="Search feedback..." 
                            value={searchQuery}
                            onChange={(e) => setSearchQuery(e.target.value)}
                            className="pl-9 h-9 text-sm rounded-xl border-gray-200 dark:border-gray-800"
                        />
                    </div>
                    
                    <Select value={filterType} onValueChange={setFilterType}>
                        <SelectTrigger className="w-[140px] h-9 text-xs font-bold rounded-xl border-gray-200 dark:border-gray-800">
                            <SelectValue placeholder="All Types" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="all">All Types</SelectItem>
                            <SelectItem value="bug">Bug Report</SelectItem>
                            <SelectItem value="feature">Feature Request</SelectItem>
                            <SelectItem value="improvement">Improvement</SelectItem>
                        </SelectContent>
                    </Select>

                    <Select value={filterStatus} onValueChange={setFilterStatus}>
                        <SelectTrigger className="w-[140px] h-9 text-xs font-bold rounded-xl border-gray-200 dark:border-gray-800">
                            <SelectValue placeholder="All Statuses" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="all">All Statuses</SelectItem>
                            <SelectItem value="pending">Pending</SelectItem>
                            <SelectItem value="in_progress">In Progress</SelectItem>
                            <SelectItem value="completed">Completed</SelectItem>
                            <SelectItem value="closed">Closed</SelectItem>
                        </SelectContent>
                    </Select>
                </div>

                {/* Feedback List */}
                <div className="space-y-4">
                    {filteredFeedbacks.length > 0 ? (
                        filteredFeedbacks.map((item) => (
                            <div 
                                key={item.id} 
                                className="group bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 p-5 rounded-2xl shadow-sm hover:shadow-md transition-all animate-in fade-in slide-in-from-bottom-2 duration-300"
                            >
                                <div className="flex items-start justify-between gap-4">
                                    <div className="flex-1 min-w-0">
                                        <h3 className="font-bold text-gray-900 dark:text-gray-100 text-lg mb-1 leading-snug">
                                            {item.title}
                                        </h3>
                                        <div className="flex items-center gap-3 mt-3">
                                            <div className="flex items-center gap-2">
                                                <div className="h-6 w-6 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-[10px] font-bold text-gray-500">
                                                    {item.user.avatar ? 
                                                        <img src={item.user.avatar} alt={item.user.name} className="h-full w-full rounded-full object-cover" /> : 
                                                        item.user.name[0]
                                                    }
                                                </div>
                                                <span className="text-xs text-gray-500 font-medium">{item.user.name}</span>
                                            </div>
                                            <div className="h-1 w-1 rounded-full bg-gray-300 dark:bg-gray-700"></div>
                                            <span className="text-xs text-gray-400">
                                                {formatDistanceToNow(new Date(item.created_at), { addSuffix: true })}
                                            </span>
                                        </div>
                                    </div>

                                    <div className="flex flex-col items-end gap-2 shrink-0">
                                        <div className="flex items-center gap-2">
                                            <Badge variant="outline" className={cn("capitalize px-2 py-0.5 rounded-lg border-0 font-bold", getTypeColor(item.type))}>
                                                {getTypeIcon(item.type)}
                                                <span className="ml-1.5">{item.type.replace('_', ' ')}</span>
                                            </Badge>
                                            <Badge variant="outline" className={cn("capitalize px-2 py-0.5 rounded-lg border-0 font-bold", getStatusColor(item.status))}>
                                                {item.status === 'completed' ? <CheckCircle className="h-3 w-3 mr-1" /> : 
                                                 item.status === 'in_progress' ? <Clock className="h-3 w-3 mr-1" /> : 
                                                 <AlertCircle className="h-3 w-3 mr-1" />}
                                                {item.status.replace('_', ' ')}
                                            </Badge>
                                        </div>
                                    </div>
                                </div>
                                
                                {item.description && (
                                    <div className="mt-4 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                                        {item.description}
                                    </div>
                                )}
                            </div>
                        ))
                    ) : (
                        <div className="text-center py-20 bg-gray-50/50 dark:bg-gray-900/20 rounded-3xl border border-dashed border-gray-200 dark:border-gray-800">
                            <div className="h-16 w-16 mx-auto bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                <MessageSquare className="h-8 w-8 text-gray-300" />
                            </div>
                            <h3 className="text-lg font-bold text-gray-900 dark:text-white">No feedback found</h3>
                            <p className="text-sm text-gray-400 mt-1 max-w-sm mx-auto">
                                Be the first to share your thoughts and help us improve the platform.
                            </p>
                            <Button 
                                onClick={() => setIsCreateModalOpen(true)}
                                variant="outline"
                                className="mt-6 font-bold rounded-xl"
                            >
                                Submit Feedback
                            </Button>
                        </div>
                    )}
                </div>
            </div>

            {/* Create Feedback Modal */}
            <Dialog open={isCreateModalOpen} onOpenChange={setIsCreateModalOpen}>
                <DialogContent className="sm:max-w-xl p-0 overflow-hidden bg-white dark:bg-gray-950 border border-gray-100 dark:border-gray-800 shadow-2xl gap-0">
                    <DialogHeader className="p-6 pb-2 space-y-1">
                        <DialogTitle className="text-xl font-bold text-gray-900 dark:text-white">Submit Feedback</DialogTitle>
                        <p className="text-sm text-gray-500 font-normal">
                            Help us improve by sharing your feedback, bug reports, or feature requests.
                        </p>
                    </DialogHeader>
                    
                    <form onSubmit={handleSubmit} className="p-6 space-y-5">
                        <div className="space-y-2">
                            <label className="text-sm font-semibold text-gray-900 dark:text-gray-100">Type</label>
                            <Select 
                                value={data.type === 'improvement' ? 'general' : data.type} 
                                onValueChange={(val: any) => setData('type', val === 'general' ? 'improvement' : val)}
                            >
                                <SelectTrigger className="w-full h-11 px-4 rounded-xl bg-white dark:bg-gray-900 border-2 border-amber-500/20 focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 transition-all font-medium">
                                    <SelectValue />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="general">General</SelectItem>
                                    <SelectItem value="bug">Bug Report</SelectItem>
                                    <SelectItem value="feature">Feature Request</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        <div className="space-y-2">
                            <label className="text-sm font-semibold text-gray-900 dark:text-gray-100">Title</label>
                            <Input 
                                value={data.title}
                                onChange={e => setData('title', e.target.value)}
                                placeholder="Brief summary of your feedback" 
                                className="h-11 px-4 rounded-xl bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-800 focus:bg-white transition-colors"
                                required
                            />
                            {errors.title && <span className="text-red-500 text-xs">{errors.title}</span>}
                        </div>

                        <div className="space-y-2">
                            <label className="text-sm font-semibold text-gray-900 dark:text-gray-100">Description</label>
                            <Textarea 
                                value={data.description}
                                onChange={e => setData('description', e.target.value)}
                                placeholder="Provide more details..." 
                                className="min-h-[120px] p-4 rounded-xl bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-800 resize-none focus:bg-white transition-colors"
                                required
                            />
                            <div className="flex justify-between items-center text-xs text-gray-400">
                                <span>Minimum 10 characters, maximum 5000 characters</span>
                                {errors.description && <span className="text-red-500">{errors.description}</span>}
                            </div>
                        </div>

                        <div className="space-y-2">
                            <div className="flex justify-between items-center">
                                <label className="text-sm font-semibold text-gray-900 dark:text-gray-100">Screenshots</label>
                                <span className="text-xs text-gray-400">5 left</span>
                            </div>
                            <div className="border-2 border-dashed border-gray-200 dark:border-gray-800 rounded-xl p-8 flex flex-col items-center justify-center text-center gap-3 bg-gray-50/50 dark:bg-gray-900/50 hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors cursor-pointer group/upload">
                                <div className="h-10 w-10 text-gray-400 group-hover/upload:text-gray-600 dark:group-hover/upload:text-gray-300 transition-colors">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" className="w-full h-full">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                        <polyline points="17 8 12 3 7 8" />
                                        <line x1="12" y1="3" x2="12" y2="15" />
                                    </svg>
                                </div>
                                <div className="space-y-1">
                                    <p className="text-sm font-semibold text-gray-900 dark:text-white">Click to upload or drop images</p>
                                    <p className="text-xs text-gray-500">PNG, JPG, GIF up to 10MB each. Paste works too!</p>
                                </div>
                                <div className="flex items-center gap-1.5 text-xs text-gray-400 mt-2">
                                    <span className="inline-flex items-center justify-center h-5 w-5 rounded border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 font-medium">⌘</span>
                                    <span>Press Ctrl/Cmd+V to paste a screenshot</span>
                                </div>
                            </div>
                        </div>

                        <div className="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                            <Button 
                                type="button"
                                variant="outline" 
                                onClick={() => setIsCreateModalOpen(false)} 
                                className="h-10 px-6 rounded-xl border-gray-200 dark:border-gray-800 font-semibold hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-200"
                            >
                                Cancel
                            </Button>
                            <Button 
                                type="submit"
                                disabled={processing}
                                className="h-10 px-6 bg-[#B47B15] hover:bg-[#96650F] text-white font-bold rounded-xl shadow-lg active:scale-95 transition-all"
                            >
                                {processing ? 'Submitting...' : 'Submit Feedback'}
                            </Button>
                        </div>
                    </form>
                </DialogContent>
            </Dialog>
        </AppLayout>
    );
}
