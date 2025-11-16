<?php
namespace App\Http\Resources\Option;

use App\Http\Resources\Question\QuestionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"          => $this->id,
            'option_text' => $this->option_text,
            'is_correct'  => $this->is_correct,
            'question'    => QuestionResource::collection($this->whenLoaded('question')),
        ];
    }
}
