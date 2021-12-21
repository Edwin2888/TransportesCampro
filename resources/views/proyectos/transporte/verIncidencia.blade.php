
@extends('template.index')

@section('title')
    Ver incidencia
@stop

@section('title-section')
    Ver incidencia
@stop

@section('css2')
    <link rel="stylesheet" type="text/css" href="../../css/transporte.css">
@stop

<main>
@include('proyectos.transporte.modal.modalCargarAdjuntoIncidencia')    
@include('proyectos.transporte.modal.modalLogCambiosCostos')   
@include('proyectos.transporte.modal.modalVerHistoricoIncidencia')
<div class="container">


    @include('proyectos.transporte.secciones.frmEncabezadoIncidenca')  
     
    <div class="row">
        <section>
            
        </section>
    </div>
</div>
</main>
@section('js')
    <script type="text/javascript" src="../../js/bootstrap-filestyle.min.js"></script>
@stop

<script type="text/javascript">
    window.addEventListener('load',ini);
   
    /* FIN: FUNCIONES PARA PESTAÑAS */
    function ini() {
        
        ocultarSincronizacionFondoBlanco();


        document.querySelector("#panel_novedades").style.display = "block";
        document.querySelector("#img_consulta_ajax").innerHTML = '<img src="../../../img/ajax-loader1.gif" id="loading_novedades" style="    margin-left: calc(50% - 50px);    margin-bottom: 13px;    margin-top: 10px;">';
        document.querySelector("#novedades_no_tiene").innerHTML = "";
        document.querySelector("#novedades_user").innerHTML = "";

        document.querySelector("#img_consulta_ajax").style.display = "block";
        document.querySelector("#novedades_no_tiene").style.display = "none";
        document.querySelector("#novedades_user").style.display = "none";
        
        
        var datos = {
              incidencia: document.querySelector("#txt_incidencia_dato").value,
              opc: 10
        };
      
        consultaAjax("../../rutaConsultaTransporte", datos, 15000, "POST", 8,0,-1); 


        @if(count($perfil) > 0)
        @if($perfil[0]->nivel_acceso == "W" && $datos->tipo_incidencia == "349")
        document.querySelector("#txt_km").addEventListener("keydown",validaIngreso);
        @endif
        @endif

        $("#file_archiv_impor").change(function(){ 
            cargaImagenesIMG(this)
        });

        @if(count($perfil) > 0)
            @if($perfil[0]->nivel_acceso == "W")
            //Save datos Ingreso

            @if($datos->id_estado != 'E4')
            document.querySelector("#btn_save_ingreso").addEventListener("click",function()
            {
                if(document.querySelector("#costo_ingreso").value == 0 || document.querySelector("#costo_ingreso").value == "")
                {
                    mostrarModal(1,null,"Guardar Salida de taller","El costo estimado no puede estar en 0.\n",0,"Aceptar","",null);
                    return;
                }

                if(document.querySelector("#txtFechaUltimoMante").value == "")
                {
                    mostrarModal(1,null,"Guardar Salida de taller","Hace falta ingresar la fecha estimada de salida.\n",0,"Aceptar","",null);
                    return;
                }

                if(document.querySelector("#txt_observacion_1").value == "")
                {
                    mostrarModal(1,null,"Guardar Salida de taller","Hace falta ingresar la observación de modificación de costo estimado.\n",0,"Aceptar","",null);
                    return;
                }

                var datos = {
                        incidencia: document.querySelector("#txt_incidencia_dato").value,
                        costo: document.querySelector("#costo_ingreso").value,
                        fecha: document.querySelector("#txtFechaUltimoMante").value,
                        obser: document.querySelector("#txt_observacion_1").value,
                        opc: 23
                    }

                consultaAjax("../../rutaInsercionTransporte", datos, 15000, "POST", 2);

            });
            @endif
        @endif

        @if(count($perfilCostoReal) > 0)
            @if($perfilCostoReal[0]->nivel_acceso == "W")
            //Log ingreso
            document.querySelector("#btn_log_ingreso").addEventListener("click",function()
            {
                var datos = {
                        incidencia: document.querySelector("#txt_incidencia_dato").value,
                        tipo: 1,
                        opc : 16
                    }

                consultaAjax("../../rutaConsultaTransporte", datos, 150000, "POST", 3);
            });


            document.querySelector("#btn_save_salida").addEventListener("click",function()
            {
                if(document.querySelector("#txt_costo_salida").value == 0 || document.querySelector("#txt_costo_salida").value == "")
                {
                    mostrarModal(1,null,"Guardar Salida de taller","El costo real no puede estar en 0.\n",0,"Aceptar","",null);
                    return;
                }

                 if(document.querySelector("#txt_observacion_2").value == "")
                {
                    mostrarModal(1,null,"Guardar Salida de taller","Hace falta ingresar la observación de modificación de costo real.\n",0,"Aceptar","",null);
                    return;
                }


                var datos = {
                        incidencia: document.querySelector("#txt_incidencia_dato").value,
                        costo: document.querySelector("#txt_costo_salida").value,
                        obser: document.querySelector("#txt_observacion_2").value,
                        opc: 24
                    }

                consultaAjax("../../rutaInsercionTransporte", datos, 15000, "POST", 2);
            });

            //Log ingreso
            document.querySelector("#btn_log_salida").addEventListener("click",function()
            {
                var datos = {
                        incidencia: document.querySelector("#txt_incidencia_dato").value,
                        tipo: 2,
                        opc : 16
                    }

                consultaAjax("../../rutaConsultaTransporte", datos, 150000, "POST", 3);
            });

            @endif
            @endif
        @endif

        <?php
            $pos = strpos($datos->incidencia, 'OM');
        ?>
        @if ($pos !== false)

        @if($acceso == "W")
            //Save datos Ingreso
            document.querySelector("#btn_save_ingreso").addEventListener("click",function()
            {
                if(document.querySelector("#costo_ingreso").value == 0 || document.querySelector("#costo_ingreso").value == "")
                {
                    mostrarModal(1,null,"Guardar Salida de taller","El costo de ingreso no puede estar en 0.\n",0,"Aceptar","",null);
                    return;
                }

                if(document.querySelector("#txtFechaUltimoMante").value == "")
                {
                    mostrarModal(1,null,"Guardar Salida de taller","Hace falta ingresar la fecha de salida de taller.\n",0,"Aceptar","",null);
                    return;
                }

                var datos = {
                        incidencia: document.querySelector("#txt_incidencia_dato").value,
                        costo: document.querySelector("#costo_ingreso").value,
                        fecha: document.querySelector("#txtFechaUltimoMante").value,
                        opc: 23
                    }

                consultaAjax("../../rutaInsercionTransporte", datos, 15000, "POST", 2);

            });

            //Log ingreso
            document.querySelector("#btn_log_ingreso").addEventListener("click",function()
            {
                var datos = {
                        incidencia: document.querySelector("#txt_incidencia_dato").value,
                        tipo: 1,
                        opc : 16
                    }

                consultaAjax("../../rutaConsultaTransporte", datos, 150000, "POST", 3);
            });

        @endif
        @endif
    }

    function abrirModalAdjunto()
    {
        $("#modal_upload_adjunto_incidencia").modal("toggle");
    }

    function cargaImagenesIMG(opc,ele)
    {
        var event = window.event;
        var selectedFile = event.target.files[0];
        var reader = new FileReader();
        var imgtag  = "";
        var input = "";
        
        imgtag = document.querySelector("#file_archiv_impor");
        input = document.querySelector("#file_archiv_impor");

        //alert(selectedFile.type);
        if(selectedFile.type == "application/pdf" )
        {
            if(selectedFile.size > 5000000)
            {
                input.value = "";
                $(imgtag).filestyle('clear');
                mostrarModal(1,null,"Tamaño imagen","El tamaño de la imagen no puede superar 5 MB.\n",0,"Aceptar","",null);
                return;
            }   
        }
        else
        {
            mostrarModal(1,null,"Tipo de archivo","Los tipo de archivo que puede cargar son .PDF\n",0,"Aceptar","",null);
            input.value = "";
            $(imgtag).filestyle('clear');
            return;
        }
    }

    function finalizaIncidencia()
    {

        var datos = {
            incidencia: document.querySelector("#txt_incidencia_dato").value,
            opc: 22
        }

        consultaAjax("../../rutaInsercionTransporte", datos, 15000, "POST", 1);

    }

    function anulaincidencia()
    {

        if(document.querySelector("#txt_obser_anulacion").value == "")
        {
            mostrarModal(1,null,"Anulación de incidencia","Por favor ingrese la observación de anulación de incidencia\n",0,"Aceptar","",null);
            return;
        }

        if(confirm("¿Seguro que desea anular la Incidencia?"))
        {
            var datos = {
                incidencia: document.querySelector("#txt_incidencia_dato").value,
                obs : document.querySelector("#txt_obser_anulacion").value,
                opc: 26
            }

            consultaAjax("../../rutaInsercionTransporte", datos, 15000, "POST", 4);            
        }
        
    }

    function abrirModalEntrega()
    {
        
    }

    function abrirModalVerificacion()
    {

    }
    
    function saveKM()
    {

        var kilo = document.querySelector("#txt_km").value;

        if(kilo == "" || kilo == "0" || kilo == ".00" || kilo == "00")
        {
            mostrarModal(1,null,"Modificar KM","Hace falta ingresar el KM\n",0,"Aceptar","",null);
            return;
        }

        var datos = {
            incidencia: document.querySelector("#txt_incidencia_dato").value,
            placa: document.querySelector("#txt_placa").value,
            km: kilo,
            opc: 28
        }

        consultaAjax("../../rutaInsercionTransporte", datos, 15000, "POST", 5);
    }

    function saveKMProx()
    {
        var kilo = document.querySelector("#txt_km_prox").value;

        if(kilo == "" || kilo == "0" || kilo == ".00" || kilo == "00")
        {
            mostrarModal(1,null,"Modificar KM","Hace falta ingresar el KM próximo\n",0,"Aceptar","",null);
            return;
        }

        var datos = {
            incidencia: document.querySelector("#txt_incidencia_dato").value,
            km: kilo,
            opc: 29
        }

        consultaAjax("../../rutaInsercionTransporte", datos, 15000, "POST", 6);
    }

    function saveVisita()
    {
        var fecha = document.querySelector("#txt_fecha_visita").value;
        var obser = document.querySelector("#txt_obser_visita").value;

        if(fecha == "")
        {
            mostrarModal(1,null,"Guardar visita","Hace falta ingresar la fecha de visita\n",0,"Aceptar","",null);
            return
        }

        fecha = fecha.split("/")[2] + "-" + fecha.split("/")[1] + "-" + fecha.split("/")[0];

        if(obser == "")
        {
            mostrarModal(1,null,"Guardar visita","Hace falta ingresar la observación de la visita\n",0,"Aceptar","",null);
            return
        }

        var datos = {
            incidencia: document.querySelector("#txt_incidencia_dato").value,
            fecha: fecha,
            obser: obser,
            opc: 30
        }

        consultaAjax("../../rutaInsercionTransporte", datos, 15000, "POST", 7);


    }


     function consultaAjax(route,datos,tiempoEspera,type,opcion,edita,dato)
    {
        if(dato != -1)
            mostrarSincronizacion();

        $.ajax({
            url: route,
            type: type,
            headers: {'X-CSRF-TOKEN':document.querySelector("#token_datos").value},
            dataType: "json",
            data:datos,
            timeout:tiempoEspera,
            success:function(data)
            {
                ocultarSincronizacion();
                if(opcion ==1)//Save incidencia
                {
                    mostrarModal(2,null,"Cierre de la incidencia","Se ha cerrado correctamente la incidencia.\n",0,"Aceptar","",null);
                    setTimeout(function()
                    {
                        location.reload();
                    },1000);
                }

                if(opcion ==2)//Save cambios
                {
                    mostrarModal(2,null,"Cambio costos","Se han cambiados los datos de salido de taller existosamente.\n",0,"Aceptar","",null);
                }

                if(opcion == 3) //Consulta datos
                {
                    if(datos.tipo == 1)
                    {
                        var html = "";

                          html += '<table id="tbl_ingreso_taller" class="table table-striped table-bordered table_center" cellspacing="0" width="94%;margin-left:3%;">';
                          html += '    <thead>';
                          html += '        <tr>';
                          html += '            <th style="width:10px;">No.</th>';
                          html += '            <th style="width:100px;">Usuario</th>';
                          html += '            <th style="width:100px;">Fecha de cambio</th>';
                          html += '            <th style="width:100px;">Costo</th>';
                          html += '            <th style="width:100px;">Salida de taller</th>';
                          html += '            <th style="width:100px;">Observación</th>';
                          html += '        </tr>';
                          html += '    </thead>';
                          html += '    <tbody>';
                          for (var i = 0; i < data[0].length; i++) {
                            html += "<tr>";
                            html += "<td>" + (i + 1)  + "</td>";
                            html += "<td>" + data[0][i].propietario  + "</td>";
                            html += "<td>" + data[0][i].fecha_servidor.split(".")[0]  + "</td>";
                            html += "<td>" + (data[0][i].costo.replace(".00","") == "" ? 0 : data[0][i].costo.replace(".00","")) + "</td>";
                            html += "<td>" + (data[0][i].fecha_taller == null ? '' : data[0][i].fecha_taller) + "</td>";
                            html += "<td>" + data[0][i].observacion  + "</td>";
                            html += "</tr>";
                          };
                          html += '</tbody>';
                          html += '</table>';

                        document.querySelector("#log_cambios").innerHTML = html;
                        $("#modal_logs_cambios").modal("toggle");
                    }
                    else
                    {
                        var html = "";

                          html += '<table id="tbl_ingreso_taller" class="table table-striped table-bordered table_center" cellspacing="0" width="94%;margin-left:3%;">';
                          html += '    <thead>';
                          html += '        <tr>';
                          html += '            <th style="width:10px;">No.</th>';
                          html += '            <th style="width:100px;">Usuario</th>';
                          html += '            <th style="width:100px;">Fecha de cambio</th>';
                          html += '            <th style="width:100px;">Costo</th>';
                          html += '            <th style="width:100px;">Observación</th>';
                          html += '        </tr>';
                          html += '    </thead>';
                          html += '    <tbody>';
                          for (var i = 0; i < data[0].length; i++) {
                            html += "<tr>";
                            html += "<td>" + (i + 1)  + "</td>";
                            html += "<td>" + data[0][i].propietario  + "</td>";
                            html += "<td>" + data[0][i].fecha_servidor.split(".")[0]  + "</td>";
                            html += "<td>" + (data[0][i].costo.replace(".00","") == "" ? 0 : data[0][i].costo.replace(".00","")) + "</td>";
                            html += "<td>" + data[0][i].observacion  + "</td>";
                            html += "</tr>";
                          };
                          html += '</tbody>';
                          html += '</table>';

                        document.querySelector("#log_cambios").innerHTML = html;
                        $("#modal_logs_cambios").modal("toggle");
                    }


                    

                    if(data[3] === "")
                        document.querySelector("#variacion_dias").style.display = "none";
                    else
                        document.querySelector("#variacion_dias").style.display = "inline-block";

                    document.querySelector("#lbl_0").innerHTML = data[3] + " días";
                    document.querySelector("#lbl_1").innerHTML = "$" + data[1];
                    document.querySelector("#lbl_2").innerHTML =  data[2].toFixed(2) + "%";
                }

                if(opcion ==4)//Save anula incidencia
                {
                    mostrarModal(2,null,"Anular incidencia","Se ha anulado correctamente la incidencia.\n",0,"Aceptar","",null);
                    setTimeout(function()
                    {
                        location.reload();
                    },1000);
                }

                if(opcion ==5)//Save KM
                {
                    mostrarModal(2,null,"Modificar KM","Se ha modificado correctamente el KM\n",0,"Aceptar","",null);
                }

                if(opcion ==6)//Save Km Próximo
                {
                    mostrarModal(2,null,"Modificar KM","Se ha modificado correctamente el KM Próximo\n",0,"Aceptar","",null);
                }


                if(opcion ==7)//Fecha de visita
                {
                    document.querySelector("#txt_fecha_visita").value = "";
                    document.querySelector("#txt_obser_visita").value = "";
                    var html = "";
                    for (var i = 0; i < data.length; i++) {
                        html += "<tr>";
                        html += "<td style='text-align:center;width:50px;'>" + (data.length - i)  + "</td>";
                        html += "<td style='text-align:center;width:50px;'>" + data[i].fecha_visita  + "</td>";
                        html += "<td style='text-align:center;width:50px;'>" + data[i].observacion  + "</td>";
                        html += "<td style='text-align:center;width:50px;'>" + data[i].propietario  + "</td>";
                        html += "</tr>";
                      };
                    document.querySelector("#tblvisitas").innerHTML = html;
                    mostrarModal(2,null,"Guardar visita","Se ha guardado correctamente la visita\n",0,"Aceptar","",null);
                }

                if(opcion == 8) //Mostrar datos log incidencia
                {

                    if(data.length == 0)
                      {
                        document.querySelector("#img_consulta_ajax").innerHTML = '';
                        document.querySelector("#novedades_no_tiene").innerHTML = '<p class="user"><i class="fa fa-user-times" aria-hidden="true"></i> No tiene novedades para esta incidencia</p>';
                        document.querySelector("#novedades_user").innerHTML = "";
                        var crearN = '';
                        
                        document.querySelector("#img_consulta_ajax").style.display = "none";
                        document.querySelector("#novedades_no_tiene").style.display = "block";
                        document.querySelector("#novedades_user").style.display = "none";

                      }
                      else
                      {
                        document.querySelector("#img_consulta_ajax").innerHTML = '';
                        document.querySelector("#novedades_no_tiene").innerHTML = '';
                        document.querySelector("#novedades_user").innerHTML = "";

                        document.querySelector("#img_consulta_ajax").style.display = "none";
                        document.querySelector("#novedades_no_tiene").style.display = "none";
                        document.querySelector("#novedades_user").style.display = "block";
                        

                        var html = '';
                        for (var i = 0; i < data.length; i++) {
                          html += '<div class="novedad_user">';
                          var tipo_user = "CENTRO DE CONTROL:";
                          if(data[i][0].tipo == 3)
                            tipo_user = "TÉCNICO:";

                          if(data[i][0].tipo == 2)
                            tipo_user = "CONDUCTOR:";

                          html += '    <p class="user"><i class="fa fa-user-circle-o" aria-hidden="true"></i>' + tipo_user + "" + data[i][1] + '</p>';
                          html += '    <p class="date"><i class="fa fa-calendar" aria-hidden="true"></i>' + data[i][0].fecha_servidor.split(".")[0] + '</p>';
                          html += '    <p class="content"><i class="fa fa-commenting-o" aria-hidden="true"></i>&quot;' + data[i][0].observacion + '&quot;</p>';
                          html += '</div>';
                        };
                        document.querySelector("#novedades_user").innerHTML = html;
                      }

                }
                

            },
            error:function(request,status,error){
                
                //$('#filter_registro').modal('toggle');

                /*mostrarModal(1,null,"Consulta de Alianzas","Existen problemas con la conexión a internet, comuníquese con la persona encargada del manejo de la red.\n",0,"Aceptar","",null);*/
                setTimeout(function()
                {
                    //location.reload();
                },3000);
            }

        });
    }

    function abrirModalIncidencia()
{
  $("#modal_incidencias_log").modal("toggle");
}

