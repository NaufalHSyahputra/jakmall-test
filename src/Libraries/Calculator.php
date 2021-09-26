<?php

namespace Jakmall\Recruitment\Calculator\Libraries;

class Calculator
{
    /**
     * @var array
     */
    private $numbers;
    /**
     * @var string
     */
    private $operator;

    /**
     * Get the value of numbers
     *
     * @return  array
     */
    public function getNumbers()
    {
        return $this->numbers;
    }

    /**
     * Set the value of numbers
     *
     * @param  array  $numbers
     *
     * @return  self
     */
    public function setNumbers(array $numbers)
    {
        $this->numbers = $numbers;

        return $this;
    }

    /**
     * Get the value of operator
     *
     * @return  string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Set the value of operator
     *
     * @param  string  $operator
     *
     * @return  self
     */
    public function setOperator(string $operator)
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * @param array $numbers
     *
     * @return float|int
     */
    public function calculateAll($numbers)
    {
        $number = array_pop($numbers);

        if (count($numbers) <= 0) {
            return (int)$number;
        }

        return $this->calculate($this->calculateAll($numbers), $number, $this->getOperator());
    }

    /**
     * @param int|float $number1
     * @param int|float $number2
     *
     * @return int|float
     */
    private function calculate($number1, $number2, $operator)
    {
        switch ($operator) {
            case '-':
                return (int)$number1 - (int)$number2;
                break;
            case '*':
                return (int)$number1 * (int)$number2;
                break;
            case '/':
                return (int)$number1 / (int)$number2;
                break;
            case '^':
                return (int)$number1 ** (int)$number2;
                break;
            default:
                return (int)$number1 + (int)$number2;
                break;
        }
    }
}
