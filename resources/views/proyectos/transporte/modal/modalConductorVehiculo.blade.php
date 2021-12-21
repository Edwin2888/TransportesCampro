<div class="modal fade" id="modal_conductorVehiculo">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header modal-filter panel-warning">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title">Conductor Vehículo</h5>

            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group has-feedback">

                        <div class="col-sm-4">
                            <div class="input-group">
                                <input id="txtCedulaCon" type="text" class="inputModalWzrd" name="proveedor" placeholder="Cédula">
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input id="txtNombreConduc" type="text" class="inputModalWzrd" name="Proveedor" placeholder="Nombre">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-primary btn-info-full btn-modal-clear" onclick="consultaInformacionConductor()">Consultar</button>
                        </div>

                    </div>
                </div>
                <div class="row" style="    margin-top: 16px;">
                    <div class="form-group has-feedback">
                        <div class="col-sm-12" id="divConductoresVehiculo">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>