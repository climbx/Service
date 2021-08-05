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
class ContainerGetWithSingleParamTest extends TestCase
{
    /**
     * @uses \Climbx\Service\Tests\TestServiceWithBoolParam
     */
    public function testGetWithBoolParam()
    {
        $configContainer = $this->createStub(ConfigContainer::class);
        $serviceConfigReader = $this->createStub(ServiceConfigReader::class);
        $serviceConfigReaderMap = [
            [TestServiceWithBoolParam::class, 'boolParam', ServiceConfigReader::PARAM_TYPE_BOOL, true]
        ];
        $serviceConfigReader->method('getParamValue')->willReturnMap($serviceConfigReaderMap);

        $container = new Container($configContainer, $serviceConfigReader);

        $service = $container->get(TestServiceWithBoolParam::class);
        $this->assertInstanceOf(TestServiceWithBoolParam::class, $service);
        $this->assertTrue($service->boolParam);
    }

    /**
     * @uses \Climbx\Service\Tests\TestServiceWithIntParam
     */
    public function testGetWithIntParam()
    {
        $configContainer = $this->createStub(ConfigContainer::class);
        $serviceConfigReader = $this->createStub(ServiceConfigReader::class);
        $serviceConfigReaderMap = [
            [TestServiceWithIntParam::class, 'intParam', ServiceConfigReader::PARAM_TYPE_INT, 1245]
        ];
        $serviceConfigReader->method('getParamValue')->willReturnMap($serviceConfigReaderMap);

        $container = new Container($configContainer, $serviceConfigReader);

        $service = $container->get(TestServiceWithIntParam::class);
        $this->assertInstanceOf(TestServiceWithIntParam::class, $service);
        $this->assertEquals(1245, $service->intParam);
    }

    /**
     * @uses \Climbx\Service\Tests\TestServiceWithStringParam
     */
    public function testGetWithStringParam()
    {
        $configContainer = $this->createStub(ConfigContainer::class);
        $serviceConfigReader = $this->createStub(ServiceConfigReader::class);
        $serviceConfigReaderMap = [
            [TestServiceWithStringParam::class, 'stringParam', ServiceConfigReader::PARAM_TYPE_STRING, 'Hello world!']
        ];
        $serviceConfigReader->method('getParamValue')->willReturnMap($serviceConfigReaderMap);

        $container = new Container($configContainer, $serviceConfigReader);

        $service = $container->get(TestServiceWithStringParam::class);
        $this->assertInstanceOf(TestServiceWithStringParam::class, $service);
        $this->assertEquals('Hello world!', $service->stringParam);
    }

    /**
     * @uses \Climbx\Service\Tests\TestServiceWithArrayParam
     */
    public function testGetWithArrayParam()
    {
        $configContainer = $this->createStub(ConfigContainer::class);
        $serviceConfigReader = $this->createStub(ServiceConfigReader::class);
        $serviceConfigReaderMap = [
            [TestServiceWithArrayParam::class, 'arrayParam', ServiceConfigReader::PARAM_TYPE_ARRAY, ['FOO' => 'BAR']]
        ];
        $serviceConfigReader->method('getParamValue')->willReturnMap($serviceConfigReaderMap);

        $container = new Container($configContainer, $serviceConfigReader);

        $service = $container->get(TestServiceWithArrayParam::class);
        $this->assertInstanceOf(TestServiceWithArrayParam::class, $service);
        $this->assertEquals(['FOO' => 'BAR'], $service->arrayParam);
    }

    /**
     * @uses \Climbx\Service\Tests\TestServiceWithConfigBagParam
     */
    public function testGetWithConfigBagParam()
    {
        $configContainer = $this->createStub(ConfigContainer::class);
        $serviceConfigReader = $this->createStub(ServiceConfigReader::class);
        $configBag = $this->createStub(ConfigBag::class);
        $serviceConfigReaderMap = [
            [
                TestServiceWithConfigBagParam::class,
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

        $service = $container->get(TestServiceWithConfigBagParam::class);
        $this->assertInstanceOf(TestServiceWithConfigBagParam::class, $service);
        $this->assertEquals($configBag, $service->configBagParam);
    }

    /**
     * @uses \Climbx\Service\Tests\TestServiceWithServiceParam
     * @uses \Climbx\Service\Tests\TestServiceDependency
     */
    public function testGetWithServiceParam()
    {
        $configContainer = $this->createStub(ConfigContainer::class);
        $serviceConfigReader = $this->createStub(ServiceConfigReader::class);

        $container = new Container($configContainer, $serviceConfigReader);

        $service = $container->get(TestServiceWithServiceParam::class);
        $this->assertInstanceOf(TestServiceWithServiceParam::class, $service);
        $this->assertInstanceOf(TestServiceDependency::class, $service->serviceParam);
    }

    /**
     * @uses \Climbx\Service\Tests\TestServiceWithInterfaceParam
     * @uses \Climbx\Service\Tests\TestServiceDependency
     */
    public function testGetWithInterfaceParam()
    {
        $configContainer = $this->createStub(ConfigContainer::class);
        $serviceConfigReader = $this->createStub(ServiceConfigReader::class);

        $container = new Container($configContainer, $serviceConfigReader);

        $service = $container->get(TestServiceWithInterfaceParam::class);
        $this->assertInstanceOf(TestServiceWithInterfaceParam::class, $service);
        $this->assertInstanceOf(TestServiceDependencyInterface::class, $service->interfaceParam);
        $this->assertInstanceOf(TestServiceDependency::class, $service->interfaceParam);
    }
}
