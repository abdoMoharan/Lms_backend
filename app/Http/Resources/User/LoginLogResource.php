<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Roles\RolesResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginLogResource extends JsonResource
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
            'login_time' => $this->login_time,
            'logout_time' => $this->logout_time,
            'ip_address' => $this->ip_address,
            'user_agent'     => $this->user_agent,
            'status' => $this->status,
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}