// =============================================================================================
// Funciones Cerrar Incidencia
// =============================================================================================
<?php if($permiso_w_cerrar_incidencia == 1): ?>
  function abrirModalCerrarIncidencia(event){
    event.preventDefault();
    event.stopPropagation();
    $("#modal_cerrar_incidencia_reg").modal('show');
  }

  function guardaCerrarIncidencia(event){
    $('#cierre_incidencia__btn_guardar').text('Cerrando Incidencia...').attr('disabled', true);

    var numero_incidencia   = $.trim($("#cierre_incidencia__numero_incidencia").val());
    var fecha_finalizacion  = $.trim($("#cierre_incidencia__fecha_finalizacion").val());
    var fecha_creacion      = $.trim($("#cierre_incidencia__fecha_creacion").val());
    var km_actual           = $.trim($("#cierre_incidencia__km_actual").val());
    var km_proximo          = $.trim($("#cierre_incidencia__km_proximo").val());
    var taller              = $.trim($("#cierre_incidencia__taller").val());
    var observaciones       = $.trim($("#cierre_incidencia__observaciones").val());
    var rutinas = new Array();
    var id_rutina = $("#id_rutina").val();
    
    $.ajax({
        type:         'POST',
        url:          "/rutinaDetalle",
        data:         {"id_rutina":id_rutina},
        headers:      {'X-CSRF-TOKEN':document.querySelector("#token_datos").value},
        dataType:     "json",
        success: function(data, textStatus) {
          console.log('serVER DETALK');
          console.log(data);
          i = 0;
            data.forEach(ele => {
                checks = '';
                if($("#cierre_incidencia__id_detalle"+ele.id_detalle+"").is(':checked') ) {
                    checks = 'S'; 
                }else{
                    checks = 'N';
                }
              //  alert("#cierre_incidencia__id_detalle"+ele.id_detalle);
                rutinas[i] = new Array(checks,$("#cierre_incidencia__rutina"+ele.id_detalle+"").val(),ele.id_detalle);
               
                i++;
            });
            var error = false;
    var mensaje_error = '';
    var formData = null;

    if(!numero_incidencia) {
      error = true;
      mensaje_error = 'ERROR: No hay una incidencia seleccionada.';
    }
    else if(!fecha_finalizacion || !km_actual || !km_proximo || !taller || !observaciones) {
      error = true;
      mensaje_error = 'ERROR: Diligencie los campos obligatorios.';
    }
    else if(fecha_finalizacion && fecha_finalizacion < fecha_creacion) {
      error = true;
      mensaje_error = 'ERROR: La fecha de cierre no debe ser menor a la fecha de creacion.';
    }

    if(!error) {
      var formData = new FormData();

    console.log(rutinas);
      formData.append("_token", "<?= csrf_token() ?>");
      formData.append("numero_incidencia",   numero_incidencia);
      formData.append("fecha_finalizacion",  fecha_finalizacion);
      formData.append("km_actual",           km_actual);
      formData.append("km_proximo",          km_proximo);
      formData.append("taller",              taller);
      formData.append("observaciones",       observaciones);
      //formData.append("rutinas",              rutinas);
      $.ajax({
        type:         'POST',
        url:          "<?= Request::root() ?>/inidencia/cerrarIncidencia",
        headers:      {'X-CSRF-TOKEN':document.querySelector("#token_datos").value},
        data:         {"rutinas":rutinas,'numero_incidencia':numero_incidencia,'fecha_finalizacion':fecha_finalizacion,'km_actual':km_actual,'km_proximo':km_proximo,'taller':taller,'observaciones':observaciones},
        dataType:     "json",
        success: function(data, textStatus) {
          console.log('server response');
          console.log(data);

          if(!data.error) {
            $('#cierre_incidencia__btn_guardar').text('Cerrar Incidencia').attr('disabled', false);
            alert('Se ha cerrado la incidencia exitosamente.');
            location.reload();
          }
          else {
            $('#cierre_incidencia__btn_guardar').text('Cerrar Incidencia').attr('disabled', false);
            alert(data.message);
          }
        },
        error: function(data) {
          $('#cierre_incidencia__btn_guardar').text('Cerrar Incidencia').attr('disabled', false);
          alert('Error al cerrar la incidencia. Intentelo de nuevo mas tarde.');
        }
      });
    }
    else {
      $('#cierre_incidencia__btn_guardar').text('Cerrar Incidencia').attr('disabled', false);
      alert(mensaje_error);
    }

    return false;
        },
        error: function(data) {
        //  $('#cierre_incidencia__btn_guardar').text('Cerrar Incidencia').attr('disabled', false);
          //alert('Error al cerrar la incidencia. Intentelo de nuevo mas tarde.');
        }
      });
    
      
    //  for (let index = 0; index < array.length; index++) {
    //      const element = array[index];
         
    //  }

    
  }
