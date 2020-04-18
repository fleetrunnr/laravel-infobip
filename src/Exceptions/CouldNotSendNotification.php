<?php

namespace FleetRunnr\Infobip\Exceptions;

use FleetRunnr\Infobip\SmsMessage;

class CouldNotSendNotification extends \Exception
{
    /**
     * @return static
     */
    public static function missingApiKey()
    {
        return new static(
            'Notification was not sent. Missing `infobip.api_key` in `config/services.php`.'
        );
    }
    
    /**
     * @param mixed $message
     *
     * @return static
     */
    public static function invalidMessageObject($message)
    {
        $className = get_class($message) ?: 'Unknown';

        return new static(
            'Notification was not sent. Message object class `{$className}` is invalid, it 
            should be `' . SmsMessage::class . '`.'
        );
    }

    /**
     * @return static
     */
    public static function invalidReceiver()
    {
        return new static(
            'The notifiable phone number is invalid. Make sure to have a valid `phone` attribute 
            on your notifiable and that the number is in international format.'
        );
    }

    /**
     * @param mixed $message
     *
     * @return static
     */
    public static function infobipError($message)
    {
        return new static(
            'Could not send notification message. Infobip replied with: ' . $message
        );
    }
}