@extends('template.index')

@section('title')
    Transporte
@stop

@section('title-section')
    Transporte
@stop

@section('css2')
    <link rel="stylesheet" type="text/css" href="../../css/transporte.css">
@stop
<!-- http://www.ajaxshake.com/demo/ES/833/453fddb1/mensajes-de-notificacion-estilo-android-con-jquery-toastmessage.html-->

<main>

<?php 
    $servidor = "http://localhost:8000";
    $servidor = config("app.server_transportes");
    //TRY123
?>

@include('proyectos.transporte.modal.modalCiudad')
@include('proyectos.transporte.modal.modalTipo')
@include('proyectos.transporte.modal.modalMarca')
@include('proyectos.transporte.modal.modalPropietarioNombre')
@include('proyectos.transporte.modal.modalProvMonitoreo')
@include('proyectos.transporte.modal.modalClienteProyecto')
@include('proyectos.transporte.modal.modalConductorVehiculo')
@include('proyectos.transporte.modal.modalPrimerMantenimiento')
@include('proyectos.transporte.modal.modalCargaOdometer')
@include('proyectos.transporte.modal.modalNovedadCambioProyecto')
@include('proyectos.transporte.modal.modalNovedadCambioEstado')
@include('proyectos.transporte.modal.modalLogVehiculo')



<div class="container">
    <div class="row">
        <section>
            <div style="margin-top:12px;margin-left:10px;" id="opciones_encabezado">
                <button data-toggle="collapse" data-target="#filter" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" onclick="nuevodocumento();">
                    <i class="fa fa-file-o"></i> &nbsp;&nbsp;Nuevo registro
                </button>

                <a href="../../transversal/transporte/filterVehiculos"  class="btn btn-primary btn-cam-trans btn-sm"><i class="fa fa-search"></i> &nbsp;&nbsp; Consultar vehículos</a>
                <a href="../../transversal/documento/"  class="btn btn-primary btn-cam-trans btn-sm" id="placa-vehiculo"><i class="fa fa-id-card-o"></i> &nbsp;&nbsp; Documentos vehículos</a>
                <a href="../../transversal/odometro/"  class="btn btn-primary btn-cam-trans btn-sm" id="odometro-vehiculo"><i class="fa fa-tachometer"></i> &nbsp;&nbsp; Odometro</a>
                @if($acceso == "W")
                <a href="#"  onclick="abrirModalMasivo();" class="btn btn-primary btn-cam-trans btn-sm" ><i class="fa fa-upload"></i> &nbsp;&nbsp; Carga masivo Odometro</a>
                @endif
                @if($acceso == "W")
                <div class="dropdown" style="    width: 104px;    display: inline-block;">
                  <button class="btn btn-primary btn-cam-trans btn-sm dropdown-toggle" type="button" data-toggle="dropdown"> <i class="fa fa-archive"></i>  Maestro
                  <span class="caret"></span></button>
                  <ul class="dropdown-menu">
                  <li><a href="../../transversal/transporte/listaContratantes"  class=" btn-sm" ><i class="fa fa-id-badge"></i> &nbsp;&nbsp; Contratantes </a></li>
                    <li><a href="../../transversal/documentos/home"  class="btn-sm" ><i class="fa fa-archive"></i> &nbsp;&nbsp; Maestro Documentos</a></li>
                       <li><a href="../../transversal/transporte/listaContratantes"  class=" btn-sm" ><i class="fa fa-id-badge"></i> &nbsp;&nbsp; Contratantes </a></li>
                    <li><a href="../../transversal/transporte/listaProveedores"  class=" btn-sm" ><i class="fa fa-user"></i> &nbsp;&nbsp; Proveedores/Terceros</a></li>
                    <li><a href="../../transporte/costos/conceptos"  class=" btn-sm" ><i class="fa fa-user"></i> &nbsp;&nbsp; Conceptos costos</a></li>
                    <li><a href="../../transversal/transporte/rutinas"  class=" btn-sm" ><i class="fa fa-wrench"></i> &nbsp;&nbsp; Rutinas de mantenimiento</a></li>
                  </ul>
                </div>
                @endif

                <div class="dropdown" style="    width: 104px;    display: inline-block;">
                  <button class="btn btn-primary btn-cam-trans btn-sm dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-file-o"></i> Reportes
                  <span class="caret"></span></button>
                  <ul class="dropdown-menu">
                    <li><a href="../../transversal/reporte/documentovencidos"  class=" btn-sm" ><i class="fa fa-exclamation-triangle"></i> &nbsp;&nbsp; Reporte de documentos vencidos</a></li>
                    <li><a href="../../transversal/reportes/reportegeneraldocumentos"  class=" btn-sm" ><i class="fa fa-file-excel-o"></i> &nbsp;&nbsp; Reporte de estados general de documentos</a></li>
                    <li><a href="../../transversal/reportes/programamantenimiento"  class=" btn-sm" ><i class="fa fa-wrench"></i> &nbsp;&nbsp; Reporte programa de mantenimientos</a></li>
                    <li><a href="../../transversal/reportes/entregaOperacion"  class=" btn-sm" ><i class="fa fa-file-excel-o"></i> &nbsp;&nbsp; Reporte de Entrega a la operación</a></li>
                    <li><a href="../../transversal/reporte/odometro"  class=" btn-sm" ><i class="fa fa-file-excel-o"></i> &nbsp;&nbsp; Reporte de Odometros</a></li>
                    <li><a href="../../transversal/reportes/vehiculosSinKilometraje"  class=" btn-sm" ><i class="fa fa-file-excel-o"></i> &nbsp;&nbsp; Reporte de vehículos sin reporte de kilometraje</a></li>
                  </ul>
                </div>

                <a href="../../transversal/incidencias/home"  class="btn btn-primary btn-cam-trans btn-sm" ><i class="fa fa-street-view"></i> &nbsp;&nbsp; Centro de control</a>
                <a href="../../transversal/mantenimiento/"  class="btn btn-primary btn-cam-trans btn-sm" id="mantenimiento-vehiculo"><i class="fa fa-cogs"></i> &nbsp;&nbsp; Mantenimientos</a>
                @if($acceso == "W")
                    <a href="../../arbolDecisiones"  class="btn btn-primary btn-cam-trans btn-sm" ><i class="fa fa-cogs"></i> &nbsp;&nbsp; Arból de decisiones</a>
                @endif

                <a href="../../transporte/costos/causacion"  class="btn btn-primary btn-cam-trans btn-sm" ><i class="fa fa-file-o"></i> &nbsp;&nbsp; Causación costos</a>

                <br><br>
            </div>
            <div class="wizard wizard-container" style="    position: relative;    top: -44px;">
                <div class="wizard-inner">
                    <div class="connecting-line"></div>
                    <ul class="nav nav-tabs" role="tablist">

                        <li role="presentation" class="active" id="tabl_1" data-panel="1">
                            <span class="title_imagenes">Datos del vehiculo</span>
                            <a href="#DatosVehiculo" data-toggle="tab" aria-controls="DatosVehiculo" role="tab" title="Datos del vehiculo">
                            <span class="round-tab">
                                <i class="faAdjust fa-bus"></i>
                            </span>
                            </a>
                        </li>

                        <li role="presentation" id="tabl_2" class="disabled" data-panel="2">
                            <span class="title_imagenes">Datos del propietario</span>
                            <a href="#DatosPropietario" data-toggle="tab" aria-controls="DatosPropietario" role="tab" title="Datos del propietario">
                            <span class="round-tab">
                                <i class="faAdjust fa-user"></i>
                            </span>
                            </a>
                        </li>
                        <li role="presentation" id="tabl_3" class="disabled" data-panel="3">
                            <span class="title_imagenes">Datos del conductor</span>
                            <a href="#DatosConductor" data-toggle="tab" aria-controls="DatosConductor" role="tab" title="Datos del conductor">
                            <span class="round-tab">
                                <i class="faAdjust fa-id-card"></i>
                            </span>
                            </a>
                        </li>

                        <li role="presentation" id="tabl_4" class="disabled" data-panel="4">
                            <span class="title_imagenes" style="width:74%;">Información complementaria</span>
                            <a href="#InfoComplementaria" data-toggle="tab" aria-controls="InfoComplementaria" role="tab" title="Información complementaria">
                            <span class="round-tab">
                                <i class="faAdjust fa-book"></i>
                            </span>
                            </a>
                        </li>

                        <li role="presentation" id="tabl_5" class="disabled" data-panel="5">
                            <span class="title_imagenes">Fotografías</span>
                            <a href="#Fotografias" data-toggle="tab" aria-controls="Fotografias" role="tab" title="Fotografías">
                            <span class="round-tab">
                                <i class="faAdjust fa-picture-o" aria-hidden="true"></i>
                            </span>
                            </a>
                        </li>
                    </ul>
                </div>


                <div class="tab-content">

                        

                        <div class="tab-pane active" role="tabpanel" id="DatosVehiculo">
                            @include('proyectos.transporte.secciones.frmVehiculo')
                        </div>
                        <div class="tab-pane" role="tabpanel" id="DatosPropietario">
                            @include('proyectos.transporte.secciones.frmPropietario')
                        </div>
                        <div class="tab-pane" role="tabpanel" id="DatosConductor">
                            @include('proyectos.transporte.secciones.frmDatosConductor')
                        </div>
                        <div class="tab-pane" role="tabpanel" id="InfoComplementaria">
                            @include('proyectos.transporte.secciones.frmInfoComplementaria')
                        </div>
                        <div class="tab-pane" role="tabpanel" id="Fotografias">
                            @include('proyectos.transporte.secciones.frmFotografiasVehiculo')
                        </div>
                        <div class="clearfix"></div>
                    </div>

            </div>
        </section>
    </div>
