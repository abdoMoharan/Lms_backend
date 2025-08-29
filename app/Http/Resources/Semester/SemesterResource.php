<?php
namespace App\Http\Resources\Semester;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SemesterResource extends JsonResource
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
            'status' => $this->status,

        ];
    }
}
