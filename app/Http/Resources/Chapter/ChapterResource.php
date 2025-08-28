<?php
namespace App\Http\Resources\Chapter;

use App\Http\Resources\EducationalStage\EducationalStageResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChapterResource extends JsonResource
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
        ];
    }
}
