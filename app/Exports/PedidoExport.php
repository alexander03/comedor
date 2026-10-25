<?php

namespace App\Exports;

use App\Pedido;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class PedidoExport implements FromView
{
	protected $fechainicio;
	protected $fechafin;
    protected $solicitante;
    protected $lugar;
    protected $persona;
    protected $desayuno;
    protected $almuerzo;
    protected $cena;
    protected $refrigerio;
    protected $frio;
    protected $carnico;
    protected $empresa;
    protected $concesionaria_id;

    public function __construct($fechainicio,$fechafin,$solicitante,$lugar,$persona,$desayuno,$almuerzo,$cena,$refrigerio,$frio,$carnico,$empresa,$concesionaria_id)
    {
        $this->fechainicio = $fechainicio;
        $this->fechafin = $fechafin;
        $this->solicitante = $solicitante;
        $this->lugar = $lugar;
        $this->persona = $persona;
        $this->desayuno = $desayuno;
        $this->almuerzo = $almuerzo;
        $this->cena = $cena;
        $this->refrigerio = $refrigerio;
        $this->frio = $frio;
        $this->carnico = $carnico;
        $this->empresa = $empresa;
        $this->concesionaria_id = $concesionaria_id;
    }
    
    public function view(): View
    {
        $fechainicio = $this->fechainicio;
        $fechafin = $this->fechafin;
        $solicitante = $this->solicitante;
        $lugar = $this->lugar;
        $persona = $this->persona;
        $desayuno = $this->desayuno;
        $almuerzo = $this->almuerzo;
        $cena = $this->cena;
        $refrigerio = $this->refrigerio;
        $frio = $this->frio;
        $carnico = $this->carnico;
        $empresa = $this->empresa;
        $concesionaria_id = $this->concesionaria_id;
    	$resultado        = Pedido::join('detallepedido','detallepedido.pedido_id','=','pedido.id')
                            ->join('servicio','servicio.id','=','detallepedido.servicio_id')
                            ->join('carnico','carnico.id','=','detallepedido.carnico_id')
                            ->whereNull('detallepedido.deleted_at')
                            ->where(function($sql) use($desayuno,$almuerzo,$cena,$refrigerio,$frio){
                                if($desayuno=="0"){
                                    $sql->orWhere(function($sql2){
                                        $sql2->whereRaw('detallepedido.carnico_id = 3 and detallepedido.servicio_id=1');
                                    });
                                }elseif($desayuno!=""){
                                    $sql->orWhere(function($sql2) use($desayuno){
                                        $sql2->whereRaw('detallepedido.servicio_id = 1 and detallepedido.carnico_id = '.$desayuno);
                                    });
                                }
                                if($almuerzo=="0"){
                                    $sql->orWhere(function($sql2){
                                        $sql2->whereRaw('detallepedido.carnico_id in (3,4) and detallepedido.servicio_id=2');
                                    });
                                }elseif($almuerzo!=""){
                                    $sql->orWhere(function($sql2) use($almuerzo){
                                        $sql2->whereRaw('detallepedido.servicio_id = 2 and detallepedido.carnico_id = '.$almuerzo);
                                    });
                                }
                                if($cena=="0"){
                                    $sql->orWhere(function($sql2){
                                        $sql2->whereRaw('detallepedido.carnico_id in (3,4) and detallepedido.servicio_id=3');
                                    });
                                }elseif($cena!=""){
                                    $sql->orWhere(function($sql2) use($cena){
                                        $sql2->whereRaw('detallepedido.servicio_id = 3 and detallepedido.carnico_id = '.$cena);
                                    });
                                }
                                if($refrigerio=="0"){
                                    $sql->orWhere(function($sql2){
                                        $sql2->whereRaw('detallepedido.carnico_id in (23,24,25,26) and detallepedido.servicio_id=4');
                                    });
                                }elseif($refrigerio!=""){
                                    $sql->orWhere(function($sql2) use($refrigerio){
                                        $sql2->whereRaw('detallepedido.servicio_id = 4 and detallepedido.carnico_id = '.$refrigerio);
                                    });
                                }
                                if($frio=="0"){
                                    $sql->orWhereIn('detallepedido.carnico_id',[20,21,22]);
                                }elseif($frio!=""){
                                    $sql->orWhere('detallepedido.carnico_id','=',$frio);
                                }
                            })
                            ->orderBy('pedido.fecha','desc')
                            ->orderBy('pedido.id','desc')
                            ->select('pedido.*','servicio.nombre as servicio','carnico.nombre as carnico','detallepedido.dieta','detallepedido.empresa','detallepedido.persona','detallepedido.id as detallepedido_id','detallepedido.persona_id','detallepedido.personawp_id');
        if($fechainicio!=""){
            $resultado = $resultado->where(DB::raw('date(pedido.fecha)'),'>=',DB::raw("'".$fechainicio."'"));
        }
        if($fechafin!=""){
            $resultado = $resultado->where(DB::raw('date(pedido.fecha)'),'<=',DB::raw("'".$fechafin."'"));
        }
        if($solicitante!=""){
            $resultado = $resultado->where('pedido.solicitante','like',DB::raw("'%".$solicitante."%'"));
        }
        if($lugar!="0"){
            $resultado = $resultado->where('pedido.lugar_id','=',$lugar);
        }
        if($persona!=""){
            $resultado = $resultado->where('detallepedido.persona','like',DB::raw("'%".$persona."%'"));
        }
        if($carnico!="0"){
            $resultado = $resultado->where('detallepedido.carnico_id','=',$carnico);
        }
        if($empresa!=""){
            $resultado = $resultado->where('detallepedido.empresa','like',DB::raw("'%".$empresa."%'"));
        }
        if($concesionaria_id!="0"){
            $resultado = $resultado->where('pedido.concesionaria_id','=',$concesionaria_id);
        }
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'Fecha', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Solicitante', 'numero' => '1');
        //$cabecera[]       = array('valor' => 'Lider', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Lugar', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Observacion', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Persona', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Servicio', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Op. Carnico', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Empresa', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Fecha Reg.', 'numero' => '1');
        return view('exports.pedido')->with(compact('lista','fechainicio','fechafin','cabecera'));
    }

   
}
