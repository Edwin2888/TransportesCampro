<table id="tbl_gestores" class="table table-striped table-bordered" cellspacing="0" width="99%">
    <thead>
        <tr>
            <th style="width:200px;">Identificación</th>
            <th style="width:200px;">Nombre Gestor</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($gestores as $gestor => $ges)
            <tr>
                <td style="text-align:center;color:blue;">{{$ges->id_cruce_gestor}}</td>
                <td style="text-align:center;color:blue;">{{$ges->nombre_gestor}}</td>
            </tr>

        @endforeach
    </tbody>
</table>