<?php

namespace Jakmall\Recruitment\Calculator\Libraries;

use Exception;

class File
{
    /**
     * @var string
     */
    private $filename;

    public function __construct($filename)
    {
        $this->setFilename($filename);
    }

    /**
     * Get the value of filename
     *
     * @return  string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set the value of filename
     *
     * @param  string  $filename
     *
     * @return  self
     */
    public function setFilename(string $filename)
    {
        $this->filename = $filename;

        return $this;
    }

    public function read()
    {
        if (!file_exists($this->getFilename())) {
            throw new Exception("File Doesn't Exists");
        }
        return file_get_contents($this->getFilename());
    }

    public function readToArray()
    {
        return file($this->getFilename(), FILE_IGNORE_NEW_LINES);
    }

    public function write($content): bool
    {
        return file_put_contents($this->getFilename(), $content);
    }

    public function append($content): bool
    {
        return file_put_contents($this->getFilename(), $content, FILE_APPEND);
    }
}
