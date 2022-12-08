<?php

namespace App\Http\Controllers;

use App\Services\TasksService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use \App\User;
use App\Task;
use App\Repositories\TaskRepository;
use Illuminate\View\View;
use App\Http\Requests\TasksIndexRequest;

class TaskController extends Controller
{
    /**
     * The task repository instance.
     *
     * @var TaskRepository
     */
    protected $tasks;

    /**
     * The task service instance.
     *
     * @var TasksService
     */
    protected $tasksService;

    /**
     * Create a new controller instance.
     *
     * @param TasksService $tasksService
     * @param  TaskRepository  $tasks
     * @return void
     */
    public function __construct(TasksService $tasksService, TaskRepository $tasks)
    {
        $this->middleware('auth');

        $this->tasks = $tasks;
        $this->tasksService = $tasksService;
    }

    /**
     * Display a list of all of the user's task.
     *
     * @param  TasksIndexRequest  $request
     * @return View
     */
    public function index(TasksIndexRequest $request)
    {
        $users = User::query()->get();
        $logins = Task::query()->distinct('login')->select('login')->get();
        $selectedStates = $request->get('states', []);
        $phone1 = $request->get('phone1', '');
        $authorsIds = $request->get('authors_ids', []);
        $usersIds = $request->get('users_ids', []);
        $search = $request->get('search', '');
        $date = $request->get('date', '');
        $selectedLogins = $request->get('logins', []);
        
        $tasks = $this->tasks->forUser($request->user(), $selectedStates, $selectedLogins, $usersIds, $search, $date, $phone1, $authorsIds);

        return view('tasks.index', [
            'users' => $users,
            'logins' => $logins,
            'selectedStates' => $selectedStates,
            'phone1' => $phone1,
            'selectedLogins' => $selectedLogins,
            'tasks' => $tasks,
            'users_ids' => $usersIds,
            'search' => $search,
            'date' => $date,
        ]);
    }

    /**
     * Show Add Task view.
     *
     * @param  Task  $task
     * @param  Request  $request
     * @return View
     */
    public function show( Task $task, Request $request){
        // Check if a task belongs to a user and check if task is viewed by a user, if so change viewed to TRUE
        if ($task->user_id == $request->user()->id && $task->isViewed() == FALSE ) {
            $task->update(['viewed' => TRUE ]);
        }


        return view('tasks.show', compact('task','request') );
    }

    /**
     * Show Add Task view.
     *
     * @return View
     */
    public function add()
    {
        return view('tasks.add');
    }

    /**
     * Create a new task.
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:33',
            'text' => 'max:255',
            'phone1' => array('regex:/^\+[0-9]{11}$/'),
            'login' => 'string|max:20',
            'type_id' => 'required',
            'priority_id' => 'required',
            //'taskuser' => 'required',
            'address' => 'string|max:20',
            'user_id' => 'required',
            'uid' => 'integer'
        ]);

        $this->tasksService->create($request);
        
        return redirect('/tasks');
    }

    /**
     * Edit Task (Only Author can edit a task)
     *
     * @param  Task  $task
     * @return View
     * @throws
     */
    public function edit(Task $task){
        $this->authorize('edit', $task);
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update Task (Only Author can edit a task)
     *
     * @param  Task  $task
     * @param  Request  $request
     * @return RedirectResponse
     * @throws
     */
    public function update(Task $task, Request $request){
        $this->authorize('edit', $task);

        $task = $this->tasksService->update($task, $request->all());

        return redirect('/tasks/'.$task->id.'/show');
    }

    /**
     * Destroy the given task.
     * Only Author can delete a task
     *
     * @param  Request  $request
     * @param  Task  $task
     * @return RedirectResponse
     * @throws
     */
    public function destroy(Request $request, Task $task)
    {
        $this->authorize('destroy', $task);

        $task->delete();

        return redirect('/tasks');
    }

    /**
     * Close Task (Only Author of a task can close a task)
     *
     * @param  Task  $task
     * @param  Request  $request
     * @return RedirectResponse
     * @throws
     */
    public function close(Task $task, Request  $request){
        $this->authorize('close', $task);

        $this->tasksService->close($task);

        return redirect('/tasks/'.$task->id.'/show');
    }

    /**
     * Change Task State
     * Only Admin, Author or User of a task can change a task state)
     * @param  Task  $task
     * @param  Request  $request
     * @return RedirectResponse
     * @throws
     */
    public function changestate (Task $task, Request $request){
        $this->authorize('changestate', $task);

        $this->tasksService->changeState($task, $request);

        return redirect('/tasks/'.$task->id.'/show');
    }
}
