<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Reference;
use App\Models\Manuscript;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. Quick Stats
        $stats = [
            'references_count' => Reference::where('user_id', $user->id)->count(),
            'projects_count' => Project::where('user_id', $user->id)
                ->orWhereHas('members', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })->count(),
            'manuscripts_count' => Manuscript::where('user_id', $user->id)->count(),
            'notifications_count' => $user->unreadNotifications()->count(),
        ];

        // 2. Recent Projects (with members)
        $recentProjects = Project::with(['members.user'])
            ->where('user_id', $user->id)
            ->orWhereHas('members', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderBy('updated_at', 'desc')
            ->take(4)
            ->get();

        // 3. Recent Activity (Mocking from notifications for now, or could use a dedicated Activity table if exists)
        $activities = $user->notifications()
            ->take(5)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'text' => $notification->data['message'] ?? 'New notification',
                    'time' => $notification->created_at->diffForHumans(),
                    'type' => $notification->type,
                ];
            });

        return Inertia::render('dashboard', [
            'stats' => $stats,
            'recentProjects' => $recentProjects,
            'activities' => $activities,
        ]);
    }
}
