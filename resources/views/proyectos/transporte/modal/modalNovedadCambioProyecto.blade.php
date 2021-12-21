<div class="modal fade" id="modal_novedad_proyecto">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header modal-filter panel-warning">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title">Novedad cambio proyecto</h5>

            </div>
            <div class="modal-body">
                
                <div class="row" style="    margin-top: 16px;">
                    <div class="row">
                        <table class="table table-striped table-bordered" cellspacing="0" width="99%">
                            <thead>
                                <th style="width:100px; text-align: center">
                                    Proyecto
                                </th>
                                <th style="width:400px;  text-align: center">
                                    Observación
                                </th >
                                <th style="width:100px;  text-align: center">
                                    Usuario
                                </th>
                                <th style="width:100px;  text-align: center">
                                    Nombre de quien autoriza
                                </th>
                                <th style="width:100px;  text-align: center">
                                    Fecha
                                </th>
                            </thead>
                            <tbody id="tblNovedadProyecto">
                            </tbody>
                        </table>

                    </div>

                </div>

                <label>Seleccione el proyecto</label>
                <div class="row">
                    <div class="form-group has-feedback">
                        <div class="input-group">

                            <select id="selProyectoNovedad" class="form-control selectWzrd">
                                <option>Seleccione</option>
                                @foreach($clienteProyecto as $key => $val)
                                    <option value="{{$val->id}}">{{$val->ceco}} - {{$val->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <label>Observación</label>
                <div class="row">
                    <div class="form-group has-feedback">
                        <div class="input-group">
                            <textarea id="txtObserNovedadProyecto" style="    width: 97%;    height: 125px;    resize: none;    border-radius: 2px;"></textarea>
                        </div>
                    </div>
                </div>

                <label>Nombre de quien autoriza el cambio de proyecto</label>
                <div class="row">
                    <div class="form-group has-feedback">
                        <div class="input-group">
                            <input id="txtNombreAutoria" style="    width: 97%;" placeholder="Nombre de quien autoriza" />
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" style="background-color:#0060ac;color:white;" onclick="saveNovedadProyecto()">Guardar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>