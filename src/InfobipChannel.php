<?php

namespace FleetRunnr\Infobip;

use FleetRunnr\Infobip\PhoneNumber;
use FleetRunnr\Infobip\SmsMessage;
use FleetRunnr\Infobip\Exceptions\CouldNotSendNotification;
use Illuminate\Notifications\Notification;
use infobip\api\client\SendSingleTextualSms;
use infobip\api\configuration\ApiKeyAuthConfiguration;
use infobip\api\model\sms\mt\send\textual\SMSTextualRequest;

class InfobipChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed $notifiable
     * @param  \Illuminate\Notifications\Notification $notification
     * @return void|bool
     */
    public function send($notifiable, Notification $notification)
    {
        // Check if the API Key exists in config
        $api_key = config('services.infobip.api_key');
        if(!$api_key) {
            throw CouldNotSendNotification::missingApiKey();
        }

        // Configure the Infobip API Client
        $configuration = new ApiKeyAuthConfiguration(config('services.infobip.api_key'));
        $client = new SendSingleTextualSms($configuration);

        // Validate the message receiver
        if(!isset($notifiable->phone) || !PhoneNumber::isValid($notifiable->phone)) {
            throw CouldNotSendNotification::invalidReceiver();
        }

        // Get the notification message
        $message = $notification->toInfobip($notifiable);

        // Validate the message
        if(!$message instanceof SmsMessage) {
            throw CouldNotSendNotification::invalidMessageObject($message);
        }

        // Get the message recipient and sender
        $to = $notifiable->phone;
        $from = $message->sender;

        // Build the request
        $requestBody = new SMSTextualRequest();
        $requestBody->setTo($to);
        $requestBody->setFrom($from);
        $requestBody->setText($message->content);

        // Try to send the SMS message
        try {
            $client->execute($requestBody);
        } catch (\Exception $e) {
            throw CouldNotSendNotification::infobipError($e->getMessage());
        }
    }
}
