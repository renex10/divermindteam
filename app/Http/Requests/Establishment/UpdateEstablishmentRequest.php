<?php


namespace App\Http\Requests\Establishment;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEstablishmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
       return true; // Cambiar de false a true
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
         return [
        'rbd' => [
            'required',
            'integer',
            Rule::unique('establishments', 'rbd')->ignore($this->establishment)
        ],
        'name' => 'required|string|max:255',
        'address' => 'required|string|max:500',
        'commune_id' => ['required', 'integer', Rule::exists('communes', 'id')],
        'pie_quota_max' => 'required|integer|min:0',
        'is_active' => 'required|boolean',
    ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->input('region_id')) {
                $commune = \App\Models\Commune::find($this->input('commune_id'));
                if ($commune && $commune->region_id != $this->input('region_id')) {
                    $validator->errors()->add('commune_id', 'La comuna no pertenece a la región seleccionada');
                }
            }
        });
    }
}
