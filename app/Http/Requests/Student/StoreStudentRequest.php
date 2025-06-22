<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
     public function rules()
    {
        return [
            // Nuevo usuario
            'new_user.name' => 'required|string|max:255',
            'new_user.last_name' => 'required|string|max:255',
            'new_user.email' => 'required|email|unique:users,email',
            'new_user.password' => 'required|string|min:8',

            // Datos del estudiante
            'birth_date' => 'required|date',
            'course_id' => 'required|exists:courses,id',
            'diagnosis' => 'nullable|string',
            'guardian_email' => 'nullable|email',

            // Documentos
            'medical_report' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:5120',
            'previous_reports.*' => 'nullable|file|mimes:pdf,doc,docx|max:5120',

            // Clasificación
            'need_type' => ['required', Rule::in(['permanent', 'temporary'])],
            'priority' => 'required|integer|min:1|max:3',
            'special_needs' => 'nullable|string',

            // Consentimientos
            'consent_pie' => 'required|boolean',
            'data_processing' => 'required|boolean',
            'guardian_name' => 'required_if:consent_pie,true|string|max:255',
            'guardian_rut' => 'required_if:consent_pie,true|string|max:20',

            // Asignación
            'assigned_specialist' => 'required|exists:professionals,id',
            'evaluation_date' => 'required|date',
            'initial_observations' => 'nullable|string',
        ];
    }



    public function prepareForValidation()
    {
        // Decodifica automáticamente new_user si es string JSON
        if ($this->has('new_user') && is_string($this->new_user)) {
            $this->merge([
                'new_user' => json_decode($this->new_user, true)
            ]);
        }
    }

    public function messages(): array
    {
        return [
            // Paso 1: Datos Básicos
        'full_name.required' => 'El nombre es obligatorio.',
        'last_name.required' => 'El apellido es obligatorio.',
        'rut.required' => 'El RUT es obligatorio.',
        'rut.unique' => 'Este RUT ya está registrado.',
        'birth_date.required' => 'La fecha de nacimiento es obligatoria.',
        'course_id.required' => 'El campo Curso es obligatorio.',
        'course_id.exists' => 'El Curso seleccionado no existe.',
        'guardian_email.email' => 'El correo del apoderado debe ser válido.',

        // Paso 2: Documentos
        'medical_report.required' => 'Debes adjuntar el Informe Médico.',
        'medical_report.file' => 'El Informe Médico debe ser un archivo.',
        'medical_report.mimes' => 'Formatos permitidos: PDF, Word, JPG, PNG.',
        'medical_report.max' => 'El archivo no debe superar los 5MB.',

        'previous_reports.*.file' => 'Cada Informe Previo debe ser un archivo.',
        'previous_reports.*.mimes' => 'Formatos permitidos: PDF, Word.',
        'previous_reports.*.max' => 'Cada archivo no debe superar los 5MB.',

        // Paso 3: Clasificación
        'need_type.required' => 'Debes seleccionar el tipo de necesidad.',
        'need_type.in' => 'La opción seleccionada para el tipo de necesidad no es válida.',

        'priority.required' => 'Debes seleccionar la prioridad.',
        'priority.in' => 'La opción seleccionada para la prioridad no es válida.',

        'special_needs.max' => 'Las necesidades específicas no deben exceder los 1000 caracteres.',

        // Paso 4: Consentimientos
        'guardian_name.required_if' => 'El nombre del apoderado es obligatorio si se autoriza la participación en PIE.',
        'guardian_rut.required_if' => 'El RUT del apoderado es obligatorio si se autoriza la participación en PIE.',

        // Paso 5: Asignación
        'assigned_specialist.required' => 'Debes seleccionar un profesional asignado.',
        'assigned_specialist.exists' => 'El profesional seleccionado no es válido.',
        'evaluation_date.required' => 'La fecha de evaluación inicial es obligatoria.',
        'evaluation_date.date' => 'La fecha de evaluación debe tener un formato válido.',
        'initial_observations.max' => 'Las observaciones iniciales no deben superar los 1000 caracteres.',


        ];
    }
}
