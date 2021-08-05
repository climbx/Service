<?php

namespace Climbx\Service\Tests;

use Climbx\Config\Bag\ConfigBagInterface;
use PHPUnit\Framework\TestCase;

/**
 * @codeCoverageIgnore
 */
class TestServiceWithConfigBagParam
{
    public function __construct(
        public ConfigBagInterface $configBagParam,
    ) {
    }
}
