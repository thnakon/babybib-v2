import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/react';
import { 
    Inbox, Check, Trash2, Clock, Bell, Archive,
    MoreHorizontal, Filter
} from 'lucide-react';
import { useLanguage } from '@/contexts/language-context';
import { cn } from '@/lib/utils';
import { formatDistanceToNow } from 'date-fns';
import { th, enUS } from 'date-fns/locale';

interface Notification {
    id: string;
    data: {
        message: string;
        action_url?: string;
        type?: string;
    };
    read_at: string | null;
    created_at: string;
}

interface Props {
    notifications: {
        data: Notification[];
        links: any[];
    };
}

export default function NotificationsIndex({ notifications }: Props) {
    const { language } = useLanguage();
    
    const breadcrumbs: BreadcrumbItem[] = [
        { title: language === 'en' ? 'Inbox' : 'กล่องจดหมาย', href: '/notifications' }
    ];

    const markAsRead = (id: string) => {
        router.post(`/notifications/${id}/mark-as-read`, {}, { preserveScroll: true });
    };

    const markAllAsRead = () => {
        router.post('/notifications/mark-all-as-read', {}, { preserveScroll: true });
    };

    const deleteNotification = (id: string) => {
        if (confirm(language === 'en' ? 'Delete this notification?' : 'ต้องการลบการแจ้งเตือนนี้หรือไม่?')) {
            router.delete(`/notifications/${id}`, { preserveScroll: true });
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={language === 'en' ? 'Inbox' : 'กล่องจดหมาย'} />
            
            <div className="mx-auto max-w-5xl p-6">
                <div className="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 className="text-3xl font-black text-scribehub-blue dark:text-white">
                            {language === 'en' ? 'Notification Center' : 'ศูนย์การแจ้งเตือน'}
                        </h1>
                        <p className="mt-1 text-sm text-gray-400 font-bold uppercase tracking-widest">
                            {language === 'en' ? 'Stay updated with your latest activities' : 'ติดตามทุกความเคลื่อนไหวล่าสุดของคุณ'}
                        </p>
                    </div>
                    <div className="flex gap-2">
                        <button 
                            onClick={markAllAsRead}
                            className="flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-[10px] font-black uppercase tracking-widest text-scribehub-blue shadow-sm transition-all hover:bg-gray-50 active:scale-95 dark:bg-gray-900 border border-gray-100 dark:border-gray-800"
                        >
                            <Check className="h-3.5 w-3.5" />
                            {language === 'en' ? 'Read All' : 'อ่านทั้งหมด'}
                        </button>
                    </div>
                </div>

                <div className="rounded-[2rem] bg-white shadow-xl shadow-blue-900/5 dark:bg-gray-900/50 overflow-hidden border border-white dark:border-gray-800">
                    {notifications.data.length === 0 ? (
                        <div className="flex flex-col items-center justify-center py-32 text-gray-400">
                            <div className="relative mb-6">
                                <Inbox className="h-24 w-24 opacity-5" />
                                <Bell className="absolute -right-2 -top-2 h-10 w-10 text-scribehub-blue/20" />
                            </div>
                            <h3 className="text-lg font-black text-scribehub-blue dark:text-white mb-1">
                                {language === 'en' ? 'Clear as crystal!' : 'ยังไม่มีการแจ้งเตือน'}
                            </h3>
                            <p className="text-xs font-bold uppercase opacity-50">
                                {language === 'en' ? 'We\'ll let you know when something happens' : 'เราจะแจ้งให้คุณทราบหากมีความเคลื่อนไหว'}
                            </p>
                        </div>
                    ) : (
                        <div className="divide-y divide-gray-50 dark:divide-gray-800">
                            {notifications.data.map((notification) => (
                                <div 
                                    key={notification.id}
                                    className={cn(
                                        "group flex items-start gap-4 p-6 transition-all hover:bg-gray-50/50 dark:hover:bg-gray-800/30",
                                        !notification.read_at && "bg-blue-50/20 dark:bg-blue-900/10 border-l-4 border-scribehub-blue"
                                    )}
                                >
                                    <div className={cn(
                                        "flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl shadow-sm",
                                        !notification.read_at ? "bg-scribehub-blue text-white" : "bg-gray-100 text-gray-400 dark:bg-gray-800"
                                    )}>
                                        <Bell className="h-5 w-5" />
                                    </div>
                                    <div className="flex-1 min-w-0">
                                        <div className="flex items-center justify-between gap-4">
                                            <p className={cn(
                                                "text-sm font-bold leading-relaxed",
                                                !notification.read_at ? "text-scribehub-blue dark:text-white" : "text-gray-600 dark:text-gray-400"
                                            )}>
                                                {notification.data.message}
                                            </p>
                                            <div className="flex shrink-0 items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                {!notification.read_at && (
                                                    <button 
                                                        onClick={() => markAsRead(notification.id)}
                                                        className="h-10 w-10 flex items-center justify-center rounded-xl bg-blue-50 text-blue-500 hover:bg-blue-100 dark:bg-blue-900/20"
                                                    >
                                                        <Check className="h-4 w-4" />
                                                    </button>
                                                )}
                                                <button 
                                                    onClick={() => deleteNotification(notification.id)}
                                                    className="h-10 w-10 flex items-center justify-center rounded-xl bg-red-50 text-red-500 hover:bg-red-100 dark:bg-red-900/20"
                                                >
                                                    <Trash2 className="h-4 w-4" />
                                                </button>
                                            </div>
                                        </div>
                                        <div className="mt-2 flex items-center gap-4">
                                            <div className="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                                <Clock className="h-3 w-3" />
                                                {formatDistanceToNow(new Date(notification.created_at), { 
                                                    addSuffix: true,
                                                    locale: language === 'th' ? th : enUS 
                                                })}
                                            </div>
                                            {notification.data.action_url && (
                                                <button 
                                                    onClick={() => router.visit(notification.data.action_url!)}
                                                    className="text-[10px] font-black text-scribehub-blue underline decoration-dotted"
                                                >
                                                    {language === 'en' ? 'View Details' : 'ดูรายละเอียด'}
                                                </button>
                                            )}
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    )}
                </div>
            </div>
        </AppLayout>
    );
}
