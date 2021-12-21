<h3>DATOS DEL PROPIETARIO</h3>
<br>
<div class="row">
    <div class="col-md-1 col-md-offset-1">
        <span>Nombre</span>
    </div>
    <div class="col-md-4">
        <div class="input-group">
            {!!Form::select('selPropietario', $propietarios, null, ["style"=>"width:90%;", "class"=>"form-control selectWzrd","placeholder"=>"Seleccione","id"=>"selPropietario"])!!}
            &nbsp;
            @if($acceso == "W")
            <button class="btn btnWzrd fa  fa-pencil-square-o  btn-cam-trans btn-sm" onclick="abrirModal(4)"></button>
            @endif
        </div>
    </div>
    <div class="col-md-1">
        <span>C.C</span>
    </div>
    <div class="col-md-4">
        <div class="input-group">
            <input id="txtccpropietario" type="text" class="form-control" name="txtccpropietario" placeholder="C.C">
        </div>
    </div>
</div>

<br>
<div class="row">
    <div class="col-md-1 col-md-offset-1">
        <span>Domicilio</span>
    </div>
    <div class="col-md-4">
        <div class="input-group">
            <input id="txtdomicilio" type="text" class="form-control" name="txtdomicilio" placeholder="Domicilio">
        </div>
    </div>

    <div class="col-md-1">
        <span>Teléfono fijo</span>
    </div>
    <div class="col-md-4">
        <div class="input-group">
            <input id="txttelefonofijo" type="text" class="form-control" name="txttelefonofijo" placeholder="Teléfono fijo">
        </div>
    </div>
</div>

<br>
<div class="row">
    <div class="col-md-1 col-md-offset-1">
        <span>Celular</span>
    </div>
    <div class="col-md-4">
        <div class="input-group">
            <input id="txtcelular" type="text" class="form-control" name="txtcelular" placeholder="Celular">
        </div>
    </div>

    <div class="col-md-1">
        <span>E-Mail</span>
    </div>
    <div class="col-md-4">
        <div class="input-group">
            <input id="txtemail" type="text" class="form-control" name="txtemail" placeholder="E-Mail">
        </div>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-1 col-md-offset-1">
        <span>Responsable SAP</span>
    </div>
    <div class="col-md-4">
        <div class="input-group">
            <input id="txtResponsable" type="text" class="form-control" name="txtResponsable" disabled>
        </div>
    </div>
    <div class="col-md-1">
        <span>Elemento pep</span>
    </div>
    <div class="col-md-4">
        <div class="input-group">
            <input id="txtprueba" type="text" class="form-control" name="txtemail" placeholder="E-Mail">
        </div>
    </div>
</div>

@if($acceso == "W")
<ul class="list-inline pull-right">
    <li><button type="button" class="btn btn-default prev-step">Anterior</button></li>
    <li><button type="button" class="btn btn-primary next-step" onclick="savePropietarioData()">Siguiente</button></li>
</ul>
@endif