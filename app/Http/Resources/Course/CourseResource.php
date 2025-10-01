<?php
namespace App\Http\Resources\Course;

use App\Http\Resources\Subject\SubjectResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
            'description'             => $this->whenLoaded('transLocale', function () {
                return $this->transLocale->first()->description ?? null;
            }, function () {
                return $this->whenLoaded('trans', function () {
                    return [
                        'en' => $this->trans->firstWhere('locale', 'en')->description ?? null,
                        'ar' => $this->trans->firstWhere('locale', 'ar')->description ?? null,
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
            'teacher' => new UserResource($this->whenLoaded('teacher')),
            'subject'            => new SubjectResource($this->whenLoaded('subject')),
            'created_by' => new UserResource($this->whenLoaded('createdBy')),
            'updated_by' => new UserResource($this->whenLoaded('updatedBy')),
        ];
    }
}
