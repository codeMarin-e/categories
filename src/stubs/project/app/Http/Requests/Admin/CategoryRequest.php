<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use App\Models\Category;
use Illuminate\Validation\ValidationException;

class CategoryRequest extends FormRequest
{

    private $mergeReturn = [];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $chCategory = request()->route('chCategory');
        $rules = array_merge([
            'parent_id' => [ function($attribute, $value, $fail) {
                if(!$value) return; //Root
                if( !($parent = Category::find($value)) ) {
                    return $fail(trans('admin/categories/validation.parent_id.not_found'));
                }
            }],
            'add.name' => 'required|max:255',
            'add.description' => 'nullable',
            'active' => 'boolean',
        ], \App\Models\Uri::validationRules('category', $chCategory));

        // @HOOK_REQUEST_RULES

        return $rules;
    }

    public function messages() {
        $return = array_merge(
            Arr::dot((array)trans('admin/uriable/uriable.validation')),
            Arr::dot((array)trans('admin/categories/validation'))
        );

        // @HOOK_REQUEST_MESSAGES

        return $return;
    }

    public function validationData() {
        $inputBag = 'category';
        $this->errorBag = $inputBag;
        $inputs = $this->all();
        if(!isset($inputs[$inputBag])) {
            throw new ValidationException(trans('admin/categories/validation.no_inputs') );
        }
        $inputs[$inputBag]['active'] = isset($inputs[$inputBag]['active']);

        // @HOOK_REQUEST_PREPARE

        $this->replace($inputs);
        request()->replace($inputs); //global request should be replaced, too
        return $inputs[$inputBag];
    }

    public function validated($key = null, $default = null) {
        $validatedData = parent::validated($key, $default);

        // @HOOK_REQUEST_VALIDATED

        if(is_null($key)) {
            \App\Models\Uri::validated($validatedData);

            // @HOOK_REQUEST_AFTER_VALIDATED

            return array_merge($validatedData, $this->mergeReturn);
        }

        // @HOOK_REQUEST_AFTER_VALIDATED_KEY

        return $validatedData;
    }
}
