<?php
namespace App\Http\Resources\Subject;

use Illuminate\Http\Request;
use App\Http\Resources\Grade\GradeResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Semester\SemesterResource;
use App\Http\Resources\Semester\SemesterSubjectResource;
use App\Http\Resources\EducationalStage\EducationalStageResource;

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
        ];
    }
}
