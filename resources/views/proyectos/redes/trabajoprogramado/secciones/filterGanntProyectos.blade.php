<br><br>
<div id="filter" class="collapse in" style="margin-top:9px;border: 1px solid #3b8bd0;    border-radius: 10px;" arial-expanded="true">
    <div class="panel-body posisition-fixed-headaer" style="border-radius: 10px;padding-top: 12px;">
    <div class="row">
    {!! Form::open(['url' => 'filterganntProyectos', "method" => "POST"]) !!}
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
                                <option value="T01" selected>Cartas y nueva demanda</option>
                                <option value="T02">Inversión y mantenimiento</option>
                            @else
                                <option value="T01">Cartas y nueva demanda</option>
                                <option value="T02" selected>Inversión y mantenimiento</option>
                            @endif
                        @else
                            <option value="T01">Cartas y nueva demanda</option>
                            <option value="T02">Inversión y mantenimiento</option>
                        @endif
                    </select>
                </div>
        </div>

            <button type="submit" style="margin-top: 23px;" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" onclick="consultaFiltroProyecto()">
                <i class="fa fa-filter"></i> &nbsp;&nbsp;Consultar
            </button>
            <a style="position: relative;    top: 12px;" href="../../redes/ordenes/home" id="btn-salir" class="btn btn-primary btn-cam-trans btn-sm" id="btn-salir">Cerrar</a>
        

        
    {!!Form::close()!!}

    <div class="elementos">   
            <a  style="background-color:#9e9e9e" ><span>1</span></a>         
            <a href="../../scrumOrdenes"    ><span>2</span></a>         
            <a href="../../redes/ordenes/reportes"    ><span>3</span></a>         
    </div>

    </div>

</div>

</div>