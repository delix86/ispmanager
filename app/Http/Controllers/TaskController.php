<?php

namespace App\Http\Controllers;

use App\Type;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use \App\User;
use App\Task;
use \App\State;
use App\Repositories\TaskRepository;
use App\Repositories\SmsRepository;
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
     * @param  TasksIndexRequest  $request
     * @return View
     */
    public function index(TasksIndexRequest $request)
    {
        $users = User::query()->get();
        $logins = Task::query()->distinct('login')->select('login')->get();
        $selectedStates = $request->get('states', []);
        $phone1 = $request->get('phone1', '');
        $usersIds = $request->get('users_ids', []);
        $search = $request->get('search', '');
        $date = $request->get('date', '');
        $selectedLogins = $request->get('logins', []);
        
        $tasks = $this->tasks->forUser($request->user(), $selectedStates, $selectedLogins, $usersIds, $search, $date, $phone1);

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

        // Create SMS for task USER if checked
        if($request->cheсksms) {
            $text = NULL;
            if ($request->type_id == 1) {
                $text = mb_convert_case(substr((Type::where('id', $request->type_id)->first()->name), 0, 2), MB_CASE_TITLE, "UTF-8") . ") " . $request->address . " %2B" . substr($request->phone1, -11) . " " . $request->name;
            } elseif ($request->type_id == 2) {
                $text = mb_convert_case(substr((Type::where('id', $request->type_id)->first()->name), 0, 2), MB_CASE_TITLE, "UTF-8") . ") " . $request->login . " %2B" . substr($request->phone1, -11) . " " . $request->name;
            } elseif (($request->type_id == 3)) { // TODO make javascript for left symbols in SMS for Задача
                $text = mb_convert_case(substr((Type::where('id', $request->type_id)->first()->name), 0, 2), MB_CASE_TITLE, "UTF-8") . ") " . $request->login . " %2B" . substr($request->phone1, -11) . " " . $request->name;
            }

            $send_result_text = SmsRepository::send(
                $text,
                User::where('id', $request->user_id)->first()->phone
            );
            $task->sms()->create([
                'text' => $request->name,
                'sender_id' => $request->user()->id,
                'recipient_id' => $request->user_id,
                'phone' => $request->phone1,
                'status' => $send_result_text['status'],
                'error_code' => $send_result_text['error_code'],
            ]);
        }

        // Create SMS for Client if checked and phone number present
        if( $request->cheсk_client_sms && ($task->phone1 != NULL) ) {
            $text_client = NULL;
            if ($request->type_id == 1) {
                $text_client = 'По Вашему обращению (' . $task->type->name . ') создана заявка № ' . $task->id . '. %2B' . substr(env('SUPPORT_PHONE', false), -11);
            } elseif ($request->type_id == 2) {
                $text_client = 'По Вашему обращению (' . $task->type->name . ') создана заявка № ' . $task->id . '. %2B' . substr(env('SUPPORT_PHONE', false), -11);
            } elseif (($request->type_id == 3)) { // TODO make javascript for left symbols in SMS for Задача
                $text_client = 'По Вашему обращению (' . $task->type->name . ') создана заявка № ' . $task->id . '. %2B' . substr(env('SUPPORT_PHONE', false), -11);
            }
            $send_result_text_client = SmsRepository::send(
                $text_client,
                $task->phone1
            );
            $task->sms()->create([
                'text' => $text_client,
                'sender_id' => $request->user()->id,
                //'recipient_id' => $request->user_id,
                'phone' => $request->phone1,
                'status' => $send_result_text_client['status'],
                'error_code' => $send_result_text_client['error_code'],
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
     * @return RedirectResponse
     * @throws
     */
    public function update(Task $task, Request $request){
        $this->authorize('edit', $task);

        $oldUserId =  $task->user_id;

        // Vadidation Request TODO

        $task->update($request->all());

        // If USER changed
        if ($oldUserId != $task->user_id) {
            // Create SMS for task USER if checked
            if($request->cheсksms) {
                $text = NULL;
                if ($request->type_id == 1) {
                    $text = mb_convert_case(substr((Type::where('id', $request->type_id)->first()->name), 0, 2), MB_CASE_TITLE, "UTF-8") . ") " . $request->address . " %2B" . substr($request->phone1, -11) . " " . $request->name;
                } elseif ($request->type_id == 2) {
                    $text = mb_convert_case(substr((Type::where('id', $request->type_id)->first()->name), 0, 2), MB_CASE_TITLE, "UTF-8") . ") " . $request->login . " %2B" . substr($request->phone1, -11) . " " . $request->name;
                } elseif (($request->type_id == 3)) { // TODO make javascript for left symbols in SMS for Задача
                    $text = mb_convert_case(substr((Type::where('id', $request->type_id)->first()->name), 0, 2), MB_CASE_TITLE, "UTF-8") . ") " . $request->login . " %2B" . substr($request->phone1, -11) . " " . $request->name;
                }

                $send_result_text = SmsRepository::send(
                    $text,
                    User::where('id', $request->user_id)->first()->phone
                );
                $task->sms()->create([
                    'text' => $request->name,
                    'sender_id' => $request->user()->id,
                    'recipient_id' => $request->user_id,
                    'phone' => $request->phone1,
                    'status' => $send_result_text['status'],
                    'error_code' => $send_result_text['error_code'],
                ]);
            }
        }

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

            // Add a note with changed status
            $request->user()->notes()->create([
                //'text' =>  '"'. $request->user()->name . '"'. ' ИЗМЕНИЛ СТАТУС НА: ' . '<' . State::where('id', $request->state_id )->first()->name . '>',
                'text' =>  ' ИЗМЕНЁН СТАТУС: ' . '___' . State::where('id', $task->state_id )->first()->name . '___',
                'task_id' => $task->id,
            ]);
        }

        // Change state 'не выполнена' -> 'не выполнена и закрыта'
        if( $task->state->name == 'не выполнена') {
            $task->update( ['state_id' => State::where('name', 'не выполнена и закрыта')->first()->id]);

            // Add a note with changed status
            $request->user()->notes()->create([
                //'text' =>  '"'. $request->user()->name . '"'. ' ИЗМЕНИЛ СТАТУС НА: ' . '<' . State::where('id', $request->state_id )->first()->name . '>',
                'text' =>  ' ИЗМЕНЁН СТАТУС: ' . '___' . State::where('id', $task->state_id )->first()->name . '___',
                'task_id' => $task->id,
            ]);
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

            // Add a note with changed status
            $request->user()->notes()->create([
                //'text' =>  '"'. $request->user()->name . '"'. ' ИЗМЕНИЛ СТАТУС НА: ' . '<' . State::where('id', $request->state_id )->first()->name . '>',
                'text' =>  ' ИЗМЕНЁН СТАТУС: ' . '___' . State::where('id', $request->state_id )->first()->name . '___',
                'task_id' => $task->id,
            ]);
        }

        // if User of task
        // and state of task is 'открыта' or 'в работе'
        // and $request->state_id is 'в работе' or 'выполнена' or 'не выполнена'
        elseif ( ($request->user()->id == $task->user_id) &&
                ( $task->state->name == 'открыта' || $task->state->name == 'в работе' ) &&
                ( $request->state_id == State::where( 'name', 'в работе')->first()->id || $request->state_id == State::where( 'name', 'выполнена')->first()->id
                    || $request->state_id == State::where( 'name', 'не выполнена')->first()->id ))
        {
            $task->update(['state_id' => $request->state_id]);

            // Add a note with changed status
            $request->user()->notes()->create([
                'text' =>  ' ИЗМЕНЁН СТАТУС: ' . '___' . State::where('id', $request->state_id )->first()->name . '___',
                'task_id' => $task->id,
            ]);
        }

        return redirect('/tasks/'.$task->id.'/show');
    }
}
