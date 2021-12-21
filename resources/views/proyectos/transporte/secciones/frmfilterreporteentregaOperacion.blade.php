
<button data-toggle="collapse" data-target="#filter" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar">
    <i class="fa fa-filter"></i> &nbsp;&nbsp;Filtrar
</button>

<div id="filter" class="collapse in" style="margin-top:9px;border: 1px solid #3b8bd0;    border-radius: 10px;" arial-expanded="true">

    <div class="panel-body posisition-fixed-headaer" style="border-radius: 10px;padding-top: 12px;">
    <div class="row">
    
{!! Form::open(['url' => 'filterEntregaOperacionRo', "method" => "POST"]) !!}

                <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_proyect">Desde:</label>
                    <div class="input-group date form_date" data-date="" data-date-format="dd/mm/yyyy"
                         style="    width: 100%;">
                        @if(Session::has('fecha_inicio'))
                            <?php
                                if(Session::get('fecha_inicio') != "")
                                {
                                    $fechaIni = Session::get('fecha_inicio');
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
                        @if(Session::has("fecha_corte"))
                            <?php
                                 if(Session::get('fecha_corte') != "")
                                {
                                    $fechaFin = Session::get('fecha_corte');
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

        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden">Tipo de vehículo:</label>
                    {!!Form::select('selTipoVehiculo', $tipoM, Session::get('selTipoVehiculoEP'), ["class"=>"form-control selectWzrd","placeholder"=>"Seleccione","id"=>"selTipoVehiculo"])!!}
                </div>
        </div>

      


        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden">Proyecto/Cliente:</label>
                    {!!Form::select('selProyectoCliente', $proy, Session::get('selProyectoClienteEP'), ["class"=>"form-control selectWzrd","placeholder"=>"Seleccione","id"=>"selProyectoCliente"])!!}
                    </select>
                </div>
        </div>

        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden">Incidencia:</label>
                    <input type="text" name="txt_incidencia" id="txt_incidencia" class="form-control" value="{{Session::get('txt_incidenciaEP')}}">
                </div>
        </div>

          <div class="col-md-1">
            <button type="submit" style="margin-top: 23px;" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" onclick="consultaFiltro()">
                <i class="fa fa-filter"></i> &nbsp;&nbsp;Consultar
            </button>
        </div>

         <div class="col-md-1">
            <a href="../../transversal/transporte/home" class="btn btn-primary btn-cam-trans btn-sm" style="    margin-left: 0px;    margin-top: 23px;    left: -8%;    position: relative;"><i class="fa fa-times"></i> &nbsp; Regresar</a>
        </div>


        </div>
        <div class="row">
        
     
              
    {!!Form::close()!!}

    </div>

</div>

</div>