<?php
namespace App\Http\Resources\Course;

use Illuminate\Http\Request;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\Grade\GradeResource;
use App\Http\Resources\Group\GroupResource;
use App\Http\Resources\Subject\SubjectResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Semester\SemesterCourseResource;
use App\Http\Resources\Semester\SemesterSubjectResource;
use App\Http\Resources\EducationalStage\EducationalStageResource;

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
            'description'      => $this->whenLoaded('transLocale', function () {
                return $this->transLocale->first()->description ?? null;
            }, function () {
                return $this->whenLoaded('trans', function () {
                    return [
                        'en' => $this->trans->firstWhere('locale', 'en')->description ?? null,
                        'ar' => $this->trans->firstWhere('locale', 'ar')->description ?? null,
                    ];
                });
            }),
            'slug'             => $this->whenLoaded('transLocale', function () {
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
            'day_count'        => $this->day_count,
            'educationalStage' => new EducationalStageResource($this->whenLoaded('educationalStage')),
            'semesters'        => SemesterCourseResource::collection($this->whenLoaded('semesters')),
            'coursePrice'      => new CoursePriceResource($this->whenLoaded('coursePrice')),
            'grade'            => new GradeResource($this->whenLoaded('grade')),
            'subject'          => new SubjectResource($this->whenLoaded('subject')),
            'groups'           => GroupResource::collection($this->whenLoaded('groups')),
            'created_by'       => new UserResource($this->whenLoaded('createdBy')),
            'updated_by'       => new UserResource($this->whenLoaded('updatedBy')),
        ];
    }
}
