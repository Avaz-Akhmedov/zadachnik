<?php

namespace Tests\Feature;

use App\Enums\TaskStatus;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use DatabaseTransactions, HasFactory;

    public function test_to_see_list_of_tasks()
    {
        Task::factory(20)->create();

        $this->getJson(route('tasks.index'))
            ->assertOk()
            ->assertJsonCount(10, 'data');
    }


    public function test_to_filter_tasks_by_status()
    {
        Task::factory(15)->create([
            'status' => TaskStatus::IMPORTANT->value
        ]);

        Task::factory(5)->create([
            'status' => TaskStatus::SIMPLE->value
        ]);

        $this->getJson(route('tasks.index', [
            'status' => TaskStatus::IMPORTANT->value
        ]))
            ->assertOk()
            ->assertJsonCount(10, 'data');

        $this->getJson(route('tasks.index', [
            'status' => TaskStatus::SIMPLE->value
        ]))
            ->assertOk()
            ->assertJsonCount(5, 'data');
    }


    public function test_to_create_new_task()
    {
        $invalidData = [
            'title' => '',
            'description' => ''
        ];

        $this->postJson(route('tasks.store'), $invalidData)
            ->assertUnprocessable();

        $validData = [
            'title' => 'Football',
            'description' => 'The best game of my life',
            'status' => TaskStatus::IMPORTANT->value
        ];

        $this->postJson(route('tasks.store'), $validData)
            ->assertCreated();

        tap(Task::query()->first(), function ($task) {
            $this->assertEquals('Football', $task->title);
            $this->assertEquals('The best game of my life', $task->description);
            $this->assertEquals(TaskStatus::IMPORTANT->value, $task->status);
        });
    }


    public function test_to_delete_existing_task()
    {
        $task = Task::factory()->create([
            'title' => 'Something'
        ]);

        $this->deleteJson(route('tasks.destroy',21121))
            ->assertNotFound();

        $this->deleteJson(route('tasks.destroy',$task->id))
            ->assertOk();

        $taskExists = Task::query()->where('title',$task->title)->doesntExist();
        $this->assertTrue($taskExists);
    }
}
