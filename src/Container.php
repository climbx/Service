<?php

namespace Climbx\Service;

use Climbx\Config\Bag\ConfigBagInterface;
use Climbx\Config\ConfigContainerInterface;
use Climbx\Config\Exception\ContainerExceptionInterface as ConfigContainerExceptionInterface;
use Climbx\Config\Exception\NotFoundExceptionInterface as ConfigNotFoundExceptionInterface;
use Climbx\Service\Config\ServiceConfigReader;
use Climbx\Service\Exception\ContainerExceptionInterface;
use Climbx\Service\Exception\InvalidArgumentException;
use Climbx\Service\Exception\NotFoundException;
use Climbx\Service\Exception\ServiceConfigNotFoundException;
use Climbx\Service\Exception\ServiceConfigParameterNotFoundException;
use ReflectionParameter;

class Container implements ContainerInterface
{
    /**
     * @var array
     */
    private array $services = [];

    public function __construct(
        private ConfigContainerInterface $configContainer,
        private ServiceConfigReader $serviceConfigReader,
    ) {
    }

    public function get(string $id): object
    {
        if (array_key_exists($id, $this->services)) {
            return $this->services[$id];
        }

        if (!$this->has($id)) {
            throw new NotFoundException(sprintf('The service "%s" do not exists', $id));
        }

        $parameters = $this->getServiceParametersInfo($id);

        if ($parameters === false) {
            $this->services[$id] = new $id();

            return $this->services[$id];
        }

        $this->services[$id] = new $id(...$this->getServiceParameters($id, $parameters));

        return $this->services[$id];
    }

    public function has(string $id): bool
    {
        if (array_key_exists($id, $this->services)) {
            return true;
        }

        return class_exists($id);
    }

    /**
     * Returns service parameters infos from ReflectionMethod
     *
     * @param string $class
     *
     * @return ReflectionParameter[]|false
     */
    private function getServiceParametersInfo(string $class): array | false
    {
        try {
            $constructReflection = new \ReflectionMethod($class, '__construct');
        } catch (\ReflectionException) {
            return false;
        }

        $parameters = $constructReflection->getParameters();

        if (empty($parameters)) {
            return false;
        }

        return $parameters;
    }

    /**
     * Returns an array of a service parameters from its informations.
     *
     * @param string                $id
     * @param ReflectionParameter[] $reflectionParameters
     *
     * @return array
     *
     * @throws ServiceConfigNotFoundException
     * @throws ServiceConfigParameterNotFoundException
     * @throws ContainerExceptionInterface
     * @throws ConfigContainerExceptionInterface
     * @throws ConfigNotFoundExceptionInterface
     */
    private function getServiceParameters(string $id, array $reflectionParameters): array
    {
        $params = [];

        foreach ($reflectionParameters as $param) {
            if (!$param->hasType()) {
                throw new InvalidArgumentException(
                    sprintf('The argument "%s" type has not been defined in service "%s"', $param->getName(), $id,)
                );
            }

            $paramType = $param->getType()->getName();

            $params[$param->getPosition()] = match ($paramType) {
                ServiceConfigReader::PARAM_TYPE_BOOL,
                ServiceConfigReader::PARAM_TYPE_INT,
                ServiceConfigReader::PARAM_TYPE_STRING,
                ServiceConfigReader::PARAM_TYPE_ARRAY =>
                    $this->serviceConfigReader->getParamValue($id, $param->getName(), $paramType),
                ConfigBagInterface::class =>
                    $this->configContainer->get(
                        (string) $this->serviceConfigReader->getParamValue(
                            $id,
                            $param->getName(),
                            ServiceConfigReader::PARAM_TYPE_STRING
                        )
                    ),
                default =>
                    ($this->has($this->getClassFromInterface($paramType))) ?
                        $this->get($this->getClassFromInterface($paramType)) :
                        throw new InvalidArgumentException(
                            sprintf('Argument "%s" has invalid type in service "%s"', $param->getName(), $id,)
                        )
            };
        }

        return $params;
    }

    /**
     * @param $serviceName
     *
     * @return string
     */
    private function getClassFromInterface($serviceName): string
    {
        return (str_ends_with($serviceName, 'Interface')) ? substr($serviceName, 0, -9) : $serviceName;
    }
}
