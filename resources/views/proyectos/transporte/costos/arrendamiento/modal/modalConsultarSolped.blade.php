<div class="modal fade" id="modal_consultarSolped">
    <div style='width:80%' class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header modal-filter panel-warning">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title">Confirmar Documento</h5>

            </div>
            <div class="modal-body">
                <form id="generarSolped">
                    <div style="    border: 1px solid #0060AC;    padding: 5px;    border-radius: 3px;">
                        <h3>Informacion Basica</h3>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="">Numero de Arrendamiento</label>
                                <input  type="text" value="" name="Sarrendamiento" id="Sarrendamiento" readonly required class="form-control text">
                            </div>
                            <div class="col-md-4">
                                <label for="">Usuario</label>
                                <input  type="text"  class="form-control text" name="Susuario" id="Susuario" readonly required>
                            </div>
                            <div class="col-md-4">
                                <label for="">Total a pagar</label>
                                <input  type="text" class="form-control text" name="Stotalapgar" id="Stotalapgar" readonly required>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div style="    border: 1px solid #0060AC;    padding: 5px;    border-radius: 3px;">
                        <h3>Informacion de la solped</h3>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="">Contrato</label>
                                <select onchange="Selcontrato()" style="width:100%" name="Scontrato" class="form-control" id="Scontrato">
                                    <option value=""><--Seleccione un contrato--></option>
                                    @foreach($contratosSap as $contrato)
                                        <option value="{{$contrato}}">{{$contrato}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="">Numero de Servicio</label>
                                <!-- <input type="text" id="Sservicio" class="form-control text"> -->
                                <select onchange="Selservicio()" style="width:100%" class="form-control" name="Sservicio" id="Sservicio"  required>
                                    <option value=""><--Seleccione Servicio-></option>
                                    @foreach($aServicios as $servicio)
                                        <option value="{{$servicio->numero_servicio}}">{{$servicio->numero_servicio}}-{{$servicio->tipo_servicio}}-{{$servicio->descripcion}}</option>
                                    @endforeach
                                </select>
                                <!-- <label for="" id="Sservicio1" class="valorDiferencial"></label> -->
                            </div>
                        </div>
                        <br><br>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="">Cuenta de proveedor</label>
                                <input type="text"  name="Scuenta" id="Scuenta" readonly required class="form-control text">
                                <label for="" id="Scuenta1" class="valorDiferencial"></label>

                            </div>
                            <div class="col-md-4">
                                <label for="">Centro Logistico</label>
                                <input type="text" name="Scentro" id="Scentro" readonly required class="form-control text">
                                <label for="" id="Scentro1" class="valorDiferencial"></label>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="">Grupo de articulos</label>
                                <input type="text" name="SgrupoArticulos" id="SgrupoArticulos" readonly required class="form-control text">
                                <label for="" id="SgrupoArticulos1" class="valorDiferencial"></label>

                            </div>
                            <div class="col-md-4">
                                <label for="">Cantidad</label>
                                <input type="text" name="Scantidad" value="1.00" minlength="1" id="Scantidad" readonly required class="form-control text">
                                <label for="" id="Scantidad1" class="valorDiferencial">1.00</label>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="">Unidad</label>
                                <input type="text" value="SER" name="Sunidad" id="Sunidad" readonly required class="form-control text">
                                <label for="" id="Sunidad1" class="valorDiferencial">SER</label>
                            </div>
                            <div class="col-md-4">
                                <label for="">Organzacion de Compras</label>
                                <input type="text" name="Sorganizacion" id="Sorganizacion" readonly required class="form-control text">
                                <label for="" id="Sorganizacion1" class="valorDiferencial"></label>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="">Grupo de compras</label>
                                <input type="text" name="SgrupoCompras" id="SgrupoCompras" readonly required class="form-control text">
                                <label for="" id="SgrupoCompras1" class="valorDiferencial"></label>

                            </div>
                            <div class="col-md-4">
                                <label for="">Moneda</label>
                                <input type="text" value="COP" name="Smoneda" id="Smoneda" readonly required class="form-control text">
                                <label for="" id="Smoneda1" class="valorDiferencial">COP</label>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="">Posicion</label>
                                <input type="text" value='1' name="Sposicion" id="Sposicion" readonly required class="form-control text">
                                <label for="" id="Sposicion1"  class="valorDiferencial">1</label>
                            </div>
                            <div class="col-md-4">
                                <label for="">Elemento Pep</label>
                                <input type="text" name="Sped" id="Sped" readonly required class="form-control text">
                                <label for="" id="Sped1" class="valorDiferencial"></label>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="">Sociedad</label>
                                <input type="text" name="Ssociedad" id="Ssociedad" readonly required class="form-control text">
                                <label for="" id="Ssociedad1" class="valorDiferencial"></label>

                            </div>
                            <div class="col-md-4">
                                <label for="">Numero Activo Fijo</label>
                                <input type="text" name="SnumActivo" id="SnumActivo" readonly required class="form-control text">
                                <label for="" id="SnumActivo1" class="valorDiferencial"></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="">Subnumero Activo Fijo</label>
                                <input type="text" name="SsubNumActivo" id="SsubNumActivo" readonly required class="form-control text">
                                <label for="" id="SsubNumActivo1" class="valorDiferencial"></label>

                            </div>
                            <div class="col-md-4">
                                <label for="">Descripcion</label>
                                <input type="text" name="Sdescripcion" id="Sdescripcion" readonly required class="form-control text">
                                <label for="" id="Sdescripcion1" class="valorDiferencial"></label>

                            </div>
                        </div>
                    </div>
                    <div class="row" style="    margin-top: 16px;">
                        <div class="form-group has-feedback">
                            <!-- <div class="col-sm-12" id="divConductoresVehiculo">
                            </div> -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <!-- <input type="submit" class="btn btn-primary" value="Confirmar"> -->
                        <button type="button" onclick="generarSolped()" class="btn btn-primary">Confirmar</button>
                    </div>
                </form>    
            </div>
        </div>
    </div>
</div>
