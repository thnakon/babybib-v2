import InputError from '@/components/input-error';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { home, register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';
import { Form, Head, Link } from '@inertiajs/react';
import { useState } from 'react';
import { useAppearance } from '@/hooks/use-appearance';
import { cn } from '@/lib/utils';
import { 
    Languages, Moon, Sun, Monitor, Check, 
    ChevronLeft, Eye, EyeOff, Facebook, 
    HelpCircle, Mail, Key
} from 'lucide-react';
import AppLogoIcon from '@/components/app-logo-icon';

type Props = {
    status?: string;
    canResetPassword: boolean;
    canRegister: boolean;
};

const translations = {
    en: {
        back: "Back",
        welcomeBack: "Welcome back",
        subtitle: "Enter your email to sign in to your account",
        passwordTab: "Password",
        magicLinkTab: "Magic Link",
        emailLabel: "Email",
        passwordLabel: "Password",
        forgotPassword: "Forgot password?",
        signIn: "Sign In",
        orContinueWith: "OR CONTINUE WITH",
        google: "Google",
        facebook: "Facebook",
        dontHaveAccount: "Don't have an account?",
        signUp: "Sign up",
        policyPrefix: "By continuing, you agree to our ",
        terms: "Terms of Service",
        and: " and ",
        privacy: "Privacy Policy"
    },
    th: {
        back: "กลับ",
        welcomeBack: "ยินดีต้อนรับกลับมา",
        subtitle: "กรอกอีเมลเพื่อเข้าสู่ระบบงานของคุณ",
        passwordTab: "รหัสผ่าน",
        magicLinkTab: "Magic Link",
        emailLabel: "อีเมล",
        passwordLabel: "รหัสผ่าน",
        forgotPassword: "ลืมรหัสผ่าน?",
        signIn: "เข้าสู่ระบบ",
        orContinueWith: "หรือดำเนินการด้วย",
        google: "Google",
        facebook: "Facebook",
        dontHaveAccount: "ยังไม่มีบัญชี?",
        signUp: "สมัครสมาชิก",
        policyPrefix: "การดำเนินการต่อแสดงว่าคุณยอมรับ ",
        terms: "เงื่อนไขการให้บริการ",
        and: " และ ",
        privacy: "นโยบายความเป็นส่วนตัว"
    }
};

export default function Login({
    status,
    canResetPassword,
    canRegister,
}: Props) {
    const [lang, setLang] = useState<'en' | 'th'>('en');
    const { appearance, updateAppearance } = useAppearance();
    const [loginMode, setLoginMode] = useState<'password' | 'magic'>('password');
    const [showPassword, setShowPassword] = useState(false);
    
    const t = translations[lang];

    return (
        <div className="min-h-screen bg-scribehub-paper font-overpass text-scribehub-grey antialiased dark:bg-[#0a0a0a] dark:text-gray-300 flex flex-col items-center justify-center p-6 sm:p-10 relative">
            <Head title={t.signIn}>
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link href="https://fonts.bunny.net/css?family=overpass:400,600,700,800&display=swap" rel="stylesheet" />
            </Head>

            {/* Top Navigation */}
            <div className="absolute top-6 left-6 sm:top-10 sm:left-10">
                <Link href={home()} className="flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white/50 border border-gray-100 hover:bg-white transition-all text-sm font-bold shadow-sm dark:bg-gray-900/50 dark:border-gray-800 dark:hover:bg-gray-900">
                    <ChevronLeft className="h-4 w-4" /> {t.back}
                </Link>
            </div>

            <div className="absolute top-6 right-6 sm:top-10 sm:right-10 flex items-center gap-2">
                <div className="group relative">
                    <button className="flex h-10 w-10 items-center justify-center rounded-xl bg-white border border-gray-100 text-scribehub-grey hover:bg-gray-50 dark:bg-gray-900 dark:border-gray-800 dark:text-gray-400 transition-all shadow-sm"><Languages className="h-5 w-5" /></button>
                    <div className="absolute right-0 top-full hidden w-32 origin-top-right rounded-xl border border-gray-100 bg-white py-2 shadow-xl ring-1 ring-black ring-opacity-5 group-hover:block dark:border-gray-800 dark:bg-gray-900 z-[60]">
                        <button onClick={() => setLang('en')} className={cn("flex w-full items-center justify-between px-4 py-2 text-xs font-bold", lang === 'en' ? "bg-gray-50 text-scribehub-blue dark:bg-gray-800" : "text-gray-700 dark:text-gray-300")}>English {lang === 'en' && <Check className="h-3 w-3" />}</button>
                        <button onClick={() => setLang('th')} className={cn("flex w-full items-center justify-between px-4 py-2 text-xs font-bold", lang === 'th' ? "bg-gray-50 text-scribehub-blue dark:bg-gray-800" : "text-gray-700 dark:text-gray-300")}>ภาษาไทย {lang === 'th' && <Check className="h-3 w-3" />}</button>
                    </div>
                </div>
                <div className="group relative">
                    <button className="flex h-10 w-10 items-center justify-center rounded-xl bg-white border border-gray-100 text-scribehub-grey hover:bg-gray-50 dark:bg-gray-900 dark:border-gray-800 dark:text-gray-400 transition-all shadow-sm">{appearance === 'dark' ? <Moon className="h-5 w-5" /> : appearance === 'light' ? <Sun className="h-5 w-5" /> : <Monitor className="h-5 w-5" />}</button>
                    <div className="absolute right-0 top-full hidden w-32 origin-top-right rounded-xl border border-gray-100 bg-white py-2 shadow-xl ring-1 ring-black ring-opacity-5 group-hover:block dark:border-gray-800 dark:bg-gray-900 z-[60]">
                        <button onClick={() => updateAppearance('light')} className={cn("flex w-full items-center justify-between px-4 py-2 text-xs font-bold", appearance === 'light' ? "bg-gray-50 text-scribehub-blue dark:bg-gray-800" : "text-gray-700 dark:text-gray-300")}>Light {appearance === 'light' && <Check className="h-3 w-3" />}</button>
                        <button onClick={() => updateAppearance('dark')} className={cn("flex w-full items-center justify-between px-4 py-2 text-xs font-bold", appearance === 'dark' ? "bg-gray-50 text-scribehub-blue dark:bg-gray-800" : "text-gray-700 dark:text-gray-300")}>Dark {appearance === 'dark' && <Check className="h-3 w-3" />}</button>
                        <button onClick={() => updateAppearance('system')} className={cn("flex w-full items-center justify-between px-4 py-2 text-xs font-bold", appearance === 'system' ? "bg-gray-50 text-scribehub-blue dark:bg-gray-800" : "text-gray-700 dark:text-gray-300")}>System {appearance === 'system' && <Check className="h-3 w-3" />}</button>
                    </div>
                </div>
            </div>

            {/* Login Card */}
            <div className="w-full max-w-sm animate-in fade-in slide-in-from-bottom-10 duration-1000">
                <div className="flex flex-col items-center mb-6">
                    <div className="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-blue-50 dark:bg-blue-900/20 p-2 border-4 border-white shadow-xl dark:border-gray-900 overflow-hidden">
                         <AppLogoIcon className="h-8 w-8 object-contain" />
                    </div>
                    <h1 className="text-xl font-extrabold text-scribehub-blue dark:text-white mb-1">{t.welcomeBack}</h1>
                    <p className="text-[11px] font-medium text-gray-500 dark:text-gray-400 text-center">{t.subtitle}</p>
                </div>

                <div className="space-y-4">
                    {/* Mode Tabs */}
                    <div className="flex p-1 bg-gray-100/50 rounded-xl dark:bg-gray-900 gap-1">
                        <button 
                            onClick={() => setLoginMode('password')}
                            className={cn("flex-1 py-2 text-[10px] font-bold rounded-lg transition-all", loginMode === 'password' ? "bg-white shadow-sm text-scribehub-blue dark:bg-gray-800" : "text-gray-400 hover:text-gray-600")}
                        >
                            {t.passwordTab}
                        </button>
                        <button 
                            onClick={() => setLoginMode('magic')}
                            className={cn("flex-1 py-2 text-[10px] font-bold rounded-lg transition-all", loginMode === 'magic' ? "bg-white shadow-sm text-scribehub-blue dark:bg-gray-800" : "text-gray-400 hover:text-gray-600")}
                        >
                            {t.magicLinkTab}
                        </button>
                    </div>

                    <Form {...store.form()} resetOnSuccess={['password']} className="space-y-4">
                        {({ processing, errors }) => (
                            <div className="grid gap-4">
                                <div className="grid gap-1.5">
                                    <Label htmlFor="email" className="text-[11px] font-bold text-gray-700 dark:text-gray-300 ml-1">{t.emailLabel}</Label>
                                    <div className="relative">
                                        <Mail className="absolute left-3 top-1/2 -translate-y-1/2 h-3.5 w-3.5 text-gray-400" />
                                        <Input
                                            id="email"
                                            type="email"
                                            name="email"
                                            required
                                            autoFocus
                                            className="h-10 pl-10 rounded-xl border-gray-100 bg-white/50 text-xs focus:ring-scribehub-blue dark:bg-gray-900/50 dark:border-gray-800"
                                            placeholder="name@example.com"
                                        />
                                    </div>
                                    <InputError message={errors.email} />
                                </div>

                                {loginMode === 'password' && (
                                    <div className="grid gap-1.5">
                                        <div className="flex items-center justify-between ml-1">
                                            <Label htmlFor="password" className="text-[11px] font-bold text-gray-700 dark:text-gray-300">{t.passwordLabel}</Label>
                                            {canResetPassword && (
                                                <TextLink href={request()} className="text-[10px] font-bold text-gray-400 hover:text-scribehub-blue">{t.forgotPassword}</TextLink>
                                            )}
                                        </div>
                                        <div className="relative">
                                            <Key className="absolute left-3 top-1/2 -translate-y-1/2 h-3.5 w-3.5 text-gray-400" />
                                            <Input
                                                id="password"
                                                type={showPassword ? "text" : "password"}
                                                name="password"
                                                required
                                                className="h-10 px-10 rounded-xl border-gray-100 bg-white/50 text-xs focus:ring-scribehub-blue dark:bg-gray-900/50 dark:border-gray-800"
                                                placeholder="••••••••"
                                            />
                                            <button 
                                                type="button" 
                                                onClick={() => setShowPassword(!showPassword)}
                                                className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-scribehub-blue transition-colors"
                                            >
                                                {showPassword ? <EyeOff className="h-3.5 w-3.5" /> : <Eye className="h-3.5 w-3.5" />}
                                            </button>
                                        </div>
                                        <InputError message={errors.password} />
                                    </div>
                                )}

                                <Button
                                    type="submit"
                                    className="h-10 w-full rounded-xl bg-scribehub-blue text-xs font-bold text-white shadow-lg shadow-blue-900/20 hover:bg-blue-700 active:scale-[0.98] transition-all"
                                    disabled={processing}
                                >
                                    {processing && <Spinner className="mr-2 h-3 w-3" />}
                                    {t.signIn}
                                </Button>
                            </div>
                        )}
                    </Form>

                    <div className="relative">
                        <div className="absolute inset-0 flex items-center"><span className="w-full border-t border-gray-100 dark:border-gray-800"></span></div>
                        <div className="relative flex justify-center text-[9px] font-bold uppercase tracking-widest"><span className="bg-scribehub-paper px-4 text-gray-400 dark:bg-[#0a0a0a]">{t.orContinueWith}</span></div>
                    </div>

                    <div className="grid grid-cols-1 gap-2.5">
                        <button className="flex h-10 w-full items-center justify-center gap-2.5 rounded-xl border border-gray-100 bg-white px-4 text-xs font-bold text-gray-700 transition-all hover:bg-gray-50 active:scale-[0.98] dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                            <svg className="h-4 w-4" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" />
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                            </svg>
                            {t.google}
                        </button>
                        <button className="flex h-10 w-full items-center justify-center gap-2.5 rounded-xl border border-gray-100 bg-[#1877F2] px-4 text-xs font-bold text-white transition-all hover:bg-[#166fe5] active:scale-[0.98] shadow-md shadow-blue-600/10">
                            <Facebook className="h-4 w-4 fill-current" />
                            {t.facebook}
                        </button>
                    </div>

                    <div className="text-center text-[13px] font-medium text-gray-500">
                        {t.dontHaveAccount}{' '}
                        <Link href={register()} className="font-bold text-scribehub-blue hover:underline">
                            {t.signUp}
                        </Link>
                    </div>

                    <div className="mt-6 text-center text-[9px] leading-relaxed text-gray-400 font-medium px-4">
                        {t.policyPrefix}
                        <Link href="#" className="underline hover:text-scribehub-blue">{t.terms}</Link>
                        {t.and}
                        <Link href="#" className="underline hover:text-scribehub-blue">{t.privacy}</Link>
                    </div>
                </div>
            </div>

            {status && (
                <div className="mt-8 text-center text-sm font-bold text-emerald-600 animate-bounce">
                    {status}
                </div>
            )}
        </div>
    );
}

