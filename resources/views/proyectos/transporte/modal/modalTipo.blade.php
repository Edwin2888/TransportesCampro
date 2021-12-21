<div class="modal fade" id="modal_tipoVehiculo">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header modal-filter panel-warning">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title">Vehiculos Crear/Editar</h5>

            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group has-feedback">

                        <div class="col-sm-4" style="display:none;">
                            <div class="input-group">
                                <input id="txtCodigoVehiculo" type="text" class="inputModalWzrd" name="TipoVehiculo" placeholder="Código">
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input id="txtNombreVehiculo" type="text" class="inputModalWzrd" name="TipoVehiculo" placeholder="Nombre">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <button class="btn btn-primary btn-cam-trans btn-sm" onclick="limpiarModal(2)"><i class="fa fa-times" aria-hidden="true"></i></button>
                            <button type="button" class="btn btn-primary btn-info-full btn-modal-clear " onclick="guardarTipoVehiculo()">Guardar</button>
                        </div>

                    </div>
                </div>
                <div class="row" style="    margin-top: 16px;">
                    <div class="form-group has-feedback">
                        <div class="col-sm-12" id="divTableModalVehiculo">
                            @include('proyectos.transporte.tables.tblTipoVehiculo')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>