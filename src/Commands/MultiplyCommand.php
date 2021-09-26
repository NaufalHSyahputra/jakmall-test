<?php

namespace Jakmall\Recruitment\Calculator\Commands;

use Illuminate\Console\Command;
use Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;
use Jakmall\Recruitment\Calculator\Libraries\Calculator;

class MultiplyCommand extends Command
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
            '%s {numbers* : The numbers to be %s} {--driver=composite}',
            $commandVerb,
            $this->getCommandPassiveVerb()
        );
        $this->description = sprintf('%s all given Numbers', ucfirst($commandVerb));
        $this->log = $log;
        parent::__construct();
    }

    protected function getCommandVerb(): string
    {
        return 'multiply';
    }

    protected function getCommandPassiveVerb(): string
    {
        return 'multiplied';
    }

    public function handle(): void
    {
        $numbers = $this->getInput();
        
        $calculator = new Calculator();
        $calculator->setNumbers($numbers);
        $calculator->setOperator($this->getOperator());
        $result = $calculator->calculateAll($numbers);
        
        $description = $this->generateCalculationDescription($numbers);
        
        $this->log->setDriver($this->getDriver());
        $this->log->log(sprintf("%s|%s", ucfirst($this->getCommandVerb()), sprintf('%s=%s', $description, $result)));

        $this->comment(sprintf('%s = %s', $description, $result));
    }

    protected function getInput(): array
    {
        return $this->argument('numbers');
    }

    protected function getDriver(): string
    {
        return $this->option('driver');
    }


    protected function generateCalculationDescription(array $numbers): string
    {
        $operator = $this->getOperator();
        $glue = sprintf(' %s ', $operator);

        return implode($glue, $numbers);
    }

    protected function getOperator(): string
    {
        return '*';
    }
}
