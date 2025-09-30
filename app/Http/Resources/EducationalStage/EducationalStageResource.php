<?php
namespace App\Http\Resources\EducationalStage;

use App\Http\Resources\Grade\GradeResource;
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
        // نستخدم whenLoaded للتحقق من تحميل العلاقات
        return [
            "id"     => $this->id,
            'name'   => $this->whenLoaded('transLocale', function () {
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
            'status' => $this->status,
            'grades' => GradeResource::collection($this->whenLoaded('grades')),
        ];
    }
}
