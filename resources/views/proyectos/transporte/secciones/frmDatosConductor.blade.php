<h3>DATOS DEL CONDUCTOR</h3>
<br>
<div class="row">
    <div class="col-md-1 col-md-offset-1">
        <span>Nombre</span>
    </div>
    <div class="col-md-4">
        <div class="input-group">
            <input id="txtnombreconductor" readonly type="text" class="form-control" name="txtnombreconductor" placeholder="Nombre conductor" style="    width: 86%;">
            &nbsp;
            @if($acceso == "W")
            <button class="btn btnWzrd fa  fa-pencil-square-o btn-cam-trans btn-sm" onclick="abrirModal(7)"></button>
            @endif
        </div>
    </div>
    <div class="col-md-1">
        <span>C.C</span>
    </div>
    <div class="col-md-4">
        <div class="input-group">
            <input id="txtccconductor" readonly type="text" class="form-control" name="txtccconductor" placeholder="C.C">
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
            <input id="txtdomiconductor" readonly type="text" class="form-control" name="txtdomiconductor" placeholder="Domicilio">
        </div>
    </div>

    <div class="col-md-1">
        <span>Teléfono</span>
    </div>
    <div class="col-md-4">
        <div class="input-group">
            <input id="txttelconductor" readonly type="text" class="form-control" name="txttelconductor" placeholder="Teléfono fijo">
        </div>
    </div>
</div>

<br>
<div class="row">

    <div class="col-md-1  col-md-offset-1">
        <span>E-Mail</span>
    </div>
    <div class="col-md-4">
        <div class="input-group">
            <input id="txtemailconductor" readonly type="text" class="form-control" name="txtemailconductor" placeholder="E-Mail">
        </div>
    </div>
</div>


@if($acceso == "W")
<ul class="list-inline pull-right">
    <li><button type="button" class="btn btn-default prev-step">Anterior</button></li>
    <li><button type="button" class="btn btn-primary next-step" onclick="saveInformacionConductor();">Siguiente</button></li>

</ul>
@endif