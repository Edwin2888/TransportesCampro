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
            @if($permisoConfirmar == "W")
                <button class="btn btn-primary  btn-cam-trans btn-sm" onclick="confirmar_documento()" style="    margin-left: 26px;    margin-top: 20px;"><i class="fa fa-check-square-o" aria-hidden="true"></i>   Confirmar documentos seleccionados</button>

                <br>
                <b style="margin-left: 27px;"> <input type="checkbox"  onchange="seleccionarTodos(this,1)" id="select_1" data-uso = "0"/> <label for="select_1">Seleccionar todos los documentos para confirmar</label></b>
            @endif
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
                    if(hijos[i].children[3].children[0].innerHTML != "RETIRADO" && hijos[i].children[7].innerHTML.trim().replace("\n","") != "$0.00"  && hijos[i].children[13].innerHTML.trim().replace("\n","") != "")
                       hijos[i].children[13].children[0].checked = true;
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
                    if(hijos[i].children[3].children[0].innerHTML != "RETIRADO" && hijos[i].children[7].innerHTML.trim().replace("\n","") != "$0.00"  && hijos[i].children[13].innerHTML.trim().replace("\n","") != "")
                       hijos[i].children[13].children[0].checked = false;
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
            timeout:tiempoEspera,
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
                    mostrarModal(2,null,"Generar documentos","Se han generado y actualizado exitosamente los documentos seleccionados",0,"Aceptar","",null); 
                    ocultarSincronizacion();
                    setTimeout(function()
                    {
                        location.reload();
                    },700);
                }

                if(opcion == 3)//Actualiza días
                {
                    mostrarNotificacion(2,"Documento: " + datos.documento + " - Día: " + datos.dia + " Mes: " + datos.mes + " actualizado correctamente");
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
                $("#modal_deschequear_dia").modal("toggle");
                document.querySelector("#txtObserDia").value = "";
                ele.dataset.obser = "";
            }
            else
            {
                ele.dataset.estado = "1";
                ele.parentElement.title = "ACTIVO";
                ele.parentElement.style.background = "#dbfedb";
                ele.parentElement.children[0].src = "{{url('/')}}/img/checked.png";
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
                $("#modal_deschequear_dia").modal("toggle");
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
    }

    function cancelarDia()
    {
        elementoGuardarObservacion.checked = true;
        $("#modal_deschequear_dia").modal("toggle");
    }

    function guardarObservacionDia()
    {
        if(document.querySelector("#txtObserDia").value == "")
        {
            mostrarModal(1,null,"Guardar observación","No se ha ingresado la observación",0,"Aceptar","",null); 
            return;
        }
        

        //Guardar Día - Mes - AR - Obser
        var documentoAr = elementoGuardarObservacion.parentElement.parentElement.children[8].children[0].innerHTML;

        if (documentoAr.indexOf('AR0') !=-1) {
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
                $("#modal_deschequear_dia").modal("toggle");
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
                $("#modal_deschequear_dia").modal("toggle");
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
            if(ele[i].children[3].children[0].innerHTML != "RETIRADO" && ele[i].children[7].innerHTML.trim().replace("\n","") != "$0.00"  && ele[i].children[13].innerHTML.trim().replace("\n","") != "")
            {
                if(ele[i].children[13].children[0].checked)
                {
                    var hijosTotales = ele[i].children.length - 14;
                    var ini = 14;
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
                        'placa' : ele[i].children[2].innerHTML.trim().replace("\n",""),
                        'documento' : (ele[i].children[13].children[0].dataset.ar == '' || ele[i].children[13].children[0].dataset.ar == null || ele[i].children[13].children[0].dataset.ar == undefined ? 'A' : ele[i].children[13].children[0].dataset.ar),
                        'dias' : hijoArreglo
                    });
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

        var hijosTotales = ele.parentElement.parentElement.children.length - 14;
        var ini = 14;
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
            var totalRecorreo = hijos[i].children.length - 14;
            var ini = 14;
            var dias = 0;
            var canon = hijos[i].children[7].innerHTML.trim().replace("\n","").replace("$","").replace(",","").replace(".00","");
            var docFechaGenerado = hijos[i].children[10].innerHTML.trim().replace("\n","");
            var totalPagar = 0;
            var tipoVehiculo = hijos[i].children[4].innerHTML.trim().replace("\n","");
            canon = parseInt(canon.replace(",",""));
            

            if(canon != 0)//No se han generado documentos AR y no tenga CANON = 0
            {
                //Recorrer todas las columnas
                for (var j = ini; j < totalRecorreo; j++) 
                {
                    if(hijos[i].children[j].innerHTML.trim().replace("\n","") == "")
                        continue;

                    if(hijos[i].children[j].children[1].checked)
                        dias++;
                };

                totalPagar = (canon / 30) *  dias;
                totalPagarFinal = totalPagarFinal + totalPagar;
                
                hijos[i].children[11].innerHTML = dias;
                hijos[i].children[12].innerHTML = "$ " + number_format(totalPagar,2);
                
                var exis = 0;
                var letra = 0;
                for (var k = 0; k < arregloTipoVehiculo.length; k++) {
                    if(arregloTipoVehiculo[k] == tipoVehiculo)
                    {
                        exis = 1;
                        letra = k;
                        break;
                    }
                };

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

        
    }
</script>