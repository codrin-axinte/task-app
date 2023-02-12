<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'children' => $this->whenLoaded('children', fn() => TaskResource::collection($this->children)),
            'children_count' => $this->whenLoaded('children', fn() => $this->children_count),
            'parent' => $this->whenLoaded('parent', fn() => TaskResource::make($this->parent)),
            'completed_at' => $this->completed_at,
            'deleted_at' => $this->deleted_at,
            'is_completed' => (bool)$this->completed_at,
            'is_deleted' => (bool)$this->deleted_at,
            'due_date' => $this->formatDueDate(),
            'due_days' => $this->due_date?->diffInDays(now()),
        ];
    }

    private function formatDueDate()
    {
        if (!$this->due_date) {
            return null;
        }

        $dueDate = $this->due_date;
        $time = $dueDate?->format('h:i');
        $dueDays = $dueDate?->diffInDays(now());

        if ($dueDate->isPast()) {
            return $dueDate?->diffForHumans();
        }

        if ($dueDate?->isToday()) {
            return 'Today at ' . $time;
        }

        if ($dueDays === 1) {
            return 'Tomorrow at ' . $time;
        }

        if ($dueDays < 4) {
            return $dueDate?->dayName . ' at ' . $time;
        }


        return $dueDate?->toFormattedDateString();
    }
}
