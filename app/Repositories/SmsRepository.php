<?php

namespace App\Repositories;

use App\Task;
use App\User;
use App\Sms;
use Illuminate\Pagination\LengthAwarePaginator;
use Ixudra\Curl\Facades\Curl;

class SmsRepository
{
    /**
     * Get all of the sms for a given user.
     *
     * @param  User  $user
     * @return LengthAwarePaginator
     */
    public function forUser(User $user)
    {
        if ($user->isAdmin()) {
            return Sms::orderBy('created_at', 'dsc')->paginate(25);
        } elseif ($user->isSupport()){
            return Sms::orderBy('created_at', 'dsc')->paginate(25);
        }elseif ($user->isWorker()){
            return Sms::where( 'sender_id' , $user->id )->orWhere('recipient_id' , $user->id)->orderBy('created_at', 'dsc')->paginate(25); // return all tasks
        }
    }

    /**
     * Get all of the sms for a given task.
     *
     * @param  Task  $task
     * @return LengthAwarePaginator
     */
    public function forTask(Task $task)
    {
        return Sms::query()->where( 'task_id' , $task->id )->orderBy('created_at', 'dsc')->paginate(25);
    }

    public static function send($text, $phone_number) {

        $result = [];

        $login = env('SMS_USERNAME', false);
        $psw = env('SMS_PASSWORD', false);
        $url = 'http://smsc.ru/sys/send.php?login='.$login.'&psw='.$psw.'&phones='.$phone_number.'&mes='.$text.'&fmt=3&charset=utf-8';

        //echo var_dump( $url);
        //die();

        $response = Curl::to($url)->get();

        $response_obj = json_decode($response);

        if(isset($response_obj->error)) {
            $result['status'] = 0;
            $result['error'] = $response_obj->error;
            $result['error_code'] = $response_obj->error_code;
            $result['id'] = isset($response_obj->id) ? $response_obj->id : null;

            \Illuminate\Support\Facades\Log::error($response);

        } else {
            $result['status'] = 1;
            $result['id'] = $response_obj->id;
            $result['error'] = false;
            $result['error_code'] = '';
        }

        return $result;

    }

}
