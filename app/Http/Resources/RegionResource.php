<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegionResource extends JsonResource
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
    ];
}

}
