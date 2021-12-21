<table id="tbl_cobertura" class="table table-striped table-bordered" cellspacing="0" width="99%" >
    <thead>
        <tr>
            <th style="width:50px;">N°</th>
            <th style="width:50px;">Cédula</th>
            <th style="width:100px;">Líder</th>
            <th style="width:50px;">Cargo</th>
            <th style="width:50px;">Proyecto</th>
            <th style="width:50px;">Centro de costo</th> 
            <th style="width:50px;">Cant. Inspeccion conformes</th>
            <th style="width:50px;">Cant. Inspeccion No conformes</th>          
        </tr>
    </thead>
    <tbody>
        @foreach ($datos as $key => $val)
            <tr>
                <td style="text-align:center;">{{$key + 1}}</td>
                <td style="text-align:center;">{{$val->cedula}}</td>
                <td style="text-align:center;">{{$val->nombre}}</td>
                <td style="text-align:center;">{{$val->cargo}}</td>
                <td style="text-align:center;">{{$val->proyecto}}</td>
                <td style="text-align:center;">{{$val->ccosto}}</td>
                <td style="text-align:center;">{{$val->inspeConforme}}</td>
                <td style="text-align:center;">{{$val->inspeNoConforme}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
