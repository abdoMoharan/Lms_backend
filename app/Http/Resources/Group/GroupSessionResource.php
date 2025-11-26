<?php
namespace App\Http\Resources\Group;

use Illuminate\Http\Request;
use App\Http\Resources\Lessons\LessonsResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Lessons\AttachmentResource;

class GroupSessionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'date'         => $this->date,
            'group'     => new GroupResource($this->whenLoaded('group')),
            'day'     => new GroupDayResource($this->whenLoaded('groupDay')),
            'lesson'     => new LessonsResource($this->whenLoaded('lesson')),
            'attachmentLesson' => AttachmentResource::collection($this->whenLoaded('attachmentLesson')),
        ];
    }
}
