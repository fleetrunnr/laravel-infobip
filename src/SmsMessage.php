<?php

namespace FleetRunnr\Infobip;

class SmsMessage
{
    /**
     * The message sender.
     *
     * @var string
     */
    public $sender;

    /**
     * The message content.
     *
     * @var string
     */
    public $content;

    /**
     * Create a new message instance.
     *
     */
    public function __construct()
    {
        $this->sender = config('services.infobip.sender_id');
        $this->content = '';
    }

    /**
     * Set the message sender.
     *
     * @param  string $sender
     * @return $this
     */
    public function sender($sender)
    {
        // Only override sender if it is set.
        if($sender) {
            $this->sender = $sender;
        }

        return $this;
    }

    /**
     * Set the message content.
     *
     * @param  string $content
     * @return $this
     */
    public function content($content)
    {
        $this->content = $content;
        return $this;
    }
}
