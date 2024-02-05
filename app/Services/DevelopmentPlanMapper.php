<?php

namespace App\Services;

class DevelopmentPlanMapper extends AbstractMapper
{
    protected static array $validFields = [
        self::OPERATION_CREATE => [
            'name',
            'is_active',
            'starts_at',
            'ends_at',
            'goals',
            'student_id',
            'instructor_id',
            'created_at',
            'updated_at',
        ],
        self::OPERATION_UPDATE => [
            'name',
            'is_active',
            'starts_at',
            'ends_at',
            'goals',
            'student_id',
            'instructor_id',
            'is_active',
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
            'goals' => $data->goals,
            'isActive' => $data->is_active,
            'createdAt' => $data->created_at ?? null,
            'updatedAt' => $data->updated_at ?? null,
            'instructor' => UserMapper::fromDTO((object)$data->instructor),
            'student' => UserMapper::fromDTO((object)$data->student),
            'trainingSessions' => array_map(
                function ($trainingSession) {
                    return TrainingSessionMapper::fromDTO((object)$trainingSession);
                },
                $data->training_sessions ?? []
            )
        ];
    }

    public static function toDTO(array $data): array
    {
        return [
            'name' => $data['name'],
            'starts_at' => $data['startsAt'],
            'ends_at' => $data['endsAt'],
            'goals' => $data['goals'],
            'student_id' => $data['studentId'],
            'instructor_id' => $data['instructorId'],
            'is_active' => $data['isActive'] ?? null,
            'updated_at' => $data['updatedAt'] ?? null,
        ];
    }
}

/**
`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
`name` varchar(255) NOT NULL,
`is_active` tinyint(1) DEFAULT NULL,
`starts_at` datetime NOT NULL,
`ends_at` datetime DEFAULT NULL,
`goals` varchar(255) DEFAULT NULL,
`student_id` bigint(20) unsigned NOT NULL,
`instructor_id` bigint(20) unsigned NOT NULL,
`created_at` timestamp NULL DEFAULT NULL,
`updated_at` timestamp NULL DEFAULT NULL,
 */
