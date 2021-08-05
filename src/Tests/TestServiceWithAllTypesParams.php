<?php

namespace Climbx\Service\Tests;

use Climbx\Config\Bag\ConfigBagInterface;
use PHPUnit\Framework\TestCase;

/**
 * @codeCoverageIgnore
 */
class TestServiceWithAllTypesParams
{
    public function __construct(
        public bool $boolParam,
        public int $intParam,
        public string $stringParam,
        public array $arrayParam,
        public ConfigBagInterface $configBagParam,
        public SimpleTestService $serviceParam,
        public TestServiceDependencyInterface $interfaceParam,
    ) {
    }
}
