# Laravel Job Failed Slack

**Automatically sends Slack notifications whenever a Laravel queued job fails.**  
Keep your team informed of failed jobs with detailed messages including job name, queue, connection, and exception details.

---

## Installation

Install via Composer:

```bash
composer require hdr/laravel-job-failed-slack
```
If your Laravel version doesn’t support auto-discovery, register the service provider manually in config/app.php:


```php
Hdruk\LaravelJobFailedSlack\Providers\JobFailedSlackServiceProvider::class,
```

## Configuration

Publish the configuration file:


```php
Hdruk\La
php artisan vendor:publish --provider="Hdruk\LaravelJobFailedSlack\Providers\JobFailedSlackServiceProvider" --tag=config
```
This will create config/job-failed-slack.php. Set your Slack webhook URL in your .env file:
```env
SLACK_FAILED_JOB_WEBHOOK_URL=https://hooks.slack.com/services/your/webhook/url
```
Optionally, set a custom app name:
```env
APP_NAME="My Laravel App"
```
## How It Works
 * The package listens automatically to Laravel’s JobFailed events.

* Whenever a queued job fails, a Slack notification is sent with:
  * Job class name
  * Queue name
  * Connection name
  * Job ID
  * Exception message
  * Current environment (APP_ENV)

No additional setup is required beyond installing the package and configuring the webhook.
```
Example Slack Notification
🚨 My Laravel App Queue Job Failed (PRODUCTION) 🚨

Environment: PRODUCTION
Job: App\Jobs\ProcessPayment
ID: 123456
Queue: default
Connection: database
Exception: Payment gateway timeout
```

## Customizing the Notification
You can extend the JobFailedSlackNotification class to add extra fields or change formatting. For example:
```php
namespace App\Notifications;

use Hdruk\JobFailedSlack\Notifications\JobFailedSlackNotification;

class CustomJobFailedNotification extends JobFailedSlackNotification
{
    public function toSlack($notifiable)
    {
        $message = parent::toSlack($notifiable);
        $message->attachment(function ($attachment) {
            $attachment->fields([
                'Retries' => $this->event->job->attempts(),
            ]);
        });

        return $message;
    }
}
```
## Requirements
Laravel 10, 11, or 12

"laravel/slack-notification-channel": "^3.7"