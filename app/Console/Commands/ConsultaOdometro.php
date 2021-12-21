<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Carbon\Carbon;
class ConsultaOdometro extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'con:ultrack';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ejecutando consulta de Odometro Ultrack';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $fechaA = Carbon::now(-5);
        $fechaShort = $fechaA->format('Y-m-d');

        $nuevafecha = strtotime ( '-1 day' , strtotime ($fechaShort) ) ;
        $nuevafecha = date ( 'Y-m-j' , $nuevafecha );


        try{
            $Headers = array(
                "Content-type: application/json",
                'Accept: application/json'
            );

            $url = "https://api.utracking.net/common_service/camMaintenance/odometerIService";
            //Consultando un grupo

            $matri = DB::connection('sqlsrvCxParque')
                    ->table('tra_maestro')
                    ->where('placa','<>','')
                    ->where('placa','<>',null)
                    ->where(DB::raw("LEN(placa)"),'>=',6)
                    ->select(DB::RAW('placa as matricula'))
                    ->get();

            DB::connection('sqlsrvCxParque')
            ->table('tra_vehiculo_odometro_ultrack')
            ->insert(
                    array(
                        array(
                            'des' => 'Consulta Odometro ULtrack ' . $fechaA->toDateTimeString() . " CANTIDAD DE PLACAS CONSULTADOS BD CAM: " . count($matri),
                            'fecha_consulta' => $nuevafecha
                            )
                        )
                );
            
            $arreglo = [];
            foreach ($matri as $key => $value) {
                array_push($arreglo, [
                    'plate'=>str_replace(" ","",$value->matricula)
                    ]);
            }

            $data = array(
                'date' => $nuevafecha,
                'token' => 'j98Yp@N}[Zio21-#',
                'informationCamSWDto'=>
                       $arreglo
                );
            $data = json_encode($data);
            //dd($data);


            //Inicia conexion.
            $conexion = curl_init();
            //Indica un error de conexion
            if (FALSE === $conexion)
                throw new \Exception('failed to initialize');

            //para devolver el resultado de la transferencia como string del valor de curl_exec() en lugar de mostrarlo directamente.
            curl_setopt($conexion,CURLOPT_RETURNTRANSFER,TRUE);
            //para incluir el header en el output.
            curl_setopt($conexion,CURLOPT_HEADER,0);//No muestra la cabecera en el resultado
            //Envía la cabecera
            curl_setopt($conexion, CURLOPT_HTTPHEADER, $Headers);
            //Dirección URL a capturar.
            curl_setopt($conexion,CURLOPT_URL,$url);
            //indicando que es post
            curl_setopt($conexion, CURLOPT_POST, 1);
            //Seteando los datos del webServicecs
            curl_setopt($conexion, CURLOPT_POSTFIELDS, $data);
            //Para que no genere problemas con el certificado SSL
            curl_setopt($conexion, CURLOPT_SSL_VERIFYPEER, false);
            $resultado = curl_exec($conexion);

            if (FALSE === $resultado)
                throw new \Exception(curl_error($conexion), curl_errno($conexion));

            curl_close($conexion);
            $resultado = json_decode($resultado, true);


            /*
            $usuario = $request->all()["usuario"];
            $pasword = $request->all()["pass"];
            */
            //dd($resultado);

            $respuesta = $resultado['state'];
            $arreglo = $resultado['informationCamSWDto'];
            $fecha = $resultado['date'];
            $cont = 0;

            for ($i = 0 ; $i < count($arreglo); $i++) {
                $cont++;
                //Inserta en BD
                if(intval($arreglo[$i]['odometer']) == 0)
                    continue;

                $contT = DB::connection('sqlsrvCxParque')
                    ->table('tra_maestro')
                    ->where('placa',$arreglo[$i]['plate'])
                    ->count();


                if($contT > 0)
                {
                    $odoActual = DB::connection('sqlsrvCxParque')
                        ->table('tra_vehiculo_odometro')
                        ->where('placa',$arreglo[$i]['plate'])
                        ->select('kilometraje')
                        ->orderBy('fecha','desc')
                        ->get();

                    if(count($odoActual) == 0) //Inserta Odometro normal
                    {

                        DB::connection('sqlsrvCxParque')
                        ->table('tra_vehiculo_odometro')
                        ->insert(array(
                            array(
                                'placa' => $arreglo[$i]['plate'],
                                'fecha_servidor' => $fechaA->toDateTimeString(),
                                'fecha' => $fechaShort,
                                'observaciones' => "CONSULTA ODOMETRO ULTRACK",
                                'kilometraje' => $arreglo[$i]['odometer'],
                                'usuario' => "ULTRACK",
                                'tipo' => 2
                                )
                            ));
                    }else //Aumenta Odometro
                    {
                        
                        $datO = intval($odoActual[0]->kilometraje) + intval($arreglo[$i]['odometer']);
                        DB::connection('sqlsrvCxParque')
                        ->table('tra_vehiculo_odometro')
                        ->insert(array(
                            array(
                                'placa' => $arreglo[$i]['plate'],
                                'fecha_servidor' => $fechaA->toDateTimeString(),
                                'fecha' => $fechaShort,
                                'observaciones' => "CONSULTA ODOMETRO ULTRACK",
                                'kilometraje' => $datO,
                                'usuario' => "ULTRACK",
                                'tipo' => 2
                                )
                            ));
                    }
                }

                $this->info("Placa: " . $arreglo[$i]['plate'] . " Odometro: " . $arreglo[$i]['odometer'] . " Fecha Consulta: " .$nuevafecha . " Hora de consulta: " . $fechaA->toDateTimeString());
            }

            DB::connection('sqlsrvCxParque')
            ->table('tra_vehiculo_odometro_ultrack')
            ->insert(
                    array(
                        array(
                            'des' => 'FIN Consulta Odometro ULtrack ' . $fechaA->toDateTimeString() . " CANTIDAD DE PLACAS BD ULTRACK : " . $cont,
                            'fecha_consulta' => $nuevafecha
                            )
                        )
                );

            //if($respuesta == "OK")
        
        //print_r($resultado['state']);
        } catch(\Exception $e) {
            //var_dump($e->getMessage());

            DB::connection('sqlsrvCxParque')
            ->table('tra_vehiculo_odometro_ultrack')
            ->insert(
                    array(
                        array(
                            'des' => 'ERROR Consulta Odometro ULtrack CODE:' . $e->getCode() . " MESSAGE: ".  $e->getMessage(),
                            'fecha_consulta' => $nuevafecha
                            )
                        )
                );

            trigger_error(sprintf(
                'Curl failed with error #%d: %s',
                $e->getCode(), $e->getMessage()),
                E_USER_ERROR);

            

        }

    }
}
