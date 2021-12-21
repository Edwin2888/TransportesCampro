@extends('template.index')

@section('title')
	Crear plan de supervisión
@stop

@section('title-section')
    Crear plan de supervisión
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
        @include('proyectos.supervisor.conformacion.modal.modalConsultaPersonas') <!-- Modal que consulta las personas -->

        <!--  -->
        

		<div class="container-fluid">

        @include('proyectos.supervisor.conformacion.frm.frmConformacionPlanSupervision') <!-- Sección que tiene la conformación del plan de supervisión -->
        
        </div>
	</main>


    <script type="text/javascript">

        window.addEventListener('load',ini);

        var elementoInput;
        var opcion;
        function ini()
        {
            document.querySelector("#sincronizando3").style.display = "none";
            document.querySelector("#sincronizando4").style.display = "none"; 
        }

        //Función encarga de abrir el modal para consulta personas
        function abrirModalPersonas(opc,elemento)
        {
            document.querySelector("#txtCedula").value = "";
            document.querySelector("#txtNombre").value = "";
            document.querySelector("#divConductoresVehiculo").innerHTML = "";
            elementoInput = elemento;
            opcion = opc;
            $("#modal_consulta_personas").modal("toggle");
        }

        //Función encarga de consultar las personas según filtro
        function consultaInformacionPersonas()
        {
            if(document.querySelector("#txtCedula").value == "" &&
                document.querySelector("#txtNombre").value == "" )
            {
                mostrarModal(1, null, "Consultar personas", "Ingrese la cédula o el nombre para realizar la consulta.\n", 0, "Aceptar", "", null);
                return;
            }

            if(opcion == 1)
            {
                var datos = {
                    nombre: document.querySelector("#txtNombre").value,
                    ced: document.querySelector("#txtCedula").value,
                    opc: 2
                }
                consultaAjax("{{url('/')}}/transversal/supervision/wsWebConsulta", datos, 15000, "POST", 3);  
            }
            else
            {
                if(document.querySelector("#txtLiderTable").value == "")
                {
                    mostrarModal(1, null, "Consultar personas", "Ingrese primero al líder por favor.\n", 0, "Aceptar", "", null);
                    return;
                }

                var datos = {
                    nombre: document.querySelector("#txtNombre").value,
                    ced: document.querySelector("#txtCedula").value,
                    lider : document.querySelector("#txtLiderTable").dataset.cedula,
                    opc: 1
                }
                consultaAjax("{{url('/')}}/transversal/supervision/wsWebConsulta", datos, 15000, "POST", 1);         
            }
            
        }

        //Función encarga de borrar datos de la persona selecciona
        function limpiarPersonas(opc,ele)
        {
            if(opc == 2)
            {
                ele.parentElement.children[8].value = "";
                ele.parentElement.children[8].dataset.cedula = "";
            }
            else
            {
                if(opc == 3)
                {
                    ele.parentElement.children[2].value = "";
                    ele.parentElement.children[2].dataset.cedula = "";
                }
                else
                {
                    ele.parentElement.children[3].value = "";
                    ele.parentElement.children[3].dataset.cedula = "";
                }
            }
        }
        
        //Función encarga de guardar datos de la pesona seleccionada
        function seleccionaPersona(nombre,cedula)
        {

            if(document.querySelector("#txtLiderTable").dataset.cedula == cedula
                || document.querySelector("#txtColaboradorTable").dataset.cedula == cedula)
            {
                mostrarModal(1,null,"Seleccionar persona","Ya esta seleccionada esta persona.\n",0,"Aceptar","",null);
                return;
            }
            
            var hijos = document.querySelector("#elementos_add").children;
            var exist = 0;
            for (var i = 0; i < hijos.length; i++) {
                
                if(hijos[i].children[0].children[2].dataset.cedula == cedula)
                {
                    exist = 1;
                    break;       
                }
            };

            if(exist == 1)
            {
                mostrarModal(1,null,"Seleccionar persona","Ya esta seleccionada esta persona.\n",0,"Aceptar","",null);
                return;
            }


            if(opcion == 2)
            {
                elementoInput.parentElement.children[8].value = nombre;
                elementoInput.parentElement.children[8].dataset.cedula = cedula;    
            }
            else
            {
                if(opcion == 3)
                {
                    elementoInput.parentElement.children[2].value = nombre;
                    elementoInput.parentElement.children[2].dataset.cedula = cedula;
                }
                else
                {
                    elementoInput.parentElement.children[3].value = nombre;
                    elementoInput.parentElement.children[3].dataset.cedula = cedula;
                }  
            }
            
            $("#modal_consulta_personas").modal("toggle");
        }

        //Función encarga de agregar colaboradores en HTML
        function agregarColaborador()
        {

            var dato1 = "";
            var dato2 = "";
            var dato3 = "";
            var dato4 = "0";

            if(document.querySelector("#ChekReplica").checked)
            {
                dato1 = document.querySelector("#txtLide1").value;
                dato2 = document.querySelector("#txtLide2").value;
                dato3 = document.querySelector("#txtLide3").value;
                dato4 = document.querySelector("#txtLide4").value;
            }


            var html = '<div class="row">';
            html += '   <div class="col-md-3" style="margin-top:10px;">';
            html += '                <button onclick="abrirModalPersonas(3,this)"  type="submit" class="btn btn-primary btn-cam-trans btn-sm"  id="btn-add-nodos-orden" ><i class="fa fa-search"></i></button>';
            html += '                <button onclick="limpiarPersonas(3,this)" type="submit" class="btn btn-primary btn-cam-trans btn-sm"  id="btn-add-nodos-orden" ><i class="fa fa-trash-o"></i></button>';
            html += '                <input type="text" readonly="" class="form-control" style="display:inline-block;width:75%;">';
            html += '            </div>                    ';
            html += '            <div class="col-md-8" style="margin-top:10px;">';
            html += '                <div class="col-md-1">';
            html += '                    <input type="number" class="form-control" min="0" value="' + dato1 + '"/>';
            html += '                </div>';
            html += '                <div class="col-md-1">';
            html += '                    <input type="number" class="form-control" min="0" value="' + dato2 + '"/>';
            html += '                </div>';
            html += '                <div class="col-md-1">';
            html += '                    <input type="number" class="form-control" min="0" value="' + dato3 + '"/>';
            html += '                </div>';
            html += '                <div class="col-md-1">';
            html += '                    <input type="number"  class="form-control" min="0" value="' + dato4 + '"/>';
            html += '                </div>';
            html += '                <div class="col-md-1">';
            html += '                    <input type="number"  class="form-control" min="0" value="' + dato4 + '"/>';
            html += '                </div>';
            html += '                <div class="col-md-1">';
            html += '                    <input type="number"  class="form-control" min="0" value="' + dato4 + '"/>';
            html += '                </div>';
            html += '                <div class="col-md-1">';
            html += '                    <input type="number"  class="form-control" min="0" value="' + dato4 + '"/>';
            html += '                </div>';
            html += '                <div class="col-md-1">';
            html += '                    <input type="number"  class="form-control" min="0" value="' + dato4 + '"/>';
            html += '                </div>';
            html += '                <div class="col-md-1">';
            html += '                    <input type="number"  class="form-control" min="0" value="' + dato4 + '"/>';
            html += '                </div>';
            html += '                <div class="col-md-1">';
            html += '                    <input type="number"  class="form-control" min="0" value="' + dato4 + '"/>';
            html += '                </div>';
            html += '                <div class="col-md-1">';
            html += '                    <input type="number"  class="form-control" min="0" value="' + dato4 + '"/>';
            html += '                </div>';
            html += '                <div class="col-md-1">';
            html += '                    <input type="number"  class="form-control" min="0" value="' + dato4 + '"/>';
            html += '                </div>';
            html += '            </div>';
            html += '                <div class="col-md-1">';
            html += '                    <i class="fa fa-times" onclick="eliminarRegistro(this)" style="color:red;font-size: 27px;    position: relative;    left: -18px;" title="Eliminar registro"></i>';
            html += '                </div>';
            html += '        </div>';

            $("#elementos_add").append(html);
        }

        //Función encarga de eliminar colaboradores en HTML
        function eliminarRegistro(ele)
        {
            if(confirm("¿Seguro que desea eliminar el registro"))
                $(ele.parentElement.parentElement.parentElement.parentElement).find(ele.parentElement.parentElement.parentElement).remove();  
        }

        //Función encarga de mostrar los datos seleccionado para el líder a todos sus colaboradores (Evento Key)
        function cambiarDatos(event,opc,ele)
        {
            if(document.querySelector("#ChekReplica").checked)
            {
                var char = event.which || event.keyCode;
                var hijos = document.querySelector("#elementos_add").children;
                document.querySelector("#txtCola"+opc).value = ele.value;
                for (var i = 0; i < hijos.length; i++) {
                    hijos[i].children[1].children[parseInt(opc)-1].children[0].value =  ele.value;
                };
                /*
                if(opc == 1)
                {
                    document.querySelector("#txtCola1").value = ele.value;
                    for (var i = 0; i < hijos.length; i++) {
                        hijos[i].children[1].children[0].children[0].value =  ele.value;
                    };

                } 

                if(opc == 2)
                {
                    document.querySelector("#txtCola2").value = ele.value;
                    for (var i = 0; i < hijos.length; i++) {
                        hijos[i].children[1].children[1].children[0].value =  ele.value;
                    };
                }

                if(opc == 3)
                {
                    document.querySelector("#txtCola3").value = ele.value;
                    for (var i = 0; i < hijos.length; i++) {
                        hijos[i].children[1].children[2].children[0].value =  ele.value;
                    };
                } 

                if(opc == 4)
                {
                    document.querySelector("#txtCola4").value = ele.value;
                    for (var i = 0; i < hijos.length; i++) {
                        hijos[i].children[1].children[3].children[0].value =  ele.value;
                    };
                }  
                */
            }
        }

        //Función encarga de mostrar los datos seleccionado para el líder a todos sus colaboradores (Evento clic)
        function cambiarDatos2(ele,opc)
        {
            if(document.querySelector("#ChekReplica").checked)
            {
                var hijos = document.querySelector("#elementos_add").children;
                document.querySelector("#txtCola"+opc).value = ele.value;
                for (var i = 0; i < hijos.length; i++) {
                    hijos[i].children[1].children[parseInt(opc)-1].children[0].value =  ele.value;
                };
                /*
                if(opc == 1)
                {
                    document.querySelector("#txtCola1").value = ele.value;
                    for (var i = 0; i < hijos.length; i++) {
                        hijos[i].children[1].children[0].children[0].value =  ele.value;
                    };
                } 

                if(opc == 2)
                {
                    document.querySelector("#txtCola2").value = ele.value;
                    for (var i = 0; i < hijos.length; i++) {
                        hijos[i].children[1].children[1].children[0].value =  ele.value;
                    };
                }

                if(opc == 3)
                {
                    document.querySelector("#txtCola3").value = ele.value;
                    for (var i = 0; i < hijos.length; i++) {
                        hijos[i].children[1].children[2].children[0].value =  ele.value;
                    };
                }   

                if(opc == 4)
                {
                    document.querySelector("#txtCola4").value = ele.value;
                    for (var i = 0; i < hijos.length; i++) {
                        hijos[i].children[1].children[3].children[0].value =  ele.value;
                    };
                }  */
            }
        }


        //Función encarga de mostrar los datos seleccionado para el líder a todos sus colaboradores (Evento clic)
        function cambiarDatosTodos()
        {
            if(document.querySelector("#ChekReplica").checked)
            {
                    var hijos = document.querySelector("#elementos_add").children;
               
                    document.querySelector("#txtCola1").value = document.querySelector("#txtLide1").value;
                    for (var i = 0; i < hijos.length; i++) {
                        hijos[i].children[1].children[0].children[0].value =  document.querySelector("#txtLide1").value;
                    };
                
                    document.querySelector("#txtCola2").value = document.querySelector("#txtLide2").value;
                    for (var i = 0; i < hijos.length; i++) {
                        hijos[i].children[1].children[1].children[0].value =  document.querySelector("#txtLide2").value;
                    };
               
                    document.querySelector("#txtCola3").value = document.querySelector("#txtLide3").value;
                    for (var i = 0; i < hijos.length; i++) {
                        hijos[i].children[1].children[2].children[0].value = document.querySelector("#txtLide3").value;
                    };

                    document.querySelector("#txtCola4").value = document.querySelector("#txtLide4").value;
                    for (var i = 0; i < hijos.length; i++) {
                        hijos[i].children[1].children[3].children[0].value =  document.querySelector("#txtLide4").value;
                    };
                document.querySelector("#txtCola4").value = document.querySelector("#txtLide4").value;
                for (var i = 0; i < hijos.length; i++) {
                    hijos[i].children[1].children[3].children[0].value =  document.querySelector("#txtLide4").value;
                };
                document.querySelector("#txtCola5").value = document.querySelector("#txtLide5").value;
                for (var i = 0; i < hijos.length; i++) {
                    hijos[i].children[1].children[4].children[0].value =  document.querySelector("#txtLide5").value;
                };
                document.querySelector("#txtCola6").value = document.querySelector("#txtLide6").value;
                for (var i = 0; i < hijos.length; i++) {
                    hijos[i].children[1].children[5].children[0].value =  document.querySelector("#txtLide6").value;
                };
                document.querySelector("#txtCola7").value = document.querySelector("#txtLide7").value;
                for (var i = 0; i < hijos.length; i++) {
                    hijos[i].children[1].children[6].children[0].value =  document.querySelector("#txtLide7").value;
                };
                document.querySelector("#txtCola8").value = document.querySelector("#txtLide8").value;
                for (var i = 0; i < hijos.length; i++) {
                    hijos[i].children[1].children[7].children[0].value =  document.querySelector("#txtLide8").value;
                };
                document.querySelector("#txtCola9").value = document.querySelector("#txtLide9").value;
                for (var i = 0; i < hijos.length; i++) {
                    hijos[i].children[1].children[8].children[0].value =  document.querySelector("#txtLide9").value;
                };
                document.querySelector("#txtCola10").value = document.querySelector("#txtLide10").value;
                for (var i = 0; i < hijos.length; i++) {
                    hijos[i].children[1].children[9].children[0].value =  document.querySelector("#txtLide10").value;
                };
                document.querySelector("#txtCola11").value = document.querySelector("#txtLide11").value;
                for (var i = 0; i < hijos.length; i++) {
                    hijos[i].children[1].children[10].children[0].value =  document.querySelector("#txtLide11").value;
                };
                document.querySelector("#txtCola12").value = document.querySelector("#txtLide12").value;
                for (var i = 0; i < hijos.length; i++) {
                    hijos[i].children[1].children[11].children[0].value =  document.querySelector("#txtLide12").value;
                };
                 
            }
        }


        //Guardar información de los colaboradores
        function guardarColaborador()
        {

            var lider = [];
            var colaboradores = [];
            if(document.querySelector("#txtLiderTable").value == ""
                || document.querySelector("#txtLide1").value == ""
                || document.querySelector("#txtLide2").value == ""
                || document.querySelector("#txtLide3").value == ""
                || document.querySelector("#txtLide4").value == "")
            {
                mostrarModal(1,null,"Guardar plan de supervisión","Hace falta ingresar información del líder.\n",0,"Aceptar","",null);
                return;
            }

            lider.push(
                {
                    'cc' : document.querySelector("#txtLiderTable").dataset.cedula,
                    'dato1' : document.querySelector("#txtLide1").value,
                    'dato2' : document.querySelector("#txtLide2").value,
                    'dato3' : document.querySelector("#txtLide3").value,
                    'dato4' : (document.querySelector("#txtLide4").value == "" ? 0 : document.querySelector("#txtLide4").value),
                    'dato5' : (document.querySelector("#txtLide5").value == "" ? 0 : document.querySelector("#txtLide5").value),
                    'dato6' : (document.querySelector("#txtLide6").value == "" ? 0 : document.querySelector("#txtLide6").value),
                    'dato7' : (document.querySelector("#txtLide7").value == "" ? 0 : document.querySelector("#txtLide7").value),
                    'dato8' : (document.querySelector("#txtLide8").value == "" ? 0 : document.querySelector("#txtLide8").value),
                    'dato9' : (document.querySelector("#txtLide9").value == "" ? 0 : document.querySelector("#txtLide9").value),
                    'dato10' : (document.querySelector("#txtLide10").value == "" ? 0 : document.querySelector("#txtLide10").value),
                    'dato11' : (document.querySelector("#txtLide11").value == "" ? 0 : document.querySelector("#txtLide11").value),
                    'dato12' : (document.querySelector("#txtLide12").value == "" ? 0 : document.querySelector("#txtLide12").value)
                });

            if(document.querySelector("#txtColaboradorTable").value == ""
                || document.querySelector("#txtCola1").value == ""
                || document.querySelector("#txtCola2").value == ""
                || document.querySelector("#txtCola3").value == ""
                || document.querySelector("#txtCola4").value == "")
            {
                mostrarModal(1,null,"Guardar plan de supervisión","Hace falta ingresar información de los colaboradores.\n",0,"Aceptar","",null);
                return;
            }
            else
            {
                colaboradores.push(
                {
                    'cc' : document.querySelector("#txtColaboradorTable").dataset.cedula,
                    'dato1' : document.querySelector("#txtCola1").value,
                    'dato2' : document.querySelector("#txtCola2").value,
                    'dato3' : document.querySelector("#txtCola3").value,
                    'dato4' : (document.querySelector("#txtCola4").value == "" ? 0 : document.querySelector("#txtCola4").value),
                    'dato5' : (document.querySelector("#txtCola5").value == "" ? 0 : document.querySelector("#txtCola5").value),
                    'dato6' : (document.querySelector("#txtCola6").value == "" ? 0 : document.querySelector("#txtCola6").value),
                    'dato7' : (document.querySelector("#txtCola7").value == "" ? 0 : document.querySelector("#txtCola7").value),
                    'dato8' : (document.querySelector("#txtCola8").value == "" ? 0 : document.querySelector("#txtCola8").value),
                    'dato9' : (document.querySelector("#txtCola9").value == "" ? 0 : document.querySelector("#txtCola9").value),
                    'dato10' : (document.querySelector("#txtCola10").value == "" ? 0 : document.querySelector("#txtCola10").value),
                    'dato11' : (document.querySelector("#txtCola11").value == "" ? 0 : document.querySelector("#txtCola11").value),
                    'dato12' : (document.querySelector("#txtCola12").value == "" ? 0 : document.querySelector("#txtCola12").value)
                }); 
            }

            var hijos = document.querySelector("#elementos_add").children;
            for (var i = 0; i < hijos.length; i++) {
                if(hijos[i].children[0].children[2].value == ""
                    || hijos[i].children[1].children[0].children[0].value == ""
                    || hijos[i].children[1].children[1].children[0].value == ""
                    || hijos[i].children[1].children[2].children[0].value == ""
                    || hijos[i].children[1].children[3].children[0].value == "")
                {
                    mostrarModal(1,null,"Guardar plan de supervisión","Hace falta ingresar información de los colaboradores.\n",0,"Aceptar","",null);
                    return;
                }
                else
                {
                    colaboradores.push(
                    {
                        'cc' : hijos[i].children[0].children[2].dataset.cedula,
                        'dato1' : hijos[i].children[1].children[0].children[0].value,
                        'dato2' : hijos[i].children[1].children[1].children[0].value,
                        'dato3' : hijos[i].children[1].children[2].children[0].value,
                        'dato4' : hijos[i].children[1].children[3].children[0].value,
                        'dato5' : hijos[i].children[1].children[4].children[0].value,
                        'dato6' : hijos[i].children[1].children[5].children[0].value,
                        'dato7' : hijos[i].children[1].children[6].children[0].value,
                        'dato8' : hijos[i].children[1].children[7].children[0].value,
                        'dato9' : hijos[i].children[1].children[8].children[0].value,
                        'dato10' : hijos[i].children[1].children[9].children[0].value,
                        'dato11' : hijos[i].children[1].children[10].children[0].value,
                        'dato12' : hijos[i].children[1].children[11].children[0].value
                    });
                }
            };

            if(document.querySelector("#txtNombreEquipo").value == "")
            {
                mostrarModal(1,null,"Guardar plan de supervisión","Hace falta ingresar el nombre del equipo.\n",0,"Aceptar","",null);
                return;
            }

            var datos = {
                lider: lider,
                colaboradores: colaboradores,
                equipo : document.querySelector("#txtNombreEquipo").value,
                anio : document.querySelector("#txt_anio").value,
                mes : document.querySelector("#txt_mes").value,
                opc: 1
            }
            consultaAjax("{{url('/')}}/transversal/supervision/wsWebSave", datos, 15000, "POST", 2);

            
        }

        //Función encargada de realizar las consultas al servidor AJAX
        function consultaAjax(route,datos,tiempoEspera,type,opcion,collback,dato,ele)
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
                    if(opcion == "1") //Consulta Personas
                    {
                        
                        document.querySelector("#divConductoresVehiculo").innerHTML = data; 
                        ocultarSincronizacion();
                    }
                    if(opcion == 2)
                    {
                         ocultarSincronizacion();
                        mostrarModal(2,null,"Guardar plan de supervisión","Se ha guardado correctamente el plan de supervisión.\n",0,"Aceptar","",null);
                        window.location.href = "{{url('/')}}/transversal/supervision/conformacion/" + data;
                    }

                    if(opcion == 3)
                    {
                       document.querySelector("#divConductoresVehiculo").innerHTML = data; 
                        ocultarSincronizacion();
                    }

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

    </script>
@stop

