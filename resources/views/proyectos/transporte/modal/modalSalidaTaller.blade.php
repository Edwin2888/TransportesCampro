<div class="modal fade" id="modal_salida_taller">
    <div class="modal-dialog modal-lg" role="document" style="width:96%">
        <div class="modal-content" style="    width: 100%;">
            <div class="modal-header modal-filter panel-warning">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title">Novedades incidencia - Salida de taller</h5>

            </div>
            <div class="modal-body" id="tbl_novedades_salir_taller">
            </div>

            @if($acceso == "W")
            <div class="col-md-12">
                <div class="row">
                        <div class="col-md-4">
                            <div class="row" style="margin-top:5px;">
                                <div class="form-group has-feedback">
                                    <label for="select_tipo_incidencia">Incidencia:</label>
                                    <input name="txt_incidencia_salida_taller" type="text" readonly class="form-control" id="txt_incidencia_salida_taller"  />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4" style="display:none">
                            <div class="row" style="margin-top:5px;">
                                <div class="form-group has-feedback">
                                    <label for="costo_salida">Costo salida de taller:</label>
                                    <input name="costo_salida" type="text" class="form-control" id="costo_salida"  value="0" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row" style="margin-top:5px;">
                                <div class="form-group has-feedback">
                                    <label for="km_proximo">Km próximo:</label>
                                    <input name="km_proximo" type="text" class="form-control" id="km_proximo"  />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row" style="margin-top:5px;">
                                <div class="form-group has-feedback">
                                    <label for="km_salida_taller">Km:</label>
                                    <input name="km_salida_taller" type="text" class="form-control" id="km_salida_taller" />
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <textarea id="txt_obser_salida_Taller" placeholder="Observación" style="height:100px;    margin-bottom: 15px;" class="form-control"></textarea>
                        </div>
                    </div>

                    <div  class="btn btn-primary btn-cam-trans btn-sm btn-maps" onclick="saveSalidaTaller()"><i class="fa fa-save"></i> &nbsp; Guardar</div>

                </div>
            @endif
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>