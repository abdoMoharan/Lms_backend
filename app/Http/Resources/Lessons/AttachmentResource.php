<?php
namespace App\Http\Resources\Lessons;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Group\GroupSessionResource;

class AttachmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"           => $this->id,
            'type'         => $this->type,
            'link'         => $this->link,
            'file'         => $this->getPath($this->file),
            'image'        => $this->getPath($this->image),
            'video_upload' => $this->getPath($this->video_upload),
            // 'lesson'       => new LessonsResource($this->whenLoaded('lesson')),
            'group_session' => new GroupSessionResource($this->whenLoaded('group_session')),
        ];
    }
}
