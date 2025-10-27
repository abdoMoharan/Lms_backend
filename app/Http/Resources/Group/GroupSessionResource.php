<?php
namespace App\Http\Resources\Group;

use App\Http\Resources\Lessons\LessonsResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'start_time'   => $this->start_time,
            'session_time' => $this->session_time,
            'group'     => new GroupResource($this->whenLoaded('group')),
            'day'     => new GroupDayResource($this->whenLoaded('groupDay')),
            'lesson'     => new LessonsResource($this->whenLoaded('lesson')),

        ];
    }
}
