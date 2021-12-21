@extends('template.index')

@section('title')
    Maestro Documentos
@stop

@section('title-section')
    Maestro Documentos
@stop

@section('css2')
    <link rel="stylesheet" type="text/css" href="../../css/transporte.css">
@stop

<main>
    @include('proyectos.transporte.modal.modalDocumentos')
    <div class="container">
        <div class="row">
            <section>
                <div style="margin-top:10px;margin-left:10px;">
                    <button data-toggle="collapse" data-target="#filter" class="btn btn-primary  btn-cam-trans btn-sm" name="Documentos" value="consultar" onclick="nuevodocumento();">
                        <i class="fa fa-plus"></i> &nbsp;&nbsp;Agregar Documentos
                    </button>
                </div>
            </section>
        </div>
        <div class="row">
            <p style="    margin-left: 11px;    margin-top: 10px;    margin-bottom: 0px;    color: red;">Para asignar tipos de documentos a las clases de los vehículos, primero seleccione el tipo vehiculo enseguida la clase en la parte izquierda, y luego seleccione los documentos de la parte derecha que quiere relacionar con la clase. Después oprima el botón guardar.</p>
        </div>
        <div class="row">
            <section>
                <div class="col-md-4">
                    @include('proyectos.transporte.secciones.frmClase')
                </div>
                <div class="col-md-8">
                    @include('proyectos.transporte.secciones.frmDocumento')
                </div>
            </section>
        </div>
    </div>
</main>
<script>
window.addEventListener('load',ini);
function ini()
{
    ocultarSincronizacionFondoBlanco();
    document.querySelector("#rdNinguno").checked = true;
}

function limpiarModal(){
    document.querySelector("#txtNombreDocumento").value = "";
}

