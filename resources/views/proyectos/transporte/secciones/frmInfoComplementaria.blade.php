<h3>INFORMACIÓN COMPLEMENTARIA</h3>
<br>
<div class="row">
    <div class="col-md-1">
    </div>
    <div class="col-md-1">
        <span>Tiene GPS</span>
    </div>
    <div class="col-md-1">
        <div class="input-group">
            <input id="chkGps" type="checkbox" class="form-control">
        </div>
    </div>
    <div class="col-md-1">
        <span>Tiene capacete</span>
    </div>
    <div class="col-md-1">
        <div class="input-group">
            <input id="chkCapacete" type="checkbox" class="form-control">
        </div>
    </div>
    <div class="col-md-1">
        <span>Portaescalera</span>
    </div>
    <div class="col-md-1">
        <div class="input-group">
            <input id="chkPortaEscalera" type="checkbox" class="form-control">
        </div>
    </div>
    <div class="col-md-1">
        <span>Tiene caja de herramientas</span>
    </div>
    <div class="col-md-1">
        <div class="input-group">
            <input id="chkCajaHerramienta" type="checkbox" class="form-control">
        </div>
    </div>
    <div class="col-md-1">
        <span>Tiene pertiga</span>
    </div>
    <div class="col-md-1">
        <div class="input-group">
            <input id="chkPertiga" type="checkbox" class="form-control">
        </div>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-1 col-md-offset-1">
        <span>Propietario GPS</span>
    </div>
    <div class="col-md-4">
        <div class="input-group">
            <input id="txtPropGps" type="text" class="form-control" name="propGps" placeholder="Propietario GPS">
        </div>
    </div>
    <div class="col-md-1">
        <span>No. Chasis</span>
    </div>
    <div class="col-md-4">
        <div class="input-group">
            <input id="txtNoChasis" type="text" class="form-control" name="noChasis" placeholder="No. chasis">
        </div>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-1 col-md-offset-1">
        <span>Proveedor Monitoreo</span>
    </div>
    <div class="col-md-4">
        <div class="input-group">
            {!!Form::select('idProvMoni', $proveedorMonitoreo, null, ["class"=>"form-control selectWzrd","placeholder"=>"Seleccione","id"=>"selProveedorMonitoreo"])!!}
            &nbsp;
            @if($acceso == "W")
            <button class="btn btnWzrd fa  fa-pencil-square-o btn-cam-trans btn-sm" onclick="abrirModal(5)"></button>
            @endif
        </div>
    </div>
    <div class="col-md-1">
        <span>No. Motor</span>
    </div>
    <div class="col-md-4">
        <div class="input-group">
            <input id="txtNoMotor" type="text" class="form-control" name="noMotor" placeholder="No. motor">
        </div>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-1 col-md-offset-1">
        <span>Serie GPS</span>
    </div>
    <div class="col-md-4">
        <div class="input-group">
            <input id="txtSerieGps" type="text" class="form-control" name="serieGps" placeholder="Serie GPS">
        </div>
    </div>
    <div class="col-md-1">
        <span>No. Orden</span>
    </div>
    <div class="col-md-4">
        <div class="input-group">
            <input id="txtNoOrden" type="text" class="form-control" name="noOrden" placeholder="No. orden">
        </div>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-1 col-md-offset-1">
        <span>Cliente/ proyecto</span>
    </div>
    <div class="col-md-4">
        <div class="input-group">
            <select id="selProyectoCliente" class="form-control selectWzrd" style="    width: 80% !important;">
                <option>Seleccione</option>
                @foreach($clienteProyecto as $key => $val)
                    <option value="{{$val->id}}">{{$val->ceco}} - {{$val->nombre}}</option>
                @endforeach
            </select>
            @if($acceso == "W")
            <button class="btn btnWzrd fa  fa-refresh btn-cam-trans btn-sm" style="    margin-left: 10px;" onclick="abrirModal(8)" title="Novedad Cambio de Proyectos"></button>
            <button class="btn btnWzrd fa  fa-pencil-square-o btn-cam-trans btn-sm"  style="    margin-left:5px;"  onclick="abrirModal(6)"></button>
            @endif
        </div>
    </div>
    <div class="col-md-1">
        <span>Valor canon</span>
    </div>
    <div class="col-md-4">
        <div class="input-group">
            <input id="txtValorCanon" type="text" class="form-control" name="valorCanon" placeholder="Valor canon">
        </div>
    </div>
</div>


<br>
<div class="row">
    <div class="col-md-1 col-md-offset-1">
        <span>Tipo de vehículo CAM</span>
    </div>
    <div class="col-md-4">
        <div class="input-group">
            {!!Form::select('selTipoCAM', $tipo_cam, null, ["class"=>"form-control selectWzrd","placeholder"=>"Seleccione","id"=>"selTipoCAM"])!!}
        </div>
    </div>

    <div class="col-md-1">
        <span>KM Promedio</span>
    </div>
    <div class="col-md-4">
        <div class="input-group">
            <input id="txtKmPromedio" 
              type="number" 
              class="form-control" 
              name="txtKmPromedio" 
              placeholder="KM Promedio" />
        </div>
    </div>
</div>


<br>
@if($acceso == "W")
<ul class="list-inline pull-right">
    <li><button type="button" class="btn btn-default prev-step">Anterior</button></li>
    <li><button type="button" class="btn btn-primary btn-info-full next-step" onclick="guardaInfoComplementaria()">Siguiente</button></li>
</ul>
@endif