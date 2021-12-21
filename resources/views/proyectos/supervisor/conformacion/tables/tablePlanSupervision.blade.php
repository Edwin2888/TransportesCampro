
<div class="row" style="margin-top:15px;width:96%;margin-left:2%">
    <table class="table table-striped table-bordered" cellspacing="0" width="99%" id="tbl_plan_supervision">
        <thead>
            <th style="width:20px; text-align: center">
                ID
            </th>
            <th style="width:20px; text-align: center">
                Nombre del equipo
            </th>
            <th style="width:20px; text-align: center">
                Año
            </th>
            <th style="width:20px; text-align: center">
                Mes
            </th>
            <th style="width:20px; text-align: center">
                Líder
            </th>
            <th style="width:20px;  text-align: center">
                Cantidad de integrantes
            </th >
            <th style="width:20px;  text-align: center">
                Cantidad de Observación del comportamiento programados
            </th>
            <th style="width:20px;  text-align: center">
                Cantidad de Observación del comportamiento realizados
            </th>

            <th style="width:20px;  text-align: center">
                Cantidad de IPALES programados
            </th>
            <th style="width:20px;  text-align: center">
                Cantidad de IPALES realizados
            </th>

            <th style="width:20px;  text-align: center">
                Cantidad de Calidad programados
            </th>
            <th style="width:20px;  text-align: center">
                Cantidad de Calidad realizados
            </th>

            <th style="width:20px;  text-align: center">
                Cantidad de Ambiental programados
            </th>
            <th style="width:20px;  text-align: center">
                Cantidad de Ambiental realizados
            </th>

            
        </thead>
        <tbody id="tbTblCiudades">
        @foreach($datos as $key => $val)
            <tr>
                <td style="text-align:center;">
                    <a href="{{url('/')}}/transversal/supervision/conformacion/{{$val->id}}" style="text-decoration:underline">{{$val->id}}</a>
                </td>
                <td style="text-align:center;">
                    {{$val->nombre_equipo}}
                </td>
                <td style="text-align:center;">
                    {{$val->anio}}
                </td>
                <td style="text-align:center;">
                    {{$val->mes}}
                </td>
                <td style="text-align:center;">
                    {{strtoupper($val->nombreL)}} - {{strtoupper($val->lider)}}
                </td>
                <td style="text-align:center;">
                    {{$val->cantColaboradores}}
                </td>
                <td style="text-align:center;">
                    {{$val->cantCompor + $val->cantComporL}}
                </td>
                <td style="text-align:center;">
                    {{$val->cantComporCumplido}}
                </td>
                <td style="text-align:center;">
                    {{$val->cantIpal + $val->cantIpalL}}
                </td>
                <td style="text-align:center;">
                    {{$val->cantIpalCumplido}}
                </td>
                <td style="text-align:center;">
                    {{$val->cantSegu + $val->cantSeguL}}
                </td>
                <td style="text-align:center;">
                    {{$val->cantSeguCumplido}}
                </td>

                <td style="text-align:center;">
                    {{$val->cantAmbi + $val->cantAmbiL}}
                </td>
                <td style="text-align:center;">
                    {{$val->cantAmbiCumplido}}
                </td>
                
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
