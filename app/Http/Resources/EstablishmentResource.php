<?php
/* ruta del archivo es app\Http\Resources\EstablishmentResource.php */

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EstablishmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'rbd' => $this->rbd,
            'pie_quota_max' => $this->pie_quota_max,
            'is_active' => $this->is_active,
            'commune' => [
                'id' => $this->commune->id,
                'name' => $this->commune->name,
                'region' => [
                    'id' => $this->commune->region->id,
                    'name' => $this->commune->region->name,
                ],
            ],
        ];
    }
}
