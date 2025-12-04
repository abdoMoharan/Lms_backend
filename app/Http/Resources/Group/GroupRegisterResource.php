<?php
namespace App\Http\Resources\Group;

use Illuminate\Http\Request;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\Group\GroupResource;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupRegisterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'price'        => $this->price,
            'student'      => new UserResource($this->whenLoaded('user')),
            'group' => new GroupResource($this->whenLoaded('group')),
        ];
    }
}
