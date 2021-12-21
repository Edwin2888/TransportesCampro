<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;


use Redirect;//para redirigir pagina
use DB;//para usar BD
use Session;//Para las sesiones
use Response;
use Carbon\Carbon;


class ControllerDescuentoArrendamiento extends Controller
{
    function __construct() {    
        $this->fechaA = Carbon::now('America/Bogota');
        //Session::put('user_login',"U01853"); //Aleja
        $this->fechaALong = $this->fechaA->toDateTimeString();   
        $this->fechaShort = $this->fechaA->format('Y-m-d');
    }

   
    public function guardaedita(Request $request)
    {
        $datos = array();
		
	$id = 0;
	if(isset($request->all()['id_descuento'])){
            $id=trim($request->all()['id_descuento']);
        }
		
	if(isset($request->all()['id_concepto_decuento'])){
            $datos['id_concepto_descuento']=trim($request->all()['id_concepto_decuento']);
        }
		
	if(isset($request->all()['valor_descuento'])){
            $datos['valor']=trim($request->all()['valor_descuento']);
        }
		
	if(isset($request->all()['id_ano_decuento'])){
            $datos['ano']=trim($request->all()['id_ano_decuento']);
        }
		
	if(isset($request->all()['id_mes_decuento'])){
            $datos['mes']=trim($request->all()['id_mes_decuento']);
        }
        		
		
        $datos['id_usuario']=Session::get('user_login');
               
        $datoslog['id_usuario']=Session::get('user_login');
        $datoslog['registro']= json_encode($datos);    
        
        $adjunto=0;
        
        if( $request->file('file')!=null && $request->file('file')!=''){
            //obtenemos el campo file definido en el formulario
        
           $file = $request->file('file');
 
            //obtenemos el nombre del archivo
            $nombre = $file->getClientOriginalName();
            $extension = strtolower($file->getClientOriginalExtension());
            
            $nuevonombre = "descuento_".$fecah= date('Y-m-d--g-i-s').".".$extension;
             //indicamos que queremos guardar un nuevo archivo en el disco local
             \Storage::disk('local')->put($nombre,  \File::get($file));
             
            $id_ftp=ftp_connect("201.217.195.43",21); //Obtiene un manejador del Servidor FTP
            ftp_login($id_ftp,"usuario_ftp","74091450652!@#1723cc"); //Se loguea al Servidor FTP
            ftp_pasv($id_ftp,true); //Se coloca el modo pasivo
            ftp_chdir($id_ftp, "ins"); // Nos dirigimos a la carpeta de destino
            $Directorio=ftp_pwd($id_ftp);
            $Directorio2=$Directorio;
            //Guardar el archivo en mi carpeta donde esta el proyecto    
            $res = 0;
            try{
                $fileL = storage_path('app') . "/" .  $nombre;
                //\Storage::disk('local')->put($nombre, $imagenDigital);
                $exi = ftp_put($id_ftp,$Directorio . "/" . $nuevonombre,$fileL,FTP_BINARY); 
                
                //echo "adjunti |".$Directorio . "/" . $nuevonombre."|";
                //Cuando se envia el archivo, se elimina el archivo
                if(file_exists($fileL)) unlink($fileL);
                
                $adjunto=1;
                $datos['adjunto']=$nuevonombre;
            }catch(Exception $e){
                $res = $e;
            }
        
        }
        
        
        
	if($id==0){
                $datoslog['accion']=1;
                $datos['id_usuario']=Session::get('user_login');
                if(isset($request->all()['placa'])){
                    $datos['placa']=trim($request->all()['placa']);
                }
		$res = DB::connection('sqlsrvCxParque')->table('tra_arrendamiento_descuento')->insert(array( $datos ));
                //$datoslog['solicitud_maniobra_id'] = $res->id;
                $datoslog['descripcion']= "Creacion de registro"; 
	}else{
            $datoslog['accion']=2;
            $datoslog['id_descuento'] = $id;
            $res = DB::connection('sqlsrvCxParque')->table('tra_arrendamiento_descuento')->where('id', $id)->update($datos);
            
            $datoslog['descripcion']= "Actualiza registro"; 
	}
	
        $res = DB::connection('sqlsrvCxParque')->table('tra_arrendamiento_log_descuento')->insert(array( $datoslog ));
        
        
        
        if($res){
	    $response = array('status' => 1,'statusText' => 'Exito','message' => 'Proceso finalizado satisfactoriamente. ','accion'=>$datoslog['accion']);
        }else{
	    $response = array('status' => 0,'statusText' => 'Error','message' => 'Ocurrió un error, por favor inténtalo nuevamente más tarde. ');
	}
		
	return json_encode($response);
        
    }

    
    public function listar(Request $request){
		
        $placa=''; 
        if(isset($request->all()['placa'])){
            $placa=trim($request->all()['placa']);
        }
	
        $ano="";
	if(isset($request->all()['ano'])){
            $ano=trim($request->all()['ano']);
        }
        
        $mes="";
	if(isset($request->all()['mes'])){
            $mes=trim($request->all()['mes']);
        }
        
           
        $direc="";
	if(isset($request->all()['dir'])){
            $direc=trim($request->all()['dir']);
        }
        
        
        
        ////////////////////////////
        ////////////////////////////////
        
        
        $responce = array();

        $page = $request->all()['draw']; // get the requested page
        $limit = $request->all()['length']; // get how many rows we want to have into the grid
        //$sidx = $request->all()['sidx']; // get index row - i.e. user click to sort
        // $sord = $request->all()['sord'];
        $start = $request->all()['start'];
        $columnas = $request->all()['columns'];

        $ordena = $request->all()['order'];
        $ordenamiento = " order by a.creacion desc ";
        if (isset($request->all()['order']) && isset($ordena[0]['column']) && $ordena[0]['column'] != null && $ordena[0]['column'] != 0) {


            $ordena = $request->all()['order'];
            $ncol = $ordena[0]['column'];

            $colunma = $columnas[$ncol];
            if ($colunma['orderable']) {
                $order = $colunma['name'];
                                
            $direcc = $request->all()['order'];
            $dir = $direcc[0]['dir'];

                     if ($order == 'datouc') { $order = 'u.propietario'; } 
                else if ($order == 'datot') { $order = "CONCAT(CONVERT(varchar,a.creacion,103),' ',CONVERT(varchar,a.creacion,108))"; }
                else if ($order == 'datod') { $order = "a.valor";   }
                else if ($order == 'datou') { $order = "d.descripcion";   }

                $ordenamiento = " order by " . $order . " " . $dir . " ";
            }
        }

        ///
        $search = $request->all()['search'];
        $buscar = "";
        if (isset($request->all()['search']) && isset($search['value']) && $search['value'] != null && $search['value'] != '') {
            $buscarc = $search['value'];
            $buscar = " and (  
                                d.descripcion like '%" . $buscarc . "%' or
                                a.valor like '%" . $buscarc . "%' or
                                CONCAT(CONVERT(varchar,a.creacion,103),' ',CONVERT(varchar,a.creacion,108))  like '%" . $buscarc . "%' or
                                u.propietario like '%" . $buscarc . "%' 
                            )     ";
        }

        
                    
             
        
