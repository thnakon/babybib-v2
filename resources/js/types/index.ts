export type * from './auth';
export type * from './navigation';
export type * from './ui';

import type { Auth } from './auth';

export type SharedData = {
    name: string;
    auth: Auth;
    sidebarOpen: boolean;
    [key: string]: unknown;
};

export interface Reference {
    id: number;
    title: string;
    authors: string[];
    year?: string;
    type: string;
    journal_name?: string;
    doi?: string;
    formatted_citation?: string;
}
