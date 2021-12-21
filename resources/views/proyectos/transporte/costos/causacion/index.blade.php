@extends('template.index')

@section('title')
    Causación costos
@stop

@section('title-section')
    Causación costos
@stop

@section('css2')
    <link rel="stylesheet" type="text/css" href="../../css/transporte.css">
@stop
<!-- http://www.ajaxshake.com/demo/ES/833/453fddb1/mensajes-de-notificacion-estilo-android-con-jquery-toastmessage.html-->

<main>

    @include('proyectos.transporte.costos.causacion.modal.modalCreateCausacion')

    <div class="container">
        @include('proyectos.transporte.costos.causacion.frm.frmFilterCausacion')
        @include('proyectos.transporte.costos.causacion.tables.tblCausacion')
    </div>
</main>

<script type="text/javascript">
    window.addEventListener('load',ini);

    /* FIN: FUNCIONES PARA PESTAÑAS */
    function ini() {

        var alto = screen.height - 400;
        var altopx = alto+"px";
        
        $('#CheApro').bootstrapToggle({
              on: 'Si',
              off: 'No'
            });

        $('#tbl_conceptos').dataTable({
            "scrollX":  "100%",
            "scrolY":   altopx,
            "paging":   true,
            "searching": true,
            "responsive":      false,
            "colReorder":      true,
            dom: 'T <"clear">lfrtip',
            tableTools: {
                "sSwfPath": "../../media/copy_csv_xls_pdf.swf", "aButtons": ["copy","xls" ]
            }
        }); 

        @if(!Session::has('selProveedorFilter'))
            document.querySelector("#selProveedorFilter").selectedIndex = 0;
        @endif
        ocultarSincronizacionFondoBlanco();
        /* FIN: FUNCIONES PARA PESTAÑAS */
    }


    function abrirModalConcepto()
    {   
        document.querySelector("#txtPlaca").value = "";
        document.querySelector("#txtDesConcepto").value = "";
        document.querySelector("#txtFecha").value = "";
        document.querySelector("#txtObser").value = "";
        document.querySelector("#txtValor").value = "";
        document.querySelector("#txtRecepcion").value = "";
        document.querySelector("#CheApro").checked = false;
        document.querySelector("#selContratante").selectedIndex = 0;
        document.querySelector("#selProveedor").selectedIndex = 0;
        document.querySelector("#selConcepto").selectedIndex = 0;

        $('#CheApro').bootstrapToggle('off');

        document.querySelector("#txtPlaca").readOnly = false;
        document.querySelector("#txtDesConcepto").readOnly = false;
        document.querySelector("#txtFecha").readOnly = false;
        document.querySelector("#txtObser").readOnly = false;
        document.querySelector("#txtValor").readOnly = false;
        document.querySelector("#txtRecepcion").readOnly = false;
        document.querySelector("#CheApro").readOnly = false;
        document.querySelector("#CheApro").disabled = false;
        document.querySelector("#selContratante").disabled = false;
        document.querySelector("#selProveedor").disabled = false;
        document.querySelector("#selConcepto").disabled = false;
        document.querySelector("#btn-add-nodos-orden").style.display = "inline-block";
        $("#modal_create_causacion").modal("toggle");
    }


    function consultaPlaca()
    {
        var datos = {
                    placa:  document.querySelector("#txtPlaca").value,
                    opc: 9
                };
        consultaAjax("../../rutaConsultaTransporte", datos, 15000, "POST", 1); 
    }

    function saveCausacion()
    {
        var txtPlaca = document.querySelector("#txtPlaca").value;
        var txtDesConcepto = document.querySelector("#txtDesConcepto").value;
        var txtFecha = document.querySelector("#txtFecha").value;
        var txtObser = document.querySelector("#txtObser").value;
        var txtValor = document.querySelector("#txtValor").value;
        var txtRecepcion = document.querySelector("#txtRecepcion").value;
        var CheApro = document.querySelector("#CheApro");
        var selConcepto = document.querySelector("#selConcepto");
        var selProveedor = document.querySelector("#selProveedor");
        var selContratante = document.querySelector("#selContratante");

        if(txtPlaca == "" || 
            txtDesConcepto == "" || 
            txtFecha == "" || 
            txtObser == "" || 
            txtValor == "" || 
            txtRecepcion == "")
        {
            mostrarModal(1, null, "Guardar causación", "Hace falta el diligenciar campos\n", 0, "Aceptar", "", null);   
            return;
        }

        if(selConcepto.selectedIndex == 0   || 
            selProveedor.selectedIndex == 0   || 
            selContratante.selectedIndex == 0  )
        {
            mostrarModal(1, null, "Guardar causación", "Hace falta el diligenciar campos\n", 0, "Aceptar", "", null);   
            return;
        }

        var datos = {
                    placa:  document.querySelector("#txtPlaca").value,
                    des:  document.querySelector("#txtDesConcepto").value,
                    fecha:  document.querySelector("#txtFecha").value,
                    obser:  document.querySelector("#txtObser").value,
                    valor:  document.querySelector("#txtValor").value,
                    recepcion:  document.querySelector("#txtRecepcion").value,
                    apro:  (document.querySelector("#CheApro").checked ? 1 : 0),
                    concepto:  document.querySelector("#selConcepto").value,
                    prov:  document.querySelector("#selProveedor").value,
                    contr:  document.querySelector("#selContratante").value,
                    opc: 1
                };

        consultaAjax("{{url('/')}}/transporte/costos/causacion/WServices", datos, 15000, "POST", 2); 
    }


    function abrirModalEditCausacion(placa,refec,fecha,obser,valor,recep,id_pro,apro,contra,concep)
    {

        document.querySelector("#txtPlaca").value = placa;
        document.querySelector("#txtDesConcepto").value = refec;
        fecha = fecha.split("-")[2] + "/" + fecha.split("-")[1] + "/" + fecha.split("-")[0];
        document.querySelector("#txtFecha").value = fecha;
        document.querySelector("#txtObser").value = obser;
        document.querySelector("#txtValor").value = valor;
        document.querySelector("#txtRecepcion").value = recep;
        
        if(apro == "1")
            $('#CheApro').bootstrapToggle('on');
        else
            $('#CheApro').bootstrapToggle('off');

        document.querySelector("#selContratante").value = contra;
        document.querySelector("#selProveedor").value = id_pro;
        document.querySelector("#selConcepto").value = concep;
        document.querySelector("#txtPlaca").readOnly = true;
        document.querySelector("#txtDesConcepto").readOnly = true;
        document.querySelector("#txtFecha").readOnly = true;
        document.querySelector("#txtObser").readOnly = true;
        document.querySelector("#txtValor").readOnly = true;
        document.querySelector("#txtRecepcion").readOnly = true;
        document.querySelector("#CheApro").readOnly = true;
        document.querySelector("#CheApro").disabled = true;
        document.querySelector("#selContratante").disabled = true;
        document.querySelector("#selProveedor").disabled = true;
        document.querySelector("#selConcepto").disabled = true;
        document.querySelector("#btn-add-nodos-orden").style.display = "none";
        $("#modal_create_causacion").modal("toggle");
    }

    


    function consultaAjax(route,datos,tiempoEspera,type,opcion,edita,dato)
    {
    
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
                   
                    if(opcion == 1)//Consulta Existencia placa
                    {
                        if(data <= 0)
                        {
                           document.querySelector("#txtPlaca").value = "";
                           if(data == -1)
                               mostrarModal(1,null,"Consulta vehículo","El vehículo esta retirado.\n",0,"Aceptar","",null);                  
                           else
                               mostrarModal(1,null,"Consulta vehículo","El vehículo no esta registrado.\n",0,"Aceptar","",null);
                        }
                        ocultarSincronizacion();
                    }

                    if(opcion == 2) //Save Causación
                    {
                           mostrarModal(2,null,"Guardar Causación","Se ha guardado correctamente la causacion.\n",0,"Aceptar","",null);
                           setTimeout(function()
                           {
                                location.reload();
                           },500);
                           
                    }

                    
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

</script>