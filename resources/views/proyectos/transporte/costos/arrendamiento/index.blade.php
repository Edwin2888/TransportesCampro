@extends('template.index')

@section('title')
    Costos de Arrendamiento
@stop

@section('title-section')
    Costos de Arrendamiento
@stop

@section('css2')
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/css/transporte.css">
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/css/calendario.css">
@stop
<!-- http://www.ajaxshake.com/demo/ES/833/453fddb1/mensajes-de-notificacion-estilo-android-con-jquery-toastmessage.html-->

<main>

    <!--@include('proyectos.transporte.costos.conceptos.modal.modalCreateConcepto')-->

    <div class="container">
        @include('proyectos.transporte.costos.arrendamiento.frm.calendario')

        @include('proyectos.transporte.costos.arrendamiento.tables.tblarrendamiento')

        
    </div>
</main>

<script type="text/javascript">
    window.addEventListener('load',ini);

    /* FIN: FUNCIONES PARA PESTAÑAS */
    function ini() {

        calculaTotal();        

        

        var alto = screen.height - 400;
        var altopx = alto+"px";
       
        $('#tbl_arrendamieno').dataTable({
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
        
        ocultarSincronizacionFondoBlanco();
        /* FIN: FUNCIONES PARA PESTAÑAS */
    }

    function checkDatos(ele)
    {
        if(ele.dataset.tipo == "1") //No esta en taller
        {
            var padre = ele.parentElement.parentElement;
            if(ele.dataset.estado == "1")   
            {
                $(padre).removeClass('sucess');
                $(padre).addClass('danger');

                padre.children[2].children[0].innerHTML = "INACTIVO";

                $(padre.children[2]).removeClass('sucess');
                $(padre.children[2]).addClass('danger');
                
                padre.children[2].children[1].src = "{{url('/')}}/img/exclamation-mark.png";
                ele.dataset.estado = "2";
            }
            else
            {
                $(padre).addClass('sucess');
                $(padre).removeClass('danger');
                
                $(padre.children[2]).addClass('sucess');
                $(padre.children[2]).removeClass('danger');

                padre.children[2].children[0].innerHTML = "ACTIVO";
                padre.children[2].children[1].src = "{{url('/')}}/img/checked.png";
                ele.dataset.estado = "1";
            }
        }
        else //Con ingreso a taller
        {

        }

        calculaTotal();
    }

    function savePlanilla()
    {

        var listaCkeck = $(".checkedelemento");
        var diaTotal = 0;
        var arreglo = [];
        for (var i = 0; i < listaCkeck.length; i++) {
            arreglo.push(
            {
                "check" : (listaCkeck[i].checked ? 1 : 0),
                "dia" : listaCkeck[i].dataset.dia,
                "pre" : listaCkeck[i].dataset.prefijo
            });
        };

        var datos = {
              "opc": 1,
              "data" : arreglo,
              "mes" :  {{$mesA}},
              "anio" : {{$anos[1]}},
              "placa" : "{{$placa}}",
              "preUser" : "{{$preUser}}",
              "canon" : document.querySelector("#id_canon").innerHTML.replace("$","").replace(",","").replace(",",""),
              "total" : document.querySelector("#total_valor").innerHTML.replace("$","").replace(",","").replace(",",""),
              "dias" : document.querySelector("#num_dias").innerHTML
            };

        consultaAjax("{{url('/')}}/transporte/costos/arrendamiento/saveWebService", datos, 150000, "POST", 1);

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
                    if(opcion == 1)//Guardar planilla
                    {
                        document.querySelector("#boton_2_modal").style.display  = "none";
                        document.querySelector("#boton_1_modal").style.display  = "none";

                        mostrarModal(2,null,"Se ha guardado correctamente la planilla N° " + data,"","","",null);                     
                        ocultarSincronizacion();
                        setTimeout(function()
                        {
                            location.reload();
                        },600);
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

    


</script>