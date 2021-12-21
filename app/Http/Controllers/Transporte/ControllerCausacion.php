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

class ControllerCausacion extends Controller
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


        $causacion = [];
        if(Session::has('fecha_inicio'))
        {
            
            $fecha1 = explode("/",Session::get('fecha_inicio'))[2] . "-" . explode("/",Session::get('fecha_inicio'))[1] . "-" . explode("/",Session::get('fecha_inicio'))[0];
            $fecha2 = explode("/",Session::get('fecha_corte'))[2] . "-" . explode("/",Session::get('fecha_corte'))[1] . "-" . explode("/",Session::get('fecha_corte'))[0];

            $causacion = DB::connection('sqlsrvCxParque')
                        ->table('cost_causacion as tbl1')
                        ->join('tra_contratantes as tbl2','tbl1.id_contratante','=','tbl2.id')
                        ->join('cost_conceptos as tbl5','tbl1.id_concepto','=','tbl5.id')
                        ->join('tra_talleres_gps as tbl3','tbl1.id_proveedor','=','tbl3.id')
                        ->select('tbl1.placa','tbl1.doc_referencia','tbl1.fecha','tbl1.observaciones'
                            ,'tbl1.valor','tbl1.recepcion','tbl1.id_proveedor','tbl1.aprovisionado',
                            'tbl1.id_contratante','tbl1.id_concepto','tbl2.nombre as contra',
                            'nombre_proveedor','tbl5.nombre as conc');  

            if(Session::get('txtfilternombre') != "")
                $causacion = $causacion->where('tbl1.placa','LIKE','%' . Session::get('txtfilternombre') . '%'); 
            
            if(Session::get('selConceptoFilter') != "")
                $causacion = $causacion->where('tbl1.id_proveedor',Session::get('selConceptoFilter')); 

            if(Session::get('selProveedorFilter') != "")
                $causacion = $causacion->where('tbl1.id_concepto', Session::get('selProveedorFilter')); 

            if(Session::get('selContraFilter') != "")
                $causacion = $causacion->where('tbl1.id_contratante', Session::get('selContraFilter')); 

            if(Session::get('txtfilternombre') == "")
              $causacion = $causacion->whereBetween('fecha_sistema',[$fecha1 . " 00:00:00", $fecha2 . " 23:59:59"]);

            $causacion = $causacion->get();

        }

        if(Session::has('maxIDConcepto'))
        {
            $causacion = DB::connection('sqlsrvCxParque')
                        ->table('cost_causacion as tbl1')
                        ->join('tra_contratantes as tbl2','tbl1.id_contratante','=','tbl2.id')
                        ->join('cost_conceptos as tbl5','tbl1.id_concepto','=','tbl5.id')
                        ->join('tra_talleres_gps as tbl3','tbl1.id_proveedor','=','tbl3.id')
                        ->where('tbl1.id',Session::get('maxIDConcepto'))
                        ->get(['tbl1.placa','tbl1.doc_referencia','tbl1.fecha','tbl1.observaciones'
                            ,'tbl1.valor','tbl1.recepcion','tbl1.id_proveedor','tbl1.aprovisionado',
                            'tbl1.id_contratante','tbl1.id_concepto','tbl2.nombre as contra',
                            'nombre_proveedor','tbl5.nombre as conc']);  
        }
        
        $contratantes = DB::connection('sqlsrvCxParque')
                            ->table('tra_contratantes')
                            ->orderBy('nombre')
                            ->lists('nombre','id');/*option , value*/
        

        $proveedor = DB::connection('sqlsrvCxParque')
                            ->table('tra_talleres_gps')
                            ->orderBy('nombre_proveedor')
                            ->lists('nombre_proveedor','id');/*option , value*/

        $conceptos = DB::connection('sqlsrvCxParque')
                            ->table('cost_conceptos')
                            ->orderBy('nombre')
                            ->lists('nombre','id');/*option , value*/


        return view('proyectos.transporte.costos.causacion.index',array(
            'fecha1' => $fechaActual,
            'fecha2' => $nuevafecha,
            'causacion' => $causacion,
            'contratantes' => $contratantes,
            'proveedor' => $proveedor,
            'conceptos' => $conceptos
            ));
    }

    public function filter(Request $request)
    {
        Session::flash('fecha_inicio', $request->all()["fecha_inicio"]);
        Session::flash('fecha_corte', $request->all()["fecha_corte"]);
        Session::flash('txtfilternombre', $request->all()["txtfilternombre"]);
        Session::flash('selConceptoFilter', $request->all()["selConceptoFilter"]);
        Session::flash('selProveedorFilter', $request->all()["selProveedorFilter"]);
        Session::flash('selContraFilter', $request->all()["selContraFilter"]);

        return Redirect::to('transporte/costos/causacion');
    }

 


    //--- WServices Ajax
    public function WServicesAjax(Request $request)
    {
        $opc = $request->all()["opc"];

        if($opc == "1") //Save Causación
        {
            $placa = $request->all()["placa"];
            $des = $request->all()["des"];
            $fecha = $request->all()["fecha"];
            $obser = $request->all()["obser"];
            $valor = $request->all()["valor"];
            $recepcion = $request->all()["recepcion"];
            $apro = $request->all()["apro"];
            $concepto = $request->all()["concepto"];
            $prov = $request->all()["prov"];
            $contr = $request->all()["contr"];

            $fecha = explode("/",$fecha)[2] . "-" . explode("/",$fecha)[1] . "-" . explode("/",$fecha)[0];

            DB::connection('sqlsrvCxParque')
            ->table('cost_causacion')
            ->insert(array(
                array(
                    'placa' => $placa,
                    'doc_referencia' => $des,
                    'fecha' => $fecha,
                    'observaciones' => $obser,
                    'valor' => $valor,
                    'recepcion' => $recepcion,
                    'id_proveedor' => $prov,
                    'aprovisionado' => $apro,
                    'id_contratante' => $contr,
                    'id_concepto' => $concepto,
                    'usuario_crea' => Session::get('user_login')
                )
            ));


            $maxId = DB::connection('sqlsrvCxParque')
                        ->table('cost_causacion')
                        ->get([DB::raw("MAX(id) as id")])[0]->id;

            Session::flash('maxIDConcepto',$maxId);
            return response()->json("1");

        }
    }
}
