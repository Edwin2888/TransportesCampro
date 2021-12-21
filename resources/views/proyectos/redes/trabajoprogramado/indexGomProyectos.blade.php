@extends('template.index')

@section('title')
	Consulta GOM
@stop

@section('title-section')
    Consulta GOM
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
        top: 0px;
    }
    </style>
	<main>
 
    	<div class="container-fluid">
                <div class="side-body" id="cuerpo" style="margin-top:20px;margin-left:0px;">
                        @include('proyectos.redes.trabajoprogramado.secciones.filterGom')
                </div>

                <div class="tbl_index_proyectos" id="tbl_index_proyectos" data-pos="0">
                    @include('proyectos.redes.trabajoprogramado.secciones.tableConsultaGom')
                </div>
            </div>
    	</main>


    <script type="text/javascript">

        window.addEventListener('load',indexGom);

        function indexGom()
        {

            var alto = screen.height - 400;
            var altopx = alto+"px";
            var tbl2=   $('#tbl_gom_estados').dataTable({
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

        }

        function cambiarEstadoGOM(ele)
        {
            var gom = ele.dataset.gom;
            var estado = ele.value;
            var estadoAnterior = ele.dataset.anterior;

            if(confirm("¿Seguro que desea cambiar el estado de la GOM?"))
            {
                var array = 
                    {
                        gom : gom,
                        esta: estado,
                        opc : "34"
                    }
                
                consultaAjax("{{url('/')}}/consultaActiMate",array,15000,"POST",1);
            }
            else
                ele.value = (estadoAnterior == 0 ? 1 : estadoAnterior);
        }


         function consultaAjax(route,datos,tiempoEspera,type,opcion,collback,dato,ele)
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
                    if(opcion == "1") //Cambio de estado de la GOM
                    {
                        mostrarModal(2,null,"Estados GOM","El cambio de estado de la GOM, se ha hecho correctemante.\n",0,"Aceptar","",null); 
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

        
