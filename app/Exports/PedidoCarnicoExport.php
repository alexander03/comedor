<?php

namespace App\Exports;

use App\PedidoCarnico;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class PedidoCarnicoExport implements FromView
{
	protected $fechainicio;
	protected $fechafin;
    protected $solicitante;
    protected $lugar;
    protected $persona;
    protected $servicio;
    protected $carnico;
    protected $empresa;
    protected $concesionaria_id;

    public function __construct($fechainicio,$fechafin,$solicitante,$lugar,$persona,$servicio,$carnico,$empresa,$concesionaria_id)
    {
        $this->fechainicio = $fechainicio;
        $this->fechafin = $fechafin;
        $this->solicitante = $solicitante;
        $this->lugar = $lugar;
        $this->persona = $persona;
        $this->servicio = $servicio;
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
        $servicio = $this->servicio;
        $carnico = $this->carnico;
        $empresa = $this->empresa;
        $concesionaria_id = $this->concesionaria_id;
    	$resultado        = PedidoCarnico::join('detallepedidocarnico','detallepedidocarnico.pedidocarnico_id','=','pedidocarnico.id')
                            ->join('servicio','servicio.id','=','detallepedidocarnico.servicio_id')
                            ->join('carnico','carnico.id','=','detallepedidocarnico.carnico_id')
                            ->whereNull('detallepedidocarnico.deleted_at')
                            ->orderBy('pedidocarnico.fecha','desc')
                            ->orderBy('pedidocarnico.id','desc')
                            ->select('pedidocarnico.*','servicio.nombre as servicio','carnico.nombre as carnico','detallepedidocarnico.dieta','detallepedidocarnico.empresa','detallepedidocarnico.persona','detallepedidocarnico.id as detallepedido_id');
        if($fechainicio!=""){
            $resultado = $resultado->where(DB::raw('date(pedidocarnico.fecha)'),'>=',DB::raw("'".$fechainicio."'"));
        }
        if($fechafin!=""){
            $resultado = $resultado->where(DB::raw('date(pedidocarnico.fecha)'),'<=',DB::raw("'".$fechafin."'"));
        }
        if($solicitante!=""){
            $resultado = $resultado->where('pedidocarnico.solicitante','like',DB::raw("'%".$solicitante."%'"));
        }
        if($lugar!="0"){
            $resultado = $resultado->where('pedidocarnico.lugar_id','=',$lugar);
        }
        if($persona!=""){
            $resultado = $resultado->where('detallepedidocarnico.persona','like',DB::raw("'%".$persona."%'"));
        }
        if($servicio!="0"){
            $resultado = $resultado->where('detallepedidocarnico.servicio_id','=',$servicio);
        }
        if($carnico!="0"){
            $resultado = $resultado->where('detallepedidocarnico.carnico_id','=',$carnico);
        }
        if($empresa!=""){
            $resultado = $resultado->where('detallepedidocarnico.empresa','like',DB::raw("'%".$empresa."%'"));
        }
        if($concesionaria_id!="0"){
            $resultado = $resultado->where('pedidocarnico.concesionaria_id','=',$concesionaria_id);
        }
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'Fecha', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Solicitante', 'numero' => '1');
        //$cabecera[]       = array('valor' => 'Lider', 'numero' => '1');
        //$cabecera[]       = array('valor' => 'Lugar', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Observacion', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Persona', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Servicio', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Op. Carnico', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Empresa', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Fecha Reg.', 'numero' => '1');
        return view('exports.pedidocarnico')->with(compact('lista','fechainicio','fechafin','cabecera'));
    }

   
}
