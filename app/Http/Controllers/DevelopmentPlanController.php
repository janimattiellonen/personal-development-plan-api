<?php

namespace App\Http\Controllers;

use App\Services\DevelopmentPlanService;
use Illuminate\Http\Request;
use \DateTime;

class DevelopmentPlanController
{
    public function create(Request $request, DevelopmentPlanService $service)
    {
        $data = $request->post();

        $personalPlanId = $service->createPersonalPlan($data);

        return response()->json(['status' => true, 'personal_plan_id' => $personalPlanId], 201);
    }

    public function update(int $id, Request $request, DevelopmentPlanService $service)
    {
        $data = $request->post();

        $service->updateDevelopmentPlan($id, $data);

        return response()->json(['status' => true], 201);
    }

    public function getDevelopmentPlan(int $id, DevelopmentPlanService $service)
    {
        $developmentPlan = $service->getDevelopmentPlan($id);
        //echo "<pre>";
        //print_r($developmentPlan);die;
        return response()->json(['status' => true, 'data' => $developmentPlan], 200);
    }

    public function getDevelopmentPlans(DevelopmentPlanService $service)
    {
        $developmentPlans = $service->getDevelopmentPlans();
        // die($developmentPlans);
        return response()->json(['status' => true, 'data' => $developmentPlans], 200);
    }
}
