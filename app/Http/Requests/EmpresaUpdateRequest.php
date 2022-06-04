<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class EmpresaUpdateRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'razao' => 'max:255',
            'nome' => 'max:255',
            'cnpj' => 'unique:empresas|max:14',
            'logo' => 'image|mimes:jpeg,png,jpg,svg|max:2048'
        ];
    }

    public function messages()
    {
        return [
            'razao.max' => 'A Razão Social ultrapassou o limite de caracteres (Máximo de 255 caracteres);',
            'nome.max' => 'O Nome Fantasia ultrapassou o limite de caracteres (Máximo de 255 caracteres);',
            'cnpj.max' => 'O CNPJ ultrapassou a quantidade máxima de caracteres (14 caracteres);',
            'cnpj.unique' => 'O CNPJ já existe;',
            'logo.image' => 'O arquivo não é uma imagem;',
            'logo.mimes' => 'A imagem deve ser: jpeg, png, jpg ou svg;',
            'logo.max' => 'A imagem ultrapassou o limite de upload (Máximo de 2MB);'
        ];
    }

}
