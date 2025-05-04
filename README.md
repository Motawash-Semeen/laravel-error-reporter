# 🚨 Laravel Error Reporter

**Never miss a critical error again!** Get instant notifications when your Laravel application encounters exceptions.

[![Latest Version](https://img.shields.io/packagist/v/msemeen/laravel-error-reporter?style=flat-square)](https://packagist.org/packages/msemeen/laravel-error-reporter)
[![Total Downloads](https://img.shields.io/packagist/dt/msemeen/laravel-error-reporter?style=flat-square)](https://packagist.org/packages/msemeen/laravel-error-reporter)
[![License](https://img.shields.io/github/license/msemeen/laravel-error-reporter?style=flat-square)](LICENSE)
[![Tests](https://img.shields.io/github/actions/workflow/status/msemeen/laravel-error-reporter/tests.yml?label=tests&style=flat-square)](https://github.com/msemeen/laravel-error-reporter/actions)

## 🔥 Features That Will Save Your Day

- **Instant Alerts** - Get notified immediately when errors occur
- **Multi-Channel Support** - Discord, Slack, Email, and more coming soon!
- **Smart Grouping** - Similar errors are grouped to avoid notification spam
- **Zero Config** - Works out of the box with sensible defaults
- **Lightweight** - No bloated dependencies or database requirements
- **Laravel 8-12+** - Full support across Laravel versions

```php
// Never miss an error again!
try {
    // Your risky code here
} catch (\Exception $e) {
    ErrorReporter::report($e); // That's it!
}
```

## 🚀 Quick Installation

1. Install via Composer:
```bash
composer require msemeen/laravel-error-reporter
```

2. Publish the config file (optional):
```bash
php artisan vendor:publish --tag=error-reporter-config
```

3. Publish the email template file (optional):
```bash
php artisan vendor:publish --tag=error-reporter-views
```

## 💡 Why You Need This

Imagine this: It's 3 AM, your production app is crashing, and you have no idea. Customers are frustrated, revenue is being lost. 

With Laravel Error Reporter, you'll:

✅ **Catch errors before users report them**  
✅ **Get notified instantly** via Discord/Slack/Email  
✅ **Sleep better** knowing you'll be alerted of issues  
✅ **Fix problems faster** with detailed error reports  

## 📌 Basic Usage

### Report Exceptions Automatically
```php
// In your exception handler add below function
use Msemeen\ErrorReporter\Facades\ErrorReporter;

public function register()
{
    $this->reportable(function (Throwable $e) {
        ErrorReporter::report($e);
    });
}
```

### Send Custom Error Messages
```php
// Manually send important notifications
ErrorReporter::send(
    'Payment service is down!', 
    'Critical Service Alert'
);
```

## ⚙️ Configuration

### Environment Variables

Configure your notification channels in `.env`:

```env

# Discord
ERROR_REPORTER_DISCORD_ENABLED=true
ERROR_REPORTER_DISCORD_WEBHOOK=https://discord.com/api/webhooks/...
ERROR_REPORTER_DISCORD_USERNAME="Error Bot"

# Email
ERROR_REPORTER_EMAIL_ENABLED=true
ERROR_REPORTER_EMAIL_TO=admin@example.com
ERROR_REPORTER_EMAIL_FROM=alerts@example.com
ERROR_REPORTER_EMAIL_SUBJECT="Application Error Alert"

# Slack
ERROR_REPORTER_SLACK_ENABLED=true
ERROR_REPORTER_SLACK_WEBHOOK=https://hooks.slack.com/services/...
ERROR_REPORTER_SLACK_USERNAME="Error Bot"
ERROR_REPORTER_SLACK_ICON=":fire:"
ERROR_REPORTER_SLACK_CHANNEL="#errors"
```

### Enable/Disable Notification Channels

You can easily enable or disable any notification channel through your `.env` file:

```env
# Enable/disable specific channels
ERROR_REPORTER_DISCORD_ENABLED=true
ERROR_REPORTER_EMAIL_ENABLED=false
ERROR_REPORTER_SLACK_ENABLED=true
```

### Setting Up Webhooks

#### Discord Webhook Setup
1. Open your Discord server
2. Go to Server Settings > Integrations > Webhooks
3. Click "New Webhook"
4. Name your webhook (e.g., "Error Reporter")
5. Select the channel where errors should be posted
6. Click "Copy Webhook URL"
7. Add this URL to your `.env` file as `ERROR_REPORTER_DISCORD_WEBHOOK`

#### Slack Webhook Setup
1. Go to https://api.slack.com/apps
2. Click "Create New App" > "From scratch"
3. Name your app and select your workspace
4. From the sidebar, click "Incoming Webhooks"
5. Toggle "Activate Incoming Webhooks" to On
6. Click "Add New Webhook to Workspace"
7. Choose the channel to post messages to
8. Copy the webhook URL
9. Add this URL to your `.env` file as `ERROR_REPORTER_SLACK_WEBHOOK`

## 🎨 Customization

### Customize Error Messages
```php
ErrorReporter::report($exception, function($message) {
    return $message->setTitle('Custom Title')
                  ->addContext('user_id', auth()->id())
                  ->addContext('request_data', request()->all());
});
```

### Advanced Configuration
You can publish and modify the configuration file for more options:

```php
// config/error-reporter.php

return [
    'enabled' => env('ERROR_REPORTER_ENABLED', true),
    
    'grouping' => [
        'enabled' => true,
        'threshold' => 5, // Group similar errors if they occur within 5 minutes
    ],
    
    'channels' => [
        'discord' => [
            'enabled' => env('ERROR_REPORTER_DISCORD_ENABLED', false),
            'webhook' => env('ERROR_REPORTER_DISCORD_WEBHOOK'),
            'username' => env('ERROR_REPORTER_DISCORD_USERNAME', 'Error Reporter'),
        ],
        
        'email' => [
            'enabled' => env('ERROR_REPORTER_EMAIL_ENABLED', false),
            'to' => env('ERROR_REPORTER_EMAIL_TO'),
            'from' => env('ERROR_REPORTER_EMAIL_FROM'),
            'subject' => env('ERROR_REPORTER_EMAIL_SUBJECT', 'Application Error Alert'),
        ],
        
        'slack' => [
            'enabled' => env('ERROR_REPORTER_SLACK_ENABLED', false),
            'webhook' => env('ERROR_REPORTER_SLACK_WEBHOOK'),
            'username' => env('ERROR_REPORTER_SLACK_USERNAME', 'Error Reporter'),
            'icon' => env('ERROR_REPORTER_SLACK_ICON', ':warning:'),
            'channel' => env('ERROR_REPORTER_SLACK_CHANNEL'),
        ],
    ],
    
    'ignore_exceptions' => [
        // List exception classes to ignore
        \Illuminate\Auth\AuthenticationException::class,
        \Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class,
    ],
];
```

## 📊 Error Filtering

Filter out noise by ignoring certain exception types:

```php
// In your ServiceProvider or exception handler
ErrorReporter::ignoreExceptions([
    \Illuminate\Validation\ValidationException::class,
    \Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class,
]);
```

## 🤝 Contributing

We welcome contributions! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## 📜 License

This package is open-source software licensed under the [MIT license](LICENSE).

---

**Stop flying blind.** Get instant visibility into your application's health today! ✨

```bash
composer require msemeen/laravel-error-reporter
```