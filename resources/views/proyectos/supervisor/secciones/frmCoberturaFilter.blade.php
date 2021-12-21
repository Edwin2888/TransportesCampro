<div id="filter" class="collapse in" style="margin-top:9px;border: 1px solid #3b8bd0;    border-radius: 10px;" arial-expanded="true">

    <div class="panel-body posisition-fixed-headaer" style="border-radius: 10px;padding-top: 12px;">
    <div class="row">
    
{!! Form::open(['url' => 'transversal/reporte/cobertura/filter', "method" => "POST"]) !!}

        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden">Proyecto:</label>
                    <select name="id_proyecto" id="id_proyecto" class="form-control" style="max-width:250px;padding:0px;">
                        <option value="">Todos</option>
                        @foreach($proyectos as $key => $valor)
                            @if(Session::get('proyecto_cobertura') == $valor->prefijo_db)
                                <option value="{{$valor->prefijo_db}}" selected>{{$valor->proyecto}}</option>
                            @else
                                <option value="{{$valor->prefijo_db}}">{{$valor->proyecto}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
        </div>

        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_proyect">Desde:</label>
                    <div class="input-group date form_date" data-date="" data-date-format="dd/mm/yyyy"
                         style="    width: 100%;">
                        @if(Session::has('fecha_ini_cobertura'))
                            <?php
                                if(Session::get('fecha_ini_cobertura') != "")
                                {
                                    $fechaIni = Session::get('fecha_ini_cobertura');
                                }
                                else
                                {
                                    $fechaIni = $fecha2;
                                }
                                
                            ?>
                            <input class="form-control" size="16" style="height:30px;" type="text"
                               value="{{$fechaIni}}" name="fecha_inicio" id="fecha_inicio"
                               placeholder="dd/mm/aaaa" >
                        @else
                            <input class="form-control" size="16" style="height:30px;" type="text"
                               value="{{$fecha2}}" name="fecha_inicio" id="fecha_inicio"
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
                        @if(Session::has("fecha_fin_cobertura"))
                            <?php
                                 if(Session::get('fecha_fin_cobertura') != "")
                                {
                                    $fechaFin = Session::get('fecha_fin_cobertura');
                                }
                                else
                                {
                                    $fechaFin = $fecha1;
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

        
        <div class="col-md-1">
            <button type="submit" style="margin-top: 23px;" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" onclick="consultaFiltro()">
                <i class="fa fa-filter"></i> &nbsp;&nbsp;Consultar
            </button>
        </div>
    {!!Form::close()!!}

    </div>

</div>

</div>


<?php
    Session::forget('proyecto_cobertura');
    Session::forget('fecha_ini_cobertura');
    Session::forget('fecha_fin_cobertura');
?>