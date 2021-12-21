
<table border="1" cellpadding="0" cellspacing="0">
		<tr>
			<td class="negrita gris" colspan="2" style="width:50px;">
				<p style="margin:0px;">INCIDENCIA ORDEN DE TRABAJO</p>
			</td>
			<td colspan="3"> 
				<p style="text-align:left;margin:0px;">No. {{$orden}}</p>
			</td>
			<td colspan="3" style="border-left:0.1mm solid black">
				<p style="text-align:left;margin:0px;">No. </p>
			</td>
			<td colspan="3">
				<p style="text-align:left;margin:0px;">No. </p>
			</td>
		</tr>
		
		<tr class="gris">
			<td style="width:1px;">No</td>
			<td style="width:200px;">Riesgo de seguridad</td>
			<td style="width:20px;" rowspan="2">SI</td>
			<td style="width:20px;" rowspan="2">NO</td>
			<td style="width:120px;" rowspan="2">Medida Control</td>
			<td style="width:20px;" rowspan="2">SI</td>
			<td style="width:20px;" rowspan="2">NO</td>
			<td style="width:120px;" rowspan="2">Medida Control</td>
			<td style="width:20px;" rowspan="2">SI</td>
			<td style="width:20px;" rowspan="2">NO</td>
			<td style="width:120px;" rowspan="2">Medida Control</td>
		</tr>
		
		<tr class="gris">
			<td style="width:1px;">1</td>
			<td style="width:200px;">Caidas al mismo nivel</td>
		</tr>
		@for ($i = 0 ;  $i < count($user) ; $i++) 
		
		@if($user[$i]->id_pregunta == 127)
		<tr >
			<td style="width:1px;border-left:1px solid black;padding-left:0px;margin-left:0px;"><span style="display:block;position:realtive;left:-2px;">1.1</span></td>
			<td style="width:200px;text-align:left">Caída por deficiencias en el suelo.	</td>
			
			@if($user[$i]->res == "SI")
			<td style="width:20px;border:1px solid black;">X</td>
			<td style="width:20px;border:1px solid black;"></td>
			@else
			<td style="width:20px;border:1px solid black;"></td>
			<td style="width:20px;border:1px solid black;">X</td>
			@endif
			
			<td style="width:120px;"> <span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;border:1px solid black;"></td>
			<td style="width:20px;border:1px solid black;"></td>
			<td style="width:120px;border:1px solid black;"></td>
			<td style="width:20px;border:1px solid black;"></td>
			<td style="width:20px;border:1px solid black;"></td>
			<td style="width:120px;border:1px solid black;"></td>
		</tr>
		@endif

		@if($user[$i]->id_pregunta == 129)
		<tr >
			<td style="width:1px;">1.2</td>
			<td style="width:200px;text-align:left">Caída por pisar o tropezar con objetos en el suelo.	</td>
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;border:1px solid black;">X</td>
				<td style="width:20px;border:1px solid black;"></td>
				@else
				<td style="width:20px;border:1px solid black;"></td>
				<td style="width:20px;border:1px solid black;">X</td>
				@endif

			<td style="width:120px;"> <span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif

		@if($user[$i]->id_pregunta == 131)
		<tr >
			<td style="width:1px;">1.3</td>
			<td style="width:200px;text-align:left">Caída por existencia de vertidos o líquidos.	</td>
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;border:1px solid black;">X</td>
				<td style="width:20px;border:1px solid black;"></td>
				@else
				<td style="width:20px;border:1px solid black;"></td>
				<td style="width:20px;border:1px solid black;">X</td>
				@endif

			<td style="width:120px;"> <span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif

		@if($user[$i]->id_pregunta == 133)
		<tr >
			<td style="width:1px;">1.4</td>
			<td style="width:200px;text-align:left">Caída por superficies en mal estado por condiciones atmosféricas (heladas, nieve, agua, etc.)														
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"> <span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif

		@if($user[$i]->id_pregunta == 135)
		<tr >
			<td style="width:1px;">1.5</td>
			<td style="width:200px;text-align:left">Resbalones / tropezones por malos apoyos del pie.		
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"> <span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif


		@if($user[$i]->id_pregunta == 138)
		<tr class="gris">
			<td style="width:1px;">2</td>
			<td style="width:200px;">Caida de personas a distinto nivel</td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
		</tr>
		<tr >
			<td style="width:1px;">2.1</td>
			<td style="width:200px;text-align:left">Caída por: Desniveles / huecos / zanjas / taludes		</td>
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"> <span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif

		@if($user[$i]->id_pregunta == 140)
		<tr >
			<td style="width:1px;">2.2</td>
			<td style="width:200px;text-align:left">Caída desde escaleras portátiles/Fijas/Andamios		</td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"> <span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif

		@if($user[$i]->id_pregunta == 142)
		<tr >
			<td style="width:1px;">2.3</td>
			<td style="width:200px;text-align:left">Caída desde tejados y muros.</td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"> <span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif

		@if($user[$i]->id_pregunta == 144)
		<tr >
			<td style="width:1px;">2.4</td>
			<td style="width:200px;text-align:left">Caída desde Postes/Torres/Porticos</td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"> <span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif

		@if($user[$i]->id_pregunta == 146)
		<tr >
			<td style="width:1px;">2.5</td>
			<td style="width:200px;text-align:left">Caída desde árboles.</td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"> <span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif

		@if($user[$i]->id_pregunta == 148)
		<tr >
			<td style="width:1px;">2.6</td>
			<td style="width:200px;text-align:left">Caída a un medio acuoso: ríos, lagos, canales, etc.	</td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"> <span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif

		@if($user[$i]->id_pregunta == 151)
			<tr class="gris">
			<td style="width:1px;">3</td>
			<td style="width:200px;">Caída de objetos.														
