

<div class="row" style="margin-top:15px;width:96%;margin-left:2%">
    <table class="table table-striped table-bordered" cellspacing="0" width="99%" id="tbl_combustible">
        <thead>
            <th style="width:20px; text-align: center">
                N°
            </th>
            <th style="width:200px;  text-align: center">
                Proyecto
            </th >
            <th style="width:200px;  text-align: center">
                Placa
            </th>
            <th style="width:200px;  text-align: center">
                Fecha
            </th>
            <th style="width:200px;  text-align: center">
                Hora
            </th>
            <th style="width:200px;  text-align: center">
                Volumen
            </th>
            <th style="width:200px;  text-align: center">
                Valor facturado
            </th>
        </thead>
        <tbody id="tbTblCiudades">
            <?php $aux = 1; ?>
        @foreach($data as $key => $val)
            <tr>
                <td style="text-align:center;">
                    {{$aux}}
                </td>
                <td style="text-align:center;">
                    {{$val->nombre}}
                </td>
                <td style="text-align:center;">
                    {{$val->placa}}
                </td>

                <td style="text-align:center;">
                    {{$val->fecha}}
                </td>

                <td style="text-align:center;">
                    {{explode(':',$val->hora)[0]}}:{{explode(':',$val->hora)[1]}}:00
                </td>

                 <td style="text-align:center;">
                    {{$val->volumen}}
                </td>
                

                 <td style="text-align:center;">
                    {{$val->valor_facturado}}
                </td>
                
                
            </tr>

            <?php $aux++;?>
        @endforeach
        </tbody>
    </table>

</div>
