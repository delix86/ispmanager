@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>SMS Сообщения</h1>
        <div class="">

            <!-- SMS -->
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            @include('common.errors')
                            Отправить SMS
                        </div>
                        <div class="panel-body">
                            <form id="sendtextsms__from" action="/sms/add" method="post">
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
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label for="phone" class="control-label">Номер телефона:</label>
                                        </div>
                                        <div class="col-xs-12 col-sm-6" style="margin-bottom: 15px;">
                                            <input id="phone" name="phone" class="form-control" placeholder="+79781234567" value="{{ old('phone') }}"/>
                                        </div>
                                        <div class="col-xs-12 col-sm-6">
                                            <select class="form-control">
                                                <option  value="">-- Не выбран --</option>
                                                @foreach (\App\User::all() as $user)
                                                    <option value="{{$user->phone}}">{{$user->fio}} {{$user->phone }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-12" style="text-align: center">
                                            <input type="submit" value="Отправить" class="btn btn-primary" style="width: 200px;"/>
                                        </div>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Отправить Логин и Пароль
                        </div>
                        <div class="panel-body">
                            @include('common.errors')
                            <form id="sendloginsms__from" action="/sms/sendlogin" method="post">
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

                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label for="phone" class="control-label">Номер телефона:</label>
                                    </div>
                                    <div class="col-xs-12 col-sm-6" style="margin-bottom: 15px;">
                                        <input id="phone" name="phone" class="form-control" placeholder="+79781234567" value="{{ old('phone') }}"/>
                                    </div>
                                    <div class="col-xs-12 col-sm-6">
                                        <select class="form-control">
                                            <option  value="">-- Не выбран --</option>
                                            @foreach (\App\User::all() as $user)
                                                <option value="{{$user->phone}}">{{$user->fio}} {{$user->phone }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-12" style="text-align: center">
                                        <input type="submit" value="Отправить" class="btn btn-primary" style="width: 200px;"/>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            @include('sms.list', ['smses' => $smses, 'class' => ''])
        </div>
    </div>

    @push('scripts')
    <script>
      $(document).ready(function() {
        $('#sendtextsms__from select').on('change', function (e) {
          console.log(1);
            $('#sendtextsms__from').find('input[name=phone]').val($(this).val());
            return false;
        });
        $('#sendloginsms__from select').on('change', function (e) {
          console.log(1);
          $('#sendloginsms__from').find('input[name=phone]').val($(this).val());
          return false;
        });
      });
    </script>
    @endpush
@endsection
