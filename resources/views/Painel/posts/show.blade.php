@extends('painel.layouts.painel')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $task->name }}</div>

                <div class="panel-body">
                    <strong>Descripção:</strong>
                    <p>{{ $task->description }}</p>
                </div>

                <div class="panel-footer clearfix">
                    <p class="pull-right"><a href="{{ url("task/$task->id/edit") }}" title="">Editar</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
