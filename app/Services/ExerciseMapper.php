<?php

namespace App\Services;

class ExerciseMapper extends AbstractMapper
{
    protected static array $validFields = [
        self::OPERATION_CREATE => [
            'name',
            'description',
            'instructions',
            'url',
            'youtube_url',
            'is_active',
            'created_at',
            'updated_at',
        ],
        self::OPERATION_UPDATE => [
            'name',
            'description',
            'instructions',
            'url',
            'youtube_url',
            'is_active',
            'is_active',
            'updated_at',
        ],
    ];

    public static function fromDTO(\stdClass $data): array
    {
        return [
            'id' => $data->id,
            'name' => $data->name,
            'description' => $data->description,
            'instructions' => $data->instructions,
            'url' => $data->url,
            'youtubeUrl' => $data->youtube_url,
            'isActive' => $data->is_active,
            'createdAt' => $data->created_at ?? null,
            'updatedAt' => $data->updated_at ?? null,
        ];
    }

    public static function toDTO(array $data): array
    {
        return [
            'id' => $data['id'] ?? null,
            'name' => $data['name'],
            'description' => $data['description'],
            'instructions' => $data['instructions'],
            'url' => $data['url'],
            'youtube_url' => $data['youtubeUrl'],
            'is_active' => $data['isActive'],
            'created_at' => $data['createdAt'] ?? null,
            'updated_at' => $data['updatedAt'] ?? null,
        ];
    }
}
