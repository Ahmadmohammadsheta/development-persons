<?php

namespace App\Http\Requests;

use App\Models\Record;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RecordRequest extends FormRequest
{
    /**
     * store validations
     */
    private function storeRequest()
    {
        return [
            'student_id' => 'required',
            'mission_id' => [
                'required',
                'integer',

                function ($attribute, $value, $fail) {
                    $existingMission = Record::where('student_id', $this->student_id)->where('mission_id', $this->mission_id)->whereDate('created_at', Carbon::today())->first();

                    if ($existingMission) {
                        $fail('المهمة مسجلة لهذا الطالب');
                    }
                },
            ],
            'points' => 'nullable',
        ];
    }

    /**
     * update validations
     */
    private function updateRequest()
    {
        return [
            'student_id' => 'required',
            'mission_id' => 'required',
            'points' => 'nullable',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return request()->method() == 'PUT' ? $this->updateRequest() : $this->storeRequest();
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages()
    {
        return [
            'student_id.required' => 'الطالب مطلوب',
            'mission_id.required' => 'المهمة مطلوبة',
        ];
    }
}

