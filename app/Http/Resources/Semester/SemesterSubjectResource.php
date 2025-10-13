<?php
namespace App\Http\Resources\Semester;

use App\Http\Resources\Semester\SemesterResource;
use App\Http\Resources\Subject\SubjectResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SemesterSubjectResource extends JsonResource
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
            'subject'  => new SubjectResource($this->whenLoaded('subject')),
            'semester' => new SemesterResource($this->whenLoaded('semester')),
        ];
    }
}
