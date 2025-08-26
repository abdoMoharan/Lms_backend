<?php
namespace App\Http\Resources\EducationalStage;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EducationalStageResource extends JsonResource
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
            "id"     => $this->id,
            'title'  => $transLocale ? $transLocale->title : null,
            'status' => $this->status,

        ];
    }
}
