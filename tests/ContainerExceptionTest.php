<?php

namespace Climbx\Service\Tests;

use Climbx\Config\ConfigContainer;
use Climbx\Service\Config\ServiceConfigReader;
use Climbx\Service\Container;
use Climbx\Service\Exception\InvalidArgumentException;
use Climbx\Service\Exception\NotFoundException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Climbx\Service\Container
 */
class ContainerExceptionTest extends TestCase
{
    public function testNotFound()
    {
        $configContainer = $this->createStub(ConfigContainer::class);
        $configReader = $this->createStub(ServiceConfigReader::class);

        $container = new Container($configContainer, $configReader);

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('The service "My\Fake\Service" do not exists');

        $container->get('My\Fake\Service');
    }

    /**
     * @uses \Climbx\Service\Tests\TestServiceWithUnknownTypeParam
     */
    public function testGetWithUnknownParam()
    {
        $configContainer = $this->createStub(ConfigContainer::class);
        $serviceConfigReader = $this->createStub(ServiceConfigReader::class);
        $container = new Container($configContainer, $serviceConfigReader);

        $this->expectException(InvalidArgumentException::class);
        $this->expectDeprecationMessage(
            'The argument "unknownParam" type has not been defined in service ' .
            '"Climbx\Service\Tests\TestServiceWithUnknownTypeParam"'
        );

        $container->get(TestServiceWithUnknownTypeParam::class);
    }

    /**
     * @uses \Climbx\Service\Tests\TestServiceWithInvalidParam
     */
    public function testGetWithInvalidParam()
    {
        $configContainer = $this->createStub(ConfigContainer::class);
        $serviceConfigReader = $this->createStub(ServiceConfigReader::class);
        $container = new Container($configContainer, $serviceConfigReader);

        $this->expectException(InvalidArgumentException::class);
        $this->expectDeprecationMessage(
            'Argument "invalidParam" has invalid type in service "Climbx\Service\Tests\TestServiceWithInvalidParam"'
        );

        $container->get(TestServiceWithInvalidParam::class);
    }

    /**
     * @uses \Climbx\Service\Tests\TestServiceWithMissingClassParam
     */
    public function testGetWithMissingClassParam()
    {
        $configContainer = $this->createStub(ConfigContainer::class);
        $serviceConfigReader = $this->createStub(ServiceConfigReader::class);
        $container = new Container($configContainer, $serviceConfigReader);

        $this->expectException(InvalidArgumentException::class);
        $this->expectDeprecationMessage(
            'Argument "missingClassParam" has invalid type in service ' .
            '"Climbx\Service\Tests\TestServiceWithMissingClassParam"'
        );

        $container->get(TestServiceWithMissingClassParam::class);
    }

    /**
     * @uses \Climbx\Service\Tests\TestServiceWithMissingClassFromInterfaceParam
     */
    public function testGetWithMissingClassFromInterfaceParam()
    {
        $configContainer = $this->createStub(ConfigContainer::class);
        $serviceConfigReader = $this->createStub(ServiceConfigReader::class);
        $container = new Container($configContainer, $serviceConfigReader);

        $this->expectException(InvalidArgumentException::class);
        $this->expectDeprecationMessage(
            'Argument "missingClassParam" has invalid type in service ' .
            '"Climbx\Service\Tests\TestServiceWithMissingClassFromInterfaceParam"'
        );

        $container->get(TestServiceWithMissingClassFromInterfaceParam::class);
    }
}
