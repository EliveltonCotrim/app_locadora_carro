<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MarcaRequest extends FormRequest
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
        if ($this->method() == 'PATCH' || $this->method() == 'PUT') {
            return [
                'nome' => 'required|unique:marcas,nome,' . $this->id . '|min:3',
                'imagem' => 'nullable|file|mimes:png'
            ];
        } else {

            return [
                'nome' => 'required|unique:marcas,nome,' . $this->id . '|min:3',
                'imagem' => 'required|file|mimes:png'
            ];
        }
    }
}
