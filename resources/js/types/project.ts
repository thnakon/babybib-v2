export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    avatar_path?: string;
}

export interface Reference {
    id: number;
    title: string;
    authors: string[] | null;
    type: string;
    year: string | null;
}

export interface TaskChecklist {
    id: number;
    task_id: number;
    title: string;
    is_completed: boolean;
    position: number;
    assigned_to?: number;
    created_at: string;
    updated_at: string;
}

export interface TaskActivity {
    id: number;
    task_id: number;
    user_id: number;
    user: User;
    activity_type: string;
    details: any;
    created_at: string;
}

export interface ProjectFile {
    id: number;
    name: string;
    path: string;
    size: number;
    extension: string;
    user: User;
    created_at: string;
    task_id?: number;
}

export interface Comment {
    id: number;
    user: User;
    content: string;
    attachments: { name: string, path: string, extension: string, size: number }[] | null;
    created_at: string;
}

export interface Task {
    id: number;
    project_id: number;
    title: string;
    description: string | null;
    status: string; // todo, in_progress, review, done
    priority: string; // low, medium, high, urgent
    due_date: string | null;
    position: number;
    assigned_to: number | null;
    assignee: User | null;
    checklists?: TaskChecklist[];
    activities?: TaskActivity[];
    attachments?: ProjectFile[];
    comments?: Comment[];
}

export interface Member {
    id: number;
    user: User;
    role: string; // owner, manager, contributor, viewer
    status: string; // pending, accepted
}

export interface Project {
    id: number;
    name: string;
    description: string | null;
    sort_order: number;
    color: string | null;
    icon: string | null;
    status: string;
    priority: string;
    due_date: string | null;
    visibility: string;
    progress: number;
    user_id: number;
    created_at: string;
    updated_at: string;
    references?: Reference[];
    tasks: Task[];
    members: Member[];
    files: ProjectFile[];
    comments: Comment[];
    user?: User;
}
