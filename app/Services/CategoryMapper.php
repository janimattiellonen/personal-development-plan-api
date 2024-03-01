<?php

namespace App\Services;

class CategoryMapper extends AbstractMapper
{
    protected static array $validFields = [
        self::OPERATION_CREATE => [
            'name',
            'is_active',
            'parent_category_id',
            'created_at',
            'updated_at',
        ],
        self::OPERATION_UPDATE => [
            'name',
            'is_active',
            'parent_category_id',
            'updated_at',
        ],
    ];

    public static function fromDTO(\stdClass $data): array
    {
        // TODO: Implement fromDTO() method.
        return [
            'id' => $data->id,
            'name' => $data->name,
            'isActive' => $data->is_active,
            'parentCategoryId' => $data->parent_category_id,
            'createdAt' => $data->created_at ?? null,
            'updatedAt' => $data->updated_at ?? null,
        ];
    }

    public static function toDTO(array $data): array
    {
        return  [
            'id' => $data['id'] ?? null,
            'name' => $data['name'],
            'is_active' => $data['isActive'],
            'parent_category_id' => $data['parentCategoryId'],
            'created_at' => $data['createdAt'] ?? null,
            'updated_at' => $data['updatedAt'] ?? null,
        ];
    }
}