</td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
		</tr>
		<tr >
			<td style="width:1px;">3.1</td>
			<td style="width:200px;text-align:left">Caída por manipulación de objetos y herramientas.	</td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"> <span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif

		@if($user[$i]->id_pregunta == 153)
		<tr >
			<td style="width:1px;">3.2</td>
			<td style="width:200px;text-align:left">Caída de elementos apilados (almacén).</td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"> <span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif

		@if($user[$i]->id_pregunta == 156)
		
			<tr class="gris">
			<td style="width:1px;">4</td>
			<td style="width:200px;">Desprendimientos, desplomes y derrumbes</td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
		</tr>
		<tr >
			<td style="width:1px;">4.1</td>
			<td style="width:200px;text-align:left">Desprendimiento de elementos de montaje fijos, desplome de muros, hundimiento de zanjas o galerias</td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"> <span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif

		@if($user[$i]->id_pregunta == 159)
		
			<tr class="gris">
			<td style="width:1px;">5</td>
			<td style="width:200px;">Choques y golpes</td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
		</tr>
		<tr >
			<td style="width:1px;">5.1</td>
			<td style="width:200px;text-align:left">Choque contra: partes salientes de: máquinas / instalaciones /Objetos / materiales / pasos estrechos.</td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"> <span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif

		@if($user[$i]->id_pregunta == 161)
		<tr >
			<td style="width:1px;">5.1</td>
			<td style="width:200px;text-align:left">Golpes por: vigas o conductos a baja altura, partes moviles en movimiento, herramientas manuales, electric.</td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"> <span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif

		@if($user[$i]->id_pregunta == 164)
		<tr class="gris">
			<td style="width:1px;">6</td>
			<td style="width:200px;">Maquinaria automotriz y vehículos (dentro del centro de trabajo).</td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
		</tr>
		<tr >
			<td style="width:1px;">6.1</td>
			<td style="width:200px;text-align:left">Choques entre vehículos, golpes a peatones, a objetos o estructuras, vuelco del vehiculo. </td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"> <span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif

		@if($user[$i]->id_pregunta == 167)
		<tr class="gris">
			<td style="width:1px;">7</td>
			<td style="width:200px;">Atrapamiento. </td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
		</tr>
		<tr >
			<td style="width:1px;">7.1</td>
			<td style="width:200px;text-align:left">Atrapamiento por: herramientas manuales, electricas, partes en movimiento, maquinas, objetos</td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"> <span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif


		@if($user[$i]->id_pregunta == 170)
	<tr class="gris">
			<td style="width:1px;">8</td>
			<td style="width:200px;">Cortes</td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
		</tr>
		<tr >
			<td style="width:1px;">8.1</td>
			<td style="width:200px;text-align:left">Cortes por: herramientas manuales, electricas, maquinas, objetos o superficies punzantes</td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"> <span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif

		@if($user[$i]->id_pregunta == 173)
		<tr class="gris">
			<td style="width:1px;">9</td>
			<td style="width:200px;">Proyecciones.</td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
		</tr>
		<tr >
			<td style="width:1px;">9.1</td>
			<td style="width:200px;text-align:left">Impacto de fragmentos o partículas sólidas, liquidas, vapor, trasportadas por el viento </td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"> <span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif

		@if($user[$i]->id_pregunta == 176)
		<tr class="gris">
			<td style="width:1px;">10</td>
			<td style="width:200px;">Contactos térmicos. </td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
		</tr>
		<tr >
			<td style="width:1px;">10.1</td>
			<td style="width:200px;text-align:left">Contacto con: fluidos; sustancias; superficies o proyecciones calientes / frías o que cambian rapidamente de temperatura  </td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"> <span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif


		@if($user[$i]->id_pregunta == 179)
		<tr class="gris">
			<td style="width:1px;">11</td>
			<td style="width:200px;">Electricos</td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
		</tr>
		<tr >
			<td style="width:1px;">11.1</td>
			<td style="width:200px;text-align:left">Contactos directos AT/MT/BT</td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"> <span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif

		@if($user[$i]->id_pregunta == 181)
		<tr >
			<td style="width:1px;">11.2</td>
			<td style="width:200px;text-align:left">Contactos indirectos AT/MT/BT</td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"> <span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif


		@if($user[$i]->id_pregunta == 183)
		<tr >
			<td style="width:1px;">11.3</td>
			<td style="width:200px;text-align:left">Proximidad con redes, conexiones y equipos energizados de AT > 57.5 a 500 Kv </td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"> <span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif

		@if($user[$i]->id_pregunta == 185)
		<tr >
			<td style="width:1px;">11.4</td>
			<td style="width:200px;text-align:left">Proximidad con redes, conexiones y equipos energizados deMT >1.0 < 57. 5 Kv</td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"> <span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif

		@if($user[$i]->id_pregunta == 187)
		<tr >
			<td style="width:1px;">11.5</td>
			<td style="width:200px;text-align:left">Proximidad con redes, conexiones y equipos energizados de BT > 25 v < 1.0 Kv</td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"> <span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif

		@if($user[$i]->id_pregunta == 189)
		<tr >
			<td style="width:1px;">11.6</td>
			<td style="width:200px;text-align:left">Descargas eléctricas (inductiva / capacitiva) teniendo en cuenta las de la sobretensión tipo rayo. </td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"> <span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif

		@if($user[$i]->id_pregunta == 191)
		<tr >
						<td style="width:1px;">11.7</td>
			<td style="width:200px;text-align:left">Identifico en SDE y terreno los cruces con otros  circuito y líneas de trasmisión.</td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"> <span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif

		@if($user[$i]->id_pregunta == 193)
		<tr >
			<td style="width:1px;">11.8</td>
			<td style="width:200px;text-align:left">Perforación de conductores de redes subterráneas de MT/BT.</td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"> <span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif

		@if($user[$i]->id_pregunta == 196)
		<tr class="gris">
			<td style="width:1px;">12</td>
			<td style="width:200px;">Explosiones</td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
		</tr>
		<tr >
			<td style="width:1px;">12.1</td>
			<td style="width:200px;text-align:left">Exposición a: atmósferas explosivas, material explosivo.</td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"> <span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif

		@if($user[$i]->id_pregunta == 199)
				<tr class="gris">
			<td style="width:1px;">13</td>
			<td style="width:200px;">Incendios														
 </td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
		</tr>
		<tr >
			<td style="width:1px;">13.1</td>
			<td style="width:200px;text-align:left">Existe acumulación de productos, material combustible, atmósfera inflamable, proyeccion de chispas,sobrecarga de la red electrica, llama abierta.	</td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"> <span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif

		@if($user[$i]->id_pregunta == 202)
		<tr class="gris">
			<td style="width:1px;">14</td>
			<td style="width:200px;">Confinamiento														
	</td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
		</tr>
		<tr >
			<td style="width:1px;">14.1</td>
			<td style="width:200px;text-align:left">Va a ingresar a recinto cerrados con atmósferas: bajas en oxígeno, inflamable, tóxica.														</td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"> <span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif

		@if($user[$i]->id_pregunta == 205)
		<tr class="gris">
			<td style="width:1px;">15</td>
			<td style="width:200px;">Acceso vehicular al lugar de trabajo														
	</td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
		</tr>
		<tr >
			<td style="width:1px;">15.1</td>
			<td style="width:200px;text-align:left">Acceso vehicular al lugar de trabajo</td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"> <span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif
		

		@if($user[$i]->id_pregunta == 207)
		<tr >
			<td style="width:1px;">15.2</td>
			<td style="width:200px;text-align:left">Trabajo en la via Publica </td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"> <span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif

		@if($user[$i]->id_pregunta == 210)
		<tr class="gris">
			<td style="width:1px;">16</td>
			<td style="width:200px;">Agresión de seres vivos.														
 </td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
			<td style="width:20px;" >SI</td>
			<td style="width:20px;" >NO</td>
			<td style="width:120px;" >Medida Control</td>
		</tr>
		<tr >
			<td style="width:1px;">16.1</td>
			<td style="width:200px;text-align:left">Agresiones de animales (Ej. insectos, perros, culebras, otros)		</td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"> <span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif

		@if($user[$i]->id_pregunta == 212)
		<tr >
			<td style="width:1px;">16.2</td>
			<td style="width:200px;text-align:left">Agresion de personas (Ej. Clientes, Robos, atracos, orden publico)	</td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"> <span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif	


		@endfor

</table>