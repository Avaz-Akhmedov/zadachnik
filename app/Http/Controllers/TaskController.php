<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TaskController extends Controller
{

    //Список всех задач
    //Для фильтрации задач по статусу отправьте запрос
    //?status = simple или important или unimportant или nonurgent
    public function index(Request $request): ResourceCollection
    {
        $tasks = Task::query()
            ->when($request->has('status'), function (Builder $query) use ($request) {
                $query->where('status', $request->input('status'));
            })
            ->latest()
            ->paginate(10);

        //Код, чтобы увидеть, сколько задач имеет каждый статус
        $taskStatusCount = Task::query()
            ->selectRaw('status, count(*) as tasksCount')
            ->groupBy('status')
            ->get()
            ->toArray();

        return TaskResource::collection($tasks)->additional([
            'statuses' => $taskStatusCount
        ]);
    }


    //Создать новую задачу
    public function store(StoreTaskRequest $request): JsonResponse
    {
        Task::query()->create($request->validated());

        return response()->json([
            'success' => true
        ], 201);
    }

    //Удалить существующую задачу
    public function destroy(Task $task): JsonResponse
    {
        $task->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
