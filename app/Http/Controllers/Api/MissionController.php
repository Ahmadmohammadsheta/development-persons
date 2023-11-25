<?php

namespace App\Http\Controllers\Api;

use App\Models\Mission;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests\MissionRequest;
use App\Repository\MissionRepositoryInterface;
use App\Http\Resources\Missions\MissionResource;
use App\Http\Traits\ResponseTrait as TraitResponseTrait;

class MissionController extends Controller
{
    use TraitResponseTrait;

    private $missionRepository;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(MissionRepositoryInterface $missionRepository)
    {
        $this->missionRepository = $missionRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // two methods working
        return $this->sendResponse(MissionResource::collection($this->missionRepository->forAllConditions($request->all())->get()), "كل المهمات", 201);
        return $this->missionRepository->forAllConditionsReturn($request->all(), MissionResource::class);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MissionRequest $request)
    {
        $mission = $this->missionRepository->create($request->validated());
        return $this->sendResponse(new MissionResource($mission), "تم تسجيل مهمات اليوم", 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Mission $mission)
    {
        return $this->sendResponse(new MissionResource($this->missionRepository->find($mission->id)), "بيانات المهمة", 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MissionRequest $request, Mission $mission)
    {
        return $this->sendResponse(new MissionResource($this->missionRepository->edit($mission->id, $request->validated())), "تم تعديل بيانات الطالب", 202);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->missionRepository->delete($id);
        return $this->sendResponse("", "تم حذف الطالب", 200);
    }
}
