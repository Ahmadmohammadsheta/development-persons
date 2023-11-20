<?php

namespace App\Http\Controllers\Api;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StudentRequest;

use App\Repository\StudentRepositoryInterface;

use App\Http\Resources\Students\StudentResource;
use App\Http\Traits\ResponseTrait as TraitResponseTrait;

class StudentController extends Controller
{
    use TraitResponseTrait;

    private $studentRepository;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(StudentRepositoryInterface $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->studentRepository->forAllConditionsReturn($request->all(), StudentResource::class);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StudentRequest $request)
    {
        $student = $this->studentRepository->create($request->validated());
        return $this->sendResponse(new StudentResource($student), "تم تسجيل تصنيفا جديدا", 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        return $this->sendResponse(new StudentResource($this->studentRepository->find($student->id)), "بيانات الطالب", 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StudentRequest $request, Student $student)
    {
        return $this->sendResponse(new StudentResource($this->studentRepository->edit($student->id, $request->validated())), "تم تعديل بيانات الطالب", 202);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        if (!count($student->units)) {
            $this->studentRepository->delete($student->id);
            return $this->sendResponse("", "تم حذف الطالب", 200);
        }else{
            return $this->sendError("لا يمكن حذف الطالب", [], 422);

        }
    }
}
