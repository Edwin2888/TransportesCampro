@extends('template.index')

@section('title')
    Combustible
@stop

@section('title-section')
    Combustible
@stop

@section('css2')
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/css/transporte.css">
@stop
<!-- http://www.ajaxshake.com/demo/ES/833/453fddb1/mensajes-de-notificacion-estilo-android-con-jquery-toastmessage.html-->

<main>
    @include('proyectos.transporte.costos.combustible.modal.modalImportCombustible')
    @include('proyectos.transporte.costos.combustible.modal.modalExporteCombustible')

    <div class="container">
        @include('proyectos.transporte.costos.combustible.frm.frmFilterCombustible')
        @include('proyectos.transporte.costos.combustible.tables.tblCombustible')
    </div>
</main>

<script type="text/javascript">
    window.addEventListener('load',ini);

    /* FIN: FUNCIONES PARA PESTAÑAS */
    function ini() {

        var alto = screen.height - 400;
        var altopx = alto+"px";
       
        $('#tbl_combustible').dataTable({
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

    function abrirModalCargaCombustible()
    {   
        $("#modal_import").modal("toggle");
    }


    function abrirModalExporteCombustible()
    {
        document.querySelector("#fecha_exporte_1").value = "";
        document.querySelector("#fecha_exporte_2").value = "";
        $("#modal_exporte_combustible").modal("toggle");
    }

    function validarExport()
    {
        var evt = window.event;

        if(document.querySelector("#fecha_exporte_1").value == "")
        {
            mostrarModal(1, null, "Generar exporte combustible", "Hace falta ingresar la fecha de inicio\n", 0, "Aceptar", "", null);
            evt.preventDefault();
            return;
        }

        if(document.querySelector("#fecha_exporte_2").value == "")
        {
            mostrarModal(1, null, "Generar exporte combustible", "Hace falta ingresar la fecha de fin\n", 0, "Aceptar", "", null);
            evt.preventDefault();
            return;
        }

        
        
    }

</script>