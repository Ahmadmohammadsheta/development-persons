<?php

namespace App\Http\Controllers\Api;

use App\Models\Record;
use App\Models\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RecordRequest;
use App\Repository\RecordRepositoryInterface;
use App\Http\Resources\Records\RecordResource;
use App\Http\Traits\ResponseTrait as TraitResponseTrait;

class RecordController extends Controller
{
    use TraitResponseTrait;

    private $recordRepository;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(RecordRepositoryInterface $recordRepository)
    {
        $this->recordRepository = $recordRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $this->recordRepository->shetaForAllConditions($request->all(), array_key_exists('paginate', $request->all()) ? 'paginate' : 'get');
        $collection = RecordResource::collection($data);

        // two methods working
        return !array_key_exists('paginate', $request->all())
        ? $this->sendResponse($collection, "Index", 200, ['user' => auth()->user()])

        : $this->paginateResponse($collection, $data, "paginated", 200, ['user' => auth()->user()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RecordRequest $request)
    {
        $record = $this->recordRepository->create($request->validated());
        return $this->sendResponse(new RecordResource($record), "تم اتمام مهمة جديدة بنجاح", 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Record $record)
    {
        return $this->sendResponse(new RecordResource($this->recordRepository->find($record->id)), "بيانات الطالب", 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RecordRequest $request, Record $record)
    {
        return $this->sendResponse(new RecordResource($this->recordRepository->edit($record->id, $request->validated())), "تم تعديل بيانات الطالب", 202);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Record $record)
    {
        $this->recordRepository->delete($record->id);
        return $this->sendResponse("", "تم حذف الطالب", 200);
    }
}
