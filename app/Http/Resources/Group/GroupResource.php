<?php
namespace App\Http\Resources\Group;

use App\Http\Resources\Course\CourseResource;
use App\Http\Resources\Lessons\AttachmentResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'group_name'       => $this->group_name,
            'max_seats'        => $this->max_seats,
            'available_seats'  => $this->available_seats,
            'status'           => $this->status,
            'start_date'       => $this->start_date,
            'session_status'   => $this->session_status,
            'group_type'       => $this->group_type,
            'duration'         => $this->duration,
            'hours_count'      => $this->hours_count,
            'course'           => new CourseResource($this->whenLoaded('course')),
            'teacher'          => new UserResource($this->whenLoaded('teacher')),
            'groupDays'        => GroupDayResource::collection($this->whenLoaded('groupDays')),
            'groupSession'     => GroupSessionResource::collection($this->whenLoaded('groupSession')),
        ];
    }
}
