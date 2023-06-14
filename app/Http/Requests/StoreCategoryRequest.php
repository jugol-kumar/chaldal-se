<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{
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
        return [
            'title' => ['required', 'unique:categories'],
//            'slug' => ['required', 'unique:categories'],
            'parent_id' => ['nullable', 'integer'],
            'summery' => ['nullable'],
            'icon' => ['nullable'],
            'banner' => ['nullable'],
            'featured' => ['boolean'],
            'top' => ['boolean'],
//            'type' => ['string', Rule::in(['physical', 'digital'])],
            'order_level' => ['integer'],
            'status' => ['string', Rule::in(['published', 'cancel', 'pending', 'deleted'])],
        ];
    }
}
