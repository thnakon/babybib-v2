import { Link, usePage, router } from '@inertiajs/react';
import { Bell, Inbox, Check, Trash2, Clock } from 'lucide-react';
import { useState } from 'react';
import { cn } from '@/lib/utils';
import { formatDistanceToNow } from 'date-fns';
import { th, enUS } from 'date-fns/locale';
import { useLanguage } from '@/contexts/language-context';

export function NotificationInbox() {
    const { auth } = usePage<any>().props;
    const { language } = useLanguage();
    const [isOpen, setIsOpen] = useState(false);

    const notifications = auth.notifications?.latest || [];
    const unreadCount = auth.notifications?.unread_count || 0;

    const markAsRead = (id: string) => {
        router.post(`/notifications/${id}/mark-as-read`, {}, { preserveScroll: true });
    };

    const markAllAsRead = () => {
        router.post('/notifications/mark-all-as-read', {}, { preserveScroll: true });
    };

    const deleteNotification = (id: string) => {
        router.delete(`/notifications/${id}`, { preserveScroll: true });
    };

    return (
        <div className="group relative">
            <button 
                onClick={() => setIsOpen(!isOpen)}
                className="relative flex h-9 w-9 items-center justify-center rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
            >
                <Bell className="h-4 w-4 text-gray-500" />
                {unreadCount > 0 && (
                    <span className="absolute -right-0.5 -top-0.5 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[8px] font-black text-white ring-2 ring-white dark:ring-gray-900 animate-in zoom-in-50 duration-300">
                        {unreadCount > 9 ? '9+' : unreadCount}
                    </span>
                )}
            </button>

            {isOpen && (
                <>
                    <div className="fixed inset-0 z-50" onClick={() => setIsOpen(false)} />
                    <div className="absolute right-0 top-full z-[60] mt-2 w-80 origin-top-right rounded-[1.5rem] border border-gray-100 bg-white shadow-2xl ring-1 ring-black ring-opacity-5 dark:border-gray-800 dark:bg-gray-900 animate-in fade-in slide-in-from-top-2 duration-200">
                        <div className="flex items-center justify-between border-b border-gray-50 p-4 dark:border-gray-800">
                            <h3 className="text-xs font-black uppercase tracking-widest text-scribehub-blue dark:text-white">
                                {language === 'en' ? 'Notifications' : 'การแจ้งเตือน'}
                            </h3>
                            {unreadCount > 0 && (
                                <button 
                                    onClick={markAllAsRead}
                                    className="text-[9px] font-bold text-gray-400 hover:text-scribehub-blue underline decoration-dotted"
                                >
                                    {language === 'en' ? 'Mark all as read' : 'อ่านทั้งหมดแล้ว'}
                                </button>
                            )}
                        </div>

                        <div className="max-h-[350px] overflow-y-auto">
                            {notifications.length === 0 ? (
                                <div className="flex flex-col items-center justify-center py-10 opacity-30">
                                    <Inbox className="h-10 w-10 mb-2" />
                                    <p className="text-[10px] font-bold">No new notifications</p>
                                </div>
                            ) : (
                                <div className="divide-y divide-gray-50 dark:divide-gray-800">
                                    {notifications.map((notification: any) => (
                                        <div 
                                            key={notification.id}
                                            className={cn(
                                                "group/item relative flex flex-col gap-1 p-4 transition-colors hover:bg-gray-50/50 dark:hover:bg-gray-800/30",
                                                !notification.read_at && "bg-blue-50/30 dark:bg-blue-900/10"
                                            )}
                                        >
                                            <div className="flex justify-between gap-2">
                                                <p className="text-[11px] font-bold text-gray-700 dark:text-gray-300">
                                                    {notification.data.message}
                                                </p>
                                                <div className="flex shrink-0 gap-1 opacity-0 group-hover/item:opacity-100 transition-opacity">
                                                    {!notification.read_at && (
                                                        <button onClick={() => markAsRead(notification.id)} className="text-blue-500 hover:scale-110 transition-transform">
                                                            <Check className="h-3 w-3" />
                                                        </button>
                                                    )}
                                                    <button onClick={() => deleteNotification(notification.id)} className="text-red-400 hover:scale-110 transition-transform">
                                                        <Trash2 className="h-3 w-3" />
                                                    </button>
                                                </div>
                                            </div>
                                            <div className="flex items-center gap-1.5 text-[9px] font-medium text-gray-400">
                                                <Clock className="h-2.5 w-2.5" />
                                                {formatDistanceToNow(new Date(notification.created_at), { 
                                                    addSuffix: true,
                                                    locale: language === 'th' ? th : enUS 
                                                })}
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            )}
                        </div>

                        <div className="border-t border-gray-50 p-3 dark:border-gray-800">
                            <Link 
                                href="/notifications" 
                                onClick={() => setIsOpen(false)}
                                className="flex w-full items-center justify-center rounded-xl bg-gray-50 py-2.5 text-[10px] font-black uppercase tracking-widest text-scribehub-blue transition-all hover:bg-scribehub-blue hover:text-white dark:bg-gray-800"
                            >
                                {language === 'en' ? 'View All' : 'ดูทั้งหมด'}
                            </Link>
                        </div>
                    </div>
                </>
            )}
        </div>
    );
}
