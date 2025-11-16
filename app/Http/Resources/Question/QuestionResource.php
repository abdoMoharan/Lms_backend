<?php
namespace App\Http\Resources\Question;

use Illuminate\Http\Request;
use App\Http\Resources\Exam\ExamResource;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\Option\OptionResource;
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
            'question_text' => $this->question_text,
            'mark'          => $this->mark,
            'exam'          => new ExamResource($this->whenLoaded('exam')),
            'options' =>      OptionResource::collection($this->whenLoaded('options')),
        ];
    }
}
