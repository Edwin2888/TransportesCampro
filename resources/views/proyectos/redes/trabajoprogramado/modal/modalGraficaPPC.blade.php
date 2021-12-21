<div class="modal fade" id="modal_graf_ppc">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">PPC</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
        <div style="height:200px;margin-bottom:50px;">
          <div class="title_reporte">Ordenes de trabajo - Pastel</div>
          <div id="container" class="reporte"></div>
        </div>
        <div style="height:200px;margin-bottom:50px;">
          <div class="title_reporte">Ordenes de trabajo - Barras</div>
          <div id="container1" class="reporte"></div>
        </div>
        <?php
            $res1 = intval($conEje);
            $res2 = intval($conAnu);
            $res3 = intval($conPro);
            $sum = $res3 + $res2 + $res1;
            if($sum == 0)
              $sum = 1;
        ?>
          <div>
            <p>PPC: <b id="ppc_label">{{($res1 / $sum * 100)}} %</b></p>
          </div>
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
</div>


