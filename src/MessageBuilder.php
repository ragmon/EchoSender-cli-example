<?php

namespace App;

/**
 * Class MessageBuilder
 *
 * @package App
 */
class MessageBuilder
{
    private $recipient;
    private $subject;
    private $content;

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @param mixed $recipient
     */
    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * Build the message entity.
     *
     * @return Message
     */
    public function build()
    {
        $message = new Message();
        $message->subject = $this->subject;
        $message->recipient = $this->recipient;
        $message->content = $this->content;
        $message->timestamp = date('Y-m-d');

        return $message;
    }
}