<?php

namespace App\Http\Controllers;

use App\Repositories\TaskRepository;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    protected $tasks;

    /**
     * 新しいコントローラーインスタンスの生成
     */
    public function __construct(TaskRepository $tasks)
    {
        $this->middleware('auth');

        $this->tasks = $tasks;
    }

    /**
     * ユーザーの全タクスをリスト表示
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request) {
        return view('tasks.index', [
            'tasks' => $this->tasks->forUser($request->user())
         ]);
    }

    /**
     * 新タスク作成
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request) {
        $this->validate($request, [
            'name' => 'required|max:255'
        ]);

        // タスクの作成
        $request->user()->tasks()->create([
            'name' => $request->name,
        ]);

        return redirect('/tasks');
    }

    /**
     * 指定タスクの削除
     *
     * @param Request $request
     * @param Task $task
     * @return Responce
     */
    public function destroy(Request $request, Task $task)
    {
        $this->authorize('destroy', $task);

        $task->delete();

        return redirect('/tasks');
    }
}
