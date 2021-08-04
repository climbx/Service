<?php

namespace Climbx\Service\Config;

use Climbx\Bag\Exception\NotFoundExceptionInterface as BagNotFoundExceptionInterface;
use Climbx\Config\Bag\ConfigBagInterface;
use Climbx\Config\Exception\NotFoundExceptionInterface as ConfigNotFoundExceptionInterface;
use Climbx\Service\Exception\InvalidArgumentException;
use Climbx\Service\Exception\ServiceConfigNotFoundException;
use Climbx\Service\Exception\ServiceConfigParameterNotFoundException;

class ServiceConfigReader
{
    public const PARAM_TYPE_BOOL = 'bool';
    public const PARAM_TYPE_INT = 'int';
    public const PARAM_TYPE_STRING = 'string';
    public const PARAM_TYPE_ARRAY = 'array';

    public function __construct(
        private ConfigBagInterface $servicesConfig
    ) {
    }

    /**
     * @param string $id
     * @param string $paramName
     * @param string $paramType
     *
     * @return bool|string|int|array
     *
     * @throws ServiceConfigNotFoundException
     * @throws ServiceConfigParameterNotFoundException
     * @throws ConfigNotFoundExceptionInterface
     */
    public function getParamValue(
        string $id,
        string $paramName,
        string $paramType
    ): bool | string | int | array {
        try {
            $serviceConfig = $this->servicesConfig->get($id);
        } catch (BagNotFoundExceptionInterface) {
            throw new ServiceConfigNotFoundException(sprintf(
                'The service "%s" configuration is missing in services config file.', $id
            ));
        }

        if (array_key_exists($paramName, $serviceConfig)) {
            return ($this->isValidConfigParamType($paramType, $serviceConfig[$paramName])) ?
                $serviceConfig[$paramName] :
                throw new InvalidArgumentException(sprintf(
                    'The parameter "%s" value has not valid type in "%s" service config file.', $paramName, $id
                ));
        }

        throw new ServiceConfigParameterNotFoundException(sprintf(
            'The parameter "%s" is required in service "%s" and has not been declared in services config file',
            $paramName, $id
        ));
    }

    /**
     * @param string                $paramType
     * @param bool|int|string|array $paramValue
     *
     * @return bool
     */
    private function isValidConfigParamType(string $paramType, bool | int | string | array $paramValue): bool
    {
        return match ($paramType) {
            self::PARAM_TYPE_BOOL => is_bool($paramValue),
            self::PARAM_TYPE_INT => is_int($paramValue),
            self::PARAM_TYPE_STRING => is_string($paramValue),
            self::PARAM_TYPE_ARRAY => is_array($paramValue),
            default => false,
        };
    }
}
