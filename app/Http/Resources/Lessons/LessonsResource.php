<?php
namespace App\Http\Resources\Lessons;

use App\Http\Resources\Lessons\AttachmentResource;
use App\Http\Resources\Unit\UnitResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
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
            'content'     => $this->whenLoaded('transLocale', function () {
                return $this->transLocale->first()->content ?? null;
            }, function () {
                return $this->whenLoaded('trans', function () {
                    return [
                        'en' => $this->trans->firstWhere('locale', 'en')->content ?? null,
                        'ar' => $this->trans->firstWhere('locale', 'ar')->content ?? null,
                    ];
                });
            }),
            'status'      => $this->status,
            'sort'        => $this->sort,
            'cover_image' => $this->getPath($this->cover_image),
            'url'         => $this->url,
            'zoom_url'    => $this->zoom_url,
            'unit'        => new UnitResource($this->whenLoaded('unit')),
            'created_by'  => new UserResource($this->whenLoaded('createdBy')),
            'updated_by'  => new UserResource($this->whenLoaded('updatedBy')),
            'attachments' => AttachmentResource::collection($this->whenLoaded('attachments')),
        ];
    }
}
