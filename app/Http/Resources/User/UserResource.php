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
            "id"    => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'user_type' => $this->user_type,
            'status' => $this->status,
            "roles" => RolesResource::collection($this->whenLoaded('roles')),
        ];
    }
}
