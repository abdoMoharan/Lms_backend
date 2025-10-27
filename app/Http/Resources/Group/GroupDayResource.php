<?php
namespace App\Http\Resources\Group;

use App\Http\Resources\Group\GroupResource;
use App\Http\Resources\Group\WeekResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupDayResource extends JsonResource
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
            'start_time'   => $this->start_time,
            'session_time' => $this->session_time,
            'group'        => GroupResource::make($this->whenLoaded('group')),
            'week'         => WeekResource::make($this->whenLoaded('week')),
        ];
    }
}
