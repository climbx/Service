<?php

namespace Climbx\Service\Tests;

use Climbx\Config\ConfigContainer;
use Climbx\Service\Config\ServiceConfigReader;
use Climbx\Service\Container;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Climbx\Service\Container
 */
class ContainerGetWithoutParamTest extends TestCase
{
    /**
     * @uses \Climbx\Service\Tests\SimpleTestService
     */
    public function testGetServiceWithoutParams()
    {
        $configContainer = $this->createStub(ConfigContainer::class);
        $configReader = $this->createStub(ServiceConfigReader::class);

        $container = new Container($configContainer, $configReader);

        $simpleService = $container->get(SimpleTestService::class);

        // loading service into the container
        $this->assertInstanceOf(SimpleTestService::class, $simpleService);

        // get service that is already loaded in the container
        $alreadyLoadedService = $container->get(SimpleTestService::class);
        $this->assertEquals($alreadyLoadedService, $simpleService);
    }
}
