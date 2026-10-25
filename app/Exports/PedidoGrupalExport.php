<?php

namespace App\Exports;

use App\Pedido;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class PedidoGrupalExport implements FromView
{
	protected $fechainicio;
	protected $fechafin;
    protected $solicitante;
    protected $lugar;
    protected $servicio;
    protected $carnico;
    protected $empresa;
    protected $concesionaria_id;

    public function __construct($fechainicio,$fechafin,$solicitante,$lugar,$servicio,$carnico,$empresa,$concesionaria_id)
    {
        $this->fechainicio = $fechainicio;
        $this->fechafin = $fechafin;
        $this->solicitante = $solicitante;
        $this->lugar = $lugar;
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
        $servicio = $this->servicio;
        $carnico = $this->carnico;
        $empresa = $this->empresa;
        $concesionaria_id = $this->concesionaria_id;
    	$resultado        = Pedido::join('detallepedidogrupal','detallepedidogrupal.pedido_id','=','pedido.id')
                            ->join('servicio','servicio.id','=','detallepedidogrupal.servicio_id')
                            ->join('carnico','carnico.id','=','detallepedidogrupal.carnico_id')
                            ->whereNull('detallepedidogrupal.deleted_at')
                            ->orderBy('pedido.fecha','desc')
                            ->orderBy('pedido.id','desc')
                            ->select('pedido.*','servicio.nombre as servicio','carnico.nombre as carnico','detallepedidogrupal.dieta','detallepedidogrupal.empresa','detallepedidogrupal.cantidad','detallepedidogrupal.id as detallepedido_id');
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
        if($servicio!="0"){
            if($servicio!="-1"){
                $resultado = $resultado->where('detallepedidogrupal.servicio_id','=',$servicio);
            }else{
                $resultado = $resultado->whereIn('detallepedidogrupal.servicio_id',[4,7,8]);
            }
        }
        if($carnico!="0"){
            $resultado = $resultado->where('detallepedidogrupal.carnico_id','=',$carnico);
        }
        if($empresa!=""){
            $resultado = $resultado->where('detallepedidogrupal.empresa','like',DB::raw("'%".$empresa."%'"));
        }
        if($concesionaria_id!="0"){
            $resultado = $resultado->where('pedido.concesionaria_id','=',$concesionaria_id);
        }
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => 'Fecha', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Solicitante', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Lugar', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Observacion', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Cantidad', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Servicio', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Op. Carnico', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Empresa', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Fecha Reg.', 'numero' => '1');
        return view('exports.pedidogrupal')->with(compact('lista','fechainicio','fechafin','cabecera'));
    }

   
}
