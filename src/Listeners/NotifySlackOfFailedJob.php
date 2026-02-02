<?php

namespace App\Listeners;

use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\Notification;
use App\Notifications\JobFailedSlackNotification;

class NotifySlackOfFailedJob
{
    public function handle(JobFailed $event)
    {
        Notification::route('slack', config('services.slack.webhook_url'))
            ->notify(new JobFailedSlackNotification($event));
    }
}