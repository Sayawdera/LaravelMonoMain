<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        parent::toArray($request);
        
        return [
            'id' => $this->id,
            'country' => $this->country,
            'state' => $this->state,
            'city' => $this->city,
            'street' => $this->street,
            'zip_code' => $this->zip_code,
            'home_number' => $this->home_number,
            'parent_country' => new CountryResource($this->whenLoaded('parentCountry')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
