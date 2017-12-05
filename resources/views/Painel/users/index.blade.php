@extends('painel.layouts.painel')

@section('content')
<section class="content">
    @if (session('success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            {{ session('success') }}
        </div>
    @endif
    @if (session('status'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
            {{ session('status') }}
        </div>
    @endif
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Contact list
                        <a href="{{ route('users.create') }}" class="btn btn-primary pull-right" style="margin-top: -8px;">Criar novo {{ $model }}</a>
                    </h4>
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
                                            <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Id</th>
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">Name</th>
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">Email</th>
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">Perfil</th>
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">Estado</th>
                                            <th colspan="2" class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $key => $user)
                                            <tr role="row" class="odd">
                                                <td class="sorting_1">{{ $user->id }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->perfil->rol }}</td>
                                                <td>
                                                    <form>
                                                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                                        <div class="togglebutton"  data-list-boolean ="{!! 'users_'.$model .'_'.$user->id !!}" data-list-name ="activo" >
                                                            <i class=" transitioned fa fa-2x fa-check text-success pointer {{ ($user->activo==1) ? '' : 'hidden' }}"></i>
                                                            <i class="transitioned fa fa-2x fa-close text-error pointer {{ ($user->activo==0) ? '' : 'hidden' }}"></i>
                                                        </div>
                                                    </form>
                                                </td>
                                                <td><a class="btn btn-info btn-xs" href="{{ route('users.edit', $user->id) }}" title="Edit"><i class="fa fa-edit" ></i></a></td>
                                                <td>
                                                    <form>
                                                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                                        <a href="{{  MyFunction::ma_get_admin_delete_url($user) }}" class="btn btn-danger btn-xs" data-role="delete-item">
                                                            <i class="fa fa-trash"></i> 
                                                        </a>
                                                    </form>
                                                </td>
                                                
                                                
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th rowspan="1" colspan="1">Id</th>
                                            <th rowspan="1" colspan="1">Nome</th>
                                            <th rowspan="1" colspan="1">Email)</th>
                                            <th rowspan="1" colspan="1">Perfil</th>
                                            <th rowspan="1" colspan="1">Estado</th>
                                            <th rowspan="1" colspan="2">Ação</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-5">
                                <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">{{ $count }} <b>Registros</b></div>
                            </div>
                            <div class="col-sm-7">
                                <div class="dataTables_paginate paging_simple_numbers" id="example1_paginate">
                                    <ul class="pagination">
                                        {{ $users->links() }}
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
          </div>
        </div>
      </div>
</div>
</section>
@endsection

@section('script')
    {{-- expr --}}
    <script src="{{ asset('js/script.js') }}"></script>
@endsection
