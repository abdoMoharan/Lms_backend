<?php
namespace App\Http\Resources\Profile;

use App\Http\Resources\Roles\RolesResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            "id"                  => $this->id,
            'first_name'          => $this->first_name,
            'last_name'           => $this->last_name,
            'email'               => $this->email,
            'image'               => $this->image,
            'phone'               => $this->phone,
            'qualification'       => $this->qualification,
            'certificate_name'    => $this->certificate_name,
            'certificate_date'    => $this->certificate_date,
            'experience'          => $this->experience,
            'id_card_number'      => $this->id_card_number,
            'id_card_image_front' => $this->id_card_image_front,
            'id_card_image_back'  => $this->id_card_image_back,
            'birthdate'           => $this->birthdate,
            'nationality'         => $this->nationality,
            'address'             => $this->address,
            'degree'              => $this->degree,
            'cv'                  => $this->cv,
            'bio'                 => $this->bio,
            'gender'              => $this->gender,
            'intro_video'         => $this->intro_video,
            'roles'               => RolesResource::collection($this->whenLoaded('roles')),
            'user'               => UserResource::make($this->whenLoaded('user')),
        ];
    }
}
