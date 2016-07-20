<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use \App\User;
use App\Task;
use \App\State;
use App\Repositories\TaskRepository;
use App\Repositories\SmsRepository;

class TaskController extends Controller
{
    /**
     * The task repository instance.
     *
     * @var TaskRepository
     */
    protected $tasks;

    /**
     * Create a new controller instance.
     *
     * @param  TaskRepository  $tasks
     * @return void
     */
    public function __construct(TaskRepository $tasks)
    {
        $this->middleware('auth');

        $this->tasks = $tasks;
    }

    /**
     * Display a list of all of the user's task.
     *
     * @param  Request  $request
     * @return Response
     */
    public function index(Request $request)
    {
        return view('tasks.index', [
            'tasks' => $this->tasks->forUser($request->user()),
        ]);
    }

    /**
     * Show Add Task view.
     *
     * @param  Task  $task
     * @param  Request  $request
     * @return Response
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
     * @return Response
     */
    public function add()
    {
        return view('tasks.add');
    }

    /**
     * Create a new task.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'phone1' => 'digits:10',
            'text' => 'max:255',
            'type_id' => 'required',
            'priority_id' => 'required',
            //'taskuser' => 'required',
            'user_id' => 'required',
        ]);

        // return var_dump($request->all());

        $task = Task::create([
            'author_id' => $request->user()->id,
            'name' => $request->name,
            'text' => $request->text,
            'type_id' => $request->type_id,
            'priority_id' => $request->priority_id,
            'phone1' => $request->phone1,
            'fio' => $request->fio,
            'login' => $request->login,
            'uid' => $request->uid,
            'address' => $request->address,
            'user_id' => $request->user_id,
        ]);
        $task->save();

        // Create SMS if checked
        if($request->cheсksms) {

            $send_result = SmsRepository::send(
                $request->name,
                User::where('id', $request->user_id)->first()->phone
            );

            $task->sms()->create([
                'text' => $request->name,
                'sender_id' => $request->user()->id,
                'recipient_id' => $request->user_id,
                'phone' => $request->phone,
                'status' => $send_result['status'],
                'error_code' => $send_result['error_code'],
            ]);
        }

        // Create Status Message
        $request->user()->notes()->create([
            //'text' =>  '"'. $request->user()->name . '"'. ' ИЗМЕНИЛ СТАТУС НА: ' . '<' . State::where('id', $request->state_id )->first()->name . '>',
            'text' =>  ' ИЗМЕНЁН СТАТУС: ' . '___' . State::where('name', 'открыта')->first()->name . '___',
            'task_id' => $task->id,
        ]);

        /*
        $request->user()->tasks()->create([
            'author_id' => $request->user()->id,
            'name' => $request->name,
            'text' => $request->text,
            'type_id' => $request->type_id,
            'priority_id' => $request->priority_id,
            'phone1' => $request->phone1,
            'fio' => $request->fio,
            'login' => $request->login,
            'uid' => $request->uid,
            'address' => $request->address,
            'user_id' => $request->taskuser,
            //'user_id' => $request->user_id, TODO
        ]);
        // Choose Task User
        $request->user()->tasks()->orderBy('created_at', 'desc')->first()->update(['user_id' => $request->taskuser,]);
        */
        
        return redirect('/tasks');
    }

    /**
     * Edit Task (Only Author can edit a task)
     *
     * @param  Task  $task
     * @return Response
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
     * @return Response
     */
    public function update(Task $task, Request $request){
        $this->authorize('edit', $task);

        // Vadidation Request TODO

        $task->update($request->all());

        return redirect('/tasks/'.$task->id.'/show');
    }

    /**
     * Destroy the given task.
     * Only Author can delete a task
     *
     * @param  Request  $request
     * @param  Task  $task
     * @return Response
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
     * @return Response
     */
    public function close(Task $task, Request $request){
        $this->authorize('close', $task);

        // Change state 'выполнена' -> 'выполнена и закрыта'
        if( $task->state->name == 'выполнена') {
            $task->update( ['state_id' => State::where('name', 'выполнена и закрыта')->first()->id]);
        }

        // Change state 'не выполнена' -> 'не выполнена и закрыта'
        if( $task->state->name == 'не выполнена') {
            $task->update( ['state_id' => State::where('name', 'не выполнена и закрыта')->first()->id]);
        }

        return redirect('/tasks/'.$task->id.'/show');
    }

    /**
     * Change Task State
     * Only Admin, Author or User of a task can change a task state)
     * @param  Task  $task
     * @param  Request  $request
     * @return Response
     */
    public function changestate (Task $task, Request $request){
        $this->authorize('changestate', $task);

        // if Admin or Author
        if( $request->user()->isAdmin() || $request->user()->id == $task->author_id ) {
            $task->update(['state_id' => $request->state_id]);
            $request->user()->notes()->create([
                //'text' =>  '"'. $request->user()->name . '"'. ' ИЗМЕНИЛ СТАТУС НА: ' . '<' . State::where('id', $request->state_id )->first()->name . '>',
                'text' =>  ' ИЗМЕНЁН СТАТУС: ' . '___' . State::where('id', $request->state_id )->first()->name . '___',
                'task_id' => $task->id,
            ]);
        }


        // if User and state is 'в работе' or 'выполнена' or 'не выполнена'
        elseif ( ($request->user()->id == $task->user_id) && ( $task->state->name == 'в работе' || $task->state->name == 'выполнена' || $task->state->name == 'не выполнена' ))
            $task->update( ['state_id' => $request->state_id]);

        return redirect('/tasks/'.$task->id.'/show');
    }
}
