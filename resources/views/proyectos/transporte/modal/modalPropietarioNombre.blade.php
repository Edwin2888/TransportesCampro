<div class="modal fade" id="modal_propietario">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header modal-filter panel-warning">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title">Propietarios Crear/Editar</h5>

            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group has-feedback">

                        <div class="col-sm-4" style="display:none;">
                            <div class="input-group">
                                <input id="txtCodigoPropietario" type="text" class="inputModalWzrd" name="matricula" placeholder="Código">
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input id="txtNombrePropietario" type="text" class="inputModalWzrd" name="matricula" placeholder="Nombre">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <button class="btn btn-primary btn-cam-trans btn-sm" onclick="limpiarModal(4)"><i class="fa fa-times" aria-hidden="true"></i></button>
                            <button type="button" class="btn btn-primary btn-info-full btn-modal-clear" onclick="guardarPropietario()">Guardar</button>
                        </div>

                    </div>
                </div>
                <div class="row" style="    margin-top: 16px;">
                    <div class="form-group has-feedback">
                        <div class="col-sm-12" id="divTableModalPropietarios">
                            @include('proyectos.transporte.tables.tblPropietarios')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>