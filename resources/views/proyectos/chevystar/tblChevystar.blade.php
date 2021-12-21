<table id="tbl_validaciones" class="table table-striped table-bordered table_center" cellspacing="0" width="99%">
    <thead>
        <tr>
            <th style="width:200px;">#</th>            
            <th style="width:200px;">Placa</th>
            <th style="width:200px;">Kilometraje</th>
            <th style="width:200px;">Fecha</th>            
            <!-- <th style="width:200px;">Fecha Vinculación</th>-->
        </tr>
    </thead>
    <tbody>            
        @if ($tabla != '')
            @foreach ($tabla as $chevy)
            <tr>
                <td class="text-center" style="width:20px;">{{ $chevy->num }}</td>
                <td class="text-center" style="width:20px;">{{ $chevy->placa }}</td>
                <td class="text-center" style="width:20px;">{{ $chevy->kilometraje }}</td>
                <td class="text-center" style="width:20px;">{{ $chevy->fecha }}</td>
            </tr>
            @endforeach  
        @endif            
    </tbody>
</table>