<?php

namespace App\Http\Requests\Establishment;

use Illuminate\Foundation\Http\FormRequest;

class StoreEstablishmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // ✅ CAMBIAR A true para permitir la creación
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'address' => 'required|string|max:200',
            'commune_id' => 'required|exists:communes,id',
            'pie_quota_max' => 'required|integer|min:0'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del establecimiento es obligatorio.',
            'address.required' => 'La dirección es obligatoria.',
            'commune_id.required' => 'Debe seleccionar una comuna.',
            'commune_id.exists' => 'La comuna seleccionada no es válida.',
            'pie_quota_max.required' => 'La cuota máxima PIE es obligatoria.',
            'pie_quota_max.integer' => 'La cuota máxima PIE debe ser un número entero.',
            'pie_quota_max.min' => 'La cuota máxima PIE no puede ser negativa.',
        ];
    }
}