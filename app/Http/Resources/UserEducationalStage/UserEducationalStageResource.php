<?php
namespace App\Http\Resources\UserEducationalStage;

use App\Http\Resources\EducationalStage\EducationalStageResource;
use App\Http\Resources\Subject\SubjectResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserEducationalStageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"      => $this->id,
            'user'    => new UserResource($this->whenLoaded('user')),
            'stage'   => new EducationalStageResource($this->whenLoaded('educational_stage')),
            'subject' => new SubjectResource($this->whenLoaded('subject')),
        ];
    }
}
