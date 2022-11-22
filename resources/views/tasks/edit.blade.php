@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-sm-offset-0 col-sm-10">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Редакирование Задачи:
                </div>

                <div class="panel-body">
                    <!-- Display Validation Errors -->
                @include('common.errors')

                <!-- New Task Form -->
                    <form action="/tasks/{{$task->id}}" method="POST" class="form-horizontal">
                    {{ csrf_field() }}
                    {{method_field('PATCH')}}

                    <!-- Task Name -->
                        <div class="form-group">
                            <label for="task-name" class="col-sm-4 control-label">Название (макс 50 симвл.) *</label>

                            <div class="col-sm-6">
                                <input type="text" name="name" id="task-name" class="form-control" value="{{ $task->name }}">
                            </div>
                        </div>

                        <!-- Task Text -->
                        <div class="form-group">
                            <label for="task-text" class="col-sm-4 control-label">Описание</label>

                            <div class="col-sm-6" >
                                <textarea name="text" id="task-text" class="form-control"  value="{{ old('task') }}">{{ $task->text }}</textarea>
                            </div>
                        </div>

                        <!-- Task phone1 -->
                        <div class="form-group">
                            <label for="task-phone1" class="col-sm-4 control-label">Телефон (10 цифр)</label>

                            <div class="col-sm-6">
                                +7<input type="text" name="phone1" id="task-phone1" class="form-control" value="{{ $task->phone1 }}">
                            </div>
                        </div>

                        <!-- Task fio -->
                        <div class="form-group">
                            <label for="task-fio" class="col-sm-4 control-label">ФИО</label>

                            <div class="col-sm-6">
                                <input type="text" name="fio" id="task-fio" class="form-control" value="{{ $task->fio }}">
                            </div>
                        </div>

                        <!-- Task address -->
                        <div class="form-group">
                            <label for="task-address" class="col-sm-4 control-label">Адрес</label>

                            <div class="col-sm-6">
                                <input type="text" name="address" id="task-address" class="form-control" value="{{ $task->address }}">
                            </div>
                        </div>

                        <!-- Task Login and UID -->
                        <div class="form-group">
                            <label for="task-login" class="col-sm-4 control-label">Login</label>
                            <div class="col-sm-2">
                                <input type="text" name="login" id="task-login" class="form-control" value="{{ $task->login }}">
                            </div>

                            <label for="task-uid" class="col-sm-2 control-label">UID</label>
                            <div class="col-sm-2">
                                <input type="text" name="uid" id="task-uid" class="form-control" value="{{ $task->uid }}">
                            </div>
                        </div>

                        <!-- Choose Type and Choose Priority -->
                        <div class="form-group">
                            <!-- Choose Type -->
                            <label for="task-type" class="col-sm-4 control-label">Тип *</label>
                            <div class="col-sm-2">
                                <p><select size="3" multiple name="type_id" class="form-control">
                                    @foreach ( \App\Type::all() as $type )
                                        @if( $type->id == $task->type_id )
                                            <option selected value={{$type->id}}>{{$type->name}}</option>
                                        @else
                                            <option value={{$type->id}}>{{$type->name}}</option>
                                        @endif
                                    @endforeach
                                </select></p>
                            </div>

                            <!-- Choose Priority -->
                            <label for="task-priority" class="col-sm-2 control-label">Приоритет*</label>
                            <div class="col-sm-2">
                                <p><select size="3" multiple name="priority_id" class="form-control">
                                    @foreach ( \App\Priority::all() as $priority )
                                        @if( $priority->id == $task->priority_id )
                                            <option selected value={{$priority->id}}>{{$priority->name}}</option>
                                        @else
                                            <option value={{$priority->id}}>{{$priority->name}}</option>
                                        @endif
                                    @endforeach
                                </select></p>
                            </div>
                        </div>

                        <!-- Choose User -->
                        <div class="form-group">
                            <label for="task-priority" class="col-sm-4 control-label">Исполнитель *</label>
                            <div class="col-sm-2">
                                <p>
                                    <select size="7" multiple name="user_id" class="form-control">
                                        @foreach (\App\User::all() as $user)
                                            @if($user->id == $task->user_id)
                                                <option selected value={{$user->id}}>{{$user->name}}</option>
                                            @else
                                                <option value={{$user->id}}>{{$user->name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </p>
                                <input type="checkbox" name="cheсksms" value="1"> отправить SMS если изменился Исполнитель<br/>
                            </div>
                        </div>

                        <!-- Add Task Button -->
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-6">
                                <button type="submit" class="btn btn-default center-block">
                                    <i class="fa fa-btn fa-plus"></i>Обновить
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection