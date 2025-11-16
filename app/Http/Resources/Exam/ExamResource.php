<?php
namespace App\Http\Resources\Exam;

use App\Http\Resources\Group\GroupSessionResource;
use App\Http\Resources\Question\QuestionResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"           => $this->id,
            'name'         => $this->name ?? null,
            'description'  => $this->description ?? null,
            'duration'     => $this->duration,
            'start_date'   => $this->start_date,
            'end_date'     => $this->end_date,
            'total'        => $this->total,
            'groupSession' => new GroupSessionResource($this->whenLoaded('groupSession')),
            'teacher'      => new UserResource($this->whenLoaded('teacher')),
            'questions'    => QuestionResource::collection($this->whenLoaded('questions')),
        ];
    }
}
