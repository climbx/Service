<?php

namespace Climbx\Service\Tests;

use Climbx\Config\Bag\ConfigBag;
use Climbx\Config\ConfigContainer;
use Climbx\Service\Config\ServiceConfigReader;
use Climbx\Service\Container;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Climbx\Service\Container
 */
class ContainerGetWithManyParamsTest extends TestCase
{
    /**
     * @uses \Climbx\Service\Tests\TestServiceWithAllTypesParams
     */
    public function testGetWithManyParams()
    {
        $configContainer = $this->createStub(ConfigContainer::class);
        $serviceConfigReader = $this->createStub(ServiceConfigReader::class);
        $configBag = $this->createStub(ConfigBag::class);
        $serviceConfigReaderMap = [
            [
                TestServiceWithAllTypesParams::class,
                'boolParam',
                ServiceConfigReader::PARAM_TYPE_BOOL,
                'true'
            ],
            [
                TestServiceWithAllTypesParams::class,
                'intParam',
                ServiceConfigReader::PARAM_TYPE_INT,
                1234
            ],
            [
                TestServiceWithAllTypesParams::class,
                'stringParam',
                ServiceConfigReader::PARAM_TYPE_STRING,
                'Hello world!'
            ],
            [
                TestServiceWithAllTypesParams::class,
                'arrayParam',
                ServiceConfigReader::PARAM_TYPE_ARRAY,
                ['FOO' => 'BAR']
            ],
            [
                TestServiceWithAllTypesParams::class,
                'configBagParam',
                ServiceConfigReader::PARAM_TYPE_STRING,
                'lib/myConfig'
            ]
        ];
        $configContainerMap = [
            ['lib/myConfig', $configBag]
        ];
        $configContainer->method('get')->willReturnMap($configContainerMap);
        $serviceConfigReader->method('getParamValue')->willReturnMap($serviceConfigReaderMap);

        $container = new Container($configContainer, $serviceConfigReader);

        $service = $container->get(TestServiceWithAllTypesParams::class);
        $this->assertInstanceOf(TestServiceWithAllTypesParams::class, $service);
        $this->assertTrue($service->boolParam);
        $this->assertEquals(1234, $service->intParam);
        $this->assertEquals('Hello world!', $service->stringParam);
        $this->assertEquals(['FOO' => 'BAR'], $service->arrayParam);
        $this->assertInstanceOf(SimpleTestService::class, $service->serviceParam);
        $this->assertInstanceOf(TestServiceDependencyInterface::class, $service->interfaceParam);
        $this->assertInstanceOf(TestServiceDependency::class, $service->interfaceParam);
        $this->assertEquals($configBag, $service->configBagParam);
    }
}
