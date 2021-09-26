<?php

namespace Jakmall\Recruitment\Calculator\Libraries\StorageDriver;

use Exception;
use Jakmall\Recruitment\Calculator\Libraries\StorageDriver\FileDriver;

class CompositeDriver
{
    private $latest_driver;
    private $file_driver;

    public function __construct()
    {
        $this->file_driver = new FileDriver();
        $this->latest_driver = new LatestDriver();
    }

    public function readAll(): array
    {
        return $this->file_driver->readAll();
    }

    public function read($id): array
    {
        if (count($this->latest_driver->read($id)) > 0) { 
            return $this->latest_driver->read($id);
        } else if (count($this->file_driver->read($id)) > 0) { 
            return $this->file_driver->read($id);
        } else {
            return [];
        }
    }

    public function write($content, $command): bool
    {
        if (!$this->latest_driver->write($content, $command)) {
            throw new Exception("Write history to Latest Driver failed!");
        }
        if (!$this->file_driver->write($content, $command)) {
            throw new Exception("Write history to File Driver failed!");
        }
        return true;
    }

    public function clearAll(): bool
    {
        $this->latest_driver->clearAll();
        $this->file_driver->clearAll();
        return true;
    }

    public function clear($id): bool
    {
        $this->latest_driver->clear($id);
        if (!$this->file_driver->clear($id)) {
            return false;
        }
        return true;
    }
}
