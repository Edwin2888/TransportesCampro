@extends('template.index')

@section('title')
    Arrendamientos
@stop

@section('title-section')
    Arrendamientos
@stop

@section('css2')
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/css/transporte.css">

    <style type="text/css">
    .selectedCL
    {
        background: #d2e7ff !important;
    }
    </style>
@stop
<!-- http://www.ajaxshake.com/demo/ES/833/453fddb1/mensajes-de-notificacion-estilo-android-con-jquery-toastmessage.html-->

<main>

    <!--@include('proyectos.transporte.costos.conceptos.modal.modalCreateConcepto')-->

    <div class="container">
    
    @include('proyectos.transporte.costos.arrendamiento.modal.modalObservacionDeschequear')


    @if($perfilTransporte == "W")
        @include('proyectos.transporte.costos.arrendamiento.frm.frmFilterArrendamiento')    
    @endif

    
    <div class="rows">
        <div class="col-md-4">
            <button class="btn btn-primary  btn-cam-trans btn-sm" onclick="generar_documentos()" style="    margin-left: 26px;    margin-top: 20px;"><i class="fa fa-check-square-o" aria-hidden="true"></i>   Generar documentos seleccionados</button>

            <br>
            
            <b style="margin-left: 27px;"><input type="checkbox"  onchange="seleccionarTodos(this,2)" id="select_2" data-uso = "0"/> <label for="select_2">Seleccionar todos los documentos para generar</label>
             </b>
        </div>  

        <div class="col-md-4">            
            <?php if($permisoConfirmar == "W"){ ?>
                <button class="btn btn-primary  btn-cam-trans btn-sm" onclick="confirmar_documento()" style="    margin-left: 26px;    margin-top: 20px;"><i class="fa fa-check-square-o" aria-hidden="true"></i>   Confirmar documentos seleccionados</button>

                <br>
                <b style="margin-left: 27px;"> <input type="checkbox"  onchange="seleccionarTodos(this,1)" id="select_1" data-uso = "0"/> <label for="select_1">Seleccionar todos los documentos para confirmar</label></b>
            <?php } ?>
        </div>

        <div class="col-md-4">
            <form action="{{url('/')}}/reporte/transporte/costos/arrendamientos" method="post" style="display:inline-block;">
                <input type="hidden" name="anio" value="{{$anio}}"/>
                <input type="hidden" name="mes" value="{{$mes}}"/>
                <input type="hidden" name="pry" value="{{$pry}}" id="id_pry"/>
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <button class="btn btn-primary  btn-cam-trans btn-sm" onclick="agregarProyecto()" style="    margin-left: 26px;    margin-top: 20px;"><i class="fa fa-print" aria-hidden="true"></i>   Generar exporte</button>
            </form>
        </div>
        <br>
        <div class="col-md-3">
                <form action="{{url('/')}}/reporte/transporte/generarExcelError" method="post" name="formularioExcel">
                    <input type="hidden" name="valorExcel" id='solpedError' />
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <!-- <button class="btn btn-primary  btn-cam-trans btn-sm" onclick="agregarProyecto()" style="    margin-left: 26px;    margin-top: 20px;"><i class="fa fa-print" aria-hidden="true"></i>   Generar exporte</button> -->
                </form>
        </div>
    </div>
    
    <div class="rows">
        .
    </div>
    

    @include('proyectos.transporte.costos.arrendamiento.tables.tblarrendamientoproyecto')

        
        
    </div>
</main>

<script type="text/javascript">
    window.addEventListener('load',ini);

    var table ;
    /* FIN: FUNCIONES PARA PESTAÑAS */
    function ini() {
        $("#arre_proyecto").select2();
        $("#txt_mes").select2();
        $('#tbl_arrendamieno tbody').on( 'click', '.select', function () {
            if ( $(this.parentElement.parentElement).hasClass('selectedCL') ) {
                $(this.parentElement.parentElement).removeClass('selectedCL');
            }
            else {
                $(this.parentElement.parentElement).addClass('selectedCL');
            }
        } );
        
        crearDiasTotal();

        var fauxTable = document.getElementById("faux-table");
          var mainTable = document.getElementById("tbl_arrendamieno");
          var clonedElement = mainTable.cloneNode(true);
          clonedElement.id = "";
          clonedElement.children[0].id = "";
          clonedElement.children[1].id = "";


          fauxTable.appendChild(clonedElement);

        ocultarSincronizacionFondoBlanco();
        /* FIN: FUNCIONES PARA PESTAÑAS */
    }

    function agregarProyecto()
    {
        document.querySelector("#id_pry").value = document.querySelector("#arre_proyecto").value ;
    }
    
    
