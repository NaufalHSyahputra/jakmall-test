<?php

namespace Jakmall\Recruitment\Calculator\Calculator;

use Jakmall\Recruitment\Calculator\Calculator\Infrastructure\CalculatorManagerInterface;
use Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;
use Jakmall\Recruitment\Calculator\Libraries\Calculator;

class CalculatorManager implements CalculatorManagerInterface
{
    private $log;
    public function __construct(CommandHistoryManagerInterface $log)
    {
        $this->log = $log;
    }
    public function calculate(array $numbers, string $operator, string $driver, string $command) : array
    {
        $calculator = new Calculator();
        $calculator->setNumbers($numbers);
        $calculator->setOperator($operator);
        $result = $calculator->calculateAll($numbers);
        
        $description = $this->generateCalculationDescription($numbers, $operator);
        
        $this->log->setDriver($driver);
        $this->log->log(sprintf("%s|%s", ucfirst($command), sprintf('%s=%s', $description, $result)));
        
        return ['command' => $command, 'operation' => $description, 'result' => $result];
    }

    private function generateCalculationDescription(array $numbers, string $operator): string
    {
        $operator = $operator;
        $glue = sprintf(' %s ', $operator);

        return implode($glue, $numbers);
    }
}
