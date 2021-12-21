<?php

namespace App\Http\Controllers\Transporte;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;
use DB;
use Carbon\Carbon;
use Redirect;


class ControllerCombustible extends Controller
{
    /*************************************************/
    /******** FUNCIONES DEL CONTROLADOR **************/
    function __construct() {
        
       // Session::put('user_login',"U01853"); //Aleja
        Session::put('proy_short',"home");
        $this->fechaA = Carbon::now('America/Bogota');
        $this->fechaALong = $this->fechaA->toDateTimeString();
        $this->fechaShort = $this->fechaA->format('Y-m-d');
    }

    /******** FIN FUNCIONES DEL CONTROLADOR **************/
    /*************************************************/
    

    /************************************************************/
    /******** FUNCIONES DEL SUB MÓDULO DE COMBUSTIBLE **************/
    //Muestra la pantalla principal de combustibles
    public function index()
    {

    	$fechaActual = explode("-",$this->fechaShort);
        $fechaActual = $fechaActual[2] . "/" .  $fechaActual[1] . "/" .  $fechaActual[0];

        $nuevafecha = strtotime ( '-1 month' , strtotime ( $this->fechaShort ) ) ;
        $nuevafecha = date ( 'Y-m-j' , $nuevafecha );

        $nuevafecha = explode("-",$nuevafecha);
        $nuevafecha = $nuevafecha[2] . "/" .  $nuevafecha[1] . "/" .  $nuevafecha[0];

        $data = [];

        if(Session::has('txtPlacaCombustible'))
    	{
    		$fecha1 = explode("/",Session::get('fecha_inicio_combustible'))[2] . "-" . explode("/",Session::get('fecha_inicio_combustible'))[1] . "-" . explode("/",Session::get('fecha_inicio_combustible'))[0];
            $fecha2 = explode("/",Session::get('fecha_corte_combustible'))[2] . "-" . explode("/",Session::get('fecha_corte_combustible'))[1] . "-" . explode("/",Session::get('fecha_corte_combustible'))[0];

            $consulta = "select [comb].[fecha], [comb].[hora], [comb].[placa], [comb].[volumen], [comb].[valor_facturado], [pry].[nombre] 
                from [tra_combustible_importe] as [comb] 
                inner join [tra_maestro] as [veh] on [veh].[placa] = [comb].[placa] 

                INNER JOIN tra_vehiculo_ubicacion_proyecto AS ubicacion ON ubicacion.placa = comb.placa
                        AND 
                        convert(nvarchar(4),ubicacion.anio) + '-' +  IIF(LEN(convert(nvarchar(4),ubicacion.mes)) = 1,'0' + convert(nvarchar(4),ubicacion.mes),convert(nvarchar(4),ubicacion.mes)) + '-' +  IIF(LEN(convert(nvarchar(4),ubicacion.dia)) = 1,'0' + convert(nvarchar(4),ubicacion.dia),convert(nvarchar(4),ubicacion.dia))  = comb.fecha
                left join [tra_contratantes] as [pry] on [pry].[id] = [ubicacion].[id_proyecto] 

                where [comb].[fecha] between '$fecha1 00:00:00' and '$fecha2 23:59:59'
                    AND ubicacion.fecha_servidor BETWEEN  '$fecha1 00:00:00' AND'$fecha2 23:59:59'
                and [comb].[placa] LIKE '%%' order by [fecha] asc, [hora] asc";


    		$data = DB::connection('sqlsrvCxParque')->select($consulta);

    	}

    	return view("proyectos.transporte.costos.combustible.index",
    		array(
    			'data' => $data,
    			'fecha1' => $fechaActual,
            	'fecha2' => $nuevafecha
    		));
    }

    //Filtro para mostrar la pantalla principal de combustibles
    public function filterCombustible(Request $request)
    {
    	Session::flash('txtPlacaCombustible', $request->all()["txtPlacaCombustible"]);
        Session::flash('fecha_inicio_combustible', $request->all()["fecha_inicio"]);
        Session::flash('fecha_corte_combustible', $request->all()["fecha_corte"]);
        
        return Redirect::to('transporte/costos/combustible');
    }

    /******** FIN FUNCIONES DEL SUB MÓDULO DE COMBUSTIBLE **************/
    /************************************************************/
    
    
    /***********************************************/
    /******** FUNCIONES CARGA MASIVA **************/

