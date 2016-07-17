<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;
use App\Note;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class NoteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a Note.
     *
     * @param  Task  $task
     * @param  Request  $request
     * @return Response
     */
    public function store(Task $task, Request $request)
    {
        $this->validate($request, [
            'notetext' => 'required|max:255',
        ]);

        $request->user()->notes()->create([
            'text' => $request->notetext,
            'task_id' => $task->id,
        ]);

        return back();
    }

    /**
     * Edit a Note.
     *
     * @param  Note  $note
     * @return Response
     */
    public function edit (Note $note){
        return view('notes.edit', compact('note'));
    }

    /**
     * Update a Note.
     *
     * @param  Note  $note
     * @param  Request  $request
     * @return Response
     */
    public function update (Note $note, Request $request){
        $note->update($request->all());

        $task = $note->task;

        return redirect('/tasks/'.$task->id.'/show');
    }
}
