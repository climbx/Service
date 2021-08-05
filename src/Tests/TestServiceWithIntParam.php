<?php

namespace Climbx\Service\Tests;

use PHPUnit\Framework\TestCase;

/**
 * @codeCoverageIgnore
 */
class TestServiceWithIntParam
{
    public function __construct(
        public int $intParam,
    ) {
    }
}
