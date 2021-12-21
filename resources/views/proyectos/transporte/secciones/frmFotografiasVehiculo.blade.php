<h3>FOTOGRAFÍAS VEHÍCULO</h3>
<br>
{!! Form::open(['url' => 'insertaGaleriaFotografias', "method" => "POST", "files" => true]) !!}
<div class="fondo_fotos">
	<input type="hidden" value="" name="placa" id="placa_fotografias"/>
	<div class="foto_carga" style="margin-left:4px">
		<div class="marco">
			<img src="../../img/izquierda.png" id="foto_1_carga" />	
		</div>
		@if($acceso == "W")
		<input type="file" class="filestyle" data-buttonName="btn-primary" data-buttonText="  Seleccionar imagen" data-size="sm" name="fil_img_1" id="fil_img_1"/>
		@endif
	</div>	

	<div class="foto_carga">
		<div class="marco">
			<img src="../../img/delante.png" id="foto_2_carga" />
		</div>
		@if($acceso == "W")
		<input type="file" class="filestyle" data-buttonName="btn-primary" data-buttonText="  Seleccionar imagen" data-size="sm" name="fil_img_2" id="fil_img_2"/>
		@endif
	</div>	

	<div class="foto_carga">
		<div class="marco">
			<img src="../../img/atras.png" id="foto_3_carga" />
		</div>
		@if($acceso == "W")
		<input type="file" class="filestyle" data-buttonName="btn-primary" data-buttonText="  Seleccionar imagen" data-size="sm" name="fil_img_3" id="fil_img_3"/>
		@endif
	</div>	

	<div class="foto_carga">
		<div class="marco">
			<img src="../../img/derecha.png" id="foto_4_carga" />
		</div>
		@if($acceso == "W")
		<input type="file" class="filestyle" data-buttonName="btn-primary" data-buttonText="  Seleccionar imagen" data-size="sm" name="fil_img_4" id="fil_img_4"/>
		@endif
	</div>	
</div>

<ul class="list-inline pull-right" style="margin-top:20px;">
    <li><button type="button" class="btn btn-default prev-step">Anterior</button></li>
    @if($acceso == "W")
    <li><button type="submit" class="btn btn-primary btn-info-full next-step" onclick="guardarFotografiasyFinalizar()">Guardar y finalizar</button></li>
    @endif
</ul>
{!!Form::close()!!}
    
