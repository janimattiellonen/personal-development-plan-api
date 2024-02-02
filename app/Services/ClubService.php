<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;

class ClubService
{
    public function createClub(array $data): int
    {
        $now = new \DateTime();

        $club = ClubMapper::toDTO($data);
        $club['createdAt'] = $now;
        $club['updatedAt'] = $now;

        $clubSanitized = ClubMapper::sanitizeData($club, ClubMapper::OPERATION_CREATE);

        return DB::table('clubs')->insertGetId(
            $clubSanitized
        );
    }

    /**
     * @param int $id
     * @param array $data
     * @return int
     * @throws NotFoundException
     */
    public function updateClub(int $id, array $data): int
    {
        $now = new \DateTime();

        $club = ClubMapper::toDTO($data);
        $club['updatedAt'] = $now;

        $clubSanitized = ClubMapper::sanitizeData($club, ClubMapper::OPERATION_UPDATE);

        $result = DB::table('clubs')
            ->where('id', $id)
            ->update($clubSanitized);

        if ($result === 0) {
            throw new NotFoundException(sprintf('Could not update club with the given id %d, as no club with that id exists', $id));
        }

        return $result;
    }

    public function removeClub(int $id): bool
    {
        return DB::table('clubs')->delete($id) === 1;
    }

    public function getClub(int $id)
    {
        return ClubMapper::fromDTO(DB::table('clubs')->orderBy('name', 'ASC')->find($id, ['id', 'name', 'is_active']));
    }

    public function getClubs()
    {
        return array_map(
            function ($item) {
                return ClubMapper::fromDTO($item);
            },
            DB::table('clubs')->orderBy('name', 'ASC')->get(['id', 'name', 'is_active', 'created_at', 'updated_at'])->all()
        );
    }
}

