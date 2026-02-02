<?php

namespace Hdruk\LaravelJobFailedSlackNotification\Listeners;

use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\Notification;
use Hdruk\LaravelJobFailedSlackNotification\Notifications\JobFailedSlackNotification;

class NotifySlackOfFailedJob
{
    public function handle(JobFailed $event)
    {
        Notification::route('slack', config('job-failed-slack.webhook_url'))
                    ->notify(new JobFailedSlackNotification($event));
    }
}