        //////////////////////
        /////////////////
        

    $scantidad = "select 
                   count(*) as cantidad
            from PARQUE.dbo.tra_arrendamiento_descuento as a 
                 inner join PARQUE.dbo.tra_arrendamiento_concepto_descuento as d on (a.id_concepto_descuento=d.id)
                 inner join CAMPRO.dbo.sis_usuarios as u on (a.id_usuario=u.id_usuario)                  
            where  
                 placa='".$placa."' and ano='".$ano."' and mes='".$mes."'  
                " . $buscar . "";
//echo $spt;   
    
    
    
        try{  
            $rcant = DB::select("SET NOCOUNT ON;" . $scantidad); 
            $responce['recordsTotal'] = $rcant[0]->cantidad;//$total_pages;
            $responce['recordsFiltered'] = $rcant[0]->cantidad;//        
        } 
        catch (\PDOException $e) {
            $responce['recordsTotal'] = 0;//$total_pages;
            $responce['recordsFiltered'] = 0;//     
        }
         
        $responce['draw'] = $page;//$page;
        $responce['data'] = array();

        
        

    $spt = "select 
                a.id,
                a.id_usuario,
                u.propietario as crea,
                a.mes,
                a.ano,
                CONCAT(CONVERT(varchar,a.creacion,103),' ',CONVERT(varchar,a.creacion,108)) as fecha,
                CONCAT(CONVERT(varchar,a.ultima_edicion,103),' ',CONVERT(varchar,a.ultima_edicion,108)) as ultima_edicion,
                a.valor,
                a.id_concepto_descuento,
                d.descripcion as concepto,
                a.adjunto
            from PARQUE.dbo.tra_arrendamiento_descuento as a 
                 inner join PARQUE.dbo.tra_arrendamiento_concepto_descuento as d on (a.id_concepto_descuento=d.id)
                 inner join CAMPRO.dbo.sis_usuarios as u on (a.id_usuario=u.id_usuario)                  
            where  
                 placa='".$placa."' and ano='".$ano."' and mes='".$mes."'   
                " . $buscar . "
                " . $ordenamiento . "
            OFFSET " . $start . " ROWS FETCH NEXT " . $limit . " ROWS ONLY ";
//echo $spt;   
    
    
    
