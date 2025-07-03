<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommuneResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */


     /**
     * Transforma el recurso en un array para su representación.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'address'      => $this->address,
            'commune'      => [
                'id'   => $this->commune->id,
                'name' => $this->commune->name,
            ],
            'pie_quota_max' => $this->pie_quota_max,
            'is_active'    => (bool) $this->is_active,
            'created_at'   => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at'   => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
