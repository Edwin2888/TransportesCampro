
<table border="1" cellpadding="0" cellspacing="0" style="margin-top:10px;">
		
		@for ($i = 0 ;  $i < count($user) ; $i++) 


		@if($user[$i]->id_pregunta == 215)
		<tr class="gris">
			<td style="width:1px;">.</td>
			<td style="width:200px;">Riegos Higiene Industrial (17 al 24)													
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
			<td style="width:1px;">17</td>
			<td style="width:200px;text-align:left">Sobre Carga Termica: Exposición prolongada al calor / frio; Cambios bruscos de temperatura </td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"><span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif

		@if($user[$i]->id_pregunta == 217)
		<tr >
			<td style="width:1px;">18</td>
			<td style="width:200px;text-align:left">Ruido: Exposición a ruido por: maquinaria, herramientas, vehiculos.</td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"><span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif

		@if($user[$i]->id_pregunta == 219)
		<tr >
			<td style="width:1px;">19</td>
			<td style="width:200px;text-align:left">Vibraciones: Exposición a vibraciones (martillos neumáticos, vibradores de hormigón, etc.).</td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"><span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif


		@if($user[$i]->id_pregunta == 221)
		<tr >
			<td style="width:1px;">20</td>
			<td style="width:200px;text-align:left">Radiaciones Ionizantes: Exposición a: rayos X, rayos gamma, etc.); contacto con productos radiactivos.</td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"><span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif


		@if($user[$i]->id_pregunta == 223)
		<tr >
			<td style="width:1px;">21</td>
			<td style="width:200px;text-align:left">Radiaciones No Ionizante: Exposicion a: radiación infraroja. ultravioleta (soldaduras, etc.); microondas; radiacion solar)</td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"><span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif

		@if($user[$i]->id_pregunta == 225)
		<tr >
			<td style="width:1px;">22.1</td>
			<td style="width:200px;text-align:left">Iluminación: Iluminación ambiental insuficiente.</td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"><span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif

		@if($user[$i]->id_pregunta == 227)
		<tr >
			<td style="width:1px;">22.2</td>
			<td style="width:200px;text-align:left">Iluminación: Deslumbramientos y reflejos.</td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"><span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif

		@if($user[$i]->id_pregunta == 229)
		<tr >
			<td style="width:1px;">23</td>
			<td style="width:200px;text-align:left">Agentes Quimicos: Exposición al contacto, inhalacion, ingestion de suatancias quimicas: liquidas solidas, gaseosas (Ej. PCB´s, etc.)</td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"><span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif


		@if($user[$i]->id_pregunta == 231)
		<tr >
			<td style="width:1px;">24</td>
			<td style="width:200px;text-align:left">Agentes Biologicos: Exposición a:(bacterias,hongos), calidad del aire, agua, plantas (Ortiga, Pringamoza, Manzanillo o Pedro Hernández)</td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"><span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif


		@if($user[$i]->id_pregunta == 234)
		<tr class="gris">
			<td style="width:1px;">.</td>
			<td style="width:200px;">Riesgos Ergonómicos (25 al 28)													
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
			<td style="width:1px;">25.1</td>
			<td style="width:200px;text-align:left">Carga física y sobreesfuerzos: Esfuerzos al empujar, levantar, sostener o manipular herramientas, objetos y cargas.</td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"><span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif


		@if($user[$i]->id_pregunta == 236)
		<tr >
			<td style="width:1px;">25.2</td>
			<td style="width:200px;text-align:left">Carga física y sobreesfuerzos: Movimientos repetitivos</td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"><span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif

		@if($user[$i]->id_pregunta == 238)
		<tr >
			<td style="width:1px;">26</td>
			<td style="width:200px;text-align:left">Psicosociales: Trabajadores afectados psicologica</td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"><span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif

		@if($user[$i]->id_pregunta == 240)
		<tr >
			<td style="width:1px;">27</td>
			<td style="width:200px;text-align:left">Condiciones ambientales del puesto de trabajo: Expuesto (lluvia, polvo en suspensión etc.)</td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"><span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif

		@if($user[$i]->id_pregunta == 242)
		<tr >
			<td style="width:1px;">28.1</td>
			<td style="width:200px;text-align:left">Configuración del puesto de trabajo: Estructuras fuera de norma </td>	
			
				@if($user[$i]->res == "SI")
				<td style="width:20px;">X</td>
				<td style="width:20px;"></td>
				@else
				<td style="width:20px;"></td>
				<td style="width:20px;">X</td>
				@endif

			<td style="width:120px;"><span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>
		@endif

		@if($user[$i]->id_pregunta == 244)
		<tr>
			<td style="width:1px;">28.2</td>
			<td style="width:200px;text-align:left">Configuración del puesto de trabajo: Instalaciones locativas inseguras</td>	
			
				@if($user[$i]->res == "SI")
					<td style="width:20px;">X</td>
					<td style="width:20px;"></td>
				@else
					<td style="width:20px;"></td>
					<td style="width:20px;">X</td>
				@endif
			<td style="width:120px;"><span style="font-size:7px;">{{utf8_decode($user[$i + 1]->res)}}</span></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<td style="width:120px;"></td>
		</tr>

		<tr style="height:5px;">
			<td style="height:5px;" colspan="11"></td>
		</tr>
		</table>

		<table border="1" cellpadding="0" cellspacing="0">
		<tr class="gris">
			<td style="width:250px;" rowspan="2" >3. VERIFICACIÓN DEL CUMPLIMIENTO DE LAS 5 REGLAS DE ORO (TRABAJO EN LÍNEAS DESENERGIZADAS)</td>
			<td style="width:20px;" rowspan="2"	> Aplica</td>
			<td style="width:20px;">SI</td>
			<td style="width:20px;">NO</td>
			<td style="width:320px;" rowspan="2">"4. REVISIÓN MATERIALES, EQUIPOS Y HERRAMIENTA(Verifique que los materiales, herramientas y equipos sean los necesarios para la ejeccuion de la actividad, estan en buen estado y funcionando correctamente)"</td>
		</tr>
		
		<tr class="gris">
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
		</tr>
	</table>

		<table border="1" cellpadding="0" cellspacing="0">
			<tr class="gris">
				<td style="width:203px;">REGLAS DE ORO</td>
				<td style="width:20px;">SI</td>
				<td style="width:20px;">NO</td>
				<td style="width:117px;">Observaciones</td>
				<td style="width:183px;">MATERIALES, EQUIPOS Y HERRAMIENTA</td>
				<td style="width:20px;">SI</td>
				<td style="width:20px;">NO</td>
				<td style="width:122px;">Medida de control</td>
			</tr>
		</table>

		@endif






	
		
		@if($user[$i]->id_pregunta == 248)
		<table border="1" cellpadding="0" cellspacing="0">
		<tr >
			<td style="width:10px;">1</td>
			<td style="width:193px;">El Corte efectivo de todas las fuentes de tensión se puede realizar de forma 100% segura (indique los seccionamientos)</td>
				@if($user[$i]->res == "SI")
					<td style="width:18px;">X</td>
					<td style="width:18px;"></td>
				@else
					<td style="width:18px;"></td>
					<td style="width:18px;">X</td>
				@endif
			<td style="width:122px;">{{$user[$i + 1]->res}}</td>

			<td style="width:10px;">1</td>
			<td style="width:173px;">Los materiales son los indicados y estan en buen estado para su utilización</td>
			@if($user[$i + 11]->res == "SI")
					<td style="width:18px;">X</td>
					<td style="width:18px;"></td>
				@else
					<td style="width:18px;"></td>
					<td style="width:18px;">X</td>
				@endif
			<td style="width:122px;">{{$user[$i + 11]->res}}</td>
		</tr>

		@endif

		@if($user[$i]->id_pregunta == 250)
		<tr >
			<td style="width:10px;">2</td>
			<td style="width:193px;">EL Bloqueo de los aparatos de corte se puede realizar de forma 100% segura  (indique los seccionamientos)</td>
				@if($user[$i]->res == "SI")
					<td style="width:18px;">X</td>
					<td style="width:18px;"></td>
				@else
					<td style="width:18px;"></td>
					<td style="width:18px;">X</td>
				@endif
			<td style="width:122px;">{{$user[$i + 1]->res}}</td>

			<td style="width:10px;">2</td>
			<td style="width:173px;">Las herramientas son las indicadas, estan en buen estado y funcionan correctamente. </td>
			@if($user[$i + 11]->res == "SI")
					<td style="width:18px;">X</td>
					<td style="width:18px;"></td>
				@else
					<td style="width:18px;"></td>
					<td style="width:18px;">X</td>
				@endif
			<td style="width:122px;">{{$user[$i + 11]->res}}</td>
		</tr>

		@endif


		@if($user[$i]->id_pregunta == 252)
		<tr >
			<td style="width:10px;">3</td>
			<td style="width:193px;">La Comprobación de ausencia de tensión se puede realizar de forma 100% segura (indique los seccionamientos / Lugar)</td>
				@if($user[$i]->res == "SI")
					<td style="width:18px;">X</td>
					<td style="width:18px;"></td>
				@else
					<td style="width:18px;"></td>
					<td style="width:18px;">X</td>
				@endif
			<td style="width:122px;">{{$user[$i + 1]->res}}</td>

			<td style="width:10px;">3</td>
			<td style="width:173px;">Las equipos son las indicados, estan en buen estado y funcionan correctamente. </td>
			@if($user[$i + 11]->res == "SI")
					<td style="width:18px;">X</td>
					<td style="width:18px;"></td>
				@else
					<td style="width:18px;"></td>
					<td style="width:18px;">X</td>
				@endif
			<td style="width:122px;">{{$user[$i + 11]->res}}</td>
		</tr>

		@endif

		@if($user[$i]->id_pregunta == 254)
		<tr >
			<td style="width:10px;">4</td>
			<td style="width:193px;">La Puesta a tierra y en cortocircuito de la red o equipo electrico se puede realizar de forma 100% segura (indique los puntos fisicos)</td>
				@if($user[$i]->res == "SI")
					<td style="width:18px;">X</td>
					<td style="width:18px;"></td>
				@else
					<td style="width:18px;"></td>
					<td style="width:18px;">X</td>
				@endif
			<td style="width:122px;">{{$user[$i + 1]->res}}</td>
			<td style="width:10px;" rowspan="2">4</td>
			<td style="width:173px;" rowspan="2">Los equipos/herramientas de trabajo en línea energizada están en buen estado, normalizados y tienen pruebas dieléctricas vigentes </td>
			@if($user[$i + 11]->res == "SI")
					<td style="width:18px;" rowspan="2">X</td>
					<td style="width:18px;" rowspan="2"></td>
				@else
					<td style="width:18px;" rowspan="2"></td>
					<td style="width:18px;" rowspan="2">X</td>
				@endif
			<td style="width:122px;" rowspan="2">{{$user[$i + 11]->res}}</td>
		</tr>
		@endif
	
		@if($user[$i]->id_pregunta == 256)
		<tr style="height:5px;">
			<td style="width:10px;">5</td>
			<td style="width:193px;">La Señalización de zona de trabajo se puede realizar 100% segura</td>

			@if($user[$i]->res == "SI")
					<td style="width:18px;" rowspan="2">X</td>
					<td style="width:18px;" rowspan="2"></td>
				@else
					<td style="width:18px;" rowspan="2"></td>
					<td style="width:18px;" rowspan="2">X</td>
				@endif

			
			<td style="width:122px;">{{$user[$i + 1]->res}}</td>
		</tr>

		<tr style="height:10px;">
			<td style="height:5px;text-align:center;" colspan="10">5. Observaciones</td>
		</tr>
	</table>
	@endif

	@if($user[$i]->id_pregunta == 282)

	<table border="1" cellpadding="0" cellspacing="0">
		<tr style="height:60px;">
			<td style="height:60px;width:237px;" >Orden No {{$orden}}</td>
			<td style="height:60px;width:237px;" >Orden No</td>
			<td style="height:60px;width:238px;" >Orden No</td>
		</tr>
	</table>

	<table border="1" cellpadding="0" cellspacing="0">
		<tr class="gris">
			<td style="width:233px;">6. VERIFICACIÓN DE ESTADO ELEMENTOS PARA TRABAJO EN ALTURAS</td>
			<td style="width:20px;">Aplica</td>
			<td style="width:10px;">SI</td>
			@if($user[$i]->res == "SI")
			<td style="width:8px;background:white;">X</td>
			<td style="width:10px;">NO</td>
			<td style="width:8px;background:white;"></td>
			@else
			<td style="width:8px;background:white;"></td>
			<td style="width:10px;">NO</td>
			<td style="width:8px;background:white;">X</td>
			@endif
			<td style="width:106px;">Altura Maxima de trabajo (m)</td>
			<td style="width:60px;">{{$user[$i + 1]->res}}</td>
			<td style="width:210px;">7. CONCLUSIONES DE LA INSPECCION PREOPRECIONAL</td>
		</tr>
	</table>

	@endif

	@if($user[$i]->id_pregunta == 285)	
	<table border="1" cellpadding="0" cellspacing="0">
		<tr class="gris">
			<td style="width:125px;" rowspan="2">EQUIPO DE TRABAJO EN ALTURA</td>
			<td style="width:200px;" rowspan="2">VERIFIQUE Y REGISTRE EL ESTADO DE LOS ELEMENTOS</td>
			<td style="width:90px;">TRABAJADOR 1</td>
			<td style="width:90px;">TRABAJADOR 2</td>
			<td style="width:125px;background:white" rowspan="2">De acuerdo con la identificacion de riesgos y los controles implementados, los trabajos se pueden realiazar de forma 100% seguros</td>
			<td style="width:15px;">SI</td>
			<td style="width:15px;">NO</td>
		</tr>

		<tr class="gris" style="height:21px;">
			<td style="width:90px;height:21px;">
				<table  cellpadding="0" cellspacing="0" style="height:21px;">
					<td style="widht:40px;border-right:1px solid black;height:21px;text-align:center;"><b style="padding-left:1px;">BUENO</b></td>
					<td style="widht:40px;border-right:1px solid black;height:21px;text-align:center;"><b style="padding-left:1px;">MALO</b></td>
					<td style="widht:40px;height:21px;text-align:center;"><b>NA</b></td>
				</table>
			</td>
			<td style="width:90px;height:21px;">
				<table  cellpadding="0" cellspacing="0" style="height:21px;">
					<td style="widht:40px;border-right:1px solid black;height:21px;text-align:center;"><b >BUENO</b></td>
					<td style="widht:40px;border-right:1px solid black;height:21px;text-align:center;"><b  style="padding-left:1px;">MALO</b></td>
					<td style="widht:40px;height:21px;text-align:center;"><b>NA</b></td>
				</table>
			</td>

			@if($user[$i]->res == "SI")
				<td style="width:15px;background:white">X</td>
				<td style="width:15px;background:white"></td>
			@else
				<td style="width:15px;background:white"></td>
				<td style="width:15px;background:white">X</td>
			@endif
		</tr>
	</table>

	<table border="1" cellpadding="0" cellspacing="0">
		<tr>
			<td style="width:129px;">Arnés de cuerpo completo</td>
			<td style="width:204px;">Correas, Hebillas, Anillos para conexión, Costuras, Protecciones dieléctricas</td>
			<td style="width:30px;"></td>
			<td style="width:26px;"></td>
			<td style="width:33px;"></td>
			<td style="width:30px;"></td>
			<td style="width:26px;"></td>
			<td style="width:36px;"></td>
			<td style="width:160px;" rowspan="4"><b>En caso de NO poder realizar los trabajos de forma 100% seguros, aplique la Politica Stop Work </b>y suspenda los mismos, tome las medidas de control correspondientes para </td>
		</tr>

		<tr>
			<td style="width:129px;">Eslinga de posicionamiento</td>
			<td style="width:204px;">Cuerda / cinta, trenzado en las puntas, guardacabo plástico, ganchos de doble seguridad</td>
			<td style="width:30px;"></td>
			<td style="width:26px;"></td>
			<td style="width:33px;"></td>
			<td style="width:30px;"></td>
			<td style="width:26px;"></td>
			<td style="width:36px;"></td>
		</tr>

		<tr>
			<td style="width:129px;">Eslinga con absorbedor de energía</td>
			<td style="width:204px;">Cuerda, guardacabo plástico, ganchos de doble seguridad, y guarda cabos plásticos no remachados</td>
			<td style="width:30px;"></td>
			<td style="width:26px;"></td>
			<td style="width:33px;"></td>
			<td style="width:30px;"></td>
			<td style="width:26px;"></td>
			<td style="width:36px;"></td>
		</tr>

		<tr>
			<td style="width:129px;">Anclaje temporal - tie off  </td>
			<td style="width:204px;">Anillo para conexión, cinta, costuras, hebilla.</td>
			<td style="width:30px;"></td>
			<td style="width:26px;"></td>
			<td style="width:33px;"></td>
			<td style="width:30px;"></td>
			<td style="width:26px;"></td>
			<td style="width:36px;"></td>
		</tr>
	</table>

	@endif


	
	

	@if($user[$i]->id_pregunta == 286)	
	<table border="1" cellpadding="0" cellspacing="0" style="position:relative;top:-2px;">
		<tr>
			<td style="width:134px;">Mosquetón, (seguro de dos pasos)</td>
			<td style="width:209.5px;padding-right:1px;">Debe tener mínimo 5000 libras de resistencia, (certificado)</td>
			<td style="width:31px;"></td>
			<td style="width:25px;"></td>
			<td style="width:34.5px;"></td>
			<td style="width:30px;"></td>
			<td style="width:27px;"></td>
			<td style="width:37px;"></td>
			<td style="width:119px;" rowspan="3"><b style="font-size:8px">Aplicó la Política Stop Work </b></td>
			<td style="width:22px;" class="gris" >SI</td>
			<td style="width:22px;" class="gris" >NO</td>
		</tr>
   


		<tr>
			<td style="width:134px;">Pretal</td>
			<td style="width:209.5px;">Cuerda, trenzado en las puntas, banda de soporte</td>
			<td style="width:31px;"></td>
			<td style="width:27px;"></td>
			<td style="width:34.5px;"></td>
			<td style="width:31px;"></td>
			<td style="width:27px;"></td>
			<td style="width:37px;"></td>
			@if($user[$i]->res == "SI")
				<td style="width:22px;" rowspan="2" >X</td>
				<td style="width:22px;" rowspan="2" ></td>
			@else
				<td style="width:22px;" rowspan="2" ></td>
				<td style="width:22px;" rowspan="2" >X</td>
			@endif
			
		</tr>
   @endif

 @endfor
		<tr>
			<td style="width:134px;">Escalera</td>
			<td style="width:209.5px;">Parales, peldaños, poleas, cuerda de extensión, zapatas auto niveladoras, seguros, cuerda amarre.</td>
			<td style="width:31px;"></td>
			<td style="width:27px;"></td>
			<td style="width:34.5px;"></td>
			<td style="width:31px;"></td>
			<td style="width:27px;"></td>
			<td style="width:37px;"></td>
		</tr>
	</table>

	<table border="1" cellpadding="0" cellspacing="0" style="position:relative;top:-5px;">
		<tr>
			<td style="width:130px;">Andamios multidireccional</td>
			<td style="width:203px;">Se solicitan procedimiento de  armado y andamios certificados, (zapatas, accesorios internos, plataformas)</td>
			<td style="width:30px;"></td>
			<td style="width:26px;"></td>
			<td style="width:33px;"></td>
			<td style="width:30px;"></td>
			<td style="width:26px;"></td>
			<td style="width:36px;"></td>
			<td style="width:160px;"><b>Indique las condiciones y comportamientos de riesgos que al inicio o en el trancurso de la ejecución de los trabajos, llevaron a la palicacion de la Politica Stop Work</b></td>
		</tr>

		<tr>
			<td style="width:130px;">Sistema para ascenso de cuerda, freno de cuerda</td>
			<td style="width:203px;">Leva, resorte, mosquetón, (gibbs) Ascendedor</td>
			<td style="width:30px;"></td>
			<td style="width:26px;"></td>
			<td style="width:33px;"></td>
			<td style="width:30px;"></td>
			<td style="width:26px;"></td>
			<td style="width:36px;"></td>
			<td style="width:160px;"></td>
		</tr>


		<tr>
			<td style="width:130px;">Línea de vida / rescate </td>
			<td style="width:203px;">Cuerda de 30 metros, guardacabo plástico, ganchos de doble seguridad, soporte superior</td>
			<td style="width:30px;"></td>
			<td style="width:26px;"></td>
			<td style="width:33px;"></td>
			<td style="width:30px;"></td>
			<td style="width:26px;"></td>
			<td style="width:36px;"></td>
			<td style="width:160px;"></td>
		</tr>

		<tr>
			<td style="width:130px;">Lineas de amarre escalera; Vientos (andamios)</td>
			<td style="width:203px;">Cuerda utilizada para amarra la escalera al poste y para vientos en los andamios.</td>
			<td style="width:30px;"></td>
			<td style="width:26px;"></td>
			<td style="width:33px;"></td>
			<td style="width:30px;"></td>
			<td style="width:26px;"></td>
			<td style="width:36px;"></td>
						<td style="width:160px;"></td>
		</tr>

		
		<tr>
			<td style="width:130px;">Equipo de rescate </td>
			<td style="width:203px;">Poleas, mosquetón, tie off, descendedor automático antipático, sistema de descenso controlado.</td>
			<td style="width:30px;"></td>
			<td style="width:26px;"></td>
			<td style="width:33px;"></td>
			<td style="width:30px;"></td>
			<td style="width:26px;"></td>
			<td style="width:36px;"></td>
						<td style="width:160px;"></td>
		</tr>

		<tr>
			<td style="width:130px;">Puntos de anclaje fijos</td>
			<td style="width:203px;">Verificación de los puntos de anclajes fijos utilizados por cada trabajador. </td>
			<td style="width:30px;"></td>
			<td style="width:26px;"></td>
			<td style="width:33px;"></td>
			<td style="width:30px;"></td>
			<td style="width:26px;"></td>
			<td style="width:36px;"></td>
						<td style="width:160px;"></td>
		</tr>


		<tr style="height:5px;">
			<td style="height:5px;text-align:center;" colspan="9"></td>
		</tr>

		<tr style="height:10px;">
			<td style="height:10px;text-align:center;" colspan="9" class="gris"><b>8. Firmas</b></td>
		</tr>
	</table>

	<table border="1" cellpadding="0" cellspacing="0" style="position:relative;top:-6px;">
		<tr>
			<td style="width:400px;border-bottom:0px;" colspan="3">RESPONSABLES EJECUCIÓN TRABAJOS</td>
		</tr>
		<tr>
			<td style="width:237px;">
				<p style="margin:0px;margin-left:5px;padding:0px;margin-top:10px;"><b>_____________________________________</b></p>
				<p style="margin:0px;margin-left:5px;padding:0px;"><b>Firma</b></p>
				<p style="margin:0px;margin-left:5px;padding:0px;"><b>Nombre</b></p>
				<p style="margin:0px;margin-left:5px;padding:0px;"><b>CC</b></p>
			</td>
			<td style="width:237px;">
				<p style="margin:0px;margin-left:5px;padding:0px;margin-top:10px;"><b>_____________________________________</b></p>
				<p style="margin:0px;margin-left:5px;padding:0px;"><b>Firma</b></p>
				<p style="margin:0px;margin-left:5px;padding:0px;"><b>Nombre</b></p>
				<p style="margin:0px;margin-left:5px;padding:0px;"><b>CC</b></p>
			</td>
			<td style="width:239px;">
				<p style="margin:0px;margin-left:5px;padding:0px;margin-top:10px;"><b>_____________________________________</b></p>
				<p style="margin:0px;margin-left:5px;padding:0px;"><b>Firma</b></p>
				<p style="margin:0px;margin-left:5px;padding:0px;"><b>Nombre</b></p>
				<p style="margin:0px;margin-left:5px;padding:0px;"><b>CC</b></p>

			</td>
		</tr>
	</table>