        try{  $cond = DB::select( $spt); } 
        catch (\PDOException $e) { $cond = array(); }
              // echo $spt;

        /*
    

        $i++;*/
        
        for($indi=0;$indi<count($cond);$indi++){
            
            $descargar ="";
            if($cond[$indi]->adjunto!=null && trim($cond[$indi]->adjunto)!=''){
               $descargar ='<center><a href="http://201.217.195.43/ins/'.$cond[$indi]->adjunto.'" title="Log" target="_blank" >      <span><i class="fa fa-cloud-download" aria-hidden="true"> </i></span></a></center> '; 
            }
            
            
                 $nuevo = array(
                    $cond[$indi]->concepto,
                    $cond[$indi]->valor,
                    $cond[$indi]->fecha,
                    $cond[$indi]->crea, 
                    $descargar,
                    '<center>
                        <a href="" title="Editar" onclick="editadescuento(event,this,\''.$cond[$indi]->id.'\',\''.$cond[$indi]->valor.'\',\''.$cond[$indi]->id_concepto_descuento.'\',\''.$cond[$indi]->ano.'\',\''.$cond[$indi]->mes.'\')" style="padding-left: 3px;padding-right: 3px;">   <span><i class="fa fa-edit" aria-hidden="true">        </i></span></a>
                        <a href="" title="Eliminar" onclick="eliminadescuento(event,this,\''.$cond[$indi]->id.'\',\''.$placa.'\')" style="padding-left: 3px;padding-right: 3px;"> <span><i class="fa fa-trash-o" aria-hidden="true">     </i></span></a>
                        <img src="'.$direc.'/img/loader6.gif" class="loadingd" alt="Loading..." style="display:none;    height: 19px; width: 19px;" >
                        <a href="" title="Log" onclick="logdescuento(event,this,\''.$cond[$indi]->id.'\')" style="padding-left: 3px;padding-right: 3px;">      <span><i class="fa fa fa-list-alt" aria-hidden="true"> </i></span></a>
                    </center>
                    '
                );
            
            array_push($responce['data'], $nuevo);   
        
        }
        
        
        
        
        
              
	return json_encode($responce);
        
    }
	 
    
    public function listarlog(Request $request){
		
        $placa=''; 
        if(isset($request->all()['placa'])){
            $placa=trim($request->all()['placa']);
        }
	
        $idlog="";
	if(isset($request->all()['id'])){
            $idlog=trim($request->all()['id']);
        }
        
        $mes="";
	if(isset($request->all()['mes'])){
            $mes=trim($request->all()['mes']);
        }
        
           
        $ano="";
	if(isset($request->all()['ano'])){
            $ano=trim($request->all()['ano']);
        }
        
        
        
        ////////////////////////////
        ////////////////////////////////
        
        
        $responce = array();

        $page = $request->all()['draw']; // get the requested page
        $limit = $request->all()['length']; // get how many rows we want to have into the grid
        //$sidx = $request->all()['sidx']; // get index row - i.e. user click to sort
        // $sord = $request->all()['sord'];
        $start = $request->all()['start'];
        $columnas = $request->all()['columns'];

        $ordena = $request->all()['order'];
        $ordenamiento = " order by  d.fecha desc ";
        if (isset($request->all()['order']) && isset($ordena[0]['column']) && $ordena[0]['column'] != null && $ordena[0]['column'] != 0) {


            $ordena = $request->all()['order'];
            $ncol = $ordena[0]['column'];

            $colunma = $columnas[$ncol];
            if ($colunma['orderable']) {
                $order = $colunma['name'];
                                
            $direcc = $request->all()['order'];
            $dir = $direcc[0]['dir'];

                     if ($order == 'datou') { $order = 'u.propietario'; } 
                else if ($order == 'datod') { $order = "d.descripcion"; }
                else if ($order == 'datot') { $order = "CONCAT(convert(varchar,d.fecha,103),' ',CONVERT(varchar,d.fecha,108))";   }
                

                $ordenamiento = " order by " . $order . " " . $dir . " ";
            }
        }

        ///
        $search = $request->all()['search'];
        $buscar = "";
        if (isset($request->all()['search']) && isset($search['value']) && $search['value'] != null && $search['value'] != '') {
            $buscarc = $search['value'];
            $buscar = " and (  
                             u.propietario like '%" . $buscarc . "%' or
                             d.descripcionr like '%" . $buscarc . "%' or
                             CONCAT(convert(varchar,d.fecha,103),' ',CONVERT(varchar,d.fecha,108))  like '%" . $buscarc . "%'
                        )     ";
        }

        
                    
             
        
        //////////////////////
        /////////////////
        

    $scantidad = "select 
                        count(*) as cantidad
                 from PARQUE.dbo.tra_arrendamiento_log_descuento as d 
                      inner join CAMPRO.dbo.sis_usuarios as u on (d.id_usuario=u.id_usuario)                  
                 where  
                      d.id_descuento='".$idlog."' 
                " . $buscar . "";
