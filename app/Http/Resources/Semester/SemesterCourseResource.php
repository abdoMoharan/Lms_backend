<?php
namespace App\Http\Resources\Semester;

use Illuminate\Http\Request;
use App\Http\Resources\Course\CourseResource;
use App\Http\Resources\Subject\SubjectResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Semester\SemesterResource;

class SemesterCourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'course'  => new CourseResource($this->whenLoaded('course')),
            'semester' => new SemesterResource($this->whenLoaded('semester')),
        ];
    }
}
