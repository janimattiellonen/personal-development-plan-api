<?php

namespace App\Services;

class ProfileMapper extends AbstractMapper
{
    protected static array $validFields = [
        self::OPERATION_CREATE => [
            'age',
            'created_at',
            'updated_at',
        ],
        self::OPERATION_UPDATE => [
            'age',
            'updated_at',
        ],
    ];

    public static function fromDTO(\stdClass $data): array
    {
        return [
            'id' => $data->id,
            'age' => $data->age,
            'createdAt' => $data->created_at ?? null,
            'updatedAt' => $data->updated_at ?? null,
        ];
    }

    public static function toDTO(array $data): array
    {
        return [
            'id' => $data['id'] ?? null,
            'age' => $data['age'],
            'created_at' => $data['createdAt'] ?? null,
            'updated_at' => $data['updatedAt'] ?? null,
        ];
    }
}