function nuevodocumento()
{
    $("#modal_documentos").modal("toggle");
}
function editarModal(id, nombre)
{
    document.querySelector("#txtNombreDocumento").value = nombre;
    document.querySelector("#txtNombreDocumento").dataset.id = id;
    document.querySelector("#txtNombreDocumento").dataset.band = 1;
}
function guardarDocumento()
{
    var nombre = document.querySelector("#txtNombreDocumento").value;
    var id = document.querySelector("#txtNombreDocumento").dataset.id;
    if(document.querySelector("#txtNombreDocumento").dataset.band == "0") //Guarda
    {
        if (nombre != "") {
            var datos = {
                id: id,
                nombre: nombre,
                opc: 14,
                edita: 0
            }
            consultaAjax("../../rutaInsercionTransporte", datos, 15000, "POST", 1, 0);
        }
        else
            mostrarModal(1, null, "Mensaje", "Nombre obligatorio\n", 0, "Aceptar", "", null);
    }
    else//Edita
    {
        if (nombre != "") {
            var datos = {
                id: id,
                nombre: nombre,
                opc: 14,
                edita: 1
            }
            consultaAjax("../../rutaInsercionTransporte", datos, 15000, "POST", 1, 1);
        }
        else
            mostrarModal(1, null, "Mensaje", "Nombre obligatorio\n", 0, "Aceptar", "", null);
    }
}
function guardarAsociacionDocumentos()
{

    var selTipoCAM = document.querySelector("#selTipoCAM").value;
    var ulPadreClase = document.querySelector("#ulPadreClase").children[0].children;
    var idClase = "";

    for(var i=0; i<ulPadreClase.length;i++){
        var type = $(ulPadreClase[i]).attr('type');
        if(type == "radio")
        {
          if(ulPadreClase[i].checked)
            idClase = ulPadreClase[i].dataset.id;
        }
    }

    var ulPadreDocumento = document.querySelector("#ulPadreDocumento").children[0].children;
    var documentos = [];
    for(var i=0; i<ulPadreDocumento.length;i++){
        var type = $(ulPadreDocumento[i]).attr('type');
        if(type == "checkbox")
        {
            if(ulPadreDocumento[i].checked){
                documentos.push(
                    {
                        id: ulPadreDocumento[i].dataset.id
                    }
                )
            }
        }
    }
    if(selTipoCAM=='' || idClase== undefined ){
        mostrarModal(1,null,"Mensaje","Debes seleccionar un tipo de Vehiculo y una clase\n",0,"Aceptar","",null);
        return false;
    }
    //alert(JSON.stringify(documentos));
    var datos = {
        idClase: idClase,
        selTipoCAM: selTipoCAM,
        documentos: documentos,
        opc: 15
    }
    consultaAjax("../../rutaInsercionTransporte", datos, 15000, "POST", 2, 1);
}
function listarDocumentosAsociados(idClase)
{   

    var selTipoCAM = document.querySelector("#selTipoCAM").value;
    var ulPadreClase = document.querySelector("#ulPadreClase").children[0].children;
    var idClase = "";

    for(var i=0; i<ulPadreClase.length;i++){
        var type = $(ulPadreClase[i]).attr('type');
        if(type == "radio")
        {
          if(ulPadreClase[i].checked)
            idClase = ulPadreClase[i].dataset.id;
        }
    }
    if(selTipoCAM=='' || idClase== undefined ){
        return false;
    }

    var datos = {
        idClase: idClase,
        selTipoCAM: selTipoCAM,
        opc: 5
    }
    consultaAjax("../../rutaConsultaTransporte", datos, 15000, "POST", 3);
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
        success:function(data)
        {
            if(opcion == 1)//Crear documento
            {
                if(data == -1)//Documento ya existe
                {
                    ocultarSincronizacion();
                    mostrarModal(1,null,"Mensaje","Documento ya existe\n",0,"Aceptar","",null);
                    return;
                }
                ocultarSincronizacion();
                if(edita != 1)
                    mostrarModal(2,null,"Mensaje","Documento guardado exitosamente\n",0,"Aceptar","",null);
                else
                    mostrarModal(2,null,"Mensaje","Documento editado exitosamente\n",0,"Aceptar","",null);

                document.querySelector("#txtNombreDocumento").value = "";
                document.querySelector("#txtNombreDocumento").dataset.band = 0;
                document.querySelector("#txtNombreDocumento").dataset.id = 0;
                document.querySelector("#divTableModalDocumentos").innerHTML = data;
                window.location.reload();
            }
            if(opcion == 2)//Crear asociacion clase y documento
            {
                ocultarSincronizacion();
                mostrarModal(2, null, "Mensaje", "Guardado exitoso\n", 0, "Aceptar", "", null);
            }
            if(opcion == 3)//Listar documentos asociados a la clase
            {
                //alert(JSON.stringify(data));

                var ulPadreDocumento = document.querySelector("#ulPadreDocumento").children[0].children;
                var documentos = [];
                for(var i=0; i<ulPadreDocumento.length;i++){//limpiar checks
                    var type = $(ulPadreDocumento[i]).attr('type');
                    if(type == "checkbox") //inputBox
                     ulPadreDocumento[i].checked = false;
                    
                }
                for(var i=0; i<ulPadreDocumento.length;i++){
                    for(var j= 0;j<data.length ;j++){
                        var type = $(ulPadreDocumento[i]).attr('type');
                        if(type == "checkbox")
                        {
                            if(data[j].id_documento  == ulPadreDocumento[i].dataset.id)
                                ulPadreDocumento[i].checked = true;
                        }
                    }
                }

                ocultarSincronizacion();
            }
        },
        error:function(request,status,error){
            ocultarSincronizacion();
            //$('#filter_registro').modal('toggle');

            mostrarModal(1,null,"Consulta de Alianzas","Existen problemas con la conexión a internet, comuníquese con la persona encargada del manejo de la red.\n",0,"Aceptar","",null);
            setTimeout(function()
            {
                location.reload();
            },3000);
        }

    });
}
</script>