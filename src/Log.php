<?php

namespace App;

use RuntimeException;

/**
 * Class Log
 *
 * @package App
 */
class Log
{
    // Columns indexes
    const COLUMN_RECIPIENT = 0;
    const COLUMN_SUBJECT = 1;
    const COLUMN_CONTENT = 2;
    const COLUMN_TIMESTAMP = 3;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var resource
     */
    private $handle;

    /**
     * CSV constructor.
     *
     * @param $filename
     */
    public function __construct($filename)
    {
        $this->filename = $filename;

        if (($this->handle = fopen($filename, 'a+')) === false) {
            throw new RuntimeException("Can't open log file $filename");
        }
    }

    /**
     * Add message to log line.
     *
     * @param Message $message
     * @return false|int Returns the length of the written string or FALSE on failure.
     */
    public function add(Message $message)
    {
        return fputcsv($this->handle, $this->transformMessage($message));
    }

    /**
     * Transform message to the log output.
     *
     * @param Message $message
     * @return array
     */
    private function transformMessage(Message $message)
    {
        return [
            self::COLUMN_RECIPIENT => $message->recipient,
            self::COLUMN_SUBJECT => $message->subject,
            self::COLUMN_CONTENT => $message->content,
            self::COLUMN_TIMESTAMP => $message->timestamp,
        ];
    }

    /**
     * Find rows by column and query.
     *
     * @param string $query
     * @param int $column
     * @return array
     */
    public function find($query, $column)
    {
        $rows = [];
        while ($row = fgetcsv($this->handle)) {
            if ($row[$column] == $query) {
                $rows[] = $row;
            }
        }
        return $rows;
    }

    /**
     * Close log file.
     *
     * @return bool
     */
    public function close()
    {
        return fclose($this->handle);
    }
}