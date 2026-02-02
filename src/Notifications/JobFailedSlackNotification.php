<?php
namespace Hdruk\LaravelJobFailedSlackNotification\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Queue\Events\JobFailed;

class JobFailedSlackNotification extends Notification
{
    public function __construct(protected JobFailed $event) {}

    public function via($notifiable)
    {
        return ['slack'];
    }

    public function toSlack($notifiable)
    {
        $job = $this->event->job;
        $env = strtoupper(config('app.env'));
        $name = config('job-failed-slack.app_name') ?? 'No App Name Configured';
        $jobId = $job->getJobId();
        $payload = $job->payload();
        $jobName = $payload['displayName'] ?? get_class($job);

        return (new SlackMessage)
            ->error()
            ->content("🚨 {$name} Queue Job Failed ({$env}) 🚨")
            ->attachment(function ($attachment) use ($job, $env, $jobName, $jobId) {
                $attachment->fields([
                    'Environment' => $env,
                    'Job' => $jobName,
                    'ID' => $jobId,
                    'Queue' => $job->getQueue(),
                    'Connection' => $this->event->connectionName,
                    'Exception' => $this->event->exception->getMessage(),
                ]);
            });
    }
}

