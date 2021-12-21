<?php

namespace App\Http\Controllers\Transporte;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Redirect;//para redirigir pagina
use DB;//para usar BD
use Session;//Para las sesiones
use Carbon\Carbon;
use Response;

class ControllerConceptos extends Controller
{

    function __construct() {
        /*$this->tblAux = "rds_gop_";
        $this->tblAux1 = "rds_";
        Session::put('proy_short',$this->tblAux1);*/
        //Session::put('user_login',"U01853");
        Session::put('proy_short',"home");
        $this->fechaA = Carbon::now('America/Bogota');
        $this->fechaALong = $this->fechaA->toDateTimeString();
        $this->fechaShort = $this->fechaA->format('Y-m-d');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $fechaActual = explode("-",$this->fechaShort);
        $fechaActual = $fechaActual[2] . "/" .  $fechaActual[1] . "/" .  $fechaActual[0];

        $nuevafecha = strtotime ( '-1 month' , strtotime ( $this->fechaShort ) ) ;
        $nuevafecha = date ( 'Y-m-j' , $nuevafecha );

        $nuevafecha = explode("-",$nuevafecha);
        $nuevafecha = $nuevafecha[2] . "/" .  $nuevafecha[1] . "/" .  $nuevafecha[0];


        $conceptos = [];
        if(Session::has('fecha_inicio'))
        {
            
            $fecha1 = explode("/",Session::get('fecha_inicio'))[2] . "-" . explode("/",Session::get('fecha_inicio'))[1] . "-" . explode("/",Session::get('fecha_inicio'))[0];
            $fecha2 = explode("/",Session::get('fecha_corte'))[2] . "-" . explode("/",Session::get('fecha_corte'))[1] . "-" . explode("/",Session::get('fecha_corte'))[0];

            $conceptos = DB::connection('sqlsrvCxParque')
                        ->table('cost_conceptos')
                        ->select('nombre','descripcion','id_estado','id','anexo_obligatorio');  

            if(Session::get('txtfilternombre') != "")
                $conceptos = $conceptos->where('nombre','LIKE','%' . Session::get('txtfilternombre') . '%'); 
            
            if(Session::get('selEstado') != "")
                $conceptos = $conceptos->where('id_estado','LIKE','%' . Session::get('selEstado') . '%'); 

            if(Session::get('txtfilternombre') == "")
                $conceptos = $conceptos->whereBetween('fecha_servidor',[$fecha1 . " 00:00:00", $fecha2 . " 23:59:59"]);
            

            $conceptos = $conceptos->get();
        }

        if(Session::has('maxIDConcepto'))
        {
            $conceptos = DB::connection('sqlsrvCxParque')
                        ->table('cost_conceptos')
                        ->where('id',Session::get('maxIDConcepto'))
                        ->get(['nombre','descripcion','id_estado','id','anexo_obligatorio']);  
        }
        

        return view('proyectos.transporte.costos.conceptos.index',array(
            'fecha1' => $fechaActual,
            'fecha2' => $nuevafecha,
            'conceptos' => $conceptos
            ));
    }

    public function filter(Request $request)
    {
        Session::flash('fecha_inicio', $request->all()["fecha_inicio"]);
        Session::flash('fecha_corte', $request->all()["fecha_corte"]);
        Session::flash('txtfilternombre', $request->all()["txtfilternombre"]);
        Session::flash('selEstado', $request->all()["selEstado"]);
        
        return Redirect::to('transporte/costos/conceptos');
    }

    public function delete(Request $request)
    {
        $id = $request->all()["id"];

        DB::connection('sqlsrvCxParque')
            ->table('cost_conceptos')
            ->where('id',$id)
            ->delete();

        Session::flash('dataExcel1',"Se ha eliminado correctamente el concepto");


        return Redirect::to('transporte/costos/conceptos');   
    }


    public function saveConcepto(Request $request)
    {
        $nombre = $request->all()["txtNombreConcepto"];
        $des = $request->all()["txtDesConcepto"];
        $esta = $request->all()["selEstado"];
        $id = $request->all()["id"];

        if(isset($request->all()["file_archiv_impor"]))
        {
            $file = $request->all()["file_archiv_impor"];
            $mime = $file->getMimeType();
            $name = $file->getClientOriginalName();

            //obtenemos el nombre del archivo
            $nombreA = "Transporte_Costos_" . $name ;
            self::envioArchivos(\File::get($file),$nombreA,"/anexos_apa/costos");
            $nombreA  = "/anexos_apa/costos/" . $nombreA;
        }   
        

        if($id != "")
        {
            if(isset($request->all()["file_archiv_impor"]))
            {   
                DB::connection('sqlsrvCxParque')
                    ->table('cost_conceptos')
                    ->where('id',$id)
                        ->update(array(
                                'nombre' => $nombre,
                                'descripcion' => $des,
                                'id_estado' => $esta,
                                'anexo_obligatorio' => $nombreA
                        ));
            }
            else
            {
                DB::connection('sqlsrvCxParque')
                    ->table('cost_conceptos')
                    ->where('id',$id)
                        ->update(array(
                                'nombre' => $nombre,
                                'descripcion' => $des,
                                'id_estado' => $esta
                        ));
            }
            

            $maxId = $id;
        }
        else
        {

            DB::connection('sqlsrvCxParque')
            ->table('cost_conceptos')
            ->insert(array(
                array(
                    'nombre' => $nombre,
                    'descripcion' => $des,
                    'id_estado' => $esta,
                    'anexo_obligatorio' => $nombreA
                )
            ));

            $maxId = DB::connection('sqlsrvCxParque')
                        ->table('cost_conceptos')
                        ->get([DB::raw("MAX(id) as id")])[0]->id;
        }
        


        Session::flash('maxIDConcepto',$maxId);
        Session::flash('dataExcel1',"Se ha guardado correctamente el concepto");
        return Redirect::to('transporte/costos/conceptos');

    }


    private function envioArchivos($archivo,$nombreArchivo,$carpeta)
    {
        $id_ftp=ftp_connect("190.60.248.195",21); //Obtiene un manejador del Servidor FTP
        ftp_login($id_ftp,"usuario_ftp","74091450652!@#1723cc"); //Se loguea al Servidor FTP
        ftp_pasv($id_ftp,true); //Se coloca el modo pasivo
        ftp_chdir($id_ftp, $carpeta); // Nos dirigimos a la carpeta de destino
        $Directorio=ftp_pwd($id_ftp);
        $Directorio2=$Directorio;        
        $res = 0;
        try
        {
            $fileL = storage_path('app') . "/" .  $nombreArchivo;
            \Storage::disk('local')->put($nombreArchivo,  $archivo);
            //Enviamos la imagen al servidor FTP
            $exi = ftp_put($id_ftp,$Directorio . "/" . $nombreArchivo,$fileL,FTP_BINARY); 
            //Cuando se envia el archivo, se elimina el archivo

            if(file_exists($fileL))
                unlink($fileL);

            $res = 1;
        }catch(Exception $e)
        {
            $res = $e;
        }
        return $res;
    }


}
