<?php
namespace App\Http\Resources\Question;

use App\Http\Resources\Answer\AnswerResource;
use App\Http\Resources\Exam\ExamResource;
use App\Http\Resources\QuestionType\QuestionTypeResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"            => $this->id,
            'name'          => $this->name,
            'exam'          => new ExamResource($this->whenLoaded('exam')),
            'question_type' => new QuestionTypeResource($this->whenLoaded('question_type')),
            'answers' =>      AnswerResource::collection($this->whenLoaded('answers')),

            'created_by'    => new UserResource($this->whenLoaded('createdBy')),
            'updated_by'    => new UserResource($this->whenLoaded('updatedBy')),
        ];
    }
}

