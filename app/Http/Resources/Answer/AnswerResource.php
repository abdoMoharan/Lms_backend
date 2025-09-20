<?php
namespace App\Http\Resources\Answer;

use App\Http\Resources\Question\QuestionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnswerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"             => $this->id,
            'name'           => $this->name,
            'correct_answer' => $this->correct_answer,
            'question'       => new QuestionResource($this->whenLoaded('question')),
        ];
    }
}
