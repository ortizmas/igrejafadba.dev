@extends('painel.layouts.painel')

@section('content')
<div class="container-fluid">
    <section class="content-header">
        <div class="sub-navbar">
            <div id="toolbar_top" class="subnavs bottomonly-shadow pb25">
                <div class="pull-right">
                    <div class="btn-group">
                        <a class="btn btn-default btn-lg" href="#" title="Export in csv format" target="_new" data-toggle="tooltip" data-placement="bottom" rel="tooltip">
                            <i class="fa fa-file-excel-o  color-main lg"></i>
                        </a>
                        <a class="btn btn-default btn-lg" href="{{ route('perfil.create') }}" title="Criar novo {{ $pageConfig }}" data-toggle="tooltip" data-placement="bottom" rel="tooltip">
                            <i class="fa fa-plus  color-main lg"></i>
                        </a>
                    </div>
                </div>
                {{-- <div id="list-action-bar" class="pull-left" style="display:none">
                    <div class="btn-group">
                        <button id="toolbar_deleteButtonHandler" class="btn btn-default btn-danger btn-lg" data-role="deleteAll" rel="tooltip" data-placement="bottom" data-toggle="tooltip" title="Delete the selected items" data-original-title="Delete the selected items">
                            <i class="fa fa-trash  "></i>
                        </button>
                        <button id="toolbar_editButtonHandler" class="btn btn-default btn-lg " data-toggle="tooltip" data-placement="bottom" title="Edit the selected item">
                            <i class="fa fa-edit"></i>
                        </button>
                    </div>
                </div> --}}
            </div>
        </div>
    </section>
</div>
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
            <div class="box">
                {{-- <div class="box-header">
                  <h3 class="box-title">Data Table With Full Features</h3>
                </div> --}}
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
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">Perfil</th>
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">Plantilla</th>
                                            <th colspan="2" class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($perfiles as $key => $perfil)
                                            <tr role="row" class="odd">
                                                <td class="sorting_1">{{ $perfil->id }}</td>
                                                <td>{{ $perfil->rol }}</td>
                                                <td>{{ $perfil->plantilla }}</td>
                                                <td>
                                                    <form>
                                                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                                        <div class="togglebutton"  data-list-boolean ="{!! 'perfil_'.$pageConfig .'_'.$perfil->id !!}" data-list-name ="activo" >
                                                            <i class=" transitioned fa fa-2x fa-check text-success pointer {{ ($perfil->activo==1) ? '' : 'hidden' }}"></i>
                                                            <i class="transitioned fa fa-2x fa-close text-error pointer {{ ($perfil->activo==0) ? '' : 'hidden' }}"></i>
                                                        </div>
                                                    </form>
                                                </td>
                                                <td><a class="btn btn-info btn-xs" href="{{ route('perfil.edit', $perfil->id) }}" title="Edit"><i class="fa fa-edit" ></i></a></td>
                                                <td>
                                                    <form>
                                                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                                        <a href="{{  MyFunction::ma_get_admin_delete_url($perfil) }}" class="btn btn-danger btn-xs" data-role="delete-item">
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
                                <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">{{ $perfiles_total }} <b>Registros</b></div>
                            </div>
                            <div class="col-sm-7">
                                <div class="dataTables_paginate paging_simple_numbers" id="example1_paginate">
                                    <ul class="pagination">
                                        {{ $perfiles->links() }}
                                    </ul>
                                </div>
                            </div>
                            {{-- <div class="pull-right">
                                <span id="perfiles-total" >{{ $perfiles_total }} </span> <b>Registros</b>
                            </div> --}}
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
