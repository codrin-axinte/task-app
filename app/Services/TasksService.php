<?php

namespace App\Services;

use App\Enums\TaskFilter;
use App\Models\Task;

class TasksService
{
    public function search($userId, string $searchQuery = '', TaskFilter $currentFilter = TaskFilter::Pending): \Illuminate\Database\Eloquent\Collection
    {
        return Task::search($searchQuery)
            ->query(function ($query) use ($userId, $currentFilter, $searchQuery) {
                $query
                    ->where('user_id', $userId)
                    ->with(['children'])
                    ->withCount('children');

                // We do this to be able to search in nested tasks as well.
                if (empty($searchQuery)) {
                    $query->onlyRoot();
                }

                switch ($currentFilter) {
                    case TaskFilter::All;
                        $query->withTrashed();
                        break;
                    case TaskFilter::Trashed;
                        $query->onlyTrashed();
                        break;
                    case TaskFilter::Completed;
                        $query->completed();
                        break;
                    default:
                        $query->pending();
                        break;
                }

                $query->latest()
                    ->orderBy('due_date', 'asc');
            })->get();

    }

}
