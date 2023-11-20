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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        //
    }
}
