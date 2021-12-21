@extends('template.index')

@section('title')
	Supervisor
@stop

@section('title-section')
    Supervisor
@stop

@section('content')
    <style type="text/css">
    #tbl_inspecciones_filter
    {
        position: relative;
        left: 100px;
    }

    </style>
	<main>

        <!-- Import Modal -->

		<div class="container-fluid">
                    <!--
                    <div class="side-body" id="cuerpo" style="margin-top:20px;margin-left:0px;">
                         proyectos.supervisor.secciones.filter  
                    </div>

                    <div class="tbl_index_proyectos" id="tbl_index_proyectos" data-pos="0">
                       proyectos.supervisor.tables.inspecciones
                    </div>
                    -->
                    
                    <div style="width:100%;clear:both;height:15px;"></div>
                    
                    <div id='contenido_tipo'>
                        
                        <center>
                            <div class="">
                                <label for="id_orden">Tipo de Inspección:</label>
                                <br>
                                <select name="tipo_insp" id="tipo_insp" class="form-control selectbusca" style="max-width:250px;padding:0px;">
                                    <option value="0">Seleccione</option>
                                    <option value="1">Seguridad</option>
                                    <option value="2">Calidad</option>
                                    <option value="3">Observación del comportamiento</option>
                                    <option value="4">Medio ambiente</option>
                                </select>
                            </div>              
                        </center>
                    </div>
                    
                    <div style="width:100%;clear:both;height:5px;"></div>
                    
                    <div id='contenido_creacion'>
                        
                    </div>
                    
                </div>
	</main>
   <link rel="stylesheet" href="{{url('/')}}/chosen/chosen.css">
   <script src="{{url('/')}}/chosen/chosen.jquery.js" type="text/javascript"></script>
                          
    <script type="text/javascript">

        window.addEventListener('load',ini);

        function ini()
        {
          
            document.querySelector("#sincronizando3").style.display = "none";
            document.querySelector("#sincronizando4").style.display = "none"; 
        }

        $(document).ready(function() {
          
            $("#tipo_insp").on('change',function(){
                
                var dato = $(this).val();
                if(dato==0){
                    $("#contenido_creacion").html("");
                }else{
                 
                    document.querySelector("#sincronizando3").style.display = "block";
                    document.querySelector("#sincronizando4").style.display = "block"; 

                    $.ajax({
                            type: 'POST',
                            url: "{{url('/')}}/cargaNuevoIpal",
                            data: {'dato':dato,
                                   '_token':'<?= csrf_token() ?>' },
                            dataType: "html",
                            success: function(data) {
                                $("#contenido_creacion").html(data);
                            }, 
                            error: function() { mensajes("Error","Ocurrió un error por favor inténtalo nuevamente mas tarde.",0);   }
                    }).always(function(){                 
                        document.querySelector("#sincronizando3").style.display = "none";
                        document.querySelector("#sincronizando4").style.display = "none"; 
                            $(".selectbusca").chosen({/*width:"100%"*/});
                    }); 
                    
                }
            });
    
        });

     function cambia_prefijo(event,elemento){
            event.stopPropagation();
            event.preventDefault();

            var dato = $(elemento).val();
            
            var tipo = $("#prefijo").find(':selected').data('tipo');
            
            $("#id_tipo_proyecto").val(tipo);

            if(dato == 0 ){
                $('#lider').empty().append('<option value="0">Seleccione</option>');
                $(".selectbusca").trigger("chosen:updated");
                cambiadatos("","","","","","","","","","","","","","");
                return;
            }
           
            document.querySelector("#sincronizando3").style.display = "block";
            document.querySelector("#sincronizando4").style.display = "block"; 
<?php //sdsfsdf  */ ?> 
            $.ajax({
                    type: 'POST',
                    url: "{{url('/')}}/cargaLideres",
                    data: {'dato':dato,
                           '_token':'<?= csrf_token() ?>' },
                    dataType: "json",
                    success: function(data) {//console.log(data.cantidad);
                        if(data.cantidad>0){
                            //console.log("entra if");
                            $('#lider').empty().append('<option value="0">Seleccione</option>');
                            
                            for(var i =0;i<data.cantidad;i++){
                            //console.log("entra for");
                                //<select id='lider' name='lider' class="form-control" onchange="cambia_lider(event,this)">
                                //<option value="0" >Seleccione</option>   
                                var option  = '<option value="'+data.datos[i]['id_lider']+'"  ';
                                    option += ' data-aux1="'+data.datos[i]['id_aux1']+'" data-aux1txt="'+data.datos[i]['aux1txt']+'"    ';
                                    option += ' data-aux2="'+data.datos[i]['id_aux2']+'" data-aux2txt="'+data.datos[i]['aux2txt']+'"    ';
                                    option += ' data-aux3="'+data.datos[i]['id_aux3']+'" data-aux3txt="'+data.datos[i]['aux3txt']+'"    ';
                                    option += ' data-conductor="'+data.datos[i]['id_conductor']+'" data-conductortxt="'+data.datos[i]['conductortxt']+'"    ';
                                    option += ' data-matricula="'+data.datos[i]['matricula']+'"    ';
                                    option += ' data-id_movil="'+data.datos[i]['id_movil']+'"    ';
                                    option += ' data-tipocu="'+data.datos[i]['id_tipo_cuadrilla']+'" data-tipocutxt="'+data.datos[i]['tipocuadrillatxt']+'"    ';
                                    option += ' data-supervisor="'+data.datos[i]['id_supervisor']+'" data-supervisortxt="'+data.datos[i]['super']+'"    ';
                                    option += ' >';
                                    option +=  data.datos[i]['lidertxt'];
                                    option += '</option>';
                                        
                                $('#lider').append(option);
                            }
                            
                            cambiadatos("","","","","","","","","","","","","","");
                            $(".selectbusca").trigger("chosen:updated");
                        }else{
                            $('#prefijo').val(0);
                            $('#lider').empty().append('<option value="0">Seleccione</option>');
                            $(".selectbusca").trigger("chosen:updated");
                            cambiadatos("","","","","","","","","","","","","","");
                        }
                    }, 
                    error: function() { 
                        $('#prefijo').val(0);
                        $('#lider').empty().append('<option value="0">Seleccione</option>');
                        $(".selectbusca").trigger("chosen:updated");
                        cambiadatos("","","","","","","","","","","","","","");
                        mensajes("Error","Ocurrió un error por favor inténtalo nuevamente mas tarde.",0);   
                    }
            }).always(function(){                 
                document.querySelector("#sincronizando3").style.display = "none";
                document.querySelector("#sincronizando4").style.display = "none"; 
            }); 
     
     }
     //$(this).find(':selected').data('id')
     
    function cambia_lider(event,elemento){
        event.stopPropagation();
        event.preventDefault();
           
        var aux1 = "";
        var aux1txt =  "";
        var aux2 =  "";
        var aux2txt =  "";
        var aux3 =  "";
        var aux3txt =  "";
        var conductor =  "";
        var conductortxt =  "";
        var tipocu =  "";
        var tipocutxt =  "";
        var matricula =  "";
        var id_movil =  ""
        var supervisor="";
        var supervisor_txt="";
            
        if( $("#lider").val() != 0 ){
           
            aux1 = $("#lider").find(':selected').data('aux1');
            aux1txt = $("#lider").find(':selected').data('aux1txt');

            aux2 = $("#lider").find(':selected').data('aux2');
            aux2txt = $("#lider").find(':selected').data('aux2txt');

            aux3 = $("#lider").find(':selected').data('aux3');
            aux3txt = $("#lider").find(':selected').data('aux3txt');

            conductor = $("#lider").find(':selected').data('conductor');
            conductortxt = $("#lider").find(':selected').data('conductortxt');

            tipocu = $("#lider").find(':selected').data('tipocu');
            tipocutxt = $("#lider").find(':selected').data('tipocutxt');

            matricula = $("#lider").find(':selected').data('matricula');

            id_movil = $("#lider").find(':selected').data('id_movil');
        
            supervisor = $("#lider").find(':selected').data('supervisor');
            supervisor_txt = $("#lider").find(':selected').data('supervisortxt');
        }
        
        cambiadatos(aux1,aux1txt,aux2,aux2txt,aux3,aux3txt,conductor,conductortxt,tipocu,tipocutxt,matricula,id_movil,supervisor,supervisor_txt);
        
    }
    
    
    function cambiadatos(aux1,aux1txt,aux2,aux2txt,aux3,aux3txt,conductor,conductortxt,tipocu,tipocutxt,matricula,id_movil,supervisor,supervisor_txt){
        
        $("#aux1").val(aux1);
        $("#aux1txt").val(aux1txt);

        $("#aux2").val(aux2);
        $("#aux2txt").val(aux2txt);

        $("#aux3").val(aux3);
        $("#aux3txt").val(aux3txt);

        $("#conductor").val(conductor);
        $("#conductortxt").val(conductortxt);

        $("#tipocu").val(tipocu);
        $("#tipocutxt").val(tipocutxt);

        $("#matricula").val(matricula);

        $("#id_movil").val(id_movil);
        
        $("#supervisor").val(supervisor);
        $("#supervisor_txt").val(supervisor_txt);
        
    }
    
    $(".selectbusca").chosen({/*width:"100%"*/});
