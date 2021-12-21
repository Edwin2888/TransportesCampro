<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;


use Redirect;//para redirigir pagina
use DB;//para usar BD
use Session;//Para las sesiones
use Response;


class ControllerListProveedores extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $talleres = DB::connection('sqlsrvCxParque')
                ->table('tra_talleres_gps')
                ->orderBy('id')
                ->get();        
                
        return view('proyectos.transporte.proveedores.listarProveedores',array("talleres"=>$talleres));

		
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function guardanuevo(Request $request)
    {
        $datos = array();
		
		$id = 0;
		if(isset($request->all()['nombre'])){
             $id=trim($request->all()['id']);
        }
		
		
		if(isset($request->all()['nombre'])){
             $datos['nombre_proveedor']=trim($request->all()['nombre']);
        }
		
		if(isset($request->all()['nit'])){
             $datos['nit']=trim($request->all()['nit']);
        }
		
		if(isset($request->all()['direccion'])){
             $datos['direccion']=trim($request->all()['direccion']);
        }
		
		if(isset($request->all()['telefono'])){
             $datos['telefono']=trim($request->all()['telefono']);
        }
		
		if(isset($request->all()['especialidad'])){
             $datos['especialidad']=trim($request->all()['especialidad']);
        }
		
		if(isset($request->all()['proyecto'])){
             $datos['proyecto']=trim($request->all()['proyecto']);
        }
		
		if(isset($request->all()['coordenadas'])){
             $datos['coordenadas']=trim($request->all()['coordenadas']);
        }
		
		if(isset($request->all()['correo'])){
             $datos['correo']=trim($request->all()['correo']);
        }
		
		if(isset($request->all()['correo2'])){
             $datos['correo2']=trim($request->all()['correo2']);
        }
		
		if(isset($request->all()['correo3'])){
             $datos['correo3']=trim($request->all()['correo3']);
        }
		
		if(isset($request->all()['obs_proyecto'])){
             $datos['obs_proyecto']=trim($request->all()['obs_proyecto']);
        }
		
		$datos['tipo']=1;
		
		if($id==0){
		  $res = DB::connection('sqlsrvCxParque')->table('tra_talleres_gps')->insert(array( $datos ));
		}else{
          $res = DB::connection('sqlsrvCxParque')->table('tra_talleres_gps')->where('id', $id)->update($datos);
		}
		if($res){
			$response = array('status' => 1,'statusText' => 'Exito','message' => 'Proceso finalizado satisfactoriamente. ');
        }else{
			$response = array('status' => 0,'statusText' => 'Error','message' => 'Ocurrió un error, por favor inténtalo nuevamente más tarde. ');
		}
		
		return json_encode($response);
		//return response()->json($response)->withCallback($request->input('callback'));
    }

    
	public function listar(){
		
        $proyectos = DB::connection('sqlsrvCxParque')
                ->table('tra_contratantes')
                ->orderBy('nombre')
               // ->where('placa',$placa)
                ->get();        
        return view('proyectos.transporte.proveedores.indexProveedores',array("proyectos"=>$proyectos));
	}
	 
	 
	public function editar($inci = 0)
    {
		$proyectos = DB::connection('sqlsrvCxParque')
                ->table('tra_contratantes')
                ->orderBy('nombre')
               // ->where('placa',$placa)
                ->get();		
				
			
		$talleres = DB::connection('sqlsrvCxParque')
                ->table('tra_talleres_gps')
                ->orderBy('id')
                ->where('id',$inci )
                ->get()[0];
				
        return view('proyectos.transporte.proveedores.editarProveedores',array("proyectos"=>$proyectos,"talleres"=>$talleres));
    }
	 
    
    public function eliminar($inci = 0)
    {
        DB::connection('sqlsrvCxParque')->table('tra_talleres_gps')->where('id', $inci)->delete();
		
        return Redirect::to('transversal/transporte/listaProveedores');
    }
}
