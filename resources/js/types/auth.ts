export type User = {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    two_factor_enabled?: boolean;
    avatar_path?: string;
    institution?: string;
    academic_title?: string;
    default_citation_style?: string;
    ai_language?: 'th' | 'en';
    default_ai_tone?: 'academic' | 'simple' | 'professional';
    theme_preference?: 'light' | 'dark' | 'system';
    created_at: string;
    updated_at: string;
    [key: string]: unknown;
};

export type Auth = {
    user: User;
};

export type TwoFactorSetupData = {
    svg: string;
    url: string;
};

export type TwoFactorSecretKey = {
    secretKey: string;
};
