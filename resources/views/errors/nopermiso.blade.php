@extends('template.index')

@section('title')
	No tiene los permisos para esta sección
@stop

@section('title-section')
    No tiene los permisos para esta sección
@stop

@section('content')
    
	<main>
        <div style="    background: #f53131;    padding: 20px;    font-size: 15px;    color: white;">
            <p>No tiene los permisos necesarios, comuníquese con el administrador del sistema por favor. Haga <a href="{{config('app.Campro')[2]}}/campro/home">click aquí</a> para continuar</p>
        </div>
	</main>


<script type="text/javascript">
    window.addEventListener('load',ini);    

    function ini()
    {
        ocultarSincronizacionFondoBlanco();
    }
</script>
@stop

