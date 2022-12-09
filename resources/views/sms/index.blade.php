@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>SMS Сообщения</h1>
        <div class="">

            <!-- SMS -->
            <div class="panel panel-default">
                <div class="panel-heading row">
                    @include('common.errors')

                    <div class="col-md-4">
                        <h5 class="text-center">Отправить SMS</h5>
                        <form action="/sms/add" method="post">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="text" class="control-label">Текст (максимум 500):</label>
                                <textarea id="text" name="text" class="form-control" rows="8">{{ old('text') }}</textarea>
                            </div>
                            <!--
                            <div class="form-group">
                                <label for="users" class="control-label">Кому:</label>
                                <select id="users" name="recipient_id" class="form-control" value="{{ old('recipient_id') }}">
                                    @foreach ($users as $user)
                                        <option value="{{$user->id}}">
                                            {{$user->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            -->
                            <div class="form-group">
                                <label for="phone" class="control-label">Номер телефона:</label>
                                <input id="phone" name="phone" class="form-control" placeholder="+79781234567" value="{{ old('phone') }}"/>
                            </div>
                            <div class="form-group">
                                <input type="submit" value="Отправить" class="form-control"/>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-4">
                        <h5 class="text-center">Отправить Логин и Пароль</h5>
                        <form action="/sms/sendlogin" method="post">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="login" class="control-label">Логин:</label>
                                <input id="login" name="login" class="form-control" value="{{ old('login') }}"/>
                            </div>
                            <div class="form-group">
                                <label for="pass" class="control-label">Пароль:</label>
                                <input id="pass" name="pass" class="form-control" value="{{ old('pass') }}"/>
                            </div>
                            <div class="form-group">
                                <label for="uid" class="control-label">UID:</label>
                                <input id="uid" name="uid" class="form-control" value="{{ old('uid') }}"/>
                            </div>
                            <div class="form-group">
                                <label for="phone" class="control-label">Номер телефона:</label>
                                <input id="phone" name="phone" class="form-control" placeholder="+79781234567" value="{{ old('phone') }}"/>
                            </div>
                            <div class="form-group">
                                <input type="submit" value="Отправить" class="form-control"/>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

            @include('sms.list', $smses)
        </div>
    </div>
@endsection
