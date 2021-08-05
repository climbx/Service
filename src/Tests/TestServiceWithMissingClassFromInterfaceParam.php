<?php

namespace Climbx\Service\Tests;

use PHPUnit\Framework\TestCase;

/**
 * @codeCoverageIgnore
 */
class TestServiceWithMissingClassFromInterfaceParam
{
    public function __construct(
        public TestUnusedInterface $missingClassParam
    ) {
    }
}