<?php if($permisoConfirmar == "W"){ ?>
    function confirmar_documento()
    {
        //tabla = $('#tbl_arrendamieno').DataTable();
        var ele = document.querySelector("#tbl_arrendamientos").children;
        var docAr = [];

        for (var i = 0; i < ele.length; i++) {
            if(ele[i].children[0].innerHTML.trim().replace("\n","") != '')
            {
                if(ele[i].children[0].children[0].checked)
                {
                    docAr.push(ele[i].children[0].children[0].dataset.ar);
                }
            }
        };
        

        if(docAr.length == 0)
        {
            mostrarModal(1,null,"Confirmar documentos","No ha seleccionado ningún documento para confirmar",0,"Aceptar","",null); 
            return;
        }
        
        
        if(confirm("¿Seguro que desea confirmar los documentos seleccionados?"))
        {
            var datos = {
              "opc": 7,
              "doc": docAr,
              "pry" : document.querySelector("#arre_proyecto").value,
              "mes" : document.querySelector("#txt_mes").value,
              "anio" : document.querySelector("#txt_anio").value
            };

            consultaAjax("{{url('/')}}/transporte/costos/arrendamiento/saveWebService", datos, 150000, "POST", 1);    
        }

    }

<?php } ?>
    
    
    
    function seleccionarTodos(ele,opc)
    {
        var hijos = document.querySelector("#tbl_arrendamientos").children;

        
        if(opc == 1)
        {
            if(document.querySelector("#select_1").dataset.uso == "0")
            {
                document.querySelector("#select_1").checked =true;
                document.querySelector("#select_1").dataset.uso = "1";
            }
            else
            {
                document.querySelector("#select_1").checked =false;
                document.querySelector("#select_1").dataset.uso = "0";
            }

            var ele = document.querySelector("#select_1");
        }
        else
        {
            if(document.querySelector("#select_2").dataset.uso == "0")
            {
                document.querySelector("#select_2").checked =true;
                document.querySelector("#select_2").dataset.uso = "1";
            }
            else
            {
                document.querySelector("#select_2").checked =false;
                document.querySelector("#select_2").dataset.uso = "0";
            }

            var ele = document.querySelector("#select_2");
        }
        
        if(ele.checked)
        {
            if(opc == 1)
            {
                for (var i = 0; i < hijos.length; i++) {
                    if(hijos[i].children[0].innerHTML.trim().replace("\n","") != "")
                       hijos[i].children[0].children[0].checked = true;
                };
            }
            else
            {
                for (var i = 0; i < hijos.length; i++) {
                    
                    
                    <?php /*if($permisoConfirmar == "W"){ */?>
                    
                        if(hijos[i].children[3].children[0].innerHTML != "RETIRADO" && hijos[i].children[7].innerHTML.trim().replace("\n","") != "$0.00"  && hijos[i].children[19].innerHTML.trim().replace("\n","") != "")
                        hijos[i].children[19].children[0].checked = true;
                    
                    <?php /*}else{ ?> 
                    
                        if(hijos[i].children[2].children[0].innerHTML != "RETIRADO" && hijos[i].children[6].innerHTML.trim().replace("\n","") != "$0.00"  && hijos[i].children[13].innerHTML.trim().replace("\n","") != "")
                        hijos[i].children[13].children[0].checked = true;
                    
                    <?php }*/ ?>
                    
                   
                };
            }
        }
        else
        {
            if(opc == 1)
            {
                for (var i = 0; i < hijos.length; i++) {
                    if(hijos[i].children[0].innerHTML.trim().replace("\n","") != "")
                       hijos[i].children[0].children[0].checked = false;
                };
            }
            else
            {
                for (var i = 0; i < hijos.length; i++) {
                    <?php /*if($permisoConfirmar == "W"){*/ ?>
                    
                    if(hijos[i].children[3].children[0].innerHTML != "RETIRADO" && hijos[i].children[7].innerHTML.trim().replace("\n","") != "$0.00"  && hijos[i].children[19].innerHTML.trim().replace("\n","") != "")
                       hijos[i].children[19].children[0].checked = false;
                    
                    <?php /*}else{ ?> 
                    
                    if(hijos[i].children[2].children[0].innerHTML != "RETIRADO" && hijos[i].children[6].innerHTML.trim().replace("\n","") != "$0.00"  && hijos[i].children[13].innerHTML.trim().replace("\n","") != "")
                       hijos[i].children[13].children[0].checked = false;
                    
                    <?php }*/ ?>
                };
            }
        }
    }

    function consultaAjax(route,datos,tiempoEspera,type,opcion,edita,dato)
    {
        if(dato != -1 && opcion != 3)
            mostrarSincronizacion();

        $.ajax({
            url: route,
            type: type,
            headers: {'X-CSRF-TOKEN':document.querySelector("#token_datos").value},
            dataType: "json",
            data:datos,
            //timeout:tiempoEspera,
            success:function(data)
            {
                if(opcion == 1)//Guardar planilla
                {
                    mostrarModal(2,null,"Confirmar documentos","Se han confirmado exitosamente los documentos seleccionados",0,"Aceptar","",null); 
                    ocultarSincronizacion();
                    setTimeout(function()
                    {
                        location.reload();
                    },700);
                }

                 if(opcion == 2)//Generar planilla
                {
                    console.log(data);
                    
                    if(data=='1'){
                        mostrarModal(2,null,"Generar documentos","Se han generado y actualizado exitosamente los documentos seleccionados",0,"Aceptar","",null); 
                        ocultarSincronizacion();
                        setTimeout(function()
                        {
                            location.reload();
                        },700);
                    }else{
                        mostrarModal(2,null,"Generar documentos","No se han generado todos los documentos con exito, verifica en el excel los documentos no generados",0,"Aceptar","",null); 
                        ocultarSincronizacion();
                        $("#solpedError").val(data);
                        document.formularioExcel.submit()
                        setTimeout(function()
                        {
                            location.reload();
                        },700);
                    }
                }

                if(opcion == 3)//Actualiza días
                {
                    mostrarNotificacion(2,"Documento: " + datos.documento + " - Día: " + datos.dia + " Mes: " + datos.mes + " actualizado correctamente");
                    setTimeout(function()
                        {
                            document.getElementById("consultar").click();
                        },700);
                }

                //
            },
            error:function(request,status,error){
                ocultarSincronizacion();
                //$('#filter_registro').modal('toggle');

                mostrarModal(1,null,"Problema con la conexión a internet","Existen problemas con la conexión a internet, comuníquese con la persona encargada del manejo de la red.\n",0,"Aceptar","",null);
                setTimeout(function()
                {
                    //location.reload();
                },3000);
            }

        });
    }

    var elementoGuardarObservacion = null;
    function cambioCheck(ele)
    {
        if(ele.dataset.tipo == "2" ) //No son incidencias
        {
            if(ele.dataset.estado == "1")
            {
                elementoGuardarObservacion = ele;
                $("#modal_deschequear_dia").modal("toggle"); $("#soporteobserdia").val("");
                document.querySelector("#txtObserDia").value = "";
                ele.dataset.obser = "";
            }
            else
            {
                ele.dataset.estado = "1";
                ele.parentElement.title = "ACTIVO";
                ele.parentElement.style.background = "#dbfedb";
                ele.parentElement.children[0].src = "{{url('/')}}/img/checked.png";
                ele.parentElement.children[0].onclick = function() { veradjunto(""); };
                ele.dataset.obser = "";
                crearDiasTotal();

                //Guardar Día - Mes - AR - Obser
                var documentoAr = ele.parentElement.parentElement.children[8].children[0].innerHTML;

                if (documentoAr.indexOf('AR0') !=-1) {
                    var datos = {
                      "opc": 9,
                      "dia": ele.dataset.dia,
                      "mes" : ele.dataset.mes,
                      "anio" : ele.dataset.anio,
                      "documento" : documentoAr,
                      "obser" : "",
                      "check" : "1"
                    };

                    consultaAjax("{{url('/')}}/transporte/costos/arrendamiento/saveWebService", datos, 1950000, "POST", 3);    

                }

            }
        }
        else //Son incidencias
        {
            if(ele.dataset.estado == "1")
            {
                elementoGuardarObservacion = ele;
                $("#modal_deschequear_dia").modal("toggle"); $("#soporteobserdia").val("");
                document.querySelector("#txtObserDia").value = "";
                ele.dataset.obser = "";
                //Abrir Modal
            }
            else
            {
                ele.dataset.estado = "1";
                ele.dataset.obser = "";
                ele.parentElement.title = ele.parentElement.title.split("OBSERVACIÓN")[0];
                //Eliminar modal
                crearDiasTotal();

                //Guardar Día - Mes - AR - Obser
                var documentoAr = ele.parentElement.parentElement.children[13].children[0].innerHTML;

                if (documentoAr.indexOf('AR0') !=-1) {
                    var datos = {
                      "opc": 9,
                      "dia": ele.dataset.dia,
                      "mes" : ele.dataset.mes,
                      "anio" : ele.dataset.anio,
                      "documento" : documentoAr,
                      "obser" : "",
                      "check" : "1"
                    };

                    consultaAjax("{{url('/')}}/transporte/costos/arrendamiento/saveWebService", datos, 1950000, "POST", 3);    
                }

            }
        }
    }

    function cancelarDia()
    {
        elementoGuardarObservacion.checked = true;
        $("#modal_deschequear_dia").modal("toggle"); $("#soporteobserdia").val("");
    }

    function guardarObservacionDia()
    {
        
        
        if(document.querySelector("#txtObserDia").value == "")
        {
            mostrarModal(1,null,"Guardar observación","No se ha ingresado la observación",0,"Aceptar","",null); 
            return;
        }
        

        //Guardar Día - Mes - AR - Obser
        /*
        var num =7;
        <?php if($permisoConfirmar == "W"){ ?>
                num =8;
        <?php } ?>*/
         var num =8;
        
        var documentoAr = elementoGuardarObservacion.parentElement.parentElement.children[num].children[0].innerHTML;




        if (documentoAr.indexOf('AR0') !=-1) {
            /*
            var datos = {
              "opc": 9,
              "dia": elementoGuardarObservacion.dataset.dia,
              "mes" : elementoGuardarObservacion.dataset.mes,
              "anio" : elementoGuardarObservacion.dataset.anio,
              "documento" : documentoAr,
              "obser" : document.querySelector("#txtObserDia").value,
              "check" : "0"
            };
            consultaAjax("{{url('/')}}/transporte/costos/arrendamiento/saveWebService", datos, 1950000, "POST", 3);    
            */            

            var formData = new FormData();
                formData.append("_token", "<?= csrf_token() ?>");
                formData.append("opc",9);
                formData.append("dia",elementoGuardarObservacion.dataset.dia);
                formData.append("mes",elementoGuardarObservacion.dataset.mes);
                formData.append("anio",elementoGuardarObservacion.dataset.anio);
                formData.append("documento",documentoAr);
                formData.append("obser",document.querySelector("#txtObserDia").value);
                formData.append("check","0");
                formData.append("file",$("#soporteobserdia")[0].files[0]);

            $.ajax({
                    type: 'POST',
                    url: "<?= Request::root() ?>/transporte/costos/arrendamiento/saveWebService",
                    dataType: "json",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data,textStatus) {  
                        if(data!=1){
                           // console.log("tiene imagen "+data);
                            elementoGuardarObservacion.parentElement.children[0].onclick = function() { veradjunto(data); };
                        }
                        
                         mostrarNotificacion(2,"Documento: " + documentoAr + " - Día: " + elementoGuardarObservacion.dataset.dia + " Mes: " + elementoGuardarObservacion.dataset.mes + " actualizado correctamente");
                    },
                    error:function(request,status,error){
                        ocultarSincronizacion();
                        mostrarModal(1,null,"Problema con la conexión a internet","Existen problemas con la conexión a internet, comuníquese con la persona encargada del manejo de la red.\n",0,"Aceptar","",null);
                        setTimeout(function()  {  /*location.reload();*/  },3000);
                    }
            }).always(function() {   }); 
            
            
        }else{
            mostrarModal(1,null,"Guardar observación","Error, primero se debe generar un documento.",0,"Aceptar","",null); 
            return;
        }
        



        if(elementoGuardarObservacion.dataset.tipo == "2" ) //No son incidencias
        {
            if(elementoGuardarObservacion.dataset.estado == "1")
            {
                elementoGuardarObservacion.dataset.estado = "2";
                elementoGuardarObservacion.dataset.obser = document.querySelector("#txtObserDia").value;
                elementoGuardarObservacion.parentElement.title = "INACTIVO";
                elementoGuardarObservacion.parentElement.title = elementoGuardarObservacion.parentElement.title + "\n OBSERVACIÓN: " + document.querySelector("#txtObserDia").value;
                elementoGuardarObservacion.parentElement.style.background = "#f8d9d9";
                elementoGuardarObservacion.parentElement.children[0].src = "{{url('/')}}/img/exclamation-mark.png";               
                $("#modal_deschequear_dia").modal("toggle"); $("#soporteobserdia").val("");
                crearDiasTotal();
            }
        }
        else //Son incidencias
        {
            if(elementoGuardarObservacion.dataset.estado == "1")
            {
                elementoGuardarObservacion.dataset.estado = "2";
                elementoGuardarObservacion.dataset.obser = document.querySelector("#txtObserDia").value;
                elementoGuardarObservacion.parentElement.title = elementoGuardarObservacion.parentElement.title + "\n OBSERVACIÓN: " + document.querySelector("#txtObserDia").value;
                $("#modal_deschequear_dia").modal("toggle"); $("#soporteobserdia").val("");
                crearDiasTotal();
            }
        }

    }


    function generar_documentos()
    {
        var ele = document.querySelector("#tbl_arrendamientos").children;
        
        var arregloVehiculos = Array();

        var cabeza = document.querySelector("#cabezatable");

        for (var i = 0; i < ele.length; i++) {
            
                
        <?php /* if($permisoConfirmar == "W"){ */ ?>
                    console.log("permi w ");
                     console.log(" permi w !"+ele[i].children[3].children[0].innerHTML +"!"+ele[i].children[7].innerHTML.trim().replace("\n","")+"!"+ele[i].children[18].innerHTML.trim().replace("\n","")+"!");
                   
                    if(ele[i].children[3].children[0].innerHTML != "RETIRAD.children[0]O" && ele[i].children[7].innerHTML.trim().replace("\n","") != "$0.00")
            {
                    console.log("permi  w entro");
                    console.log(ele[i].children[19].children[0]);
            if(ele[i].children[19].children[0] != undefined){
            
            console.log(ele[i].children[19].children[0]); 
                          if(ele[i].children[19].children[0].checked){
                              var resta=20;
        <?php /* }else{  ?>
                    
                    console.log("sin permi w !"+ele[i].children[2].children[0].innerHTML +"!"+ele[i].children[6].innerHTML.trim().replace("\n","")+"!"+ele[i].children[12].innerHTML.trim().replace("\n","")+"!");
                    if(ele[i].children[2].children[0].innerHTML != "RETIRADO" && ele[i].children[6].innerHTML.trim().replace("\n","") != "$0.00"  && ele[i].children[12].innerHTML.trim().replace("\n","") != "")
            {
                    console.log("sin permi  w entro");
                   if(ele[i].children[13].children[0].checked){
                              var resta=14;
        <?php  }*/ ?>

              //  if(ele[i].children[19].children[0].checked)
             //   {////////////
                    var hijosTotales = ele[i].children.length - resta;
                    var ini = resta;
                    var hijoArreglo = Array();
                    for (var j = 0; j < hijosTotales; j++) {
                        if(ele[i].children[ini].innerHTML != "")
                        {
                            hijoArreglo.push(
                                {
                                    'dia' : cabeza.children[0].children[ini].dataset.dia,
                                    'mes' : cabeza.children[0].children[ini].dataset.mes,
                                    'anio' : cabeza.children[0].children[ini].dataset.anio,
                                    'check' : (ele[i].children[ini].children[1].checked ? "1" : "0"),
                                    'obser' : (ele[i].children[ini].children[1].dataset.obser ? ele[i].children[ini].children[1].dataset.obser : ""),
                                });
                        }
                        ini++;
                    };

                    arregloVehiculos.push(
                    {   
                        'pry' : '{{$pry}}',
                        'mes' : '{{$mes}}',
                        'anio' : '{{$anio}}',
                        
        <?php /*if($permisoConfirmar == "W"){*/ ?>
                        'placa' : ele[i].children[2].innerHTML.trim().replace("\n",""),
                        'documento' : (ele[i].children[19].children[0].dataset.ar == '' || ele[i].children[19].children[0].dataset.ar == null || ele[i].children[19].children[0].dataset.ar == undefined ? 'A' : ele[i].children[19].children[0].dataset.ar),
        <?php /*}else{?> 
        
                        'placa' : ele[i].children[1].innerHTML.trim().replace("\n",""),
                        'documento' : (ele[i].children[13].children[0].dataset.ar == '' || ele[i].children[13].children[0].dataset.ar == null || ele[i].children[13].children[0].dataset.ar == undefined ? 'A' : ele[i].children[13].children[0].dataset.ar),
        
        
        <?php } */ ?> 
            
            'dias' : hijoArreglo
                    });
                }
            }
            }
        };

        if(arregloVehiculos.length == 0)
        {
            mostrarModal(1,null,"Generar documentos","No ha seleccionado ningún vehículo para generar documentos",0,"Aceptar","",null); 
            return;
        }

        if(confirm("¿Seguro que desea generar o actualizar los documentos de los vehículos seleccionados?"))
        {
            var datos = {
              "opc": 8,
              "data": arregloVehiculos,
              "pry" : document.querySelector("#arre_proyecto").value,
              "mes" : document.querySelector("#txt_mes").value,
              "anio" : document.querySelector("#txt_anio").value
            };

            consultaAjax("{{url('/')}}/transporte/costos/arrendamiento/saveWebService", datos, 1950000, "POST", 2);    
        }
    }

    function generaDocumentoIndividual(ele)
    {
        var arregloVehiculos = Array();

        var cabeza = document.querySelector("#cabezatable");

        <?php /*if($permisoConfirmar == "W"){*/ ?>
        var hijosTotales = ele.parentElement.parentElement.children.length - 20;
        var ini = 20;
        <?php /*}else{ ?> 
        var hijosTotales = ele.parentElement.parentElement.children.length - 14;
        var ini = 14;
        <?php }*/ ?>
        var hijoArreglo = Array();
        for (var j = 0; j < hijosTotales; j++) {
            if(ele.parentElement.parentElement.children[ini].innerHTML != "")
            {
                hijoArreglo.push(
                    {
                        'dia' : cabeza.children[0].children[ini].dataset.dia,
                        'mes' : cabeza.children[0].children[ini].dataset.mes,
                        'anio' : cabeza.children[0].children[ini].dataset.anio,
                        'check' : (ele.parentElement.parentElement.children[ini].children[1].checked ? "1" : "0"),
                        'obser' : (ele.parentElement.parentElement.children[ini].children[1].dataset.obser ? ele.parentElement.parentElement.children[ini].children[1].dataset.obser : ""),
                    });
            }
            ini++;
        };

        arregloVehiculos.push(
        {   
            'pry' : '{{$pry}}',
            'mes' : '{{$mes}}',
            'anio' : '{{$anio}}',
            'placa' : ele.dataset.placa.trim().replace("\n",""),
            'documento' : ele.dataset.doc.trim().replace("\n",""),
            'dias' : hijoArreglo
        });

       // alert(JSON.stringify(arregloVehiculos));
        //return;
        if(confirm("¿Seguro que desea generar o actualizar los documentos de los vehículos seleccionados?"))
        {
            var datos = {
              "opc": 8,
              "data": arregloVehiculos,
              "pry" : document.querySelector("#arre_proyecto").value,
              "mes" : document.querySelector("#txt_mes").value,
              "anio" : document.querySelector("#txt_anio").value
            };

            consultaAjax("{{url('/')}}/transporte/costos/arrendamiento/saveWebService", datos, 1950000, "POST", 2);    
        }
    }


    //Función encargada de colocar los datosde cantidad de días  y total a pagar, si no se ha colocado
    function crearDiasTotal()
    {
        var hijos = document.querySelector("#tbl_arrendamientos").children;
        var totalPagarFinal = 0;

        var arregloTipoVehiculo = [];
        var arregloDineroTipoVehiculo = [];
        var arreglototal = [];

        for (var i = 0; i < hijos.length; i++) {
            var totalRecorreo = hijos[i].children.length ;//- 14;
            var ini = 20;
            var dias = 0;
            var canon = hijos[i].children[7].innerHTML.trim().replace("\n","").replace("$","").replace(",","").replace(".00","");
            var docFechaGenerado = hijos[i].children[15].innerHTML.trim().replace("\n","");
            var totalPagar = 0;
            var tipoVehiculo = hijos[i].children[4].innerHTML.trim().replace("\n","");
            canon = parseInt(canon.replace(",",""));
            

            if(canon != 0)//No se han generado documentos AR y no tenga CANON = 0
            {
                //Recorrer todas las columnas
                //console.log("ini "+ini+" totalRecorreo" +totalRecorreo+" dias"+dias)
                for (var j = ini; j < totalRecorreo; j++) 
                {
                    if(hijos[i].children[j].innerHTML.trim().replace("\n","") == "")
                        continue;

                    if(hijos[i].children[j].children[1].checked)
                        dias++;
                };
                
                //console.log(" dias"+dias)
                if(dias>30){dias=30;}
               // dias=30;
                
                
                var descuentos =0;
                try {
                    descuentos=$(hijos[i]).find("#suma_descuentos_"+hijos[i].children[2].innerHTML.trim()).val();
                }
                catch(err) {
                }
                
                
                
                /////////////////////////////////
                
                var anoi = $("#txt_anio").val();
                var mesi = $("#txt_mes").val();
                mesi--;
                
                var fechaini =  new Date(anoi,mesi,25);
                
                var mesf = (mesi-1);
                var anof = anoi;
                if(mesi==1){
                    mesf=12;
                    anof=(anoi-1);
                }
                
                var fechafin =  new Date(anof,mesf,26);
                
                
                var fechaInicio = fechaini.getTime();
                var fechaFinal    = fechafin.getTime();
                var diff = (fechaInicio-fechaFinal);
                var diasdif = parseInt( ((diff/(1000*60*60*24))+1) );//mas uno por que se del 25 a las 00:00:00 al 26 del seguienete mes a las 00:00:00 por lo que el 26 no contaria
                
                
                console.log("fecha 1 ==>"+fechafin );
                console.log("fecha 2 ==>"+fechaini );
                console.log("dias ==>"+diasdif );
                
                
                totalPagar = (canon / 30) *  dias;
                
                if(totalPagar>canon){
                    totalPagar=canon;
                }
                
                
                //totalPagar = (canon / diasdif) *  dias;
              //  console.log("total  "+totalPagard );
                totalPagar = totalPagar-descuentos;
                totalPagarFinal = totalPagarFinal + totalPagar;
                
               // $(hijos[i]).find("#suma_descuentos_"+hijos[i].children[2].innerHTML.trim()).val();
               // console.log("#suma_descuentos_"+hijos[i].children[2].innerHTML.trim()+"  |"+descuentos+"|");
                
                //hijos[i].children[11].innerHTML = dias;
                //hijos[i].children[13].innerHTML = "$ " + number_format(totalPagar,2);
                
                var exis = 0;
                var letra = 0;
                for (var k = 0; k < arregloTipoVehiculo.length; k++) {
                    if(arregloTipoVehiculo[k] == tipoVehiculo){
                        exis = 1;
                        letra = k;
                        break;
                    }
                }

                if(exis == 0)
                {
                    arregloTipoVehiculo.push(tipoVehiculo);
                    arregloDineroTipoVehiculo.push(totalPagar);
                    arreglototal.push(1);
                }
                else
                {
                    //Ya existe Tipo vehículo
                    arregloDineroTipoVehiculo[letra] = parseInt(arregloDineroTipoVehiculo[letra]) + totalPagar;
                    arreglototal[letra] = parseInt(arreglototal[letra]) + 1;
                }

                
                
                //Leer por tipos de vehículo
            }
        };

        document.querySelector("#total_pagar_final").innerHTML  = "$ " + number_format(totalPagarFinal,2);

        //Mostrar nueva tabla
        var html = "";
        for (var i = 0; i < arregloTipoVehiculo.length; i++) 
        {
            html += "<tr>";
                html += "<td>" + arregloTipoVehiculo[i] +  "</td>";
                html += "<td>" + arreglototal[i] +  "</td>";
                html += "<td>" + "$ " + number_format(arregloDineroTipoVehiculo[i],2) +  "</td>";
            html += "</tr>";
        };

        document.querySelector("#tbody_detalle_resumen").innerHTML = html;

       // crearDiasTotal();
       cargaresumecategoria()
    }
    
