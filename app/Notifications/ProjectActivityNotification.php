<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProjectActivityNotification extends Notification
{
    use Queueable;

    protected $project;
    protected $user;
    protected $message;
    protected $type;

    public function __construct(Project $project, $user, $message, $type = 'activity')
    {
        $this->project = $project;
        $this->user = $user;
        $this->message = $message;
        $this->type = $type;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'message' => $this->message,
            'user_name' => $this->user->name,
            'user_id' => $this->user->id,
            'project_id' => $this->project->id,
            'project_name' => $this->project->name,
            'action_url' => route('projects.show', $this->project->id),
            'type' => $this->type
        ];
    }
}
