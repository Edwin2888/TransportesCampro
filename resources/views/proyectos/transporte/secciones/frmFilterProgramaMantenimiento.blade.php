
<button data-toggle="collapse" data-target="#filter" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar">
    <i class="fa fa-filter"></i> &nbsp;&nbsp;Filtrar
</button>

<div id="filter" class="collapse in" style="margin-top:9px;border: 1px solid #3b8bd0;    border-radius: 10px;" arial-expanded="true">

    <div class="panel-body posisition-fixed-headaer" style="border-radius: 10px;padding-top: 12px;">
    <div class="row">
    
{!! Form::open(['url' => 'filterProgramaMantenimiento', "method" => "POST"]) !!}

                <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_proyect">Desde:</label>
                    <div class="input-group date form_date" data-date="" data-date-format="dd/mm/yyyy"
                         style="    width: 100%;">
                        @if(Session::has('fecha_inicio_prog_man'))
                            <?php
                                if(Session::get('fecha_inicio_prog_man') != "")
                                    $fechaIni = Session::get('fecha_inicio_prog_man');
                                else
                                    $fechaIni = $fecha2;
                            ?>
                            <input class="form-control" size="16" style="height:30px;" type="text"
                               value="{{$fechaIni}}" name="fecha_inicio_prog_man" id="fecha_inicio_prog_man"
                               placeholder="dd/mm/aaaa" onBlur="valida_fecha(this.form);">
                        @else
                            <input class="form-control" size="16" style="height:30px;" type="text"
                               value="{{$fecha2}}" name="fecha_inicio_prog_man" id="fecha_inicio_prog_man"
                               placeholder="dd/mm/aaaa" onBlur="valida_fecha(this.form);">
                        @endif
                        <span class="input-group-addon"><i class="fa fa-times"></i></span>
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span> 
                    </div>
                </div>
        </div>

        <!-- <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="text_nombre_proyect"># mantenimientos:</label>
                    <div class="input-group" data-date="" >
                        @if(Session::has('cant_mmto_prog_man'))
                            <input type="number" value="{{Session::get('cant_mmto_prog_man')}}" min="3" max="6" class="form-control" name="cant_mmto_prog_man"/>
                        @else
                            <input type="number" value="3" min="3" max="6" class="form-control" name="cant_mmto_prog_man"/>
                        @endif
                        
                    </div>
                </div>
        </div> -->

        <div class="col-md-3">
                <div class="form-group has-feedback">
                    <label for="id_orden">Tipo de vehículo:</label>
                    {!!Form::select('selTipoVehiculoProgMant', $tipo_v, Session::get('selTipoVehiculoProgMant'), ["class"=>"form-control selectWzrd","placeholder"=>"Seleccione","id"=>"selTipoVehiculoProgMant"])!!}
                </div>
        </div>

        <div class="col-md-4">
                <div class="form-group has-feedback">
                    <label for="id_orden">Proyecto/Cliente:</label>
                    {!!Form::select('selProyectoClienteProgMant', $proyecto, Session::get('selProyectoClienteProgMant'), ["class"=>"form-control selectWzrd","placeholder"=>"Seleccione","id"=>"selProyectoClienteProgMant","required"=>"required"])!!}
                </div>
        </div> 

        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden">Placa:</label>
                    <input type="text" value="{{Session::get('txtPlacaProgMant')}}" name="txtPlacaProgMant" class="form-control" />
                </div>
        </div> 


        

       </div>
       <div class="row">       
        <div class="col-md-1">
            <button type="submit" style="margin-top: 23px;" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" onclick="consultaFiltro()">
                <i class="fa fa-filter"></i> &nbsp;&nbsp;Consultar
            </button>
        </div>
        <div class="col-md-1">
            <a href="../../transversal/transporte/home" class="btn btn-primary btn-cam-trans btn-sm" style="    margin-left: 0px;    margin-top: 23px;    left: -8%;    position: relative;"><i class="fa fa-times"></i> &nbsp; Cerrar</a>
        </div>
        
        <div align="center" class="col-md-9">
            <div class="alert alert-info" role="alert">
              <strong><i class="fa fa-exclamation-triangle"></i></strong> Si el recorrido promedio real es menor al Recorrido promedio declarado en la ficha del vehículo;  el calculo de la fecha estimada de Mantenimiento se hará tomando como referencia el recorrido declarado en la Ficha del Vehículo.
            </div>
        </div>
    {!!Form::close()!!}

    </div>

</div>

</div>