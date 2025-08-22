<?php

namespace App\Http\Resources\Language;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LanguageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"            => $this->id,
            "name"          => $this->name,
            "locale"        => $this->locale,
            "flag_image"    => $this->flag_image,
            "direction"     => $this->direction,
            "status"        => $this->status,
            "created_by"    => UserResource::make($this->whenLoaded('createdBy')),
            "updated_by"    => UserResource::make($this->whenLoaded('updatedBy')),
        ];
    }
}
