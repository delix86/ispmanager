<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Sms;

use App\User;

use App\Repositories\SmsRepository;

class SmsController extends Controller
{
    /**
     * The sms repository instance.
     *
     * @var SmsRepository
     */
    protected $sms;

    /**
     * Create a new controller instance.
     *
     * @param  SmsRepository  $sms
     * @return void
     */
    public function __construct(SmsRepository $sms)
    {
        $this->middleware('auth');

        $this->sms = $sms;
    }

    /**
     * Display a list of all of the user's sms.
     *
     * @param  Request  $request
     * @return Response
     */
    public function index(Request $request)
    {
        return view('sms.index', [
            'smses' => $this->sms->forUser($request->user()),
            'users' => User::all(),
        ]);
    }

    public function add(Request $request) {

        if($request && $request->all()) {

            $this->validate($request, [
                'text' => 'required|max:500',
                'recipient_id' => 'sometimes|exists:users,id',
                'task_id' => 'sometimes|exists:tasks,id',
                'phone' => array('regex:/^\+[0-9]{11}$/'),
            ]);

            $send_result = SmsRepository::send(
                $request->text,
                $request->phone
            );

            $sms = Sms::create([
                'text' => $request->text,
                'sender_id' => $request->user()->id,
                'recipient_id' => $request->recipient_id,
                'phone' => $request->phone,
                'task_id' => $request->task_id,
                'status' => $send_result['status'],
                'error_code' => $send_result['error_code'],
            ]);

        }

        return redirect('/sms');
    }

    public function sendlogin(Request $request) {

        if($request && $request->all()) {

            $this->validate($request, [
                'login' => 'required|max:20',
                'pass' => 'required|max:20',
                'uid' => 'integer|required',
                'recipient_id' => 'sometimes|exists:users,id',
                'task_id' => 'sometimes|exists:tasks,id',
                'phone' => array('regex:/^\+[0-9]{11}$/'),
            ]);

            $text = 'Логин: ' . $request->login . ' Пароль: ' . $request->pass . ' Код: ' . $request->uid . ' Сайт: ' . env ('WEB_SITE', false);

            $send_result = SmsRepository::send(
                $text,
                $request->phone
            );

            $sms = Sms::create([
                'text' => $text ,
                'sender_id' => $request->user()->id,
                'recipient_id' => $request->recipient_id,
                'phone' => $request->phone,
                'task_id' => $request->task_id,
                'status' => $send_result['status'],
                'error_code' => $send_result['error_code'],
            ]);

        }

        return redirect('/sms');
    }
}
