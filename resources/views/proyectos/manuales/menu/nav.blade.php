<div class="mp-pusher" id="mp-pusher">

				<!-- mp-menu -->
				<nav id="mp-menu" class="mp-menu">
					<div class="mp-level">
						<h2 class="icon icon-world">MANUALES POR PROYECTO</h2>
						<ul>
							<li><a class="icon icon-photo" href="../../manuales/add" style="    background: rgb(243,132,25);">AGREGAR MANUAL</a></li>

							@foreach($proyectosSeleccionado as $key => $val)
							<!-- Agregamos cada proyecto seleccionado-->
								<li class="icon icon-arrow-left">
									<a class="icon icon-display" href="#">
										@foreach($proyecto as $key => $valor)
							                @if($val->id_proyecto == $valor->prefijo_db)
							                    {{$valor->proyecto}}
							                @endif
							                @if($val->id_proyecto == "T01")
							                    RECURSOS HUMANOS
							                    <?php  break;?>
							                @endif
							                @if($val->id_proyecto == "T02")
							                    SUPERVISIÓN Y SST
							                    <?php  break;?>
							                @endif
							                @if($val->id_proyecto == "T03")
							                    TRANSPORTES
							                    <?php  break;?>
							                @endif
							                @if($val->id_proyecto == "T04")
							                    HERRAMIENTAS
							                    <?php  break;?>
							                @endif
							            @endforeach 
									</a>
									<div class="mp-level">
										<h2 class="icon icon-display">
											@foreach($proyecto as $key => $valor)
								                @if($val->id_proyecto == $valor->prefijo_db)
								                    {{$valor->proyecto}}
								                @endif
								            @endforeach 
										</h2>
										<a class="mp-back" href="#" onclick="abrirManual(1)">Atrás</a>
										
										<ul>
											<?php  $cadenaW = "";$cadenaM = ""; ?>
											@foreach($proyectosManual as $key => $valor)
								                @if($val->id_proyecto == $valor->id_proyecto)
								                	@if($valor->tipo == 1)
								                		<?php  $cadenaW .= "<li class='icon icon-arrow-left'>
														<a class='icon icon-phone' href='#' onclick='abrirManual(this)' data-titulo='$valor->titulo'
														data-descripcion='$valor->descripcion' data-embebido='$valor->embebido' data-version='$valor->version'>$valor->titulo V-$valor->version</a>
														</li>"; ?>
								                	@else
								                		<?php  $cadenaM .= "<li class='icon icon-arrow-left'>
														<a class='icon icon-phone' href='#' onclick='abrirManual(this)' data-titulo='$valor->titulo'
														data-descripcion='$valor->descripcion' data-embebido='$valor->embebido' data-version='$valor->version'>$valor->titulo V-$valor->version</a>
														</li>"; ?>
								                	@endif
								                    
								                @endif
								            @endforeach 	
								            @if($cadenaW != "")		
								            <li style="    padding: 5px;    color: white;    background: #F38419;">Aplicaciones web</li>
								            <?php echo $cadenaW; ?> 
								            @endif
								            @if($cadenaM != "")		
								            <li style="    padding: 5px;    color: white;    background: #F38419;">Aplicaciones móvil</li>							
								            <?php echo $cadenaM; ?> 
								            @endif
										</ul>
									</div>
								</li>
							@endforeach
						</ul>
							
					</div>
				</nav>
				<!-- /mp-menu -->
				<a href="#" id="trigger" class="menu-trigger" style="display:none"></a>

			</div><!-- /pusher -->