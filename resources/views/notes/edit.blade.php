@extends('layouts.app')

@section('content')
    <!-- New Note Form -->
    <!-- Display Validation Errors -->
    @include('common.errors')
    <form action="/notes/{{$note->id}}" method="POST" class="form-horizontal">
        {{ csrf_field() }}
        {{method_field('PATCH')}}
        <div class="form-group">
            <label for="task-note" class="col-sm-4 control-label">Редактировать</label>
            <div class="col-sm-6" ><textarea name="text" id="task-note" class="form-control"  value="{{ old('note') }}">{{$note->text}}</textarea></div>
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
@endsection