<?php

namespace Climbx\Service\Tests;

use PHPUnit\Framework\TestCase;

/**
 * @codeCoverageIgnore
 */
class TestServiceWithBoolParam
{
    public function __construct(
        public bool $boolParam,
    ) {
    }
}
