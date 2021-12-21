<style type="text/css">
    .dv-clear{
        width: 100%;
        clear: both;
        height:0px;
    }
    .padl-0{
        padding-left:0px;
    }
    .padr-0{
        padding-right:0px;
    }
    .padt-0{
        padding-top:0px;
    }
    .padb-0{
        padding-bottom:0px;
    }
    
    
    .padl-4{
        padding-left:4px;
    }
    .padr-4{
        padding-right:4px;
    }
    .centro{
        text-align: center;
    }
    .negrita{
        font-weight: bold;
    }
    
    .content_da{
        width:100%;
    }
    
    .subconten_dat{
        width:100%;
    }
    .divtitu{
        border-top: 1px #000000 solid;
        border-bottom: 1px #000000 solid;
    }
    .divizq{
        border-left: 1px #000000 solid;
    }
    .divcen{
        border-left: 1px #000000 solid;
    }
    .divder{       
        border-left: 1px #000000 solid; 
        border-right: 1px #000000 solid;
    }
    .content_dat{
        border-bottom: 1px #000000 solid;
    }
    .btnsubmis{
        background: transparent;
        border: 0px solid transparent;
    }
    .loading{
        height: 19px;
        width: 19px;
        display: none;
    }
    .colorcampro,.colorcampro > div{
        background-color: #99c7ea;
    }
    .btnborraf{
        font-size: 21px;
        /* font-weight: bold; */
        color: #ffffff;
        background-color: blue;
        padding: 6px;
        border-radius: 6px;
    }
</style>

