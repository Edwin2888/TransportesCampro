@extends('template.index')

@section('title')
    Odometro vehículo
@stop

@section('title-section')
    Odometro vehículo
@stop

@section('css2')
    <link rel="stylesheet" type="text/css" href="../../css/transporte.css">
@stop

<main>

<div class="container">

    <!-- MODAL-->
    @include('proyectos.transporte.modal.modalCapturaOdometroWeb')    


    @include('proyectos.transporte.secciones.frmEncabezadoVehiculo')    
    <br>
    @if($acceso == "W")
        <a href="#" id="capture_odometer_web" class="btn btn-primary btn-cam-trans btn-sm" style="margin-left:31px;margin-bottom:10px;"><i class="fa fa-plus"></i> &nbsp; Captura odómetro</a>
    @endif
    <a href="../../selectVehiculo/{{$datos->placa}}" class="btn btn-primary btn-cam-trans btn-sm" style="margin-left:31px;margin-bottom:10px;"><i class="fa fa-arrows-alt"></i> &nbsp; Cerrar</a>
    <div class="row">
        <section>
            @include('proyectos.transporte.tables.tblOdometroVehiculo')
        </section>
    </div>
</div>
</main>
<script type="text/javascript">
    window.addEventListener('load',ini);
    
    /* FIN: FUNCIONES PARA PESTAÑAS */
    function ini() {

        
        @if($acceso == "W")
        document.querySelector("#capture_odometer_web").addEventListener("click",function()
        {
            document.querySelector("#txt_kilometraje").value = "";
            document.querySelector("#fec_registro").value = "";
            $("#modal_odometro").modal("toggle");
        });
        @endif
        document.querySelector("#save_odometer_btn").addEventListener("click",saveOdometer);
        ocultarSincronizacionFondoBlanco();
        /* FIN: FUNCIONES PARA PESTAÑAS */

        
    }
    

     function eliminarOdometro(placa,id)
        {
            if(confirm("¿Seguro que desea eliminar el registro de odometros?"))
            {
                var datos = {
                    placa: placa,
                    id: id,
                    opc: 33
                }
                consultaAjax("../../rutaInsercionTransporte", datos, 15000, "POST", 2);
            }
        }


    function saveOdometer()
    {
        if(document.querySelector("#txt_kilometraje").value == "" ||
            document.querySelector("#fec_registro").value == "")
        {
            mostrarModal(1,null,"Mensaje","Hace falta ingresar información, para guardar la captura del odómetro\n",0,"Aceptar","",null);
            return;
        }

        var fec = document.querySelector("#fec_registro").value.split("/")[2] + "-" + document.querySelector("#fec_registro").value.split("/")[1] + "-" + document.querySelector("#fec_registro").value.split("/")[0];
        //Consulta Fotografías
        var datosA = {
            placa: document.querySelector("#txt_matricula_vehiculo").value,
            fecha: fec,
            guardar_odometro: true,
            kilometraje: document.querySelector("#txt_kilometraje").value,
            opc: 2
        };

        consultaAjax("../../consultarmoviltransporte", datosA, 150000, "POST", 1);
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
                if(opcion ==1)//Save Odometro
                {
                    if(data == 3)
                    {
                        mostrarModal(1,null,"Mensaje","El kilometraje ingresado debe ser mayor al actual.\n",0,"Aceptar","",null);
                    }

                    if(data == 1)
                    {
                        mostrarModal(2,null,"Mensaje","Se ha guardado correctamente la captura del odómetro.\n",0,"Aceptar","",null);
                        setTimeout(function()
                        {
                            location.reload();
                        },1000);
                    }
                }

                if(opcion ==2)//Eliminar odometro
                {
                    mostrarModal(2,null,"Eliminar odometro","Se ha eliminado correctamen el registro del odometro.\n",0,"Aceptar","",null);


                    ocultarSincronizacion();
                     location.reload();
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
</script>