<?php

namespace Jakmall\Recruitment\Calculator\Commands;

use Illuminate\Console\Command;
use Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;

class HistoryListCommand extends Command
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
        return 'history:list';
    }

    public function handle(): void
    {
        $id = $this->getInput();
        $option = $this->getOption();
        $this->log->setDriver($option);
        if (!$id) {
            $new_arrays = $this->log->findAll();
            if (count($new_arrays) <= 0) {
                $this->error("Data doesn't exists");
                return;
            }
            $this->table(['ID', 'Command', 'Operation', 'Result'], $new_arrays);
        } else {
            $new_arrays = $this->log->find($id);
            if (count($new_arrays) <= 0) {
                $this->error("Data doesn't exists");
                return;
            }
            $this->table(['ID', 'Command', 'Operation', 'Result'], $new_arrays);
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
