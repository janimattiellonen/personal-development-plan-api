<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClubRequest;
use App\Services\ClubService;

class ClubController
{
    public function create(ClubRequest $request, ClubService $clubService)
    {
        $data = $request->post();

        $clubId = $clubService->createClub($data);

        return response()->json(['status' => true, 'id' => $clubId], 201);
    }

    public function update(int $id, ClubRequest $request, ClubService $clubService)
    {
        $data = $request->post();

        $clubService->updateClub($id, $data);

        return response()->json(['status' => true, 'id' => $id], 201);
    }

    public function remove(int $id, ClubService $clubService) {
        $status = $clubService->removeClub($id);

        return response()->json(['status' => true, 'id' => $id], $status ? 200 : 404);
    }

    public function getClub(int $id, ClubService $clubService)
    {
        $club = $clubService->getClub($id);

        if (!$club) {
            return response()
                ->json(['status' => 'error', 'message' => 'Not found'], 404);
        }

        return response()->json(['status' => true, 'data' => $club], 200);
    }

    public function getClubs(ClubService $clubService)
    {
        $clubs = $clubService->getClubs();

        return response()->json(['status' => true, 'data' => $clubs], 200);
    }



}
