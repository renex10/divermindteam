<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\RegionResource; // 👈 Importación necesaria

class CommuneResource extends JsonResource
{
    /**
     * Transforma el recurso en un array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
  public function toArray($request)
{
    return [
        'id' => $this->id,
        'name' => $this->name,
        'region_id' => $this->region_id,
    ];
}
}
