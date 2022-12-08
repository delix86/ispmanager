@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>История задачи</h1>
        <div class="">
            <!-- History List -->
            <div class="panel panel-default">

                <div class="panel-body">
                    <thead>
                        <tr>
                            <th>
                                Поле
                            </th>
                            <th>
                                Исходное значение
                            </th>
                            <th>
                                Новое значение
                            </th>
                        </tr>

                    </thead>

                    @foreach($task->logs as $historyItem)
                        @if($historyItem->text)
                            <div>
                                @php
                                    $data = json_decode($historyItem->text);
                                    if(!is_object($data)) {
                                        continue;
                                    }
                                @endphp
                                @foreach($data->changes as $key => $value)
                                    <div>{{ \App\Task::FIELDS[$key] }}</div>
                                    <div>@if($task->getRelationValue(\App\Task::RELATIONS[$key])->fio) @endif {{ $data->original->$key }}</div>
                                    <div>@if($task->getRelationValue(\App\Task::RELATIONS[$key])->fio) @endif {{ $data->original->$key }}</div>
                                @endforeach
                            </div>
                        @endif
                    @endforeach
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
        });
      });
    </script>
@endpush
