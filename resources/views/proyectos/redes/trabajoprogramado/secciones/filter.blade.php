@include('proyectos.redes.trabajoprogramado.modal.modalExporteProgramacionRecurso')
@include('proyectos.redes.trabajoprogramado.modal.modalImportarMasivoSupervisores')

<button data-toggle="collapse" data-target="#filter" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar">
    <i class="fa fa-filter"></i> &nbsp;&nbsp;Filtrar
</button>

{!!Form::open(['url' => array('proyecto'), "style" => 'display:inline-block' ])!!}
    <input type="hidden" name="pro" value="0" />
    <button class="btn btn-primary  btn-cam-trans btn-sm" type="submit"  name="consultar" {{( session('user_login') == 'U04172' ? ' disabled ':'')}}value="consultar">
        <i class="fa fa-plus"></i> &nbsp;&nbsp;Crear Proyecto
    </button>
{!!Form::close()!!}

@if(session('user_login') != 'U04172')
<div class="dropdown" style="    width: 104px;    display: inline-block;">
  <button class="btn btn-primary btn-cam-trans btn-sm dropdown-toggle" type="button" data-toggle="dropdown" > <i class="fa fa-random"></i>  Gestionar
  <span class="caret"></span></button>
  <ul class="dropdown-menu" style="padding-bottom:0px;">
    @if($opc == "1" )
        <li><a style="width:100%;color:#0060AC;" href="../../redes/ordenes/orden"   class="btn btn-primary btn-cam-trans btn-sm" ><i class="fa fa-search"></i> &nbsp;&nbsp; Gestionar ManiObras</a></li>
        <li><a style="width:100%;color:#0060AC;" href="../../redes/ordenes/documentos"   class="btn btn-primary btn-cam-trans btn-sm"><i class="fa fa-search"></i> &nbsp;&nbsp; Gestionar Solicitudes de Materiales</a>    </li>
        <li><a style="width:100%;color:#0060AC;" href="{{url('/')}}/transversal/consultar/goms"   class="btn btn-primary btn-cam-trans btn-sm"><i class="fa fa-search"></i> &nbsp;&nbsp; Gestionar ordenes GOM</a>    </li>
    @endif

    @if($opc == "2" )
        <li><a style="width:100%;color:#0060AC;" href="../../redes/ordenes/home"   class="btn btn-primary btn-cam-trans btn-sm"><i class="fa fa-search"></i> &nbsp;&nbsp; Gestionar Proyectos</a></li>
        <li><a style="width:100%;color:#0060AC;" href="../../redes/ordenes/documentos"   class="btn btn-primary btn-cam-trans btn-sm"><i class="fa fa-search"></i> &nbsp;&nbsp; Gestionar Solicitudes de Materiales</a>    </li>
        <li><a style="width:100%;color:#0060AC;" href="{{url('/')}}/transversal/consultar/goms"   class="btn btn-primary btn-cam-trans btn-sm"><i class="fa fa-search"></i> &nbsp;&nbsp; Gestionar ordenes GOM</a>    </li>
    @endif

    @if($opc == "3" )
        <li><a style="width:100%;color:#0060AC;" href="../../redes/ordenes/home"   class="btn btn-primary btn-cam-trans btn-sm"><i class="fa fa-search"></i> &nbsp;&nbsp; Gestionar Proyectos</a></li>
        <li><a style="width:100%;color:#0060AC;" href="../../redes/ordenes/orden"   class="btn btn-primary btn-cam-trans btn-sm"><i class="fa fa-search"></i> &nbsp;&nbsp; Gestionar ManiObras</a></li>
        <li><a style="width:100%;color:#0060AC;" href="{{url('/')}}/transversal/consultar/goms"   class="btn btn-primary btn-cam-trans btn-sm"><i class="fa fa-search"></i> &nbsp;&nbsp; Gestionar ordenes GOM</a>    </li>
    @endif

  </ul>
</div>

    @if($opc == "1" )
    <div class="dropdown" style="    width: 104px;    display: inline-block;">
      <button class="btn btn-primary btn-cam-trans btn-sm dropdown-toggle" type="button" data-toggle="dropdown"> <i class="fa fa-pencil-square-o"></i> &nbsp;&nbsp; Captura
      <span class="caret"></span></button>
      <ul class="dropdown-menu" style="padding-bottom:0px;">


            <li> <button style="width:100%;" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" id="btn-eje">
                    <i class="fa fa-pencil-square-o"></i> &nbsp;&nbsp;Capturar ejecución
            </button></li>

            <li> <button style="width:100%;" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" id="btn-conci">
                    <i class="fa fa-pencil-square-o"></i> &nbsp;&nbsp;Capturar conciliación
            </button></li>
      </ul>
    </div>
    @endif
