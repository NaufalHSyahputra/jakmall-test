<?php

namespace Jakmall\Recruitment\Calculator\Calculator\Infrastructure;

//TODO: create implementation.
interface CalculatorManagerInterface
{
    /**
     * Do Calculation
     *
     * @return void
     */
    public function calculate(array $numbers, string $operator, string $driver, string $command) : array;
}
