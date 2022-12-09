<div class="container">
    <div class="">
        <!-- SMS -->
        <div class="panel panel-default">
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
                        @if($smses->count())
                            @foreach ($smses as $sms)
                                <tr @if($sms->status) class="" @else class="danger" @endif>
                                    <td class="table-text"><div>{{ $sms->id }}</div></td>
                                    <td class="table-text"><div>{{ $sms->text }}</div></td>
                                    <td class="table-text"><div>@if($sms->sender){{ $sms->sender->fio }}@endif</div></td>
                                    <td class="table-text"><div>@if($sms->recipient){{ $sms->recipient->fio }}@endif</div></td>
                                    <td class="table-text"><div>{{ $sms->phone }}</td>
                                    <td class="table-text"><div>@if($sms->task)<a href="/tasks/{{$sms->task->id}}/show" >{{ $sms->task->name }}</a>@endif</div></td>
                                    <td class="table-text"><div><div>@if($sms->status)<span class="glyphicon glyphicon-ok-sign"></span> Да @else <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> Нет@endif</div></div></td>
                                    <td class="table-text"><div><div>@if($sms->error_code) {{ $sms->getErrorText() }}@endif</div></td>
                                    <td class="table-text"><div>{{ $sms->created_at }}</div></td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                    {{ $smses->links() }}
                </table>
                {{ $smses->links() }}
            </div>

        </div>
    </div>
</div>
