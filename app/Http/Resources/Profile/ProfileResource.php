<?php

namespace App\Http\Resources\Profile;

use App\Http\Resources\Roles\RolesResource;
use Illuminate\Http\Request;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserInfo\UserInfoResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"                => $this->id,
            'first_name'        => $this->first_name,
            'last_name'         => $this->last_name,
            'email'             => $this->email,
            'image'             => $this->image,
            'phone'             => $this->phone,
            'roles' => RolesResource::collection($this->whenLoaded('roles')),
        ];
    }
}
