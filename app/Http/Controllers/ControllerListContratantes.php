<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;


use Redirect;//para redirigir pagina
use DB;//para usar BD
use Session;//Para las sesiones
use Response;


class ControllerListContratantes extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $contratantes = DB::connection('sqlsrvCxParque')
                ->table('tra_contratantes')
                ->orderBy('id')
                ->get();        
                
        return view('proyectos.transporte.contratantes.listarContratantes',array("contratantes"=>$contratantes));

		
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
        
            
        if(isset($request->all()['ceco'])){
             $datos['codigo_pep']=trim($request->all()['ceco']);
        }

         if(isset($request->all()['grupo_compras'])){
             $datos['grupo_compras']=trim($request->all()['grupo_compras']);
        }
  
       // $datos['tipo']=1;
        
        if($id==0){
          $res = DB::connection('sqlsrvCxParque')->table('tra_contratantes')->insert(array( $datos ));
        }else{
          $res = DB::connection('sqlsrvCxParque')->table('tra_contratantes')->where('id', $id)->update($datos);
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
                ->table('tra_cecos')
                ->orderBy('nombre')
               // ->where('placa',$placa)
                ->get();        
        return view('proyectos.transporte.contratantes.indexContratantes',array("proyectos"=>$proyectos));
	}
	 
	 
	public function editar($inci = 0)
    {
        $proyectos = DB::table('elementos_pep')
                ->orderBy('codigo_pep')
                ->get();
                	
        $grupos = DB::connection('sqlsrvCxParque')
                ->table('tra_grupo_compras')
                ->orderBy('grupo_compras')
               // ->where('placa',$placa)
                ->get();

             	
				
			
		$contratantes = DB::connection('sqlsrvCxParque')
                ->table('tra_contratantes')
                ->orderBy('id')
                ->where('id',$inci )
                ->get()[0];
				
        return view('proyectos.transporte.contratantes.editarContratantes',array("proyectos"=>$proyectos,"grupos"=>$grupos,"contratantes"=>$contratantes));
    }
	 
    
    public function eliminar($inci = 0)
    {
        DB::connection('sqlsrvCxParque')->table('tra_contratantes')->where('id', $inci)->delete();
		
        return Redirect::to('transversal/transporte/listaContratantes');
    }
}
