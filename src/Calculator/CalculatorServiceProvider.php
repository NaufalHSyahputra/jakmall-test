<?php

namespace Jakmall\Recruitment\Calculator\Calculator;

use Illuminate\Contracts\Container\Container;
use Jakmall\Recruitment\Calculator\Calculator\Infrastructure\CalculatorManagerInterface;
use Jakmall\Recruitment\Calculator\Calculator\CalculatorManager;
use Jakmall\Recruitment\Calculator\Container\ContainerServiceProviderInterface;

class CalculatorServiceProvider implements ContainerServiceProviderInterface
{
    /**
     * @inheritDoc
     */
    public function register(Container $container): void
    {
        $container->bind(
            CalculatorManagerInterface::class,
            CalculatorManager::class
        );
    }
}
