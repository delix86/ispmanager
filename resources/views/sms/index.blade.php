@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>SMS Сообщения</h1>
        <div class="">

            <!-- SMS -->
            <div class="panel panel-default">

                <div class="panel-heading row">

                    @include('common.errors')

                    <div class="col-md-4"></div>

                    <div class="col-md-4">
                        <h5 class="text-center">Отправить SMS</h5>
                        <form action="/sms/add" method="post">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="text" class="control-label">Текст (максимум 500):</label>
                                <textarea id="text" name="text" class="form-control" rows="8">{{ old('text') }}</textarea>
                            </div>
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
                            <div class="form-group">
                                <label for="phone" class="control-label">Номер телефона:</label>
                                <input id="phone" name="phone" class="form-control" value="{{ old('phone') }}"/>
                            </div>
                            <div class="form-group">
                                <input type="submit" value="Отправить" class="form-control"/>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-4"></div>

                </div>

            </div>

            <!-- SMS -->
            <div class="panel panel-default">

                <div class="panel-heading">
                    <div class='btn-group pull-left col-xs-1'>
                        <a title="Добавить" class='hidden-print' href="/sms/add" ><span class='glyphicon glyphicon-plus'></span></a>
                        <a title="Поиск" class='hidden-print' href="#" ><span class='glyphicon glyphicon-search'></span></a>
                    </div>
                    <div class='btn-group btn-group-xs' role='group'>
                        <a title="Все" class='btn  btn-default  ' href="#" >Все</a>
                        <a title="Открыта" class='btn  btn-default ' href="#" >Открыта</a>
                        <a title="В работе" class='btn  btn-default  ' href="#" >В обработке</a>
                        <a title="Приостановление" class='btn  btn-default  ' href="#" >Приостановление</a>
                        <a title="Выполнена и закрыта" class='btn  btn-default  ' href="#" >Выполнена и закрыта</a>
                        <a title="Не выполнена и закрыта" class='btn  btn-default  ' href="#" >Не выполнена и закрыта</a>

                    </div>
                </div>

                <div class="panel-body">
                    <table class="table  task-table">
                        <thead>
                            <tr>
                                <th class="table-text"><div><b>№</b></div></th>
                                <th class="table-text"><div><b>Текст</b></div></th>
                                <th class="table-text"><div><b>От кого</b></div></th>
                                <th class="table-text"><div><b>Кому</b></div></th>
                                <th class="table-text"><div><b>№ Телефона</b></div></th>
                                <th class="table-text"><div><b>Задача</b></div></th>
                                <th class="table-text"><div><b>Отправлено</b></div></th>
                                <th class="table-text"><div><b>Ошибка</b></div></th>
                                <th class="table-text"><div><b>Дата</b></div></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($smses as $sms)
                                <tr @if($sms->status) class="" @else class="danger" @endif>
                                    <td class="table-text"><div>{{ $sms->id }}</div></td>
                                    <td class="table-text"><div><a href="/tasks/{{$sms->id}}/show" >{{ $sms->text }}</a></div></td>
                                    <td class="table-text"><div>@if($sms->sender){{ $sms->sender->name }}@endif</div></td>
                                    <td class="table-text"><div>@if($sms->recipient){{ $sms->recipient->name }}@endif</div></td>
                                    <td class="table-text"><div>{{ $sms->phone }}</td>
                                    <td class="table-text"><div>@if($sms->task){{ $sms->task->name }}@endif</div></td>
                                    <td class="table-text"><div><div>@if($sms->status)<span class="glyphicon glyphicon-ok-sign"></span> Да @else <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> Нет@endif</div></div></td>
                                    <td class="table-text"><div><div>@if($sms->error_code) {{ $sms->getErrorText() }}@endif</div></td>
                                    <td class="table-text"><div>{{ $sms->created_at }}</div></td>
                                </tr>
                            @endforeach

                        </tbody>
                        {{ $smses->links() }}
                    </table>
                    {{ $smses->links() }}
                </div>

            </div>
        </div>
    </div>
@endsection
