@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-sm-offset-0 col-sm-10">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Новая Задача:
                </div>

                <div class="panel-body">
                    <!-- Display Validation Errors -->
                @include('common.errors')

                <!-- New Task Form -->
                    <form action="{{ url('task') }}" method="POST" class="form-horizontal">
                    {{ csrf_field() }}

                    <!-- Task Name -->
                        <div class="form-group">
                            <label for="task-name" class="col-sm-4 control-label">Название (макс 33 симвл.) *</label>

                            <div class="col-sm-6">
                                <input type="text" name="name" id="task-name" class="form-control" value="{{ old('name') }}">
                            </div>
                        </div>

                        <!-- Task Text -->
                        <div class="form-group">
                            <label for="task-text" class="col-sm-4 control-label">Описание (подробно)</label>

                            <div class="col-sm-6" >
                                <textarea name="text" id="task-text" class="form-control">{{ old('text') }}</textarea>
                            </div>
                        </div>

                        <!-- Task phone1 -->
                        <div class="form-group">
                            <label for="task-phone1" class="col-sm-4 control-label">Телефон (12 симвл.)</label>

                            <div class="col-sm-3">
                                <input type="text" name="phone1" id="task-phone1" class="form-control" placeholder="+79787554299" value="{{ old('phone1') }}">
                            </div>

                            <div class="col-sm-3">
                                <input type="checkbox" name="cheсk_client_sms" value="1"  @if(old('cheсk_client_sms', 1)) checked @endif> отправить SMS<Br>
                            </div>
                        </div>

                        <!-- Task fio -->
                        <div class="form-group">
                            <label for="task-fio" class="col-sm-4 control-label">ФИО</label>

                            <div class="col-sm-6">
                                <input type="text" name="fio" id="task-fio" class="form-control" value="{{ old('fio') }}">
                            </div>
                        </div>

                        <!-- Task address -->
                        <div class="form-group">
                            <label for="task-address" class="col-sm-4 control-label">Адрес (макс 20 симвл.)</label>

                            <div class="col-sm-6">
                                <input type="text" name="address" id="task-address" class="form-control" value="{{ old('address') }}">
                            </div>
                        </div>

                        <!-- Task Login and UID -->
                        <div class="form-group">
                            <label for="task-login" class="col-sm-4 control-label">Login (макс 20 симвл.)</label>
                            <div class="col-sm-2">
                                <input type="text" name="login" id="task-login" class="form-control" value="{{ old('login') }}">
                            </div>

                            <label for="task-uid" class="col-sm-2 control-label">UID (макс 5 цифр)</label>
                            <div class="col-sm-2">
                                <input type="text" name="uid" id="task-uid" class="form-control" value="{{ old('uid') }}">
                            </div>
                        </div>

                        <!-- Choose Type and Choose Priority -->
                        <div class="form-group">
                            <!-- Choose Type -->
                            <label for="task-type" class="col-sm-4 control-label">Тип *</label>
                            <div class="col-sm-2">
                                <p>
                                    <select size="3"  name="type_id" class="form-control">
                                        <option @if(old('type_id') == 1) selected @endif value="1">Подключение</option>
                                        <option @if(old('type_id', 2) == 2) selected @endif value="2">Ремонт</option>
                                        <option @if(old('type_id') == 3) selected @endif value="3">Задача</option>
                                    </select>
                                </p>
                            </div>
                            <!-- Choose Priority -->
                            <label for="task-priority" class="col-sm-2 control-label">Приоритет*</label>
                            <div class="col-sm-2">
                                <p>
                                    <select size="3" multiple name="priority_id" class="form-control">
                                        <option @if(old('priority_id') == 1) selected @endif value="1">Низкий</option>
                                        <option @if(old('priority_id', 2) == 2) selected @endif value="2">Нормальный</option>
                                        <option @if(old('priority_id') == 3) selected @endif value="3">Высокий</option>
                                        <option @if(old('priority_id') == 4) selected @endif value="4">Суппер Важный</option>
                                    </select>
                                </p>
                            </div>
                        </div>

                        <!-- Choose User -->
                        <div class="form-group">
                            <label for="task-priority" class="col-sm-4 control-label">Исполнитель *</label>
                            <div class="col-sm-2">
                                <!--<p><select size="7" multiple name="taskuser" class="form-control">-->
                                <p>
                                    <select size="7" multiple name="user_id" class="form-control">
                                        @foreach (\App\User::all() as $user)
                                            <option value={{$user->id}} @if(old('user_id') == $user->id) selected @endif>{{$user->name}}</option>
                                        @endforeach
                                    </select>
                                </p>

                                <input type="checkbox" name="cheсksms" value="1" @if(old('cheсksms') == 1) checked @endif> отправить SMS<Br>
                            </div>
                        </div>

                        <div class="col-sm-offset-2 col-sm-4">
                            <b>*</b> - обязательные поля
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
