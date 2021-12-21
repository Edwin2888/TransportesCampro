
<div class="form-group" style="width: 90%;    margin-left: 5%;" >
    <div class="col-md-3">
        <label>Orden</label><br>
        <input type="text" id='id_orden' class="form-control" name='' >       
    </div>
    <div class="col-md-3">
        <label>Proyecto</label><br>
        <select id='prefijo' name='prefijo' class="form-control selectbusca" onchange="cambia_prefijo(event,this)">
            <option value="0" >Seleccione</option>            
            <?php foreach($proyecto as $pro){ ?>
                 <option value="<?= $pro->prefijo_db ?>" data-tipo='<?= $pro->id_proyecto ?>' ><?= $pro->proyecto ?></option> 
            <?php } ?>
        </select> 
    </div>
    <div class="col-md-3">
        <label>ID tipo proyecto</label><br>   
        <input type="text" id='id_tipo_proyecto' class="form-control" name='' readonly="">       
    </div>
    <div class="col-md-3">`
        <label>Supervisor</label><br>
        <input type="hidden" id='supervisor' >    
        <input type="text" id='supervisor_txt' class="form-control" name=''  readonly="">    
    </div>
    
    <div style="width:100%;height:4px;clear:both;"></div>
    
    
    <div class="col-md-3">
        <label>Lider</label><br>
        <select id='lider' name='lider' class="form-control selectbusca" onchange="cambia_lider(event,this)">
            <option value="0" >Seleccione</option>       
        </select>     
    </div>
    <div class="col-md-3">
        <label>Auxiliar Uno</label><br>
        <input type="hidden" id="aux1" >
        <input type="text" id='aux1txt' class="form-control" name=''  readonly="readonly">       
    </div>
    <div class="col-md-3">
        <label>Auxiliar Dos</label><br>
        <input type="hidden" id="aux2" >
        <input type="text" id='aux2txt' class="form-control" name=''  readonly="readonly">       
    </div>
    <div class="col-md-3">
        <label>Auxiliar Tres</label><br>
        <input type="hidden" id="aux3" >
        <input type="text" id='aux3txt' class="form-control" name=''  readonly="readonly">       
    </div>    
    <div style="width:100%;height:4px;clear:both;"></div>
    
    
    <div class="col-md-3">
        <label>Conductor</label><br>
        <input type="hidden" id="conductor" >
        <input type="text" id='conductortxt' class="form-control" name='' readonly="readonly" >       
    </div>
    <div class="col-md-3">
        <label>Matricula</label><br>
        <input type="text" id='matricula' class="form-control" name='' readonly="readonly" >       
    </div>
    <div class="col-md-3">
        <label>Tipo Cuadrilla</label><br>
        <input type="hidden" id="tipocu" >
        <input type="text" id='tipocutxt' class="form-control" name='' readonly="readonly" >       
    </div>
    <div class="col-md-3">
        <label>Movil</label><br>
        <input type="text" id='id_movil' class="form-control" name='' readonly="readonly" >       
    </div>    
    <div style="width:100%;height:4px;clear:both;"></div>
    
    
    
    
    
    <div class="col-md-3">
        <label>Mes / Año</label><br>
        <select id="mes" class="form-control" style="width:45%;float: left;">
            <?php $mes=intval(date("n")); ?>
            <option value="1" <?= ($mes==1)?'selected':'' ?> >Enero</option>
            <option value="1" <?= ($mes==2)?'selected':'' ?> >Febrero</option>
            <option value="1" <?= ($mes==3)?'selected':'' ?> >Marzo</option>
            <option value="1" <?= ($mes==4)?'selected':'' ?> >Abril</option>
            <option value="1" <?= ($mes==5)?'selected':'' ?> >Mayo</option>
            <option value="1" <?= ($mes==6)?'selected':'' ?> >Junio</option>
            <option value="1" <?= ($mes==7)?'selected':'' ?> >Julio</option>
            <option value="1" <?= ($mes==8)?'selected':'' ?> >Agosto</option>
            <option value="1" <?= ($mes==9)?'selected':'' ?> >Septiembre</option>
            <option value="1" <?= ($mes==10)?'selected':'' ?> >Octubre</option>
            <option value="1" <?= ($mes==11)?'selected':'' ?> >Noviembre</option>
            <option value="1" <?= ($mes==12)?'selected':'' ?> >Diciembre</option>
            
        </select>
        &nbsp;
        <select id="anio"  class="form-control" style="width:45%;float: right">
            <?php  $anio=intval(date("Y"));
                for($i=0;$i <= 5;$i++){ ?>
                    <option value="<?= ($anio-$i) ?>"  ><?= ($anio-$i) ?></option>
            <?php } ?>            
        </select>       
    </div>
    <div class="col-md-3">
        <label>Resultado</label><br>
        <input type="hidden" id='resultado' class="form-control" > 
        <input type="text" id='resultadotxt' class="form-control" name=''  readonly="readonly">      
    </div>
    <div class="col-md-3">
        <label>Calificación</label><br>
        <input type="text" id='calificacion' class="form-control" name=''  readonly="readonly">       
    </div>
    <div class="col-md-3">  
        <label>Dirección inspección</label><br>
         <input type="text" id='direccion_inspeccion' class="form-control" name='direccion_inspeccion'  > 
    </div>    
    <div style="width:100%;height:4px;clear:both;">
       
        
    </div>
    
    
    
    
</div>
<br>


<div class="form-group" style="width: 90%;    margin-left: 5%;" >
    <div class="col-md-12">
        <?= $table ?>
    </div>
</div>

<div style="width:100%;height:4px;clear:both;"></div>
<div class="form-group" style="width: 90%;    margin-left: 5%;" >
    <div class="col-md-12">
        <label>Observación</label><br>
        <input type="text" id='observacion' class="form-control" name='' >       
    </div>   
    <div style="width:100%;height:4px;clear:both;"></div>
</div>

<?php if($dato==1){ ?>

 <div class="form-group" style="width: 90%;    margin-left: 5%;" >
    <div class="col-md-12">
        <label>EVALUACIÓN DE LA GRABACIÓN DE LA CHARLA OPERATIVA</label><br>
        <?php $estilo=" style='padding-left:3px;padding-right:3px;' "; ?>
        <table>
            <tr>
                <td <?= $estilo ?> >
                    <b>Charla</b> 
                </td>
                <td <?= $estilo ?> >
                    <b>Supervisor / Realizador IPAL</b> 
                </td>
                <td <?= $estilo ?> >
                    <b>Cuadrilla</b> 
                </td>
                <td <?= $estilo ?> >
                    <b>Código</b> 
                </td>
            </tr>
            <tr>
                <td <?= $estilo ?> >
                    La cuadrilla realiza la grabación de la charla operativa
                </td>
                <td <?= $estilo ?> >
                    Revisar la grabación y la cuadrilla informa riesgos operacionales y del entorno
                </td>
                <td <?= $estilo ?> >
                    En conocimiento
                </td>
                <td <?= $estilo ?> >
                    B1 <input type="radio" name="charla" value="B1" >
                </td>
            </tr>
            <tr>
                <td <?= $estilo ?> >
                    La cuadrilla realiza la grabación de la charla operativa
                </td>
                <td <?= $estilo ?> >
                    Revisar la grabación y la cuadrilla no informa riesgos operacionales ni del entorno
                </td>
                <td <?= $estilo ?> >
                    Desconocimiento
                </td>
                <td <?= $estilo ?> >
                    B2 <input type="radio" name="charla" value="B2" >
                </td>
            </tr>
            <tr>
                <td <?= $estilo ?> >
                    La cuadrilla realiza la grabación de la charla operativa
                </td>
                <td <?= $estilo ?> >
                    Revisar la grabación y la cuadrilla informa riesgos operacionales pero no del entorno
                </td>
                <td <?= $estilo ?> >
                    En conocimiento
                </td>
                <td <?= $estilo ?> >
                    B3 <input type="radio" name="charla" value="B3" >
                </td>
            </tr>
            <tr>
                <td <?= $estilo ?> >
                    La cuadrilla no realiza la grabación de la charla operativa
                </td>
                <td <?= $estilo ?> >
                    No hay grabación
                </td>
                <td <?= $estilo ?> >
                    Desconocimiento
                </td>
                <td <?= $estilo ?> >
                    B4 <input type="radio" name="charla" value="B4" >
                </td>
            </tr>
        </table>
    </div>   
</div>   

<?php } ?>

<div style="width:100%;height:13px;clear:both;"></div>
<center>
    <a style="display: inline-block;    padding: 10px 59px !important;    margin-bottom: 15px;" href=""  class="btn btn-primary btn-cam-trans btn-sm" onclick="guarda(this,event)">
         <i class="fa fa-floppy-o" aria-hidden="true"></i> GUARDAR
   </a>
</center>


<div style="width:100%;height:13px;clear:both;"></div>
