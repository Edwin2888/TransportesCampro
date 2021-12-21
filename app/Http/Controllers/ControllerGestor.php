<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Redirect;//para redirigir pagina
use DB;//para usar BD
use Session;//Para las sesiones
use Carbon\Carbon;
use Response;



class ControllerGestor extends Controller
{

    /*************************************************/
    /******** FUNCIONES DEL CONTROLADOR **************/

    //Función contructor
    function __construct() {

        //Session::put('user_login',"U00190"); //Oscar
        //Session::put('user_login',"U01853"); //Aleja
        //Session::put('user_login',"U03783"); //Karen Tinjaca
       
       // Session::put('user_login',"U01852");
       // Session::put('proy_short',"home");
        $this->fechaA = Carbon::now('America/Bogota');
        $this->fechaALong = $this->fechaA->toDateTimeString();
        $this->fechaShort = $this->fechaA->format('Y-m-d');
    }
    
    public function bandeja($tipo=''){
        
        if(!Session::has('user_login')){
            return Redirect::to('{{config("app.Campro")[2]}}/campro');
        }
        
        
        
        /*
        if(count($permisoIncidencia) == 0){return view('errors.nopermiso');}   

        // W -> Total
        // R -> Restringuido
        // N -> No tiene acceso

        if($permisoIncidencia[0]->nivel_acceso == "N"){return view('errors.nopermiso');}    

        */
        $tipod=0;//materiles
        if($tipo=='descargos'){
            $tipod=1;//descargos            
        }
        
        
        $tipopro = DB::connection('sqlsrv')
                ->table('rds_gop_tipo_proyecto')
                ->orderBy('des_proyecto')
                ->get();

        $usuarios = DB::connection('sqlsrv')
                ->table('sis_usuarios')
                ->orderBy('propietario')
                ->get();
        
        
        return view('proyectos.redes.trabajoprogramado.indexGestorMatriales',array(
            'tipod'     =>  $tipod,
            'tipopro'   =>  $tipopro,
            'usuarios'  =>  $usuarios
        ));
        
    }
    
