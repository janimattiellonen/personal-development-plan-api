<?php

namespace App\Http\Controllers;

use App\Services\ClubService;
use App\Services\NotFoundException;
use Illuminate\Http\Request;

class ClubController
{
    public function create(Request $request, ClubService $clubService)
    {
        $data = $request->post();

        $clubId = $clubService->createClub($data);

        return response()->json(['status' => true, 'id' => $clubId], 201);
    }

    public function update(int $id, Request $request, ClubService $clubService)
    {
       // return response()
       //     ->json(['status' => 'error', 'message' => 'Update failed'], 400);

        $data = $request->post();

        $clubService->updateClub($id, $data);

        return response()->json(['status' => true, 'id' => $id], 201);
    }

    public function remove(int $id, ClubService $clubService) {
        $status = $clubService->removeClub($id);

        return response()->json(['status' => $status, 'id' => $id], $status ? 200 : 404);
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
