<?php

namespace App\Services;

class UserMapper extends AbstractMapper
{
    public static function fromDTO(\stdClass $data): array
    {
        return [
            'id' => $data->id,
            'name' => $data->name ?? null,
            'firstName' => $data->first_name,
            'lastName' => $data->last_name,
            'email' => $data->email ?? null,
            'type' => $data->type ?? null,
            'userRole' => $data->user_role ?? null,
        ];
    }

    public static function toDTO(array $data): array
    {
        return [];
    }
}
