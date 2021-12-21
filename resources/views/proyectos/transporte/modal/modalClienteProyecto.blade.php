<div class="modal fade" id="modal_clienteProyecto">
    <div class="modal-dialog800" role="document">
        <div class="modal-content">
            <div class="modal-header modal-filter panel-warning">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title">Cliente/Proyecto</h5>

            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group has-feedback">
                        <div class="col-sm-3" style="display:none">
                            <div class="input-group">
                                <input id="txtCodigoCliente" type="text" class="inputModalWzrd" name="cliente" placeholder="Código">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input id="txtNombreCliente"  type="text" class="inputModalWzrd form-control" name="cliente" placeholder="Nombre">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input id="txtPrefijo" type="text" class="inputModalWzrd form-control" name="cliente" placeholder="Prefijo">
                            </div>
                        </div>

                         <div class="col-sm-3">
                            <div class="input-group">
                                <input id="txtlineaproyecto" type="text" class="inputModalWzrd form-control" name="cliente" placeholder="Línea">
                            </div>
                        </div>


                         <div class="col-sm-3">
                            <div class="input-group">
                                <input id="txtCCosto" type="text" class="inputModalWzrd form-control" name="cliente" placeholder="Centro de costo">
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="input-group">
                                <input id="txtop" type="text" class="inputModalWzrd form-control" name="cliente" placeholder="Op">
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="input-group">
                                <input id="txtadicional" type="text" class="inputModalWzrd form-control" name="cliente" placeholder="Adicional">
                            </div>
                        </div>



                        <div class="col-sm-3">
                            <button class="btn btn-primary btn-cam-trans btn-sm" onclick="limpiarModal(6)"><i class="fa fa-times" aria-hidden="true"></i></button>
                            <button type="button" class="btn btn-primary btn-info-full btn-modal-clear" onclick="guardarProyecto()">Guardar</button>
                        </div>

                    </div>
                </div>
                <div class="row" style="    margin-top: 16px;">
                    <div class="form-group has-feedback">
                        <div class="col-sm-12" id="divTableModalClienteProyecto">
                            @include('proyectos.transporte.tables.tblClienteProyecto')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>