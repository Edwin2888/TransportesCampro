<button data-toggle="collapse" data-target="#filter" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar">
    <i class="fa fa-filter"></i> &nbsp;&nbsp;Filtrar
</button>

<div id="filter" class="collapse in" style="margin-top:9px;border: 1px solid #3b8bd0;    border-radius: 10px;" arial-expanded="true">
    <div class="panel-body posisition-fixed-headaer" style="border-radius: 10px;padding-top: 12px;">
        <div class="row">

        <form action="{{ url('chevystar/consultaKm') }}" method="POST">
            {{ csrf_field() }}

            <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden">Placa:</label>
                    <input type="text" class="form-control"  name="placa"/>
                </div> 
            </div>
            <div class="col-md-2">                
                <label for="id_proyect">Desde:</label>
                <div class="input-group ">                                                   
                    <input class="form-control" type="date" name="fechaInicio" id="" value="{{ date('Y-m-d') }}" required>                       
                </div>                
            </div>        
            <div class="col-md-2">
                <label for="text_nombre_proyect">Hasta:</label>
                <div class="input-group ">                                                   
                    <input class="form-control" type="date" name="fechaFin" id="" value="{{ date('Y-m-d') }}" required>                       
                </div>                                
            </div>    
            <button type="submit" class="btn btn-primary btn-cam-trans btn-sm" style="margin-top:20px;" id="btn-add-nodos-orden" ><i class="fa fa-search"></i>  Consultar</button>
        </form>
        </div>
    </div>
</div>


