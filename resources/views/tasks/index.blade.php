@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Задачи</h1>
        <div class="">
            <!-- Current Tasks -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class='btn-group pull-left col-xs-1'>
                        <a title="Добавить" class='hidden-print' href="/tasks/add" ><span class='glyphicon glyphicon-plus'></span></a>
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
                                <th class="table-text"><div><b>Назавание</b></div></th>
                                <th class="table-text"><div><b>Логин</b></div></th>
                                <th class="table-text"><div><b>Адрес</b></div></th>
                                <th class="table-text"><div><b>Тип</b></div></th>
                                <th class="table-text"><div><b>Исполнитель</b></div></th>
                                <th class="table-text"><div><b>Просмтр</b></div></th>
                                <th class="table-text"><div><b>Приоритет</b></div></th>
                                <th class="table-text"><div><b>Статус</b></div></th>
                                <th class="table-text"><div></div>Составлена</th>
                                <th class="table-text"><div></div>Обновлена</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tasks as $task)
                                <tr @if($task->state_id == 1) class="active"
                                    @elseif($task->state_id == 2) class="info"
                                    @elseif($task->state_id == 3 ) class="success"
                                    @elseif($task->state_id == 4 ) class="danger"
                                    @endif>

                                    <td class="table-text"><div>{{ $task->id }}</div></td>
                                    <td class="table-text"><div><a href="/tasks/{{$task->id}}/show" >{{ $task->name }}</a></div></td>
                                    <td class="table-text"><div><a target="_blank" href="https://{{ env('ABILLS_URL', false) }}/admin/index.cgi?index=7&search=1&type=10&LOGIN={{$task->login}}">{{$task->login}}</a></div></td>
                                    <td class="table-text"><div>{{ $task->address }}</div></td>
                                    <td class="table-text"><div>{{ substr( $task->type->name, 0, 12 )}}</div></td>
                                    <td class="table-text"><div>{{ $task->user->name }}</div></td>
                                    <td class="table-text"><div>@if($task->viewed == TRUE)да@else <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> нет@endif</div></td>
                                    <td class="table-text"><div>{{ $task->priority->name }}</div></td>
                                    <td class="table-text"><div>{{ $task->state->name }}</div></td>
                                    <td class="table-text"><div>{{ $task->created_at }}</div></td>
                                    <td class="table-text"><div>{{ $task->updated_at }}</div></td>
                                </tr>
                            @endforeach

                        </tbody>
                        {{ $tasks->links() }}
                    </table>
                    {{ $tasks->links() }}
                </div>

            </div>
        </div>
    </div>
@endsection
