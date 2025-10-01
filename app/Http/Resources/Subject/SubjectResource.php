<?php
namespace App\Http\Resources\Subject;

use App\Http\Resources\EducationalStage\EducationalStageResource;
use App\Http\Resources\Grade\GradeResource;
use App\Http\Resources\Semester\SemesterResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubjectResource extends JsonResource
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
            'slug'   => $this->whenLoaded('transLocale', function () {
                return $this->transLocale->first()->slug ?? null;
            }, function () {
                return $this->whenLoaded('trans', function () {
                    return [
                        'en' => $this->trans->firstWhere('locale', 'en')->slug ?? null,
                        'ar' => $this->trans->firstWhere('locale', 'ar')->slug ?? null,
                    ];
                });
            }),
            'status'           => $this->status,
            'educationalStage' => new EducationalStageResource($this->whenLoaded('educationalStage')),
            'semester'         => new SemesterResource($this->whenLoaded('semester')),
            'grade'            => new GradeResource($this->whenLoaded('grade')),
        ];
    }
}
