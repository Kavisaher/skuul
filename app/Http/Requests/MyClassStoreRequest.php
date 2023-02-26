<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;

class MyClassStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $classGroupId = $this->input('class_group_id');

        return [
            'name' => [
                'required',
                'max:255',
                //checks if there is a class with a name in class group
                Rule::unique('my_classes', 'name')->where(fn ($query) => $query->where('class_group_id', $classGroupId)),
            ],
            'class_group_id' => [
                'required',
                Rule::exists('classGroup', 'id')->where(function (Builder $query) {
                    return $query->where('school_id', auth()->user()->school->id);
                }),
            ],
        ];
    }

    public function messages()
    {
        return [
            'class_group_id.required' => 'Please select a class group',
        ];
    }
}
