<div class="modal fade" id="modal_ipal">
  <div class="modal-dialog modal-lg" role="document" style="width:75%;">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Entrega a la operación</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <table id="tbl_causa" class="table table-striped table-bordered" cellspacing="0" width="99%">
        <thead>
            <tr>
                <th style="width:10px;">Item</th>
                <th style="width:100px;">Descripción</th>
                <th style="width:10px;">Respuesta</th>
                <th style="width:10px;">Fecha</th>
            </tr>
        </thead>
        <tbody id="tbl_ipal">
            @foreach($form as $key => $valor)
              <tr>
                  <td style="text-align:center;">{{$valor->item_num}}</td>
                  <td style="text-align:center;">{{$valor->descrip_pregunta}}</td>
                  <td style="text-align:center;">
                  @if($valor->respuesta == 2)
                    SI
                  @endif
                  @if($valor->respuesta == 1)
                    NO
                  @endif
                  @if($valor->respuesta == 0)
                    NA
                  @endif
                  </td>
              </tr>
            @endforeach
        </tbody>
    </table>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
</div>