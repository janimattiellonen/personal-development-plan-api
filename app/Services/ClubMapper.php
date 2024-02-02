<?php

namespace App\Services;

class ClubMapper
{
    const OPERATION_CREATE = 1;
    const OPERATION_UPDATE = 2;

    protected static array $validFields = [
        self::OPERATION_CREATE => [
            'name',
            'is_ctive',
            'created_at',
            'updated_at',
        ],
        self::OPERATION_UPDATE => [
            'name',
            'is_active',
            'updated_at',
        ],
    ];

    /**
     * @param int $operation
     * @return void
     * @throws \Exception
     */
    protected static function assertValidOperation(int $operation): void
    {
        if (!in_array($operation, [self::OPERATION_CREATE, self::OPERATION_UPDATE])) {
            throw new \Exception(sprintf('Invalid operation %d. Must be one of %s', $operation, join(', ', [self::OPERATION_CREATE, self::OPERATION_UPDATE])));
        }
    }

    /**
     * @param array $data
     * @param int $operation
     * @return array
     * @throws \Exception
     */
    public static function sanitizeData(array $data, int $operation): array
    {
        self::assertValidOperation($operation);

        $validFields = self::$validFields[$operation];

        return array_filter(
            $data,
            function ($item, $key) use ($validFields) {
                return in_array($key, $validFields);
            },
            ARRAY_FILTER_USE_BOTH
        );
    }

    public static function fromDTO(\stdClass $data)
    {
        return [
            'id' => $data->id,
            'name' => $data->name,
            'isActive' => $data->is_active,
            'createdAt' => $data->created_at ?? null,
            'updatedAt' => $data->updated_at ?? null,
        ];
    }

    public static function toDTO(array $data)
    {
        return [
            'id' => $data['id'] ?? null,
            'name' => $data['name'],
            'created_at' => $data['createdAt'] ?? null,
            'updated_at' => $data['updatedAt'] ?? null,
        ];
    }
}
