<?php

namespace Jakmall\Recruitment\Calculator\Commands;

use Exception;
use Illuminate\Console\Command;
use Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;

class HistoryClearCommand extends Command
{
    /**
     * @var string
     */
    protected $signature;

    /**
     * @var string
     */
    protected $description;

    protected $log;

    public function __construct(CommandHistoryManagerInterface $log)
    {
        $commandVerb = $this->getCommandVerb();

        $this->signature = sprintf(
            '%s {id? : Log ID} {--driver=composite}',
            $commandVerb,
        );
        $this->description = "Print Log";
        $this->log = $log;
        parent::__construct();
    }

    protected function getCommandVerb(): string
    {
        return 'history:clear';
    }

    public function handle(): void
    {
        $id = $this->getInput();
        $option = $this->getOption();
        $this->log->setDriver($option);
        if ($this->confirm("Are you sure to clear the history?", false)) {
            if (!$id) {
                if ($this->log->clearAll() === false) {
                    throw new Exception("Clear History Failed");
                }
                $this->comment("All history is cleared.");
            } else {
                if ($this->log->clear($id) === false) {
                    throw new Exception("Clear History Failed");
                }
                $this->comment("Data with ID {$id} is removed");
            }
        }
    }

    protected function getInput()
    {
        return $this->argument('id');
    }

    protected function getOption(): string
    {
        return $this->option('driver');
    }
}
