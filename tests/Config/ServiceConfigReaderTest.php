<?php

namespace Climbx\Service\Tests\Config;

use Climbx\Bag\Exception\NotFoundException as BagNotFoundException;
use Climbx\Config\Bag\ConfigBag;
use Climbx\Service\Config\ServiceConfigReader;
use Climbx\Service\Exception\InvalidArgumentException;
use Climbx\Service\Exception\ServiceConfigNotFoundException;
use Climbx\Service\Exception\ServiceConfigParameterNotFoundException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Climbx\Service\Config\ServiceConfigReader
 */
class ServiceConfigReaderTest extends TestCase
{
    public function testGetNotFoundServiceId()
    {
        $servicesConfigStub = $this->createStub(ConfigBag::class);
        $servicesConfigStub->method('get')->willThrowException(new BagNotFoundException());

        $reader = new ServiceConfigReader($servicesConfigStub);

        $this->expectException(ServiceConfigNotFoundException::class);
        $this->expectExceptionMessage(
            'The service "My\Missing\Service" configuration is missing in services config file.'
        );

        $reader->getParamValue(
            'My\Missing\Service', 'myMissingParam', ServiceConfigReader::PARAM_TYPE_STRING
        );
    }

    public function testGetNotFoundParam()
    {
        $servicesConfigStub = $this->createStub(ConfigBag::class);
        $servicesConfigStub->method('get')->willReturn([]);

        $reader = new ServiceConfigReader($servicesConfigStub);

        $this->expectException(ServiceConfigParameterNotFoundException::class);
        $this->expectExceptionMessage(
            'The parameter "missingParam" is required in service "My\Missing\Service" ' .
            'and has not been declared in services config file'
        );

        $reader->getParamValue(
            'My\Missing\Service', 'missingParam', ServiceConfigReader::PARAM_TYPE_STRING
        );
    }

    /**
     * @dataProvider getParamValueProvider
     */
    public function testGetParamValue(array $config, string $type)
    {
        $servicesConfigStub = $this->createStub(ConfigBag::class);
        $servicesConfigStub->method('get')->willReturn($config);
        $key = key($config);

        $reader = new ServiceConfigReader($servicesConfigStub);

        $value = $reader->getParamValue('My\Service', $key, $type);

        $this->assertEquals($config[$key], $value);
    }

    /**
     * @return array[]
     */
    public function getParamValueProvider(): array
    {
        return [
            [['FOO' => true], ServiceConfigReader::PARAM_TYPE_BOOL],
            [['FOO' => 1234], ServiceConfigReader::PARAM_TYPE_INT],
            [['FOO' => 'Hello world'], ServiceConfigReader::PARAM_TYPE_STRING],
            [['FOO' => ''], ServiceConfigReader::PARAM_TYPE_STRING],
            [['FOO' => ['BAR' => 'BAZ']], ServiceConfigReader::PARAM_TYPE_ARRAY],
        ];
    }

    /**
     * @dataProvider badParamTypeProvider
     */
    public function testGetBadParamValueType(array $config, string $type)
    {
        $servicesConfigStub = $this->createStub(ConfigBag::class);
        $servicesConfigStub->method('get')->willReturn($config);
        $key = key($config);

        $reader = new ServiceConfigReader($servicesConfigStub);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf(
            'The parameter "%s" value has not valid type in "My\Service" service config file.', $key
        ));

        $reader->getParamValue('My\Service', $key, $type);

    }

    /**
     * @return array[]
     */
    public function badParamTypeProvider(): array
    {
        return [
            [['FOO' => true], ServiceConfigReader::PARAM_TYPE_INT],
            [['FOO' => true], ServiceConfigReader::PARAM_TYPE_STRING],
            [['FOO' => true], ServiceConfigReader::PARAM_TYPE_ARRAY],

            [['FOO' => 123], ServiceConfigReader::PARAM_TYPE_BOOL],
            [['FOO' => 123], ServiceConfigReader::PARAM_TYPE_STRING],
            [['FOO' => 123], ServiceConfigReader::PARAM_TYPE_ARRAY],

            [['FOO' => 'Hello world'], ServiceConfigReader::PARAM_TYPE_BOOL],
            [['FOO' => 'Hello world'], ServiceConfigReader::PARAM_TYPE_INT],
            [['FOO' => '1245'], ServiceConfigReader::PARAM_TYPE_INT],
            [['FOO' => 'Hello world'], ServiceConfigReader::PARAM_TYPE_ARRAY],

            [['FOO' => ['BAR']], ServiceConfigReader::PARAM_TYPE_BOOL],
            [['FOO' => ['BAR']], ServiceConfigReader::PARAM_TYPE_INT],
            [['FOO' => ['BAR']], ServiceConfigReader::PARAM_TYPE_STRING],
        ];
    }
}
