<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Carbon\Carbon;

class CierreEntragaOperacion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tra:cierreentregaop';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

        $entregaOP = DB::connection('sqlsrvCxParque')
            ->table('tra_incidencia')
            ->whereNotNull('tecnico_entrega')
            ->whereNull('conductor_entrega')
            ->get(['fecha_entrega','incidencia','tecnico_entrega']);

        $fechaFin = date_create($fechaA->toDateTimeString());
        
        $aux = 0;
        foreach ($entregaOP as $key => $value) {
            $aux++;
            $datetime1 = date_create($value->fecha_entrega);  
            $interval = date_diff($datetime1, $fechaFin);
            $diasPasdos = intval($interval->format('%a'));
            if($diasPasdos >= 1)
            {
                $user = DB::Table('rh_personas')
                        ->where('identificacion',$value->tecnico_entrega)
                        ->get(['nombres','apellidos']);

                $nombreTecnico = "";
                if(count($user) > 0)
                    $nombreTecnico =  $user[0]->nombres . " " . $user[0]->apellidos;


                DB::connection('sqlsrvCxParque')
                ->table('tra_incidencia')
                ->where('incidencia',$value->incidencia)
                ->update(
                        array(
                                'conductor_entrega' => 'SISTEMA - ' . $nombreTecnico,
                                'fecha_verificaciones' => $fechaA->toDateTimeString(),
                                'tipo_cierre' => 2,
                                'id_estado' => 'E06',
                                'fecha_cierre' => $fechaA->toDateTimeString(),
                                'usuario_cierre' => $value->tecnico_entrega 
                            )
                    );

                //Agrega acción
                DB::connection('sqlsrvCxParque')
                        ->table('tra_incidencia_accion')
                        ->insert(array(
                            array(
                                'incidencia' => $value->incidencia,
                                'usuario' => $value->tecnico_entrega ,
                                'fecha_servidor' => $fechaA->toDateTimeString(),
                                'observacion' => "El SISTEMA cierra la incidencia.",
                                'tipo' => 3
                                )
                            ));

              //  var_dump($value);
            }

            //if($aux == 2)
                //break;
        }        
    }
}
