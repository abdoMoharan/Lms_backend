<?php
namespace App\Http\Resources\Subject;

use App\Http\Resources\EducationalStage\EducationalStageResource;
use App\Http\Resources\Grade\GradeResource;
use App\Http\Resources\Semester\SemesterResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $transLocale = $this->transLocale()->first();
        return [
            "id"               => $this->id,
            'name'             => $transLocale ? $transLocale->name : null,
            'status'           => $this->status,
            'educationalStage' => new EducationalStageResource($this->whenLoaded('educationalStage')),
            'semester'         => new SemesterResource($this->whenLoaded('semester')),
            'grade'            => new GradeResource($this->whenLoaded('grade')),
        ];
    }
}
