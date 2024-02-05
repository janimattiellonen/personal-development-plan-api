<?php

namespace App\Services;

abstract class AbstractMapper
{
    const OPERATION_CREATE = 1;
    const OPERATION_UPDATE = 2;

    protected static array $validFields = [
        self::OPERATION_CREATE => [

        ],
        self::OPERATION_UPDATE => [

        ],
    ];

    /**
     * @param array $data
     * @param int $operation
     * @return array
     * @throws MapperException
     */
    public static function sanitizeData(array $data, int $operation): array
    {
        static::assertValidOperation($operation);

        $validFields = static::$validFields[$operation];

        return array_filter(
            $data,
            function ($item, $key) use ($validFields) {
                return in_array($key, $validFields);
            },
            ARRAY_FILTER_USE_BOTH
        );
    }

    public abstract static function fromDTO(\stdClass $data): array;

    public abstract static function toDTO(array $data): array;

    protected static function assertValidOperation(int $operation): void
    {
        if (!in_array($operation, [static::OPERATION_CREATE, static::OPERATION_UPDATE])) {
            throw new MapperException(sprintf('Invalid operation %d. Must be one of %s', $operation, join(', ', [self::OPERATION_CREATE, self::OPERATION_UPDATE])));
        }
    }

}
