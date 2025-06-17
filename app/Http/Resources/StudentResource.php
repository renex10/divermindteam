<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

/**
 * Class StudentResource
 *
 * Este recurso transforma los datos del modelo Student antes de enviarlos en respuestas API.
 * Permite estructurar la salida en JSON, asegurando que solo se envíen los datos relevantes
 * y con formato consistente para el frontend.
 */
class StudentResource extends JsonResource
{
    /**
     * Transforma el recurso en un array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            // ID del estudiante
            'id' => $this->id,
            
            // Información del usuario relacionado (datos personales)
            'user' => [
                'id' => $this->user?->id,
                'name' => $this->user?->name ?? 'Sin nombre',
                'last_name' => $this->user?->last_name ?? 'Sin apellido',
                'email' => $this->user?->email ?? 'Sin email',
                'full_name' => $this->user ? 
                    trim($this->user->name . ' ' . $this->user->last_name) : 
                    'Usuario sin nombre'
            ],
            
            // IDs de relaciones
            'document_id' => $this->document_id,
            'course_id' => $this->course_id,
            
            // Datos específicos del estudiante
            'birth_date' => $this->birth_date,
            'birth_date_formatted' => $this->birth_date ? 
                Carbon::parse($this->birth_date)->format('d/m/Y') : 
                null,
            'age' => $this->birth_date ? 
                Carbon::parse($this->birth_date)->age : 
                null,
            
            // Tipo de necesidad con texto descriptivo
            'need_type' => $this->need_type,
            'need_type_text' => match($this->need_type) {
                'permanent' => 'Permanente',
                'temporary' => 'Temporal',
                default => 'No especificado'
            },
            
            // Diagnóstico
            'diagnosis' => $this->diagnosis,
            'has_diagnosis' => !empty($this->diagnosis),
            
            // Prioridad con texto descriptivo y color
            'priority' => $this->priority,
            'priority_text' => match($this->priority) {
                1 => 'Máxima',
                2 => 'Media',
                3 => 'Básica',
                default => 'Sin definir'
            },
            'priority_color' => match($this->priority) {
                1 => 'red',    // Prioridad máxima
                2 => 'yellow', // Prioridad media
                3 => 'green',  // Prioridad básica
                default => 'gray'
            },
            
            // Estado activo
            'active' => $this->active,
            'status_text' => $this->active ? 'Activo' : 'Inactivo',
            'status_color' => $this->active ? 'green' : 'red',
            
            // Timestamps formateados
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_at_formatted' => $this->created_at ? 
                $this->created_at->format('d/m/Y H:i') : 
                null,
            'updated_at_formatted' => $this->updated_at ? 
                $this->updated_at->format('d/m/Y H:i') : 
                null,
            
            // Campos adicionales útiles para el frontend
            'initials' => $this->getUserInitials(),
            'display_name' => $this->getDisplayName(),
        ];
    }
    
    /**
     * Obtiene las iniciales del usuario
     *
     * @return string
     */
    private function getUserInitials(): string
    {
        if (!$this->user) {
            return 'NN';
        }
        
        $firstInitial = $this->user->name ? 
            mb_strtoupper(mb_substr($this->user->name, 0, 1)) : 
            'N';
        
        $lastInitial = $this->user->last_name ? 
            mb_strtoupper(mb_substr($this->user->last_name, 0, 1)) : 
            'N';
        
        return $firstInitial . $lastInitial;
    }
    
    /**
     * Obtiene el nombre completo para mostrar
     *
     * @return string
     */
    private function getDisplayName(): string
    {
        if (!$this->user) {
            return 'Usuario sin nombre';
        }
        
        $name = trim($this->user->name . ' ' . $this->user->last_name);
        
        return !empty($name) ? $name : 'Usuario sin nombre';
    }
    
    /**
     * Campos adicionales que se pueden incluir en la respuesta
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function with($request): array
    {
        return [
            'meta' => [
                'resource_type' => 'student',
                'version' => '1.0',
                'generated_at' => now()->toISOString()
            ]
        ];
    }
}