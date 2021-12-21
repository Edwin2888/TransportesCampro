@extends('template.index')

@section('title')
	Validador
@stop

@section('title-section')
    Validador
@stop

@section('content')
    <style type="text/css">
    #proyectos_filter
    {
        position: relative;
        top: -35px;
        left: -15px;
    }

    #proyectos_wrapper .dataTables_scroll
    {
        position: relative;
        top: -35px;
    }
    </style>
	<main>

        <!-- Import Modal -->
        @include('proyectos.electricaribe.validador.modal.cargaExcelValidador')
        @include('proyectos.electricaribe.validador.modal.consultaLiquidacionNic')
        @include('proyectos.electricaribe.validador.modal.modalgestores')
        @include('proyectos.electricaribe.validador.modal.modalcreategestor')

		<div class="container-fluid">
            <div class="side-body" id="cuerpo" style="margin-top:20px;margin-left:0px;">
                    @include('proyectos.electricaribe.validador.secciones.filter')
            </div>

            <div class="tbl_index_proyectos" id="tbl_index_proyectos" data-pos="0">
                @include('proyectos.electricaribe.validador.tables.validaciones')
            </div>
        </div>
	</main>


    <script type="text/javascript">

        window.addEventListener('load',ini);

        function ini()
        {
            document.querySelector("#upload_excel").addEventListener("click",abrirModalExcel);
            document.querySelector("#gestores").addEventListener("click",abrirModalGestores);

            document.querySelector("#create_gestores").addEventListener("click",function()
                {
                    $("#modal_gestores_view").modal("toggle");
                    setTimeout(function()
                    {
                        $("#modal_gestores_create_view").modal("toggle");
                    },500);
                });
            
            

            document.querySelector("#sincronizando3").style.display = "none";
            document.querySelector("#sincronizando4").style.display = "none";

            $("#tbl_gestores").DataTable();
            
            $("#tbl_validaciones").DataTable(
                {
                    dom: 'Bfrtip',
                        buttons: [
                            {
                                extend: 'excelHtml5',
                                title: 'Validaciones',
                                exportOptions: {
                                    columns: [ 0,1,2,3,4,5,6,7,8,9,10,11,12]
                                }
                            }                                      
                        ]
                    
                }
                );

        }


        function abrirModalExcel()
        {
            $("#modal_import_1").modal("toggle");
        }


        function abrirModalNic(nic,cliente,gestor,fecha)
        {
            document.querySelector("#id_nic").value = nic;
            document.querySelector("#id_cliente").value = cliente;
            document.querySelector("#id_gestor").value = gestor;
            document.querySelector("#fecha_visita").value = fecha;

            
            var array = 
            {
                nic : nic,
                opc : "1"
            }
            consultaAjax("../../consultaInformacionValidador",array,15000,"POST",1);
        }

            
        function abrirModalGestores()
        {
            $("#modal_gestores_view").modal("toggle");
        }


        function updateGestorVisita()
        {
            if(document.querySelector("#fecha_visita").value == "")
            {
                mostrarModal(1,null,"Actualizar asignación","Hace falta ingresar la fecha de la visita.\n",0,"Aceptar","",null); 
                return;
            }

            var array = 
            {
                nic : document.querySelector("#id_nic").value,
                gestor: document.querySelector("#id_gestor_select").value,
                fecha : document.querySelector("#fecha_visita").value,
                opc : "3"
            }
            consultaAjax("../../consultaInformacionValidador",array,15000,"POST",3);
        }


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
                    if(opcion == "1") //Consulta NIC Liquidación
                    {
                        
                        var html = '';
                        html += '<table id="tbl_liquidacion" class="table table-striped table-bordered" cellspacing="0" width="99%">';
                        html += '    <thead>';
                        html += '        <tr>';
                        html += '            <th style="width:200px;">UNICOM</th>';
                        html += '            <th style="width:200px;">TIPO RECIBO</th>';
                        html += '            <th style="width:200px;">ESTADO RECIBO</th>';
                        html += '            <th style="width:20px;">SIMBOLO VARIABLE</th>';
                        html += '            <th style="width:20px;">FECHA FACTURACIÓN</th>';
                        html += '            <th style="width:50px;">FECHA VENCIMIENTO</th>';
                        html += '            <th style="width:50px;">IMPORTE</th>';
                        html += '            <th style="width:50px;">COBRADO</th>';
                        html += '            <th style="width:50px;">PENDIENTE</th>';
                        html += '        </tr>';
                        html += '    </thead>';
                        html += '    <tbody>';
                        
                        var aux1,aux2,aux3,aux4,aux5,aux6,aux7,aux8,auxValor1,auxValor2,auxValor3,auxValor4,auxValor5,auxValor6,auxValor7,auxValor8;;
                        aux1= aux2 = aux3 = aux4 = aux5 = aux6 = aux7 = aux8 = auxValor1= auxValor2 = auxValor3 = auxValor4 = auxValor5 = auxValor6 = auxValor7 = auxValor8 =  0;
                        for (var i = 0; i < data.length; i++) {



                                html += '<tr>';
                                    html += '    <td style="text-align:center;">' + data[i].unicom +'</td>';
                                    html += '    <td style="text-align:center;">' + data[i].tipo_recibo +'</td>';
                                    html += '    <td style="text-align:center;">' + data[i].estado_recibo +'</td>';
                                    html += '    <td style="text-align:center;">' + data[i].simbolo_variable +'</td>';
                                    html += '    <td style="text-align:center;">' + data[i].fecha_facturacion +'</td>';
                                    html += '    <td style="text-align:center;">' + data[i].fecha_vencimiento +'</td>';
                                    data[i].importe = data[i].importe.replace(".00","");
                                    data[i].cobrado = data[i].cobrado.replace(".00","");
                                    data[i].importe = (data[i].importe == "" ? "0" : data[i].importe);
                                    data[i].cobrado = (data[i].cobrado == "" ? "0" : data[i].cobrado);
                                    html += '    <td style="text-align:center;">' + data[i].importe +'</td>';
                                    if(data[i].cobrado > 0)
                                        html += '    <td style="text-align:center;color:green">' + data[i].cobrado +'</td>';
                                    else
                                        html += '    <td style="text-align:center;">' + data[i].cobrado +'</td>';
                                    if((parseInt(data[i].importe) - parseInt(data[i].cobrado)) > 0)
                                        html += '    <td style="text-align:center;color:red">' + (parseInt(data[i].importe) - parseInt(data[i].cobrado)) +'</td>';
                                    else
                                        html += '    <td style="text-align:center;">' + (parseInt(data[i].importe) - parseInt(data[i].cobrado)) +'</td>';
                                    html += '</tr>';
                                    
                                    if(data[i].val_4 == "01 CUOTA INICIAL")
                                    {
                                        aux1++;
                                        auxValor1 = auxValor1 + (parseInt(data[i].importe) - parseInt(data[i].cobrado));
                                    }

                                    if(data[i].val_4 == "02 A FINANCIAR")
                                    {
                                     aux2++;   
                                     auxValor2 = auxValor2 + (parseInt(data[i].importe) - parseInt(data[i].cobrado));   
                                    }

                                    if(data[i].val_4 == "03 CARGOS VARIOS" || data[i].val_4 == "03 CARGOS VARIOS ")
                                    {
                                     aux3++;   
                                     auxValor3 = auxValor3 + (parseInt(data[i].importe) - parseInt(data[i].cobrado));   
                                    }

                                    if(data[i].val_4 == "04 SEGUNDO ACUERDO")
                                    {
                                     aux4++;   
                                     auxValor4 = auxValor4 + (parseInt(data[i].importe) - parseInt(data[i].cobrado));   
                                    }

                                    if(data[i].val_4 == "05 PRESCRITA" || data[i].val_4 == "05 PRESCRITA ")
                                    {
                                     aux5++;   
                                     auxValor5 = auxValor5 + (parseInt(data[i].importe) - parseInt(data[i].cobrado));   
                                    }

                                    if(data[i].val_4 == "05.1 IRREGULARIDAD PRESCRITA" || data[i].val_4 == "05.1 IRREGULARIDAD PRESCRITA ")
                                    {
                                     aux6++;   
                                     auxValor6 = auxValor6 + (parseInt(data[i].importe) - parseInt(data[i].cobrado));   
                                    }   

                                    if(data[i].val_4 == "06 UNICOM 55")
                                    {
                                     aux7++;   
                                     auxValor7 = auxValor7 + (parseInt(data[i].importe) - parseInt(data[i].cobrado));   
                                    }

                                    if(data[i].val_4 == "07 IRREGULARIDAD")
                                    {
                                     aux8++;   
                                     auxValor8 = auxValor8 + (parseInt(data[i].importe) - parseInt(data[i].cobrado));   
                                    }
                            };  
                        html += '    </tbody>';
                        html += '</table>';


                        document.querySelector("#row1").children[2].innerHTML = aux1;
                        document.querySelector("#row1").children[3].innerHTML = "$" + auxValor1;

                        document.querySelector("#row2").children[2].innerHTML = aux2;
                        document.querySelector("#row2").children[3].innerHTML = "$" + auxValor2;

                        document.querySelector("#row3").children[2].innerHTML = aux3;
                        document.querySelector("#row3").children[3].innerHTML = "$" + auxValor3;

                        document.querySelector("#row4").children[2].innerHTML = aux8;
                        document.querySelector("#row4").children[3].innerHTML = "$" + auxValor8;

                        document.querySelector("#row5").children[2].innerHTML = aux5;
                        document.querySelector("#row5").children[3].innerHTML = "$" + auxValor5;

                        document.querySelector("#row6").children[2].innerHTML = aux6;
                        document.querySelector("#row6").children[3].innerHTML = "$" + auxValor6;

                        document.querySelector("#row7").children[2].innerHTML = aux7;
                        document.querySelector("#row7").children[3].innerHTML = "$" + auxValor7;

                        document.querySelector("#row8").children[2].innerHTML = aux4;
                        document.querySelector("#row8").children[3].innerHTML = "$" + auxValor4;

                        var sumaCantidadCancelar = aux1 + aux2 + aux3 + aux8;
                        var sumaCancelar = auxValor1 + auxValor2 + auxValor3 + auxValor8;

                        document.querySelector("#rw1").innerHTML = sumaCantidadCancelar;
                        document.querySelector("#rw2").innerHTML = "$" + sumaCancelar;

                        var sumaCantidadDescuento = aux5 + aux6 + aux7;
                        var sumaDescuento = auxValor5 + auxValor6 + auxValor7 ;
                        
                        document.querySelector("#rw3").innerHTML = sumaCantidadDescuento;
                        document.querySelector("#rw4").innerHTML = "$" + sumaDescuento;


                        var sumaCantidadDescuentoCumplimiento = aux4;
                        var sumaDescuentoCumplimiento = auxValor4;

                        document.querySelector("#rw5").innerHTML = sumaCantidadDescuentoCumplimiento;
                        document.querySelector("#rw6").innerHTML = "$" + sumaDescuentoCumplimiento;

                        var totalCantidad = sumaCantidadCancelar + sumaCantidadDescuento + sumaCantidadDescuentoCumplimiento;
                        var totalSuma = sumaCancelar + sumaDescuento + sumaDescuentoCumplimiento;

                        document.querySelector("#rw7").innerHTML = totalCantidad;
                        document.querySelector("#rw8").innerHTML = "$" + totalSuma;

                        document.querySelector("#tbl_datos_liquidadcion").innerHTML = html;

                        $("#tbl_liquidacion").DataTable(
                        {
                            dom: 'Bfrtip',
                                buttons: [
                                    {
                                        extend: 'excelHtml5',
                                        title: 'Liquidación',
                                        exportOptions: {
                                            columns: [ 0,1,2,3,4,5,6,7,8]
                                        }
                                    }                                      
                                ]
                            
                        }
                        );


                        //Ocultar elementos

                        if(aux2 == 0)
                            document.querySelector("#row2").style.display = "none";
                        else
                            document.querySelector("#row2").style.display = "table-row";

                        if(aux3 == 0)
                            document.querySelector("#row3").style.display = "none";
                        else
                            document.querySelector("#row3").style.display = "table-row";

                        if(aux8 == 0)
                            document.querySelector("#row4").style.display = "none";
                        else
                            document.querySelector("#row4").style.display = "table-row";


                        if(aux6 == 0)
                            document.querySelector("#row6").style.display = "none";
                        else
                            document.querySelector("#row6").style.display = "table-row";

                        if(aux7 == 0)
                            document.querySelector("#row7").style.display = "none";
                        else
                            document.querySelector("#row7").style.display = "table-row";


                        
                        if(aux4 == 0)
                        {
                            document.querySelector("#row8").style.display = "none";
                            document.querySelector("#row9").style.display = "none";
                        }else{
                            
                            document.querySelector("#row8").style.display = "table-row";
                            document.querySelector("#row9").style.display = "table-row";
                        }
                        
                           // mostrarModal(2,null,"Estados GOM","El cambio de estado de la GOM, se ha hecho correctemante.\n",0,"Aceptar","",null); 
                            $("#modal_liquidacion_view").modal("toggle");
                           /* for (var i = 0; i < select.length; i++) {
                                select[i].addEventListener("change",function()
                                {
                                    var array = 
                                    {
                                        gom : this.parentElement.parentElement.children[0].innerHTML,
                                        esta: this.value,
                                        opc : "16"
                                    }
                                    consultaAjax("../../consultaActiMate",array,15000,"POST",1);
                                });
                            };*/
                        ocultarSincronizacion();
                    }
                    if(opcion == 2)
                    {
                        if(data == "-1")
                        {
                            mostrarModal(1,null,"Creación gestor","Ya existe un gestor con ese número de identificación.\n",0,"Aceptar","",null);
                            ocultarSincronizacion();       
                            return;
                        }

                        mostrarModal(2,null,"Creación gestor","Se ha creado correctamente el gestor.\n",0,"Aceptar","",null);
                        window.location.reload();
                    }

                    if(opcion == 3)
                    {

                        mostrarModal(2,null,"Actualizar asignación visita","Se ha actualizado correctamente asiganción de la visita del gestor.\n",0,"Aceptar","",null);
                        window.location.reload();
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



        function saveGestor()
        {
            if(document.querySelector("#txt_nombre_gestor").value == "" ||
                document.querySelector("#txt_iden_gestor").value == "")
            {
                mostrarModal(1,null,"Guardar gestor","Hace falta ingresar información\n",0,"Aceptar","",null);
                return;
            }
            
            var array = 
            {
                nombre : document.querySelector("#txt_nombre_gestor").value,
                gestor : document.querySelector("#txt_iden_gestor").value,
                opc : "2"
            }
            consultaAjax("../../consultaInformacionValidador",array,15000,"POST",2);

            
            
        }


    </script>
@stop