//echo $spt;   
    
    
    
        try{  
            $rcant = DB::select("SET NOCOUNT ON;" . $scantidad); 
            $responce['recordsTotal'] = $rcant[0]->cantidad;//$total_pages;
            $responce['recordsFiltered'] = $rcant[0]->cantidad;//        
        } 
        catch (\PDOException $e) {
            $responce['recordsTotal'] = 0;//$total_pages;
            $responce['recordsFiltered'] = 0;//     
        }
         
        $responce['draw'] = $page;//$page;
        $responce['data'] = array();

        
        

    $spt = "select 
                u.propietario,
                d.descripcion,
                CONCAT(convert(varchar,d.fecha,103),' ',CONVERT(varchar,d.fecha,108)) as fecha
         from PARQUE.dbo.tra_arrendamiento_log_descuento as d 
              inner join CAMPRO.dbo.sis_usuarios as u on (d.id_usuario=u.id_usuario)                  
         where  
              d.id_descuento='".$idlog."'  
                " . $buscar . "
                " . $ordenamiento . "
            OFFSET " . $start . " ROWS FETCH NEXT " . $limit . " ROWS ONLY ";
//echo $spt;   
    
    
    
        try{  $cond = DB::select( $spt); } 
        catch (\PDOException $e) { $cond = array(); }
              // echo $spt;

        /*
    

        $i++;*/
        
        for($indi=0;$indi<count($cond);$indi++){
            
                 $nuevo = array(
                    $cond[$indi]->propietario,
                    $cond[$indi]->descripcion,
                    $cond[$indi]->fecha
                );
            
            array_push($responce['data'], $nuevo);   
        
        }
        
        
        
              
	return json_encode($responce);
        
    }
    
    public function eliminar(Request $request)
    {
        $id=0;
         if(isset($request->all()['id'])){
            $id=trim($request->all()['id']);
        }
		
        DB::connection('sqlsrvCxParque')->table('tra_arrendamiento_descuento')->where('id', $id)->delete();
		
        
        $datoslog['id_usuario']=Session::get('user_login');
        $datoslog['descripcion']= "Elimino registro"; 
        $datoslog['accion']=3;
        $datoslog['id_descuento'] = $id;
        $res = DB::connection('sqlsrvCxParque')->table('tra_arrendamiento_log_descuento')->insert(array( $datoslog ));
          
        
        return json_encode(['result'=>'1']);
    }
    
    
    public function suma(Request $request){
        
        $placa=''; 
        if(isset($request->all()['placa'])){
            $placa=trim($request->all()['placa']);
        }
        
        $mes="";
	if(isset($request->all()['mes'])){
            $mes=trim($request->all()['mes']);
        }
        
           
        $ano="";
	if(isset($request->all()['ano'])){
            $ano=trim($request->all()['ano']);
        }
        
        
        $sql="select ISNULL(sum(valor),0) as suma from PARQUE.dbo.tra_arrendamiento_descuento where placa='".$placa."' and ano = '".$ano."' and mes='".$mes."'";
        
        
        try{  $cond = DB::select( $sql); 
              
            return json_encode(array('cantidad'=>$cond[0]->suma,'cantidadd'=> number_format($cond[0]->suma, 2) ));  
        
        } 
        catch (\PDOException $e) { 
            return json_encode(array('cantidad'=>0,'cantidadd'=>0));            
        }
        
    }
    
    
    
    public function listadocategoria(Request $request){
                
        $mes="";
	if(isset($request->all()['mes'])){
            $mes=trim($request->all()['mes']);
        }
                   
        $ano="";
	if(isset($request->all()['ano'])){
            $ano=trim($request->all()['ano']);
        }
        
        $prefijo="";
	if(isset($request->all()['id_area'])){
            $prefijo=trim($request->all()['id_area']);
        }
        
        $dias=0;
	if(isset($request->all()['dias'])){
            $dias=trim($request->all()['dias']);
        }
        
        $mesPasado = intval($mes) - 1;
        $anioActual = $ano;
        $anioPasado = ($mesPasado == 0 ? intval($anioActual) - 1 : $anioActual);
        $mesPasado = ($mesPasado == 0 ? 12 : $mesPasado);
        
        // DAY(dateadd(ms,-3,DATEADD(mm, DATEDIFF(m,0,CONCAT(@anio,'-',@mes,'-1') )+1, 0)));
        $spt = " 
                  DECLARE @mes integer;
                  DECLARE @anio integer; 
                  DECLARE @diasmes integer; 
                  DECLARE @mesd integer;
                  DECLARE @aniod integer; 
                  DECLARE @prefijo nvarchar(5); 
                  SET @mes  = ".$mes.";
                  SET @anio = ".$ano."; 
                  SET @mesd  = ".$mesPasado.";
                  SET @aniod = ".$anioPasado."; 
                  SET @prefijo = '".$prefijo."';
                  set @diasmes = ".$dias.";-- DAY(dateadd(ms,-3,DATEADD(mm, DATEDIFF(m,0,CONCAT(@anio,'-',@mes,'-1') )+1, 0)));
                  select 
                     tipo_vinculo,
                     sum(canon_actual) as valor_contrato,
                     count(placa) as cantidad,
                     sum(pagar) as pagar,
                     sum(total_pagar) as total_pagar,
                     sum(descuento) as descuentos,
                     sum(total_pagar)-sum(descuento) as pagarreal
                  from (   
                    select 
                        DISTINCT
                         m.placa,
                         m.id_tipo_vinculo,
                         t.nombre as tipo_vinculo,
                         iif(a.cantidad_dias>@diasmes,@diasmes,a.cantidad_dias) as cantidad_dias,
                         CONVERT(decimal(18,2),  ISNULL(a.canon_actual,m.valor_contrato) ) as canon_actual,
                         CONVERT(decimal(18,2),a.total_pagar) as total_pagar,
                         @diasmes as diasmes,
                         ((CONVERT(decimal,  ISNULL(a.canon_actual,m.valor_contrato) ) /@diasmes)* a.cantidad_dias ) as pagar,
                         ((CONVERT(decimal,  ISNULL(a.canon_actual,m.valor_contrato) ) /@diasmes)* ( iif(a.cantidad_dias>@diasmes,@diasmes,a.cantidad_dias) )) as pagard,
                         (select ISNULL(sum(valor),0) as suma from PARQUE.dbo.tra_arrendamiento_descuento where placa=m.placa and ano = @anio and mes=@mes ) as descuento
                  from 
                      (select 
                              placa,id_proyecto,dia
                              from PARQUE.dbo.tra_vehiculo_ubicacion_proyecto 
                       where 
                              (mes =  @mes  and anio = @anio AND dia < 26 ) OR 
                              (mes =  @mesd and anio =  @aniod  AND dia > 25 )
                       GROUP BY placa,id_proyecto,dia) as histoVehico

                      INNER JOIN 

                      PARQUE.dbo.tra_maestro as m on ( m.placa = histoVehico.placa) inner join
                      PARQUE.dbo.tra_arrendamiento as a  on ( a.placa = m.placa ) inner join 
                      PARQUE.dbo.tra_tipo_vinculacion as t on (m.id_tipo_vinculo = t.id_tipo_vinculo)
                  where 
                         a.anio=@anio and
                         a.mes =@mes and 
                       --  ( a.id_estado = 'E3' OR a.id_estado = 'E1' ) AND
                         a.prefijo = @prefijo
                        -- and  m.id_tipo_vehiculo IN (4,6)
                  ) as tabla 
                  group by id_tipo_vinculo,tipo_vinculo

                      ";
//echo $spt;   
    
    
    
        try{  $cond = DB::select( $spt); } 
        catch (\PDOException $e) { $cond = array(); }
        
        
            return json_encode(array('status' => 1,'datos'=>$cond));      
        
    }
    
    
    //******************************************************
    //-----------------    GENERACIÓN EXCEL IPAL
    //******************************************************
    public function generaExcel(Request $request)
    {
        $id_orden    =  $request->all()["id_orden"];
        $id_proyecto =  $request->all()["id_proyecto"];
        
        $datos = ['id_orden'=>$id_orden,'id_proyecto'=>$id_proyecto];
        

        \Excel::create('registro' . $this->fechaALong, function($excel) use($datos) {


                 
                $excel->setTitle('Solicitud de maniobras');
                $excel->setCreator('Cam Colombia')
                      ->setCompany('Cam Colombia');
            
                $excel->sheet('Solicitud Maniobra', function($sheet) use($datos){
                    $titulos = ["ITEM","ACCIÓN DE MANIOBRA","HORA","ELEMENTO","OBSERVACIONES","ITEM","ACCIÓN DE MANIOBRA","HORA","ELEMENTO","OBSERVACIONES"];
                    $sheet->fromArray($titulos);


                    $consulta="select 
                                 sm.id,
                                 sm.accion_maniobra_id,
                                 am.descripcion as accionsss,
                                 am.codigo as accion,
                                 convert(varchar,sm.hora,103) as fecha,
                                 convert(varchar,sm.hora,108) as hora,
                                 sm.elemento_maniobra_id,
                                 em.descripcion as elementoant,
                                 elemento_maniobra_txt as elemento,
                                 sm.observaciones,
                                 sm.tipo_maniobra_id
                            from 
                                rds_gop_solicitud_maniobra as sm inner join 
                                rds_gop_accion_maniobra as am on ( sm.accion_maniobra_id=am.id ) left join 
                                rds_gop_elemento_maniobra as em on ( sm.elemento_maniobra_id = em.id )
                            where sm.id_orden='".$datos['id_orden']."' and sm.id_proyecto='".$datos['id_proyecto']."'  ";
                 /*
                    $clase =
            DB::connection('sqlsrvCxParque')
                ->table('tra_maestro as tbl4')
                ->join('tra_maestro_clase_documentos as tbl3','tbl3.id_clase','=','tbl4.id_clase')
                ->join('tra_tipo_clase as tbl1','tbl1.id_tipo_clase','=','tbl3.id_clase')
                ->where('tbl4.placa',$placa)
                ->select('tbl1.nombre')
                ->get()[0]->nombre;*/
                    $dat = DB::connection('sqlsrv')->select($consulta);
                    
                   
                    $fila = 2;
                    $cont = 1;
                    $filad = 2;
                    $contd = 1;
                    foreach ($dat as $daro) {
                              /*  $sheet->row($fila +1, array( $cont ,  $daro->accion,  $daro->hora,   $daro->elemento,  $daro->observaciones,  $daro->tipo_maniobra_id ));  
                                $fila++;  
                                $cont++; */    
                        if($daro->tipo_maniobra_id==1){        
                            /*$sheet->protectCells('A'.$fila, $cont);
                            $sheet->protectCells('B'.$fila, $daro->accion);
                            $sheet->protectCells('C'.$fila, $daro->hora);
                            $sheet->protectCells('D'.$fila, $daro->elemento);
                            $sheet->protectCells('E'.$fila, $daro->observaciones);
                            $fila++;  
                            $cont++; */
                             $sheet->row($fila, array( $cont ,  $daro->accion,  $daro->hora,   $daro->elemento,  $daro->observaciones ));  
                                $fila++;  
                                $cont++;
                        }else{        
                            //$sheet->protectCells('F'.$filad, $contd);
                            
                            $sheet->SetCellValue("F$filad",  $contd);
                            $sheet->SetCellValue('G'.$filad, $daro->accion);
                            $sheet->SetCellValue('H'.$filad, $daro->hora);
                            $sheet->SetCellValue('I'.$filad, $daro->elemento);
                            $sheet->SetCellValue('J'.$filad, $daro->observaciones);
                            $filad++;  
                            $contd++; 
                        }
                    }
                });
        })->export('xls');
    }
}