</script>  

<form id='formulariodescuentos'>
    <input type="hidden" id='placa_descuentos' value="">
    <input type="hidden" id='ano_descuentos' value="">
    <input type="hidden" id='mes_descuentos' value="">
</form>

<input type="hidden" id='id_log_consulta' value="">
<style type="text/css">
    
    .colorcampro,.colorcampro > div{
        background-color: #99c7ea;
    }
    .btnborraf{
        font-size: 21px;
        /* font-weight: bold; */
        color: #ffffff;
        background-color: blue;
        padding: 6px;
        border-radius: 6px;
    }
    
</style>


    @include('proyectos.transporte.costos.arrendamiento.modal.modalDescuentoArrendamiento')
<script type="text/javascript">

    var tabladescuento=null;
    var tabladescuentolog=null;
    
    function ver_costos(event,elemento,placa){
          event.stopPropagation();
          event.preventDefault();
          
          
          $("#placa_descuentos").val(placa);
          $("#mes_descuentos").val($("#txt_mes" ).val());
          $("#ano_descuentos").val($("#txt_anio").val());
         // $(elemento).parent().find(".valor").html("siclas");
          tabladescuento.ajax.reload();
          $("#modal_descuentos_arrendamieno").modal('show');
          
    }
    
    window.onload = function() {
    
    
    tabladescuento=$('#tabla_descuentos').DataTable( {
                      dom: 'T <"clear">lfrtip', 
                      
                      tableTools: {
                          "sSwfPath": "../media/swf/copy_csv_xls_pdf.swf",
                          "aButtons": ["copy", "xls"]
                      },

                    initComplete: function () {},
                    "scrollX": true,
                    scrollY: '200px',
                    "processing": true,
                    "language": 
                                {          
                                //"processing": "<img style='width:300px; height:250px;' src='{{config('app.Campro')[2]}}/campro/recursos/img/tenor.gif' />",
                                },
                    "serverSide": true,
                    "aLengthMenu": [[10, 15, 25, 35, 50, 100, 6000], [10, 15, 25, 35, 50, 100, 6000]],
                    "ajax": {
                        "url": "<?= Request::root() ?>/descuentos/arrendamiento",
                        "type": "POST",
                        "async": "true",
                        "data": function (d) {
                            d.placa=$("#placa_descuentos").val();
                            d.ano=$("#ano_descuentos").val();
                            d.mes=$("#mes_descuentos").val();
                            d._token='<?= csrf_token() ?>';
                            d.dir='<?= Request::root() ?>';
                        },
                        "complete": function (response) {
                            //$("#genSpiner").removeClass("fa fa-refresh fa-spin");
                            //console.log("Data Recibida:", response)
                        }
                    },
                    "columns": [
                        {"name": "datou",'orderable':false,"searchable":true},
                        {"name": "datod",'orderable':true,"searchable":false},
                        {"name": "datot",'orderable':true,"searchable":true},
                        {"name": "datouc",'orderable':true,"searchable":true},
                        {"name": "adjunto",'orderable':false,"searchable":true},
                        {"name": "datouci",'orderable':false,"searchable":true}
                    ]
            }); 
            
    
    
    tabladescuentolog=$('#tabla_descuentos_lod').DataTable( {
                      dom: 'T <"clear">lfrtip', 
                      
                      tableTools: {
                          "sSwfPath": "../media/swf/copy_csv_xls_pdf.swf",
                          "aButtons": ["copy", "xls"]
                      },

                    initComplete: function () {},
                    "scrollX": true,
                    scrollY: '200px',
                    "processing": true,
                    "language": 
                                {          
                                //"processing": "<img style='width:300px; height:250px;' src='{{config('app.Campro')[2]}}/campro/recursos/img/tenor.gif' />",
                                },
                    "serverSide": true,
                    "aLengthMenu": [[10, 15, 25, 35, 50, 100, 6000], [10, 15, 25, 35, 50, 100, 6000]],
                    "ajax": {
                        "url": "<?= Request::root() ?>/descuentos/arrendamientolos",
                        "type": "POST",
                        "async": "true",
                        "data": function (d) {
                            d.placa=$("#placa_descuentos").val();
                            d.ano=$("#ano_descuentos").val();
                            d.mes=$("#mes_descuentos").val();
                            d._token='<?= csrf_token() ?>';
                            d.id=$("#id_log_consulta").val();
                        },
                        "complete": function (response) {
                            //$("#genSpiner").removeClass("fa fa-refresh fa-spin");
                            //console.log("Data Recibida:", response)
                        }
                    },
                    "columns": [
                        {"name": "datou",'orderable':true,"searchable":true},
                        {"name": "datod",'orderable':true,"searchable":false},
                        {"name": "datot",'orderable':true,"searchable":true},
                    ]
            });         
            
            
  };
  
  function editadescuento(event,elemento,id,valor,id_concepto,ano,mes){
      event.stopPropagation();
      event.preventDefault();
      
      abrirnuevoedit(id,id_concepto,valor,ano,mes);
  
  }
  
  function eliminadescuento(event,elemento,id,placa){
        event.stopPropagation();
        event.preventDefault();
        
        
        //////////////////////////
        
             
        new PNotify({
          title: 'Confirmacion',
          text: 'Esta seguro de eliminar el elemento seleccionado?',
          icon: 'glyphicon glyphicon-question-sign',
          hide: false,
          addclass: 'stack-modal colorcampro',
          stack: {'dir1': 'down', 'dir2': 'right', 'modal': true},
          confirm: {
            confirm: true,
            buttons: [
              {
                text: 'Aceptar',
                //addClass: 'btn-primary',
                click: function(){
                                        
                        $(elemento).hide('slow',function(){
                        $(elemento).parent().find(".loadingd").show('slow');        
                        $.ajax({
                                type: 'POST',
                                url: "<?= Request::root() ?>/descuentos/borrar",
                                dataType: "json",
                                data: {                
                                        _token:'<?= csrf_token() ?>',   
                                        id:id
                                    },
                                dataType: "json",
                                success: function(data,textStatus) {  PNotify.removeAll();
                                        if(data.result==1){
                                            mensajes("Exito","Proceso finalizado satisfactoriamenente",1);
                                            recargar();
                                            sumar(placa);
                                        }else{
                                            mensajes("Error","Ocurrio un error, intentalo nuevamente mas tarde.\n",0); 
                                        }
                                    }, 
                                    error: function(data) { PNotify.removeAll();
                                        mensajes("Error","Ocurrio un error, intentalo nuevamente mas tarde.\n",0);  
                                    }
                            }).always(function() {  
                                $(elemento).parent().find(".loadingd").hide('slow',function(){ $(elemento).show('slow');   });
                            });  

                          });
                             
                }
              },
              {
                text: 'Cancelar',
                click: function(){
                   PNotify.removeAll();
                }
              }
            ]
          },
          buttons: {
            closer: false,
            sticker: false
          },
          history: {
            history: false
          }
        });
        
        /////////////////////////
        
  
  
  }
 
  function nuevodescu(){
      $("#id_descuento").val(0);
      $("#id_concepto_decuento").val(0);
      $("#valor_cescuento").val("");
      $("#id_ano_decuento").val(0);
      $("#id_mes_decuento").val(0);
      $("#id_concepto_decuento_val").val("");
      $("#valor_cescuento_val").val("");
      $("#id_ano_decuento_val").val("");
      $("#id_mes_decuento_val").val("");
      $("#modal_crear_edita_descuentos_arrendamieno").modal('show');
      
  }
  
  function abrirnuevoedit(id_descuento,id_concepto_decuento,valor_cescuento,id_ano_decuento,id_mes_decuento){
      $("#id_descuento").val(id_descuento);
      $("#id_concepto_decuento").val(id_concepto_decuento);
      $("#valor_cescuento").val(valor_cescuento);
      $("#id_ano_decuento").val(id_ano_decuento);
      $("#id_mes_decuento").val(id_mes_decuento);
      $("#id_concepto_decuento_val").val("");
      $("#valor_cescuento_val").val("");
      $("#id_ano_decuento_val").val("");
      $("#id_mes_decuento_val").val("");
      $("#modal_crear_edita_descuentos_arrendamieno").modal('show');
  }
  
  
  
    function formulario(event){
            event.stopPropagation();
            event.preventDefault();


           var control=0;

           var elementos = $("#formcreaedita .valida_enteros");
           var tam = elementos.length;
           for (var i=0; i<tam; i++) {
               if(valida_texto(elementos[i])==0){
                   control=1;
               }
           }

           var elementos4 = $("#formcreaedita .valida_select");
           var tam4 = elementos4.length;
           for (var i=0; i<tam4; i++) {
               if(valida_select(elementos4[i])==0){
                   control=1;
               }
           } 

           if(control==1){return false;}


        var id_descuento = $("#id_descuento").val();
        var id_concepto_decuento = $("#id_concepto_decuento").val();
        var valor_descuento = $("#valor_cescuento").val();
        var id_ano_decuento = $("#id_ano_decuento").val();
        var id_mes_decuento = $("#id_mes_decuento").val();
        var placa = $("#placa_descuentos").val();


       var formData = new FormData();
           formData.append("_token", "<?= csrf_token() ?>");
           formData.append("id_descuento",id_descuento);
           formData.append("id_concepto_decuento",id_concepto_decuento);
           formData.append("valor_descuento",valor_descuento);
           formData.append("id_ano_decuento",id_ano_decuento);
           formData.append("id_mes_decuento",id_mes_decuento);
           formData.append("placa",placa);
           formData.append("file",$("#adjunto_cescuento")[0].files[0]);
         

        $(".btn-form").hide('slow',function(){
            $(".loading").show('slow');        
            $.ajax({
                    type: 'POST',
                    url: "<?= Request::root() ?>/descuentos/guardaedita",
                    dataType: "json",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
               /*     data: {                
                        _token:'<?= csrf_token() ?>',                       
                        id_descuento:id_descuento,
                        id_concepto_decuento:id_concepto_decuento,
                        valor_descuento:valor_descuento,
                        id_ano_decuento:id_ano_decuento,
                        id_mes_decuento:id_mes_decuento,
                        placa:placa
                    },
                    dataType: "json",*/
                    success: function(data,textStatus) {  
                        if(data.status==1){
                            mensajes("Exito","Proceso finalizado satisfactoriamenente",1);
                            if(id_descuento==0){                                
                                    $("#id_descuento").val(0);
                                    $("#id_concepto_decuento").val(0);
                                    $("#valor_cescuento").val("");
                                    $("#id_ano_decuento").val(0);
                                    $("#id_mes_decuento").val(0);
                                    $("#id_concepto_decuento_val").val("");
                                    $("#valor_cescuento_val").val("");
                                    $("#id_ano_decuento_val").val("");
                                    $("#id_mes_decuento_val").val("");
                            }
                            $("#adjunto_cescuento").val("");
                            recargar();
                            sumar(placa);
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
    
  
    
    
    function recargar(){
        tabladescuento.ajax.reload();
    }
    
    function logdescuento(event,elemento,id){
        
            event.stopPropagation();
            event.preventDefault();

        
            $("#id_log_consulta").val(id);
            $("#placa_log_m").html($("#placa_descuentos").val());
            
            tabladescuentolog.ajax.reload();
            $("#modal_descuentos_arrendamieno_log").modal('show');
          
        //tabla_descuentos_lod
    }
    
    
    function sumar(placa){
        
        var id_ano_decuento = $("#txt_anio").val();
        var id_mes_decuento = $("#txt_mes").val();
       // var placa = $("#placa_descuentos").val();
          $.ajax({
                    type: 'POST',
                    url: "<?= Request::root() ?>/descuentos/suma",
                    dataType: "json",
                    data: {                
                        _token:'<?= csrf_token() ?>',   
                        ano:id_ano_decuento,
                        mes:id_mes_decuento,
                        placa:placa
                    },
                    dataType: "json",
                    success: function(data,textStatus) {  
                        if(data.cantidad>=0){
                           // console.log("la suma es "+data.cantidad);
                            $(".table-wrap #suma_descuentos_txt_"+placa).html(data.cantidadd);
                            $(".table-wrap #suma_descuentos_"+placa).val(data.cantidad);
                            $(".faux-table #suma_descuentos_txt_"+placa).html(data.cantidadd);
                            $(".faux-table #suma_descuentos_"+placa).val(data.cantidad);
                            
                            setTimeout(function(){
                                crearDiasTotal();
                            },1000);
                            
                            
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
        
    }
    
    
    function cargaresumecategoria(){
      
    
    
        var id_ano_decuento = $("#txt_anio").val();
        var id_mes_decuento = $("#txt_mes").val();
        var id_area = $("#arre_proyecto").val();
        
        
        var anoi = $("#txt_anio").val();
        var mesi = $("#txt_mes").val();
        mesi--;
        var fechaini =  new Date(anoi,mesi,25);

        var mesf = (mesi-1);
        var anof = anoi;
        if(mesi==1){
            mesf=12;
            anof=(anoi-1);
        }
        var fechafin =  new Date(anof,mesf,26);

        var fechaInicio = fechaini.getTime();
        var fechaFinal    = fechafin.getTime();
        var diff = (fechaInicio-fechaFinal);
        var diasdif = parseInt( ((diff/(1000*60*60*24))+1) );//mas uno por que se del 25 a las 00:00:00 al 26 del seguienete mes a las 00:00:00 por lo que el 26 no contaria
                
       // var placa = $("#placa_descuentos").val();
          $.ajax({
                    type: 'POST',
                    url: "<?= Request::root() ?>/descuentos/listadocategoria",
                    dataType: "json",
                    data: {                
                        _token:'<?= csrf_token() ?>',   
                        ano:id_ano_decuento,
                        mes:id_mes_decuento,
                        id_area:id_area,
                        dias:diasdif
                    },
                    dataType: "json",
                    success: function(data,textStatus) {  
                        if( data.status ==1 ){
                           // console.log("datod "+data.datos);
                              //Mostrar nueva tabla
                            var html = "";
                            var totalu=0;
                            var totald=0;
                            var totalt=0;
                            
                            for (var i = 0; i < data.datos.length; i++) 
                            {
                                html += "<tr>";
                                    html += "<td>" + data.datos[i]['tipo_vinculo'] +  "</td>";
                                    html += "<td>" + data.datos[i]['cantidad'] +  "</td>";
                                    html += "<td>" + "$ " + number_format(data.datos[i]['total_pagar'],2) +  "</td>";
                                    html += "<td>" + "$ " + number_format(data.datos[i]['descuentos'],2) +  "</td>";
                                    html += "<td>" + "$ " + number_format(data.datos[i]['pagarreal'],2) +  "</td>";
                                html += "</tr>";
                                
                                totalu=totalu+parseFloat(data.datos[i]['total_pagar']);
                                totald=totald+parseFloat(data.datos[i]['descuentos']);
                                totalt=totalt+parseFloat(data.datos[i]['pagarreal']);
                                
                            };
                            
                            if( totalu>0 || totald>0 || totalt>0 ){                               
                                html += "<tr>";
                                    html += "<td></td>";
                                    html += "<td> Total </td>";
                                    html += "<td>" + "$ " + number_format(totalu,2) +  "</td>";
                                    html += "<td>" + "$ " + number_format(totald,2) +  "</td>";
                                    html += "<td>" + "$ " + number_format(totalt,2) +  "</td>";
                                html += "</tr>"; 
                            }

                            document.querySelector("#tbody_detalle_resumen_vinculacion").innerHTML = html;
                            
                        }else{
                          //  mensajes("Error","Ocurrio un error, intentalo nuevamente mas tarde.\n",0); 
                        }
                    }, 
                    error: function(data) {
                     //   mensajes("Error","Ocurrio un error, intentalo nuevamente mas tarde.\n",0);  
                    }
            }).always(function() {         
            });  
        
    }
    
    
    function veradjunto(adjunto){
    
        if( adjunto.trim() != '' ){
            var adju = "http://190.60.248.195/anexos_apa/arrendamientos/"+adjunto;
            var a = document.createElement("a");
                a.target = "_blank";
                a.href = adju;
                a.click();
        }
    }
    
</script>