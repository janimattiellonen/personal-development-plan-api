<?php

namespace App\Repositories;

use App\Services\Exception\RecordNotFoundException;
use App\Services\Exception\RecordAlreadyExistsException;
use Illuminate\Support\Facades\DB;

class ClubRepository
{
    public function createClub(array $data): int
    {
        $foundClub = $this->findClubByName($data['name']);

        if ($foundClub) {
            throw new RecordAlreadyExistsException('Could not create club with the given name, as a different club with the same name already exists');
        }

        return DB::table('clubs')->insertGetId($data);
    }

    public function updateClub(int $id, array $data): int
    {
        $club = $this->findClubById($id);

        if (!$club) {
            throw new RecordNotFoundException(sprintf('Could not update club with the given id %d, as no club with that id exists', $id));
        }

        $foundClub = $this->findClubByName($data['name']);

        if ($foundClub && $foundClub->id !== $id) {
            throw new RecordAlreadyExistsException('Could not update club with the given name, as a different club with the same name already exists');
        }

        return DB::table('clubs')
            ->where('id', $id)
            ->update($data);
    }

    public function findClubById(int $id): ?\stdClass
    {
        return DB::table('clubs')->find($id);
    }

    public function findClubByName(string $name): ?\stdClass
    {
        return DB::table('clubs')
            ->where('name', '=', $name)->first();
    }

    public function removeClub(int $id): bool
    {
        return DB::table('clubs')->delete($id) === 1;
    }

    public function getClub(int $id): ?\stdClass
    {
        return DB::table('clubs')
            ->orderBy('name', 'ASC')
            ->find($id, ['id', 'name', 'is_active']);
    }
}