<?php endif; ?>



<?php if($peredit==1){ ?>

    function abrirModalCerrarIncidencia(event){
        event.preventDefault();
        event.stopPropagation();
        var id_rutina = document.getElementById("id_rutina").value;
        $.ajax({
            type: "POST",
            url: "/rutinaDetalle",
                data:{"id_rutina":id_rutina},
                headers: {'X-CSRF-TOKEN':document.querySelector("#token_datos").value},
            dataType: "json",
            success: function(data,textStatus) { 
                $("#rutinaMantenimiento").html("");
                html1 = "";
                html1 = html1 + "<br>";
                html1 = html1 + "<table style='border-top: 2px solid #ddd;' name='rutinaMantenimiento' id='rutinaMantenimiento' class='table table-striped' style='padding: 10%;'>";
                html1 = html1 + "<thead><th>Acción</th><th>Detalle</th><th></th><th>Observación</th></thead>";
                data.forEach(element => {
                    // console.log(element.nombre);
                    html1 = html1 + "<tr><td width='35%'>"+element.nombre+"</td><td width='35%'>"+element.descripcion+"</td><td width='4%' style='text-align: center;'><input type='checkbox' class='form-control seleccionar' style='width:15px;' data-val='cierre_incidencia__id_detalle' id='cierre_incidencia__id_detalle"+element.id_detalle+"' name='cierre_incidencia__id_detalle' value='"+element.id_detalle+"'></td><td width='40%'><textarea class='form-control' width='32%' rows='2' data-val='cierre_incidencia__rutina' name='cierre_incidencia__rutina' id='cierre_incidencia__rutina"+element.id_detalle+"'></textarea></td></tr>";
                    html1 = html1 + "";
                });
                html1 = html1 + "</table>";
                html1 = html1 + "</div>";
                $("#rutinaMantenimiento").html(html1);

                console.log(data);
            }
        });
       // $("#kmeditas").val($(".inputkmtr").val());
      //  $("#fecha_cierre_ed").val($("#idfechadecierre").val().replace(":00.000", ""));
        $("#modal_cerrar_incidencia_reg").modal('show');
    }


    function abrirModalEdicion(event){
        event.preventDefault();
        event.stopPropagation();
       // $("#kmeditas").val($(".inputkmtr").val());
      //  $("#fecha_cierre_ed").val($("#idfechadecierre").val().replace(":00.000", ""));
        $("#modal_edutar_reg").modal('show');
    }

    function guardaeditreg(event){
        event.preventDefault();
        event.stopPropagation();
        
        var control=0;

        var elementos = $(".datosparaedit .valida_enteros");
        var tam = elementos.length;
        for (var i=0; i<tam; i++) {
            if(valida_enteros(elementos[i])==0){
                   control=1;
            }
        }

        var elementos4 = $(".datosparaedit .valida_texto");
        var tam4 = elementos4.length;
        for (var i=0; i<tam4; i++) {
            if(valida_texto(elementos4[i])==0){
                control=1;
            }
        } 

        if(control==1){return false;}
        
        
        

       var formData = new FormData();
           formData.append("_token", "<?= csrf_token() ?>");
           formData.append("km", $("#kminciden").val());
           formData.append("kmpro", $("#kmeditas").val());
           formData.append("fecha", $("#fecha_cierre_ed").val() );
           formData.append("incidenciaedit", $("#incidenciaedit").val() );
        
       
        $(".datosparaedit .btn-form").hide('slow',function(){
            $(".datosparaedit .loading").show('slow');        
            $.ajax({
                    type: 'POST',
                    url: "<?= Request::root() ?>/inidencia/actuaguarda",
                    dataType: "json",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data,textStatus) {  
                        if(data.status==1){
                            mensajes("Exito","Proceso finalizado satisfactoriamenente",1);
                            
                            $(".inputkmtr").val($("#kminciden").val());
                            $("#idfechadecierre").val($("#fecha_cierre_ed").val().replace(":00.000", "")); 
                            
                        }else{
                            mensajes("Error","Ocurrio un error, intentalo nuevamente mas tarde.\n",0); 
                        }
                    }, 
                    error: function(data) {
                        mensajes("Error","Ocurrio un error, intentalo nuevamente mas tarde.\n",0);  
                    }
            }).always(function() {         
                $(".loading").hide('slow',function(){  $(".btn-form").show('slow');   });
            });  

          });
          
          return false; 
        
        
    }
    
    window.onload=function() {
	$(".form_datetime").datetimepicker({format: 'yyyy-mm-dd hh:ii'});
    }
    

<?php } ?>

</script>
