<?php
namespace App\Http\Resources\Question;

use App\Http\Resources\Exam\ExamResource;
use App\Http\Resources\QuestionType\QuestionTypeResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
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
            "id"            => $this->id,
            'name'          => $this->whenLoaded('transLocale', function () {
                return $this->transLocale->first()->name ?? null;
            }, function () {
                return $this->whenLoaded('trans', function () {
                    return [
                        'en' => $this->trans->firstWhere('locale', 'en')->name ?? null,
                        'ar' => $this->trans->firstWhere('locale', 'ar')->name ?? null,
                    ];
                });
            }),
            'exam'          => new ExamResource($this->whenLoaded('exam')),
            'question_type' => new QuestionTypeResource($this->whenLoaded('question_type')),
            'created_by'    => new UserResource($this->whenLoaded('createdBy')),
            'updated_by'    => new UserResource($this->whenLoaded('updatedBy')),
        ];
    }
}
