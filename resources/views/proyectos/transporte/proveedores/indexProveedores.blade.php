@extends('template.index')

@section('title')
    Crear proveerdor
@stop

@section('title-section')
    Crear proveerdor
@stop

@section('css2')
    <link rel="stylesheet" type="text/css" href="../../css/transporteProveedores.css">
@stop
<main>
	<div class="container" style="margin-top:80px;">
		<div class="row">
						<form id="nuevoprov">	
                            <input type="hidden" name="_token" value="{{ csrf_token() }}"></input>	

                            <input type="hidden" name="id"  value="0" />								
                            <div class="row contenedorinputs" >
								  <div class="form-group">
									<div class="col-md-6">
											<span>Nombre</span>
											<input type="text" class="form-control valida_texto" name="nombre" id="nombre" data-val="val_nombre"   value="" >      
											<label class="mensaje-validacion val_nombre" ></label>
									</div>
									<div class="col-md-6">
											<span>Nit</span>
											<input type="text" class="form-control valida_texto" name="nit" id="nit" data-val="val_nit" maxlength="50"  value="" >      
											<label class="mensaje-validacion val_nit" ></label>
									</div>
									<div class="dv-clear"></div>
									<div class="col-md-6">
											<span>Dirección</span>
											<input type="text" class="form-control" name="direccion" id="direccion" data-val="val_direccion" maxlength="50"  value="" >      
											<label class="mensaje-validacion val_direccion" ></label>
									</div>
									<div class="col-md-6">
											<span>Teléfono</span>
											<input type="text" class="form-control" name="telefono" id="telefono" data-val="val_telefono" maxlength="50"  value="" >      
											<label class="mensaje-validacion val_telefono" ></label>
									</div>
									<div class="dv-clear"></div>
									<div class="col-md-6">
											<span>Especialidad</span>
											<input type="text" class="form-control " name="especialidad" data-val="val_especialidad"   value="" >      
											<label class="mensaje-validacion val_especialidad" ></label>
									</div>
									<div class="col-md-6">
											<span>Proyecto</span>
											<select id="proyecto" name="proyecto" class="form-control" data-val="valida_proyecto">
											   <option value="0">Seleccione Proyecto</option>
											    <?php foreach($proyectos as $pro){ ?>
													<option value="<?php echo $pro->id ?>"><?php echo   $pro->nombre ?></option>   
												<?php } ?>
											</select>
											<label class="mensaje-validacion valida_proyecto" ></label>
									</div>
									<div class="dv-clear"></div>
									<div class="col-md-6">
											<span>Coordenadas</span>
											<input type="text" class="form-control " name="coordenadas" id="coordenadas" data-val="val_coordenadas"   value="" >      
											<label class="mensaje-validacion val_coordenadas" ></label>
									</div>
									<div class="col-md-6">
											<span>Correo</span>
											<input type="text" class="form-control " name="correo" id="correo" data-val="val_coreo"   value="" >      
											<label class="mensaje-validacion val_coreo" ></label>
									</div>
									
									<div class="dv-clear"></div>
									<div class="col-md-6">
											<span>Correo 2</span>
											<input type="text" class="form-control " name="correo2" id="correo2" data-val="val_correo2"   value="" >      
											<label class="mensaje-validacion val_correo2" ></label>
									</div>
									<div class="col-md-6">
											<span>Correo 3</span>
											<input type="text" class="form-control " name="correo3" id="correo3" data-val="val_coreo3"   value="" >      
											<label class="mensaje-validacion val_coreo3" ></label>
									</div>
									
									<div class="dv-clear"></div>
									
									<div class="col-md-12">
											<span>Observaciones</span>
											<textarea class="form-control " rows="4" name="obs_proyecto" id="obs_proyecto" data-val="val_obs_proyecto"  ></textarea>      
											<label class="mensaje-validacion val_obs_proyecto" ></label>
									</div>
									<div class="dv-clear"></div>
									
									
									<div class="dv-clear"></div>
                                        <div class="form-group has-feedback">
                                            <div class="col-md-12">
                                                <center>

                                                    <button type="submit" class="mb-xs mt-xs mr-xs btn btn-primary btn-ini">Guardar <i class='fa fa-floppy-o'></i></button>
                                                    <img src="../../img/loader6.gif" class="loading " alt="Loading..." >
													&nbsp;&nbsp;
													<a href="../../transversal/transporte/listaProveedores" class="btn btn-primary" >LISTAR</a>
                                                </center>
                                            </div>
                                        </div>
									 <div class="dv-clear"></div>	
								</div>
						    </div>	
		                </form>
		</div>
	</div>	
</main>	
<script type="text/javascript">
    //Función INI Javascript
    window.addEventListener('load',iniList);
 
   
 
 	function valida_texto(te)
 	{
 		return te.length;
 	}

 	function valida_select(ele)
 	{
 		return ele.selectedIndex;
 	}
 
    function iniList() 
    {
        //Oculta el banner transparente
        ocultarSincronizacionFondoBlanco();

         //alert("aja =>"+$("#nombre").val());
		$("#nuevoprov").on('submit',function(event){
			
			event.preventDefault();
			event.stopPropagation();
            var elementos = $("#nuevoprov .valida_texto");
            var tam = elementos.length;
            var control=0;
            for (var i=0; i<tam; i++) {
                if(valida_texto(elementos[i])==0){
                     control=1;
                }
            }
          
            var elementos2 = $("#nuevoprov .valida_select");
            var tam2 = elementos2.length;
            for (var i=0; i<tam2; i++) {
                if(valida_select(elementos2[i])==0){
                     control=1;
                }
            }
            if(control==1){return false;}
			
             $('#nuevoprov .btn-ini').hide("slow",function(){ 
			  
                    $('#nuevoprov .loading').show("slow"); 
                     
                     var formData = new FormData($("#nuevoprov")[0]);
                    $.ajax({
                            type: 'POST',
                            url: "/transversal/transporte/guardaProveedores",
                            data: formData,
                            dataType: "json",
                            cache: false,
                            contentType: false,
                            processData: false,
                            success: function(data,textStatus) {  
                            	if(data.status==1){
                                	mostrarModal(2,null,"Creación de proveedor","Se ha creado correctamente el proveedor.\n",0,"Aceptar","",null);                                 
                                	$('#nuevoprov')[0].reset();
                                }else{
                                    mostrarModal(1,null,"Creación de proveedor","No se ha creado el proveedor.\n",0,"Aceptar","",null);                                 
                                }
                            }, 
                            error: function(data) {
                                mensajes("Error","ocurrio un error",0);
                            }
                     }).always(function() {              
                          $('#nuevoprov .loading').hide("slow",function(){$('#nuevoprov .btn-ini').show();}); 
                     });  
             });
             return false;         
     });
		 
		 
		 
		
    }  
</script>
     




