<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Roles\RolesResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"     => $this->id,
            "first_name"   => $this->first_name,
            "last_name"   => $this->last_name,
            "email"  => $this->email,
            "phone"  => $this->phone,
            "image"  => $this->image,
            "status" => $this->status,
            "last_login" => $this->last_login,
            "roles"  => RolesResource::collection($this->whenLoaded('roles')),
        ];
    }
}
