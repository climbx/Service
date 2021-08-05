<?php

namespace Climbx\Service\Tests;

use PHPUnit\Framework\TestCase;

/**
 * @codeCoverageIgnore
 */
class TestServiceWithServiceParam
{
    public function __construct(
        public TestServiceDependency $serviceParam
    ) {
    }
}
