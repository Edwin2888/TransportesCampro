<table class="table table-striped table-bordered" cellspacing="0" width="99%">
    <thead>
        <tr>
            @if($tipo == "1")
                <th style="width:10px;"></th>
                <th style="width:20px;    padding-right: 0px;    padding-left: 0px;">Nodo</th>
                <th style="width:80px;padding-right: 0px;    padding-left: 0px;">DC</th>
                <th style="width:80px; !important;padding-right: 0px;    padding-left: 0px;">CS</th>
                <th style="width:100px; !important;padding-right: 0px;    padding-left: 0px;">Usuario</th>
                <th style="width:80px; !important;padding-right: 0px;    padding-left: 0px;">Fecha</th>
                <th style="width:80px; !important;padding-right: 0px;    padding-left: 0px;">Conciliado</th>
            @else
                <th style="width:10px;"></th>
                <th style="width:20px;    padding-right: 0px;    padding-left: 0px;">Nodo</th>
                <th style="width:100px; !important;padding-right: 0px;    padding-left: 0px;">Usuario</th>
                <th style="width:80px; !important;padding-right: 0px;    padding-left: 0px;">Fecha</th>
                <th style="width:80px; !important;padding-right: 0px;    padding-left: 0px;">Conciliado</th>
            @endif
        </tr>
    </thead>
    <tbody id="persona_a_cargo1">
        @foreach ($nodos as $nod => $val)
            <tr>
                @if($tipo == "1")
                @if($val->propietario != NULL)
                    <td style="text-align:center;" id="val_check_2" data-val="1">
                @else
                    <td style="text-align:center;" id="val_check_2" data-val="0">
                @endif
                
                    @if($val->conciliado != NULL && $val->conciliado != 0)
                        <i class="fa fa-check-square-o" aria-hidden="true" style="color:green" ></i>
                    @else
                        <i class="fa fa-arrows-alt" aria-hidden="true" style="color:red" ></i>
                    @endif
                </td>
                <td style="text-align:center;" data-nodo="{{$val->id_nodo}}">{{$val->nombre_nodo}}</td>
                <td style="text-align:center;">
                @if($val->id_documento == NULL)
                    NO APLICA
                @else
                    {{$val->id_documento}}
                @endif
                </td>
                <td style="text-align:center;">
                @if($val->id_documento == NULL)
                    NO APLICA
                @else
                    {{$val->id_documento_cs}}
                @endif
                </td>
                <td style="text-align:center;">{{$val->propietario}}</td>
                <td style="text-align:center;">{{str_replace(".000", "", $val->fecha)}}</td>
                
                <td>
                    @if($val->conciliado != NULL && $val->conciliado != 0)
                        SI
                    @else
                        NO
                    @endif

                </td>
                @else
                    @if($val->propietario != NULL)
                        <td style="text-align:center;" id="val_check_2" data-val="1">
                    @else
                        <td style="text-align:center;" id="val_check_2" data-val="0">
                    @endif
                    @if($val->conciliado != NULL && $val->conciliado != 0)
                        <i class="fa fa-check-square-o" aria-hidden="true" style="color:green" ></i>
                    @else
                        <i class="fa fa-arrows-alt" aria-hidden="true" style="color:red" ></i>
                    @endif
                    </td>
                    <td style="text-align:center;" data-nodo="{{$val->id_nodo}}">{{$val->nombre_nodo}}</td>
                    <td style="text-align:center;">{{$val->propietario}}</td>
                    <td style="text-align:center;">{{str_replace(".000", "", $val->fecha)}}</td>
                    <td>
                        @if($val->conciliado != NULL && $val->conciliado != 0)
                            SI
                        @else
                            NO
                        @endif
                    </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table> 