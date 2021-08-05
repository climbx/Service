<?php

namespace Climbx\Service\Tests;

use Climbx\Config\ConfigContainer;
use Climbx\Service\Config\ServiceConfigReader;
use Climbx\Service\Container;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Climbx\Service\Container
 */
class ContainerHasTest extends TestCase
{
    /**
     * @uses \Climbx\Service\Tests\SimpleTestService
     */
    public function testHas()
    {
        $configContainer = $this->createStub(ConfigContainer::class);
        $configReader = $this->createStub(ServiceConfigReader::class);

        $container = new Container($configContainer, $configReader);

        $this->assertTrue($container->has(SimpleTestService::class));
        $this->assertFalse($container->has('My\Fake\Class'));
    }
}
