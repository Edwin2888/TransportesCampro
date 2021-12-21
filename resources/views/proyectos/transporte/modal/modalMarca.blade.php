<div class="modal fade" id="modal_marca">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header modal-filter panel-warning">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title">Marca Crear/Editar</h5>

            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group has-feedback">

                        <div class="col-sm-4" style="display:none;">
                            <div class="input-group">
                                <input id="txtCodigoMarca" type="text" class="inputModalWzrd" name="Marca" placeholder="Código">
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input id="txtNombreMarca" type="text" class="inputModalWzrd" name="Marca" placeholder="Nombre">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <button class="btn btn-primary btn-cam-trans btn-sm" onclick="limpiarModal(3)"><i class="fa fa-times" aria-hidden="true"></i></button>
                            <button type="button" class="btn btn-primary btn-info-full btn-modal-clear" onclick="guardarMarca()">Guardar</button>
                        </div>

                    </div>
                </div>
                <div class="row" style="    margin-top: 16px;">
                    <div class="form-group has-feedback">
                        <div class="col-sm-12" id="divTableModalMarca">
                            @include('proyectos.transporte.tables.tblMarca')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>