    public function listar(Request $request){
		
        $complementosql="";
        
      
		
        $tipo=0;//0 materiales //1 descargos
        
	if(isset($request->all()['tipo'])){
            $tipo=trim($request->all()['tipo']);
        }
         
        //0 inbox (todo en orden de fecha) 1 no leidos 2 leidos 
        if(isset($request->all()['bandeja']) && ($request->all()['bandeja']==1 || $request->all()['bandeja']==2) ){
            $bandeja=trim($request->all()['bandeja']);
            if($bandeja==1){
                if($tipo==0){$complementosql .= " and (o.lectura_grupo_uno is null  or  o.lectura_grupo_uno=0) ";}
                else{$complementosql .= " and (o.lectura_grupo_dos is null  or  o.lectura_grupo_dos=0) ";}
            }else if($bandeja==2){
               if($tipo==0){$complementosql .= " and (o.lectura_grupo_uno=1) ";}
               else{$complementosql .= " and (o.lectura_grupo_dos=1) ";}
            }
        }
        
        
        //0 sin especificar
	if(isset($request->all()['tipoproyecto']) && $request->all()['tipoproyecto']!='0'){
            $tipoproyecto=trim($request->all()['tipoproyecto']);            
            $complementosql .= " and p.tipo_proyecto = '".$tipoproyecto."' ";
        }
        
        $proyecto='';//'' sin especificar
  if(isset($request->all()['proyecto']) && $request->all()['proyecto']!=''){
            $proyecto=trim($request->all()['proyecto']);
            $complementosql .= " and p.nombre like '%".$proyecto."%' ";
        }

        //$id_usuario = 'U00196'; // Session::get('user_login');
        //$complementosql .= " AND ( usumaterialer.id_usuario = '{$id_usuario}' OR usudescargos.id_usuario = '{$id_usuario}' )";
        
       //'' sin especificar
	if(isset($request->all()['fecha1']) && $request->all()['fecha1']!=''){
            $fecha1=trim($request->all()['fecha1']);
            $complementosql .= " and o.fecha>= '".$fecha1."' ";
        }
        
       //'' sin especificar
	if(isset($request->all()['fecha2']) && $request->all()['fecha2']!=''){
            $fecha2=trim($request->all()['fecha2']);
            $complementosql .= " and o.fecha<= '".$fecha2."' ";
        }
                         
        
        
        
        
        
        $pagina=1;//'' sin especificar
	if(isset($request->all()['pagina'])){
            $pagina=trim($request->all()['pagina']);
        }
        $limite=10;
        $inicia = $limite*$pagina - $limite; // do not put $limit*($page - 1)
        if($inicia < 0){$inicia=0;}
        
        $sql="  select 
                    concat(convert(varchar,o.fecha,103),' ',convert(varchar,o.fecha,108)) as fecha,
                    tp.des_proyecto as tipoproyecto,
                    p.nombre as proyecto,
                    o.id_orden,
                    convert(varchar,o.fecha_ejecucion,103) as fecha_ejecucion,
                    convert(varchar,o.fecha_ejecucion_final,103) as fecha_ejecucion_final,
                    o.hora_corte,
                    o.hora_cierre,
                    o.observaciones,
                    usumaterialer.propietario as usuariomaterialer,
                    usudescargos.propietario as usuariodescargos,
                    o.id_proyecto,
                    lectura_grupo_uno,
                    lectura_grupo_dos";
        
         if($tipo==0){
                   $sql .="  ,
                     STUFF(  (  select 
                                      '<br><a  href=\"{{config('app.Campro')[2]}}/campro/inventarios/rds/despachos.php?id_documento='+doc.id_documento+'\" target=\"_blank\" > '+doc.id_documento+'</a>'
                                from
                                     rds_inv_documentos as doc 
                                where  
                    doc.id_orden=o.id_orden and 
                    doc.id_tipo_movimiento='T005'  FOR XML PATH('')),1 ,1, '') as despachos";
        }
        $sql .="
                 from rds_gop_ordenes as o inner join rds_gop_proyectos as p on (o.id_proyecto=p.id_proyecto)
                                           inner join rds_gop_tipo_proyecto as tp on (p.tipo_proyecto=tp.id_proyecto)
                                           left join sis_usuarios as usumaterialer on (o.usuario_grupo_uno=usumaterialer.id_usuario)
                                           left join sis_usuarios as usudescargos on (o.usuario_grupo_dos=usudescargos.id_usuario)
                 where  
                    o.id_tipo in ('T56','T06')  ".$complementosql."
                order by o.fecha desc
              OFFSET ".$inicia." ROWS FETCH NEXT ".$limite." ROWS ONLY  ";
       // echo $sql;
        $proyectos = DB::select($sql);   
        /*
        if($tipo==0){
            for($i=0;$i<count($proyectos);$i++){ 
                $idorden=$proyectos[$i]->id_orden;
                //echo "=>".$idorden."<=";
                $despachos=[];
                $sqld="  select 
                            doc.id_documento as numdespacho
                        from rds_inv_documentos as doc 
                         where  
                         doc.id_orden='".$idorden."' and 
                         doc.id_tipo_movimiento='T005' ";
               $proyectosd = DB::select($sqld);   
               foreach ($proyectosd as $pro){
                   array_push($despachos, $pro->numdespacho);
               }
               $proyectos[$i]->despachos=(object)$despachos;
            }
        } */

        // $proyectos['sql'] = $sql;
            
	return json_encode($proyectos);
        
    }
    
    
    
    public function verdetalle($idproyecto='',$idorden='',$tipo=0){
        ////0 materiales //1 descargos 
        
        if($tipo==0 || $tipo=='0'){                               // ->where('id_orden', '=', $idorden) ->where(function ($query) { $query->where('lectura_grupo_uno', '=', 0)->orWhere('lectura_grupo_uno', 'is', null); })
            $res = DB::connection('sqlsrv')->table('rds_gop_ordenes')->where('id_orden', $idorden)->where(function ($query) { $query->whereNull('lectura_grupo_uno')->orWhere('lectura_grupo_uno', '=',0); })->update(array('lectura_grupo_uno' => '1','usuario_grupo_uno'=>Session::get('user_login')));
        }else{    
            $res = DB::connection('sqlsrv')->table('rds_gop_ordenes')->where('id_orden', $idorden)->where(function ($query) { $query->whereNull('lectura_grupo_dos')->orWhere('lectura_grupo_dos', '=',0); })->update(array('lectura_grupo_dos' => '1','usuario_grupo_dos'=>Session::get('user_login')));
        }
            
        return Redirect::to('redes/ordenes/ordentrabajo/'.$idproyecto.'/'.$idorden); 
        
    }
    
        //Consulta permisos usuarios
    private function consultaAcceso($opc)
    {
        $id_perfil = DB::table('sis_usuarios')
                    ->where('id_usuario',Session::get('user_login'))
                    ->value('id_perfil');

        return  DB::table('sis_perfiles_opciones')
                    ->where('id_perfil',$id_perfil)
                    ->where('id_opcion',$opc)
                    ->select('id_opcion','nivel_acceso')
                    ->get();
    }
    
    
}