@endif
<div class="dropdown" style="    width: 124px;    display: inline-block;">
  <button class="btn btn-primary btn-cam-trans btn-sm dropdown-toggle" type="button" data-toggle="dropdown"> <i class="fa fa-calendar-check-o"></i>  Last Planner
  <span class="caret"></span></button>
  <ul class="dropdown-menu" style="padding-bottom:0px;">
    
    <li><a style="width:100%;color:#0060AC" target="_blank" href="../../ganntProyectos" id="btn-salir" class="btn btn-primary btn-cam-trans btn-sm" id="btn-salir"><i class="fa fa-file-o"></i> &nbsp;&nbsp; Gannt - Kanban Last Planner</a></li>
      @if(session('user_login') != 'U04172')
    <li><a style="width:100%;color:#0060AC" target="_blank" href="../../restricciones" id="btn-salir" class="btn btn-primary btn-cam-trans btn-sm" id="btn-salir"><i class="fa fa-file-o"></i> &nbsp;&nbsp; Restricciones Last Planner</a></li>
      @endif
    <li><a style="width:100%;color:#0060AC" target="_blank" href="{{config('app.Campro')[2]}}/campro/gop/rds/gantt/" id="btn-salir" class="btn btn-primary btn-cam-trans btn-sm" id="btn-salir"><i class="fa fa-file-o"></i> &nbsp;&nbsp; Gannt - Uso capacidad operativa</a></li>

  </ul>
</div>
@if(session('user_login') != 'U04172')
<div class="dropdown" style="    width: 104px;    display: inline-block;">
  <button class="btn btn-primary btn-cam-trans btn-sm dropdown-toggle" type="button" data-toggle="dropdown"> <i class="fa fa-file-o"></i> &nbsp;&nbsp; Ver
  <span class="caret"></span></button>
  <ul class="dropdown-menu" style="padding-bottom:0px;">

    <li>    
        <button style="width:100%;" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" id="btn-balance">
            <i class="fa fa-list-alt"></i> &nbsp;&nbsp;Balance de materiales
        </button>
    </li>
    <li>
        <a style="width:100%;color:#0060AC;" href="../../cam/programador/planner" target="_blank"  class="btn btn-primary btn-cam-trans btn-sm"><i class="fa fa-calendar"></i> &nbsp;&nbsp; Agendamiento</a>
    </li>

  </ul>
</div>
@endif

<div class="dropdown" style="    width: 170px;    display: inline-block;">
  <button class="btn btn-primary btn-cam-trans btn-sm dropdown-toggle" type="button" data-toggle="dropdown"> <i class="fa fa-sign-in"></i>  Importar y exportar
  <span class="caret"></span></button>
  <ul class="dropdown-menu" style="padding-bottom:0px;">
      @if(session('user_login') != 'U04172')
        <li><button  style="width:100%;" class="btn btn-primary  btn-cam-trans btn-sm" id="upload_descargo_ot" type="submit" name="consultar" >
            <i class="fa fa-upload"></i> &nbsp;&nbsp;Importar descargos
        </button>
        </li>
        <li>
            <button  style="width:100%;" class="btn btn-primary  btn-cam-trans btn-sm" id="upload_gom" type="submit" name="consultar" value="consultar">
                    <i class="fa fa-upload"></i> &nbsp;&nbsp;Importar GOMs
            </button>
        </li>

        <li>
            <button  style="width:100%;" class="btn btn-primary  btn-cam-trans btn-sm" onclick="abrirModalImportarPro()" type="submit" name="consultar" >
                    <i class="fa fa-upload"></i> &nbsp;&nbsp;Importar Masivo supervisores
            </button>
        </li>

        <li>
            <button  style="width:100%;" class="btn btn-primary  btn-cam-trans btn-sm" id="dowload_pre" type="submit" name="consultar" >
                    <i class="fa fa-download"></i> &nbsp;&nbsp;Exporte preplanillas
            </button>
        </li>
    @endif

    <li>
        <button  style="width:100%;" class="btn btn-primary  btn-cam-trans btn-sm" onclick="abrirModalExportePro()" type="submit" name="consultar" >
                <i class="fa fa-download"></i> &nbsp;&nbsp;Exportar programación
        </button>
    </li>
  </ul>
