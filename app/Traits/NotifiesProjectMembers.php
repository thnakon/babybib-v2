<?php

namespace App\Traits;

use App\Models\Project;
use App\Models\User;
use App\Notifications\ProjectActivityNotification;

trait NotifiesProjectMembers
{
    /**
     * Notify all members of a project about an activity.
     */
    protected function notifyMembers(Project $project, User $actor, string $message, string $type)
    {
        $project->members()
            ->with('user')
            ->get()
            ->each(function ($member) use ($actor, $message, $project, $type) {
                if ($member->user && $member->user->id !== $actor->id) {
                    $member->user->notify(new ProjectActivityNotification($project, $actor, $message, $type));
                }
            });
    }
}
