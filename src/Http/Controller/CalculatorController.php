<?php

namespace Jakmall\Recruitment\Calculator\Http\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Jakmall\Recruitment\Calculator\Calculator\Infrastructure\CalculatorManagerInterface;
use Jakmall\Recruitment\Calculator\Libraries\Calculator;

class CalculatorController
{
    private $calculate;
    public function __construct(CalculatorManagerInterface $calculate)
    {
        $this->calculate = $calculate;
    }
    public function calculate(Request $request, string $command)
    {
        $commands = [
            'add' => '+',
            'divide' => '/',
            'power' => '^',
            'multiply' => '*',
            'substract' => '-',
        ];
        if (!in_array($command, array_keys($commands))) { 
            return Response::create(['message' => 'Command not found!'], Response::HTTP_BAD_REQUEST);
        }
        if (!$request->has('input')) { 
            return Response::create(['message' => 'Input Required!'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        if (!is_array($request->get('input'))) { 
            return Response::create(['message' => 'Input Must Be an Array!'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return Response::create($this->calculate->calculate($request->get('input'), $commands[$command], $request->query('driver', 'composite'), $command), 200);

    }
}
