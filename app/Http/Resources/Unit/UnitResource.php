<?php
namespace App\Http\Resources\Unit;

use App\Http\Resources\Course\CourseResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"         => $this->id,
            'name'       => $this->whenLoaded('transLocale', function () {
                return $this->transLocale->first()->name ?? null;
            }, function () {
                return $this->whenLoaded('trans', function () {
                    return [
                        'en' => $this->trans->firstWhere('locale', 'en')->name ?? null,
                        'ar' => $this->trans->firstWhere('locale', 'ar')->name ?? null,
                    ];
                });
            }),
            'status'     => $this->status,
            'sort'       => $this->sort,
            'course'     => new CourseResource($this->whenLoaded('course')),
            'created_by' => new UserResource($this->whenLoaded('createdBy')),
            'updated_by' => new UserResource($this->whenLoaded('updatedBy')),
        ];
    }
}
