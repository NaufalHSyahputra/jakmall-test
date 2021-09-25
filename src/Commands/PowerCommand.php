<?php

namespace Jakmall\Recruitment\Calculator\Commands;

use Illuminate\Console\Command;
use Jakmall\Recruitment\Calculator\Libraries\Calculator;

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

    public function __construct()
    {
        $commandVerb = $this->getCommandVerb();

        $this->signature = sprintf(
            '%s {numbers* : The numbers to be %s}',
            $commandVerb,
            $this->getCommandPassiveVerb()
        );
        $this->description = sprintf('%s all given Numbers', ucfirst($commandVerb));
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
        $calculator = new Calculator();
        $calculator->setNumbers($numbers);
        $calculator->setOperator($this->getOperator());
        $result = $calculator->calculateAll($numbers);
        $description = $this->generateCalculationDescription($numbers);

        $this->comment(sprintf('%s = %s', $description, $result));
    }

    protected function getInput(): array
    {
        return $this->argument('numbers');
    }

    protected function generateCalculationDescription(array $numbers): string
    {
        $operator = $this->getOperator();
        $glue = sprintf(' %s ', $operator);

        return implode($glue, $numbers);
    }

    protected function getOperator(): string
    {
        return '^';
    }
}
