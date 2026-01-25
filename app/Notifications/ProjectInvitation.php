<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProjectInvitation extends Notification
{
    use Queueable;

    protected $project;
    protected $inviter;

    public function __construct(Project $project, $inviter)
    {
        $this->project = $project;
        $this->inviter = $inviter;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'message' => "{$this->inviter->name} invited you to join project: {$this->project->name}",
            'project_id' => $this->project->id,
            'action_url' => route('projects.show', $this->project->id),
            'type' => 'project_invitation'
        ];
    }
}
