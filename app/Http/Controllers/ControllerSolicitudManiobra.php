<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;


use Redirect;//para redirigir pagina
use DB;//para usar BD
use Session;//Para las sesiones
use Response;
use Carbon\Carbon;


class ControllerSolicitudManiobra extends Controller
{
    function __construct() {    
        $this->fechaA = Carbon::now('America/Bogota');
        //Session::put('user_login',"U01853"); //Aleja
        $this->fechaALong = $this->fechaA->toDateTimeString();   
        $this->fechaShort = $this->fechaA->format('Y-m-d');
    }

   
    public function guardanuevo(Request $request)
    {
        $datos = array();
		
	$id = 0;
	if(isset($request->all()['id'])){
            $id=trim($request->all()['id']);
        }
		
	if(isset($request->all()['id_orden'])){
            $datos['id_orden']=trim($request->all()['id_orden']);
        }
		
	if(isset($request->all()['id_proyecto'])){
            $datos['id_proyecto']=trim($request->all()['id_proyecto']);
        }
		
	if(isset($request->all()['tipo_maniobra_id'])){
            $datos['tipo_maniobra_id']=trim($request->all()['tipo_maniobra_id']);
        }
		
	if(isset($request->all()['accion_maniobra_id'])){
            $datos['accion_maniobra_id']=trim($request->all()['accion_maniobra_id']);
        }
		
	if(isset($request->all()['hora'])){
            $datos['hora']=trim($request->all()['hora']);
        }
		
	if(isset($request->all()['elemento_maniobra_id'])){
            $datos['elemento_maniobra_id']=trim($request->all()['elemento_maniobra_id']);
        }
        	
	if(isset($request->all()['elemento_maniobra_txt'])){
            $datos['elemento_maniobra_txt']=trim($request->all()['elemento_maniobra_txt']);
        }
        
		
	if(isset($request->all()['observaciones'])){
            $datos['observaciones']=trim($request->all()['observaciones']);
        }
		
        $datos['usuario_ultima_modif']=Session::get('user_login');
        
		
        
        $datoslog['usuario_id']=Session::get('user_login');
        $datoslog['descripcion']= json_encode($datos);    
        /*
          accion int NULL,
          solicitud_maniobra_id bigint NULL,
          descripcion varchar(800) COLL
        */    
        
        $datoslog['descripcion']= json_encode($datos);  
        
	if($id==0){
                $datoslog['accion']=1;
                $datos['usuario_id']=Session::get('user_login');
		$res = DB::connection('sqlsrv')->table('rds_gop_solicitud_maniobra')->insert(array( $datos ));
                //$datoslog['solicitud_maniobra_id'] = $res->id;
	}else{
            $datoslog['accion']=2;
            $datoslog['solicitud_maniobra_id'] = $id;
            $res = DB::connection('sqlsrv')->table('rds_gop_solicitud_maniobra')->where('id', $id)->update($datos);
	}
	
        $res = DB::connection('sqlsrv')->table('rds_gop_solicitud_maniobra_log')->insert(array( $datoslog ));
        
        if($res){
	    $response = array('status' => 1,'statusText' => 'Exito','message' => 'Proceso finalizado satisfactoriamente. ','accion'=>$datoslog['accion']);
        }else{
	    $response = array('status' => 0,'statusText' => 'Error','message' => 'Ocurrió un error, por favor inténtalo nuevamente más tarde. ');
	}
		
	return json_encode($response);
        
    }

    
    public function listar(Request $request){
		
        if(isset($request->all()['id_orden'])){
            $datos['id_orden']=trim($request->all()['id_orden']);
        }
		
	if(isset($request->all()['id_proyecto'])){
            $datos['id_proyecto']=trim($request->all()['id_proyecto']);
        }
        
        $proyectos = DB::connection('sqlsrv')
                     ->table('rds_gop_solicitud_maniobra')
                     ->orderBy('id')
                     ->where('id_orden',$datos['id_orden'])
                     ->where('id_proyecto',$datos['id_proyecto'])
                     ->get();        
	return json_encode($proyectos);
        
    }
	 
    public function eliminar(Request $request)
    {
        $id=0;
         if(isset($request->all()['id'])){
            $id=trim($request->all()['id']);
        }
		
        DB::connection('sqlsrv')->table('rds_gop_solicitud_maniobra')->where('id', $id)->delete();
		
        
        $datoslog['usuario_id']=Session::get('user_login');
        $datoslog['descripcion']= "Elimino registro"; 
        $datoslog['accion']=3;
        $datoslog['solicitud_maniobra_id'] = $id;
        $res = DB::connection('sqlsrv')->table('rds_gop_solicitud_maniobra_log')->insert(array( $datoslog ));
          
        
        return json_encode(['result'=>'1']);
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
