<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index(Request $request): \Inertia\Response
    {
        $allowedFilters = ['all', 'pending', 'completed', 'trashed'];

        $validated = $request->validate([
            'filter' => ['nullable', Rule::in($allowedFilters)]
        ]);

        $currentFilter = $validated['filter'] ?? 'pending';

        $user = $request->user();

        $query = $user
            ->tasks()
            ->with(['children'])
            ->withCount('children')
            ->onlyRoot();

        switch ($currentFilter) {
            case 'all';
                $query->withTrashed();
                break;
            case 'trashed';
                $query->onlyTrashed();
                break;
            case 'completed';
                $query->completed();
                break;
            default:
                $query->pending();
                break;
        }

        $tasks = TaskResource::collection($query->latest()->get());
        //$tasks = $query->latest()->get();



        return Inertia::render(
            'Tasks/Index',
            compact('tasks', 'currentFilter', 'allowedFilters')
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(StoreTaskRequest $request): \Illuminate\Http\RedirectResponse
    {

        $request->user()->tasks()->create($request->validated());

        return back();
    }


    /**
     * Update the specified resource in storage.s
     */
    public function update(UpdateTaskRequest $request, Task $task): \Illuminate\Http\RedirectResponse
    {
        $task->update($request->validated());

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): \Illuminate\Http\RedirectResponse
    {
        $task->trashed() ? $task->forceDelete() : $task->delete();

        return back();
    }

    public function toggle(Task $task): \Illuminate\Http\RedirectResponse
    {

        $task->completed_at = is_null($task->completed_at) ? now() : null;
        $task->save();

        return back();
    }

    public function restore($task): \Illuminate\Http\RedirectResponse
    {
        $task = Task::onlyTrashed()->findOrFail($task);

        $task->restore();
        $task->completed_at = null;
        $task->save();

        return back();
    }
}
