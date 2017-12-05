@extends('layouts.admin')

@section('content')
<div class="container">
    <section class="content-header">
      <h1>
        Buttons
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">UI</a></li>
        <li class="active">Buttons</li>
      </ol>
    </section>
    
    @if (session('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            {{ session('success') }}
        </div>
    @endif
    <div class="row">
        {{-- <a class="button-animado" href="#">Click me!</a>
        <button type="submit" class="button-animado">Click me!</button> --}}
        
        {{-- <div id="alert" class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
        </div> --}}
        @if (session('status'))
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
                {{ session('status') }}
            </div>
        @endif
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nome tarefa</th>
                    <th>Descripção</th>
                    <th>Estado</th>
                    <th colspan="2">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tasks as $task)
                    @can('update-task', $task)                    
                    <tr>
                        <td>{{ $task->id }}</td>
                        <td>{{ $task->name }}</td>
                        <td>{{ $task->description }}</td>
                        {{-- <td>{{ $task->ativo }}</td> --}}
                        <td>
                            <form>
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                            <div class="togglebutton"  data-list-boolean ="{!! $pageConfig .'_'.$task->id !!}" data-list-name ="ativo" >
                                <i class=" transitioned fa fa-2x fa-check text-success pointer {{ ($task->ativo==1) ? '' : 'hidden' }}"></i>
                                <i class="transitioned fa fa-2x fa-close text-error pointer {{ ($task->ativo==0) ? '' : 'hidden' }}"></i>
                            </div>
                            </form>
                        </td>
                        {{-- <td>{!! action_row($selectedNavigation->url, $item->id, $item->title, [['image' => '/admin/photos/articles/'.$item->id], 'show', 'edit', 'delete']) !!}</td> --}}
                        <td><a class="btn btn-info btn-xs" href="{{ route('task.edit', $task->id) }}" title="Edit"><i class="fa fa-edit" ></i></a></td>
                        <td>
                            
                            {{-- <a href="{{ route('task.destroy', $task->id) }}" title="Destroy"><i class="fa fa-trash-o" ></i></a> --}}
                            {{-- {!! Form::open(['route' =>  ['task.destroy', $task->id], 'method' => 'DELETE']) !!}
                                    <a href="#" class="btn-delete"><i class="fa fa-trash-o" ></i></a>
                            {!! Form::close() !!} --}}
                            <form>
                                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                {{-- <a href="{{ url("delete/task/$task->id") }}" class="btn btn-danger btn-xs" data-role="delete-item">
                                    <i class="fa fa-trash"></i> 
                                </a> --}}
                                <a href="{{  ma_get_admin_delete_url($task) }}" class="btn btn-danger btn-xs" data-role="delete-item">
                                    <i class="fa fa-trash"></i> 
                                </a>
                            </form>
                        </td>
                    </tr>
                    @endcan
                @endforeach
            </tbody>
        </table>
        <div class="pull-right">
            <span id="tasks-total" >{{ $tasks_total }} </span> <b>Registros</b>
        </div>
        {{ $tasks->links() }}
    </div>
</div>
<section class="content">
      <div class="row">
        <div class="col-xs-12">

        <div class="box">
            <div class="box-header">
              <h3 class="box-title">Data Table With Full Features</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="dataTables_length" id="example1_length">
                            <label>Show 
                                <select name="example1_length" aria-controls="example1" class="form-control input-sm">
                                    <option value="10">10</option><option value="25">25</option>
                                    <option value="50">50</option><option value="100">100</option>
                                </select> 
                                entries
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div id="example1_filter" class="dataTables_filter">
                            <label>Search:
                                <input type="search" class="form-control input-sm" placeholder="" aria-controls="example1">
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                            <thead>
                                <tr role="row">
                                    <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 297px;">Id</th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 361px;">Titulo</th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 322px;">Descripção</th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" style="width: 257px;">Estado</th>
                                    <th colspan="2" class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending" style="width: 190px;">Ação</th>
                                </tr>
                            </thead>
                <tbody>
                    @foreach ($tasks as $task)
                        <tr role="row" class="odd">
                            <td class="sorting_1">{{ $task->id }}</td>
                            <td>{{ $task->name }}</td>
                            <td>{{ $task->description }}</td>
                            {{-- <td>{{ $task->ativo }}</td> --}}
                            <td>
                                <form>
                                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                <div class="togglebutton"  data-list-boolean ="{!! $pageConfig .'_'.$task->id !!}" data-list-name ="ativo" >
                                    <i class=" transitioned fa fa-2x fa-check text-success pointer {{ ($task->ativo==1) ? '' : 'hidden' }}"></i>
                                    <i class="transitioned fa fa-2x fa-close text-error pointer {{ ($task->ativo==0) ? '' : 'hidden' }}"></i>
                                </div>
                                </form>
                            </td>
                            {{-- <td>{!! action_row($selectedNavigation->url, $item->id, $item->title, [['image' => '/admin/photos/articles/'.$item->id], 'show', 'edit', 'delete']) !!}</td> --}}
                            <td><a class="btn btn-info btn-xs" href="{{ route('task.edit', $task->id) }}" title="Edit"><i class="fa fa-edit" ></i></a></td>
                            <td>
                                
                                {{-- <a href="{{ route('task.destroy', $task->id) }}" title="Destroy"><i class="fa fa-trash-o" ></i></a> --}}
                                {{-- {!! Form::open(['route' =>  ['task.destroy', $task->id], 'method' => 'DELETE']) !!}
                                        <a href="#" class="btn-delete"><i class="fa fa-trash-o" ></i></a>
                                {!! Form::close() !!} --}}
                                <form>
                                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                    {{-- <a href="{{ url("delete/task/$task->id") }}" class="btn btn-danger btn-xs" data-role="delete-item">
                                        <i class="fa fa-trash"></i> 
                                    </a> --}}
                                    <a href="{{  ma_get_admin_delete_url($task) }}" class="btn btn-danger btn-xs" data-role="delete-item">
                                        <i class="fa fa-trash"></i> 
                                    </a>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    {{-- <tr role="row" class="odd">
                      <td class="sorting_1">Gecko</td>
                      <td>Firefox 1.0</td>
                      <td>Win 98+ / OSX.2+</td>
                      <td>1.7</td>
                      <td>A</td>
                    </tr>
                    <tr role="row" class="even">
                      <td class="sorting_1">Gecko</td>
                      <td>Firefox 1.5</td>
                      <td>Win 98+ / OSX.2+</td>
                      <td>1.8</td>
                      <td>A</td>
                    </tr>
                    <tr role="row" class="odd">
                      <td class="sorting_1">Gecko</td>
                      <td>Firefox 2.0</td>
                      <td>Win 98+ / OSX.2+</td>
                      <td>1.8</td>
                      <td>A</td>
                    </tr> --}}
                </tbody>
                <tfoot>
                    <tr>
                        <th rowspan="1" colspan="1">Id</th>
                        <th rowspan="1" colspan="1">Titulo</th>
                        <th rowspan="1" colspan="1">Descrição)</th>
                        <th rowspan="1" colspan="1">Estado</th>
                        <th rowspan="1" colspan="2">Ação</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-5">
            <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">Showing 1 to 10 of 57 entries</div>
        </div>
        <div class="col-sm-7">
            <div class="dataTables_paginate paging_simple_numbers" id="example1_paginate">
                <ul class="pagination">
                    <li class="paginate_button previous disabled" id="example1_previous"><a href="#" aria-controls="example1" data-dt-idx="0" tabindex="0">Previous</a></li>
                    <li class="paginate_button active"><a href="#" aria-controls="example1" data-dt-idx="1" tabindex="0">1</a></li>
                    <li class="paginate_button "><a href="#" aria-controls="example1" data-dt-idx="2" tabindex="0">2</a></li>
                    <li class="paginate_button "><a href="#" aria-controls="example1" data-dt-idx="3" tabindex="0">3</a></li>
                    <li class="paginate_button "><a href="#" aria-controls="example1" data-dt-idx="4" tabindex="0">4</a></li>
                    <li class="paginate_button "><a href="#" aria-controls="example1" data-dt-idx="5" tabindex="0">5</a></li>
                    <li class="paginate_button "><a href="#" aria-controls="example1" data-dt-idx="6" tabindex="0">6</a></li>
                    <li class="paginate_button next" id="example1_next"><a href="#" aria-controls="example1" data-dt-idx="7" tabindex="0">Next</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
</div>
</section>
@endsection

@section('script')
    {{-- expr --}}
    <script src="{{ asset('js/script.js') }}"></script>
@endsection
