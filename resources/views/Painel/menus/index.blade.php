@extends('painel.layouts.painel')

@section('title')
    {{ config('app.name', 'Fadba')}}
@endsection

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
                            <a href="{{ route('recurso.create') }}" class="btn btn-primary pull-right" style="margin-top: -8px;">Criar novo Menu</a>
                        </h4>
                    </div>

                    <ul class="nav nav-tabs nav-justified">
                        <?php $counter = 1; ?>
                        <?php if(empty($front)) { ?>
                            <li class="<?php echo ($counter==1) ? 'active' : '';?>"><a href="<?php echo '#tab'.$counter; ?>" data-toggle="tab">Frontend</a></li>
                            <?php $counter++; ?>            
                        <?php } ?>
                        
                        <?php foreach($menus as $menu): ?>
                            <?php if($menu->visibilidad  != \App\Models\Menu::BACKEND) { ?>
                                <?php continue;?>
                            <?php } ?>
                            <li class="<?php echo ($counter==1) ? 'active' : '';?>"><a href="<?php echo '#tab'.$counter; ?>" data-toggle="tab"><?php echo ucfirst($menu->nome); ?></a></li>
                            <?php $counter++; ?>
                        <?php endforeach; ?>               
                    </ul>

                    <div class="tab-content">
                        <?php $counter = 1; ?>
                        <?php if(empty($front)) { ?>
                            <div class="tab-pane <?php echo ($counter==1) ? 'active' : '';?>" id="<?php echo 'tab'.$counter; ?>">            
                                
                                <table class="table table-bordered table-hover table-striped table-condensed table-responsive">
                                    <thead>                    
                                        <tr>
                                            <th>NUM</th>
                                            <th data-order="posicion">POSICION</th>
                                            <th data-order="padre">PADRE</th>
                                            <th data-order="menu">TITULO</th>
                                            <th data-order="recurso">RECURSO</th>
                                            <th data-order="url">URL</th>
                                            <th class="col-collapse">ICONO</th>
                                            <th data-order="visibilidad">VISIBILIDAD</th>
                                            <th data-order="activo">ESTADO</th>
                                            <th class="btn-actions col-blocked text-center">ACCIONES</th>
                                        </tr>                    
                                    </thead>
                                    <tbody>
                                
                                    <?php $front = $menu->getListadoMenuPadres($menu::FRONTEND); /*App\Models\Menu::FRONTEND */ ?> 
                                    <?php foreach($front as $menu): ?>
                                        <?php if($menu->visibilidad != $menu::FRONTEND) { ?>
                                            <?php continue;?>
                                        <?php } ?>
                                        <?php $key_upd = MyFunction::setKey($menu->id, 'upd_menu'); ?>
                                        <?php $key_ina = MyFunction::setKey($menu->id, 'inactivar_menu'); ?>
                                        <?php $key_rea = MyFunction::setKey($menu->id, 'reactivar_menu'); ?>
                                        <?php $key_del = MyFunction::setKey($menu->id, 'eliminar_menu'); ?>                            
                                            <tr>
                                                <td></td>                                
                                                <td><?php echo $menu->posicion; ?></td>
                                                <td></td>
                                                <td><?php echo $menu->menu; ?></td>
                                                <td><?php echo $menu->recurso; ?></td>
                                                <td><?php echo $menu->url; ?></td>
                                                <td><?php echo $menu->icono; ?></td>
                                                <td><?php echo ($menu->visibilidad == $menu::BACKEND) ? '<span class="label label-success">Backend</span>' : '<span class="label label-warning">Frontend</span>'; ; ?></td>
                                                <td><?php echo ($menu->activo == $menu::ACTIVO) ? '<span class="label label-success">Activo</span>' : '<span class="label label-danger">Bloqueado</span>'; ; ?></td>
                                                <td>
                                                    <?php echo MyFunction::buttonTable('Modificar menú', "sistema/menus/editar/$key_upd/", null, 'warning', 'fa-edit'); ?>
                                                    <?php if($menu->activo == $menu::ACTIVO) { ?>
                                                        <?php echo MyFunction::buttonTable('Bloquear menú', "sistema/menus/estado/inactivar/$key_ina/", null, 'success', 'fa-flag'); ?>
                                                    <?php } else { ?>
                                                        <?php echo MyFunction::buttonTable('Reactivar menú', "sistema/menus/estado/reactivar/$key_rea/", null, 'danger', 'fa-flag'); ?>
                                                    <?php } ?>
                                                    <?php echo MyFunction::buttonTable('Eliminar menú', "sistema/menus/eliminar/$key_del/", array('class'=>'js-confirm', 'confirm-title'=>'Eliminar menú', 'confirm-body'=>'Está seguro de eliminar este menú? <br />Recuerda que esta operación no se puede reversar.'), 'danger', 'fa-ban'); ?>
                                                </td>
                                            </tr>
                                            <?php $hijos = $menu->getMenusPorPadre($menu->id, $order); ?>
                                            {!! dd($hijos) !!}
                                            <?php if($hijos) { ?>
                                                <?php $counter2 = 1; ?>
                                                <?php foreach($hijos as $hijo): ?>
                                                    <?php $key_upd = MyFunction::setKey($hijo->id, 'upd_menu'); ?>
                                                    <?php $key_ina = MyFunction::setKey($hijo->id, 'inactivar_menu'); ?>
                                                    <?php $key_rea = MyFunction::setKey($hijo->id, 'reactivar_menu'); ?>
                                                    <?php $key_del = MyFunction::setKey($hijo->id, 'eliminar_menu'); ?>
                                                    <tr>
                                                        <td><?php echo $counter.'-'.$counter2; ?></td>                                
                                                        <td><?php echo $hijo->posicion; ?></td>
                                                        <td><?php echo $hijo->padre; ?></td>
                                                        <td><?php echo $hijo->menu; ?></td>
                                                        <td><?php echo $hijo->recurso; ?></td>
                                                        <td><?php echo $hijo->url; ?></td>
                                                        <td><?php echo $hijo->icono; ?></td>
                                                        <td><?php echo ($hijo->visibilidad == $menu::BACKEND) ? '<span class="label label-success">Backend</span>' : '<span class="label label-warning">Frontend</span>'; ; ?></td>
                                                        <td><?php echo ($hijo->activo == $menu::ACTIVO) ? '<span class="label label-success">Activo</span>' : '<span class="label label-danger">Bloqueado</span>'; ; ?></td>
                                                        <td>
                                                            <?php echo MyFunction::buttonTable('Modificar menú', "sistema/menus/editar/$key_upd/", null, 'warning', 'fa-edit'); ?>
                                                            <?php if($hijo->activo == $menu::ACTIVO) { ?>
                                                                <?php echo MyFunction::buttonTable('Bloquear menú', "sistema/menus/estado/inactivar/$key_ina/", null, 'success', 'fa-flag'); ?>
                                                            <?php } else { ?>
                                                                <?php echo MyFunction::buttonTable('Reactivar menú', "sistema/menus/estado/reactivar/$key_rea/", null, 'danger', 'fa-flag'); ?>
                                                            <?php } ?>
                                                            <?php echo MyFunction::buttonTable('Eliminar menú', "sistema/menus/eliminar/$key_del/", array('class'=>'js-confirm', 'confirm-title'=>'Eliminar menú', 'confirm-body'=>'Está seguro de eliminar este menú? <br />Recuerda que esta operación no se puede reversar.'), 'danger', 'fa-ban'); ?>
                                                        </td>
                                                    </tr>     
                                                    <?php $counter2++; ?>
                                                <?php endforeach; ?>
                                            <?php } ?>
                                        <?php $counter++; ?>
                                        <?php endforeach; ?>
                                    </tbody>                                       
                                </table>            
                            </div>
                        <?php } ?>  

                       <!-- Inicio de Backend -->
                        <?php foreach($categories as $menu): ?>  
                            <?php if($menu->visibilidad != $menu::BACKEND) { ?>
                                <?php continue;?>
                            <?php } ?>
                            <?php $key_upd = MyFunction::setKey($menu->id, 'upd_menu'); ?>
                            <?php $key_ina = MyFunction::setKey($menu->id, 'inactivar_menu'); ?>
                            <?php $key_rea = MyFunction::setKey($menu->id, 'reactivar_menu'); ?>
                            <?php $key_del = MyFunction::setKey($menu->id, 'eliminar_menu'); ?>
                        
                            <div class="tab-pane <?php echo ($counter==1) ? 'active' : '';?>" id="<?php echo 'tab'.$counter; ?>">
                                <table class="table table-bordered table-hover table-striped table-condensed table-responsive">
                                    <thead>                    
                                        <tr>
                                            <th>NUM</th>
                                            <th data-order="posicion">POSICION</th>
                                            <th data-order="padre">PADRE</th>
                                            <th data-order="menu">TITULO</th>
                                            <th data-order="recurso">RECURSO</th>
                                            <th data-order="url">URL</th>
                                            <th class="col-collapse">ICONO</th>
                                            <th data-order="visibilidad">VISIBILIDAD</th>
                                            <th data-order="activo">ESTADO</th>
                                            <th class="btn-actions col-blocked text-center">ACCIONES</th>
                                        </tr>                    
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td></td>                                
                                            <td><?php echo $menu->posicion; ?></td>
                                            <td></td>
                                            <td><?php echo $menu->nome; ?></td>
                                            <td><?php /*echo $menu->recurso; */?></td>
                                            <td><?php echo $menu->url; ?></td>
                                            <td><?php echo $menu->icono; ?></td>
                                            <td><?php echo ($menu->visibilidad == $menu::BACKEND) ? '<span class="label label-success">Backend</span>' : '<span class="label label-warning">Frontend</span>'; ; ?></td>
                                            <td><?php echo ($menu->activo == $menu::ACTIVO) ? '<span class="label label-success">Activo</span>' : '<span class="label label-danger">Bloqueado</span>'; ; ?></td>
                                            <td>
                                                <?php echo MyFunction::buttonTable('Modificar menú', "menus/$key_upd/editar", null, 'warning', 'fa-edit'); ?>
                                                <?php if($menu->activo == $menu::ACTIVO) { ?>
                                                    <?php echo MyFunction::buttonTable('Bloquear menú', "menus/estado/inactivar/$key_ina/", null, 'success', 'fa-flag'); ?>
                                                <?php } else { ?>
                                                    <?php echo MyFunction::buttonTable('Reactivar menú', "menus/estado/reactivar/$key_rea/", null, 'danger', 'fa-flag'); ?>
                                                <?php } ?>
                                                <?php echo MyFunction::buttonTable('Eliminar menú', "menus/$key_del/remover", array('class'=>'js-confirm', 'confirm-title'=>'Eliminar menú', 'confirm-body'=>'Está seguro de eliminar este menú? <br />Recuerda que esta operación no se puede reversar.'), 'danger', 'fa-ban'); ?>
                                            </td>
                                        </tr>
                                            <?php $counter2 = 1 ?>
                                            <?php foreach($menu->children as $row): ?>
                                                <?php $key_upd = MyFunction::setKey($row->id, 'upd_menu'); ?>
                                                <?php $key_ina = MyFunction::setKey($row->id, 'inactivar_menu'); ?>
                                                <?php $key_rea = MyFunction::setKey($row->id, 'reactivar_menu'); ?>
                                                <?php $key_del = MyFunction::setKey($row->id, 'eliminar_menu'); ?>

                                                <tr>
                                                    <td><?php echo $counter2; ?></td>                                
                                                    <td><?php echo $row->posicion; ?></td>
                                                    <td><?php /*echo $row->padre;*/ ?></td>
                                                    <td><?php echo $row->nome; ?></td>
                                                    <td><?php echo $row->recurso; ?></td>
                                                    <td><?php echo $row->url; ?></td>
                                                    <td><?php echo $row->icono; ?></td>
                                                    <td><?php echo ($row->visibilidad == $menu::BACKEND) ? '<span class="label label-success">Backend</span>' : '<span class="label label-warning">Frontend</span>'; ; ?></td>
                                                    <td><?php echo ($row->activo == $menu::ACTIVO) ? '<span class="label label-success">Activo</span>' : '<span class="label label-danger">Bloqueado</span>'; ; ?></td>
                                                    <td>
                                                        <?php echo MyFunction::buttonTable('Modificar menú', "menus/$key_upd/editar", null, 'warning', 'fa-edit'); ?>
                                                        <?php if($row->activo == $menu::ACTIVO) { ?>
                                                            <?php echo MyFunction::buttonTable('Bloquear menú', "menus/estado/inactivar/$key_ina/", null, 'success', 'fa-flag'); ?>
                                                        <?php } else { ?>
                                                            <?php echo MyFunction::buttonTable('Reactivar menú', "menus/estado/reactivar/$key_rea/", null, 'danger', 'fa-flag'); ?>
                                                        <?php } ?>
                                                        <?php echo MyFunction::buttonTable('Eliminar menú', "menus/eliminar/$key_del/", array('class'=>'js-confirm', 'confirm-title'=>'Eliminar menú', 'confirm-body'=>'Está seguro de eliminar este menú? <br />Recuerda que esta operación no se puede reversar.'), 'danger', 'fa-ban'); ?>
                                                    </td>
                                                </tr>
                                                <?php $counter3 = 1 ?>
                                                <?php foreach($row->children as $hijo): ?>
                                                    <?php $key_upd = MyFunction::setKey($hijo->id, 'upd_menu'); ?>
                                                    <?php $key_ina = MyFunction::setKey($hijo->id, 'inactivar_menu'); ?>
                                                    <?php $key_rea = MyFunction::setKey($hijo->id, 'reactivar_menu'); ?>
                                                    <?php $key_del = MyFunction::setKey($hijo->id, 'eliminar_menu'); ?>
                                                    <tr>
                                                        <td><?php echo $counter2.'-'.$counter3; ?></td>                                
                                                        <td><?php echo $hijo->posicion; ?></td>
                                                        <td><?php echo $row->nome; ?></td>
                                                        <td><?php echo $hijo->nome; ?></td>
                                                        <td><?php echo $hijo->recurso->recurso; ?></td>
                                                        <td><?php echo $hijo->url; ?></td>
                                                        <td><?php echo $hijo->icono; ?></td>
                                                        <td><?php echo ($hijo->visibilidad == $menu::BACKEND) ? '<span class="label label-success">Backend</span>' : '<span class="label label-warning">Frontend</span>'; ; ?></td>
                                                        <td><?php echo ($hijo->activo == $menu::ACTIVO) ? '<span class="label label-success">Activo</span>' : '<span class="label label-danger">Bloqueado</span>'; ; ?></td>
                                                        <td>
                                                            <?php echo MyFunction::buttonTable('Modificar menú', "menus/$key_upd/editar", null, 'warning', 'fa-edit'); ?>
                                                            <?php if($hijo->activo == $menu::ACTIVO) { ?>
                                                                <?php echo MyFunction::buttonTable('Bloquear menú', "menus/estado/inactivar/$key_ina/", null, 'success', 'fa-flag'); ?>
                                                            <?php } else { ?>
                                                                <?php echo MyFunction::buttonTable('Reactivar menú', "menus/estado/reactivar/$key_rea/", null, 'danger', 'fa-flag'); ?>
                                                            <?php } ?>
                                                            <?php echo MyFunction::buttonTable('Eliminar menú', "menus/$key_del/remover", array('class'=>'js-confirm', 'confirm-title'=>'Eliminar menú', 'confirm-body'=>'Está seguro de eliminar este menú? <br />Recuerda que esta operación no se puede reversar.'), 'danger', 'fa-ban'); ?>
                                                        </td>
                                                    </tr>    
                                                    <?php $counter3++ ?> 
                                                <?php endforeach; ?>
                                                <?php $counter2++ ?>
                                            <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php $counter++; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- /.Modal para confirmação ao excluir row-->
        {!! Btn::delete() !!}

    </section>
@endsection

@section('script')

    <script src="{{ asset('js/script.js') }}"></script>

@endsection
