# 🚨 Laravel Error Reporter

**Never miss a critical error again!** Get instant notifications when your Laravel application encounters exceptions.

[![Latest Version](https://img.shields.io/packagist/v/yourusername/laravel-error-reporter?style=flat-square)](https://packagist.org/packages/yourusername/laravel-error-reporter)
[![Total Downloads](https://img.shields.io/packagist/dt/yourusername/laravel-error-reporter?style=flat-square)](https://packagist.org/packages/yourusername/laravel-error-reporter)
[![License](https://img.shields.io/github/license/yourusername/laravel-error-reporter?style=flat-square)](LICENSE)
[![Tests](https://img.shields.io/github/actions/workflow/status/yourusername/laravel-error-reporter/tests.yml?label=tests&style=flat-square)](https://github.com/yourusername/laravel-error-reporter/actions)

## 🔥 Features That Will Save Your Day

- **Instant Alerts** - Get notified immediately when errors occur
- **Multi-Channel Support** - Discord, Email, and more coming soon!
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

## 💡 Why You Need This

Imagine this: It's 3 AM, your production app is crashing, and you have no idea. Customers are frustrated, revenue is being lost. 

With Laravel Error Reporter, you'll:

✅ **Catch errors before users report them**  
✅ **Get notified instantly** via Discord/Email  
✅ **Sleep better** knowing you'll be alerted of issues  
✅ **Fix problems faster** with detailed error reports  

## 📌 Basic Usage

### Report Exceptions Automatically
```php
// In your exception handler
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

Configure your notification channels in `.env`:

```env
# Discord
ERROR_REPORTER_DISCORD_ENABLED=true
ERROR_REPORTER_DISCORD_WEBHOOK=https://discord.com/api/webhooks/...
ERROR_REPORTER_DISCORD_USERNAME="Error Bot"

# Email
ERROR_REPORTER_EMAIL_ENABLED=true
ERROR_REPORTER_EMAIL_TO=admin@example.com
```

## 🎨 Customization

### Customize Error Messages
```php
ErrorReporter::report($exception, function($message) {
    return $message->setTitle('Custom Title')
                  ->addContext('user_id', auth()->id());
});
```

### Add Your Own Channels
```php
// In a service provider
ErrorReporter::extend('slack', function($app, $config) {
    return new SlackChannel($config);
});
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