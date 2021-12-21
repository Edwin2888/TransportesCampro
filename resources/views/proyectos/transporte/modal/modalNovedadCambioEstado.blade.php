<div class="modal fade" id="modal_novedad_estado">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header modal-filter panel-warning">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title">Novedad cambio estado</h5>

            </div>
            <div class="modal-body">
                
                <div class="row" style="    margin-top: 16px;">
                    <div class="row">
                        <table class="table table-striped table-bordered" cellspacing="0" width="99%">
                            <thead>
                                <th style="width:200px; text-align: center">
                                    Estado
                                </th>
                                <th style="width:400px;  text-align: center">
                                    Observación
                                </th >
                                <th style="width:300px;  text-align: center">
                                    Usuario
                                </th>
                                <th style="width:300px;  text-align: center">
                                    Fecha
                                </th>
                            </thead>
                            <tbody id="tblNovedadEstado">
                            </tbody>
                        </table>

                    </div>

                </div>

                <label>Seleccione el proyecto</label>
                <div class="row">
                    <div class="form-group has-feedback">
                        <div class="input-group">
                            <select class="form-control form-control-input selectWzrdCompleto" id="selEstadoNovedad"  style="    width: 90%; ">
                                <option>Seleccione</option>
                                @for($i = 0; $i < count($estados) ; $i++)
                                    @if($estados[$i]->id_estado != "E02" && $estados[$i]->id_estado != "E01" 
                                    && $estados[$i]->id_estado != "E05")
                                        <option disabled value="{{$estados[$i]->id_estado}}">{{$estados[$i]->nombre}}</option>
                                    @else
                                        <option value="{{$estados[$i]->id_estado}}">{{$estados[$i]->nombre}}</option>
                                    @endif
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
                <label>Observación</label>
                <div class="row">
                    <div class="form-group has-feedback">
                        <div class="input-group">
                            <textarea id="txtObserNovedadEstado" style="    width: 97%;    height: 125px;    resize: none;    border-radius: 2px;"></textarea>
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" style="background-color:#0060ac;color:white;" onclick="saveNovedadEstado()">Guardar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>