<?php

require 'vendor/autoload.php';

// Only for command line usage
if (PHP_SAPI !== 'cli')
    exit();

// Define STDIN in case if it is not already defined by PHP for some reason
if (!defined("STDIN")) {
    define("STDIN", fopen('php://stdin', 'r'));
}

use App\Cli;
use App\MessageBuilder;
use App\Log;

// Path to the EchoSender log file
define('LOG_FILE', './log.csv');

$cli = new Cli(STDIN);

print("EchoSender CLI\n");

$recipient = $cli->prompt('Enter the recipient email:');
$subject = $cli->prompt('Enter the subject:', 32);
$content = $cli->prompt('Enter the message content:', 1024);

// Validate recipient
if ( ! filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
    print('Recipient email must be valid');
    exit(1);
}

$log = new Log(LOG_FILE);

// Check if notification for current recipient already sent today
$recipientMessages = $log->find($recipient, Log::COLUMN_RECIPIENT);
if ($lastMessage = end($recipientMessages)) {
    $lastMessageDate = date_create($lastMessage[Log::COLUMN_TIMESTAMP]);
    $now = date_create('now');

    // Notification was already sent today for this recipient
    if ($now->diff($lastMessageDate)->format('d') == 0) {
        print("Recipient $recipient was sent the message today");
        exit(1);
    }
}

// Build the message entity
$builder = new MessageBuilder();
$builder->setRecipient($recipient);
$builder->setSubject($subject);
$builder->setContent($content);
$message = $builder->build();

// Add new log line (CSV line)
$log->add($message);
$log->close();

print('Message stored');
