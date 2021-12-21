@extends('template.index')

@section('title')
    Consulta documentos de arrendamiento
@stop

@section('title-section')
    Consulta documentos de arrendamiento
@stop

@section('css2')
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/css/transporte.css">
@stop

<main>
    <div class="container">
        
        <!--MODAL -->
            @include('proyectos.transporte.costos.arrendamiento.modal.modalDescargarMasivoProveedores') 

        <!--FRM-->
            @include('proyectos.transporte.costos.arrendamiento.frm.frmFilterArrendamientoDocumentos') 

        

        @include('proyectos.transporte.costos.arrendamiento.tables.tblarrendamientoconsulta')       
        
    </div>
</main>

<script type="text/javascript">
    window.addEventListener('load',ini);

    var table ;
    /* FIN: FUNCIONES PARA PESTAÑAS */
    function ini() {

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


    function abrirModalMasivo()
    {
        $("#modal_masivo_proveedor").modal("toggle");
    }

    function validarExport(evt)
    {
        var evento = evt || window.event;
        if(document.querySelector("#Cbo_proveedores").value == "")
        {
            mostrarModal(1,null,"Hace falta ingresar información","Seleccione por favor el proveedor",null,"Aceptar","",null);
            evento.preventDefault();
            return;
        }


        if(document.querySelector("#fecha1").value == "")
        {
            mostrarModal(1,null,"Hace falta ingresar información","Ingrese la fecha de inicio",null,"Aceptar","",null);
            evento.preventDefault();
            return;
        }

        if(document.querySelector("#fecha2").value == "")
        {
            mostrarModal(1,null,"Hace falta ingresar información","Ingrese la fecha de fin",null,"Aceptar","",null);
            evento.preventDefault();
            return;
        }

    }

</script>