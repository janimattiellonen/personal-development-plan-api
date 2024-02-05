<?php

namespace App\Services;

class StudentMapper extends AbstractMapper
{
    protected static array $validFields = [
        self::OPERATION_CREATE => [
            'name',
            'first_name',
            'last_name',
            'email',
            'password',
            'type',
            'user_role',
            'created_at',
            'updated_at',
        ],
        self::OPERATION_UPDATE => [
            'name',
            'first_name',
            'last_name',
            'email',
            'password',
            'type',
            'user_role',
            'updated_at',
        ],
    ];

    public static function fromDTO(\stdClass $data): array
    {
        return [
            'id' => $data->id,
            'name' => $data->name,
            'firstName' => $data->first_name,
            'lastName' => $data->last_name,
            'username' => $data->email,
            // password not currently returned as to not expose sensitive information
            'type' => $data->type,
            'userRole' => $data->user_role,
            'createdAt' => $data->created_at ?? null,
            'updatedAt' => $data->updated_at ?? null,
        ];
    }

    public static function toDTO(array $data): array
    {
        return [
            'id' => $data['id'] ?? null,
            'name' => $data['name'],
            'first_name' => $data['firstName'],
            'last_name' => $data['lastName'],
            'email' => $data['username'],
            'password' => $data['password'],
            'type' => $data['type'],
            'user_role' => $data['userRole'],
            'created_at' => $data['createdAt'] ?? null,
            'updated_at' => $data['updatedAt'] ?? null,
        ];
    }
}
