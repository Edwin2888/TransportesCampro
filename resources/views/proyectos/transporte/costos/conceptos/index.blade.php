@extends('template.index')

@section('title')
    Conceptos costos
@stop

@section('title-section')
    Conceptos costos
@stop

@section('css2')
    <link rel="stylesheet" type="text/css" href="../../css/transporte.css">
@stop
<!-- http://www.ajaxshake.com/demo/ES/833/453fddb1/mensajes-de-notificacion-estilo-android-con-jquery-toastmessage.html-->

<main>

    @include('proyectos.transporte.costos.conceptos.modal.modalCreateConcepto')

    <div class="container">
        @include('proyectos.transporte.costos.conceptos.frm.frmFilterConceptos')

        @include('proyectos.transporte.costos.conceptos.tables.tblConceptos')
    </div>
</main>

<script type="text/javascript">
    window.addEventListener('load',ini);

    /* FIN: FUNCIONES PARA PESTAÑAS */
    function ini() {

        var alto = screen.height - 400;
        var altopx = alto+"px";
       
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

        ocultarSincronizacionFondoBlanco();
        /* FIN: FUNCIONES PARA PESTAÑAS */
    }


    function abrirModalConcepto()
    {   
        document.querySelector("#id").value = "";
        document.querySelector("#txtNombreConcepto").value = "";
        document.querySelector("#txtDesConcepto").value = "";
        document.querySelector("#selEstado").selectedIndex = 0;
        document.querySelector("#file_archiv_impor").value = "";
        document.querySelector("#anexo").style.display = "none";
        document.querySelector("#anexo").href = "";
        $("#modal_create_concepto").modal("toggle");
    }


    function saveConcepto(evt)
    {
        var nombre = document.querySelector("#txtNombreConcepto").value;
        var des = document.querySelector("#txtDesConcepto").value;
        var estado = document.querySelector("#selEstado").value;
        var file = document.querySelector("#file_archiv_impor").value;

        if(nombre == "")
        {
            mostrarModal(1, null, "Guardar concepto", "Hace falta el nombre\n", 0, "Aceptar", "", null);
            var evt = evt || window.event;
            evt.preventDefault();
            return;
        }

        if(des == "")
        {
            mostrarModal(1, null, "Guardar concepto", "Hace falta la descripci+on\n", 0, "Aceptar", "", null);
            var evt = evt || window.event;
            evt.preventDefault();
            return;
        }

        if( document.querySelector("#selEstado").selectedIndex == 0)
        {
            mostrarModal(1, null, "Guardar concepto", "No ha seleccionado el estado\n", 0, "Aceptar", "", null);
            var evt = evt || window.event;
            evt.preventDefault();
            return;
        }

        if(file == "" && document.querySelector("#anexo").style.display == "none")
        {
            mostrarModal(1, null, "Guardar concepto", "No ha seleccionado el anexo\n", 0, "Aceptar", "", null);
            var evt = evt || window.event;
            evt.preventDefault();
            return;
        }
        mostrarSincronizacion();
        //consultaAjax("{{url('/')}}/rutaConsultaTransporte", datos, 15000, "POST", 14); 
    }


    function abrirModalEditConcepto(nombre,des,estado,id,anexo)
    {
        document.querySelector("#id").value = id;
        document.querySelector("#txtNombreConcepto").value = nombre;
        document.querySelector("#txtDesConcepto").value = des;
        document.querySelector("#selEstado").value = estado;
        document.querySelector("#file_archiv_impor").value = "";
        document.querySelector("#anexo").style.display = "block";
        document.querySelector("#anexo").href = "http://190.60.248.195/" + anexo;
        $("#modal_create_concepto").modal("toggle");
    }

    function deleteConcepto(id)
    {
        if(confirm("¿Seguro que desea eliminar el concepto"))
        {
            mostrarSincronizacion();
            document.querySelector("#id_costo_delete").value = id;
            document.querySelector("#id_formulario_delete").submit();
        }
    }


</script>