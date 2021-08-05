<?php

namespace Climbx\Service\Tests;

use PHPUnit\Framework\TestCase;

/**
 * @codeCoverageIgnore
 */
class TestServiceWithUnknownTypeParam
{
    public function __construct(
        public $unknownParam,
    ) {
    }
}
