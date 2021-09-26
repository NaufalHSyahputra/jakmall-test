<?php

namespace Jakmall\Recruitment\Calculator\History;

use Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;
use Jakmall\Recruitment\Calculator\Libraries\StorageDriver\CompositeDriver;
use Jakmall\Recruitment\Calculator\Libraries\StorageDriver\FileDriver;
use Jakmall\Recruitment\Calculator\Libraries\StorageDriver\LatestDriver;

class CommandHistoryManager implements CommandHistoryManagerInterface
{
    private $driverClass = null;

    public function setDriver($driver): void
    { 
        switch ($driver) {
            case 'latest':
                $this->driverClass = new LatestDriver();
                break;
            case 'file':
                $this->driverClass = new FileDriver();
                break;
            default:
                $this->driverClass = new CompositeDriver();
                break;
        }
    }
    /**
     * Returns array of command history.
     *
     * @return array returns an array of commands in storage
     */
    public function findAll(): array
    {
        if (!$this->driverClass) { 
            $this->driverClass = new CompositeDriver();
        }
        return $this->driverClass->readAll();
    }

    /**
     * Find a command by id.
     *
     * @param string|int $id
     *
     * @return null|mixed returns null when id not found.
     */
    public function find($id)
    {
        if (!$this->driverClass) { 
            $this->driverClass = new CompositeDriver();
        }
        return $this->driverClass->read($id);
    }

    /**
     * Log command data to storage.
     *
     * @param mixed $command The command to log.
     *
     * @return bool Returns true when command is logged successfully, false otherwise.
     */
    public function log($command): bool
    {
        if (!$this->driverClass) { 
            $this->driverClass = new CompositeDriver();
        }
        $command_split = explode("|",$command);
        return $this->driverClass->write($command_split[1],$command_split[0]);
    }

    /**
     * Clear a command by id
     *
     * @param string|int $id
     *
     * @return bool Returns true when data with $id is cleared successfully, false otherwise.
     */
    public function clear($id): bool
    {
        if (!$this->driverClass) { 
            $this->driverClass = new CompositeDriver();
        }
        return $this->driverClass->clear($id);
    }

    /**
     * Clear all data from storage.
     *
     * @return bool Returns true if all data is cleared successfully, false otherwise.
     */
    public function clearAll(): bool
    {
        if (!$this->driverClass) { 
            $this->driverClass = new CompositeDriver();
        }
        return $this->driverClass->clearAll();
    }
}
