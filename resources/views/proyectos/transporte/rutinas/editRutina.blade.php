@extends('template.index')

@section('title')
Detalle rutina
@stop
@section('title-section')
Detalle rutina
@stop
<br>
<br>

</form>
<div class="row">
    <div class="col-xs-4 col-sm-4 col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">DETALLE DE RUTINA</div>
            <div class="panel-body">
                <?php foreach ($rutinas as $rutina) { ?>
                    <label>Nombre de la rutina:</label>
                    <label><?= $rutina->nombre ?></label><br>
                    <label>Ciclo:</label>
                    <label><?= $rutina->ciclo ?></label>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<div class="container" style="margin-top:8px;">
    <div class="row" style="margin-left:2%;width:96%">
        <div class="panel panel-default">
            <div class="panel-body">
                <form method="POST" action="/transversal/transporte/guardaRutina" role="form">
                    <input type="hidden" name="_token" value="<?= csrf_token() ?>">
                    <input type="hidden" name="id_rutina" value="<?php echo $_GET['id_rutina']; ?>">
                    <table id="tabla1" class="table table-striped table-bordered" cellspacing="0">
                        <thead>
                            <td>Nombre</td>
                            <td>Descripción</td>
                            <td></td>
                        </thead>
                        <td><input class="form-control " size="25%" name="nombre" id="nombre" data-val="Nombre"></input></td>
                        <td><input class="form-control " size="148%" name="descripcion" id="Descripcion"></input></td>
                        <td>
                            <center><button type="submit" class="btn btn-success text-align: center"><i class="fa fa-floppy-o"></i></button></center>
                        </td>
                        <?php foreach ($detalles as $detalle) { ?>
                            <tr>
                                <td><?= $detalle->nombre ?>
                                </td>
                                <td><?= $detalle->descripcion ?>
                                </td>
                                <td></td>
                            </tr>
                        <?php } ?>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript">
    //Funci�n INI Javascript
    window.addEventListener('load', iniList);

    function iniList() {
        var alto = screen.height - 400;
        var altopx = alto + "px";

        $('#tabla1').dataTable({
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
</script>