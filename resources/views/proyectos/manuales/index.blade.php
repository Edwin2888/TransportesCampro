@extends('template.index')

@section('title')
    Manuales
@stop

@section('title-section')
    Manuales
@stop

@section('css')
    <link rel="stylesheet" type="text/css" href="../../css/demo.css" />
    <link rel="stylesheet" type="text/css" href="../../css/component.css" />
@stop

<main>
    <div class="container">
        @include('proyectos.manuales.menu.nav')

    <div class="section-derecha-manual">
        <p style="    color: black;
    margin: 0px;
    padding-top: 10px;
    font-size: 17px;">Titulo:<b id="lbltitulo">¿Como utilizar el módulo de manuales?</b></p>
        <p style="    margin: 0px;
    color: black;
    padding-bottom: 9px;
    font-size: 17px;">Descripción:<b id="lbldescipcion">En la siguiente presentación se indica como se pueden visualizar los manuales por proyecto.</b></p>
        <span style="display:inline-block;height:100%;width:100%;" id="embed_id">
            <iframe style="width:84%; height:86%;" src="//e.issuu.com/embed.html#29506046/50403512" frameborder="0" allowfullscreen></iframe>
        </span>
    </div>
</main>

@section('js')
    <script src="js/classie.js"></script>
    <script src="js/mlpushmenu.js"></script>
    <span >
        <script id="java_id" type="text/javascript" src="//e.issuu.com/embed.js"></script>    
    </span>
    
@stop
<script type="text/javascript">
    window.addEventListener('load',ini);
    /* INICIO: FUNCIONES PARA PESTAÑAS */  
    function ini() {

        new mlPushMenu( document.getElementById( 'mp-menu' ), document.getElementById( 'trigger' ) );
        document.querySelector("#trigger").click();
        ocultarSincronizacionFondoBlanco();
        /* FIN: FUNCIONES PARA PESTAÑAS */
    }

    function abrirManual(ele)
    {
        var titulo = "";
        var des = "";
        var embebido = "";
        var version =""
        if(ele == 1)
        {   
            titulo = "¿Como utilizar el módulo de manuales?";
            des = "En la siguiente presentación se indica como se pueden visualizar los manuales por proyecto.";
            embebido = '<iframe style="width:84%; height:86%" src="https://e.issuu.com/embed.html#29506046/50403512" frameborder="0" allowfullscreen></iframe>';
        }
        else
        {
            titulo = ele.dataset.titulo;
            version = ele.dataset.version;
            des = ele.dataset.descripcion + " V-" + version;
            embebido = '<iframe style="width:84%; height:86%" src="https://e.issuu.com/embed.html#' + ele.dataset.embebido + '" frameborder="0" allowfullscreen></iframe>';
        }

        document.querySelector("#lbltitulo").innerHTML = titulo;
        document.querySelector("#lbldescipcion").innerHTML = des;
        document.querySelector("#embed_id").innerHTML = embebido;

    }

</script>