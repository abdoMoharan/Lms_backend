<?php
namespace App\Http\Resources\Course;

use App\Http\Resources\Course\CourseResource;
use App\Http\Resources\EducationalStage\EducationalStageResource;
use App\Http\Resources\Grade\GradeResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CoursePriceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'price'            => $this->price,
            'educationalStage' => new EducationalStageResource($this->whenLoaded('stage')),
            'grade'            => new GradeResource($this->whenLoaded('grade')),
            'course'           => new CourseResource($this->whenLoaded('course')),
        ];
    }
}
