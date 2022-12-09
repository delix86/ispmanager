<?php

namespace App\Services;

use App\Repositories\SmsRepository;
use App\State;
use App\Task;
use App\Type;
use App\User;

class TasksService
{
    protected $logger;

    public function __construct()
    {
        $this->logger = new HistoryLogger();
    }

    public function create($data)
    {
        /** @var User $worker */
        $worker = User::query()->firstOrFail($data['user_id']);

        // return var_dump($request->all());

        $task = Task::create([
            'author_id' => request()->user()->id,
            'name' => $data['name'],
            'text' => $data['text'],
            'type_id' => $data['type_id'],
            'priority_id' => $data['priority_id'],
            'phone1' => $data['phone1'],
            'fio' => $data['fio'],
            'login' => $data['login'],
            'uid' => $data['uid'],
            'address' => $data['address'],
            'user_id' => $data['user_id'],
        ]);
        $task->save();

        // Create SMS for task USER if checked
        if(isset($data['cheсksms']) && $data['cheсksms']) {
            $text = NULL;
            if ($data['type_id'] == 1) {
                $text = mb_convert_case(substr((Type::where('id', $task->type_id)->first()->name), 0, 2), MB_CASE_TITLE, "UTF-8") . ") " . $task->address . " %2B" . substr($task->phone1, -11) . " " . $task->name;
            } elseif ($data['type_id'] == 2) {
                $text = mb_convert_case(substr((Type::where('id', $task->type_id)->first()->name), 0, 2), MB_CASE_TITLE, "UTF-8") . ") " . $task->login . " %2B" . substr($task->phone1, -11) . " " . $task->name;
            } elseif (($data['type_id'] == 3)) { // TODO make javascript for left symbols in SMS for Задача
                $text = mb_convert_case(substr((Type::where('id', $task->type_id)->first()->name), 0, 2), MB_CASE_TITLE, "UTF-8") . ") " . $task->login . " %2B" . substr($task->phone1, -11) . " " . $task->name;
            }

            if (env('SMS_ACTIVE')) {
                $send_result_text = SmsRepository::send(
                    $text,
                    $worker->phone
                );
            } else {
                $send_result_text = [
                    'status' => 0,
                    'error_code' => 'SMS система не активирована'
                ];
            }
            $task->sms()->create([
                'text' => $task->name,
                'sender_id' => request()->user()->id,
                'recipient_id' => $task->user_id,
                'phone' => $worker->phone,
                'status' => $send_result_text['status'],
                'error_code' => $send_result_text['error_code'],
            ]);
        }

        // Create SMS for Client if checked and phone number present
        if( isset($data['cheсk_client_sms']) && $data['cheсk_client_sms'] && ($task->phone1 != NULL) ) {
            $text_client = NULL;
            if ($data['type_id'] == 1) {
                $text_client = 'По Вашему обращению (' . $task->type->name . ') создана заявка № ' . $task->id . '. %2B' . substr(env('SUPPORT_PHONE', false), -11);
            } elseif ($data['type_id'] == 2) {
                $text_client = 'По Вашему обращению (' . $task->type->name . ') создана заявка № ' . $task->id . '. %2B' . substr(env('SUPPORT_PHONE', false), -11);
            } elseif (($data['type_id'] == 3)) { // TODO make javascript for left symbols in SMS for Задача
                $text_client = 'По Вашему обращению (' . $task->type->name . ') создана заявка № ' . $task->id . '. %2B' . substr(env('SUPPORT_PHONE', false), -11);
            }

            if (env('SMS_ACTIVE')) {
                $send_result_text_client = SmsRepository::send(
                    $text_client,
                    $task->phone1
                );
            } else {
                $send_result_text_client = [
                    'status' => 0,
                    'error_code' => 'SMS система не активирована'
                ];
            }

            $task->sms()->create([
                'text' => $text_client,
                'sender_id' => request()->user()->id,
                //'recipient_id' => $request->user_id,
                'phone' => $task->phone1,
                'status' => $send_result_text_client['status'],
                'error_code' => $send_result_text_client['error_code'],
            ]);
        }

        // Create Status Message
        request()->user()->notes()->create([
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
    }

    public function update(Task $task, $data)
    {
        /** @var User $worker */
        $worker = User::query()->findOrFail($data['user_id']);

        // Vadidation Request TODO

        $oldTask = $task;
        $task->fill($data);

        if (!empty($task->getDirty())) {
            $changes = $task->getDirty();

            $this->createRevision($oldTask);
        }

        $task->save();

        // If USER changed
        if (isset($changes['user_id'])) {

            // Create SMS for task USER if checked
            if(isset($data['cheсksms']) && $data['cheсksms']) {
                $text = NULL;
                if ($data['type_id'] == 1) {
                    $text = mb_convert_case(substr((Type::where('id', $data['type_id'])->first()->name), 0, 2), MB_CASE_TITLE, "UTF-8") . ") " .$data['address'] . " %2B" . substr($data['phone1'], -11) . " " . $data['name'];
                } elseif ($data['type_id'] == 2) {
                    $text = mb_convert_case(substr((Type::where('id', $data['type_id'])->first()->name), 0, 2), MB_CASE_TITLE, "UTF-8") . ") " . $data['login'] . " %2B" . substr($data['phone1'], -11) . " " . $data['name'];
                } elseif ($data['type_id'] == 3) { // TODO make javascript for left symbols in SMS for Задача
                    $text = mb_convert_case(substr((Type::where('id', $data['type_id'])->first()->name), 0, 2), MB_CASE_TITLE, "UTF-8") . ") " . $data['login'] . " %2B" . substr($data['phone1'], -11) . " " . $data['name'];
                }



                if (env('SMS_ACTIVE')) {
                    $send_result_text = SmsRepository::send(
                        $text,
                        $worker->phone
                    );
                } else {
                    $send_result_text = [
                        'status' => 0,
                        'error_code' => 'SMS система не активирована'
                    ];
                }

                $task->sms()->create([
                    'text' => $data['name'],
                    'sender_id' => request()->user()->id,
                    'recipient_id' => $task->user_id,
                    'phone' => $worker->phone,
                    'status' => $send_result_text['status'],
                    'error_code' => $send_result_text['error_code'],
                ]);
            }

            $this->logger->log($task->getKey(), 'change_user', $worker->getKey(), request()->user()->id);

            // Add a note with changed worker
            request()->user()->notes()->create([
                //'text' =>  '"'. $request->user()->name . '"'. ' ИЗМЕНИЛ СТАТУС НА: ' . '<' . State::where('id', $request->state_id )->first()->name . '>',
                'text' =>  ' ИЗМЕНЁН ИСПОЛНИТЕЛЬ: ' . $worker->fio,
                'task_id' => $task->id,
            ]);
        }

        if (isset($changes['name'])) {
            $this->logger->log($task->getKey(), 'change_name_task', $changes['name'], request()->user()->id);

            // Add a note with changed name
            request()->user()->notes()->create([
                //'text' =>  '"'. $request->user()->name . '"'. ' ИЗМЕНИЛ СТАТУС НА: ' . '<' . State::where('id', $request->state_id )->first()->name . '>',
                'text' =>  ' ИЗМЕНЁНО НАЗВАНИЕ: ' . $changes['name'] ,
                'task_id' => $task->id,
            ]);
        }

        return $task;
    }

    /**
     * @param Task $task
     * @throws \Exception
     */
    public function close(Task $task)
    {
        $oldTask = $task;

        if( $task->state->name != 'выполнена' && $task->state->name != 'не выполнена') {
            throw new \Exception('Нельзя закрыть заявку, заявка имеет неподходящий статус');
        }

        // Change state 'выполнена' -> 'выполнена и закрыта'
        if( $task->state->name == 'выполнена') {
            /** @var State $state */
            $state = State::query()->where('name', 'выполнена и закрыта')->firstOrFail();
        }
        // Change state 'не выполнена' -> 'не выполнена и закрыта'
        elseif( $task->state->name == 'не выполнена') {
            /** @var State $state */
            $state = State::query()->where('name', 'не выполнена и закрыта')->firstOrFail();
        }

        $task->fill(['state_id' => $state->getKey()]);

        if (!empty($task->getDirty())) {
            $this->createRevision($oldTask);
        }

        $task->save();

        $this->logger->log($task->getKey(), 'close_task', $state->getKey(), request()->user()->id);

        // Add a note with changed status
        request()->user()->notes()->create([
            //'text' =>  '"'. $request->user()->name . '"'. ' ИЗМЕНИЛ СТАТУС НА: ' . '<' . State::where('id', $request->state_id )->first()->name . '>',
            'text' =>  ' ИЗМЕНЁН СТАТУС: ' . '___' . $state->name . '___',
            'task_id' => $task->id,
        ]);
    }

    public function changeState($task, $request)
    {
        $oldTask = $task;
        $task->fill(['state_id' => $request->state_id]);

        if (!empty($task->getDirty())) {
            $changes = $task->getDirty();

            $this->createRevision($oldTask);
        }

        // if Admin or Author
        if( $request->user()->isAdmin() || $request->user()->id == $task->author_id ) {
            $task->save();

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
            $task->save();

            // Add a note with changed status
            $request->user()->notes()->create([
                'text' =>  ' ИЗМЕНЁН СТАТУС: ' . '___' . State::where('id', $request->state_id )->first()->name . '___',
                'task_id' => $task->id,
            ]);
        }
    }

    /**
     * @param Task $task
     * @throws \Exception
     */
    public function delete(Task $task)
    {
        $user = request()->user();

        $this->logger->log($task->getKey(), 'delete_task', $user->id, $user->id);

        /** @var State $state */
        $state = State::query()->where('name', 'удалена')->firstOrFail();

        // Add a note with changed status
        $user->notes()->create([
            //'text' =>  '"'. $request->user()->name . '"'. ' ИЗМЕНИЛ СТАТУС НА: ' . '<' . State::where('id', $request->state_id )->first()->name . '>',
            'text' =>  ' ИЗМЕНЁН СТАТУС: ' . '___' . $state->name . '___',
            'task_id' => $task->id,
        ]);

        $task->delete();
    }

    protected function createRevision(Task $oldTask)
    {
        $revisionTask = new Task();
        $revisionTask->fill($oldTask->getAttributes());
        $revisionTask->parent_id = $oldTask->getKey();
        $revisionTask->save();
    }
}