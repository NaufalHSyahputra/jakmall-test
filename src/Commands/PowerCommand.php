<?php

namespace Jakmall\Recruitment\Calculator\Commands;

use Illuminate\Console\Command;
use Jakmall\Recruitment\Calculator\Calculator\Infrastructure\CalculatorManagerInterface;
use Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;

class PowerCommand extends Command
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

    protected $calculate;

    public function __construct(CommandHistoryManagerInterface $log, CalculatorManagerInterface $calculate)
    {
        $commandVerb = $this->getCommandVerb();

        $this->signature = sprintf(
            '%s {numbers* : The numbers to be %s} {--driver=composite}',
            $commandVerb,
            $this->getCommandPassiveVerb()
        );
        $this->description = sprintf('%s all given Numbers', ucfirst($commandVerb));
        $this->log = $log;
        $this->calculate = $calculate;
        parent::__construct();
    }

    protected function getCommandVerb(): string
    {
        return 'power';
    }

    protected function getCommandPassiveVerb(): string
    {
        return 'power';
    }

    public function handle(): void
    {
        $numbers = $this->getInput();
        $return = $this->calculate->calculate($numbers, $this->getOperator(), $this->getDriver(), $this->getCommandVerb());
        $this->comment(sprintf('%s = %s', $return['operation'], $return['result']));
    }

    protected function getInput(): array
    {
        return $this->argument('numbers');
    }

    protected function getDriver(): string
    {
        return $this->option('driver');
    }

    protected function getOperator(): string
    {
        return '^';
    }
}
