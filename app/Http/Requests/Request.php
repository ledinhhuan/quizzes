<?php
namespace App\Http\Requests;

use \Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Validation\Validator;

class Request extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status_code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message' => trans('messages.errors.input'),
                'errors' => $validator->errors()->first()
            ], Response::HTTP_UNPROCESSABLE_ENTITY)
        );
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
     * @return array
     */
    public function rules()
    {
        return [];
    }
}