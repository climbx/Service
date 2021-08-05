<?php

namespace Climbx\Service\Tests;

use PHPUnit\Framework\TestCase;

/**
 * @codeCoverageIgnore
 */
class TestServiceWithInvalidParam
{
    public function __construct(
        public float $invalidParam
    ) {
    }
}
