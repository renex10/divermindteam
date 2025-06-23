<?php

namespace App\Http\Requests\Student;
use Illuminate\Support\Facades\Log;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class StoreStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // La autorización se manejará posteriormente con políticas
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
         Log::debug('Validating:', $this->all());

        $establishmentId = Auth::user()->establishment_id;

        return [
            // Nuevo usuario (estudiante)
            'new_user.name' => 'required|string|max:255',
            'new_user.last_name' => 'required|string|max:255',
            'new_user.email' => 'required|email|unique:users,email',
            'new_user.password' => 'required|string|min:8',

            // Datos del estudiante
            'birth_date' => 'required|date',
            'course_id' => [
                'required',
                Rule::exists('courses', 'id')->where('establishment_id', $establishmentId)
            ],
            'diagnosis' => 'nullable|string',
            'need_type' => ['required', Rule::in(['permanent', 'temporary'])],
            'priority' => 'required|integer|min:1|max:3',
            'special_needs' => 'nullable|string',

            // Asignación de especialista
            'assigned_specialist_id' => [
                'required',
                Rule::exists('professionals', 'id')->where('establishment_id', $establishmentId)
            ],
            'evaluation_date' => 'required|date',
            'initial_observations' => 'nullable|string',

            // Documentos médicos
            'medical_report' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:5120',
            'previous_reports.*' => 'nullable|file|mimes:pdf,doc,docx|max:5120',

            // Apoderado y consentimientos
            'consent_pie' => 'required|boolean',
            'data_processing' => 'required|boolean',
            'guardian_email' => 'required_if:consent_pie,true|email',
            'guardian_name' => 'required_if:consent_pie,true|string|max:255',
            'guardian_rut' => [
                'required_if:consent_pie,true',
                'string',
                'max:20',
                // Agregar regla personalizada para formato RUT si es necesario
            ],
            'relationship' => 'required_if:consent_pie,true|string|max:50|in:parent,tutor,other',
        ];
    }

    public function messages(): array
    {
        return [
            'course_id.exists' => 'El curso seleccionado no pertenece a su establecimiento.',
            'assigned_specialist_id.exists' => 'El especialista seleccionado no pertenece a su establecimiento.',
            'guardian_email.required_if' => 'El email del apoderado es requerido cuando se otorga consentimiento PIE.',
            'guardian_name.required_if' => 'El nombre del apoderado es requerido cuando se otorga consentimiento PIE.',
            'guardian_rut.required_if' => 'El RUT del apoderado es requerido cuando se otorga consentimiento PIE.',
            'relationship.required_if' => 'La relación con el estudiante es requerida cuando se otorga consentimiento PIE.',
            'relationship.in' => 'La relación debe ser una de: padre, tutor, otro.',
        ];
    }

    public function attributes(): array
    {
        return [
            'new_user.name' => 'nombre del estudiante',
            'new_user.last_name' => 'apellido del estudiante',
            'assigned_specialist_id' => 'especialista asignado',
            'guardian_rut' => 'RUT del apoderado',
        ];
    }

public function prepareForValidation()
{
    // Siempre procesar new_user como JSON
    if ($this->has('new_user')) {
        $newUserData = is_string($this->new_user) 
            ? json_decode($this->new_user, true) 
            : $this->new_user;
        
        $this->merge(['new_user' => $newUserData]);
    }

    // Convertir booleanos
    $this->merge([
        'consent_pie' => filter_var($this->consent_pie, FILTER_VALIDATE_BOOLEAN),
        'data_processing' => filter_var($this->data_processing, FILTER_VALIDATE_BOOLEAN),
    ]);
}
}