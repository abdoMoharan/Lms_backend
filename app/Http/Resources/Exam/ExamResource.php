<?php
namespace App\Http\Resources\Exam;

use App\Http\Resources\Course\CourseResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamResource extends JsonResource
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
            "id"          => $this->id,
            'name'        => $this->whenLoaded('transLocale', function () {
                return $this->transLocale->first()->name ?? null;
            }, function () {
                return $this->whenLoaded('trans', function () {
                    return [
                        'en' => $this->trans->firstWhere('locale', 'en')->name ?? null,
                        'ar' => $this->trans->firstWhere('locale', 'ar')->name ?? null,
                    ];
                });
            }),
            'description' => $this->whenLoaded('transLocale', function () {
                return $this->transLocale->first()->description ?? null;
            }, function () {
                return $this->whenLoaded('trans', function () {
                    return [
                        'en' => $this->trans->firstWhere('locale', 'en')->description ?? null,
                        'ar' => $this->trans->firstWhere('locale', 'ar')->description ?? null,
                    ];
                });
            }),
            'course'      => new CourseResource($this->whenLoaded('course')),
            'teacher'     => new UserResource($this->whenLoaded('teacher')),
            'time'        => $this->time,
            'start_date'  => $this->start_date,
            'end_date'    => $this->end_date,
            'total'       => $this->total,
        ];
    }
}