<div class="modal fade" id="modal_solicitud_maniobra">
  <div class="modal-dialog modal-lg" role="document" style="width:95%;">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Solicitud de Maniobra</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body tipo_modal">
     
        <div class="row" style="margin-top:10px;">
            <div class="col-md-6 padl-4 padr-4" id="" >
                <div class="col-md-12"><center>PROCEDIMIENTO DE MANIOBRA AT-MT</center> </div>                
                <div class="dv-clear"></div>                
                <div class="divtitu">
                    <div class="divizq  col-md-1 padl-0 padr-0 centro negrita"> ITEM</div>
                    <div class="divcen col-md-2 padl-0 padr-0 centro negrita"> Acción de Maniobra</div>
                    <div class="divcen col-md-2 padl-0 padr-0 centro negrita"> Hora</div>
                    <div class="divcen col-md-3 padl-0 padr-0 centro negrita"> Elemento</div>
                    <div class="divcen col-md-3 padl-0 padr-0 centro negrita"> Observaciones</div>
                    <div class="divder col-md-1 padl-0 padr-0 centro negrita"> <span class="fa fa-cogs"></span></div>
                    <div class="dv-clear"></div>
                </div>
                <div id="contenedorizquierda"></div>
                
            </div>
            <div class="col-md-6 padl-4 padr-4" id="datos_log_user">
                
                <div class="col-md-12"><center>  PROCEDIMIENTO DE MANIOBRA TeT</center>  </div>                              
                <div class="dv-clear"></div>         
                <div class="divtitu">
                    <div class="divizq  col-md-1 padl-0 padr-0 centro negrita"> ITEM</div>
                    <div class="divcen col-md-2 padl-0 padr-0 centro negrita"> Acción de Maniobra</div>
                    <div class="divcen col-md-2 padl-0 padr-0 centro negrita"> Hora</div>
                    <div class="divcen col-md-3 padl-0 padr-0 centro negrita"> Elemento</div>
                    <div class="divcen col-md-3 padl-0 padr-0 centro negrita"> Observaciones</div>
                    <div class="divder col-md-1 padl-0 padr-0 centro negrita"> <span class="fa fa-cogs"></span></div>
                    <div class="dv-clear"></div>
                </div>
                                
                <div id="contenedderecha"></div>
            </div>
            
            
            
            
                                
            <div class="dv-clear"></div>
            <br>
            
            
            <div class="col-md-12">
                <center>
                    <table style="    border: solid 0px transparent;">
                        <tr>
                            <td style="    border: solid 0px transparent;    padding-right: 7px;">
                               <div onclick="creanu()" id="btncenu" class="btn btn-primary btn-cam-trans btn-sm" title="Agregar Registro" href="#"><i class="fa fa-plus" aria-hidden="true"></i></div>
                            </td>
                            <td style="    border: solid 0px transparent;    padding-left: 7px;">
                               <form action="<?= Request::root() ?>/redes/ordenes/excelsolicitudmaniobra" method="post">
                                    <input type="hidden" name="id_orden" value="<?= $orden ?>" >
                                    <input type="hidden" name="id_proyecto" value="<?= $proyecto ?>" >
                                    <input type="hidden" name="_token" value="<?= csrf_token() ?>" >
                                    <button type='submit' class='btnsubmis'  style='color:#14b939;' ><i class='fa fa-file-excel-o' aria-hidden='true' style="    font-size: 31px;"></i></button>
                                </form> 
                            </td>
                        </tr>
                    </table>
                </center>
                
                <div id="contentnu" style="display:none;">
                    <div class="col-md-1"> Tipo</div>
                    <div class="col-md-2"> Acción de Maniobra</div>
                    <div class="col-md-2"> Hora</div>
                    <div class="col-md-3"> Elemento</div>
                    <div class="col-md-3"> Observaciones</div>
                    <div class="col-md-1"> Acciones</div>
                    
                    <div class="dv-clear"></div>
                    
                    <div class="subcontend_datnu alturad"  >
                        <form onsubmit="return submitfrm(event,this)">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="id" value="0">
                            <input type="hidden" name="id_orden" value="<?= $orden ?>">
                            <input type="hidden" name="id_proyecto" value="<?= $proyecto ?>">
                            
                            <div class=" col-md-1 padl-0 padr-0 centro alturad">                            
                                <select  id="tipo_nuevo" name="tipo_maniobra_id" onchange="cambiotipo()"  class="form-control accion taga valida_select"  >
                                    <option value="0" >Seleccione Tipo</option>
                                    <?php foreach($tipomani as $dato){ ?>
                                        <option value="<?= $dato->id ?>" ><?= $dato->descripcion ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class=" col-md-2 padl-0 padr-0 centro alturad " id="accionnuevo">                            
                                <select  id="accion_num" name="accion_maniobra_id"  class="form-control accion taga valida_select"  >
                                    <option value="0" >Seleccione Accion</option>
                                </select>
                            </div>
                            <div class=" col-md-2 padl-0 padr-0 centro alturad"> 
                               <!-- <input type="text" id="hora_num" name="hora" value=""   class="form-control hora taga valida_texto " > -->
                                <div class='input-group date no_select' id='datetimepicker1'>
                                    <input type='text' id="hora_num" name="hora"  class="form-control hora taga valida_texto " />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                            <div class=" col-md-3 padl-0 padr-0 alturad" id="elementonuevo">
                                <?php /* 
                                <select  id="elemento_num" name="elemento_maniobra_id"  class="form-control elemento taga valida_select"  >
                                    <option value="0" >Seleccione Elemento</option>
                                </select>
                                */ ?>
                                <input type='text' id="elemento_num_txt" name="elemento_maniobra_txt"  class="form-control elemento_maniobra_txt taga valida_texto " />
                               
                            </div>
                            <div class=" col-md-3 padl-0 padr-0 alturad">
                                <textarea  id="observaciones_num" name="observaciones"  class="form-control observaciones taga " ></textarea>
                            </div>
                            <div class=" col-md-1 padl-0 padr-0 centro alturad">
                                <div class="btnfrmoc centro">
                                    <button type="submit" class="btnsubmis " onclick="detener(event)" style="color:#14b939;" ><i class="fa fa-save" aria-hidden="true"></i></button>
                                    <a href="#"  onclick="cancelnuregistro(event,this);" class="btnprev " ><i class="fa fa-reply" aria-hidden="true"></i></a> 
                                </div> 
                                <img src="<?= Request::root() ?>/img/loader6.gif" class="loading" alt="Loading..." >
                            </div>
                        </form>    
                    </div>    
                    <br><br><br><br><br><br><br><br>
                </div>
                
            </div>
            
            
            
               
            
        </div>
          
          
          
          
      <div class="modal-footer"> 
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
</div>

