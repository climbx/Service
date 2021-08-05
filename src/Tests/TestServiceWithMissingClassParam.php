<?php

namespace Climbx\Service\Tests;

use PHPUnit\Framework\TestCase;

/**
 * @codeCoverageIgnore
 */
class TestServiceWithMissingClassParam
{
    public function __construct(
        public MyMissingClass $missingClassParam
    ) {
    }
}
