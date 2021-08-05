<?php

namespace Climbx\Service\Tests;

use PHPUnit\Framework\TestCase;

/**
 * @codeCoverageIgnore
 */
class TestServiceWithArrayParam
{
    public function __construct(
        public array $arrayParam,
    ) {
    }
}
