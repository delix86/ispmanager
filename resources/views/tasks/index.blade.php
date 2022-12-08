@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Задачи</h1>
        <div class="">
            <!-- Current Tasks -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row" style="margin-top: 7px;">
                        <div class='col-xs-12' style="text-align: right">
                            <a title="Добавить" class='btn btn-primary' href="/tasks/add">Создать</span></a>
                        </div>
                    </div>
                    <form method="GET">
                        <div class="row" style="margin-top: 15px;">
                            <div class="col-xs-12">
                                <div class='btn-group' role='group' id="states-buttons">
                                    <a class='btn btn-default reset-button @if(count($selectedStates) == 8) btn-primary @endif' href="#" data-state-id="0">Все</a>
                                    <a class='btn btn-default @if(in_array(1 , $selectedStates)) btn-primary @endif' href="#" data-state-id="1">Открыта</a>
                                    <a class='btn btn-default @if(in_array(3 , $selectedStates)) btn-primary @endif' href="#" data-state-id="3">В обработке</a>
                                    <a class='btn btn-default @if(in_array(2 , $selectedStates)) btn-primary @endif' href="#" data-state-id="2">Приостановление</a>
                                    <a class='btn btn-default @if(in_array(4 , $selectedStates)) btn-primary @endif' href="#" data-state-id="4">Выполнена</a>
                                    <a class='btn btn-default @if(in_array(5 , $selectedStates)) btn-primary @endif' href="#" data-state-id="5">Не выполнена</a>
                                    <a class='btn btn-default @if(in_array(6 , $selectedStates)) btn-primary @endif' href="#" data-state-id="6">Отменена</a>
                                    <a class='btn btn-default @if(in_array(7 , $selectedStates)) btn-primary @endif' href="#"  data-state-id="7">Выполнена и закрыта</a>
                                    <a class='btn btn-default @if(in_array(8 , $selectedStates)) btn-primary @endif' href="#"  data-state-id="8">Не выполнена и закрыта</a>
                                </div>
                                <div id="states-inputs">
                                    <input id="state-checkbox-1" type="checkbox" name="states[1]" style="display:none;" value="1" @if(in_array(1 , $selectedStates)) checked="checked" @endif/>
                                    <input id="state-checkbox-2" type="checkbox" name="states[2]" style="display:none;" value="2" @if(in_array(2 , $selectedStates)) checked="checked" @endif/>
                                    <input id="state-checkbox-3" type="checkbox" name="states[3]" style="display:none;" value="3" @if(in_array(3 , $selectedStates)) checked="checked" @endif/>
                                    <input id="state-checkbox-4" type="checkbox" name="states[4]" style="display:none;" value="4" @if(in_array(4 , $selectedStates)) checked="checked" @endif/>
                                    <input id="state-checkbox-5" type="checkbox" name="states[5]" style="display:none;" value="5" @if(in_array(5 , $selectedStates)) checked="checked" @endif/>
                                    <input id="state-checkbox-6" type="checkbox" name="states[6]" style="display:none;" value="6" @if(in_array(6 , $selectedStates)) checked="checked" @endif/>
                                    <input id="state-checkbox-7" type="checkbox" name="states[7]" style="display:none;" value="7" @if(in_array(7 , $selectedStates)) checked="checked" @endif/>
                                    <input id="state-checkbox-8" type="checkbox" name="states[8]" style="display:none;" value="8" @if(in_array(8 , $selectedStates)) checked="checked" @endif />
                                </div>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 15px;">
                            <div class='btn-group col-xs-12 col-md-3' style="margin-bottom: 15px;">
                                <input type="text" name="phone1" value="{{ $phone1 }}" placeholder="Телефон" class="form-control" />
                            </div>
                            <div class='btn-group col-xs-12 col-md-9' style="margin-bottom: 15px;">
                                <input type="text" name="search" value="{{ $search }}" placeholder="Поиск" class="form-control" />
                            </div>
                        </div>

                        <div class="row">
                            <div class='col-xs-12 col-md-3' style="margin-bottom: 15px;">
                                <select id="logins-selector" class="chosen-select" name="logins[]" multiple="multiple" data-placeholder="Логин">
                                    @foreach($logins as $login)
                                        <option value="{{ $login->login }}" @if(in_array($login->login, $selectedLogins)) selected="selected" @endif>{{ $login->login }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class='col-xs-12 col-md-4' style="margin-bottom: 15px;">
                                <select id="users-selector" class="chosen-select" name="users_ids[]" multiple="multiple" data-placeholder="Исполнитель">
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" @if(in_array($user->id, $users_ids)) selected="selected" @endif>{{ $user->fio }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xs-12 col-md-2" style="margin-bottom: 15px;">
                                <input id="filter-date" class="form-control" name="date" autocomplete="off" type="text" value="{{ $date }}" data-date-format="yyyy-mm-dd"  style="max-width: 140px;" placeholder="Дата"/>
                            </div>
                            <div class="col-xs-12 col-md-2">
                                <input type="submit" value="Применить" class="btn btn-primary">
                            </div>
                        </div>
                    </form>
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
                                <th class="table-text"><div><b>Автор</b></div></th>
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
                                    <td class="table-text"><div><a target="_blank" href="{{ env('ABILLS_URL', false) }}/#clientcard/{{$task->uid}}">{{$task->login}}</a></div></td>
                                    <td class="table-text"><div>{{ $task->address }}</div></td>
                                    <td class="table-text"><div>{{ substr( $task->type->name, 0, 12 )}}</div></td>
                                    <td class="table-text"><div>{{ $task->author->fio }}</div></td>
                                    <td class="table-text"><div>{{ $task->user ? $task->user->fio : '' }}</div></td>
                                    <td class="table-text"><div>@if($task->viewed == TRUE)да@else <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> нет@endif</div></td>
                                    <td class="table-text"><div>{{ $task->priority->name }}</div></td>
                                    <td class="table-text"><div>{{ $task->state->name }}</div></td>
                                    <td class="table-text"><div>{{ $task->created_at }}</div></td>
                                    <td class="table-text"><div>{{ $task->updated_at }}</div></td>
                                </tr>
                            @endforeach

                        </tbody>
                        {{ $tasks->appends(request()->all())->links() }}
                    </table>
                    {{ $tasks->appends(request()->all())->links() }}
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
      $(document).ready(function() {
        $("#logins-selector").chosen({width: '255px', allow_single_deselect: true});
        $("#users-selector").chosen({width: '354px', allow_single_deselect: true});

        $("#filter-date").datepicker();

        $('#states-buttons a').click(function() {
          let id = $(this).data('state-id');
          if (id === 0) {
            if ($(this).hasClass('btn-primary')) {
              $('#states-buttons a').removeClass('btn-primary');
              $('#states-inputs input').prop('checked', false);
            } else {
              $('#states-buttons a').addClass('btn-primary');
              $('#states-inputs input').prop('checked', true);
            }
          } else {
            console.log($('#state-checkbox-' + id).is(":checked"));
            if ($('#state-checkbox-' + id).is(":checked")) {
              $('#state-checkbox-' + id).prop('checked', false);
              $(this).removeClass('btn-primary');
              $('a[data-state-id=0]').removeClass('btn-primary');
            } else {
              $('#state-checkbox-' + id).prop('checked', true);
              $('a[data-state-id=0]').removeClass('btn-primary');
              $(this).addClass('btn-primary');
            }
          }
          return false;
        });
      });
    </script>
@endpush