</div>
<?php // echo  "<h1>".$pdescargos ."</h1>" ?>

        @if(isset($pdescargos) && $pdescargos == 1 )
                            <a href="<?= Request::root() ?>/redes/gestor/descargos" class="btn btn-primary btn-cam-trans btn-sm"><i class="fa fa-book"></i> &nbsp;&nbsp;Gestor de Descargos</a>
                           @endif
                    
                            @if(isset($pmateriales) && $pmateriales == 1 )
                             <a href="<?= Request::root() ?>/redes/gestor/materiales" class="btn btn-primary btn-cam-trans btn-sm"><i class="fa fa-wrench"></i> &nbsp;&nbsp;Gestor de Materiales</a>
                            @endif

<div id="filter" class="collapse in" style="margin-top:9px;border: 1px solid #3b8bd0;    border-radius: 10px;" arial-expanded="true">

    <div class="panel-body posisition-fixed-headaer" style="border-radius: 10px;padding-top: 12px;">
    <div class="row">
    
@if($opc == "1")
    {!! Form::open(['url' => 'consultaFiltroOrdenes', "method" => "POST"]) !!}
@endif
@if($opc == "2")
    {!! Form::open(['url' => 'consultaFiltroOrden', "method" => "POST"]) !!}
@endif
@if($opc == "3")
    {!! Form::open(['url' => 'consultaFiltroDoc', "method" => "POST"]) !!}