///$('input[name=radioName]:checked').val()

        function checkradio(idpregunta){
            
            var rep = $(" input[name='pregunta_"+idpregunta+"']:checked").val();
            if(rep=='NO'){
                $("#acto_condicion_"+idpregunta).prop( "disabled", false );
                $("#texto_extra_"+idpregunta).prop( "disabled", false );
            }else{
                $("#acto_condicion_"+idpregunta).val(0);
                $("#texto_extra_"+idpregunta).val("");
                $("#acto_condicion_"+idpregunta).prop( "disabled", true );
                $("#texto_extra_"+idpregunta).prop( "disabled", true );
            }
            
            
            
            var resp = valida_todo_marcado(); 
            if(resp['control']==1){//esta todo marcado
                $("#resultado").val(resp['resultado']);                
                var resultadotxt="CONFORME";
                if(resp['resultado'] != 'C'){
                    resultadotxt="NO CONFORME";  
                }
                $("#resultadotxt").val(resultadotxt);
                $("#calificacion").val(resp['calificacion']);             
            }else{  //faltan por marcar
                $("#resultado").val("");
                $("#resultadotxt").val("");
                $("#calificacion").val("");
                
            }
        }
        
        function valida_todo_marcado(){
            
            var elementos = $(".contenresp");
            var tam = elementos.length;
            var control=1;
            var validaacto=1;
            var resultado=1;
            var calificacion=0;
            var matriz = [];
            var sinmarcar="";
            var coma="";
            
            var sinactocondi="";
            var comad="";
            
            for (var i=0; i<tam; i++) {
                var pregid = $(elementos[i]).data('idpre');
                var tipo = $(elementos[i]).data('tipocontrol');
                var califica = $(elementos[i]).data('califica');
                var item = $(elementos[i]).data('item');
                
                if(tipo==2 || tipo==12){/// los que tienen radios
                
                   // console.log("Pregunta |"+pregid+"|"+tipo+"|"+$(elementos[i]).find(" input[name='pregunta_"+pregid+"']").is(':checked')+"|");
                    if( !$(" input[name='pregunta_"+pregid+"']").is(':checked') ){
                         control=0;
                         sinmarcar=sinmarcar+coma+item;
                         coma=", ";
                    }else{
                       var rep = $(" input[name='pregunta_"+pregid+"']:checked").val();
                       if(rep=='NO'){
                           if( $("#acto_condicion_"+pregid).val()==0 ){
                                validaacto=0;
                                sinactocondi=sinactocondi+comad+item;
                                comad=", ";
                           }
                           
                           resultado=0;
                          if(calificacion<califica){calificacion=califica}
                       }
                    }
               }
            }
            
            if(sinactocondi!=""){sinmarcar=sinmarcar+" preguntas sin acto o condicipón "+sinactocondi;}
            
            var resultadotxt="C";
            if(resultado==0){resultadotxt="NC";}
            return {'control':control,'validaacto':validaacto,'resultado':resultadotxt,'calificacion':calificacion,'sinmarcar':sinmarcar};
        }
        
        
        function guarda(elemento,event){
        
            event.preventDefault();
            event.stopPropagation();
            
            
            
            var prefijo = $("#prefijo").val();
            var lider = $("#lider").val();
            var direccion = $("#direccion_inspeccion").val();
            
             if(prefijo==0){//Faltan por marcar
                mensajes("Error","Se debe selecciones un proyecto: ",0);   
                return
            }
            
             if(lider==0){//Faltan por marcar
                mensajes("Error","Se debe selecciones un lider: ",0);   
                return
            }
            
            if(direccion.trim()==""){
                mensajes("Error","Ingrese una dirección",0);   
                return
            }
            
            var resp = valida_todo_marcado(); 
            if(resp['control']==0 || resp['validaacto']==0 ){//Faltan por marcar 
                mensajes("Error","Es obligatorio responder todas las preguntas, las preguntas sin marcar son: "+resp['sinmarcar'],0);   
                return;
            }
            /*
            var respuesta=[];
            respuesta['direccion_inspeccion']=direccion;
            
            respuesta['prefijo']=prefijo;
            respuesta['lider']=lider;
            respuesta['id_orden']=$("#id_orden").val();
            respuesta['id_tipo_proyecto']=$("#id_tipo_proyecto").val();
            respuesta['supervisor']=$("#supervisor").val();
            respuesta['aux1']=$("#aux1").val();
            respuesta['aux2']=$("#aux2").val();
            respuesta['aux3']=$("#aux3").val();
            respuesta['conductor']=$("#conductor").val();
            respuesta['matricula']=$("#matricula").val();
            respuesta['tipocu']=$("#tipocu").val();
            respuesta['id_movil']=$("#id_movil").val();
            respuesta['anio']=$("#anio").val();
            respuesta['resultadotxt']=$("#resultadotxt").val();
            respuesta['calificacion']=$("#calificacion").val();
            respuesta['observacion']=$("#observacion").val();*/
                 // console.log("1");
            
            var datos=[];
            var elementos = $(".contenresp");
            var tam = elementos.length;
            var tipoformulario=1;
            
            for (var i=0; i<tam; i++) { //console.log("2");
                var pregid = $(elementos[i]).data('idpre');
                var tipo = $(elementos[i]).data('tipocontrol');
                var califica = $(elementos[i]).data('califica');
                var item = $(elementos[i]).data('item');
                var version = $(elementos[i]).data('version');
                    tipoformulario = $(elementos[i]).data('tipoformulario');
                var rep="N/A";
                var acto_condicion="";
                var texto_extra="";
                if(tipo==2 || tipo==12){/// los que tienen radios
                
                    rep = $(" input[name='pregunta_"+pregid+"']:checked").val();
                    if($("#acto_condicion_"+pregid).val()!=0){
                       acto_condicion = $("#acto_condicion_"+pregid).val();
                    }
                    texto_extra = $("#texto_extra_"+pregid).val();
                    
               }else{
                   texto_extra = $("#texto_extra_"+pregid).val();
               }
               
                datos[i]={'id_pregunta':pregid,'respuesta':rep,'version':version,'acto_condicion':acto_condicion,'texto_extra':texto_extra,'id_inspeccion':tipoformulario  };
            }
            //console.log("3");
            
            //respuesta['datos']=datos;
            
            var charla = "";
            if(tipoformulario==1){ //console.log("4");
                if( $(" input[name='charla']").is(':checked') ){ //console.log("5");
                    charla = $(" input[name='charla']:checked").val();
                }else{ //console.log("6");
                    mensajes("Error","Seleccione respuesta para la CHARLA OPERATIVA ",0);   
                    return;                    
                }
            } ///console.log("7");
            /*
            respuesta['charla']=charla;
            respuesta['tipo_inspeccion']=tipoformulario;
            */
            
            //console.log(respuesta);
            <?php //sfsdfdsf  ?>
            
            document.querySelector("#sincronizando3").style.display = "block";
            document.querySelector("#sincronizando4").style.display = "block"; 
            
            $.ajax({
                    type: 'POST',
                    url: "{{url('/')}}/guardaNuevoIpal",
                    data: { 'datos':datos,
                            'direccion_inspeccion':direccion,            
                            'prefijo':prefijo,
                            'lider':lider,
                            'id_orden':$("#id_orden").val(),
                            'id_tipo_proyecto':$("#id_tipo_proyecto").val(),
                            'supervisor':$("#supervisor").val(),
                            'aux1':$("#aux1").val(),
                            'aux2':$("#aux2").val(),
                            'aux3':$("#aux3").val(),
                            'conductor':$("#conductor").val(),
                            'matricula':$("#matricula").val(),
                            'tipocu':$("#tipocu").val(),
                            'id_movil':$("#id_movil").val(),
                            'anio':$("#anio").val(),
                            'mes':$("#mes").val(),
                            'resultado':resp['resultado'],
                            'calificacion':resp['calificacion'],
                            'observacion':$("#observacion").val(),
                            'charla':charla,
                            'tipo_inspeccion':tipoformulario,            
                           '_token':'<?= csrf_token() ?>' },
                    dataType: "json",
                    success: function(data) {
                        if(data.status==1){
                            $("#tipo_insp").val(0);
                            $("#contenido_creacion").html("");
                            $(".selectbusca").trigger("chosen:updated");
                            mensajes("Exito",data.message,1);
                        }else if(data.status==0){
                            mensajes("Error",data.message,0);
                        }else{
                            mensajes("Error","Ocurrió un error por favor inténtalo nuevamente mas tarde.",0);
                        }
                         
                    }, 
                    error: function() { mensajes("Error","Ocurrió un error por favor inténtalo nuevamente mas tarde.",0);   }
            }).always(function(){                  
                    
                document.querySelector("#sincronizando3").style.display = "none";
                document.querySelector("#sincronizando4").style.display = "none"; 
            }); 
            
        }

    </script>
@stop

