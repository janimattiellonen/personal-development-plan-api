<?php

namespace App\Services;

class TrainingSessionMapper extends AbstractMapper
{
    protected static array $validFields = [
        self::OPERATION_CREATE => [
            'name',
            'is_active',
            'starts_at',
            'ends_at',
            'personal_plan_id',
            'created_at',
            'updated_at',
        ],
        self::OPERATION_UPDATE => [
            'name',
            'is_active',
            'starts_at',
            'ends_at',
            'personal_plan_id',
            'updated_at',
        ],
    ];
    public static function fromDTO(\stdClass $data): array
    {
        return [
            'id' => $data->id,
            'name' => $data->name,
            'startsAt' => $data->starts_at ?? null,
            'endsAt' => $data->ends_at ?? null,
            'personalPlanId' => $data->personal_plan_id ?? null,
            'isActive' => $data->is_active ?? null,
            'createdAt' => $data->created_at ?? null,
            'updatedAt' => $data->updated_at ?? null,
        ];
    }

    public static function toDTO(array $data): array
    {
        return [
            'id' => $data['id'] ?? null,
            'name' => $data['name'],
            'starts_at' => $data['startsAt'],
            'ends_at' => $data['endsAt'],
            'personal_plan_id' => $data['personalPlanId'],
            'is_active' => $data['isActive'],
            'created_at' => $data['createdAt'] ?? null,
            'updated_at' => $data['updatedAt'] ?? null,
        ];
    }
}
