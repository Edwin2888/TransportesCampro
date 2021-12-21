<div id="filter" class="collapse in" style="margin-top:9px;border: 1px solid #3b8bd0;    border-radius: 10px;" arial-expanded="true">

    <div class="panel-body posisition-fixed-headaer" style="border-radius: 10px;padding-top: 12px;">
    <div class="row">
    
{!! Form::open(['url' => 'filterinasistencias', "method" => "POST"]) !!}

        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_proyect">Desde:</label>
                    <div class="input-group date form_date" data-date="" data-date-format="dd/mm/yyyy"
                         style="    width: 100%;">
                        @if(Session::has('fecha_filter_1'))
                            <?php
                                if(Session::get('fecha_filter_1') != "")
                                {
                                    $fechaIni = Session::get('fecha_filter_1');
                                }
                                else
                                {
                                    $fechaIni = $fecha1;
                                }
                                
                            ?>
                            <input class="form-control" size="16" style="height:30px;" type="text"
                               value="{{$fechaIni}}" name="fecha_filter_1" id="fecha_filter_1"
                               placeholder="dd/mm/aaaa" >
                        @else
                            <input class="form-control" size="16" style="height:30px;" type="text"
                               value="{{$fecha1}}" name="fecha_filter_1" id="fecha_filter_1"
                               placeholder="dd/mm/aaaa" >
                        @endif
                        <span class="input-group-addon"><i class="fa fa-times"></i></span>
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span> 
                    </div>
                </div>
        </div>

        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="text_nombre_proyect">Hasta:</label>
                    <div class="input-group date form_date" data-date="" data-date-format="dd/mm/yyyy" style="    width: 100%;">
                        @if(Session::has("fecha_filter_2"))
                            <?php
                                 if(Session::get('fecha_filter_2') != "")
                                {
                                    $fechaFin = Session::get('fecha_filter_2');
                                }
                                else
                                {
                                    $fechaFin = $fecha2;
                                }
                            ?>
                            <input class="form-control" size="16" style="height:30px;" type="text"
                               value="{{$fechaFin}}" name="fecha_filter_2" id="fecha_filter_2"
                               placeholder="dd/mm/aaaa" onBlur="valida_fecha(this.form);">
                        @else
                            <input class="form-control" size="16" style="height:30px;" type="text"
                               value="{{$fecha2}}" name="fecha_filter_2" id="fecha_filter_2"
                               placeholder="dd/mm/aaaa" onBlur="valida_fecha(this.form);">
                        @endif
                        <span class="input-group-addon"><i class="fa fa-times"></i></span>
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>
        </div>

        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden">Proyecto:</label>
                    <select  name="filter-proyecto-inasistencia" id="filter-proyecto-inasistencia" placeholder="Proyecto" class="form-control" style="max-width:250px;padding:0px;">
                        <option value="-1">Todos</option>
                        @for($i = 0; $i < count($proyectos); $i++)
                            @if(Session::get('filter-proyecto-inasistencia') == $proyectos[$i]->prefijo_db)
                                <option value="{{$proyectos[$i]->prefijo_db}}" selected>{{$proyectos[$i]->proyecto}}</option>
                            @else
                                <option value="{{$proyectos[$i]->prefijo_db}}">{{$proyectos[$i]->proyecto}}</option>
                            @endif
                        @endfor
                    </select>
                </div>
        </div>


        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden">Estado:</label>
                    <select name="estado_filter_ina" id="estado_filter_ina" class="form-control" style="max-width:250px;padding:0px;">
                        @if(Session::get('estado_filter_ina') == null || Session::get('estado_filter_ina') == "-1")
                            <option value="-1">Todos</option>
                            <option value="0">Asistio</option>
                            <option value="1">No Asistio</option>
                            <option value="2">Incapacitado</option>
                            <option value="4">Inhabilitado</option>
                            <option value="5">Cambio de turno</option>
                        @endif

                        @if(Session::get('estado_filter_ina') == "0")
                            <option value="-1">Todos</option>
                            <option value="0" selected>Asistio</option>
                            <option value="1">No Asistio</option>
                            <option value="2">Incapacitado</option>
                            <option value="4">Inhabilitado</option>
                            <option value="5">Cambio de turno</option>
                        @endif

                        @if(Session::get('estado_filter_ina') == "1")
                            <option value="-1">Todos</option>
                            <option value="0">Asistio</option>
                            <option value="1" selected>No Asistio</option>
                            <option value="2">Incapacitado</option>
                            <option value="4">Inhabilitado</option>
                            <option value="5">Cambio de turno</option>
                        @endif

                        @if(Session::get('estado_filter_ina') == "2")
                            <option value="-1">Todos</option>
                            <option value="0">Asistio</option>
                            <option value="1">No Asistio</option>
                            <option value="2" selected>Incapacitado</option>
                            <option value="4">Inhabilitado</option>
                            <option value="5">Cambio de turno</option>
                        @endif

                        @if(Session::get('estado_filter_ina') == "4")
                            <option value="-1">Todos</option>
                            <option value="0">Asistio</option>
                            <option value="1">No Asistio</option>
                            <option value="2">Incapacitado</option>
                            <option value="4" selected>Inhabilitado</option>
                            <option value="5">Cambio de turno</option>
                        @endif

                        @if(Session::get('estado_filter_ina') == "5")
                            <option value="-1">Todos</option>
                            <option value="0">Asistio</option>
                            <option value="1">No Asistio</option>
                            <option value="2">Incapacitado</option>
                            <option value="4">Inhabilitado</option>
                            <option value="5" selected>Cambio de turno</option>
                        @endif
                    </select>
                </div>
        </div>

        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden">Turno:</label>
                    <select name="filter_turno_ina" id="filter_turno_ina" class="form-control" style="max-width:250px;padding:0px;">
                        @if(Session::get('filter_turno_ina') == null || Session::get('filter_turno_ina') == "-1")
                            <option value="-1">Todos</option>
                            <option value="A">A</option>
                            <option value="C">C</option>
                        @endif
                        @if( Session::get('filter_turno_ina') == "A")
                            <option value="-1">Todos</option>
                            <option value="A" selected>A</option>
                            <option value="C">C</option>
                        @endif
                        @if( Session::get('filter_turno_ina') == "C")
                            <option value="-1">Todos</option>
                            <option value="A" >A</option>
                            <option value="C" selected>C</option>
                        @endif
                    </select>
                </div>
        </div>

        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden">Móvil:</label>
                    <input value="{{Session::get('filter_movil_ina')}}" name="filter_movil_ina" id="filter_movil_ina" placeholder="Móvil" class="form-control" style="max-width:250px;padding:0px;"/>
                </div>
        </div>

        

    </div>

    <div class="row">

        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden">Cuadrillero:</label>
                    <input value="{{Session::get('filter_cuadrillero_ina')}}" name="filter_cuadrillero_ina" id="filter_cuadrillero_ina" placeholder="Cédula cuadrillero" class="form-control" style="max-width:250px;padding:0px;"/>
                </div>
        </div>


        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden">Supervisor:</label>
                    <input value="{{Session::get('filter_super_ina')}}" name="filter_super_ina" id="filter_super_ina" placeholder="Cédula supervisor" class="form-control" style="max-width:250px;padding:0px;"/>
                </div>
        </div>
        
        <div class="col-md-1">
            <button type="submit" style="margin-top: 23px;" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" onclick="consultaFiltro()">
                <i class="fa fa-filter"></i> &nbsp;&nbsp;Consultar
            </button>
        </div>

        <div class="col-md-1" >
            <a href="../../inspeccionOrdenes" class="btn btn-primary btn-cam-trans btn-sm" style="margin-top:23px;">
            <i class="fa fa-times" aria-hidden="true"></i>  Cerrar</a>
        </div>

        
    {!!Form::close()!!}

    </div>

</div>

</div>