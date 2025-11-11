<?php
namespace App\Http\Resources\Meeting;


use App\Http\Resources\Group\GroupSessionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ZoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            'zoom_id' => $this->zoom_id,
            'host_id' => $this->host_id,
            'host_email' => $this->host_email,
            'topic' => $this->topic,
            'start_time' => $this->start_time,
            'duration' => $this->duration,
            'timezone' => $this->timezone,
            'start_url' => $this->start_url,
            'join_url' => $this->join_url,
            'password' => $this->password,
            'is_meeting_created' => $this->is_meeting_created,
            'group_session' => new GroupSessionResource($this->whenLoaded('group_session')),
        ];
    }
}