@endif

        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_proyect">Desde:</label>
                    <div class="input-group date form_date no_select" data-date="" data-date-format="dd/mm/yyyy"
                         style="    width: 100%;">
                        @if(Session::has('fecha11'))
                            <?php
                                if(Session::get('fecha11') != "")
                                {
                                    $fechaIni =  Session::get('fecha11');
                                }
                                else
                                {
                                    $fechaIni = $fecha2;
                                }
                                
                            ?>
                            <input class="form-control" size="16" style="height:30px;" type="text"
                               value="{{$fechaIni}}" name="fecha_inicio" id="fecha_inicio"
                               placeholder="dd/mm/aaaa" onBlur="valida_fecha(this.form);">
                        @else
                            <input class="form-control" size="16" style="height:30px;" type="text"
                               value="{{$fecha2}}" name="fecha_inicio" id="fecha_inicio"
                               placeholder="dd/mm/aaaa" onBlur="valida_fecha(this.form);">
                        @endif
                        <span class="input-group-addon"><i class="fa fa-times"></i></span>
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span> 
                    </div>
                </div>
        </div>

        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="text_nombre_proyect">Hasta:</label>
                    <div class="input-group date form_date no_select" data-date="" data-date-format="dd/mm/yyyy" style="    width: 100%;">
                        @if(Session::has("fecha21"))
                            <?php
                                 if(Session::get('fecha21') != "")
                                {
                                    $fechaFin =Session::get('fecha21');
                                }
                                else
                                {
                                    $fechaFin = $fecha;
                                }
                            ?>
                            <input class="form-control" size="16" style="height:30px;" type="text"
                               value="{{$fechaFin}}" name="fecha_corte" id="fecha_corte"
                               placeholder="dd/mm/aaaa" onBlur="valida_fecha(this.form);">
                        @else
                            <input class="form-control" size="16" style="height:30px;" type="text"
                               value="{{$fecha}}" name="fecha_corte" id="fecha_corte"
                               placeholder="dd/mm/aaaa" onBlur="valida_fecha(this.form);">
                        @endif
                        <span class="input-group-addon"><i class="fa fa-times"></i></span>
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>
        </div>

        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden">Tipo:</label>
                    <select name="id_tipo" id="id_tipo" class="form-control" style="max-width:250px;padding:0px;" onchange="cambioProyecto()">
                        @foreach($proyecto as $key => $val)
                            @if(Session::get("tipo1") == $val->id_proyecto)
                                <option value="{{$val->id_proyecto}}" selected>{{$val->des_proyecto}}</option>
                            @else
                                <option value="{{$val->id_proyecto}}" >{{$val->des_proyecto}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
        </div>

<?php 
     if(isset($tproceso)){
        ?>
        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden">Tipo Proceso:</label>
                    <select name="tproceso" id="tproceso" class="form-control" style="max-width:250px;padding:0px;" onchange="cambioProyecto()">
                        
                        <option value="0" selected>[Seleccione]</option>
                        @foreach($tproceso as $tpro)
                             @if( Session::get("tproceso")!= null && Session::get("tproceso") != '' &&    Session::get("tproceso") == $tpro->tipo_proceso )
                           
                                <option value="{{$tpro->tipo_proceso}}" selected>{{$tpro->tipo_proceso}}</option>
                            @else
                                <option value="{{$tpro->tipo_proceso}}" >{{$tpro->tipo_proceso}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
        </div>
<?php } ?>

        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden">Estado:</label>
                        <select name="cbo_estado" id="cbo_estado" required class="form-control"
                        style="max-width:300px;padding:0px;">
                        <option value="0" selected>[Seleccione]</option>
                        @foreach($estad as $est => $val)
                            <option value="{{$val->id}}">{{$val->nom}}</option>
                        @endforeach
                </select>
                </div>
        </div>

<?php /*
        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden">Estado NODO:</label>
                    <select name="estadonodo" id="estadonodo" required class="form-control"
                    style="max-width:300px;padding:0px;">
                            <option value="0" <?= (Session::has('estadonodo') && Session::get("estadonodo")=="0" )?'selected':'' ?> >[Seleccione]</option>
                            <option value=""  <?= (Session::has('estadonodo') && Session::get("estadonodo")==""  )?'selected':'' ?> >Sin Estado</option>
                            <option value="E" <?= (Session::has('estadonodo') && Session::get("estadonodo")=="E" )?'selected':'' ?> >Ejecutada</option>
                            <option value="R" <?= (Session::has('estadonodo') && Session::get("estadonodo")=="R" )?'selected':'' ?> >Reprogramada</option>
                            <option value="C" <?= (Session::has('estadonodo') && Session::get("estadonodo")=="C" )?'selected':'' ?> >Cancelada</option>
                    </select>
                </div>
        </div>
*/ ?>

        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="proyecto">N° Proyecto:</label>
                        @if(Session::has("proyecto1"))
                            <input name="proyecto" type="text" class="form-control" id="proyecto" value="{{Session::get('proyecto1')}}"/>
                        @else
                            <input name="proyecto" type="text" class="form-control" id="proyecto" value=""/>
                        @endif
                </div>
        </div>
        
        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="proyectoN">Nombre Proyecto:</label>
                    @if(Session::has("proyectoN1"))
                        <input name="proyectoN" type="text" class="form-control" id="proyectoN" value="{{Session::get('proyectoN1')}}"/>
                    @else
                        <input name="proyectoN" type="text" class="form-control" id="proyectoN" value=""/>
                    @endif
                </div>
        </div>
        @if($opc == "2")
        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="proyectoN">ManiObra:</label>
                    @if(Session::has("ordenM12"))
                        <input name="ordenManiObra" type="text" class="form-control" id="ordenManiObra" value="{{Session::get('ordenM12')}}"/>
                    @else
                        <input name="ordenManiObra" type="text" class="form-control" id="ordenManiObra" value=""/>
                    @endif
                </div>
        </div>
        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="proyectoN">GOM:</label>
                    @if(Session::has("gomM2"))
                        <input name="ordenGOM" type="text" class="form-control" id="ordenGOM" value="{{Session::get('gomM2')}}"/>
                    @else
                        <input name="ordenGOM" type="text" class="form-control" id="ordenGOM" value=""/>
                    @endif
                </div>
        </div>

        @endif

        @if($opc == "3")
        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="proyectoN">ManiObra:</label>
                    @if(Session::has("ordenM1"))
                        <input name="ordenManiObra" type="text" class="form-control" id="ordenManiObra" value="{{Session::get('ordenM1')}}"/>
                    @else
                        <input name="ordenManiObra" type="text" class="form-control" id="ordenManiObra" value=""/>
                    @endif
                </div>
        </div>
        @endif
        <div class="col-md-2">
            <button type="submit" style="margin-top: 23px;" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" onclick="consultaFiltro()">
                <i class="fa fa-filter"></i> &nbsp;&nbsp;Consultar
            </button>
        </div>
    {!!Form::close()!!}

    </div>

</div>

</div>

<script type="text/javascript">
    
    function abrirModalExportePro()
    {
        $("#modal_exporte_programacion").modal("toggle");
    }

    function cambioProyecto()
    {
        if(document.querySelector("#id_tipo").value == "T03")
            document.querySelector("#panel_civiles_masivo_supervisores").style.display = "inline-block";
        else
            document.querySelector("#panel_civiles_masivo_supervisores").style.display = "none";
    }

    function abrirModalImportarPro()
    {
        $("#modal_import_masivo").modal("toggle");
    }

</script>