</div>
</main>
<script type="text/javascript">
    window.addEventListener('load',ini);
    /* INICIO: FUNCIONES PARA PESTAÑAS */
    function nextTab(elem) {
        $(elem).next().find('a[data-toggle="tab"]').click();
    }
    function prevTab(elem) {
        $(elem).prev().find('a[data-toggle="tab"]').click();
    }
    /* FIN: FUNCIONES PARA PESTAÑAS */
    function ini() {

        if('<?php print $permisoModificacionInfoVehiculo; ?>') {

            // SE INHABILITAN LOS CAMPOS DE MODIFICACION
            if('<?php print $permisoModificacionInfoVehiculo; ?>' !== 'W') {
                $('#opciones_encabezado').hide();
                $('.wizard-container').css('top', '0px');

                $('.wizard .tab-content input').prop("disabled", true);
                $('.wizard .tab-content select').prop("disabled", true);
                $('.wizard .tab-content button').hide();
                $('.wizard .tab-content .btn.btn-primary.next-step').hide();

                $("#subefich").hide();

                $('.wizard .tab-content #fil_img_1').hide();
                $('.wizard .tab-content #fil_img_2').hide();
                $('.wizard .tab-content #fil_img_3').hide();
                $('.wizard .tab-content #fil_img_4').hide();
                $('.wizard .tab-content #fil_img_5').hide();
                $('.wizard .tab-content .bootstrap-filestyle.input-group').hide();
                $('.wizard .tab-content #Fotografias button').hide();
            }

            // SE HABILITAN LOS CAMPOS DE CONSULTA
            $('.wizard .tab-content #txtMatricula').prop("disabled", false);
            $('.wizard .tab-content #btn_consulta').show();
        }


        $("#selTipoVehiculo").change(function()
        {
            if(this.options[this.selectedIndex].dataset.cam == "" || this.options[this.selectedIndex].dataset.cam == null || this.options[this.selectedIndex].dataset.cam == undefined)
                document.querySelector("#txt_agrupacion").value = "";
            else
                document.querySelector("#txt_agrupacion").value = "Tipo vehículo CAM:" + this.options[this.selectedIndex].dataset.cam;
        });
        //cargaCiudades();
        /* INICIO: FUNCIONES PARA PESTAÑAS */
       // $('.nav-tabs > li a[title]').tooltip();

        //Wizard
        $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
            var $target = $(e.target);
            if ($target.parent().hasClass('disabled')) {
                return false;
            }
        });

        $(".next-step").click(function (e) {
            var $active = $('.wizard .nav-tabs li.active');
            if(validaPanelLleno($active[0].dataset.panel))
            {
                $active.next().removeClass('disabled');
                nextTab($active);
            }
        });

        $(".prev-step").click(function (e) {
            var $active = $('.wizard .nav-tabs li.active');
            prevTab($active);
        });

        //Eventos Input y Select
        
        var input = $(".wizard input");
        var select = $(".wizard select");

        for (var i = 0; i < input.length; i++) {
            input[i].addEventListener("keyup",function()
            {
                if(this.value == "")
                    this.style.boxShadow = "1px 0px 10px 0px rgb(255, 0, 0)";
                else
                    this.style.boxShadow = "1px 0px 5px 0px rgb(91, 192, 222)";
            });
            input[i].addEventListener("onblur",function()
            {
                if(this.value == "")
                    this.style.boxShadow = "1px 0px 10px 0px rgb(255, 0, 0)";
                else
                    this.style.boxShadow = "1px 0px 5px 0px rgb(91, 192, 222)";
            });
            input[i].addEventListener("onclick",function()
            {
                if(this.value == "")
                    this.style.boxShadow = "1px 0px 10px 0px rgb(255, 0, 0)";
                else
                    this.style.boxShadow = "1px 0px 5px 0px rgb(91, 192, 222)";
            });
            input[i].addEventListener("onFocus",function()
            {
                if(this.value == "")
                    this.style.boxShadow = "1px 0px 10px 0px rgb(255, 0, 0)";
                else
                    this.style.boxShadow = "1px 0px 5px 0px rgb(91, 192, 222)";
            });
            
        };   

        for (var i = 0; i < select.length; i++) {
            select[i].addEventListener("change",function()
            {
                if(this.value == "" || this.value == "0")
                    this.style.boxShadow = "1px 0px 10px 0px rgb(255, 0, 0)";
                else
                    this.style.boxShadow = "1px 0px 5px 0px rgb(91, 192, 222)";
            });
        };  


        $('#chkGps').bootstrapToggle({
            on: 'Si',
            off: 'No'
        });

        $('#chkCapacete').bootstrapToggle({
            on: 'Si',
            off: 'No'
        });

        $('#chkPortaEscalera').bootstrapToggle({
            on: 'Si',
            off: 'No'
        });

        $('#chkCajaHerramienta').bootstrapToggle({
            on: 'Si',
            off: 'No'
        });

        $('#chkPertiga').bootstrapToggle({
            on: 'Si',
            off: 'No'
        });

        ocultarSincronizacionFondoBlanco();
        /* FIN: FUNCIONES PARA PESTAÑAS */

        //Eventos File
        $("#fil_img_1").change(function(){ 
            cargaImagenesIMG(1,this)
        });
        $("#fil_img_2").change(function(){ 
            cargaImagenesIMG(2,this)
        });
        $("#fil_img_3").change(function(){ 
            cargaImagenesIMG(3,this)
        });
        $("#fil_img_4").change(function(){ 
            cargaImagenesIMG(4,this)
        });
        //Agregar Eventos DOM
        document.querySelector("#btn_consulta").addEventListener("click",consultaInformacionVehiculo);
        document.querySelector("#txtMatricula").addEventListener("blur",consultaInformacionVehiculo);

        @if(Session::has('imagen_guardada') || Session::has("placa_select"))
            consultaInformacionVehiculo();
        @else
            document.querySelector("#placa-vehiculo").style.display = "none";
            document.querySelector("#odometro-vehiculo").style.display = "none";
        @endif

        //Agrega eventos validación íngreso número

        document.querySelector("#txtSerieGps").addEventListener("keydown",validaIngreso);
        //document.querySelector("#txtNoChasis").addEventListener("keydown",validaIngreso);
        //document.querySelector("#txtNoMotor").addEventListener("keydown",validaIngreso);
        document.querySelector("#txtNoOrden").addEventListener("keydown",validaIngreso);
        document.querySelector("#txtValorCanon").addEventListener("keydown",validaIngreso);

        
        
    }

    function formato_download()
    {
        document.querySelector("#download_format").submit();
    }


    function nuevodocumento()
    {
        window.location.reload();
    }


    function abrirModalMasivo()
    {
        $("#modal_import_odometer").modal("toggle");
    }

    function abrirModal(tipo){
        if(tipo == 1)//modal ciudad
            $("#modal_ciudad").modal("toggle");
        if(tipo == 2)//modal Tipo vehiculo
            $("#modal_tipoVehiculo").modal("toggle");
        if(tipo == 3)//modal marca
            $("#modal_marca").modal("toggle");
        if(tipo == 4)//modal propietario
            $("#modal_propietario").modal("toggle");
        if(tipo == 5)//modal proveedor Monitoreo
            $("#modal_provMonitoreo").modal("toggle");
        if(tipo == 6)//modal cliente proyecto
            $("#modal_clienteProyecto").modal("toggle");
        if(tipo == 7)//modal conductor
        { 
            document.querySelector("#txtCedulaCon").value = "";
           document.querySelector("#txtNombreConduc").value = "";
           document.querySelector("#divConductoresVehiculo").innerHTML = "";
           $("#modal_conductorVehiculo").modal("toggle");
        }
        if(tipo == 8)//modal Log proyecto
        {
            document.querySelector("#selProyectoNovedad").value = document.querySelector("#selProyectoCliente").value;
            document.querySelector("#txtObserNovedadProyecto").value = "";
            document.querySelector("#txtNombreAutoria").value = "";

            var datos = {
                placa: document.querySelector("#txtMatricula").value,
                opc: 18
            }
            consultaAjax("../../rutaConsultaTransporte", datos, 15000, "POST", 14);  
        }


        if(tipo == 9)//modal Log proyecto
        {
            document.querySelector("#selEstadoNovedad").value = document.querySelector("#selEstado").value;

            if(document.querySelector("#selEstado").value == "E04" || document.querySelector("#selEstado").value == "E03")
            {
                var hijos = document.querySelector("#selEstadoNovedad").children;
                for (var i = 0; i < hijos.length; i++) {
                    hijos[i].disabled = true;
                };
            }
            else
            {
                var hijos = document.querySelector("#selEstadoNovedad").children;
                for (var i = 1; i < hijos.length; i++) {
                    if(i == 1 || i == 4 || i == 5)
                        hijos[i].disabled = false;
                };   
            }
            document.querySelector("#txtObserNovedadEstado").value = "";
            var datos = {
                placa: document.querySelector("#txtMatricula").value,
                opc: 19
            }
            consultaAjax("../../rutaConsultaTransporte", datos, 15000, "POST", 16);  
        }

    }
