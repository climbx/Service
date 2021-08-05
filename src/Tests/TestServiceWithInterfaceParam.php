<?php

namespace Climbx\Service\Tests;

use PHPUnit\Framework\TestCase;

/**
 * @codeCoverageIgnore
 */
class TestServiceWithInterfaceParam
{
    public function __construct(
        public TestServiceDependencyInterface $interfaceParam
    ) {
    }
}
