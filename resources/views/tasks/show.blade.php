@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-sm-offset-2 col-sm-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Задача № {{ $task->id }}
                </div>

                <div class="panel-body">
                    <!-- Display Validation Errors -->
                @include('common.errors')

                    <!-- Task name -->
                    <div class="row">
                        <label for="task-name" class="col-sm-2 control-label">Название</label>
                        <div class="col-sm-6">{{ $task->name }}</div>
                    </div>

                    <!-- Task text -->
                    <div class="row">
                        <label for="task-text" class="col-sm-2 control-label">Описание</label>
                        <div class="col-sm-8" >{{ $task->text }}</div>
                    </div>

                    <!-- Task type -->
                    <div class="row">
                        <label for="task-type" class="col-sm-2 control-label">Тип</label>
                        <div class="col-sm-6">{{ $task->type->name }}</div>
                    </div>

                    <!-- Task priority -->
                    <div class="row">
                        <label for="task-priority" class="col-sm-2 control-label">Приоритет</label>
                        <div class="col-sm-6">{{ $task->priority->name }}</div>
                    </div>


                    <!-- Task state -->
                    <div class="row">
                        <label for="task-state" class="col-sm-2 control-label">Состояние</label>
                        <div class="col-sm-3">{{ $task->state->name }}</div>

                        <!-- Choose State -->
                        @if(( $request->user()->id == $task->author_id ) ||
                            ( $request->user()->isAdmin() ) ||
                            ( ($request->user()->id == $task->user_id) && ( $task->state->name == 'открыта' || $task->state->name == 'в работе' )) )
                            <form action="{{url('tasks/' . $task->id) . '/changestate'}}" method="POST" class="form-horizontal">
                                {{ csrf_field() }}
                                <div class="col-sm-4">
                                    <p><select size="6" multiple name="state_id" class="form-control">
                                            @foreach ( \App\State::all() as $state )
                                                @if(( $request->user()->id == $task->author_id ) || ( $request->user()->isAdmin() ))
                                                    @if( $state->id == $task->state_id )
                                                        <option selected value={{$state->id}}>{{$state->name}}</option>
                                                    @else
                                                        <option value={{$state->id}}>{{$state->name}}</option>
                                                    @endif
                                                @elseif($request->user()->id == $task->user_id )
                                                    @if( $state->name == 'в работе' || $state->name == 'выполнена' || $state->name == 'не выполнена')
                                                        @if( $state->id == $task->state_id )
                                                            <option selected value={{$state->id}}>{{$state->name}}</option>
                                                        @else
                                                            <option value={{$state->id}}>{{$state->name}}</option>
                                                        @endif
                                                    @endif
                                                @endif
                                            @endforeach
                                        </select></p>
                                </div>

                                <!-- Add Task Button -->
                                <div class="form-group">
                                    <div class="col-sm-1">
                                        <button type="submit" class="btn btn-default center-block">
                                            <i class="fa fa-btn fa-exchange"></i>Изменить
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>

                    <!-- Task phone1 -->
                    <div class="row">
                        <label for="task-phone1" class="col-sm-2 control-label">Телефон</label>
                        <div class="col-sm-6">{{ $task->phone1 }}</div>
                    </div>

                    <!-- Task fio -->
                    <div class="row">
                        <label for="task-fio" class="col-sm-2 control-label">ФИО</label>
                        <div class="col-sm-6">{{ $task->fio }}</div>
                    </div>

                    <!-- Task address -->
                    <div class="row">
                        <label for="task-address" class="col-sm-2 control-label">Адрес</label>
                        <div class="col-sm-6"> {{ $task->address }}</div>
                    </div>

                    <!-- Task Login and UID -->
                    <div class="row">
                        <label for="task-login" class="col-sm-2 control-label">Login</label>
                        <div class="col-sm-2"><a target="_blank" href="{{ env('ABILLS_URL', false) }}/#clientcard/{{$task->uid}}">{{$task->login}}</a></div>

                        <label for="task-uid" class="col-sm-offset-1 col-sm-1 control-label">UID</label>
                        <div class="col-sm-2"><a target="_blank" href="{{ env('ABILLS_URL', false) }}/#clientcard/{{$task->uid}}">{{$task->uid}}</a></div>
                    </div>

                    <!-- Task Author -->
                    <div class="row">
                        <label for="task-login" class="col-sm-2 control-label">Автор</label>
                        <div class="col-sm-2"><a href="/tasks?authors_ids[]={{App\User::find($task->author_id)->id}}">{{App\User::find($task->author_id)->fio}}</a></div>
                    </div>

                    <!-- Task USER -->
                    <div class="row">
                        <label for="task-login" class="col-sm-offset-0 col-sm-2 control-label">Исполнитель</label>
                        <div class="col-sm-2"><a href="/tasks?users_ids[]={{$task->user->id}}">{{$task->user->fio}}</a></div>
                    </div>

                    <!-- Task Date and Update date -->
                    <div class="row">
                        <label for="task-login" class="col-sm-2 control-label">Создана</label>
                        <div class="col-sm-2">{{ $task->created_at }}</div>

                        <label for="task-uid" class="col-sm-offset-1 col-sm-2 control-label">Обновленна</label>
                        <div class="col-sm-2">{{ $task->updated_at }}</div>
                    </div>

                    <hr>

                    <!-- Close Task Button -->
                    <!-- Coment TODO -->
                    @if( ( $request->user()->id == $task->author_id || $request->user()->isAdmin() ) && ( $task->state->name == 'выполнена' || $task->state->name == 'не выполнена' ) )
                        <div class="btn-group">
                            <form action="{{url('tasks/' . $task->id . '/close')}}" method="POST">
                                {{ csrf_field() }}

                                <button title="Закрыть" type="submit" id="close-task-{{ $task->id }}" class="btn btn-success" class="pull-right">
                                    <i class="fa fa-btn fa-check"></i>Закрыть
                                </button>
                            </form>
                        </div>
                    @endif

                    <!-- Edit Task Button -->
                    @if(  $request->user()->id == $task->author_id || $request->user()->isAdmin() )
                        <div class="btn-group">
                            <form action="/tasks/{{$task->id}}/edit" method="POST">
                                {{ csrf_field() }}

                                <button title="Редактировать" type="submit" id="edit-task-{{ $task->id }}" class="btn btn-primary">
                                    <i class="fa fa-btn fa-pencil"></i>Редактировать
                                </button>
                            </form>

                        </div>
                    @endif

                    <!-- Delete Task Button -->
                    @if( $request->user()->id == $task->author_id || $request->user()->isAdmin() )
                        <div class="btn-group">
                            <!--<form class="delete" action="{{url('task/' . $task->id)}}" method="POST">-->
                            <form class="delete" action="{{url('task/' . $task->id)}}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}

                                <button title="Удалить" type="submit" id="delete-task-{{ $task->id }}" class="btn btn-danger" class="pull-right">
                                    <i class="fa fa-btn fa-trash"></i> Удалить
                                </button>
                            </form>
                            <!--<script>
                                $(".delete").on("submit", function(){
                                    return confirm("Do you want to delete this item?");
                                });
                            </script>-->
                        </div>
                    @endif
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    Ход выполнения задачи
                </div>

                <div class="panel-body">
                    <!-- Show Notes if present -->
                    @if (count($task->notes) > 0)
                        <div class="row">
                            <div class="col-md-12 col-md-offset-0">
                                <ul class="list-group">
                                    @foreach( $task->notes as $note)
                                        <li class="list-group-item">
                                            <!-- Edit Note Button -->
                                            <div class="form-group pull-right">
                                                <form action="/notes/{{$note->id}}/edit" method="POST">
                                                    {{ csrf_field() }}
                                                    <button title="Редактировать" type="submit" id="edit-note-{{ $note->id }}" class="btn btn-primary btn-xs">
                                                        <i class="fa fa-btn fa-pencil"></i>
                                                    </button>
                                                </form>
                                            </div>

                                            [{{ $note->created_at }}] {{ $note->text }}
                                            <div class="pull-right"><label class="control-label">{{$note->user->name}} </label></div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                    <hr>

                    <!-- New Note Form -->
                    <!-- Display Validation Errors -->
                    @include('common.errors')
                    <form action="/tasks/{{$task->id}}/notes" method="POST" class="form-horizontal">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="task-note" class="col-sm-4 control-label">Новая запись (исполнитель*)</label>
                            <div class="col-sm-6" ><textarea name="notetext" id="task-note" class="form-control"  value="{{ old('note') }}"></textarea></div>
                        </div>

                        <!-- Add Task Button -->
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-6">
                                <button type="submit" class="btn btn-default center-block">
                                    <i class="fa fa-btn fa-plus"></i>Добавить
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
