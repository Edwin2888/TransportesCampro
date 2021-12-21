<h3>Asociación  Vehículo-Clase</h3>
<div class="col-sm-12" style="position:relative;top:-20px;">    
<br><br>
    <span for="">Tipo de Vehiculo</span> 

    {!!Form::select('selTipoCAM', $Tvehiculos, null, ["style"=>"box-shadow: rgb(91, 192, 222) 1px 0px 5px 0px;", "class"=>"form-control selectWzrd","placeholder"=>"Seleccione","id"=>"selTipoCAM","onchange"=>"listarDocumentosAsociados()"])!!}
    <div id="ulPadreClase" class="formulario">
        <div class="radio">
                <!-- <input id="rdNinguno" type="radio" name="clase" onclick="listarDocumentosAsociados(0)"/>
                <label for="rdNinguno">Ninguno</label>
                <br> -->
             <?php  $aux = 1;?>
            @foreach($clases as $idClase => $nombreClase)
                <input type="radio" name="clase" id="data_radio_{{$aux}}" data-id="{{$idClase}}" onclick="listarDocumentosAsociados()"/>
                <label for="data_radio_{{$aux}}">{{$nombreClase}}</label>
                <br>
            <?php  $aux++;?>
            @endforeach 
            <br>
        </div>  
    </div>
</div>
<div class="col-sm-12">
    <button type="button" class="btn btn-primary btn-cam-trans btn-sm " onclick="guardarAsociacionDocumentos()"> <i class="fa fa-save" aria-hidden="true"></i> Guardar</button>
    <a href="../../transversal/transporte/home" id="btn-salir" class="btn btn-primary btn-cam-trans btn-sm" id="btn-salir">Cerrar</a>
</div>
