<?php

namespace Climbx\Service\Tests;

use PHPUnit\Framework\TestCase;

/**
 * @codeCoverageIgnore
 */
class TestServiceWithStringParam
{
    public function __construct(
        public string $stringParam,
    ) {
    }
}
