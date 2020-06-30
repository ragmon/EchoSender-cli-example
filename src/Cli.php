<?php

namespace App;

use RuntimeException;

/**
 * Class Cli
 *
 * @package App
 */
class Cli
{
    /**
     * @var resource Input stream.
     */
    protected $inputStream;

    /**
     * Cli constructor.
     *
     * @param resource $inputStream
     */
    public function __construct($inputStream)
    {
        $this->inputStream = $inputStream;
    }

    /**
     * Request user input.
     *
     * @param string $hint Hint for user.
     * @param int $length Max input chars.
     * @return string
     * @throws RuntimeException
     */
    public function prompt($hint, $length = 255)
    {
        print("$hint\n");

        if (($data = fread(STDIN, $length)) === false) {
            throw new RuntimeException('Error read from input stream');
        }

        // Remove new line break
        $data = trim($data);

        return $data;
    }
}