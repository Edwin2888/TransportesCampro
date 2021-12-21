
<button data-toggle="collapse" data-target="#filter" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar">
    <i class="fa fa-filter"></i> &nbsp;&nbsp;Filtrar
</button>

<div id="filter" class="collapse in" style="margin-top:9px;border: 1px solid #3b8bd0;    border-radius: 10px;" arial-expanded="true">

    <div class="panel-body posisition-fixed-headaer" style="border-radius: 10px;padding-top: 12px;">
    <div class="row">
    
{!! Form::open(['url' => 'filterVehiculos', "method" => "POST"]) !!}

        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden">Tipo de vehículo:</label>
                    {!!Form::select('selTipoVehiculo', $tipo_v, Session::get('selTipoVehiculo'), ["class"=>"form-control selectWzrd","placeholder"=>"Seleccione","id"=>"selTipoVehiculo"])!!}
                </div>
        </div>

        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden">Estado:</label>
                    {!!Form::select('selEstado', $estados, Session::get('selEstado'), ["class"=>"form-control selectWzrd","placeholder"=>"Seleccione","id"=>"selEstado"])!!}
                </div>
        </div>

        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden">Proyecto/Cliente:</label>
                    {!!Form::select('selProyectoCliente', $proyecto, Session::get('selProyectoCliente'), ["class"=>"form-control selectWzrd","placeholder"=>"Seleccione","id"=>"selProyectoCliente"])!!}
                </div>
        </div>

        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden">Propietario:</label>
                    {!!Form::select('selPropietario', $propietarios, Session::get('selPropietario'), ["class"=>"form-control selectWzrd","placeholder"=>"Seleccione","id"=>"selPropietario"])!!}
                </div>
        </div>
       
        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden">Placa:</label>
                    <input type="text" class="form-control" name="placa_consulta" value="{{Session::get('placa_consulta')}}"/>
                </div>
        </div>

       
        
        </div>
        </div>
        <div class="row" style="margin-bottom:10px;">
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" onclick="consultaFiltro()">
                <i class="fa fa-filter"></i> &nbsp;&nbsp;Consultar
            </button>
        </div>
        <div class="col-md-2" >
            <a href="../../transversal/transporte/home"  style="position:relative;left:-110px;" class="btn btn-primary btn-cam-trans btn-sm"><i class="fa fa-arrows-alt"></i> &nbsp; Cerrar</a>
        </div>
            
    
        
    {!!Form::close()!!}

    </div>

</div>

</div>