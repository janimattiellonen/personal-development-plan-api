<?php

namespace App\Services;

use App\Repositories\ClubRepository;
use App\Services\Exception\RecordNotFoundException;
use Illuminate\Support\Facades\DB;

class ClubService
{
    public function __construct(protected ClubRepository $clubRepository) { }

    public function createClub(array $data): int
    {
        $now = new \DateTime();

        $club = ClubMapper::toDTO($data);
        $club['createdAt'] = $now;
        $club['updatedAt'] = $now;

        $clubSanitized = ClubMapper::sanitizeData($club, AbstractMapper::OPERATION_CREATE);

        return $this->clubRepository->createClub($clubSanitized);
    }

    /**
     * @param int $id
     * @param array $data
     * @return int
     * @throws RecordNotFoundException
     */
    public function updateClub(int $id, array $data): int
    {
        $now = new \DateTime();

        $club = ClubMapper::toDTO($data);
        $club['updatedAt'] = $now;

        $clubSanitized = ClubMapper::sanitizeData($club, AbstractMapper::OPERATION_UPDATE);

        return $this->clubRepository->updateClub($id, $clubSanitized);
    }

    public function removeClub(int $id): bool
    {
        return $this->clubRepository->removeClub($id);
    }

    public function getClub(int $id)
    {
        $club = $this->clubRepository->getClub($id);

        if (!$club) {
            throw new RecordNotFoundException(sprintf('Could not find club with the given id %d', $id));
        }

        return ClubMapper::fromDTO($club);
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

