import ProfileController from '@/actions/App/Http/Controllers/ProfileController';
import DeleteUser from '@/components/delete-user';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/app-layout';
import SettingsLayout from '@/layouts/settings/layout';
import { edit } from '@/routes/profile';
import { send } from '@/routes/verification';
import type { BreadcrumbItem, SharedData } from '@/types';
import { Form, Head, Link, usePage } from '@inertiajs/react';

import { toast } from 'sonner';
import { useEffect, useState } from 'react';

const SuccessToast = ({ show }: { show: boolean }) => {
    useEffect(() => {
        if (show) {
            toast.success('Profile updated successfully');
        }
    }, [show]);
    return null;
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Profile settings',
        href: edit().url,
    },
];

export default function Profile({
    mustVerifyEmail,
    status,
}: {
    mustVerifyEmail: boolean;
    status?: string;
}) {
    const { auth } = usePage<SharedData>().props;
    const [avatarPreview, setAvatarPreview] = useState<string | null>(auth.user.avatar_path || null);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Profile settings" />

            <h1 className="sr-only">Profile Settings</h1>

            <SettingsLayout>
                <div className="space-y-6">
                    <Heading
                        variant="small"
                        title="Profile information"
                        description="Update your name and email address"
                    />

                    <Form
                        {...ProfileController.update.form()}
                        options={{
                            preserveScroll: true,
                        }}
                        className="space-y-6"
                        encType="multipart/form-data"
                    >
                        {({ processing, recentlySuccessful, errors }) => {
                             return (
                            <>
                                    <SuccessToast show={recentlySuccessful} />
                                    {/* Avatar Section */}
                                    <div className="flex items-center gap-6 mb-6">
                                        <div className="relative group">
                                            <div className="h-24 w-24 rounded-full overflow-hidden border-4 border-white dark:border-gray-800 shadow-lg bg-gray-100 dark:bg-gray-700">
                                                {avatarPreview ? (
                                                    <img 
                                                        src={avatarPreview} 
                                                        alt="Profile" 
                                                        className="h-full w-full object-cover" 
                                                    />
                                                ) : (
                                                    <img 
                                                        src={`https://ui-avatars.com/api/?name=${encodeURIComponent(auth.user.name)}&color=7F9CF5&background=EBF4FF`} 
                                                        alt="Profile" 
                                                        className="h-full w-full object-cover" 
                                                    />
                                                )}
                                            </div>
                                            <div className="absolute bottom-0 right-0">
                                                <Label htmlFor="avatar" className="cursor-pointer">
                                                    <div className="h-8 w-8 bg-black dark:bg-white rounded-full flex items-center justify-center shadow-md hover:scale-110 transition-transform">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" className="w-4 h-4 text-white dark:text-black">
                                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                            <polyline points="17 8 12 3 7 8" />
                                                            <line x1="12" y1="3" x2="12" y2="15" />
                                                        </svg>
                                                    </div>
                                                </Label>
                                                <input 
                                                    type="file" 
                                                    id="avatar" 
                                                    name="avatar" 
                                                    className="hidden" 
                                                    accept="image/*"
                                                    onChange={(e) => {
                                                        const file = e.target.files?.[0];
                                                        if (file) {
                                                            const reader = new FileReader();
                                                            reader.onloadend = () => {
                                                                setAvatarPreview(reader.result as string);
                                                            };
                                                            reader.readAsDataURL(file);
                                                        }
                                                    }}
                                                />
                                            </div>
                                        </div>
                                        <div className="space-y-1">
                                            <p className="font-semibold text-lg">{auth.user.name}</p>
                                            <p className="text-sm text-gray-500">{auth.user.email}</p>
                                        </div>
                                    </div>

                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div className="grid gap-2">
                                            <Label htmlFor="name">Full Name</Label>
                                            <Input
                                                id="name"
                                                className="mt-1 block w-full"
                                                defaultValue={auth.user.name}
                                                name="name"
                                                required
                                                autoComplete="name"
                                                placeholder="Full name"
                                            />
                                            <InputError className="mt-2" message={errors.name} />
                                        </div>

                                        <div className="grid gap-2">
                                            <Label htmlFor="academic_title">Academic Title</Label>
                                            <Input
                                                id="academic_title"
                                                className="mt-1 block w-full"
                                                defaultValue={auth.user.academic_title}
                                                name="academic_title"
                                                placeholder="e.g. Dr., Prof., Assoc. Prof."
                                            />
                                            <InputError className="mt-2" message={errors.academic_title} />
                                        </div>
                                    </div>

                                    <div className="grid gap-2">
                                        <Label htmlFor="institution">Institution / University</Label>
                                        <Input
                                            id="institution"
                                            className="mt-1 block w-full"
                                            defaultValue={auth.user.institution}
                                            name="institution"
                                            placeholder="Institution Name"
                                        />
                                        <InputError className="mt-2" message={errors.institution} />
                                    </div>

                                <div className="grid gap-2">
                                    <Label htmlFor="email">Email address</Label>
                                    <Input
                                        id="email"
                                        type="email"
                                        className="mt-1 block w-full"
                                        defaultValue={auth.user.email}
                                        name="email"
                                        required
                                        autoComplete="username"
                                        placeholder="Email address"
                                    />
                                    <InputError className="mt-2" message={errors.email} />
                                </div>

                                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div className="grid gap-2">
                                        <Label htmlFor="default_citation_style">Default Citation Style</Label>
                                        <select
                                            id="default_citation_style"
                                            name="default_citation_style"
                                            className="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                            defaultValue={auth.user.default_citation_style || 'apa7'}
                                        >
                                            <option value="apa7">APA 7th Edition</option>
                                            <option value="mla9">MLA 9th Edition</option>
                                            <option value="chicago">Chicago Manual of Style</option>
                                            <option value="harvard">Harvard</option>
                                            <option value="ieee">IEEE</option>
                                        </select>
                                    </div>

                                    <div className="grid gap-2">
                                        <Label htmlFor="theme_preference">Theme Preference</Label>
                                        <select
                                            id="theme_preference"
                                            name="theme_preference"
                                            className="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                            defaultValue={auth.user.theme_preference || 'system'}
                                        >
                                            <option value="light">Light</option>
                                            <option value="dark">Dark</option>
                                            <option value="system">System</option>
                                        </select>
                                    </div>

                                    <div className="grid gap-2">
                                        <Label htmlFor="ai_language">Preferred AI Language</Label>
                                        <select
                                            id="ai_language"
                                            name="ai_language"
                                            className="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                            defaultValue={auth.user.ai_language || 'th'}
                                        >
                                            <option value="th">Thai (ภาษาไทย)</option>
                                            <option value="en">English (ภาษาอังกฤษ)</option>
                                        </select>
                                    </div>

                                    <div className="grid gap-2">
                                        <Label htmlFor="default_ai_tone">Default AI Tone</Label>
                                        <select
                                            id="default_ai_tone"
                                            name="default_ai_tone"
                                            className="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                            defaultValue={auth.user.default_ai_tone || 'academic'}
                                        >
                                            <option value="academic">Academic (วิชาการ)</option>
                                            <option value="professional">Professional (ทางการ)</option>
                                            <option value="simple">Simple (เข้าใจง่าย)</option>
                                            <option value="creative">Creative (สร้างสรรค์)</option>
                                        </select>
                                    </div>
                                </div>

                                {mustVerifyEmail &&
                                    auth.user.email_verified_at === null && (
                                        <div>
                                            <p className="-mt-4 text-sm text-muted-foreground">
                                                Your email address is
                                                unverified.{' '}
                                                <Link
                                                    href={send()}
                                                    as="button"
                                                    className="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500"
                                                >
                                                    Click here to resend the
                                                    verification email.
                                                </Link>
                                            </p>

                                            {status ===
                                                'verification-link-sent' && (
                                                <div className="mt-2 text-sm font-medium text-green-600">
                                                    A new verification link has
                                                    been sent to your email
                                                    address.
                                                </div>
                                            )}
                                        </div>
                                    )}

                                <div className="flex items-center gap-4">
                                    <Button
                                        disabled={processing}
                                        data-test="update-profile-button"
                                    >
                                        Save Changes
                                    </Button>
                                </div>
                            </>
                            );
                        }}
                    </Form>
                </div>

                <DeleteUser />
            </SettingsLayout>
        </AppLayout>
    );
}
