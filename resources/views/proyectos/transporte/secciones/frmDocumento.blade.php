<h3>Tipos de documentos</h3>
<div class="col-sm-12" style="position:relative;top:-20px;">
    <div id="ulPadreDocumento" class="formulario">
        <div class="checkbox">
        <?php  $aux = 1;?>
        @foreach($documentos as $idDocumento => $nombreDocumento)
            	<input type="checkbox" name="doc" data-id="{{$idDocumento}}" id="data_check_{{$aux}}">
				<label for="data_check_{{$aux}}">{{$nombreDocumento}}</label>
			    <br>
            <?php  $aux++;?>
        @endforeach
        </div>	
    </div>
</div>