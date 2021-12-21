<style type="text/css">
    #tbl_manuales td
    {
        text-align: center;
    }
</style>
<table class="table table-striped table-bordered" id="tbl_manuales" cellspacing="0" width="99%">
    <thead>
        <th style="width:50px;">
            Proyecto
        </th>
        <th style="width:200px;">
            Título
        </th >
        <th style="width:500px;">
            Descripción
        </th>
        <th style="width:50px;">
            Versión
        </th>
        <th style="width:50px;">
            Código
        </th>
        <th style="width:50px;">
            Tipo
        </th>
        <th style="width:50px;">
            Estado
        </th>
        <th style="width:50px;">
            F. Creación
        </th>
        <th style="width:50px;">
            F. Actualización
        </th>
        <th>Ver</th>
    </thead>
    <tbody id="tbTblCiudades">
    @foreach($manuales as $key => $val)
        <tr>
            <td>
            @foreach($proyecto as $key => $valor)
                @if($val->id_proyecto == $valor->prefijo_db)
                    {{$valor->proyecto}}
                @endif
                @if($val->id_proyecto == "T01")
                    RECURSOS HUMANOS
                    <?php  break;?>
                @endif

                @if($val->id_proyecto == "T02")
                    SUPERVISIÓN Y SST
                    <?php  break;?>
                @endif

                @if($val->id_proyecto == "T03")
                    TRANSPORTES
                    <?php  break;?>
                @endif

                @if($val->id_proyecto == "T04")
                    HERRAMIENTAS
                    <?php  break;?>
                @endif
            @endforeach             
            </td>
            <td>{{$val->titulo}}</td>
            <td>{{$val->descripcion}}</td>

            <td>{{$val->version}}</td>
            <td>{{$val->codigo}}</td>
            <td>
            @if($val->tipo == 1)
                App Web
            @else
                App Móvil
            @endif
            </td>
            <td>
                @if($val->estado == 1)
                    ACTIVO
                @else
                    INACTIVO
                @endif
            </td>
            <td>{{explode(".",$val->fecha_servidor_creacion)[0]}}</td>
            <td>{{explode(".",$val->fecha_servidor_update)[0]}}</td>
            <td>
                <button onclick="abrirModal('{{$val->id_manual}}','{{$val->id_proyecto}}','{{$val->titulo}}','{{$val->descripcion}}','{{$val->embebido}}','{{$val->estado}}','{{$val->version}}','{{$val->tipo}}','{{$val->codigo}}')" class="btn btn-primary btn-cam-trans btn-sm" ><i class="fa fa-search" aria-hidden="true"></i></button>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
