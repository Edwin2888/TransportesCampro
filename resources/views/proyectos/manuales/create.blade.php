@extends('template.index')

@section('title')
    Administrador de  Manuales
@stop

@section('title-section')
    Administrador de  Manuales
@stop

<main>
        @include('proyectos.manuales.modal.modalManual')
    <div class="container">
        @include('proyectos.manuales.secciones.filter')
        <br>
        @include('proyectos.manuales.tables.tblmanuales')
    </div>
</main>


<script type="text/javascript">
    window.addEventListener('load',ini);
    
    function ini() {
        
        var alto = screen.height - 400;
            var altopx = alto+"px";
           
        var  tbl2=   $('#tbl_manuales').dataTable({
                    "scrollX":  "100%",
                    "scrolY":   altopx,
                    "paging":   true,
                    "searching": true,
                    "responsive":      false,
                    "colReorder":      true,
                    "order": [[ 0, 'asc' ]],
                    dom: 'T <"clear">lfrtip',
                    tableTools: {
                        "sSwfPath": "../../media/copy_csv_xls_pdf.swf", "aButtons": ["copy","xls" ]
                    }
                }); 

        

        document.querySelector("#btnCrearManual").addEventListener("click",function()
        {
            document.querySelector("#manual_id").value = "0";

            document.querySelector("#id_proyecto_add").selectedIndex = 0;
            document.querySelector("#txtTituloManual").value = "";
            document.querySelector("#txtDescripcionManual").value = "";
            document.querySelector("#txtEmbebidoManial").value = "";
            document.querySelector("#txtversion").value = "";
            document.querySelector("#select_tipo_manual").selectedIndex = 0;


            document.querySelector("#btn-save-primer-mante").innerHTML = "Guardar manual";
            $("#moda_manual").modal('toggle');
        }); 

        ocultarSincronizacionFondoBlanco();
    }

    function saveManual(evt)
    {
        var evento = evt || window.event;
        if(document.querySelector("#id_proyecto_add").value == "0" ||
        document.querySelector("#txtTituloManual").value == "" ||
        document.querySelector("#txtDescripcionManual").value == "" ||
        document.querySelector("#txtEmbebidoManial").value == "" ||
        document.querySelector("#txtversion").value == "" ||
        document.querySelector("#select_tipo_manual").value == "")
        {
            mostrarModal(1, null, "Mensaje", "Hay campos vacios\n", 0, "Aceptar", "", null);
            evento.preventDefault();
            return;
        }
    }

    function abrirModal(id,proyecto,titulo,descripcion,embebido,estado,version,tipo,codigo)
    {
        document.querySelector("#id_proyecto_add").value = proyecto;
        document.querySelector("#txtTituloManual").value = titulo;
        document.querySelector("#txtDescripcionManual").value = descripcion;
        document.querySelector("#txtEmbebidoManial").value = embebido;
        document.querySelector("#manual_id").value = id;
        document.querySelector("#txtversion").value = version;
        document.querySelector("#select_tipo_manual").value = tipo;
        document.querySelector("#txtcodigo").value = codigo;
        document.querySelector("#btn-save-primer-mante").innerHTML = "Actualizar manual";
        $("#moda_manual").modal('toggle');
        

    }

</script>