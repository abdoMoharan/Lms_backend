<?php
namespace App\Http\Resources\Grade;

use App\Http\Resources\EducationalStage\EducationalStageResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GradeResource extends JsonResource
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
            'name'             => $this->whenLoaded('transLocale', function () {
                return $this->transLocale->first()->name ?? null;
            }, function () {
                return $this->whenLoaded('trans', function () {
                    return [
                        'en' => $this->trans->firstWhere('locale', 'en')->name ?? null,
                        'ar' => $this->trans->firstWhere('locale', 'ar')->name ?? null,
                    ];
                });
            }),
            'status'           => $this->status,
            'educationalStage' => new EducationalStageResource($this->whenLoaded('educationalStage')),
        ];
    }
}
