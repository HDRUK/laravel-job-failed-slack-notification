<?php

namespace YourVendor\JobFailedSlack;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Queue\Events\JobFailed;
use YourVendor\JobFailedSlack\Listeners\NotifySlackOfFailedJob;

class JobFailedSlackServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/job-failed-slack.php' => config_path('job-failed-slack.php'),
        ], 'config');

        Event::listen(JobFailed::class, NotifySlackOfFailedJob::class);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/job-failed-slack.php',
            'job-failed-slack'
        );
    }
}
