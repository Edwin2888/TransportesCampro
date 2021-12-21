@extends('template.index')

@section('title')
Crear rutina
@stop
@section('title-section')
Crear rutina
@stop
<br>
<br>
<br>
<div class="container-fluid">
    <form method="POST" action="/transversal/transporte/createRutina/store" name="miformulario" role="form">
        <div class="panel panel-default">
            <div class="panel-heading">Creación rutina de mantenimiento</div>
            <div class="panel-body posisition-fixed-headaer">
                <div class="row">
                    <div class="col-xs-5 col-sm-5 col-md-5">
                        <input type="hidden" name="_token" value="<?= csrf_token() ?>">
                        <div class="form-group">
                            <label>Actividad</label>
                            <input type="text" name="nombre" id="nombre" class="form-control input-sm" placeholder="Actividad">
                        </div>
                    </div>
                    <div class="col-xs-5 col-sm-5 col-md-5">
                        <div class="form-group">
                            <label>Ciclo</label>
                            <input type="int" name="ciclo" id="ciclo" class="form-control input-sm" placeholder="Ciclo">
                        </div>
                    </div>
                    <div style="padding-top: 20px;" class="col-xs-2 col-sm-2 col-md-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-floppy-o fa-1x" aria-hidden="true"></i> Guardar</button>
                    </div>
                </div>
            </div>
            <br>

    </form>
</div>
<script type="text/javascript">
    //Funci�n INI Javascript
    window.addEventListener('load', iniList);

    function iniList() {
        var alto = screen.height - 400;
        var altopx = alto + "px";

        $('#tabla').dataTable({
            "scrollX": "100%",
            "scrolY": altopx,
            "paging": true,
            "searching": true,
            "responsive": false,
            "colReorder": true,
            dom: 'T <"clear">lfrtip',
            tableTools: {
                "sSwfPath": "../../media/copy_csv_xls_pdf.swf",
                "aButtons": ["copy", "xls"]
            }
        });

        //Oculta el banner transparente
        ocultarSincronizacionFondoBlanco();
    }
    window.addEventListener("load", function() {
        miformulario.ciclo.addEventListener("keypress", soloNumeros, false);
    });

    //Solo permite introducir numeros.
    function soloNumeros(e) {
        var key = window.event ? e.which : e.keyCode;
        if (key < 48 || key > 57) {
            e.preventDefault();
        }
    }
</script>