    //Función encargada de realiza el cargue del masivo del combustible
    public function cargarCombustibles(Request $request)
    {
    	//obtenemos el campo file definido en el formulario
        $file = $request->file('file_upload');
       
        //Varificamos que carge un .xlsx
        $mime = $file->getMimeType();
           if($mime != "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")
           {
                Session::flash('dataExcel1',".");
                Session::flash('dataExcel2',"Tipo de archivo invalido, tiene que carga un archivo .xlsx");
                return Redirect::to('/redes/ordenes/ver');
           }

        //obtenemos el nombre del archivo
        $nombre = $file->getClientOriginalName();

        //indicamos que queremos guardar un nuevo archivo en el disco local
        \Storage::disk('local')->put($nombre,  \File::get($file));
        $ruta = "storage/app" . "/" . $nombre;


        set_time_limit(0);

        $placaQueNoExisten = "";

        $result = \Excel::load($ruta, function($reader) use ($request,$placaQueNoExisten) {

            $results = $reader->toArray();

            if(isset($results[0]["fecha"])  == false||
                    isset($results[0]["hora"])  == false||
                    isset($results[0]["placa"])  == false||
                    isset($results[0]["volumen"])  == false ||
                    isset($results[0]["valor_factura"])  == false) 
            {
                Session::flash('dataExcel2',"El archivo que esta tratando de cargar para el combustible no es válido");                  
            }
            else
            {
	            for ($i=0; $i < count($results); $i++) 
	            {
	            	$fecha = explode(" ",$results[$i]["fecha"])[0];
	            	$hora = $results[$i]["hora"];
	            	$placa = strToUpper($results[$i]["placa"]);
	            	$volumen = $results[$i]["volumen"];
	            	$valor_factura = $results[$i]["valor_factura"];


	            	if(DB::connection('sqlsrvCxParque')
				    		->table('tra_maestro')
				    		->where('placa',$placa)
				    		->count() == 0)
	            	{	
	            		$placaQueNoExisten .= " - La placa $placa no existe en CAMPRO";
	            		continue;
	            	}


	            	$contRegistro = DB::connection('sqlsrvCxParque')
				    				->table('tra_combustible_importe')
				    				->where('fecha',$fecha)
				    				->where('hora',$hora)
				    				->where('placa',$placa)
				    				->count();

				    if($contRegistro == 0) //Ingresar registro
				    {
				    	DB::connection('sqlsrvCxParque')
				    				->table('tra_combustible_importe')
				    				->insert(
				    					array(
				    							array(
				    								'fecha' => $fecha,
				    								'hora' => $hora,
				    								'placa' => $placa,
				    								'volumen' => $volumen,
				    								'valor_facturado' => $valor_factura,
				    								'fecha_inserta' => $this->fechaALong,
				    								'usuario_inserta' => Session::get('user_login')
				    							)
				    						)
				    				);
				    }	
				    else //Actualizar registro
				    {
				    	DB::connection('sqlsrvCxParque')
				    				->table('tra_combustible_importe')
				    				->where('fecha',$fecha)
				    				->where('hora',$hora)
				    				->where('placa',$placa)
				    				->update(
				    					array(
				    						'volumen' => $volumen,
				    						'valor_facturado' => $valor_factura,
				    						'fecha_actualiza' => $this->fechaALong,
				    						'usuario_actualiza' => Session::get('user_login')
				    					)
				    				);
				    }


	            }
            }             

            if($placaQueNoExisten != "")
        		Session::flash('dataExcel2',"Se presentaron las siguientes inconsistencias:" . $placaQueNoExisten);

            var_dump("Se esta cargando el documento en excel, espero un momento");

        })->get();
        
        
        
			Session::flash('dataExcel1',"Se ha cargado correctamente el archivo de combustibles.");

       return Redirect::to('/transporte/costos/combustible');
    }

    /******** FIN FUNCIONES CARGA MASIVA **************/
    /***********************************************/
    

    /***********************************************/
    /******** FUNCIONES EXPORTAR **************/
    //Exportar masivo de combustibles
    public function exporteCombustibles(Request $request)
    {
    	$fecha1 = explode("/",$request->all()['fecha_exporte_1'])[2] . "-" . explode("/",$request->all()['fecha_exporte_1'])[1] . "-" . explode("/",$request->all()['fecha_exporte_1'])[0];
            $fecha2 = explode("/",$request->all()['fecha_exporte_2'])[2] . "-" . explode("/",$request->all()['fecha_exporte_2'])[1] . "-" . explode("/",$request->all()['fecha_exporte_2'])[0];

    	
    	$cad = "EXEC sp_tra_consulta_combustible '" . $fecha1
                     . "','" . $fecha2 . "'";

        $arr = DB::connection('sqlsrvCxParque')->select("SET NOCOUNT ON;" . $cad);


        \Excel::create('Exporte de Combustible' . $this->fechaALong, function($excel) use($arr) {            

                $excel->sheet('Exporte', function($sheet) use($arr){
                    
                    $sheet->setColumnFormat(array(
                        'G' => '0,00'
                    ));

                    $fecha = explode("-",$this->fechaShort)[2] . "/" . explode("-",$this->fechaShort)[1] . "/" . explode("-",$this->fechaShort)[0] . " 0:00";

                    $usuario_login = DB::Table('sis_usuarios')
                    					->where('id_usuario',Session::get('user_login'))
                    					->value('propietario');

                    for ($i=0; $i < count($arr); $i++) 
                    {    
                            $sheet->row($i +1, array(
                                1,
                                'TAB',
                                'CCM',
                                'TAB',
                                'CAMCO PRINCIPAL',
                                'TAB',
                                $arr[$i]->valor,
                                'TAB',
                                $fecha,
                                'TAB',
                                $fecha,
                                'TAB',
                                $arr[$i]->proyecto,
                                '*AD',
                                "7495350001",
                                "ENT",
                                "TAB",
                                "TAB",
                                str_replace(" ","%",str_replace("  "," ",$usuario_login)),
                                "TAB",
                                "CAMCO%PRIN",
                                "TAB",
                                "TAB",
                                "90001.90001.7495350001." . $arr[$i]->ccosto,
                                "*PB",
                                "*IR",
                                "TAB"
                            ));                        
                    }

                });
        })->export('xls');
    }

    /******** FIN FUNCIONES EXPORTAR **************/
    /***********************************************/
}
