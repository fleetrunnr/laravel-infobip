# Infobip SMS notification channel for Laravel

[![Software License][ico-license]](LICENSE.md)

## About

The `laravel-infobip` package allows you to send SMS messages via the [Infobip](https://infobip.com) service.
If creates a new notification channel which you can utilize by calling the `toInfobip` function in your notification classes.

## Features

* Send SMS messages from Laravel using notification channels
* Ability to specify a custom Sender ID before sending a message

## Installation

You may install this package via Composer:

```sh
composer require fleetrunnr/laravel-infobip`
```

## Configuration

Add your Infobip API key and Sender ID (optional) to your `config/services.php`:

```sh
...

'infobip' => [
    'api_key' => env('INFOBIP_API_KEY'),
    'from' => env('INFOBIP_FROM'), // optional, will fallback to your account default
],

...
```

## Usage

The first thing to be done is make sure your `notifiable` class has a `phone` attribute.
Phone numbers should follow the E164 international format (eg. +9613123456).

After that, create a new notification class using the `php artisan make:notification WelcomeNotification` command.

In your notification class, make sure to use the `FleetRunnr\Infobip\InfobipChannel` and `FleetRunnr\Infobip\SmsMessage` classes. You should then specify the `InfobipChannel::class` in your `via` method, and then implement a `toInfobip` method that creates a new `SmsMessage`:

```php
<?php

namespace App\Notifications;

use FleetRunnr\Infobip\InfobipChannel;
use FleetRunnr\Infobip\SmsMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification
{
	use Queueable;
	
	/**
     * The SMS message text.
     *
     * @var string
     */
    protected $text;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($text)
    {
        $this->text = $text;
    }

    /**
     * Get the notification channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return [InfobipChannel::class];
    }

    /**
     * Get the SMS representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return SmsMessage
     */
    public function toInfobip($notifiable)
    {
		return (new SmsMessage)
			->sender(env('APP_NAME'))
			->content($this->text);
    }
}
```

After creating your notification class, you may now utilize it as follows (given the model you are notifying already uses the `Notifiable` trait):

```php
<?php

namespace App\Http\Controllers;

use App\User;
use App\Notifications\WelcomeNotification;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
	...

	/**
     * Send a welcome SMS to the user.
     *
     * @param Request $request
     * @return Response
     */
	public function sendWelcomeSms(Request $request)
	{
		...

		// Notify the user
		$user->notify(new WelcomeNotification("Welcome to the app!"));

		...
	}

	...
}
```

## License

This library is licensed under the [MIT License](https://opensource.org/licenses/MIT)
