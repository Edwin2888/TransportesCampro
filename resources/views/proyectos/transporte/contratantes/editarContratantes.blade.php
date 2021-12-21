@extends('template.index')

@section('title')
    Editar Contratante
@stop

@section('title-section')
    Editar Contratante
@stop

@section('css2')
    <link rel="stylesheet" type="text/css" href="/css/transporteProveedores.css">
@stop
<main>
	<div class="container">
		<div class="row">
			<section>
			    <div class="tab-pane active" role="tabpanel" id="" style="margin-top: 53px;">
				  <h3>EDITAR CONTRATANTE</h3>
								<br>
						<form id="nuevoprov">	
                            <input type="hidden" name="_token" value="{{ csrf_token() }}"></input>	
                            <input type="hidden" name="id"  value="<?= $contratantes->id ?>" />			
                            <div class="row contenedorinputs" >
								  <div class="form-group">
									<div class="col-md-6">
											<span>Nombre</span>
											<input type="text" class="form-control valida_texto" name="nombre" id="nombre" data-val="val_nombre" readonly  value="<?= $contratantes->nombre ?>" >      
											<label class="mensaje-validacion val_nombre" ></label>
									</div>
					
									<div class="dv-clear"></div>
							<!--	<div class="col-md-12">
											<span>Elemento PEP</span>
											<select id="ceco" name="ceco" class="form-control valida_select"  data-val="valida_proyecto">
											   <option value="0">Seleccione Proyecto</option>
											    <?php foreach($proyectos as $pro){
												   $selected="";
												    if($contratantes->codigo_pep== $pro->elemento_pep){$selected="selected";}
											     ?>
													<option value="<?php echo $pro->elemento_pep ?>" <?= $selected ?> ><?php echo   $pro->elemento_pep ?>   -  <?php echo   $pro->denominacion ?></option>   
												<?php } ?>
											</select>
											<label class="mensaje-validacion valida_proyecto" ></label>
									</div>-->
							
									<div class="dv-clear"></div>
									<div class="col-md-6">
											<span>Proyecto</span>
											<input type="text" readonly class="form-control " name="elemento_pep" data-val="val_especialidad"   value="<?= $contratantes->c_n ?>" >      
											<label class="mensaje-validacion val_especialidad" ></label>
									</div>

										<div class="dv-clear"></div>
								

										<div class="col-md-8">
											<span>Grupo de Compras</span>
											<select id="grupo_compras" name="grupo_compras" class="form-control valida_select" data-val="valida_proyecto">
											   <option value="0">Seleccione Grupo de Compras</option>
											    <?php foreach($grupos as $grp){
												   $selected="";
												    if($contratantes->grupo_compras== $grp->grupo_compras){$selected="selected";}
											     ?>
													<option value="<?php echo $grp->grupo_compras ?>" <?= $selected ?> ><?php echo   $grp->grupo_compras ?>   -  <?php echo   $grp->nombre ?></option>   
												<?php } ?>
											</select>
											<label class="mensaje-validacion valida_proyecto" ></label>
									</div>

								
									
									
									<div class="dv-clear"></div>
                                        <div class="form-group has-feedback">
                                            <div class="col-md-12">
                                                <center>

                                                    <button type="submit" class="mb-xs mt-xs mr-xs btn btn-primary btn-ini">Guardar <i class='fa fa-floppy-o'></i></button>
                                                    <img src="/img/loader6.gif" class="loading " alt="Loading..." >
													&nbsp;&nbsp;
													<a href="/transversal/transporte/listaContratantes" class="btn btn-primary" >LISTAR</a>
                                                </center>
                                            </div>
                                        </div>
									 <div class="dv-clear"></div>	
								</div>
						    </div>	
		                </form>
		          </div>
		
			</section>
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
		$("#ceco").select2();
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
                            url: "/transversal/transporte/guardaContratantes",
                            data: formData,
                            dataType: "json",
                            cache: false,
                            contentType: false,
                            processData: false,
                            success: function(data,textStatus) {  
                                if(data.status==1){
                                	mostrarModal(2,null,"Actualizaciòn de contratante","Se ha actualizado correctamente el Contratante.\n",0,"Aceptar","",null);                                 
                                }else{
                                    mostrarModal(1,null,"Actualizaciòn de contratante","No se ha actualizado el Contratante.\n",0,"Aceptar","",null);                                 
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
     




