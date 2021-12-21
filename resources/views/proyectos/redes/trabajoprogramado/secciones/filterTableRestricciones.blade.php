
<div id="filter" class="collapse in" style="margin-top:9px;border: 1px solid #3b8bd0;    border-radius: 10px;" arial-expanded="true">

    <div class="panel-body posisition-fixed-headaer" style="border-radius: 10px;padding-top: 12px;">
    <div class="row">
    

    {!! Form::open(['url' => 'filterTableRestricciones', "method" => "POST"]) !!}


        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_proyect">Desde:</label>
                    <div class="input-group date form_date" data-date="" data-date-format="dd/mm/yyyy"
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
                    <div class="input-group date form_date" data-date="" data-date-format="dd/mm/yyyy" style="    width: 100%;">
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
                               value="{{$fecha1}}" name="fecha_corte" id="fecha_corte"
                               placeholder="dd/mm/aaaa" onBlur="valida_fecha(this.form);">
                        @endif
                        <span class="input-group-addon"><i class="fa fa-times"></i></span>
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>
        </div>

        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden">Tipo proyecto:</label>
                    <select name="id_tipo" id="id_tipo" class="form-control" style="max-width:250px;padding:0px;">
                        @if(Session::has("tipo1"))
                            @if(Session::get("tipo1") == "T01")
                                <option value="0">Todos</option>
                                <option value="T01" selected>Cartas y nueva demanda</option>
                                <option value="T02">Inversión y mantenimiento</option>
                            @else
                                @if(Session::get("tipo1") == "T01")
                                    <option value="0">Todos</option>
                                    <option value="T01">Cartas y nueva demanda</option>
                                    <option value="T02" selected>Inversión y mantenimiento</option>
                                @else
                                    <option value="0">Todos</option>
                                    <option value="T01">Cartas y nueva demanda</option>
                                    <option value="T02">Inversión y mantenimiento</option>
                                @endif
                            @endif
                        @else
                            <option value="0">Todos</option>
                            <option value="T01">Cartas y nueva demanda</option>
                            <option value="T02">Inversión y mantenimiento</option>
                        @endif
                    </select>
                </div>
        </div>

        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden">Estado:</label>
                    <select name="id_estado" id="id_estado" class="form-control" style="max-width:250px;padding:0px;">
                        @if(Session::has("esta21"))
                            @if(Session::get("esta21") == "0")  
                                <option value="0">Todos</option>
                                <option value="A">Abiertas</option>
                                <option value="X">Ignoradas</option>
                                <option value="C">Cerradas</option>
                            @endif

                            @if(Session::get("esta21") == "A")  
                                <option value="0">Todos</option>
                                <option value="A" selected>Abiertas</option>
                                <option value="X">Ignoradas</option>
                                <option value="C">Cerradas</option>
                            @endif

                            @if(Session::get("esta21") == "X")  
                                <option value="0">Todos</option>
                                <option value="A">Abiertas</option>
                                <option value="X" selected>Ignoradas</option>
                                <option value="C">Cerradas</option>
                            @endif

                            @if(Session::get("esta21") == "C")  
                                <option value="0">Todos</option>
                                <option value="A">Abiertas</option>
                                <option value="X">Ignoradas</option>
                                <option value="C" selected>Cerradas</option>
                            @endif
                        @else
                            <option value="0">Todos</option>
                            <option value="A">Abiertas</option>
                            <option value="X">Ignoradas</option>
                            <option value="C">Cerradas</option>
                        @endif
                            
                    </select>
                </div>
        </div>

        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden" style="    display: block;">Proyecto:</label>
                    <input name="name_proyecto" id="id_proyecto" type="text" readonly="" value="{{Session::get('proyecto')}}" class="form-control" style="width: 69%;padding:0px;float: left;" placeholder="Proyecto">
                    <a onclick="abrirModal(1)" class="btn btn-primary  btn-cam-trans btn-sm" style="float: left;margin-left: 5px;height: 29px;"><i class="fa fa-search" style="    position: relative;    top: -2px;"></i></a>
                    <a onclick="limpiar1(1)" class="btn btn-primary  btn-cam-trans btn-sm" style="float: left;margin-left: 5px;height: 29px;"><i class="fa fa-trash" style="    position: relative;    top: -2px;"></i></a>
                </div>
        </div>

        </div>
        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden">Orden de trabajo:</label>
                    <input name="name_orden" type="text" class="form-control" value="{{Session::get('orden')}}" style="max-width:250px;padding:0px;" placeholder="Orden de trabajo">
                </div>
        </div>
        
        <div class="row">
            <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden" style="    display: block;">Responsable:</label>
                    <input name="responsable" id="id_responsable"  value="{{Session::get('responsable')}}" type="text" readonly="" class="form-control" style="width: 69%;padding:0px;float: left;" placeholder="Responsable">
                    <a onclick="abrirModal(2)" class="btn btn-primary  btn-cam-trans btn-sm" style="float: left;margin-left: 5px;height: 29px;"><i class="fa fa-search" style="    position: relative;    top: -2px;"></i></a>
                    <a onclick="limpiar1(2)" class="btn btn-primary  btn-cam-trans btn-sm" style="float: left;margin-left: 5px;height: 29px;"><i class="fa fa-trash" style="    position: relative;    top: -2px;"></i></a>
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" style="margin-top: 23px;" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" onclick="consultaFiltroRestricciones()">
                <i class="fa fa-filter"></i> &nbsp;&nbsp;Consultar
            </button>
            <a style="position: relative;    top: 12px;" href="../../redes/ordenes/home" id="btn-salir" class="btn btn-primary btn-cam-trans btn-sm" id="btn-salir">Cerrar</a>
            </div>
        </div>

    {!!Form::close()!!}

        <div class="elementos">   
            <a href="#" style="background-color:#9e9e9e"><span>1</span></a>         
            <a href="../../scrumRestricciones" ><span>2</span></a>         
        </div>
    </div>

</div>

</div>

<br>