///
    function consultaInformacionVehiculo()
    {
        if(document.querySelector("#txtMatricula").value != "")
        {
            document.querySelector("#placa_fotografias").value = document.querySelector("#txtMatricula").value;
            var datos = {
                placa: document.querySelector("#txtMatricula").value,
                opc: 1
            }
            consultaAjax("../../rutaConsultaTransporte", datos, 15000, "POST", 7);  
        }
    }

    function editarModal(opcion, id, nombre,pref,costo,li,op,ad){
        if (opcion == 1)//Ciudad
        {
            document.getElementById("txtCodigoCiudad").value= id;
            document.getElementById("txtNombreCiudad").value = nombre;
            document.getElementById("txtCodigoCiudad").disabled= true;
        }
        if(opcion ==2){//Tipo
            document.getElementById("txtCodigoVehiculo").value= id;
            document.getElementById("txtNombreVehiculo").value = nombre;
            document.getElementById("txtCodigoVehiculo").disabled= true;
        }
        if(opcion ==3){//Marca
            document.getElementById("txtCodigoMarca").value= id;
            document.getElementById("txtNombreMarca").value = nombre;
            document.getElementById("txtCodigoMarca").disabled= true;
        }
        if(opcion ==4){//Propietario
            document.getElementById("txtCodigoPropietario").value= id;
            document.getElementById("txtNombrePropietario").value = nombre;
            document.getElementById("txtCodigoPropietario").disabled= true;
        }
        if(opcion ==6){//Proveedor monitoreo
            document.getElementById("txtCodigoProveedorMonitoreo").value= id;
            document.getElementById("txtNombreProveedorMonitoreo").value = nombre;
            document.getElementById("txtCodigoProveedorMonitoreo").disabled= true;
        }

        if(opcion ==7){//Pry
            document.getElementById("txtCodigoCliente").value= id;
            document.getElementById("txtNombreCliente").value = nombre;
            document.getElementById("txtPrefijo").value = pref;

            document.getElementById("txtCCosto").value = costo;

            document.getElementById("txtlineaproyecto").value = li;
            document.getElementById("txtop").value = op;
            document.getElementById("txtadicional").value = ad;

            document.getElementById("txtCodigoCliente").disabled= true;
        }


    }

    function limpiarModal(opcion){
        if(opcion ==1){//ciudad
            document.getElementById("txtCodigoCiudad").value= "";
            document.getElementById("txtNombreCiudad").value = "";
        }
        if(opcion ==2){//tipo
            document.getElementById("txtCodigoVehiculo").value= "";
            document.getElementById("txtNombreVehiculo").value = "";
        }
        if(opcion ==3){//Marca
            document.getElementById("txtCodigoMarca").value= "";
            document.getElementById("txtNombreMarca").value = "";
        }
        if(opcion ==4){//Propietario
            document.getElementById("txtCodigoPropietario").value= "";
            document.getElementById("txtNombrePropietario").value = "";
        }
        if(opcion ==6){//Proveedor monitoreo
            document.getElementById("txtCodigoProveedorMonitoreo").value= "";
            document.getElementById("txtNombreProveedorMonitoreo").value = "";
        }
        if(opcion ==6){//Cliente proyecto
            document.getElementById("txtCodigoCliente").value= "";
            document.getElementById("txtNombreCliente").value = "";
            document.getElementById("txtPrefijo").value = "";
        }
    }

    function guardarProyecto()
    {
        var codigo = document.getElementById("txtCodigoCliente").value;
        var nombre = document.getElementById("txtNombreCliente").value;
        var pre = document.getElementById("txtPrefijo").value;
        var costo = document.getElementById("txtCCosto").value;

        var ln = document.getElementById("txtlineaproyecto").value;
        var op = document.getElementById("txtop").value ;
        var ad = document.getElementById("txtadicional").value;



        if(nombre == "" || pre == "")
        {
            mostrarModal(1, null, "Guardar proyecto", "Nombre y prefijo obligatorios\n", 0, "Aceptar", "", null);
            return;
        }


        if(costo == "")
        {
            mostrarModal(1, null, "Guardar proyecto", "Centro de costo es obligatoria\n", 0, "Aceptar", "", null);
            return;
        }

        if(ln == "")
        {
            mostrarModal(1, null, "Guardar proyecto", "Línea es obligatoria\n", 0, "Aceptar", "", null);
            return;
        }

        if(op == "")
        {
            mostrarModal(1, null, "Guardar proyecto", "Op es obligatoria\n", 0, "Aceptar", "", null);
            return;
        }

        if(ad == "")
        {
            mostrarModal(1, null, "Guardar proyecto", "Adicional es obligatoria\n", 0, "Aceptar", "", null);
            return;
        }



        var datos = {
                    codigo: codigo,
                    nombre: nombre,
                    pre: pre,
                    costo: costo,
                    ln: ln,
                    op: op,
                    adicional: ad,
                    opc: 10
                }

        consultaAjax("../../rutaInsercionTransporte", datos, 15000, "POST", 10,1);


    }


    /* SAVE PRIMER PANEL*/
    function guardarCiudad(){
        var codigo = document.getElementById("txtCodigoCiudad").value;
        var nombre = document.getElementById("txtNombreCiudad").value;

        if(document.getElementById("txtCodigoCiudad").disabled != true)//guarda
        {
            if ( nombre != "") {
                var datos = {
                    codigo: "-1",
                    nombre: nombre,
                    opc: 1,
                    edita:0
                }
                consultaAjax("../../rutaInsercionTransporte", datos, 15000, "POST", 1,0);
            }
            else
                mostrarModal(1, null, "Mensaje", "Código y nombre obligatorios\n", 0, "Aceptar", "", null);
        }

        else//edita
        {
            if (nombre != "") {
                var datos = {
                    codigo: codigo,
                    nombre: nombre,
                    opc: 1,
                    edita: 1
                }
                consultaAjax("../../rutaInsercionTransporte", datos, 15000, "POST", 1,1);
            }
            else
                mostrarModal(1, null, "Mensaje", "Código y nombre obligatorios\n", 0, "Aceptar", "", null);
        }
    }

    function guardarTipoVehiculo(){
        var codigo = document.getElementById("txtCodigoVehiculo").value;
        var nombre = document.getElementById("txtNombreVehiculo").value;
        if(document.getElementById("txtCodigoVehiculo").disabled != true)//guarda
        {
            if ( nombre != "") {
                var datos = {
                    codigo: "-1",
                    nombre: nombre,
                    opc: 2,
                    edita: 0
                }
                consultaAjax("../../rutaInsercionTransporte", datos, 15000, "POST", 2);
            }
            else
                mostrarModal(1, null, "Mensaje", "Código y nombre obligatorios\n", 0, "Aceptar", "", null);
        }
        else//edita
        {
            if (nombre != "") {
                var datos = {
                    codigo: codigo,
                    nombre: nombre,
                    opc: 2,
                    edita: 1
                }
                consultaAjax("../../rutaInsercionTransporte", datos, 15000, "POST", 2,1);
            }
            else
                mostrarModal(1, null, "Mensaje", "Código y nombre obligatorios\n", 0, "Aceptar", "", null);
        }
    }

    function guardarMarca(){
        var codigo = document.getElementById("txtCodigoMarca").value;
        var nombre = document.getElementById("txtNombreMarca").value;
        if(document.getElementById("txtCodigoMarca").disabled != true)//guarda
        {
            if ( nombre != "") {
                var datos = {
                    codigo: "-1",
                    nombre: nombre,
                    opc: 3,
                    edita: 0
                }
                consultaAjax("../../rutaInsercionTransporte", datos, 15000, "POST", 3);
            }
            else
                mostrarModal(1, null, "Mensaje", "Código y nombre obligatorios\n", 0, "Aceptar", "", null);
        }
        else//edita
        {
            if (nombre != "") {
                var datos = {
                    codigo: codigo,
                    nombre: nombre,
                    opc: 3,
                    edita: 1
                }
                consultaAjax("../../rutaInsercionTransporte", datos, 15000, "POST", 3,1);
            }
            else
                mostrarModal(1, null, "Mensaje", "Código y nombre obligatorios\n", 0, "Aceptar", "", null);
        }
    }

    function guardaDatosVehiculo(){

        if(validaPanelLleno("1"))
        {
            var matricula = document.getElementById("txtMatricula").value;
            var ciudad = document.getElementById("selCiudad").value;;
            var tipo = document.getElementById("selTipoVehiculo").value;
            var marca = document.getElementById("selMarca").value;
            var modelo = document.getElementById("selModelo").value;
            var color = document.getElementById("txtColor").value;
            var numPasajeros = document.getElementById("txtNumPasajeros").value;
            var linea = document.getElementById("txtLinea").value;
            var cilindraje = document.getElementById("txtCilindraje").value;
            var pep = document.getElementById("txtPep").value;
            var tipoCombustible = document.getElementById("selTipoCombustible").value;
            var tipoTransmision = document.getElementById("selTipoTransmision").value;
            var tipoVinculacion = document.getElementById("selTipoVinculacion").value;
            var fecha = document.getElementById("fecha_inicio").value;
            var estado = document.getElementById("selEstado").value;
            var clase = document.getElementById("selClase").value;

            var rutina = document.getElementById("txt_rutina_km").value;
            var servicio = document.getElementById("selServicio").value;
            var contrato = document.getElementById("selContrato").value;


            var datos = {
                matricula: matricula,
                ciudad: ciudad,
                tipo: tipo,
                marca: marca,
                modelo: modelo,
                color: color,
                numPasajeros: numPasajeros,
                linea: linea,
                cilindraje: cilindraje,
                tipoCombustible: tipoCombustible,
                tipoTransmision: tipoTransmision,
                tipoVinculacion: tipoVinculacion,
                fecha: fecha,
                estado: estado,
                clase: clase,
                rutina : rutina,
                servicio : servicio,
                contrato : contrato,
                opc: 4
            }
            consultaAjax("../../rutaInsercionTransporte", datos, 15000, "POST", 4);
        }
    }


    function saveNovedadEstado()
    {
        if(document.querySelector("#selEstadoNovedad").value == document.querySelector("#selEstado").value)
        {
            mostrarModal(1,null,"Cambio de estado","El estado debe ser diferente al actual.\n",0,"Aceptar","",null);
            return;
        }

        if(document.querySelector("#txtObserNovedadEstado").value == "")
        {
            mostrarModal(1,null,"Cambio de estado","Ingrese por favor la observación de cambio de estado.\n",0,"Aceptar","",null);
            return;
        }

        var datos = {
                obser: document.querySelector("#txtObserNovedadEstado").value,
                placa: document.querySelector("#txtMatricula").value,
                esta: document.querySelector("#selEstadoNovedad").value,
                opc: 31
            };
        consultaAjax("../../rutaInsercionTransporte", datos, 15000, "POST", 18);

    }

    /*FIN SAVE PRIMER PANEL*/

    /* SAVE SEGUNDO PANEL*/
    function guardarPropietario()
    {
        var codigo = document.getElementById("txtCodigoPropietario").value;
        var nombre = document.getElementById("txtNombrePropietario").value;
        if(document.getElementById("txtCodigoPropietario").disabled != true)//guarda
        {
            if (nombre != "") {
                var datos = {
                    codigo: "-1",
                    nombre: nombre,
                    opc: 5,
                    edita: 0
                }
                consultaAjax("../../rutaInsercionTransporte", datos, 15000, "POST", 5);
            }
            else
                mostrarModal(1, null, "Mensaje", "Código y nombre obligatorios\n", 0, "Aceptar", "", null);
        }
        else//edita
        {
            if (nombre != "") {
                var datos = {
                    codigo: codigo,
                    nombre: nombre,
                    opc: 5,
                    edita: 1
                }
                consultaAjax("../../rutaInsercionTransporte", datos, 15000, "POST", 5,1);
            }
            else
                mostrarModal(1, null, "Mensaje", "Código y nombre obligatorios\n", 0, "Aceptar", "", null);
        }
    }

    function savePropietarioData()
    {
        if(validaPanelLleno("2"))
        {
            var propi = document.querySelector("#selPropietario").value;
            var txtcc = document.querySelector("#txtccpropietario").value;;
            var domi = document.querySelector(  "#txtdomicilio").value;
            var telefeF = document.querySelector("#txttelefonofijo").value;
            var teleC = document.querySelector("#txtcelular").value;
            var email = document.querySelector("#txtemail").value;

            var datos = {
                propi: propi,
                txtcc: txtcc,
                domi: domi,
                telefeF: telefeF,
                teleC: teleC,
                email: email,
                matri : document.querySelector("#txtMatricula").value,
                opc: 6
            }
            consultaAjax("../../rutaInsercionTransporte", datos, 15000, "POST", 6);
        }
    }

    /*FIN SAVE SEGUNDO PANEL*/

    /*TERCE PANEL*/
    function consultaInformacionConductor()
    {
        if(document.querySelector("#txtCedulaCon").value == "" &&
            document.querySelector("#txtNombreConduc").value == "" )
        {
            mostrarModal(1, null, "Mensaje", "Ingrese la cédula o el nombre del conductor para realizar el filtro.\n", 0, "Aceptar", "", null);
            return;
        }

        var datos = {
            nombre: document.querySelector("#txtNombreConduc").value,
            ced: document.querySelector("#txtCedulaCon").value,
            opc: 3
        }
        consultaAjax("../../rutaConsultaTransporte", datos, 15000, "POST", 11);  
    }

    function seleccionaPersona(nombre,ident,domi,tel,email)
    {
        document.querySelector("#txtnombreconductor").value = nombre;
        document.querySelector("#txtccconductor").value = ident;
        document.querySelector("#txtdomiconductor").value = domi;
        document.querySelector("#txttelconductor").value = tel;
        document.querySelector("#txtemailconductor").value = email;
        $("#modal_conductorVehiculo").modal("toggle");
    }

    function saveInformacionConductor()
    {
        if(document.querySelector("#txtnombreconductor").value == "" ||
            document.querySelector("#txtccconductor").value == "" )
            return;

        var datos = {
            conductor: document.querySelector("#txtccconductor").value,
            matri : document.querySelector("#txtMatricula").value,
            opc: 13
        };

        consultaAjax("../../rutaInsercionTransporte", datos, 15000, "POST", 12);
    }
    /*FIN TERCER PANEL*/


    /* SAVE CUARTO PANEL*/
    function guardarProveedorMonitoreo(){
        var codigo = document.getElementById("txtCodigoProveedorMonitoreo").value;
        var nombre = document.getElementById("txtNombreProveedorMonitoreo").value;
        if(document.getElementById("txtCodigoProveedorMonitoreo").disabled != true)//guarda
        {
            if (nombre != "") {
                var datos = {
                    codigo: "-1",
                    nombre: nombre,
                    opc: 8,
                    edita: 0
                }
                consultaAjax("../../rutaInsercionTransporte", datos, 15000, "POST", 8);
            }
            else
                mostrarModal(1, null, "Mensaje", "Código y nombre obligatorios\n", 0, "Aceptar", "", null);
        }
        else//edita
        {
            if (nombre != "") {
                var datos = {
                    codigo: codigo,
                    nombre: nombre,
                    opc: 8,
                    edita: 1
                }
                consultaAjax("../../rutaInsercionTransporte", datos, 15000, "POST", 8,1);
            }
            else
                mostrarModal(1, null, "Mensaje", "Código y nombre obligatorios\n", 0, "Aceptar", "", null);
        }
    }

    function guardarClienteProyecto(){
        var codigo = document.getElementById("txtCodigoCliente").value;
        var nombre = document.getElementById("txtNombreCliente").value;
        var domicilio = document.getElementById("txtDomicilioCliente").value;
        var telefono = document.getElementById("txtTelefonoCliente").value;
        if(document.getElementById("txtCodigoCliente").disabled != true)//guarda
        {
            if (codigo != "" && nombre != "") {
                var datos = {
                    codigo: codigo,
                    nombre: nombre,
                    domicilio: domicilio,
                    telefono: telefono,
                    opc: 9,
                    edita: 0
                }
                consultaAjax("../../rutaInsercionTransporte", datos, 15000, "POST", 9);
            }
            else
                mostrarModal(1, null, "Mensaje", "Código y nombre obligatorios\n", 0, "Aceptar", "", null);
        }
        else//edita
        {
            if (nombre != "") {
                var datos = {
                    codigo: codigo,
                    nombre: nombre,
                    opc: 10,
                    edita: 1
                }
                consultaAjax("../../rutaInsercionTransporte", datos, 15000, "POST", 10,1);
            }
            else
                mostrarModal(1, null, "Mensaje", "Código y nombre obligatorios\n", 0, "Aceptar", "", null);
        }
    }

    function guardaInfoComplementaria()
    {
        if(validaPanelLleno("4"))
        {
            var gps = document.querySelector("#chkGps").checked ? 1: 0;
            var capacete = document.querySelector("#chkCapacete").checked ? 1: 0;
            var portaEscalera = document.querySelector("#chkPortaEscalera").checked ? 1: 0;
            var cajaHerramientas = document.querySelector("#chkCajaHerramienta").checked ? 1: 0;
            var pertiga = document.querySelector("#chkPertiga").checked ? 1: 0;

            var propietarioGps = document.querySelector("#txtPropGps").value;
            var numChasis = document.querySelector("#txtNoChasis").value;;
            var proveedorMonitoreo = document.querySelector(  "#selProveedorMonitoreo").value;
            var numMotor = document.querySelector("#txtNoMotor").value;
            var serieGps = document.querySelector("#txtSerieGps").value;
            var numOrden = document.querySelector("#txtNoOrden").value;

            var tipoCAM = document.querySelector("#selTipoCAM").value;
            var txtKmPromedio = document.querySelector("#txtKmPromedio").value;

            
            //var clienteProyecto = document.querySelector("#selClienteProyecto").value;
            var canon = document.querySelector("#txtValorCanon").value;

            var datos = {
                gps: gps,
                capacete: capacete,
                portaEscalera: portaEscalera,
                cajaHerramientas: cajaHerramientas,
                pertiga: pertiga,   
                propietarioGps: propietarioGps,
                numChasis: numChasis,
                proveedorMonitoreo: proveedorMonitoreo,
                numMotor: numMotor,
                serieGps: serieGps,
                numOrden: numOrden,
                canon : canon,
                selProyectoCliente : document.querySelector("#selProyectoCliente").value,
                matricula: document.querySelector("#txtMatricula").value,
                tipoCAM : tipoCAM,
                kmPromedio: txtKmPromedio,
                opc: 9
            }
            consultaAjax("../../rutaInsercionTransporte", datos, 15000, "POST", 9);
        }
    }

    function saveNovedadProyecto()
    {
        if(document.querySelector("#selProyectoNovedad").value == document.querySelector("#selProyectoCliente").value)
        {
            mostrarModal(1,null,"Cambio de proyecto","El proyecto debe ser diferente al actual.\n",0,"Aceptar","",null);
            return;
        }

        if(document.querySelector("#txtObserNovedadProyecto").value == "")
        {
            mostrarModal(1,null,"Cambio de proyecto","Ingrese por favor la observación de cambio de proyecto.\n",0,"Aceptar","",null);
            return;
        }

        if(document.querySelector("#txtNombreAutoria").value == "")
        {
            mostrarModal(1,null,"Cambio de proyecto","Ingrese por favor el nombre de quien autoriza el cambio de proyecto.\n",0,"Aceptar","",null);
            return;
        }

        

        var datos = {
                obser: document.querySelector("#txtObserNovedadProyecto").value,
                placa: document.querySelector("#txtMatricula").value,
                pry: document.querySelector("#selProyectoNovedad").value,
                autoriza: document.querySelector("#txtNombreAutoria").value,
                opc: 27
            };
        consultaAjax("../../rutaInsercionTransporte", datos, 15000, "POST", 15);

    }

    /*FIN SAVE CUARTO PANEL*/


    /* SAVE QUINTO PANEL*/
    function guardarFotografiasyFinalizar(evt)
    {
        if(document.querySelector("#fil_img_1").value == "" &&
            document.querySelector("#fil_img_2").value == "" &&
            document.querySelector("#fil_img_3").value == "" &&
            document.querySelector("#fil_img_4").value == "" )
        {
            if(document.querySelector("#foto_1_carga").src == "{{ public_path('img/izquierda.png')}}" ||
                document.querySelector("#foto_2_carga").src == "{{ public_path('img/delante.png')}}" ||
                document.querySelector("#foto_3_carga").src == "{{ public_path('img/atras.png')}}" ||
                document.querySelector("#foto_4_carga").src == "{{ public_path('img/derecha.png')}}")
            {
                mostrarModal(1,null,"Carga Fotografías","Debe por lo menos cargar una fotografía del vehículo.\n",0,"Aceptar","",null);
                var evt = evt || window.event;
                evt.preventDefault();
                return;
            }   
        }
    }

    function cargaImagenesIMG(opc,ele)
    {
        var event = window.event;
        var selectedFile = event.target.files[0];
        var reader = new FileReader();
        var imgtag  = "";
        var input = "";
        if(opc == 1)
        {
            imgtag = document.querySelector("#foto_1_carga");
            input = document.querySelector("#fil_img_1");
            $("#foto_1_carga").addClass("img_donwload");
        }

        if(opc == 2)
        {
            input = document.querySelector("#fil_img_2");
            imgtag = document.querySelector("#foto_2_carga");
            $("#foto_2_carga").addClass("img_donwload");
        }

        if(opc == 3)
        {
            input = document.querySelector("#fil_img_3");
            imgtag = document.querySelector("#foto_3_carga");
            $("#foto_3_carga").addClass("img_donwload");
        }

        if(opc == 4)
        {
            input = document.querySelector("#fil_img_4");
            imgtag = document.querySelector("#foto_4_carga");
            $("#foto_4_carga").addClass("img_donwload");
        }     

        //alert(selectedFile.type);
        if(selectedFile.type == "image/jpge" || selectedFile.type == "image/png" || selectedFile.type == "image/jpg" || selectedFile.type == "image/jpeg")
        {
            if(selectedFile.size > 2000000)
            {
                input.value = "";
                $(imgtag).filestyle('clear');
                mostrarModal(1,null,"Tamaño imagen","El tamaño de la imagen no puede superar 2 MB.\n",0,"Aceptar","",null);
                return;
            }   

            imgtag.title = selectedFile.name;
            reader.onload = function(event) {
                imgtag.src = event.target.result;
            };
            reader.readAsDataURL(selectedFile);        
        }
        else
        {
            mostrarModal(1,null,"Tipo de archivo","Los tipo de archivo que puede cargar son .PNG. .JPGE, .JPG. .JPEG\n",0,"Aceptar","",null);
            input.value = "";
            $(imgtag).filestyle('clear');
            return;
        }
    }

    /*FIN SAVE QUINTO PANEL*/

    /*FUNCIONES TRABAJADAS POR PANEL*/
    function limpiarCamposFrm()
    {
        document.querySelector("#selCiudad").value = "";
        document.querySelector("#selTipoVehiculo").value = "";
        document.querySelector("#selMarca").value = "";
        document.querySelector("#selModelo").value = "";
        document.querySelector("#txtColor").value = "";
        document.querySelector("#txtNumPasajeros").value = "";
        document.querySelector("#txtLinea").value = "";
        document.querySelector("#txtCilindraje").value = "";
        document.querySelector("#txtPep").value = "";
        document.querySelector("#selTipoCombustible").value = "";
        document.querySelector("#selTipoTransmision").value = "";
        document.querySelector("#selTipoVinculacion").value = "";
        document.querySelector("#fecha_inicio").value = "";
        document.querySelector("#selEstado").value = "";
        document.querySelector("#selClase").value = "";
        
        document.getElementById("txt_rutina_km").value = "";

        //Datos Propietario
        document.querySelector("#selPropietario").value = "";
        document.querySelector("#txtccpropietario").value = "";
        document.querySelector("#txtdomicilio").value = "";
        document.querySelector("#txttelefonofijo").value = "";
        document.querySelector("#txtcelular").value = "";
        document.querySelector("#txtemail").value = "";
        document.querySelector("#txtResponsable").value = "";

       
        $('#chkGps').bootstrapToggle('off');
        $('#chkCapacete').bootstrapToggle('off');
        $('#chkPortaEscalera').bootstrapToggle('off');
        $('#chkCajaHerramienta').bootstrapToggle('off');
        $('#chkPertiga').bootstrapToggle('off');
        
        document.querySelector("#txtPropGps").value = "";
        document.querySelector("#txtNoChasis").value = "";
        document.querySelector("#selProveedorMonitoreo").value = "";
        document.querySelector("#txtNoMotor").value = "";
        document.querySelector("#txtSerieGps").value = "";
        document.querySelector("#txtNoOrden").value = "";
        document.querySelector("#selProyectoCliente").value = "";
        document.querySelector("#txtValorCanon").value = "";

        document.querySelector("#txtnombreconductor").value = "";
        document.querySelector("#txtccconductor").value = "";
        document.querySelector("#txtdomiconductor").value = "";
        document.querySelector("#txttelconductor").value = "";
        document.querySelector("#txtemailconductor").value = "";


        $("#tabl_1").addClass("active");
        $("#tabl_2").addClass("disabled");
        $("#tabl_3").addClass("disabled");
        $("#tabl_4").addClass("disabled");
        $("#tabl_5").addClass("disabled");
    }

    //Valida Panel lleno
    function validaPanelLleno(opc)
    {   
        validaInputCombox(opc);
        //Valida panel 1 Lleno
        if(opc == "1")
        {
            if( document.querySelector("#txtMatricula").value == "" ||
                document.querySelector("#selCiudad").value == "" ||
                document.querySelector("#selTipoVehiculo").value == "" ||
                document.querySelector("#selMarca").value == "" ||
                document.querySelector("#selModelo").value == "" ||
                document.querySelector("#txtColor").value == "" ||
                document.querySelector("#txtNumPasajeros").value == "" ||
                document.querySelector("#txtLinea").value == "" ||
                document.querySelector("#txtCilindraje").value == "" ||
                document.querySelector("#txtPep").value == "" ||
                document.querySelector("#selTipoCombustible").value == "" ||
                document.querySelector("#selTipoTransmision").value == "" ||
                document.querySelector("#selTipoVinculacion").value == "" ||
                document.querySelector("#fecha_inicio").value == "" ||
                document.querySelector("#txt_rutina_km").value == "" ||
                document.querySelector("#selClase").value == "")
            {
                mostrarModal(1,null,"Mensaje","Hace falta ingresar información del vehículo.\n",0,"Aceptar","",null);
                return false;
            }
        }

        //Valida panel 2 Lleno
        if(opc == "2")
        {

            if(document.querySelector("#selPropietario").value == "0" ||
            document.querySelector("#txtccpropietario").value == "" ||
            document.querySelector(  "#txtdomicilio").value == "" ||
            document.querySelector("#txttelefonofijo").value == "" ||
            document.querySelector("#txtcelular").value == "" ||
            document.querySelector("#txtemail").value == "")
            {
                mostrarModal(1,null,"Mensaje","Hace falta ingresar información del propietario de vehículo.\n",0,"Aceptar","",null);
                return false;
            }

        }

        //Valida panel 3 Lleno
        if(opc == "3")
        {
            return true;
        }

        //Valida panel 4 Lleno
        if(opc == "4")
        {
            if(document.querySelector("#txtPropGps").value == "" || 
            document.querySelector("#txtNoChasis").value == "" || 
            document.querySelector(  "#selProveedorMonitoreo").value == "" || 
            document.querySelector("#txtNoMotor").value == "" || 
            document.querySelector("#txtSerieGps").value == "" || 
            document.querySelector("#txtNoOrden").value == "" || 
            document.querySelector("#selProyectoCliente").value == "" || 
            document.querySelector("#selTipoCAM").value == "" || 
            document.querySelector("#txtValorCanon").value == "")
            {
                mostrarModal(1,null,"Mensaje","Hace falta ingresar información complementaria de vehículo.\n",0,"Aceptar","",null);
                return false;
            }
        }
        return true;
    }

    function validaInputCombox(opc)
    {
        

        if(opc == "1")
        {
            var input = $("#DatosVehiculo input");
            var select = $("#DatosVehiculo select");

            for (var i = 0; i < input.length; i++) {
                if(input[i].value == "")
                    input[i].style.boxShadow = "1px 0px 10px 0px rgb(255, 0, 0)";
                else
                    input[i].style.boxShadow = "1px 0px 5px 0px rgb(91, 192, 222)";
            };   

            for (var i = 0; i < select.length; i++) {
                if(select[i].value == "" || select[i].value == "0")
                    select[i].style.boxShadow = "1px 0px 10px 0px rgb(255, 0, 0)";
                else
                    select[i].style.boxShadow = "1px 0px 5px 0px rgb(91, 192, 222)";
            };  

        }

        if(opc == "2")
        {
            var input = $("#DatosPropietario input");
            var select = $("#DatosPropietario select");

            for (var i = 0; i < input.length; i++) {
                if(input[i].value == "")
                    input[i].style.boxShadow = "1px 0px 10px 0px rgb(255, 0, 0)";
                else
                    input[i].style.boxShadow = "1px 0px 5px 0px rgb(91, 192, 222)";
            };   

            for (var i = 0; i < select.length; i++) {
                if(select[i].value == "" || select[i].value == "0")
                    select[i].style.boxShadow = "1px 0px 10px 0px rgb(255, 0, 0)";
                else
                    select[i].style.boxShadow = "1px 0px 5px 0px rgb(91, 192, 222)";
            };  

        }

        if(opc == "3")
        {
            var input = $("#DatosConductor input");
            var select = $("#DatosConductor select");

            for (var i = 0; i < input.length; i++) {
                if(input[i].value == "")
                    input[i].style.boxShadow = "1px 0px 10px 0px rgb(255, 0, 0)";
                else
                    input[i].style.boxShadow = "1px 0px 5px 0px rgb(91, 192, 222)";
            };   

            for (var i = 0; i < select.length; i++) {
                if(select[i].value == "" || select[i].value == "0")
                    select[i].style.boxShadow = "1px 0px 10px 0px rgb(255, 0, 0)";
                else
                    select[i].style.boxShadow = "1px 0px 5px 0px rgb(91, 192, 222)";
            };  

        }

        if(opc == "4")
        {
            var input = $("#InfoComplementaria input");
            var select = $("#InfoComplementaria select");

            for (var i = 0; i < input.length; i++) {
                if(input[i].value == "")
                    input[i].style.boxShadow = "1px 0px 10px 0px rgb(255, 0, 0)";
                else
                    input[i].style.boxShadow = "1px 0px 5px 0px rgb(91, 192, 222)";
            };   

            for (var i = 0; i < select.length; i++) {
                if(select[i].value == "" || select[i].value == "0")
                    select[i].style.boxShadow = "1px 0px 10px 0px rgb(255, 0, 0)";
                else
                    select[i].style.boxShadow = "1px 0px 5px 0px rgb(91, 192, 222)";
            };  

        }

    }
    /*FIN FUNCIONES TRABAJADAS POR PANEL*/


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
                
                if(opcion ==1)//Crear ciudad
                {
                    if(data == -1)//la ciudad ya existe
                    {
                        ocultarSincronizacion();
                        mostrarModal(1,null,"Mensaje","Código de ciudad ya existe\n",0,"Aceptar","",null);
                        return;
                    }
                    ocultarSincronizacion();
                    if(edita != 1)
                        mostrarModal(2,null,"Mensaje","Ciudad guardada exitosamente\n",0,"Aceptar","",null);
                    else
                        mostrarModal(2,null,"Mensaje","Ciudad editada exitosamente\n",0,"Aceptar","",null);

                    document.querySelector("#txtCodigoCiudad").value = "";
                    document.querySelector("#txtNombreCiudad").value = "";
                    document.querySelector("#txtCodigoCiudad").disabled = false;

                    document.querySelector("#divTableModalCiudades").innerHTML = data;

                    //pintar select
                    var hijosCiudades = document.querySelector("#tbTblCiudades").children;

                    var HMTLOptionSelCiudad = '';
                    for(var i=0; i<hijosCiudades.length;i++){
                        var idHijoCiudad = hijosCiudades[i].children[0].innerText;
                        var nombreHijoCiudad = hijosCiudades[i].children[1].innerText
                        HMTLOptionSelCiudad += "<option value='"+idHijoCiudad +"'>"+nombreHijoCiudad+"</option>";
                    }
                    document.querySelector("#selCiudad").innerHTML =HMTLOptionSelCiudad;

                    //(opcion,callback,title,mensaje,boton,txtBoton1,txtBoton2,callback2)

                }
                if(opcion ==2)//Crear tipo vehiculo
                {
                    if(data == -1){//tipo vehiculo ya existe
                        ocultarSincronizacion();
                        mostrarModal(1,null,"Mensaje","Código de tipo de vehiculo ya existe\n",0,"Aceptar","",null);
                        return;
                    }
                    ocultarSincronizacion();
                    if(edita != 1)
                        mostrarModal(2,null,"Mensaje","Tipo de vehículo guardado exitosamente\n",0,"Aceptar","",null);
                    else
                        mostrarModal(2,null,"Mensaje","Tipo de vehículo editado exitosamente\n",0,"Aceptar","",null);

                    document.querySelector("#txtCodigoVehiculo").value = "";
                    document.querySelector("#txtNombreVehiculo").value = "";
                    document.querySelector("#txtCodigoVehiculo").disabled = false;
                    document.querySelector("#divTableModalVehiculo").innerHTML = data;

                    //pintar select
                    var hijosVehiculo = document.querySelector("#tbTblVehiculo").children;

                    var HMTLOptionSelVehiculo = '';
                    for(var i=0; i<hijosVehiculo.length;i++){
                        var idHijoVehiculo = hijosVehiculo[i].children[0].innerText;
                        var nombreHijoVehiculo = hijosVehiculo[i].children[1].innerText
                        HMTLOptionSelVehiculo += "<option value='"+idHijoVehiculo +"'>"+nombreHijoVehiculo+"</option>";
                    }
                    document.querySelector("#selTipoVehiculo").innerHTML =HMTLOptionSelVehiculo;

                    //(opcion,callback,title,mensaje,boton,txtBoton1,txtBoton2,callback2)

                }
                if(opcion ==3)//Crear marca
                {
                    if(data == -1){//marca ya existe
                        ocultarSincronizacion();
                        mostrarModal(1,null,"Mensaje","Código de marca ya existe\n",0,"Aceptar","",null);
                        return;
                    }
                    ocultarSincronizacion();
                    if(edita != 1)
                        mostrarModal(2,null,"Mensaje","Marca guardada exitosamente\n",0,"Aceptar","",null);
                    else
                        mostrarModal(2,null,"Mensaje","Marca editada exitosamente\n",0,"Aceptar","",null);

                    document.querySelector("#txtCodigoMarca").value = "";
                    document.querySelector("#txtNombreMarca").value = "";
                    document.querySelector("#txtCodigoMarca").disabled = false;
                    document.querySelector("#divTableModalMarca").innerHTML = data;

                    //pintar select
                    var hijosMarca = document.querySelector("#tbTblMarca").children;

                    var HMTLOptionSelMarca = '';
                    for(var i=0; i<hijosMarca.length;i++){
                        var idHijoMarca = hijosMarca[i].children[0].innerText;
                        var nombreMarca = hijosMarca[i].children[1].innerText
                        HMTLOptionSelMarca += "<option value='"+idHijoMarca +"'>"+nombreMarca+"</option>";
                    }
                    document.querySelector("#selMarca").innerHTML =HMTLOptionSelMarca;

                    //(opcion,callback,title,mensaje,boton,txtBoton1,txtBoton2,callback2)

                }
                if(opcion ==4)//Guarda datos del vehiculo
                {
                    ocultarSincronizacion();
                    mostrarNotificacion(2,"Datos de vehículo almacenados exitosamente");
                    //mostrarModal(2,null,"Mensaje","\n",0,"Aceptar","",null);
                }
                if(opcion ==5)//Crear Propietario
                {
                    if(data == -1){//Propietario ya existe
                        ocultarSincronizacion();
                        mostrarNotificacion(4,"Código de propietario ya existe");
                        //mostrarModal(1,null,"Mensaje","Código de propietario ya existe\n",0,"Aceptar","",null);
                        return;
                    }
                    ocultarSincronizacion();
                    if(edita != 1)
                        mostrarNotificacion(2,"Propietario guardado exitosamente");
                    else
                        mostrarNotificacion(2,"Propietario editado exitosamente");

                    document.querySelector("#txtCodigoPropietario").value = "";
                    document.querySelector("#txtNombrePropietario").value = "";
                    document.querySelector("#txtCodigoPropietario").disabled = false;
                    document.querySelector("#divTableModalPropietarios").innerHTML = data;

                    //pintar select
                    var hijosMarca = document.querySelector("#tbTblPropitarios").children;

                    var HMTLOptionSelMarca = '';
                    HMTLOptionSelMarca += "<option value='0'>Seleccione</option>";
                    for(var i=0; i<hijosMarca.length;i++){
                        var idHijoMarca = hijosMarca[i].children[0].innerText;
                        var nombreMarca = hijosMarca[i].children[1].innerText
                        HMTLOptionSelMarca += "<option value='"+idHijoMarca +"'>"+nombreMarca+"</option>";
                    }
                    document.querySelector("#selPropietario").innerHTML =HMTLOptionSelMarca;

                    //(opcion,callback,title,mensaje,boton,txtBoton1,txtBoton2,callback2)
                }
                if(opcion ==6)//Guarda Datos propietario
                {
                    mostrarNotificacion(2,"Datos de propietario de vehículo almacenados exitosamente");
                }
                if(opcion == 7) //Consulta Datos Vehiculo
                {
                    document.querySelector("#txt_agrupacion").value = "";
                    //Limpia fotos
                    document.querySelector("#foto_1_carga").src = "../../img/izquierda.png";
                    document.querySelector("#foto_2_carga").src = "../../img/delante.png";
                    document.querySelector("#foto_3_carga").src = "../../img/atras.png";
                    document.querySelector("#foto_4_carga").src = "../../img/derecha.png";

                    for (var i = 1; i <= 4; i++) {
                        $("#foto_" + i + "_carga").removeClass("img_donwload");
                    };

                    if(data.length > 0)
                    {
                        if(data[0].length > 0)
                        {

                            document.querySelector("#selCiudad").value = data[0][0].id_ciudad;
                            document.querySelector("#selTipoVehiculo").value = data[0][0].id_tipo_vehiculo;


                            var tipoVeh = document.querySelector("#selTipoVehiculo").options[document.querySelector("#selTipoVehiculo").selectedIndex].dataset.cam;


                            if(tipoVeh == "" || tipoVeh == null || tipoVeh == undefined)
                                document.querySelector("#txt_agrupacion").value = "";
                            else
                                document.querySelector("#txt_agrupacion").value = "Tipo vehículo CAM:" + tipoVeh;


                            

                            document.querySelector("#selTipoCAM").value = data[0][0].id_tipo_vehiculo_cam;
                            
                            document.querySelector("#selMarca").value = data[0][0].id_marca;
                            document.querySelector("#selModelo").value = data[0][0].modelo;
                            document.querySelector("#txtColor").value = data[0][0].color;
                            document.querySelector("#txtNumPasajeros").value = data[0][0].pasajeros;
                            document.querySelector("#txtLinea").value = data[0][0].linea;

                            document.getElementById("txt_rutina_km").value = data[0][0].rutina ;
                            document.ready = document.querySelector("#selServicio").value = data[0][0].numero_servicio;
                            document.ready = document.querySelector("#selContrato").value = data[0][0].numero_contrato_vehiculo;
                             $("#selServicio").change();
                             $("#selContrato").change();

                            document.querySelector("#txtCilindraje").value = data[0][0].cilindraje;
                            document.querySelector("#txtPep").value = data[0][0].pep;
                            document.querySelector("#selTipoCombustible").value = data[0][0].id_tipo_combustible;
                            document.querySelector("#selTipoTransmision").value = data[0][0].id_transmision;
                            document.querySelector("#selTipoVinculacion").value = data[0][0].id_tipo_vinculo;
                            if(data[0][0].fecha){
                                document.querySelector("#fecha_inicio").value = data[0][0].fecha.split("-")[2] + "/" + data[0][0].fecha.split("-")[1] +  "/" + data[0][0].fecha.split("-")[0];
                            }
                            document.querySelector("#selEstado").value = data[0][0].id_estado;
                            document.querySelector("#div_estado").style.display = "block";
                            document.querySelector("#selClase").value = data[0][0].id_clase;

                            $("#enlacefichatec").attr("href","http://190.60.248.195"+data[0][0].ficha_tecnica);
                            $("#enlacefichatec").show("slow");

                            if('<?php print $permisoModificacionInfoVehiculo; ?>' === 'W') {
                                $("#subefich").show("slow");
                            }
                                 
                            //Datos Propietario
                            //Se valida que tengo datos de propietarios
                            if(data[0][0].id_propietario != null && data[0][0].id_propietario != undefined)
                            {
                                $("#tabl_2").removeClass("disabled");
                                document.querySelector("#selPropietario").value = data[0][0].id_propietario;
                                $("#selPropietario").change();
                                document.querySelector("#txtccpropietario").value = data[0][0].cedula;
                                document.querySelector("#txtdomicilio").value = data[0][0].domicilio;
                                document.querySelector("#txttelefonofijo").value = data[0][0].telefonoFijo;
                                document.querySelector("#txtcelular").value = data[0][0].telefonoCel;
                                //Pruebaaaa de datos
                                //document.querySelector("#txtprueba").value = data[0][0].elemento_pep;
                                document.querySelector("#txtemail").value = data[0][0].correo;    
                                document.querySelector("#txtResponsable").value = data[0][0].responsable;
                            }
                                
                            //Datos del conductor
                            if(data[1].length > 0)
                            {
                                $("#tabl_3").removeClass("disabled");
                                document.querySelector("#txtnombreconductor").value = data[1][0].nombres + " " + data[1][0].apellidos;
                                document.querySelector("#txtccconductor").value = data[1][0].identificacion;
                                document.querySelector("#txtdomiconductor").value = data[1][0].domicilio;
                                document.querySelector("#txttelconductor").value = data[1][0].telefono1;
                                document.querySelector("#txtemailconductor").value = data[1][0].correo;
                            } 


                            //Información complementaría
                            //Se valida que tengo datos de complementaría
                            if(data[0][0].id_propietario != null && data[0][0].id_propietario != undefined)
                            {
                                $("#tabl_4").removeClass("disabled");
                                $("#tabl_5").removeClass("disabled");
                                if(data[0][0].gps == "0")
                                $('#chkGps').bootstrapToggle('off');
                                else
                                    $('#chkGps').bootstrapToggle('on');

                                if(data[0][0].capacete == "0")
                                    $('#chkCapacete').bootstrapToggle('off');
                                else
                                    $('#chkCapacete').bootstrapToggle('on');

                                if(data[0][0].portaescaleras == "0")
                                    $('#chkPortaEscalera').bootstrapToggle('off');
                                else
                                    $('#chkPortaEscalera').bootstrapToggle('on');

                                if(data[0][0].caja_herramientas == "0")
                                    $('#chkCajaHerramienta').bootstrapToggle('off');
                                else
                                    $('#chkCajaHerramienta').bootstrapToggle('on');

                                if(data[0][0].portapertiga == "0")
                                    $('#chkPertiga').bootstrapToggle('off');
                                else
                                    $('#chkPertiga').bootstrapToggle('on');

                                document.querySelector("#txtPropGps").value = data[0][0].propietario_gps;
                                document.querySelector("#txtNoChasis").value = data[0][0].chasis;
                                document.querySelector("#selProveedorMonitoreo").value = data[0][0].id_proveedor_monitoreo;
                                document.querySelector("#txtNoMotor").value = data[0][0].motor;
                                document.querySelector("#txtSerieGps").value = data[0][0].serie_gps;
                                document.querySelector("#txtNoOrden").value = data[0][0].serie_gps;
                                document.querySelector("#selProyectoCliente").value = data[0][0].id_proyecto;
                                document.querySelector("#txtValorCanon").value = data[0][0].valor_contrato;   
                                document.querySelector("#txtKmPromedio").value = data[0][0].km_promedio;   
                            }                            
                            
                            var ruta = "{{$servidor}}/transversal/documento/" + datos.placa;
                            document.querySelector("#placa-vehiculo").href = ruta;
                            ruta = "{{$servidor}}/transversal/odometro/" + datos.placa;
                            document.querySelector("#odometro-vehiculo").href = ruta;
                            ruta = "{{$servidor}}/transversal/mantenimiento/" + datos.placa;
                            document.querySelector("#mantenimiento-vehiculo").href = ruta;


                            

                            var primerMante = data[0][0].primer_mantenimiento;
                            if(primerMante == null || primerMante == undefined || primerMante == 0)
                            {
                                document.querySelector("#placa_vehi_registro_primerMante").value = datos.placa;

                                if('<?php print $permisoModificacionInfoVehiculo; ?>' === 'W') {
                                    $("#modal_promixo_mantenimiento").modal("toggle");
                                }
                            }

                               
                            document.querySelector("#placa-vehiculo").style.display = "inline-block";
                            document.querySelector("#odometro-vehiculo").style.display = "inline-block";

                            $("#tabl_1").addClass("active");
                            
                            

                            if(data[0][0].ruta_imagen1 != "" && data[0][0].ruta_imagen1 != null && data[0][0].ruta_imagen1 != undefined)
                            {
                                document.querySelector("#foto_1_carga").src = "visor/" + btoa(data[0][0].direccion1);
                            
                                $("#foto_1_carga").addClass("img_donwload");
                            }

                            if(data[0][0].ruta_imagen2 != "" && data[0][0].ruta_imagen2 != null && data[0][0].ruta_imagen2 != undefined)
                            {
                                document.querySelector("#foto_2_carga").src = "visor/" + btoa(data[0][0].direccion2);
                                $("#foto_2_carga").addClass("img_donwload");
                            }

                            if(data[0][0].ruta_imagen3 != "" && data[0][0].ruta_imagen3 != null && data[0][0].ruta_imagen3 != undefined)
                            {
                                document.querySelector("#foto_3_carga").src = "visor/" + btoa(data[0][0].direccion3);
                                $("#foto_3_carga").addClass("img_donwload");
                            }

                            if(data[0][0].ruta_imagen4 != "" && data[0][0].ruta_imagen4 != null && data[0][0].ruta_imagen4 != undefined)
                            {
                                document.querySelector("#foto_4_carga").src = "visor/" + btoa(data[0][0].direccion4);
                                $("#foto_4_carga").addClass("img_donwload");
                            }
                            //Consulta Fotografías
                            /*var datosA = {
                                placa: document.querySelector("#txtMatricula").value,
                                opc: 6
                            }
                            consultaAjax("../../rutaConsultaTransporte", datosA, 150000, "POST", 13,null,-1);  */

                            document.querySelector("#selProyectoCliente").disabled = true;

                            document.querySelector("#btn_log_vehi").style.display = "block";
                        }
                        else
                        {
                            document.querySelector("#placa-vehiculo").href = "{{$servidor}}/transversal/documento/";
                            document.querySelector("#odometro-vehiculo").href = "{{$servidor}}/transversal/odometro/";
                            document.querySelector("#mantenimiento-vehiculo").href = "{{$servidor}}/transversal/mantenimiento/";
                            document.querySelector("#div_estado").style.display = "none"; 
                            document.querySelector("#placa_vehi_registro_primerMante").value = "";     
                            document.querySelector("#placa-vehiculo").style.display = "none";
                            document.querySelector("#odometro-vehiculo").style.display = "none"; 
                            document.querySelector("#selProyectoCliente").disabled = false;
                            limpiarCamposFrm();
                            document.querySelector("#btn_log_vehi").style.display = "none";
                        }
                        //Datos Vehículo
                        
                    }
                    else
                    {
                        document.querySelector("#placa-vehiculo").style.display = "none";
                        document.querySelector("#odometro-vehiculo").style.display = "none";
                        document.querySelector("#placa-vehiculo").href = "{{$servidor}}/transversal/documento/";
                        document.querySelector("#odometro-vehiculo").href = "{{$servidor}}/transversal/odometro/";
                        document.querySelector("#div_estado").style.display = "none";
                        document.querySelector("#placa_vehi_registro_primerMante").value = "";    
                        document.querySelector("#selProyectoCliente").disabled = false;
                        limpiarCamposFrm();
                        document.querySelector("#btn_log_vehi").style.display = "none";
                    }
                    validaInputCombox("1");
                    validaInputCombox("2");
                    validaInputCombox("3");
                    validaInputCombox("4");
                }
                if(opcion == 8)//Crea proveedor monitoreo
                {
                    if(data == -1){//proveedor ya existe
                        ocultarSincronizacion();
                        mostrarModal(1,null,"Mensaje","Proveedor de monitoreo ya existe\n",0,"Aceptar","",null);
                        return;
                    }
                    ocultarSincronizacion();
                    if(edita != 1)
                        mostrarModal(2,null,"Mensaje","Proveedor de monitoreo guardado exitosamente\n",0,"Aceptar","",null);
                    else
                        mostrarModal(2,null,"Mensaje","Proveedor de monitoreo editado exitosamente\n",0,"Aceptar","",null);

                    document.querySelector("#txtCodigoProveedorMonitoreo").value = "";
                    document.querySelector("#txtNombreProveedorMonitoreo").value = "";
                    document.querySelector("#txtCodigoProveedorMonitoreo").disabled = false;
                    document.querySelector("#divTableModalProveedorMonitoreo").innerHTML = data;

                    //pintar select
                    var hijosProveedor = document.querySelector("#tbTblProveedorMonitoreo").children;

                    var HMTLOptionSelProveedor = '';
                    for(var i=0; i<hijosProveedor.length;i++){
                        var idHijoProveedor = hijosProveedor[i].children[0].innerText;
                        var nombreProveedor = hijosProveedor[i].children[1].innerText
                        HMTLOptionSelProveedor += "<option value='"+idHijoProveedor +"'>"+nombreProveedor+"</option>";
                    }
                    document.querySelector("#selProveedorMonitoreo").innerHTML =HMTLOptionSelProveedor;

                }
                if(opcion == 9)//Guarda información complementaria
                {
                    document.querySelector("#selProyectoCliente").disabled = true;
                    mostrarNotificacion(2,"Información complementaria almacenada exitosamente");
                }
                if(opcion == 10)//Crea cliente proyecto
                {
                    if(data == -1){//cliente ya existe
                        ocultarSincronizacion();
                        mostrarModal(1,null,"Mensaje","Cliente/proyecto ya existe\n",0,"Aceptar","",null);
                        return;
                    }
                    ocultarSincronizacion();
                    if(edita != 1)
                        mostrarModal(2,null,"Mensaje","Cliente/proyecto guardado exitosamente\n",0,"Aceptar","",null);
                    else
                        mostrarModal(2,null,"Mensaje","Cliente/proyecto editado exitosamente\n",0,"Aceptar","",null);

                    document.querySelector("#txtCodigoCliente").value = "";
                    document.querySelector("#txtNombreCliente").value = "";
                    document.querySelector("#txtPrefijo").value = "";
                    document.querySelector("#txtCCosto").value = "";

                    document.getElementById("txtlineaproyecto").value = "";
                    document.getElementById("txtop").value = "";
                    document.getElementById("txtadicional").value = "";
                    
                    document.querySelector("#txtCodigoCliente").disabled = false;
                    document.querySelector("#divTableModalClienteProyecto").innerHTML = data;

                    //pintar select
                    var hijosProveedor = document.querySelector("#tbTblProveedorMonitoreo").children;

                    var HMTLOptionSelProveedor = '';
                    for(var i=0; i<hijosProveedor.length;i++){
                        var idHijoProveedor = hijosProveedor[i].children[0].innerText;
                        var nombreProveedor = hijosProveedor[i].children[1].innerText
                        HMTLOptionSelProveedor += "<option value='"+idHijoProveedor +"'>"+nombreProveedor+"</option>";
                    }
                    document.querySelector("#selProveedorMonitoreo").innerHTML =HMTLOptionSelProveedor;
                }
                if(opcion == 11) //Consulta Conductores
                {
                    document.querySelector("#divConductoresVehiculo").innerHTML = data; 
                }
                if(opcion == 12) //Guarda información de conductor del vehículo
                {
                    mostrarNotificacion(2,"Información del conductor almacenado correctamente");
                }
                if(opcion == 13) //Consulta Imagenes
                {
                    if(data[0] != "")
                    {
                        document.querySelector("#foto_1_carga").src = "data:image/jpeg;base64," + data[0];
                        $("#foto_1_carga").addClass("img_donwload");
                    }

                    if(data[1] != "")
                    {
                        document.querySelector("#foto_2_carga").src = "data:image/jpeg;base64," + data[1];
                        $("#foto_2_carga").addClass("img_donwload");
                    }

                    if(data[2] != "")
                    {
                        document.querySelector("#foto_3_carga").src = "data:image/jpeg;base64," + data[2];
                        $("#foto_3_carga").addClass("img_donwload");
                    }

                    if(data[3] != "")
                    {
                        document.querySelector("#foto_4_carga").src = "data:image/jpeg;base64," + data[3];
                        $("#foto_4_carga").addClass("img_donwload");
                    }

                }

                if(opcion == 14) //Consulta Log Novedad
                {
                    document.querySelector("#tblNovedadProyecto").innerHTML = "";
                    var html = "";
                    for (var i = 0; i < data.length; i++) {
                        html += "<tr>";
                            html += "<td>" + data[i].nombre + "</td>";
                            html += "<td>" + data[i].observacion + "</td>";
                            html += "<td>" + data[i].propietario + "</td>";
                            html += "<td>" + (data[i].autoriza == null ? "" : data[i].autoriza) + "</td>";
                            @if($permisoFecha == "W")
                                html += "<td><b>" + data[i].fecha_servidor.split(".")[0] + "</b>";
                                html += "<input data-id='" + data[i].id + "' type='date' style='display:none' data-fecha='" + data[i].fecha_servidor.split(" ")[0]  + "'/>";
                                html += "<button style='padding: 3px;    margin-top: 8px;' class='btn btn-primary' onclick='editFecha(this)' data-ingreso='0'>Editar Fecha</button><i style='display:none;    color: red;     font-size: 20px;    position: relative;    top: 6px;    margin-left: 3px;' onclick='cancelarEdit(this)' class='fa fa-times'></i>"  ;
                                html +=  "</td>";
                            @else
                                html += "<td>" + data[i].fecha_servidor.split(".")[0] + "</td>";
                            @endif
                        html += "</tr>";
                    };

                    document.querySelector("#tblNovedadProyecto").innerHTML = html;
                    $("#modal_novedad_proyecto").modal("toggle");
                }

                if(opcion == 15)
                {
                    mostrarModal(2,null,"Cambio de proyecto","Se ha modificado correctamente el proyecto.\n",0,"Aceptar","",null);                    
                    document.querySelector("#selProyectoCliente").value = document.querySelector("#selProyectoNovedad").value;
                    $("#modal_novedad_proyecto").modal("toggle");
                }


                if(opcion == 16) //Consulta Log Estados
                {
                    document.querySelector("#tblNovedadEstado").innerHTML = "";
                    var html = "";
                    for (var i = 0; i < data.length; i++) {
                        html += "<tr>";
                            html += "<td>" + data[i].nombre + "</td>";
                            html += "<td>" + data[i].observacion + "</td>";
                            html += "<td>" + data[i].propietario + "</td>";
                            html += "<td>" + data[i].fecha_servidor.split(".")[0] + "</td>";
                        html += "</tr>";
                    };

                    document.querySelector("#tblNovedadEstado").innerHTML = html;
                    $("#modal_novedad_estado").modal("toggle");
                }

                if(opcion == 18)
                {
                    mostrarModal(2,null,"Cambio de estado","Se ha modificado correctamente el estado.\n",0,"Aceptar","",null);                    
                    document.querySelector("#selEstado").value = document.querySelector("#selEstadoNovedad").value;
                    $("#modal_novedad_estado").modal("toggle");
                }

                if(opcion == 19)
                {
                    
                    var html = "";
                    for (var i = 0; i < data.length; i++) {
                        html += "<tr>";
                        html += "<td>" + (i + 1) + "</td>";
                        html += "<td>" + data[i].descripcion + "</td>";
                        html += "<td>" + data[i].propietario + "</td>";
                        html += "<td>" + data[i].fecha.split(".")[0] + "</td>";
                        html += "<td>" + data[i].obser + "</td>";
                        html += "</tr>";
                    }                    
                    
                    //fecha','log.descripcion','tra.descripcion as obser','sis.propietario

                    document.querySelector("#tbn_hist_log").innerHTML = html;
                    $("#modal_log_vehiculo").modal("toggle");
                }

                if(opcion == 20)
                {
                    mostrarModal(2,null,"Cambiar fecha","Se ha modificado correctamente la fecha\n",0,"Aceptar","",null);
                    $("#modal_novedad_proyecto").modal("toggle");
                }


                ocultarSincronizacion();
            },
            error:function(request,status,error){
                ocultarSincronizacion();
                //$('#filter_registro').modal('toggle');

                /*mostrarModal(1,null,"Consulta de Alianzas","Existen problemas con la conexión a internet, comuníquese con la persona encargada del manejo de la red.\n",0,"Aceptar","",null);*/
                setTimeout(function()
                {
                    //location.reload();
                },3000);
            }

        });
    }

    function editFecha(ele)
    {
        if(ele.dataset.ingreso == "1")
        {
            if(ele.parentElement.children[1].value == "")
            {
                mostrarModal(1,null,"Cambiar fecha","Ingrese la nueva fecha\n",0,"Aceptar","",null);
                return;
            }
            ele.dataset.ingreso = "0";
            ele.innerHTML = "Editar fecha";
            ele.parentElement.children[0].style.display = "block";
            ele.parentElement.children[3].style.display = "none";
            ele.parentElement.children[1].style.display = "none";

            guardarFechaNueval(ele.parentElement.children[1].value,ele.parentElement.children[1].dataset.id);
        }
        else
        {
            ele.dataset.ingreso = "1";
            ele.innerHTML = "Guardar fecha";

            var fechaA = ele.parentElement.children[1].dataset.fecha.split("-");
            fechaA =  fechaA[0] + "-" + fechaA[1] + "-" + fechaA[2];
            ele.parentElement.children[1].value = fechaA;

            ele.parentElement.children[0].style.display = "none";
            ele.parentElement.children[1].style.display = "block";
            
            ele.parentElement.children[3].style.display = "inline-block";
        }
    }

    function cancelarEdit(ele)
    {
        var ele = ele.parentElement.children[2];

        ele.dataset.ingreso = "0";
        ele.innerHTML = "Editar fecha";
        ele.parentElement.children[0].style.display = "block";
        ele.parentElement.children[3].style.display = "none";
        ele.parentElement.children[1].style.display = "none";

    }

    function guardarFechaNueval(fechaNueva,id)
    {
        if(confirm("¿Seguro que desea cambiar la fecha"))
        {
            var datos = {
                    id: id,
                    fecha: fechaNueva,
                    opc: 32
                };
            
            consultaAjax("../../rutaInsercionTransporte", datos, 15000, "POST", 20);
        }

    }

    function consultaLogVehiculo()
    {
        if(document.querySelector("#txtMatricula").value != "")
        {
            var datos = {
                placa: document.querySelector("#txtMatricula").value,
                opc: 20
            }

            consultaAjax("../../rutaConsultaTransporte", datos, 15000, "POST", 19);      
        }
        
    }

</script>

@section('js')
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
    <script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
    <script src="jquery.ui.datepicker-es.js"></script>

    <script>
        $("#selPropietario").select2();
        $.datepicker.setDefaults($.datepicker.regional["es"]);
        $("#fecha_inicio").datepicker({
            dateFormat: "dd/mm/yy",
            maxDate: "+1M"
        });


    </script>
@stop

<?php
Session::forget('imagen_guardada');
Session::forget('placa_select');
?>
